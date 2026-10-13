<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../includes/blog-associations.php';
requireLogin();

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
            $targetDir = __DIR__ . '/../assets/uploads';
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

ob_start();

if (isset($_GET['action']) && ($_GET['action'] === 'create' || $_GET['action'] === 'edit')):
    $post = ['id' => '', 'title' => '', 'slug' => '', 'category_id' => '', 'tag' => '', 'short_description' => '', 'content' => '', 'featured_image' => '', 'status' => 'draft', 'is_featured' => 0, 'seo_title' => '', 'seo_description' => '', 'seo_keywords' => ''];
    $selectedCategoryIds = [];
    $selectedTagIds = [];
    if ($_GET['action'] === 'edit' && isset($_GET['id'])) {
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
    ?>

    <style>
        .admin-association-list {
            max-height: 250px;
            min-width: 0;
            margin: 0;
            padding: 8px;
            overflow-y: auto;
            border: 1px solid #dfe7e1;
            border-radius: 8px;
        }
        .admin-association-list legend {
            float: none;
            width: auto;
            margin: 0 0 7px;
            padding: 0 4px;
            color: #748078;
            font-size: 0.76rem;
        }
        .admin-association-search {
            min-height: 32px;
            margin-bottom: 4px;
        }
        .admin-association-option {
            display: flex;
            align-items: center;
            gap: 9px;
            height: 32px;
            min-height: 32px;
            margin: 0;
            padding: 4px 8px;
            border-radius: 6px;
            color: #3c4940;
            cursor: pointer;
        }
        .admin-association-option span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .admin-association-option:hover {
            background: #f4f9f5;
        }
        .admin-association-option input {
            flex: 0 0 auto;
            width: 17px;
            height: 17px;
            margin: 0;
            accent-color: #229954;
        }
        .admin-association-option input:checked + span {
            color: #187642;
            font-weight: 700;
        }
                .editor-image-lightbox {
                    width: min(560px, calc(100% - 2rem));
                    max-width: none;
                    padding: 0;
                    border: 0;
                    border-radius: 10px;
                    color: #26362b;
                    box-shadow: 0 20px 60px rgba(15, 35, 22, 0.3);
                }
                .editor-image-lightbox::backdrop {
                    background: rgba(14, 27, 18, 0.62);
                    backdrop-filter: blur(3px);
                }
                .editor-image-lightbox .modal-header,
                .editor-image-lightbox .modal-footer {
                    padding: 16px 20px;
                    border-color: #e4ebe5;
                }
                .editor-image-lightbox .modal-body {
                    display: grid;
                    gap: 14px;
                    padding: 20px;
                }
                .editor-image-preview {
                    display: none;
                    width: 100%;
                    max-height: 280px;
                    border-radius: 6px;
                    background: #f4f7f4;
                    object-fit: contain;
                }
                .editor-image-upload-status {
                    min-height: 20px;
                    margin: 0;
                    color: #68766c;
                    font-size: 0.85rem;
                }
    </style>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-edit me-2"></i> <?php echo $post['id'] !== '' ? 'Edit' : 'Create'; ?> Blog Post</span>
            <a href="<?php echo htmlspecialchars(adminUrl('blogs.php')); ?>" class="btn btn-sm btn-secondary">Back to List</a>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo htmlspecialchars(adminUrl('blogs.php')); ?>" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken()); ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($post['id']); ?>">

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required
                                value="<?php echo htmlspecialchars($post['title']); ?>" id="blogTitle">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug (Optional)</label>
                            <input type="text" name="slug" class="form-control"
                                value="<?php echo htmlspecialchars($post['slug']); ?>" id="blogSlug">
                            <small class="text-muted">Leave empty to auto-generate</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Short Description</label>
                            <textarea name="short_description" class="form-control" rows="2"
                                required><?php echo htmlspecialchars($post['short_description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Content</label>
                            <textarea name="content" id="editor" class="form-control"
                                rows="10"><?php echo htmlspecialchars($post['content']); ?></textarea>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <i class="fas fa-search me-2"></i> SEO Settings
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">SEO Title</label>
                                    <input type="text" name="seo_title" class="form-control"
                                        value="<?php echo htmlspecialchars($post['seo_title']); ?>"
                                        placeholder="Leave empty to use blog title">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">SEO Description</label>
                                    <textarea name="seo_description" class="form-control" rows="3"
                                        placeholder="Brief summary for search engines"><?php echo htmlspecialchars($post['seo_description']); ?></textarea>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">SEO Keywords</label>
                                    <input type="text" name="seo_keywords" class="form-control"
                                        value="<?php echo htmlspecialchars($post['seo_keywords']); ?>"
                                        placeholder="keyword1, keyword2, keyword3">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <fieldset class="admin-association-list" aria-label="Blog categories">
                                <legend>Choose one or more categories</legend>
                                <input type="search" class="form-control form-control-sm admin-association-search" placeholder="Search categories..." aria-label="Search categories" autocomplete="off">
                                <?php foreach ($categories as $cat): ?>
                                    <label class="admin-association-option">
                                        <input type="checkbox" name="category_ids[]" value="<?php echo htmlspecialchars($cat['id']); ?>" <?php echo in_array($cat['id'], $selectedCategoryIds, true) ? 'checked' : ''; ?>>
                                        <span><?php echo htmlspecialchars($cat['name']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </fieldset>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tag</label>
                            <fieldset class="admin-association-list" aria-label="Blog tags">
                                <legend>Choose any tags</legend>
                                <input type="search" class="form-control form-control-sm admin-association-search" placeholder="Search tags..." aria-label="Search tags" autocomplete="off">
                                <?php foreach ($availableTags as $availableTag): ?>
                                    <label class="admin-association-option">
                                        <input type="checkbox" name="tag_ids[]" value="<?php echo htmlspecialchars($availableTag['id']); ?>" <?php echo in_array($availableTag['id'], $selectedTagIds, true) ? 'checked' : ''; ?>>
                                        <span><?php echo htmlspecialchars($availableTag['name']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </fieldset>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Featured Image</label>
                            <?php if ($post['featured_image']): ?>
                                <div class="mb-2">
                                    <img src="../<?php echo htmlspecialchars($post['featured_image']); ?>" class="img-thumbnail"
                                        style="max-height: 150px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="image" class="form-control" <?php echo $post['id'] !== '' ? '' : 'required'; ?>>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft" <?php echo $post['status'] === 'draft' ? 'selected' : ''; ?>>Draft
                                </option>
                                <option value="published" <?php echo $post['status'] === 'published' ? 'selected' : ''; ?>>
                                    Published</option>
                                <option value="archived" <?php echo $post['status'] === 'archived' ? 'selected' : ''; ?>>
                                    Archived</option>
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

    <dialog class="editor-image-lightbox" id="editorImageLightbox" aria-labelledby="editorImageTitle">
        <form id="editorImageUploadForm">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0" id="editorImageTitle">Insert image</h2>
                <button type="button" class="btn-close" id="closeEditorImageLightbox" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label mb-0" for="editorImageFile">Choose an image from your device</label>
                <input class="form-control" type="file" id="editorImageFile" accept="image/jpeg,image/png,image/gif,image/webp" required>
                <img class="editor-image-preview" id="editorImagePreview" alt="Selected image preview">
                <label class="form-label mb-0" for="editorImageAlt">Alternative text</label>
                <input class="form-control" type="text" id="editorImageAlt" maxlength="250" placeholder="Describe the image">
                <p class="editor-image-upload-status" id="editorImageUploadStatus" role="status" aria-live="polite"></p>
            </div>
            <div class="modal-footer d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light" id="cancelEditorImageUpload">Cancel</button>
                <button type="submit" class="btn btn-primary" id="insertEditorImage">Upload and insert</button>
            </div>
        </form>
    </dialog>

    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
        const editorImageToken = <?php echo json_encode(rawurlencode(csrfToken())); ?>;
        const editorImageUploadUrl = <?php echo json_encode(adminUrl('upload-image.php?token=' . rawurlencode(csrfToken()) . '&response=json')); ?>;
        const editorImagePublicPrefix = <?php echo json_encode(adminUrl('../assets/uploads/')); ?>;
        const blogEditor = CKEDITOR.replace('editor', {
            baseHref: new URL(<?php echo json_encode(adminUrl('../')); ?>, window.location.origin).href,
            filebrowserImageUploadUrl: <?php echo json_encode(adminUrl('upload-image.php?token=' . rawurlencode(csrfToken()))); ?>,
            filebrowserUploadMethod: 'form'
        });

        const editorImageLightbox = document.getElementById('editorImageLightbox');
        const editorImageUploadForm = document.getElementById('editorImageUploadForm');
        const editorImageFile = document.getElementById('editorImageFile');
        const editorImagePreview = document.getElementById('editorImagePreview');
        const editorImageStatus = document.getElementById('editorImageUploadStatus');
        const insertEditorImage = document.getElementById('insertEditorImage');
        let editorImagePreviewUrl = '';

        function openEditorImageLightbox() {
            editorImageUploadForm.reset();
            editorImagePreview.removeAttribute('src');
            editorImagePreview.style.display = 'none';
            editorImageStatus.textContent = '';
            if (editorImagePreviewUrl) {
                URL.revokeObjectURL(editorImagePreviewUrl);
                editorImagePreviewUrl = '';
            }
            editorImageLightbox.showModal();
        }

        blogEditor.on('instanceReady', function () {
            const imageCommand = blogEditor.getCommand('image');
            if (imageCommand) {
                imageCommand.exec = openEditorImageLightbox;
            }
        });

        function closeEditorImageLightbox() {
            editorImageLightbox.close();
        }

        document.getElementById('closeEditorImageLightbox').addEventListener('click', closeEditorImageLightbox);
        document.getElementById('cancelEditorImageUpload').addEventListener('click', closeEditorImageLightbox);
        editorImageFile.addEventListener('change', () => {
            const file = editorImageFile.files[0];
            if (editorImagePreviewUrl) {
                URL.revokeObjectURL(editorImagePreviewUrl);
                editorImagePreviewUrl = '';
            }
            if (!file) {
                editorImagePreview.removeAttribute('src');
                editorImagePreview.style.display = 'none';
                return;
            }
            editorImagePreviewUrl = URL.createObjectURL(file);
            editorImagePreview.src = editorImagePreviewUrl;
            editorImagePreview.style.display = 'block';
        });

        editorImageUploadForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const file = editorImageFile.files[0];
            if (!file) {
                editorImageStatus.textContent = 'Choose an image first.';
                return;
            }

            const formData = new FormData();
            formData.append('upload', file);
            formData.append('csrf_token', editorImageToken);
            insertEditorImage.disabled = true;
            editorImageStatus.textContent = 'Uploading image...';
            try {
                const response = await fetch(editorImageUploadUrl, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });
                const result = await response.json();
                const imageUrl = new URL(result.url || '', window.location.origin);
                if (!response.ok || result.uploaded !== 1 || imageUrl.origin !== window.location.origin
                    || !(result.url || '').startsWith(editorImagePublicPrefix)
                    || !/[a-f0-9]{32}\.(?:jpe?g|png|gif|webp)$/i.test(imageUrl.pathname)) {
                    throw new Error(result.error?.message || 'The image could not be uploaded.');
                }

                const image = blogEditor.document.createElement('img');
                image.setAttribute('src', imageUrl.href);
                image.setAttribute('alt', document.getElementById('editorImageAlt').value.trim());
                image.setAttribute('style', 'max-width:100%;height:auto');
                blogEditor.insertElement(image);
                blogEditor.focus();
                closeEditorImageLightbox();
            } catch (error) {
                editorImageStatus.textContent = error.message || 'The image could not be uploaded.';
            } finally {
                insertEditorImage.disabled = false;
            }
        });

        document.querySelectorAll('.admin-association-search').forEach((search) => {
            search.addEventListener('input', () => {
                const query = search.value.trim().toLocaleLowerCase();
                search.closest('.admin-association-list').querySelectorAll('.admin-association-option').forEach((option) => {
                    option.hidden = !option.textContent.trim().toLocaleLowerCase().includes(query);
                });
            });
        });

        document.getElementById('blogTitle').addEventListener('input', function () {
            if (!document.getElementById('blogSlug').value || document.getElementById('blogSlug').getAttribute('data-auto') === 'true') {
                const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
                document.getElementById('blogSlug').value = slug;
                document.getElementById('blogSlug').setAttribute('data-auto', 'true');
            }
        });
    </script>

<?php else: ?>

    <style>
        .blog-detail-row {
            cursor: pointer;
            transition: background-color 160ms ease;
        }
        .blog-detail-row:hover {
            background-color: #f4fbf6;
        }
        .blog-detail-row:focus {
            outline: 2px solid #27ae60;
            outline-offset: -2px;
        }
        .blog-detail-lightbox {
            width: min(980px, calc(100% - 2rem));
            max-width: none;
            max-height: calc(100% - 2rem);
            padding: 0;
            border: 0;
            border-radius: 1rem;
            overflow: hidden;
            color: #25312a;
            background: #fff;
            box-shadow: 0 2rem 5rem rgba(15, 23, 42, 0.3);
            animation: blog-detail-enter 180ms ease-out;
        }
        .blog-detail-lightbox::backdrop {
            background: rgba(16, 27, 20, 0.5);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .blog-detail-lightbox .modal-content {
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 2rem);
            border: 0;
            border-radius: 0;
        }
        .blog-detail-lightbox .modal-body {
            padding: 1.75rem;
            overflow-y: auto;
        }
        .blog-detail-lightbox .modal-header {
            align-items: flex-start;
            gap: 1rem;
            padding: 1.5rem 1.75rem;
            border-bottom: 1px solid #e8eee9;
            background: linear-gradient(135deg, #f3fbf5, #fff 75%);
        }
        .blog-detail-heading {
            min-width: 0;
        }
        .blog-detail-eyebrow {
            display: block;
            margin-bottom: 0.35rem;
            color: #25864c;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .blog-detail-lightbox .modal-title {
            margin: 0;
            color: #1d2c22;
            font-size: clamp(1.2rem, 2.4vw, 1.65rem);
            font-weight: 700;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }
        .blog-detail-lightbox .btn-close {
            flex: 0 0 auto;
            padding: 0.7rem;
            border-radius: 50%;
            background-color: #e8f3eb;
            opacity: 0.8;
        }
        .blog-detail-lightbox .btn-close:hover {
            background-color: #d8ebdd;
            opacity: 1;
        }
        .blog-detail-image {
            display: none;
            width: 100%;
            max-height: 340px;
            object-fit: cover;
            border-radius: 0.75rem;
            background: #f1f5f2;
            box-shadow: 0 0.5rem 1.5rem rgba(25, 55, 35, 0.1);
        }
        .blog-detail-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
            margin: 0 0 1.75rem;
        }
        .blog-detail-meta > div {
            min-width: 0;
            padding: 0.85rem 1rem;
            border: 1px solid #e8eee9;
            border-radius: 0.65rem;
            background: #f8faf8;
        }
        .blog-detail-meta dt {
            margin-bottom: 0.3rem;
            color: #77827a;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .blog-detail-meta dd {
            margin: 0;
            color: #26362b;
            font-size: 0.92rem;
            font-weight: 600;
            overflow-wrap: anywhere;
        }
        .blog-detail-section {
            margin-top: 1.5rem;
        }
        .blog-detail-section h3 {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin: 0 0 0.7rem;
            color: #26362b;
            font-size: 0.95rem;
            font-weight: 700;
        }
        .blog-detail-section h3::before {
            width: 0.25rem;
            height: 1rem;
            border-radius: 1rem;
            background: #2ecc71;
            content: "";
        }
        .blog-detail-description,
        .blog-detail-content {
            color: #4b5a50;
            font-size: 0.95rem;
            line-height: 1.75;
        }
        .blog-detail-description {
            margin: 0;
            padding: 1rem 1.1rem;
            border-left: 3px solid #2ecc71;
            border-radius: 0 0.5rem 0.5rem 0;
            background: #f5faf6;
        }
        .blog-detail-content {
            min-height: 3rem;
            padding: 1.1rem 1.2rem;
            border: 1px solid #e8eee9;
            border-radius: 0.65rem;
            background: #fff;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }
        .blog-detail-lightbox .modal-footer {
            gap: 0.6rem;
            padding: 1rem 1.75rem;
            border-top: 1px solid #e8eee9;
            background: #fbfcfb;
        }
        .blog-detail-lightbox .modal-footer .btn {
            min-width: 110px;
            border-radius: 0.55rem;
            font-weight: 600;
        }
        @keyframes blog-detail-enter {
            from { opacity: 0; transform: translateY(10px) scale(0.985); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @media (max-width: 640px) {
            .blog-detail-lightbox {
                width: calc(100% - 1rem);
                max-height: calc(100% - 1rem);
                border-radius: 0.75rem;
            }
            .blog-detail-lightbox .modal-content {
                max-height: calc(100vh - 1rem);
            }
            .blog-detail-lightbox .modal-header,
            .blog-detail-lightbox .modal-body {
                padding: 1.1rem;
            }
            .blog-detail-meta {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.55rem;
                margin-bottom: 1.25rem;
            }
            .blog-detail-meta > div {
                padding: 0.7rem;
            }
            .blog-detail-lightbox .modal-footer {
                padding: 0.85rem 1.1rem;
            }
        }
    </style>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-blog me-2"></i> All Blog Posts</span>
            <a href="<?php echo htmlspecialchars(adminUrl('blogs.php?action=create')); ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> New Post
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="blogsTable" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Tag</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

        <dialog class="blog-detail-lightbox" id="blogDetailModal" aria-labelledby="blogDetailTitle">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="blog-detail-heading">
                        <span class="blog-detail-eyebrow">Blog preview</span>
                        <h2 class="modal-title" id="blogDetailTitle"></h2>
                    </div>
                    <button type="button" class="btn-close" data-modal-close aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="blog-detail-image mb-4" id="blogDetailImage" alt="">
                    <dl class="blog-detail-meta">
                        <div><dt>Author</dt><dd id="blogDetailAuthor"></dd></div>
                        <div><dt>Category</dt><dd id="blogDetailCategory"></dd></div>
                        <div><dt>Tag</dt><dd id="blogDetailTag"></dd></div>
                        <div><dt>Status</dt><dd id="blogDetailStatus"></dd></div>
                        <div><dt>Featured</dt><dd id="blogDetailFeatured"></dd></div>
                        <div><dt>Created</dt><dd id="blogDetailCreated"></dd></div>
                        <div><dt>Slug</dt><dd id="blogDetailSlug"></dd></div>
                    </dl>
                    <section class="blog-detail-section">
                        <h3>Short description</h3>
                        <p class="blog-detail-description" id="blogDetailDescription"></p>
                    </section>
                    <section class="blog-detail-section">
                        <h3>Full content</h3>
                        <div class="blog-detail-content" id="blogDetailContent"></div>
                    </section>
                    <section class="blog-detail-section">
                        <h3>SEO details</h3>
                        <dl class="blog-detail-meta mb-0">
                            <div><dt>SEO title</dt><dd id="blogDetailSeoTitle"></dd></div>
                            <div><dt>SEO description</dt><dd id="blogDetailSeoDescription"></dd></div>
                            <div><dt>SEO keywords</dt><dd id="blogDetailSeoKeywords"></dd></div>
                        </dl>
                    </section>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" id="blogDetailUpdate" href="#">
                        <i class="fas fa-edit me-1"></i> Update
                    </a>
                    <button type="button" class="btn btn-secondary" data-modal-close>Close</button>
                </div>
            </div>
        </dialog>
    </div>

    <script>
        (() => {
            const modalElement = document.getElementById('blogDetailModal');
            const fields = {
                title: 'blogDetailTitle',
                author: 'blogDetailAuthor',
                category: 'blogDetailCategory',
                tag: 'blogDetailTag',
                status: 'blogDetailStatus',
                featured: 'blogDetailFeatured',
                created: 'blogDetailCreated',
                slug: 'blogDetailSlug',
                shortDescription: 'blogDetailDescription',
                content: 'blogDetailContent',
                seoTitle: 'blogDetailSeoTitle',
                seoDescription: 'blogDetailSeoDescription',
                seoKeywords: 'blogDetailSeoKeywords'
            };

            function showBlogDetails(row) {
                const blog = JSON.parse(row.dataset.blog);
                Object.entries(fields).forEach(([key, id]) => {
                    document.getElementById(id).textContent = blog[key] || '—';
                });

                const image = document.getElementById('blogDetailImage');
                if (blog.image && !blog.image.split('/').includes('..') && /^assets\/(?:img\/blog|uploads)\/[a-zA-Z0-9_./-]+\.(?:jpe?g|png|webp)$/i.test(blog.image)) {
                    image.src = '../' + blog.image;
                    image.alt = blog.title || 'Blog featured image';
                    image.style.display = 'block';
                } else {
                    image.removeAttribute('src');
                    image.alt = '';
                    image.style.display = 'none';
                }

                document.getElementById('blogDetailUpdate').href =
                    <?php echo json_encode(adminUrl('blogs.php?action=edit&id=')); ?> + encodeURIComponent(blog.id);
                modalElement.showModal();
            }

            const tableElement = document.getElementById('blogsTable');
            tableElement.addEventListener('click', (event) => {
                const row = event.target.closest('tr.blog-detail-row');
                if (row && !event.target.closest('a, button, form')) {
                    showBlogDetails(row);
                }
            });
            tableElement.addEventListener('keydown', (event) => {
                const row = event.target.closest('tr.blog-detail-row');
                if (row && (event.key === 'Enter' || event.key === ' ') && event.target === row) {
                    event.preventDefault();
                    showBlogDetails(row);
                }
            });

            modalElement.querySelectorAll('[data-modal-close]').forEach((button) => {
                button.addEventListener('click', () => modalElement.close());
            });
            modalElement.addEventListener('click', (event) => {
                if (event.target === modalElement) {
                    modalElement.close();
                }
            });
        })();
    </script>

<?php endif; ?>

<?php
if (!isset($_GET['action']) || !in_array($_GET['action'], ['create', 'edit'], true)) {
    $extraScripts = '<script>window.initAdminDataTable("#blogsTable", "blogs", {columns:Array.from({length:8}, (_, index) => ({data:"cells." + index})), order:[[6,"desc"]], columnDefs:[{targets:[0,5,7],orderable:false,searchable:false}]});</script>';
}
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>