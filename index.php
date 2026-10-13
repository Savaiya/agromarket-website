<?php
$pageTitle = 'Home - Maize Farming';
require_once __DIR__ . '/admin/config.php';

$recentBlogs = [];
try {
    $recentBlogs = getDB()->query(
        "SELECT title, slug, short_description, featured_image, published_at, created_at
         FROM blogs
         WHERE status = 'published'
         ORDER BY COALESCE(published_at, created_at) DESC, created_at DESC, id DESC
         LIMIT 4"
    )->fetchAll();
} catch (PDOException $exception) {
    error_log('Homepage recent blogs could not be loaded from MySQL.');
} catch (RuntimeException $exception) {
    error_log('Homepage recent blogs could not be loaded.');
}

if (empty($recentBlogs)) {
    $recentBlogs = [
        [
            'title' => 'Product Sourcing',
            'short_description' => 'We carefully source products from trusted manufacturers, including our own brand and well-known market brands.',
            'featured_image' => 'assets/img/home-1/testimonial/03.png',
            'slug' => '',
        ],
        [
            'title' => 'Quality Check',
            'short_description' => 'Every product goes through a strict quality inspection to ensure safety, reliability, and performance.',
            'featured_image' => 'assets/img/home-1/testimonial/02.png',
            'slug' => '',
        ],
        [
            'title' => 'Easy Purchase',
            'short_description' => 'Customers can buy products easily and quickly, whether retail or bulk.',
            'featured_image' => 'assets/img/home-1/testimonial/05.png',
            'slug' => '',
        ],
        [
            'title' => 'Smart Storage',
            'short_description' => 'Products are stored in well-managed warehouses to maintain freshness and quality.',
            'featured_image' => 'assets/img/home-1/testimonial/05.png',
            'slug' => '',
        ],
    ];
}

require_once 'includes/header.php';
?>


<!-- Hero Section-2 Start -->
<section class="hero-section hero-2 fix">
    <div class="hero-portfolio-revealing-slider">
        <div class="hero-portfolio-revealing-slider-slides">
            <div class="hero-portfolio-revealing-slide s-active">
                <div class="hero-portfolio-revealing-slide-inner bg-cover"
                    data-background="assets/img/home-1/hero/main.webp">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <div class="hero-portfolio-revealing-slide-content">

                                    <div class="hero-button" data-animation="fadeInUp" data-delay="1.5s">
                                        <a href="contact.php" class="theme-btn-2">Explore Our Farm</a>
                                        <a href="contact.php" class="theme-btn-2 border-btn">contact us</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-portfolio-revealing-slide">
                <div class="hero-portfolio-revealing-slide-inner bg-cover"
                    data-background="assets/img/home-1/hero/main2.webp">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <div class="hero-portfolio-revealing-slide-content">

                                    <div class="hero-button" data-animation="fadeInUp" data-delay="1.5s">
                                        <a href="contact.php" class="theme-btn-2">Explore Our Farm</a>
                                        <a href="contact.php" class="theme-btn-2 border-btn">contact us</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-portfolio-revealing-slide">
                <div class="hero-portfolio-revealing-slide-inner bg-cover"
                    data-background="assets/img/home-1/hero/main3.webp">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <div class="hero-portfolio-revealing-slide-content">

                                    <div class="hero-button" data-animation="fadeInUp" data-delay="1.5s">
                                        <a href="contact.php" class="theme-btn-2">Explore Our Farm</a>
                                        <a href="contact.php" class="theme-btn-2 border-btn">contact us</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-portfolio-revealing-slider-control">
            <img src="assets/img/home-2/icon/01.svg" alt="img" class="hero-portfolio-revealing-slider-control-line">
        </div>
        <div class="hero-portfolio-revealing-slider-control hero-portfolio-revealing-slider-control-right m-right">
            <img src="assets/img/home-2/icon/12.svg" alt="img" class="hero-portfolio-revealing-slider-control-line">
        </div>
    </div>
    <div class="hero-border-item">
        <div class="hero-bottom-item">
            <h2>What Makes Us Different</h2>
            <div class="hero-list">
                <span><i class="fa-solid fa-circle-check"></i> 100% organic</span>
                <span><i class="fa-solid fa-circle-check"></i> Chemical-free farming</span>
                <span><i class="fa-solid fa-circle-check"></i> Quality control</span>
                <span><i class="fa-solid fa-circle-check"></i> Fresh from farm</span>
            </div>
        </div>
        <div class="pagi-item">
            <div class="dot-number">
                <span class="dot-num">
                    <span>02</span>
                </span>
                <span class="dot-num">
                    <span class="style-2">03</span>
                </span>
            </div>
        </div>
    </div>
</section>

<!-- Feature Section-2 Start -->
<section class="feature-section-2 section-padding pb-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-xl-4 col-lg-6 col-md-6 tp_fade_anim" data-delay=".3" data-fade-from="left">
                <div class="feature-box-item-2">
                    <div class="maize-shape">
                        <img src="assets/img/home-1/icon/about-icon-1-1.png" alt="img">
                    </div>
                    <div class="masking-shape">
                        <img src="assets/img/home-2/mask.png" alt="img">
                        <div class="masking-2">
                            <img src="assets/img/home-2/mask-2.png" alt="img">
                        </div>
                        <div class="icon">
                            <img src="assets/img/home-2/icon/07.svg" alt="img">
                        </div>
                    </div>
                    <div class="content">
                        <h3>
                            Growing the Future
                        </h3>
                        <p>
                            Cultivating sustainable agriculture for a healthier and greener tomorrow.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 tp_fade_anim" data-delay=".5" data-fade-from="left">
                <div class="feature-box-item-2">
                    <div class="maize-shape">
                        <img src="assets/img/home-1/icon/about-icon-1-2.png" alt="img">
                    </div>
                    <div class="masking-shape">
                        <img src="assets/img/home-2/mask.png" alt="img">
                        <div class="masking-2">
                            <img src="assets/img/home-2/mask-2.png" alt="img">
                        </div>
                        <div class="icon">
                            <img src="assets/img/home-2/icon/08.svg" alt="img">
                        </div>
                    </div>
                    <div class="content">
                        <h3>
                            Empowering Farmers
                        </h3>
                        <p>
                            Supporting farmers with better resources, knowledge, and opportunities to thrive.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 tp_fade_anim" data-delay=".7" data-fade-from="left">
                <div class="feature-box-item-2">
                    <div class="maize-shape">
                        <img src="assets/img/home-1/icon/hero-icon-1.png" alt="img">
                    </div>
                    <div class="masking-shape">
                        <img src="assets/img/home-2/mask.png" alt="img">
                        <div class="masking-2">
                            <img src="assets/img/home-2/mask-2.png" alt="img">
                        </div>
                        <div class="icon">
                            <img src="assets/img/home-2/icon/09.svg" alt="img">
                        </div>
                    </div>
                    <div class="content">
                        <h3>
                            Our Motto
                        </h3>
                        <p>
                            Quality, sustainability, and innovation at every step from farm to future.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section Start -->
<section class="about-section-2 section-padding fix pt-0">
    <div class="left-shape">
        <img src="assets/img/home-2/about-shape-1.png" alt="img">
    </div>
    <div class="right-shape">
        <img src="assets/img/home-2/about-shape-2.png" alt="img">
    </div>
    <div class="maize-shape float-bob-y">
        <img src="assets/img/home-1/maize-1.png" alt="img">
    </div>
    <div class="maize-shape-2 float-bob-y">
        <img src="assets/img/home-1/maize-1.png" alt="img">
    </div>
    <div class="container">
        <div class="section-title-area">
            <div class="section-title">
                <span class="sub-title-2">
                    <img src="assets/img/home-2/icon/04.svg" alt="img"> About Us
                </span>
                <h2 class="text-anim">
                    AMPL Agro Market <br>
                    Private Limited
                </h2>
            </div>
            <div class="content">
                <p class="about-text">AMPL Agro Market Private LimitedTrusted Partner in Agricultural Growth
                    <br>
                    Founded by Parv Yadav, AMPL Agro Market Pvt. Ltd. is a fast-growing agri-trading and <br> branded
                    agri-input company committed to empowering agri-retailers, farmers, and <br> agricultural
                    enterprises across India.

                </p>
                <a href="about.php" class="link-btn-2">more about us</a>
            </div>
        </div>
        <div class="about-wrapper-2">
            <div class="count-wrap">
                <div class="count-item-2">
                    <h2><span class="count">20</span>k</h2>
                    <p>
                        Hectares under sustainable
                        cultivation
                    </p>
                </div>
                <div class="count-item-2 style-top">
                    <h2><span class="count">98</span>%</h2>
                    <p>
                        Customer Satisfaction based on
                        service feedback
                    </p>
                </div>
                <div class="count-item-2">
                    <h2><span class="count">12</span>k</h2>
                    <p>
                        Farmers partnered with
                        across regions
                    </p>
                </div>
            </div>
            <div class="trac-wrap">
                <div class="tractor-image float-bob-x">
                    <img src="assets/img/home-1/maize-2.png" alt="img">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why-Choose-Us-2 Section Start -->
<section class="why-choose-us-section-2 section-padding fix theme-bg-2">
    <div class="left-shape float-bob-x">
        <img src="assets/img/home-2/maize-shape.png" alt="img">
    </div>
    <div class="right-shape float-bob-x">
        <img src="assets/img/home-2/maize-shape-2.png" alt="img">
    </div>
    <div class="maize-3">
        <img src="assets/img/home-1/maize-2.png" alt="img">
    </div>
    <div class="container">
        <div class="why-choose-us-wrapper-2">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="why-choose-us-image float-bob-y">
                        <img src="assets/img/home-2/maize.png" alt="img">
                        <div class="circle-shape">
                            <img src="assets/img/home-2/circle.png" alt="img">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="why-choose-us-content">
                        <div class="section-title mb-0">
                            <span class="sub-title-2 style-2">
                                <img src="assets/img/home-2/icon/05.svg" alt="img"> Who We Are
                            </span>
                            <h2 class="text-anim">
                                AgroMarket Private Limited is a leading agri-input trading company…
                            </h2>
                        </div>

                        <div class="choose-us-box wow fadeInUp" data-wow-delay=".5s">
                            <div class="content">
                                <h3>Trusted Agri-Input Solutions</h3>
                                <span>
                                    AgroMarket Private Limited is a trusted agri-input trading company and manufacturer
                                    of AMPL own-brand products.
                                </span>
                            </div>
                            <div class="content style-2">
                                <h3>Premium Quality</h3>
                                <span>
                                    We supply high-quality agri inputs to retailers, dealers, and bulk buyers through
                                    a strong offline distribution network across India, driven by quality, trust, and
                                    long-term partnerships.
                                </span>
                            </div>
                        </div>
                        <div class="choose-us-btn-item wow fadeInUp" data-wow-delay=".3s">
                            <a href="contact.php" class="theme-btn">
                                Explore Our Farm
                            </a>
                            <div class="icon-item">
                                <div class="icon">
                                    <img src="assets/img/home-2/icon/06.svg" alt="img">
                                </div>
                                <div class="cont">
                                    <h4>Call us :</h4>
                                    <h5>
                                        <a href="tel: +91 99939 55051"> +91 99939 55051</a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Section Start -->
<section class="service-section-2 section-padding fix">
    <div class="mask-image wt-about-title2">
        <img src="assets/img/home-2/service/image.png" alt="img" class="animated-img">
    </div>
    <div class="container">
        <div class="row g-4">
            <div class="col-xl-4 col-lg-6 col-md-6 tp_fade_anim" data-delay=".3" data-fade-from="left">
                <div class="service-bg-item bg-cover" style="background-image: url(assets/img/home-2/service/01.jpg);">
                    <div class="service-cont">
                        <div class="section-title mb-0">
                            <span class="sub-title-2">
                                <img src="assets/img/home-2/icon/04.svg" alt="img"> our services
                            </span>
                            <h2 class="text-anim text-white">
                                Organic Farm <br>
                                Services
                            </h2>
                        </div>
                        <a href="product.php" class="theme-btn-2">
                            View All Products
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 tp_fade_anim" data-delay=".5" data-fade-from="left">
                <div class="service-card-items-2">
                    <div class="top-content">
                        <div class="tag-list">
                            <span>Empowerment</span>
                            <span>Agriculture</span>
                            <span>Technology</span>
                        </div>
                        <p>
                            Founded by Parv Yadav, AMPL empowers Indian farmers and retailers
                            through sustainable, high-growth agri-input trading.
                        </p>
                    </div>
                    <div class="service-image">
                        <img src="assets/img/home-2/service/02.jpg" alt="img">
                    </div>
                    <h3>
                        Trusted Agricultural Growth Partner
                    </h3>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 tp_fade_anim" data-delay=".7" data-fade-from="left">
                <div class="service-card-items-2">
                    <div class="top-content">
                        <div class="tag-list">
                            <span>Empowerment</span>
                            <span>Agriculture</span>
                            <span>Technology</span>
                        </div>
                        <p>
                            AMPL provides high-quality insecticides and bio-plant protectors,
                            including specialized brands like Blast 505 and Fire-101.
                        </p>
                    </div>
                    <div class="service-image">
                        <img src="assets/img/home-2/service/03.jpg" alt="img">
                    </div>
                    <h3>
                        Premium Crop Protection Products
                    </h3>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 tp_fade_anim" data-delay=".3" data-fade-from="left">
                <div class="service-card-items-2">
                    <div class="top-content">
                        <div class="tag-list">
                            <span>Empowerment</span>
                            <span>Agriculture</span>
                            <span>Technology</span>
                        </div>
                        <p>
                            The company manufactures advanced bio-fertilizers and growth
                            regulators like Shaktishali to significantly enhance farm productivity.
                        </p>
                    </div>
                    <div class="service-image">
                        <img src="assets/img/home-2/service/04.jpg" alt="img">
                    </div>
                    <h3>
                        Innovative Fertilizers and Growth Regulators
                    </h3>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 tp_fade_anim" data-delay=".5" data-fade-from="left">
                <div class="service-card-items-2">
                    <div class="top-content">
                        <div class="tag-list">
                            <span>Empowerment</span>
                            <span>Agriculture</span>
                            <span>Technology</span>
                        </div>
                        <p>
                            This digital platform ensures transparent pricing and faster
                            delivery by selling products directly to end customers.
                        </p>
                    </div>
                    <div class="service-image">
                        <img src="assets/img/home-2/service/05.jpg" alt="img">
                    </div>
                    <h3>
                        Kisanogram: Direct-to-Customer Sales
                    </h3>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 tp_fade_anim" data-delay=".7" data-fade-from="left">
                <div class="service-image-item">
                    <div class="swiper service-image-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="slider-thumb">
                                    <img src="assets/img/home-1/p1.webp" alt="img">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="slider-thumb">
                                    <img src="assets/img/home-1/p3.webp" alt="img">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="slider-thumb">
                                    <img src="assets/img/home-1/p6.webp" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-dot4">
                        <div class="dot2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Faq Section Start -->
<section class="faq-section-2 section-padding fix bg-cover"
    style="background-image: url(assets/img/home-2/faq-bg.jpg);">
    <div class="faq-shape float-bob-x">
        <img src="assets/img/home-2/maize-6.png" alt="img">
    </div>
    <div class="container">
        <div class="faq-wrapper-2">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="faq-content">
                        <div class="section-title mb-0">
                            <span class="sub-title-2">
                                <img src="assets/img/home-2/icon/04.svg" alt="img"> Case studies
                            </span>
                            <h2 class="text-anim text-white">
                                Latest Case studies
                            </h2>
                        </div>
                        <div class="progress-wrap">
                            <div class="pro-items">
                                <div class="pro-head">
                                    <h3 class="title">
                                        Discover real-world examples of how AMPL’s strategic vision and tailored
                                        agricultural solutions have driven rapid growth, efficiency, and long-term
                                        success for
                                        farmers and retailers across the ecosystem.
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-box">
                        <div class="faq-items mt-0">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item wow fadeInUp" data-wow-delay=".3s">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            Case studies 2023
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample" role="region">
                                        <div class="accordion-body">
                                            <p>
                                                Foundation & Bootstrapping In 2023, Parv Yadav founded AMPL Agro
                                                Market Pvt. Ltd. as a bootstrapped venture in Jabalpur, Madhya Pradesh.
                                                The company
                                                prioritized building a strong offline distribution network and
                                                agri-input trading
                                                system, successfully establishing branches in Indore and Jabalpur to
                                                gain the trust of
                                                local farmers.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item wow fadeInUp" data-wow-delay=".5s">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                            Case studies 2024
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo" data-bs-parent="#accordionExample" role="region">
                                        <div class="accordion-body">
                                            <p>
                                                Rapid Scaling & Expansion The company saw rapid growth in 2024 by
                                                shifting focus from trading to manufacturing its own branded products
                                                like “Fire-101”
                                                and “Shaktishali.” This expansion into proprietary seeds and fertilizers
                                                strengthened
                                                their market position and allowed for higher margins through direct
                                                retailer
                                                relationships.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item wow fadeInUp" data-wow-delay=".7s">
                                    <h2 class="accordion-header" id="headingthree">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapsethree"
                                            aria-expanded="false" aria-controls="collapsethree">
                                            Case studies 2025
                                        </button>
                                    </h2>
                                    <div id="collapsethree" class="accordion-collapse collapse"
                                        aria-labelledby="headingthree" data-bs-parent="#accordionExample" role="region">
                                        <div class="accordion-body">
                                            <p>
                                                Rapid Scaling & Expansion The company saw rapid growth in 2024 by
                                                shifting focus from trading to manufacturing its own branded products
                                                like “Fire-101”
                                                and “Shaktishali.” This expansion into proprietary seeds and fertilizers
                                                strengthened
                                                their market position and allowed for higher margins through direct
                                                retailer
                                                relationships.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<br>

<!--==============================
  
    ============================== -->
<section class="testimonial-layout1 space">
    <div class="container">
        <div class="row align-items-center">

            <!-- Left Content -->
            <div class="col-lg-6">
                <div class="title-area wow fadeInUp wow-animated" data-wow-delay="0.3s">
                    <span class="sec-subtitle .text-danger fw-bold">
                        We Don’t Just Trade — We Build Agricultural Growth
                    </span>

                    <p class="project-text">
                        At AMPL Agro Market Pvt. Ltd., we go beyond conventional agri-trading.
                        We act as a reliable growth partner for agri-retailers, dealers, and bulk buyers by
                        delivering high-quality agricultural inputs, transparent pricing, and a strong nationwide
                        supply network.
                    </p>
                </div>
                <br>

                <!-- New Swiper -->
                <div class="swiper testimonial-slider-2">
                    <div class="swiper-wrapper mb-4">
                        <!-- Vision -->
                        <div class="swiper-slide">
                            <div class="testi-style1">
                                <div class="auther-inner">
                                    <div class="auther-content">
                                        <h4 class="name mb-2">Vision</h4>
                                        <span class="designation">
                                            <h6>Organic Real Farmer</h6>
                                        </span>

                                        <div class="rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <p class="testi-text">
                                    To become India’s most trusted and efficient agri-input aggregator,
                                    empowering agricultural businesses to grow sustainably, adapt to evolving
                                    market needs, and lead with confidence.
                                </p>
                            </div>
                        </div>

                        <!-- Mission -->
                        <div class="swiper-slide">
                            <div class="testi-style1">
                                <div class="auther-inner">
                                    <div class="auther-content">
                                        <h4 class="name mb-2">Mission</h4>
                                        <span class="designation">
                                            <h6>Organic Real Farmer</h6>
                                        </span>

                                        <div class="rating">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <p class="testi-text">
                                    To provide high-quality, cost-effective agricultural inputs while
                                    continuously expanding our network, innovating supply solutions, and
                                    collaborating with key stakeholders to strengthen India’s agri-input ecosystem.
                                </p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Dots -->
                    <div class="swiper-pagination testimonial-pagination"></div>
                </div>
            </div>

            <!-- Right Image -->
            <div class="col-lg-6">
                <div class="testi-img text-center">
                    <img draggable="true" src="assets/img/home-1/farm1.jpg" alt="Agricultural fields" class="img-fluid">
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Recent Blogs Start -->
<section class="testimonial-section-2 section-padding pt-0 fix bg-cover"
    style="background-image: url(assets/img/home-2/testimonial-bg.jpg);">
    <div class="client-man">
        <img src="assets/img/home-2/client.png" alt="img">
    </div>
    <div class="container">
        <div class="testimonial-top-item">
            <div class="section-title">
                <span class="sub-title-2">
                    <img src="assets/img/home-2/icon/04.svg" alt="img"> our Steps
                </span>
                <h2 class="text-anim">
                    Recent <br>Blogs
                </h2>
            </div>
            <div class="right-item">
                <div class="star">
                    <img src="assets/img/home-2/star.png" alt="img">
                </div>
                <div class="arrow-button">
                    <button class="array-prev">
                        <img src="assets/img/home-2/icon/01.svg" alt="img">
                    </button>
                    <button class="array-next">
                        <img src="assets/img/home-2/icon/02.svg" alt="img">
                    </button>
                </div>
            </div>
        </div>
        <div class="testimonial-wrapper-2">
            <div class="swiper testimonial-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($recentBlogs as $recentBlog): ?>
                        <?php
                        $recentBlogTitle = trim((string) ($recentBlog['title'] ?? ''));
                        $recentBlogImage = trim((string) ($recentBlog['featured_image'] ?? ''));
                        if ($recentBlogImage === '') {
                            $recentBlogImage = 'assets/img/inner-page/news/02.jpg';
                        }
                        $recentBlogSlug = trim((string) ($recentBlog['slug'] ?? ''));
                        $recentBlogUrl = $recentBlogSlug === ''
                            ? 'blogs.php'
                            : 'blogs.php?slug=' . rawurlencode($recentBlogSlug);
                        $recentBlogDateValue = $recentBlog['published_at'] ?? $recentBlog['created_at'] ?? null;
                        $recentBlogTimestamp = $recentBlogDateValue ? strtotime((string) $recentBlogDateValue) : false;
                        $recentBlogDate = $recentBlogTimestamp === false
                            ? ''
                            : date('d M Y', $recentBlogTimestamp);
                        ?>
                        <div class="swiper-slide">
                            <article class="testimonial-card-item-2 recent-blog-card">
                                <div class="content">
                                    <div class="client-image">
                                        <img src="<?php echo htmlspecialchars($recentBlogImage, ENT_QUOTES, 'UTF-8'); ?>"
                                            alt="<?php echo htmlspecialchars($recentBlogTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <h3><?php echo htmlspecialchars($recentBlogTitle, ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <?php if ($recentBlogDate !== ''): ?>
                                        <span><?php echo htmlspecialchars($recentBlogDate, ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (trim((string) ($recentBlog['short_description'] ?? '')) !== ''): ?>
                                    <p><?php echo htmlspecialchars((string) $recentBlog['short_description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php endif; ?>
                                <a href="<?php echo htmlspecialchars($recentBlogUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                    class="theme-btn theme-btn-3 recent-blog-read-more">
                                    Read More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Brand Section Start -->
<div class="brand-section-2 section-padding pt-0 fix">
    <div class="container">
        <div class="swiper brand-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="brand-box-1">
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/01.png" alt="img">
                        </span>
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/08.png" alt="img">
                        </span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="brand-box-1">
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/02.png" alt="img">
                        </span>
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/09.png" alt="img">
                        </span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="brand-box-1">
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/03.png" alt="img">
                        </span>
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/10.png" alt="img">
                        </span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="brand-box-1">
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/04.png" alt="img">
                        </span>
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/11.png" alt="img">
                        </span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="brand-box-1">
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/05.png" alt="img">
                        </span>
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/12.png" alt="img">
                        </span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="brand-box-1">
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/06.png" alt="img">
                        </span>
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/13.png" alt="img">
                        </span>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="brand-box-1">
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/07.png" alt="img">
                        </span>
                        <span class="brand-img-1">
                            <img src="assets/img/home-1/brand/14.png" alt="img">
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once 'includes/footer.php'; ?>