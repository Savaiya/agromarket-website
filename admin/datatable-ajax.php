<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/blog-associations.php';
requireLogin();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, private');

function dataTableEscape($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function dataTableJson(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function dataTableAssociationCell(array $associations, string $label): string
{
    $names = array_values(array_filter(array_map(
        static fn($association): string => trim((string) ($association['name'] ?? '')),
        $associations
    )));
    if ($names === []) {
        return '-';
    }
    if (count($names) === 1) {
        return dataTableEscape($names[0]);
    }

    $items = '';
    foreach ($names as $name) {
        $items .= '<li><span class="dropdown-item-text">' . dataTableEscape($name) . '</span></li>';
    }
    return '<div class="dropdown"><button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">'
        . count($names) . ' ' . dataTableEscape($label) . '</button><ul class="dropdown-menu">' . $items . '</ul></div>';
}

$table = $_GET['table'] ?? '';
if (!is_string($table)) {
    dataTableJson(['error' => 'Invalid table.'], 400);
}

$draw = max(0, (int) ($_GET['draw'] ?? 0));
$start = max(0, min(1000000, (int) ($_GET['start'] ?? 0)));
$length = (int) ($_GET['length'] ?? 10);
if ($length < 1 || $length > 100) {
    $length = 10;
}
$searchValue = $_GET['search']['value'] ?? '';
$searchValue = is_string($searchValue) ? trim(mb_substr($searchValue, 0, 100)) : '';
$orderInput = $_GET['order'][0] ?? [];
$orderIndex = is_array($orderInput) ? (int) ($orderInput['column'] ?? 0) : 0;
$orderDirection = is_array($orderInput) && strtolower((string) ($orderInput['dir'] ?? '')) === 'asc' ? 'ASC' : 'DESC';

$configurations = [
    'blogs' => [
        'from' => 'FROM blogs b',
        'search' => [
            'b.title', 'b.slug', 'b.author',
            '(SELECT GROUP_CONCAT(c.name SEPARATOR \' \') FROM category_map cm INNER JOIN blog_categories c ON c.id = cm.category_id WHERE cm.blog_id = b.id)',
            '(SELECT GROUP_CONCAT(t.name SEPARATOR \' \') FROM tag_map tm INNER JOIN blog_tags t ON t.id = tm.tag_id WHERE tm.blog_id = b.id)',
            'b.status', 'b.short_description',
        ],
        'order' => [null, 'b.title', 'category_names', 'tag_names', 'b.status', 'b.is_featured', 'b.created_at', null],
        'select' => 'b.id, b.title, b.slug, b.author, b.category_id, b.tag, b.status, b.is_featured, b.created_at, b.featured_image, b.short_description, b.content, b.seo_title, b.seo_description, b.seo_keywords, (SELECT GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR \'||\') FROM category_map cm INNER JOIN blog_categories c ON c.id = cm.category_id WHERE cm.blog_id = b.id) AS category_names, (SELECT GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR \'||\') FROM tag_map tm INNER JOIN blog_tags t ON t.id = tm.tag_id WHERE tm.blog_id = b.id) AS tag_names',
    ],
    'leads' => [
        'from' => 'FROM leads l',
        'search' => ['l.name', 'l.email', 'l.phone', 'l.subject', 'l.message', 'l.status'],
        'order' => [null, 'l.name', 'l.email', 'l.subject', null, 'l.status', 'l.created_at', null],
        'select' => 'l.id, l.name, l.email, l.phone, l.subject, l.message, l.status, l.created_at',
    ],
    'categories' => [
        'from' => 'FROM blog_categories c',
        'search' => ['c.name', 'c.slug', 'c.description'],
        'order' => ['c.name', 'c.slug', null, null],
        'select' => 'c.id, c.name, c.slug, c.description, (SELECT COUNT(*) FROM category_map cm WHERE cm.category_id = c.id) AS post_count',
    ],
    'tags' => [
        'from' => 'FROM blog_tags t',
        'search' => ['t.name', 't.slug', 't.description'],
        'order' => ['t.name', 't.slug', null, null],
        'select' => 't.id, t.name, t.slug, t.description, (SELECT COUNT(*) FROM tag_map tm WHERE tm.tag_id = t.id) AS post_count',
    ],
    'dashboard-leads' => [
        'from' => 'FROM leads l',
        'search' => ['l.name', 'l.email', 'l.status'],
        'order' => ['l.name', 'l.email', 'l.status', 'l.created_at'],
        'select' => 'l.id, l.name, l.email, l.status, l.created_at',
    ],
    'dashboard-blogs' => [
        'from' => 'FROM blogs b',
        'search' => ['b.title', '(SELECT GROUP_CONCAT(c.name SEPARATOR \' \') FROM category_map cm INNER JOIN blog_categories c ON c.id = cm.category_id WHERE cm.blog_id = b.id)', 'b.status'],
        'order' => ['b.title', 'category_names', 'b.status', 'b.created_at'],
        'select' => 'b.id, b.title, b.category_id, b.status, b.created_at, (SELECT GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR \'||\') FROM category_map cm INNER JOIN blog_categories c ON c.id = cm.category_id WHERE cm.blog_id = b.id) AS category_names',
    ],
];

if (!isset($configurations[$table])) {
    dataTableJson(['error' => 'Unknown table.'], 400);
}

try {
    $db = getDB();
    $configuration = $configurations[$table];
    $where = '';
    $searchParameters = [];
    if ($searchValue !== '') {
        $conditions = [];
        foreach ($configuration['search'] as $index => $column) {
            $parameterName = 'search_' . $index;
            $conditions[] = $column . ' LIKE :' . $parameterName;
            $searchParameters[$parameterName] = '%' . $searchValue . '%';
        }
        $where = ' WHERE (' . implode(' OR ', $conditions) . ')';
    }

    $totalRecords = (int) $db->query('SELECT COUNT(*) ' . $configuration['from'])->fetchColumn();
    if ($where !== '') {
        $countStatement = $db->prepare('SELECT COUNT(*) ' . $configuration['from'] . $where);
        $countStatement->execute($searchParameters);
        $filteredRecords = (int) $countStatement->fetchColumn();
    } else {
        $filteredRecords = $totalRecords;
    }

    $orderColumn = $configuration['order'][$orderIndex] ?? null;
    $orderSql = $orderColumn === null ? ' ORDER BY ' . ($table === 'categories' || $table === 'tags' ? 'name ASC' : ($table === 'dashboard-leads' ? 'l.created_at DESC' : ($table === 'dashboard-blogs' ? 'b.created_at DESC' : ($table === 'leads' ? 'l.created_at DESC' : 'b.created_at DESC')))) : ' ORDER BY ' . $orderColumn . ' ' . $orderDirection;
    $statement = $db->prepare(
        'SELECT ' . $configuration['select'] . ' ' . $configuration['from'] . $where
        . $orderSql . ' LIMIT :limit OFFSET :offset'
    );
    foreach ($searchParameters as $name => $value) {
        $statement->bindValue(':' . $name, $value, PDO::PARAM_STR);
    }
    $statement->bindValue(':limit', $length, PDO::PARAM_INT);
    $statement->bindValue(':offset', $start, PDO::PARAM_INT);
    $statement->execute();
    $records = $statement->fetchAll();
    $blogAssociations = $table === 'blogs'
        ? loadBlogAssociations($db, array_column($records, 'id'))
        : [];

    $data = [];
    foreach ($records as $record) {
        if ($table === 'blogs') {
            $categories = $blogAssociations[$record['id']]['categories'] ?? [];
            $tags = $blogAssociations[$record['id']]['tags'] ?? [];
            if ($categories === [] && !empty($record['category_id'])) {
                $legacyCategoryStatement = $db->prepare('SELECT id, name, slug FROM blog_categories WHERE id = :id');
                $legacyCategoryStatement->execute(['id' => $record['category_id']]);
                $legacyCategory = $legacyCategoryStatement->fetch();
                if ($legacyCategory) {
                    $categories[] = $legacyCategory;
                }
            }
            if ($tags === [] && trim((string) ($record['tag'] ?? '')) !== '') {
                $legacyTagStatement = $db->prepare('SELECT id, name, slug FROM blog_tags WHERE name = :name');
                $legacyTagStatement->execute(['name' => trim((string) $record['tag'])]);
                $legacyTag = $legacyTagStatement->fetch();
                if ($legacyTag) {
                    $tags[] = $legacyTag;
                }
            }
            $categoryNames = array_column($categories, 'name');
            $tagNames = array_column($tags, 'name');
            if ($categoryNames === [] && trim((string) ($record['category_names'] ?? '')) !== '') {
                $categoryNames = explode('||', $record['category_names']);
            }
            if ($tagNames === [] && trim((string) ($record['tag_names'] ?? '')) !== '') {
                $tagNames = explode('||', $record['tag_names']);
            }
            $blogDetails = [
                'id' => $record['id'],
                'title' => $record['title'] ?? '',
                'author' => $record['author'] ?? '',
                'category' => implode(', ', $categoryNames),
                'tag' => implode(', ', $tagNames),
                'status' => $record['status'] ?? '',
                'featured' => !empty($record['is_featured']) ? 'Yes' : 'No',
                'created' => adminFormatDate($record['created_at'] ?? null, 'M d, Y h:i A'),
                'slug' => $record['slug'] ?? '',
                'shortDescription' => $record['short_description'] ?? '',
                'content' => $record['content'] ?? '',
                'image' => $record['featured_image'] ?? '',
                'seoTitle' => $record['seo_title'] ?? '',
                'seoDescription' => $record['seo_description'] ?? '',
                'seoKeywords' => $record['seo_keywords'] ?? '',
            ];
            $imagePath = trim((string) ($record['featured_image'] ?? '')) ?: 'assets/img/placeholder.jpg';
            $statusClass = $record['status'] === 'published' ? 'success' : ($record['status'] === 'draft' ? 'warning' : 'secondary');
            $actions = '<div class="btn-group btn-group-sm"><a href="' . dataTableEscape(adminUrl('blogs.php?action=edit&id=' . rawurlencode($record['id']))) . '" class="btn btn-outline-primary" aria-label="Edit blog"><i class="fas fa-edit"></i></a>'
                . '<form method="post" action="' . dataTableEscape(adminUrl('blogs.php')) . '" onsubmit="return confirm(\'Delete this blog post?\');"><input type="hidden" name="csrf_token" value="' . dataTableEscape(csrfToken()) . '"><input type="hidden" name="delete" value="' . dataTableEscape($record['id']) . '"><button class="btn btn-outline-danger" type="submit" aria-label="Delete blog"><i class="fas fa-trash"></i></button></form></div>';
            $data[] = [
                'cells' => [
                    '<img src="../' . dataTableEscape($imagePath) . '" class="img-thumbnail" style="width:60px;height:40px;object-fit:cover" alt="">',
                    '<div class="fw-bold">' . dataTableEscape($record['title']) . '</div><small class="text-muted">by ' . dataTableEscape($record['author']) . '</small>',
                    dataTableAssociationCell($categories, 'categories'),
                    dataTableAssociationCell($tags, 'tags'),
                    '<span class="badge bg-' . $statusClass . '">' . dataTableEscape(ucfirst($record['status'])) . '</span>',
                    !empty($record['is_featured']) ? '<span class="text-warning"><i class="fas fa-star"></i></span>' : '<span class="text-muted"><i class="far fa-star"></i></span>',
                    dataTableEscape(adminFormatDate($record['created_at'] ?? null, 'M d, Y')),
                    $actions,
                ],
                'DT_RowClass' => 'blog-detail-row',
                'DT_RowAttr' => [
                    'tabindex' => '0',
                    'role' => 'button',
                    'aria-label' => 'View blog: ' . (string) $record['title'],
                    'data-blog' => json_encode($blogDetails, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_INVALID_UTF8_SUBSTITUTE),
                ],
            ];
        } elseif ($table === 'leads') {
            $statusClasses = ['new' => 'badge-new', 'contacted' => 'badge-contacted', 'converted' => 'badge-converted', 'lost' => 'badge-lost'];
            $statusOptions = '';
            foreach (['new', 'contacted', 'converted', 'lost'] as $leadStatus) {
                $statusOptions .= '<li><form method="post" action="' . dataTableEscape(adminUrl('leads.php')) . '" class="m-0"><input type="hidden" name="csrf_token" value="' . dataTableEscape(csrfToken()) . '"><input type="hidden" name="id" value="' . dataTableEscape($record['id']) . '"><input type="hidden" name="status" value="' . $leadStatus . '"><button class="dropdown-item" type="submit">' . ucfirst($leadStatus) . '</button></form></li>';
            }
            $data[] = [
                '#' . dataTableEscape($record['id']),
                '<div class="fw-bold">' . dataTableEscape($record['name']) . '</div>',
                '<div><i class="fas fa-envelope fa-xs text-muted me-1"></i> ' . dataTableEscape($record['email']) . '</div>' . (!empty($record['phone']) ? '<div><i class="fas fa-phone fa-xs text-muted me-1"></i> ' . dataTableEscape($record['phone']) . '</div>' : ''),
                '<span class="text-truncate d-inline-block" style="max-width:150px">' . dataTableEscape($record['subject']) . '</span>',
                '<button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#leadMessageModal" data-lead-name="' . dataTableEscape($record['name']) . '" data-lead-subject="' . dataTableEscape($record['subject']) . '" data-lead-message="' . dataTableEscape($record['message']) . '" data-lead-email="' . dataTableEscape($record['email']) . '">View Message</button>',
                '<div class="dropdown"><button class="badge ' . ($statusClasses[$record['status']] ?? 'bg-secondary') . ' border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown">' . dataTableEscape(ucfirst($record['status'])) . '</button><ul class="dropdown-menu">' . $statusOptions . '</ul></div>',
                '<small>' . dataTableEscape(adminFormatDate($record['created_at'] ?? null, 'M d, Y h:i A')) . '</small>',
                '<form method="post" action="' . dataTableEscape(adminUrl('leads.php')) . '" onsubmit="return confirm(\'Delete this lead?\');"><input type="hidden" name="csrf_token" value="' . dataTableEscape(csrfToken()) . '"><input type="hidden" name="delete" value="' . dataTableEscape($record['id']) . '"><button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Delete lead"><i class="fas fa-trash"></i></button></form>',
            ];
        } elseif ($table === 'categories' || $table === 'tags') {
            $isTag = $table === 'tags';
            $page = $isTag ? 'tags.php' : 'blog-categories.php';
            $entity = $isTag ? 'tag' : 'category';
            $actions = '<div class="btn-group btn-group-sm"><a href="' . dataTableEscape(adminUrl($page . '?edit=' . rawurlencode($record['id']))) . '" class="btn btn-outline-primary" aria-label="Edit ' . $entity . '"><i class="fas fa-edit"></i></a>'
                . '<form method="post" action="' . dataTableEscape(adminUrl($page)) . '" onsubmit="return confirm(\'Delete this ' . $entity . '?\');"><input type="hidden" name="csrf_token" value="' . dataTableEscape(csrfToken()) . '"><input type="hidden" name="delete" value="' . dataTableEscape($record['id']) . '"><button class="btn btn-outline-danger" type="submit" aria-label="Delete ' . $entity . '"><i class="fas fa-trash"></i></button></form></div>';
            $data[] = [
                '<span class="fw-bold">' . dataTableEscape($record['name']) . '</span>',
                '<code>' . dataTableEscape($record['slug']) . '</code>',
                '<span class="badge bg-info">' . (int) $record['post_count'] . '</span>',
                $actions,
            ];
        } elseif ($table === 'dashboard-leads') {
            $statusClasses = ['new' => 'badge-new', 'contacted' => 'badge-contacted', 'converted' => 'badge-converted', 'lost' => 'badge-lost'];
            $data[] = [
                dataTableEscape($record['name']),
                dataTableEscape($record['email']),
                '<span class="badge ' . ($statusClasses[$record['status']] ?? 'bg-secondary') . '">' . dataTableEscape(ucfirst($record['status'])) . '</span>',
                dataTableEscape(adminFormatDate($record['created_at'] ?? null, 'M d')),
            ];
        } else {
            $data[] = [
                '<span class="fw-bold">' . dataTableEscape($record['title']) . '</span>',
                dataTableEscape(implode(', ', array_filter(explode('||', (string) ($record['category_names'] ?? '')))) ?: '-'),
                '<span class="badge bg-' . ($record['status'] === 'published' ? 'success' : 'secondary') . '">' . dataTableEscape(ucfirst($record['status'])) . '</span>',
                dataTableEscape(adminFormatDate($record['created_at'] ?? null, 'M d')),
            ];
        }
    }

    dataTableJson([
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $data,
    ]);
} catch (Throwable $exception) {
    error_log('Admin DataTable query failed: ' . $exception->getMessage());
    dataTableJson([
        'draw' => $draw,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => [],
        'error' => 'The table data could not be loaded.',
    ], 500);
}
