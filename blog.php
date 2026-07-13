<?php 
require_once 'admin/config.php';
include 'include/header.php'; 

$db = getDB();

// Fetch Categories
$categories = $db->query("SELECT c.*, (SELECT COUNT(*) FROM blogs b WHERE b.category_id = c.id AND b.status = 'published') as post_count FROM blog_categories c WHERE c.status = 'active' ORDER BY c.name ASC")->fetchAll();

// Fetch Recent Posts
$recentPosts = $db->query("SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC LIMIT 3")->fetchAll();

// Fetch Blog Posts (with pagination logic simplified)
$cat_id = isset($_GET['category']) ? intval($_GET['category']) : 0;
if ($cat_id > 0) {
    $stmt = $db->prepare("SELECT b.*, c.name as category_name FROM blogs b LEFT JOIN blog_categories c ON b.category_id = c.id WHERE b.status = 'published' AND b.category_id = ? ORDER BY b.created_at DESC");
    $stmt->execute([$cat_id]);
    $blogs = $stmt->fetchAll();
} else {
    $blogs = $db->query("SELECT b.*, c.name as category_name FROM blogs b LEFT JOIN blog_categories c ON b.category_id = c.id WHERE b.status = 'published' ORDER BY b.created_at DESC")->fetchAll();
}
?>

    <div class="breadcumb-wrapper" style="background-color: #2ecc71; padding: 60px 0;">
        <div class="container z-index-common">
            <div class="breadcumb-content text-center">
                <h1 class="breadcumb-title text-white">Our Blogs</h1>
            </div>
            <div class="breadcumb-menu-wrap text-center">
                <ul class="breadcumb-menu list-unstyled d-flex justify-content-center gap-2 text-white">
                    <li><a href="index.php" class="text-white text-decoration-none">Home</a></li>
                    <li>/</li>
                    <li>Our Blogs</li>
                </ul>
            </div>
        </div>
    </div>
    <!--==============================
    Blog Area
    ==============================-->
    <section class="vs-blog-wrapper space-top space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <?php if (!empty($blogs)): ?>
                        <?php foreach ($blogs as $blog): ?>
                        <div class="vs-blog blog-single">
                            <div class="blog-img">
                                <a href="blog-details.php?id=<?php echo $blog['id']; ?>">
                                    <img src="<?php echo $blog['featured_image'] ?: 'assets/img/index/blog-s-1-3.webp'; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" style="width:100%; max-height:450px; object-fit:cover;">
                                </a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <a href="blog.php?category=<?php echo $blog['category_id']; ?>"><i class="fal fa-tag"></i><?php echo htmlspecialchars($blog['category_name']); ?></a>
                                </div>
                                <h2 class="blog-title"><a href="blog-details.php?id=<?php echo $blog['id']; ?>"><?php echo htmlspecialchars($blog['title']); ?></a></h2>
                                <p class="blog-text"><?php echo htmlspecialchars($blog['short_description']); ?></p>
                                <div class="blog-inner-author">
                                    <img src="assets/img/blog/blog-auth-1-1.png" alt="blog author">
                                    by <a href="#"><?php echo htmlspecialchars($blog['author'] ?: 'Admin'); ?></a>
                                    <a href="#" class="blog-date"><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info">No blog posts found in this category.</div>
                    <?php endif; ?>
                    
                    <!-- Pagination (Static for now) -->
                    <div class="vs-pagination">
                        <ul>
                            <li class="arrow"><a href="#"><i class="fal fa-long-arrow-left"></i></a></li>
                            <li><a href="#" class="active">1</a></li>
                            <li class="arrow"><a href="#"><i class="fal fa-long-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <aside class="sidebar-area">
                        <div class="widget  ">
                            <h3 class="widget_title">Recent Posts</h3>
                            <div class="recent-post-wrap">
                                <?php foreach ($recentPosts as $rp): ?>
                                <div class="recent-post">
                                    <div class="media-img">
                                        <a href="blog-details.php?id=<?php echo $rp['id']; ?>">
                                            <img src="<?php echo $rp['featured_image'] ?: 'assets/img/blog/recent-post-1-1.jpg'; ?>" alt="Blog Image" style="width:80px; height:80px; object-fit:cover;">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="recent-post-meta">
                                            <a href="#"><?php echo date('M d, Y', strtotime($rp['created_at'])); ?></a>
                                        </div>
                                        <h4 class="post-title"><a class="text-inherit" href="blog-details.php?id=<?php echo $rp['id']; ?>"><?php echo htmlspecialchars($rp['title']); ?></a></h4>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="widget widget_categories   ">
                            <h3 class="widget_title">Categories</h3>
                            <ul>
                                <?php foreach ($categories as $cat): ?>
                                <li>
                                    <a href="blog.php?category=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></a>
                                    <span><?php echo $cat['post_count']; ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="widget widget_nav_menu   ">
                            <h3 class="widget_title">Useful Services</h3>
                            <div class="menu-all-pages-container footer-menu">
                                <ul class="menu">
                                    <li><a href="about.php">About Us</a></li>
                                    <li><a href="product.php">Our Products</a></li>
                                    <li><a href="contact.php">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
    
<?php include 'include/footer.php'; ?>