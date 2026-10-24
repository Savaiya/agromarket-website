<?php

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/blog-associations.php';

class BlogsController
{
    public function handle(): array
    {
        requireLogin();
        $errorMessage = null;
        $successMessage = null;

$pageTitle = 'Blog Posts';
$activePage = 'blogs';
$useDataTables = true;
$db = getDB();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete'])) {
    requireValidCsrfToken();
    $id = is_string($_POST['id'] ?? null) ? trim($_POST['id']) : '';
    if ($id !== '' && !isAdminId($id)) {
        http_response_code(400);
        exit('Invalid blog post identifier.');
    }
    $title = trim($_POST['title'] ?? '');
    $slug = strtolower(trim($_POST['slug'] ?? ''));
    if ($slug === '') {
        $slug = $title;
    }
    $slug = substr(trim(preg_replace('/[^a-z0-9]+/', '-', $slug), '-'), 0, 200);
    $normalizeIds = static function ($values): ?array {
        if (!is_array($values) || count($values) > 100) {
            return null;
        }
        $ids = [];
        foreach ($values as $value) {
            if (!is_string($value) || !isAdminId(trim($value))) {
                return null;
            }
            $ids[] = trim($value);
        }
        return array_values(array_unique($ids));
    };
    $categoryIds = $normalizeIds($_POST['category_ids'] ?? []);
    $tagIds = $normalizeIds($_POST['tag_ids'] ?? []);
    if ($categoryIds === null || $tagIds === null) {
        http_response_code(400);
        exit('Invalid blog category or tag selection.');
    }
    $short_description = trim($_POST['short_description'] ?? '');
    $content_text = $_POST['content'] ?? '';
    $status = $_POST['status'] ?? 'draft';
    $author = $_SESSION['admin_name'] ?? 'Admin';
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $seo_title = trim($_POST['seo_title'] ?? '');
    $seo_description = trim($_POST['seo_description'] ?? '');
    $seo_keywords = trim($_POST['seo_keywords'] ?? '');
    if (!in_array($status, ['draft', 'published', 'archived'], true)) {
        http_response_code(400);
        exit('Invalid blog status.');
    }

    $category_id = $categoryIds[0] ?? null;
    $tag = '';
    if ($categoryIds === []) {
        $errorMessage = 'Select at least one blog category.';
    }
    foreach ([['blog_categories', $categoryIds], ['blog_tags', $tagIds]] as [$table, $selectedIds]) {
        if ($selectedIds === []) {
            continue;
        }
        $placeholders = implode(', ', array_fill(0, count($selectedIds), '?'));
        $statement = $db->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE id IN (' . $placeholders . ')');
        $statement->execute($selectedIds);
        if ((int) $statement->fetchColumn() !== count($selectedIds)) {
            http_response_code(400);
            exit('One or more selected categories or tags no longer exist.');
        }
    }
    if ($tagIds !== []) {
        $statement = $db->prepare('SELECT name FROM blog_tags WHERE id = :id');
        $statement->execute(['id' => $tagIds[0]]);
        $tag = (string) $statement->fetchColumn();
    }

    $featured_image = '';
    if ($id !== '') {
        $statement = $db->prepare('SELECT id, featured_image, published_at FROM blogs WHERE id = :id');
        $statement->execute(['id' => $id]);
        $existingPost = $statement->fetch();
        if (!$existingPost) {
            http_response_code(404);
            exit('Blog post not found.');
        }
        $featured_image = $existingPost['featured_image'] ?? '';
    }
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload = $_FILES['image'];
        $imageInfo = $upload['error'] === UPLOAD_ERR_OK ? getimagesize($upload['tmp_name']) : false;
        $mimeType = $imageInfo['mime'] ?? '';
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if ($upload['error'] !== UPLOAD_ERR_OK || $upload['size'] > 5 * 1024 * 1024 || !isset($extensions[$mimeType])) {
            $errorMessage = 'Choose a JPEG, PNG, or WebP image smaller than 5 MB.';
        } else {
            $targetDir = dirname(__DIR__, 3) . '/assets/uploads';
            if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
                throw new RuntimeException('The blog image upload directory could not be created.');
            }
            $fileName = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
            if (!move_uploaded_file($upload['tmp_name'], $targetDir . DIRECTORY_SEPARATOR . $fileName)) {
                throw new RuntimeException('The uploaded blog image could not be saved.');
            }
            $featured_image = 'assets/uploads/' . $fileName;
        }
    }

    if (!isset($errorMessage) && $title === '') {
        $errorMessage = 'A blog title is required.';
    }
    if (!isset($errorMessage) && $slug === '') {
        $errorMessage = 'The title or slug must contain letters or numbers.';
    }
    if (!isset($errorMessage) && $id === '' && $featured_image === '') {
        $errorMessage = 'A featured image is required for a new blog post.';
    }
    if (!isset($errorMessage)) {
        $isUpdate = $id !== '';
        if (!$isUpdate) {
            $id = newAdminId();
        }
        $db->beginTransaction();
        try {
            if ($isUpdate) {
                $statement = $db->prepare(
                    'UPDATE blogs
                     SET title = :title, slug = :slug, category_id = :category_id, tag = :tag,
                         short_description = :short_description, content = :content,
                         featured_image = :featured_image, status = :status,
                         is_featured = :is_featured, seo_title = :seo_title,
                         seo_description = :seo_description, seo_keywords = :seo_keywords,
                         updated_at = UTC_TIMESTAMP(),
                         published_at = CASE
                             WHEN :is_published = 1 THEN COALESCE(published_at, UTC_TIMESTAMP())
                             ELSE NULL
                         END
                     WHERE id = :id'
                );
                $statement->execute([
                    'title' => $title,
                    'slug' => $slug,
                    'category_id' => $category_id,
                    'tag' => $tag,
                    'short_description' => $short_description,
                    'content' => $content_text,
                    'featured_image' => $featured_image,
                    'status' => $status,
                    'is_featured' => $is_featured,
                    'seo_title' => $seo_title,
                    'seo_description' => $seo_description,
                    'seo_keywords' => $seo_keywords,
                    'is_published' => $status === 'published' ? 1 : 0,
                    'id' => $id,
                ]);
            } else {
                $statement = $db->prepare(
                    'INSERT INTO blogs
                        (id, title, slug, category_id, tag, short_description, content, featured_image,
                         status, author, is_featured, created_at, updated_at, published_at,
                         seo_title, seo_description, seo_keywords)
                     VALUES
                        (:id, :title, :slug, :category_id, :tag, :short_description, :content, :featured_image,
                         :status, :author, :is_featured, UTC_TIMESTAMP(), UTC_TIMESTAMP(),
                         CASE WHEN :is_published = 1 THEN UTC_TIMESTAMP() ELSE NULL END,
                         :seo_title, :seo_description, :seo_keywords)'
                );
                $statement->execute([
                    'id' => $id,
                    'title' => $title,
                    'slug' => $slug,
                    'category_id' => $category_id,
                    'tag' => $tag,
                    'short_description' => $short_description,
                    'content' => $content_text,
                    'featured_image' => $featured_image,
                    'status' => $status,
                    'author' => $author,
                    'is_featured' => $is_featured,
                    'is_published' => $status === 'published' ? 1 : 0,
                    'seo_title' => $seo_title,
                    'seo_description' => $seo_description,
                    'seo_keywords' => $seo_keywords,
                ]);
            }
            saveBlogAssociations($db, $id, $categoryIds, $tagIds);
            $db->commit();
            $successMessage = $isUpdate ? 'Blog post updated successfully.' : 'Blog post created successfully.';
        } catch (Throwable $exception) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $exception;
        }
    }
}

// Handle Delete
if (isset($_POST['delete'])) {
    requireValidCsrfToken();
    $id = is_string($_POST['delete']) ? trim($_POST['delete']) : '';
    if (!isAdminId($id)) {
        http_response_code(400);
        exit('Invalid blog post identifier.');
    }
    $statement = $db->prepare('DELETE FROM blogs WHERE id = :id');
    $statement->execute(['id' => $id]);
    $successMessage = $statement->rowCount() > 0
        ? 'Blog post deleted successfully.'
        : 'The selected blog post was not found.';
}

$categories = $db->query('SELECT id, name FROM blog_categories ORDER BY name ASC')->fetchAll();
$availableTags = $db->query('SELECT id, name FROM blog_tags ORDER BY name ASC')->fetchAll();


        $blogEditor = isset($_GET['action']) && in_array($_GET['action'], ['create', 'edit'], true);
$post = ['id' => '', 'title' => '', 'slug' => '', 'category_id' => '', 'tag' => '', 'short_description' => '', 'content' => '', 'featured_image' => '', 'status' => 'draft', 'is_featured' => 0, 'seo_title' => '', 'seo_description' => '', 'seo_keywords' => ''];
    $selectedCategoryIds = [];
    $selectedTagIds = [];
    if ($blogEditor && $_GET['action'] === 'edit' && isset($_GET['id'])) {
        $editId = is_string($_GET['id']) ? trim($_GET['id']) : '';
        if (!isAdminId($editId)) {
            http_response_code(400);
            exit('Invalid blog post identifier.');
        }
        $statement = $db->prepare('SELECT * FROM blogs WHERE id = :id');
        $statement->execute(['id' => $editId]);
        $post = $statement->fetch() ?: $post;
        $associations = loadBlogAssociations($db, [$editId]);
        $selectedCategoryIds = array_column($associations[$editId]['categories'] ?? [], 'id');
        $selectedTagIds = array_column($associations[$editId]['tags'] ?? [], 'id');
        if ($selectedCategoryIds === [] && !empty($post['category_id'])) {
            $selectedCategoryIds[] = $post['category_id'];
        }
        if ($selectedTagIds === [] && trim((string) ($post['tag'] ?? '')) !== '') {
            $statement = $db->prepare('SELECT id FROM blog_tags WHERE name = :name');
            $statement->execute(['name' => trim((string) $post['tag'])]);
            $legacyTagId = $statement->fetchColumn();
            if ($legacyTagId !== false) {
                $selectedTagIds[] = $legacyTagId;
            }
        }
    }

        return compact('pageTitle', 'activePage', 'useDataTables', 'db', 'categories', 'availableTags', 'errorMessage', 'successMessage', 'post', 'selectedCategoryIds', 'selectedTagIds', 'blogEditor');
    }
}
