<?php
require_once __DIR__ . '/config.php';
requireLogin();

$pageTitle = 'Blog Categories';
$activePage = 'categories';
$useDataTables = true;
$db = getDB();

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireValidCsrfToken();
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
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
        $successMessage = "Category added successfully.";
    }
}

ob_start();
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus-circle me-2"></i> <?php echo isset($_GET['edit']) ? 'Edit' : 'Add New'; ?> Category
            </div>
            <div class="card-body">
                <?php 
                $editCat = ['id' => '', 'name' => '', 'description' => ''];
                if (isset($_GET['edit'])) {
                    $editId = is_string($_GET['edit']) ? trim($_GET['edit']) : '';
                    if (isAdminId($editId)) {
                        $statement = $db->prepare('SELECT id, name, description FROM blog_categories WHERE id = :id');
                        $statement->execute(['id' => $editId]);
                        $editCat = $statement->fetch() ?: $editCat;
                    }
                }
                ?>
                <form method="POST" action="<?php echo htmlspecialchars(adminUrl('blog-categories.php')); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken()); ?>">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editCat['id']); ?>">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($editCat['name']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($editCat['description']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i> Save Category
                    </button>
                    <?php if (isset($_GET['edit'])): ?>
                        <a href="<?php echo htmlspecialchars(adminUrl('blog-categories.php')); ?>" class="btn btn-light w-100 mt-2">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i> Existing Categories
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="categoriesTable" class="table table-hover align-middle mb-0 w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Posts</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$extraScripts = '<script>window.initAdminDataTable("#categoriesTable", "categories", {order:[[0,"asc"]], columnDefs:[{targets:[3],orderable:false,searchable:false}]});</script>';
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
