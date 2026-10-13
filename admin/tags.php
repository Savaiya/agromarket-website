<?php
require_once __DIR__ . '/config.php';
requireLogin();

$pageTitle = 'Blog Tags';
$activePage = 'tags';
$useDataTables = true;
$db = getDB();

function adminTagSlug(string $name): string
{
    $slug = substr(trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($name)), '-'), 0, 100);
    return $slug !== '' ? $slug : 'tag-' . bin2hex(random_bytes(3));
}

function syncBlogTagCatalog(PDO $db): void
{
    $registered = $db->query('SELECT name, slug FROM blog_tags')->fetchAll();
    $names = array_column($registered, 'name');
    $slugs = array_column($registered, 'slug');
    $legacyTags = $db->query(
        "SELECT DISTINCT TRIM(tag) AS name
         FROM blogs
         WHERE tag IS NOT NULL AND TRIM(tag) <> ''"
    )->fetchAll();
    $insert = $db->prepare(
        'INSERT IGNORE INTO blog_tags (id, name, slug, created_at, updated_at)
         VALUES (:id, :name, :slug, UTC_TIMESTAMP(), UTC_TIMESTAMP())'
    );

    foreach ($legacyTags as $legacyTag) {
        $name = trim((string) $legacyTag['name']);
        if ($name === '' || in_array($name, $names, true)) {
            continue;
        }
        $baseSlug = adminTagSlug($name);
        $slug = $baseSlug;
        $suffix = 2;
        while (in_array($slug, $slugs, true)) {
            $slug = substr($baseSlug, 0, 94) . '-' . $suffix++;
        }
        $insert->execute([
            'id' => newAdminId(),
            'name' => $name,
            'slug' => $slug,
        ]);
        $names[] = $name;
        $slugs[] = $slug;
    }
}

syncBlogTagCatalog($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidCsrfToken();
    $id = is_string($_POST['id'] ?? null) ? trim($_POST['id']) : '';

    if (isset($_POST['delete'])) {
        $id = is_string($_POST['delete']) ? trim($_POST['delete']) : '';
        if (!isAdminId($id)) {
            http_response_code(400);
            exit('Invalid tag identifier.');
        }
        $statement = $db->prepare('SELECT name FROM blog_tags WHERE id = :id');
        $statement->execute(['id' => $id]);
        $tagName = $statement->fetchColumn();
        if ($tagName === false) {
            $errorMessage = 'The selected tag was not found.';
        } else {
            $db->beginTransaction();
            try {
                $statement = $db->prepare('UPDATE blogs SET tag = \'\' WHERE TRIM(tag) = :name');
                $statement->execute(['name' => $tagName]);
                $statement = $db->prepare('DELETE FROM blog_tags WHERE id = :id');
                $statement->execute(['id' => $id]);
                $db->commit();
                $successMessage = 'Tag deleted successfully.';
            } catch (Throwable $exception) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }
                throw $exception;
            }
        }
    } else {
        $name = trim((string) ($_POST['name'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $slug = adminTagSlug($name);
        if ($id !== '' && !isAdminId($id)) {
            http_response_code(400);
            exit('Invalid tag identifier.');
        }
        if ($name === '') {
            $errorMessage = 'A tag name is required.';
        } elseif (mb_strlen($name) > 80) {
            $errorMessage = 'Tag names must be 80 characters or fewer.';
        } elseif (mb_strlen($description) > 2000) {
            $errorMessage = 'Tag descriptions must be 2,000 characters or fewer.';
        } else {
            $statement = $id !== ''
                ? $db->prepare('SELECT COUNT(*) FROM blog_tags WHERE name = :name AND id <> :id')
                : $db->prepare('SELECT COUNT(*) FROM blog_tags WHERE name = :name');
            $statement->execute($id !== '' ? ['name' => $name, 'id' => $id] : ['name' => $name]);
            if ((int) $statement->fetchColumn() > 0) {
                $errorMessage = 'A tag with that name already exists.';
            } else {
                $statement = $id !== ''
                    ? $db->prepare('SELECT COUNT(*) FROM blog_tags WHERE slug = :slug AND id <> :id')
                    : $db->prepare('SELECT COUNT(*) FROM blog_tags WHERE slug = :slug');
                $statement->execute($id !== '' ? ['slug' => $slug, 'id' => $id] : ['slug' => $slug]);
                if ((int) $statement->fetchColumn() > 0) {
                    $errorMessage = 'A tag with that URL slug already exists.';
                } elseif ($id !== '') {
                    $statement = $db->prepare('SELECT name FROM blog_tags WHERE id = :id');
                    $statement->execute(['id' => $id]);
                    $oldName = $statement->fetchColumn();
                    if ($oldName === false) {
                        $errorMessage = 'The selected tag was not found.';
                    } else {
                        $db->beginTransaction();
                        try {
                            $statement = $db->prepare('UPDATE blogs SET tag = :name WHERE TRIM(tag) = :old_name');
                            $statement->execute(['name' => $name, 'old_name' => $oldName]);
                            $statement = $db->prepare(
                                'UPDATE blog_tags SET name = :name, slug = :slug, description = :description,
                                 updated_at = UTC_TIMESTAMP() WHERE id = :id'
                            );
                            $statement->execute([
                                'name' => $name,
                                'slug' => $slug,
                                'description' => $description,
                                'id' => $id,
                            ]);
                            $db->commit();
                            $successMessage = 'Tag updated successfully.';
                        } catch (Throwable $exception) {
                            if ($db->inTransaction()) {
                                $db->rollBack();
                            }
                            throw $exception;
                        }
                    }
                } else {
                    $statement = $db->prepare(
                        'INSERT INTO blog_tags (id, name, slug, description, created_at, updated_at)
                         VALUES (:id, :name, :slug, :description, UTC_TIMESTAMP(), UTC_TIMESTAMP())'
                    );
                    $statement->execute([
                        'id' => newAdminId(),
                        'name' => $name,
                        'slug' => $slug,
                        'description' => $description,
                    ]);
                    $successMessage = 'Tag added successfully.';
                }
            }
        }
    }
}

$editTag = ['id' => '', 'name' => '', 'description' => ''];
if (isset($_GET['edit'])) {
    $editId = is_string($_GET['edit']) ? trim($_GET['edit']) : '';
    if (isAdminId($editId)) {
        $statement = $db->prepare('SELECT id, name, description FROM blog_tags WHERE id = :id');
        $statement->execute(['id' => $editId]);
        $editTag = $statement->fetch() ?: $editTag;
    }
}

ob_start();
?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus-circle me-2"></i> <?php echo $editTag['id'] !== '' ? 'Edit' : 'Add New'; ?> Tag
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars(adminUrl('tags.php')); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken()); ?>">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editTag['id']); ?>">
                    <div class="mb-3">
                        <label class="form-label" for="tagName">Tag Name</label>
                        <input id="tagName" type="text" name="name" class="form-control" maxlength="80" required value="<?php echo htmlspecialchars($editTag['name']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tagDescription">Description</label>
                        <textarea id="tagDescription" name="description" class="form-control" rows="3" maxlength="2000"><?php echo htmlspecialchars($editTag['description']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i> Save Tag
                    </button>
                    <?php if ($editTag['id'] !== ''): ?>
                        <a href="<?php echo htmlspecialchars(adminUrl('tags.php')); ?>" class="btn btn-light w-100 mt-2">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-list me-2"></i> Existing Tags</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tagsTable" class="table table-hover align-middle mb-0 w-100">
                        <thead>
                            <tr><th>Name</th><th>Slug</th><th>Posts</th><th>Actions</th></tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$extraScripts = '<script>window.initAdminDataTable("#tagsTable", "tags", {order:[[0,"asc"]]});</script>';
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
