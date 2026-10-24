<style>
    .news-section-2 .news-card-items-2 .news-image {
        aspect-ratio: 9 / 11;
    }

    .news-section-2 .news-card-items-2 .news-image>img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
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
                <h1 class="text-white wow fadeInUp" data-wow-delay=".3s">Blog Grid</h1>
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
                    Blog Grid
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- News Section-2 Start -->
<section class="news-section-2 section-padding fix">
    <div class="container">
        <div class="section-title text-center d-none">
            <span class="sub-title-2">
                <img src="assets/img/home-2/icon/04.svg" alt="img"> our complete farming latest news and blog <img
                    src="assets/img/home-2/icon/04.svg" alt="img" class="ms-2">
            </span>
            <h2 class="text-anim">
                Our Organic News & Blog
            </h2>
        </div>
        <div class="row g-4">
            <?php if (!empty($publishedBlogs)): ?>
                <?php foreach ($publishedBlogs as $index => $blog): ?>
                    <?php
                    $title = (string) ($blog['title'] ?? '');
                    $slug = (string) ($blog['slug'] ?? '');
                    $image = trim((string) ($blog['featured_image'] ?? '')) ?: 'assets/img/inner-page/news/02.jpg';
                    $description = trim((string) ($blog['short_description'] ?? ''));
                    $dateValue = $blog['published_at'] ?? $blog['created_at'] ?? null;
                    $timestamp = $dateValue ? strtotime((string) $dateValue) : false;
                    $date = $timestamp === false ? '' : date('d M, Y', $timestamp);
                    $delay = ['.3s', '.5s', '.7s'][$index % 3];
                    ?>
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="<?php echo $delay; ?>">
                        <div class="news-card-items-2 style-inner">
                            <div class="news-image">
                                <img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>"
                                    alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy">
                                <a href="blogs-details.php?slug=<?php echo rawurlencode($slug); ?>"
                                    class="theme-btn theme-btn-3">Read More</a>
                                <div class="news-content">
                                    <h3><a
                                            href="blogs-details.php?slug=<?php echo rawurlencode($slug); ?>"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></a>
                                    </h3>
                                    <p><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>
                                    <div class="tag">
                                        <?php if ($date !== ''): ?>
                                            <span><i
                                                    class="fa-regular fa-calendar"></i><?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?></span>
                                        <?php endif; ?>
                                        <span><i class="fa-regular fa-comment"></i>25, Comments</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                    <div class="news-card-items-2 style-inner">
                        <div class="news-image">
                            <img src="assets/img/home-2/news/01.jpg" alt="img">
                            <a href="blogs-details.php?slug=sustainable-agriculture" class="theme-btn theme-btn-3">
                                Read More
                            </a>
                            <div class="news-content">
                                <h3>
                                    <a href="blogs-details.php?slug=sustainable-agriculture">
                                        Lipsum has been the industry’s
                                        standard dummy text
                                    </a>
                                </h3>
                                <p>
                                    We prioritize eco-friendly practices that protect natural resources and ensure long-term
                                    agricultural productivity for.
                                </p>
                                <div class="tag">
                                    <span>
                                        <i class="fa-regular fa-calendar"></i>
                                        22 Dec, 2026
                                    </span>
                                    <span>
                                        <i class="fa-regular fa-comment"></i>
                                        25, Comments
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                    <div class="news-card-items-2 style-inner">
                        <div class="news-image">
                            <img src="assets/img/home-2/news/02.jpg" alt="img">
                            <a href="blogs-details.php?slug=benefits-of-organic-food" class="theme-btn theme-btn-3">
                                Read More
                            </a>
                            <div class="news-content">
                                <h3>
                                    <a href="blogs-details.php?slug=benefits-of-organic-food">
                                        The True Benefits of Choosing Organic for Your Family
                                    </a>
                                </h3>
                                <p>
                                    We prioritize eco-friendly practices that protect natural resources and ensure long-term
                                    agricultural productivity for.
                                </p>
                                <div class="tag">
                                    <span>
                                        <i class="fa-regular fa-calendar"></i>
                                        22 Dec, 2026
                                    </span>
                                    <span>
                                        <i class="fa-regular fa-comment"></i>
                                        25, Comments
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                    <div class="news-card-items-2 style-inner">
                        <div class="news-image">
                            <img src="assets/img/home-2/news/03.jpg" alt="img">
                            <a href="blogs-details.php?slug=farm-to-home-freshness" class="theme-btn theme-btn-3">
                                Read More
                            </a>
                            <div class="news-content">
                                <h3>
                                    <a href="blogs-details.php?slug=farm-to-home-freshness">
                                        Behind the Scenes: Ensuring Freshness from Farm to Your Home
                                    </a>
                                </h3>
                                <p>
                                    We prioritize eco-friendly practices that protect natural resources and ensure long-term
                                    agricultural productivity for.
                                </p>
                                <div class="tag">
                                    <span>
                                        <i class="fa-regular fa-calendar"></i>
                                        22 Dec, 2026
                                    </span>
                                    <span>
                                        <i class="fa-regular fa-comment"></i>
                                        25, Comments
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                    <div class="news-card-items-2 style-inner">
                        <div class="news-image">
                            <img src="assets/img/home-3/team/08.jpg" alt="img">
                            <a href="blogs-details.php?slug=traditional-farming-wisdom" class="theme-btn theme-btn-3">
                                Read More
                            </a>
                            <div class="news-content">
                                <h3>
                                    <a href="blogs-details.php?slug=traditional-farming-wisdom">
                                        Bringing Traditional Farming Wisdom Into Modern Agriculture
                                    </a>
                                </h3>
                                <p>
                                    We prioritize eco-friendly practices that protect natural resources and ensure long-term
                                    agricultural productivity for.
                                </p>
                                <div class="tag">
                                    <span>
                                        <i class="fa-regular fa-calendar"></i>
                                        22 Dec, 2026
                                    </span>
                                    <span>
                                        <i class="fa-regular fa-comment"></i>
                                        25, Comments
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".5s">
                    <div class="news-card-items-2 style-inner">
                        <div class="news-image">
                            <img src="assets/img/home-3/team/09.jpg" alt="img">
                            <a href="blogs-details.php?slug=natural-pest-control" class="theme-btn theme-btn-3">
                                Read More
                            </a>
                            <div class="news-content">
                                <h3>
                                    <a href="blogs-details.php?slug=natural-pest-control">
                                        Natural Pest Control That Protects Both Crops and Nature
                                    </a>
                                </h3>
                                <p>
                                    We prioritize eco-friendly practices that protect natural resources and ensure long-term
                                    agricultural productivity for.
                                </p>
                                <div class="tag">
                                    <span>
                                        <i class="fa-regular fa-calendar"></i>
                                        22 Dec, 2026
                                    </span>
                                    <span>
                                        <i class="fa-regular fa-comment"></i>
                                        25, Comments
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".7s">
                    <div class="news-card-items-2 style-inner">
                        <div class="news-image">
                            <img src="assets/img/home-3/team/10.jpg" alt="img">
                            <a href="blogs-details.php?slug=understanding-crop-insurance" class="theme-btn theme-btn-3">
                                Read More
                            </a>
                            <div class="news-content">
                                <h3>
                                    <a href="blogs-details.php?slug=understanding-crop-insurance">
                                        Breaking Down Barriers to Crop Insurance
                                    </a>
                                </h3>
                                <p>
                                    We prioritize eco-friendly practices that protect natural resources and ensure long-term
                                    agricultural productivity for.
                                </p>
                                <div class="tag">
                                    <span>
                                        <i class="fa-regular fa-calendar"></i>
                                        22 Dec, 2026
                                    </span>
                                    <span>
                                        <i class="fa-regular fa-comment"></i>
                                        25, Comments
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="page-nav-wrap text-center">
            <ul>
                <li><a class="page-numbers" href="#"><i class="fa-solid fa-arrow-up-left"></i></a></li>
                <li class="active"><a class="page-numbers" href="#">01</a></li>
                <li><a class="page-numbers" href="#">02</a></li>
                <li><a class="page-numbers" href="#">03</a></li>
                <li><a class="page-numbers" href="#"><i class="fa-solid fa-arrow-up-right"></i></a></li>
            </ul>
        </div>
    </div>
</section>