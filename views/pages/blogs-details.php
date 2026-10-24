<?php
$fallbackSets = $fallbackSets ?? (require dirname(__DIR__, 2) . '/includes/blog-fallbacks.php');
$fallbackBlogs = array_merge($fallbackSets['standard'], $fallbackSets['grid']);
$slug = $slug ?? ($_GET['slug'] ?? '');
$slug = is_string($slug) ? trim($slug) : '';
$blog = $blog ?? null;
$suggestedBlogs = $suggestedBlogs ?? [];

if ($blog) {
    $suggestedSlugs = array_column($suggestedBlogs, 'slug');
    foreach ($fallbackBlogs as $fallbackBlog) {
        if (count($suggestedBlogs) >= 5) {
            break;
        }
        if ($fallbackBlog['slug'] !== $slug && !in_array($fallbackBlog['slug'], $suggestedSlugs, true)) {
            $suggestedBlogs[] = $fallbackBlog;
            $suggestedSlugs[] = $fallbackBlog['slug'];
        }
    }
} else {
    $suggestedBlogs = array_slice($fallbackBlogs, 0, 5);
}

$blogTitle = (string) ($blog['title'] ?? 'Blog story unavailable');
$blogImage = trim((string) ($blog['featured_image'] ?? '')) ?: 'assets/img/inner-page/news/02.jpg';
$blogDateValue = $blog['published_at'] ?? $blog['created_at'] ?? null;
$blogTimestamp = $blogDateValue ? strtotime((string) $blogDateValue) : false;
$blogDate = $blogTimestamp === false ? '' : date('F j, Y', $blogTimestamp);
$blogCategories = $blog['categories'] ?? [];
if ($blogCategories === [] && !empty($blog['category_name'])) {
    $blogCategories[] = ['name' => $blog['category_name']];
}
$blogTags = $blog['tags'] ?? [];
if ($blogTags === [] && trim((string) ($blog['tag'] ?? '')) !== '') {
    $blogTags[] = ['name' => $blog['tag']];
}
$blogCategoryNames = implode(', ', array_column($blogCategories, 'name'));
$blogTagNames = implode(', ', array_column($blogTags, 'name'));
$blogDescription = trim((string) ($blog['short_description'] ?? ''));
$blogContent = (string) ($blog['content'] ?? '');
$blogContent = preg_replace('/<\s*br\s*\/?>/i', "\n", $blogContent);
$blogContent = preg_replace('/<\s*\/\s*(p|div|h[1-6]|li|blockquote)\s*>/i', "\n\n", $blogContent);
$blogContent = trim(html_entity_decode(strip_tags($blogContent), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
?>

<style>
    .blog-story-page {
        --story-ink: #24352a;
        --story-muted: #68766c;
        --story-green: #2d7548;
        --story-line: #e2e9e2;
        padding: 64px 0 88px;
        background: #f6f8f4;
    }
    .blog-story-breadcrumb {
        background-image: none !important;
        background-color: #28573d;
    }
    .blog-story-breadcrumb .top-image {
        display: none;
    }
    .blog-story-breadcrumb .page-heading {
        min-height: 220px;
        padding: 52px 0;
    }
    .blog-story-breadcrumb .breadcrumb-sub-title h1 {
        margin: 0;
        color: #fff !important;
        font-size: 40px;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }
    .blog-story-breadcrumb .breadcrumb-items,
    .blog-story-breadcrumb .breadcrumb-items a {
        color: rgba(255, 255, 255, 0.88);
    }
    .blog-story-breadcrumb .breadcrumb-items li:last-child {
        color: #e8c58f;
    }
    .blog-story-breadcrumb .breadcrumb-items {
        flex-wrap: wrap;
    }
    .blog-story-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 330px;
        gap: 56px;
        align-items: start;
    }
    .blog-story-article {
        min-width: 0;
        padding: 0 0 36px;
        border-bottom: 1px solid var(--story-line);
    }
    .blog-story-taxonomy {
        display: grid;
        gap: 9px;
        margin-bottom: 20px;
    }
    .blog-story-taxonomy-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 7px;
    }
    .blog-story-taxonomy-label {
        color: var(--story-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .blog-story-taxonomy-chip {
        display: inline-block;
        padding: 7px 11px;
        border-radius: 4px;
        color: #3f5145;
        background: #f3f7f3;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }
    .blog-story-taxonomy-chip:hover {
        color: #fff;
        background: var(--story-green);
    }
    .blog-story-article h1 {
        max-width: 900px;
        margin: 0 0 20px;
        color: var(--story-ink);
        font-size: 42px;
        font-weight: 700;
        line-height: 1.16;
        overflow-wrap: anywhere;
    }
    .blog-story-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 22px;
        margin-bottom: 28px;
        color: var(--story-muted);
        font-size: 14px;
    }
    .blog-story-meta span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .blog-story-meta i {
        color: var(--story-green);
    }
    .blog-story-hero {
        width: 100%;
        aspect-ratio: 16 / 9;
        margin-bottom: 30px;
        border-radius: 4px;
        background: #e8eee8;
        object-fit: cover;
    }
    .blog-story-summary {
        margin: 0 0 28px;
        padding: 4px 0 4px 20px;
        border-left: 3px solid #c68140;
        color: #435349;
        font-size: 18px;
        line-height: 1.75;
    }
    .blog-story-body {
        color: #48574d;
        font-size: 16px;
        line-height: 1.9;
        overflow-wrap: anywhere;
    }
    .blog-story-sidebar {
        position: sticky;
        top: 110px;
    }
    .blog-story-sidebar h2 {
        margin: 0 0 8px;
        color: var(--story-ink);
        font-size: 22px;
        font-weight: 700;
    }
    .blog-story-sidebar-intro {
        margin: 0 0 20px;
        color: var(--story-muted);
        font-size: 14px;
        line-height: 1.6;
    }
    .blog-suggestion {
        display: grid;
        grid-template-columns: 82px minmax(0, 1fr);
        gap: 14px;
        padding: 15px 0;
        border-top: 1px solid var(--story-line);
    }
    .blog-suggestion img {
        width: 82px;
        height: 74px;
        border-radius: 3px;
        background: #e8eee8;
        object-fit: cover;
    }
    .blog-suggestion h3 {
        display: -webkit-box;
        margin: 0 0 7px;
        overflow: hidden;
        color: var(--story-ink);
        font-size: 15px;
        font-weight: 700;
        line-height: 1.4;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }
    .blog-suggestion h3 a {
        color: inherit;
    }
    .blog-suggestion h3 a:hover {
        color: var(--story-green);
    }
    .blog-suggestion time {
        color: var(--story-muted);
        font-size: 12px;
    }
    .blog-story-back {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        margin-top: 28px;
        color: var(--story-green);
        font-size: 14px;
        font-weight: 700;
    }
    .blog-story-not-found {
        grid-column: 1 / -1;
        padding: 44px 0;
        color: var(--story-ink);
    }
    .blog-story-not-found h1 {
        margin: 0 0 12px;
        font-size: 32px;
    }
    .blog-story-not-found p {
        color: var(--story-muted);
    }
    @media (max-width: 991px) {
        .blog-story-layout {
            grid-template-columns: minmax(0, 1fr) 280px;
            gap: 32px;
        }
        .blog-story-article h1 {
            font-size: 36px;
        }
    }
    @media (max-width: 767px) {
        .blog-story-page {
            padding: 42px 0 60px;
        }
        .blog-story-layout {
            grid-template-columns: 1fr;
            gap: 44px;
        }
        .blog-story-sidebar {
            position: static;
        }
        .blog-story-article h1 {
            font-size: 32px;
        }
        .blog-story-summary {
            font-size: 16px;
        }
    }
</style>

<div class="breadcrumb-wrapper bg-cover blog-story-breadcrumb">
    <div class="top-image">
        <img src="assets/img/breadcrumb-2.jpg" alt="">
    </div>
    <div class="container">
        <div class="page-heading">
            <div class="breadcrumb-sub-title">
                <h1 class="text-white">Blog Story</h1>
            </div>
            <ul class="breadcrumb-items">
                <li><a href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                <li>:</li>
                <li><a href="blogs.php">Blog</a></li>
                <li>:</li>
                <li><?php echo htmlspecialchars($blogTitle, ENT_QUOTES, 'UTF-8'); ?></li>
            </ul>
        </div>
    </div>
</div>

<main class="blog-story-page">
    <div class="container">
        <?php if (!$blog): ?>
            <div class="blog-story-not-found">
                <h1><?php echo $blogLoadError ? 'Story temporarily unavailable' : 'Story not found'; ?></h1>
                <p><?php echo $blogLoadError ? 'Please try again shortly.' : 'This published story could not be found.'; ?></p>
                <a class="blog-story-back" href="blogs.php"><i class="fa-solid fa-arrow-left"></i> Browse all stories</a>
            </div>
        <?php else: ?>
            <div class="blog-story-layout">
                <article class="blog-story-article">
                    <h1><?php echo htmlspecialchars($blogTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
                    <div class="blog-story-meta">
                        <?php if ($blogDate !== ''): ?>
                            <span><i class="fa-regular fa-calendar"></i><?php echo htmlspecialchars($blogDate, ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                        <span><i class="fa-regular fa-user"></i><?php echo htmlspecialchars((string) ($blog['author'] ?? 'AgroMarket'), ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <?php if ($blogCategories !== [] || $blogTags !== []): ?>
                        <div class="blog-story-taxonomy">
                            <?php if ($blogCategories !== []): ?>
                                <div class="blog-story-taxonomy-row">
                                    <span class="blog-story-taxonomy-label">Categories</span>
                                    <?php foreach ($blogCategories as $category): ?>
                                        <?php if (!empty($category['slug'])): ?>
                                            <a class="blog-story-taxonomy-chip" href="blogs.php?category=<?php echo rawurlencode((string) $category['slug']); ?>"><?php echo htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8'); ?></a>
                                        <?php else: ?>
                                            <span class="blog-story-taxonomy-chip"><?php echo htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($blogTags !== []): ?>
                                <div class="blog-story-taxonomy-row">
                                    <span class="blog-story-taxonomy-label">Tags</span>
                                    <?php foreach ($blogTags as $tag): ?>
                                        <a class="blog-story-taxonomy-chip" href="blogs.php?search=<?php echo rawurlencode((string) $tag['name']); ?>"><?php echo htmlspecialchars((string) $tag['name'], ENT_QUOTES, 'UTF-8'); ?></a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <img class="blog-story-hero" src="<?php echo htmlspecialchars($blogImage, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($blogTitle, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if ($blogDescription !== ''): ?>
                        <p class="blog-story-summary"><?php echo htmlspecialchars($blogDescription, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                    <div class="blog-story-body">
                        <?php echo nl2br(htmlspecialchars($blogContent, ENT_QUOTES, 'UTF-8')); ?>
                    </div>
                    <a class="blog-story-back" href="blogs.php"><i class="fa-solid fa-arrow-left"></i> Back to all stories</a>
                </article>
                <aside class="blog-story-sidebar" aria-labelledby="suggestedStoriesTitle">
                    <h2 id="suggestedStoriesTitle">Suggested Stories</h2>
                    <p class="blog-story-sidebar-intro">More ideas and updates from across agriculture.</p>
                    <?php foreach (array_slice($suggestedBlogs, 0, 5) as $suggestedBlog): ?>
                        <?php
                        $suggestedImage = trim((string) ($suggestedBlog['featured_image'] ?? '')) ?: 'assets/img/inner-page/news/02.jpg';
                        $suggestedDateValue = $suggestedBlog['published_at'] ?? $suggestedBlog['created_at'] ?? null;
                        $suggestedTimestamp = $suggestedDateValue ? strtotime((string) $suggestedDateValue) : false;
                        ?>
                        <article class="blog-suggestion">
                            <a href="blogs-details.php?slug=<?php echo rawurlencode((string) $suggestedBlog['slug']); ?>" aria-hidden="true" tabindex="-1">
                                <img src="<?php echo htmlspecialchars($suggestedImage, ENT_QUOTES, 'UTF-8'); ?>" alt="" loading="lazy">
                            </a>
                            <div>
                                <h3><a href="blogs-details.php?slug=<?php echo rawurlencode((string) $suggestedBlog['slug']); ?>"><?php echo htmlspecialchars((string) $suggestedBlog['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                <?php if ($suggestedTimestamp !== false): ?>
                                    <time datetime="<?php echo htmlspecialchars(date('Y-m-d', $suggestedTimestamp), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(date('M j, Y', $suggestedTimestamp), ENT_QUOTES, 'UTF-8'); ?></time>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </aside>
            </div>
        <?php endif; ?>
    </div>
</main>
