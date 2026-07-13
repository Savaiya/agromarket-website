<?php 
require_once 'admin/config.php';
$db = getDB();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch Blog Post
$stmt = $db->prepare("SELECT b.*, c.name as category_name FROM blogs b LEFT JOIN blog_categories c ON b.category_id = c.id WHERE b.id = ? AND b.status = 'published'");
$stmt->execute([$id]);
$blog = $stmt->fetch();

if (!$blog) {
    header('Location: blog.php');
    exit;
}

// Set SEO Variables
$meta_title = !empty($blog['seo_title']) ? $blog['seo_title'] : $blog['title'] . " | AMPL Agro Market";
$meta_description = !empty($blog['seo_description']) ? $blog['seo_description'] : $blog['short_description'];
$meta_keywords = !empty($blog['seo_keywords']) ? $blog['seo_keywords'] : $blog['tags'];

include 'include/header.php'; 

// Fetch Categories
$categories = $db->query("SELECT c.*, (SELECT COUNT(*) FROM blogs b WHERE b.category_id = c.id AND b.status = 'published') as post_count FROM blog_categories c WHERE c.status = 'active' ORDER BY c.name ASC")->fetchAll();

// Fetch Recent Posts
$recentPosts = $db->query("SELECT * FROM blogs WHERE status = 'published' AND id != $id ORDER BY created_at DESC LIMIT 3")->fetchAll();
?>
    <!--==============================
    Breadcumb
    ============================== -->
    <div class="breadcumb-wrapper" style="background-color: #2ecc71; padding: 60px 0;">
        <div class="container z-index-common">
            <div class="breadcumb-content text-center">
                <h1 class="breadcumb-title text-white">BLOG DETAILS</h1>
            </div>
            <div class="breadcumb-menu-wrap text-center">
                <ul class="breadcumb-menu list-unstyled d-flex justify-content-center gap-2 text-white">
                    <li><a href="index.php" class="text-white text-decoration-none">Home</a></li>
                    <li>/</li>
                    <li>BLOG DETAILS</li>
                </ul>
            </div>
        </div>
    </div>
    <!--==============================
    Blog Details Area
    ==============================-->
    <section class="vs-blog-wrapper blog-details space-top space-extra-bottom">
        <div class="container">
            <div class="row gx-60">
                <div class="col-lg-8">
                    <div class="vs-blog blog-single">
                        <div class="blog-content">
                            
                            <?php if ($blog['featured_image']): ?>
                            <div class="mb-30">
                                <img src="<?php echo $blog['featured_image']; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" style="width:100%; border-radius: 10px;">
                            </div>
                            <?php endif; ?>
                            
                            <div class="blog-meta mb-3">
                                <span class="ms-3 text-muted"><i class="fal fa-calendar-alt me-1"></i> <?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                                <span class="ms-3 text-muted"><i class="fal fa-user me-1"></i> by <?php echo htmlspecialchars($blog['author'] ?: 'Admin'); ?></span>
                            </div>
                            
                            <h2 class="blog-title mb-4" style="font-weight: 700;"><?php echo htmlspecialchars($blog['title']); ?></h2>
                            
                            <div class="blog-text-content" style="line-height: 1.8; color: #444;">
                                <?php echo $blog['content']; ?>
                            </div>
                        </div>
                        
                        <div class="share-links clearfix mt-5">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-xl-auto">
                                    <span class="share-links-title">Tags:</span>
                                    <div class="tagcloud">
                                        <?php 
                                        $tags = explode(',', $blog['tags'] ?? 'Agriculture,AgroMarket');
                                        foreach ($tags as $tag): 
                                        ?>
                                        <a href="#"><?php echo trim($tag); ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="col-xl-auto text-xl-end">
                                    <span class="share-links-title">Share:</span>
                                    <ul class="social-links">
                                        <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <aside class="sidebar-area">
                        <div class="widget">
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
                        <div class="widget widget_categories">
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
                    </aside>
                </div>
            </div>
        </div>
    </section>
<?php include 'include/footer.php'; ?>