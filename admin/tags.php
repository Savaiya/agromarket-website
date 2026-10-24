<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/src/controller/TagsController.php';

$tagData = (new TagsController())->handle();
extract($tagData, EXTR_SKIP);

$pageTitle = 'Blog Tags';
$activePage = 'tags';
$useDataTables = true;

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
