<?php
$showSelectedBlog = $showSelectedBlog ?? false;
$selectedBlogLoadError = $selectedBlogLoadError ?? false;
$blogLoadError = $blogLoadError ?? false;
$usingFallbackBlogs = $usingFallbackBlogs ?? empty($blogs);
?>
<style>
    .blog-listing-card[data-blog-url] {
        cursor: pointer;
    }
    .blog-listing-card[data-blog-url]:focus-visible {
        outline: 3px solid #2d7548;
        outline-offset: 4px;
    }
    .blog-inline-metadata {
        display: grid;
        gap: 7px;
        max-width: 100%;
        margin: 10px 0 14px;
    }
    .blog-inline-metadata-row {
        display: grid;
        grid-template-columns: 76px minmax(0, 1fr);
        align-items: center;
        gap: 7px;
        min-width: 0;
    }
    .blog-inline-metadata-label {
        color: #68766c;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .blog-inline-metadata-items {
        display: flex;
        gap: 6px;
        min-width: 0;
        overflow: hidden;
        white-space: nowrap;
    }
    .blog-inline-chip {
        flex: 0 0 auto;
        max-width: 100%;
        overflow: hidden;
        padding: 6px 10px;
        border-radius: 4px;
        color: #3f5145;
        background: #f3f7f3;
        font-size: 12px;
        line-height: 1.2;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .blog-inline-chip:hover {
        color: #fff;
        background: #2d7548;
    }
    @media (max-width: 575px) {
        .blog-inline-metadata-row {
            grid-template-columns: 66px minmax(0, 1fr);
        }
    }
</style>

                <!-- Breadcrumb Section Start -->
                <div class="breadcrumb-wrapper bg-cover" style="background-image: url('assets/img/breadcrumb.png');">
                    <div class="top-image">
                        <img src="assets/img/breadcrumb-2.jpg" alt="img">
                    </div>
                    <div class="container">
                        <div class="page-heading">
                            <div class="breadcrumb-sub-title">
                                <h1 class="text-white wow fadeInUp" data-wow-delay=".3s">Blog Standard</h1>
                            </div>
                            <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".5s">
                                <li>
                                    <a href="index.php">
                                        <i class="fa-solid fa-house"></i> Home
                                    </a>
                                </li>
                                <li>
                                    :
                                </li>
                                <li>
                                   Blog Standard
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
               
                <!-- Blogs-Details Section Start -->
                <section class="news-standard-section blog-index-section section-padding">
                    <div class="container">
                        <div class="news-standard-wrapper">
                            <div class="row g-4">
                                <div class="col-12 col-lg-8">
                                    <div class="news-standard-items">
                                        <div class="blog-listing-heading">
                                            <span>From the AgroMarket journal</span>
                                            <h2>Ideas for better growing</h2>
                                            <p>Practical insights, fresh perspectives, and stories from across agriculture.</p>
                                        </div>
                                        <?php if ($showSelectedBlog && $selectedBlogLoadError): ?>
                                            <p class="alert alert-warning" role="alert">
                                                This blog summary is temporarily unavailable. Please try again later.
                                            </p>
                                        <?php elseif ($showSelectedBlog && empty($blogs)): ?>
                                            <p class="alert alert-info" role="status">
                                                This published blog could not be found. <a href="blogs.php">Browse all blogs</a>.
                                            </p>
                                        <?php elseif ($blogLoadError): ?>
                                            <p class="alert alert-warning" role="status">
                                                Blog posts could not be loaded from the database. Showing sample posts instead.
                                            </p>
                                        <?php elseif ($usingFallbackBlogs): ?>
                                            <p class="alert alert-info" role="status">
                                                No published blog posts are available yet. Showing sample posts.
                                            </p>
                                        <?php elseif (($blogSearch !== '' || $blogCategory !== '') && empty($blogs)): ?>
                                            <p class="alert alert-info" role="status">
                                                No published blog posts matched the selected filters.
                                            </p>
                                        <?php endif; ?>
                                        <?php foreach ($blogs as $index => $blog): ?>
                                            <?php
                                            $image = trim((string) ($blog['featured_image'] ?? ''));
                                            if ($image === '') {
                                                $image = 'assets/img/inner-page/news/02.jpg';
                                            }
                                            $description = trim((string) ($blog['short_description'] ?? ''));
                                            $dateValue = $blog['published_at'] ?? $blog['created_at'] ?? null;
                                            $timestamp = $dateValue ? strtotime((string) $dateValue) : false;
                                            $displayDate = $timestamp === false ? '' : date('d F Y', $timestamp);
                                            $blogCategories = $blog['categories'] ?? [];
                                            if ($blogCategories === [] && !empty($blog['category_name'])) {
                                                $blogCategories[] = [
                                                    'name' => $blog['category_name'],
                                                    'slug' => $blog['category_slug'] ?? '',
                                                ];
                                            }
                                            $blogTags = $blog['tags'] ?? [];
                                            if ($blogTags === [] && trim((string) ($blog['tag'] ?? '')) !== '') {
                                                $blogTags[] = ['name' => $blog['tag']];
                                            }
                                            $blogSlug = (string) ($blog['slug'] ?? '');
                                            ?>
                                            <article class="news-card-items-4 blog-listing-card<?php echo $index === count($blogs) - 1 ? ' mb-0' : ''; ?>"<?php if (!$showSelectedBlog): ?> data-blog-url="blogs-details.php?slug=<?php echo rawurlencode($blogSlug); ?>" tabindex="0" aria-label="Open story: <?php echo htmlspecialchars((string) $blog['title'], ENT_QUOTES, 'UTF-8'); ?>"<?php endif; ?>>
                                                <div class="news-image">
                                                    <img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>"
                                                        alt="<?php echo htmlspecialchars((string) $blog['title'], ENT_QUOTES, 'UTF-8'); ?>">
                                                </div>
                                                <div class="news-content">
                                                    <ul class="date-list">
                                                        <?php if ($displayDate !== ''): ?>
                                                            <li>
                                                                <i class="fa-solid fa-calendar-days"></i>
                                                                <?php echo htmlspecialchars($displayDate, ENT_QUOTES, 'UTF-8'); ?>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                    <div class="blog-inline-metadata">
                                                        <?php if ($blogCategories !== []): ?>
                                                            <div class="blog-inline-metadata-row">
                                                                <span class="blog-inline-metadata-label">Categories</span>
                                                                <div class="blog-inline-metadata-items">
                                                        <?php foreach ($blogCategories as $blogCategoryItem): ?>
                                                            <?php if (!empty($blogCategoryItem['slug'])): ?>
                                                                    <a class="blog-inline-chip" href="blogs.php?category=<?php echo rawurlencode((string) $blogCategoryItem['slug']); ?>"><?php echo htmlspecialchars((string) $blogCategoryItem['name'], ENT_QUOTES, 'UTF-8'); ?></a>
                                                            <?php else: ?>
                                                                    <span class="blog-inline-chip"><?php echo htmlspecialchars((string) $blogCategoryItem['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($blogTags !== []): ?>
                                                            <div class="blog-inline-metadata-row">
                                                                <span class="blog-inline-metadata-label">Tags</span>
                                                                <div class="blog-inline-metadata-items">
                                                        <?php foreach ($blogTags as $blogTagItem): ?>
                                                                    <a class="blog-inline-chip" href="blogs.php?search=<?php echo rawurlencode((string) $blogTagItem['name']); ?>"><?php echo htmlspecialchars((string) $blogTagItem['name'], ENT_QUOTES, 'UTF-8'); ?></a>
                                                        <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <h2><?php echo htmlspecialchars((string) $blog['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                                                    <?php if ($description !== ''): ?>
                                                        <p><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>
                                                    <?php endif; ?>
                                                    <?php if ($showSelectedBlog): ?>
                                                        <a href="blogs.php" class="theme-btn theme-btn-3 blog-read-more">
                                                            Back to all blogs <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a class="theme-btn theme-btn-3 blog-read-more"
                                                            href="blogs-details.php?slug=<?php echo rawurlencode($blogSlug); ?>">
                                                            Read full story <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                        <?php if (!$usingFallbackBlogs && $totalPages > 1): ?>
                                            <nav class="page-nav-wrap text-center" aria-label="Blog pages">
                                                <ul>
                                                    <?php if ($currentPage > 1): ?>
                                                        <li>
                                                            <a class="page-numbers" href="?<?php echo $blogSearch !== '' ? 'search=' . rawurlencode($blogSearch) . '&amp;' : ''; ?><?php echo $blogCategory !== '' ? 'category=' . rawurlencode($blogCategory) . '&amp;' : ''; ?>page=<?php echo $currentPage - 1; ?>" aria-label="Previous page">
                                                                <i class="fa-solid fa-arrow-up-left" aria-hidden="true"></i>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                                                        <li<?php echo $page === $currentPage ? ' class="active"' : ''; ?>>
                                                            <a class="page-numbers" href="?<?php echo $blogSearch !== '' ? 'search=' . rawurlencode($blogSearch) . '&amp;' : ''; ?><?php echo $blogCategory !== '' ? 'category=' . rawurlencode($blogCategory) . '&amp;' : ''; ?>page=<?php echo $page; ?>"<?php echo $page === $currentPage ? ' aria-current="page"' : ''; ?>>
                                                                <?php echo sprintf('%02d', $page); ?>
                                                            </a>
                                                        </li>
                                                    <?php endfor; ?>
                                                    <?php if ($currentPage < $totalPages): ?>
                                                        <li>
                                                            <a class="page-numbers" href="?<?php echo $blogSearch !== '' ? 'search=' . rawurlencode($blogSearch) . '&amp;' : ''; ?><?php echo $blogCategory !== '' ? 'category=' . rawurlencode($blogCategory) . '&amp;' : ''; ?>page=<?php echo $currentPage + 1; ?>" aria-label="Next page">
                                                                <i class="fa-solid fa-arrow-up-right" aria-hidden="true"></i>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </nav>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-12">
                                    <div class="main-sideber blog-sidebar sticky-style">
                                        <div class="single-sideber-widget">
                                            <div class="widget-title">
                                                <h3>Search</h3>
                                            </div>
                                            <div class="search-widget">
                                                <form action="blogs.php" method="get" role="search">
                                                    <label class="visually-hidden" for="blogSidebarSearch">Search blog posts or categories</label>
                                                    <input id="blogSidebarSearch" type="search" name="search"
                                                        value="<?php echo htmlspecialchars($blogSearch, ENT_QUOTES, 'UTF-8'); ?>"
                                                        placeholder="Search posts or categories...">
                                                    <?php if ($blogCategory !== ''): ?>
                                                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($blogCategory, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?php endif; ?>
                                                    <button type="submit" aria-label="Search blog posts"><i class="fa-solid fa-magnifying-glass"></i></button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Product Categories Start -->
                                        <div class="single-sideber-widget">
                                            <div class="widget-title">
                                                <h3>Categories</h3>
                                            </div>
                                            <ul class="category-list">
                                                <?php foreach ($categories as $category): ?>
                                                    <li<?php echo $blogCategory === (string) $category['slug'] ? ' class="active"' : ''; ?>>
                                                        <a href="blogs.php?category=<?php echo rawurlencode((string) $category['slug']); ?>">
                                                            <?php echo htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8'); ?>
                                                        </a>
                                                        <span>(<?php echo (int) $category['post_count']; ?>)</span>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <div class="single-sideber-widget">
                                            <div class="widget-title">
                                                <h3>Recent Post</h3>
                                            </div>
                                            <div class="recent-post-area">
                                                <?php foreach ($recentPosts as $recentPost): ?>
                                                    <?php
                                                    $recentTitle = (string) ($recentPost['title'] ?? '');
                                                    $recentSlug = (string) ($recentPost['slug'] ?? '');
                                                    $recentImage = trim((string) ($recentPost['featured_image'] ?? ''));
                                                    if ($recentImage === '') {
                                                        $recentImage = 'assets/img/inner-page/news/post-1.jpg';
                                                    }
                                                    $recentDateValue = $recentPost['published_at'] ?? $recentPost['created_at'] ?? null;
                                                    $recentTimestamp = $recentDateValue ? strtotime((string) $recentDateValue) : false;
                                                    $recentDate = $recentTimestamp === false ? '' : date('F j, Y', $recentTimestamp);
                                                    ?>
                                                    <div class="recent-items">
                                                        <a class="recent-thumb" href="blogs-details.php?slug=<?php echo rawurlencode($recentSlug); ?>" tabindex="-1" aria-hidden="true">
                                                            <img src="<?php echo htmlspecialchars($recentImage, ENT_QUOTES, 'UTF-8'); ?>"
                                                                alt="" loading="lazy">
                                                        </a>
                                                        <div class="recent-content">
                                                            <h4>
                                                                <a href="blogs-details.php?slug=<?php echo rawurlencode($recentSlug); ?>">
                                                                    <?php echo htmlspecialchars($recentTitle, ENT_QUOTES, 'UTF-8'); ?>
                                                                </a>
                                                            </h4>
                                                            <?php if ($recentDate !== ''): ?>
                                                                <time datetime="<?php echo htmlspecialchars(date('Y-m-d', (int) $recentTimestamp), ENT_QUOTES, 'UTF-8'); ?>">
                                                                    <?php echo htmlspecialchars($recentDate, ENT_QUOTES, 'UTF-8'); ?>
                                                                </time>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="single-sideber-widget mb-0">
                                            <div class="widget-title">
                                                <h3>Popular Tags</h3>
                                            </div>
                                            <div class="tagcloud">
                                                <?php if (!empty($popularTags)): ?>
                                                    <?php foreach (array_slice($popularTags, 0, 10) as $popularTag): ?>
                                                        <?php $tagName = trim((string) ($popularTag['tag'] ?? 'General')); if ($tagName === '') { $tagName = 'General'; } ?>
                                                        <a href="blogs.php?search=<?php echo rawurlencode($tagName); ?>"><?php echo htmlspecialchars($tagName, ENT_QUOTES, 'UTF-8'); ?></a>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <a href="blogs.php?search=General">General</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

<script>
    document.querySelectorAll('.blog-listing-card[data-blog-url]').forEach((card) => {
        card.addEventListener('click', (event) => {
            if (!event.target.closest('a, button, form')) {
                window.location.href = card.dataset.blogUrl;
            }
        });
        card.addEventListener('keydown', (event) => {
            if ((event.key === 'Enter' || event.key === ' ') && event.target === card) {
                event.preventDefault();
                window.location.href = card.dataset.blogUrl;
            }
        });
    });
</script>
