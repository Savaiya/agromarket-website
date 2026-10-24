<?php

require_once __DIR__ . '/../../../config/config.php';

class BlogCategoriesController
{
    public function handle(): array
    {
        requireLogin();
        $db = getDB();
        $errorMessage = null;
        $successMessage = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            requireValidCsrfToken();
            $name = trim(is_string($_POST['name'] ?? null) ? $_POST['name'] : '');
            $description = trim(is_string($_POST['description'] ?? null) ? $_POST['description'] : '');
            $slug = substr(trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($name)), '-'), 0, 100);
            $id = is_string($_POST['id'] ?? null) ? trim($_POST['id']) : '';

            if (isset($_POST['delete'])) {
                $id = is_string($_POST['delete']) ? trim($_POST['delete']) : '';
                if (!isAdminId($id)) {
                    http_response_code(400);
                    exit('Invalid category identifier.');
                }

                $statement = $db->prepare('DELETE FROM blog_categories WHERE id = :id');
                $statement->execute(['id' => $id]);
                $successMessage = $statement->rowCount() > 0
                    ? 'Category deleted successfully.'
                    : 'The selected category was not found.';
            } elseif ($name === '') {
                $errorMessage = 'A category name is required.';
            } elseif ($slug === '') {
                $errorMessage = 'The category name must contain letters or numbers.';
            } elseif ($id !== '') {
                if (!isAdminId($id)) {
                    http_response_code(400);
                    exit('Invalid category identifier.');
                }
                $statement = $db->prepare(
                    'UPDATE blog_categories
                     SET name = :name, slug = :slug, description = :description, updated_at = UTC_TIMESTAMP()
                     WHERE id = :id'
                );
                $statement->execute([
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                    'id' => $id,
                ]);
                $successMessage = $statement->rowCount() > 0
                    ? 'Category updated successfully.'
                    : 'The selected category was not found.';
            } else {
                $statement = $db->prepare(
                    "INSERT INTO blog_categories (id, name, slug, description, status, created_at, updated_at)
                     VALUES (:id, :name, :slug, :description, 'active', UTC_TIMESTAMP(), UTC_TIMESTAMP())"
                );
                $statement->execute([
                    'id' => newAdminId(),
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                ]);
                $successMessage = 'Category added successfully.';
            }
        }

        $editCat = ['id' => '', 'name' => '', 'description' => ''];
        if (isset($_GET['edit'])) {
            $editId = is_string($_GET['edit']) ? trim($_GET['edit']) : '';
            if (isAdminId($editId)) {
                $statement = $db->prepare('SELECT id, name, description FROM blog_categories WHERE id = :id');
                $statement->execute(['id' => $editId]);
                $editCat = $statement->fetch() ?: $editCat;
            }
        }

        return [
            'db' => $db,
            'editCat' => $editCat,
            'errorMessage' => $errorMessage,
            'successMessage' => $successMessage,
        ];
    }
}
