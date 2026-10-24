<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/src/controller/BlogCategoriesController.php';

$categoryData = (new BlogCategoriesController())->handle();
extract($categoryData, EXTR_SKIP);

$pageTitle = 'Blog Categories';
$activePage = 'categories';
$useDataTables = true;

ob_start();
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus-circle me-2"></i> <?php echo isset($_GET['edit']) ? 'Edit' : 'Add New'; ?> Category
            </div>
            <div class="card-body">
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
