<?php
require_once 'config.php';
requireLogin();

$pageTitle = 'Blog Categories';
$activePage = 'categories';
$db = getDB();

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $slug = strtolower(str_replace(' ', '-', $name));
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        $stmt = $db->prepare("UPDATE blog_categories SET name = ?, slug = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $slug, $description, $id]);
        $successMessage = "Category updated successfully.";
    } else {
        $stmt = $db->prepare("INSERT INTO blog_categories (name, slug, description) VALUES (?, ?, ?)");
        $stmt->execute([$name, $slug, $description]);
        $successMessage = "Category added successfully.";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $db->prepare("DELETE FROM blog_categories WHERE id = ?");
    $stmt->execute([$id]);
    $successMessage = "Category deleted successfully.";
}

// Fetch Categories
$categories = $db->query("SELECT * FROM blog_categories ORDER BY name ASC")->fetchAll();

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
                    $stmt = $db->prepare("SELECT * FROM blog_categories WHERE id = ?");
                    $stmt->execute([intval($_GET['edit'])]);
                    $editCat = $stmt->fetch() ?: $editCat;
                }
                ?>
                <form method="POST" action="blog-categories.php">
                    <input type="hidden" name="id" value="<?php echo $editCat['id']; ?>">
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
                        <a href="blog-categories.php" class="btn btn-light w-100 mt-2">Cancel</a>
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
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Posts</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): 
                                $count = $db->query("SELECT COUNT(*) as count FROM blogs WHERE category_id = " . $cat['id'])->fetch();
                            ?>
                            <tr>
                                <td class="fw-bold"><?php echo htmlspecialchars($cat['name']); ?></td>
                                <td><code><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                                <td><span class="badge bg-info"><?php echo $count['count']; ?></span></td>
                                <td>
                                    <a href="?edit=<?php echo $cat['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('blog-categories.php?delete=<?php echo $cat['id']; ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
