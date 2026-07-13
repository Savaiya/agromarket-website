<?php
require_once 'config.php';
requireLogin();

$pageTitle = 'Blog Posts';
$activePage = 'blogs';
$db = getDB();

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = trim($_POST['title']);
    $slug = trim($_POST['slug']);
    if (empty($slug)) $slug = strtolower(str_replace(' ', '-', $title));
    $category_id = intval($_POST['category_id']);
    $short_description = trim($_POST['short_description']);
    $content_text = $_POST['content'];
    $status = $_POST['status'];
    $author = $_SESSION['admin_name'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $seo_title = trim($_POST['seo_title']);
    $seo_description = trim($_POST['seo_description']);
    $seo_keywords = trim($_POST['seo_keywords']);
    
    // Handle Image Upload
    $featured_image = $_POST['current_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "../assets/img/blog/uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $featured_image = "assets/img/blog/uploads/" . $fileName;
        }
    }

    if ($id > 0) {
        $stmt = $db->prepare("UPDATE blogs SET title = ?, slug = ?, category_id = ?, short_description = ?, content = ?, featured_image = ?, status = ?, is_featured = ?, seo_title = ?, seo_description = ?, seo_keywords = ? WHERE id = ?");
        $stmt->execute([$title, $slug, $category_id, $short_description, $content_text, $featured_image, $status, $is_featured, $seo_title, $seo_description, $seo_keywords, $id]);
        $successMessage = "Blog post updated successfully.";
    } else {
        $stmt = $db->prepare("INSERT INTO blogs (title, slug, category_id, short_description, content, featured_image, status, author, is_featured, published_at, seo_title, seo_description, seo_keywords) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute([$title, $slug, $category_id, $short_description, $content_text, $featured_image, $status, $author, $is_featured, $seo_title, $seo_description, $seo_keywords]);
        $successMessage = "Blog post created successfully.";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $db->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->execute([$id]);
    $successMessage = "Blog post deleted successfully.";
}

// Fetch Blogs
$blogs = $db->query("SELECT b.*, c.name as category_name FROM blogs b LEFT JOIN blog_categories c ON b.category_id = c.id ORDER BY b.created_at DESC")->fetchAll();
$categories = $db->query("SELECT * FROM blog_categories ORDER BY name ASC")->fetchAll();

ob_start();

if (isset($_GET['action']) && ($_GET['action'] === 'create' || $_GET['action'] === 'edit')):
    $post = ['id' => 0, 'title' => '', 'slug' => '', 'category_id' => '', 'short_description' => '', 'content' => '', 'featured_image' => '', 'status' => 'draft', 'is_featured' => 0, 'seo_title' => '', 'seo_description' => '', 'seo_keywords' => ''];
    if ($_GET['action'] === 'edit' && isset($_GET['id'])) {
        $stmt = $db->prepare("SELECT * FROM blogs WHERE id = ?");
        $stmt->execute([intval($_GET['id'])]);
        $post = $stmt->fetch() ?: $post;
    }
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-edit me-2"></i> <?php echo $post['id'] ? 'Edit' : 'Create'; ?> Blog Post</span>
        <a href="blogs.php" class="btn btn-sm btn-secondary">Back to List</a>
    </div>
    <div class="card-body">
        <form method="POST" action="blogs.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
            <input type="hidden" name="current_image" value="<?php echo $post['featured_image']; ?>">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($post['title']); ?>" id="blogTitle">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug (Optional)</label>
                        <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($post['slug']); ?>" id="blogSlug">
                        <small class="text-muted">Leave empty to auto-generate</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="2" required><?php echo htmlspecialchars($post['short_description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Content</label>
                        <textarea name="content" id="editor" class="form-control" rows="10"><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <i class="fas fa-search me-2"></i> SEO Settings
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">SEO Title</label>
                                <input type="text" name="seo_title" class="form-control" value="<?php echo htmlspecialchars($post['seo_title']); ?>" placeholder="Leave empty to use blog title">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SEO Description</label>
                                <textarea name="seo_description" class="form-control" rows="3" placeholder="Brief summary for search engines"><?php echo htmlspecialchars($post['seo_description']); ?></textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">SEO Keywords</label>
                                <input type="text" name="seo_keywords" class="form-control" value="<?php echo htmlspecialchars($post['seo_keywords']); ?>" placeholder="keyword1, keyword2, keyword3">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $post['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Featured Image</label>
                        <?php if ($post['featured_image']): ?>
                            <div class="mb-2">
                                <img src="../<?php echo $post['featured_image']; ?>" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" <?php echo $post['id'] ? '' : 'required'; ?>>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" <?php echo $post['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo $post['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                            <option value="archived" <?php echo $post['status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" <?php echo $post['is_featured'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="isFeatured">Featured Post</label>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-save me-1"></i> Save Blog Post
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
    
    document.getElementById('blogTitle').addEventListener('input', function() {
        if (!document.getElementById('blogSlug').value || document.getElementById('blogSlug').getAttribute('data-auto') === 'true') {
            const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            document.getElementById('blogSlug').value = slug;
            document.getElementById('blogSlug').setAttribute('data-auto', 'true');
        }
    });
</script>

<?php else: ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-blog me-2"></i> All Blog Posts</span>
        <a href="?action=create" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> New Post
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($blogs)): ?>
                        <?php foreach ($blogs as $blog): ?>
                        <tr>
                            <td>
                                <img src="../<?php echo $blog['featured_image'] ?: 'assets/img/placeholder.jpg'; ?>" 
                                     class="img-thumbnail" style="width: 60px; height: 40px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($blog['title']); ?></div>
                                <small class="text-muted">by <?php echo htmlspecialchars($blog['author']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($blog['category_name']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $blog['status'] === 'published' ? 'success' : ($blog['status'] === 'draft' ? 'warning' : 'secondary'); ?>">
                                    <?php echo ucfirst($blog['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($blog['is_featured']): ?>
                                    <span class="text-warning"><i class="fas fa-star"></i></span>
                                <?php else: ?>
                                    <span class="text-muted"><i class="far fa-star"></i></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="?action=edit&id=<?php echo $blog['id']; ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('blogs.php?delete=<?php echo $blog['id']; ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No blog posts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php endif; ?>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
