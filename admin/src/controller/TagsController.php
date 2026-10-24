<?php

require_once __DIR__ . '/../../../config/config.php';

class TagsController
{
    public function handle(): array
    {
        requireLogin();
        $db = getDB();
        $errorMessage = null;
        $successMessage = null;

        $this->syncBlogTagCatalog($db);

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
                $name = trim(is_string($_POST['name'] ?? null) ? $_POST['name'] : '');
                $description = trim(is_string($_POST['description'] ?? null) ? $_POST['description'] : '');
                $slug = $this->tagSlug($name);
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

        return [
            'db' => $db,
            'editTag' => $editTag,
            'errorMessage' => $errorMessage,
            'successMessage' => $successMessage,
        ];
    }

    private function tagSlug(string $name): string
    {
        $slug = substr(trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($name)), '-'), 0, 100);

        return $slug !== '' ? $slug : 'tag-' . bin2hex(random_bytes(3));
    }

    private function syncBlogTagCatalog(PDO $db): void
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
            $baseSlug = $this->tagSlug($name);
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
}
