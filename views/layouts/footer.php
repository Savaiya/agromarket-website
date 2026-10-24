<?php
/**
 * views/layouts/footer.php
 * Master layout footer
 */
$whatsappUrl = 'https://wa.me/' . preg_replace('/\D+/', '', $sitePhone);
?>
<!-- Footer Section Start -->
<footer class="footer-section-2 footer-bg fix pb-0">
    <div class="left-shape float-bob-x">
        <img src="assets/img/home-2/maize-tree-2.png" alt="img">
    </div>
    <div class="right-shape float-bob-x">
        <img src="assets/img/home-2/maize-tree.png" alt="img">
    </div>
    <div class="container">
        <div class="footer-top-item">
            <div class="footer-logo wow fadeInUp" data-wow-delay=".2s">
                <a href="index.php">
                    <img class="w-25" src="assets/img/logo/logo.webp" alt="img">
                </a>
            </div>
            <div class="footer-right-item">
                <div class="social-icon wow fadeInUp" data-wow-delay=".4s">
                    <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="<?php echo htmlspecialchars($whatsappUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                </div>
                <div class="icon-item wow fadeInUp" data-wow-delay=".6s">
                    <div class="icon">
                        <img src="assets/img/home-2/icon/10.svg" alt="img">
                    </div>
                    <div class="cont">
                        <span>call for details</span>
                        <h4>
                            <a href="tel:<?php echo $sitePhone; ?>" class="footer-phone-number" style="white-space: nowrap;">&nbsp;<?php echo $sitePhone; ?></a>
                        </h4>
                    </div>
                </div>
                <div class="icon-item wow fadeInUp" data-wow-delay=".8s">
                    <div class="icon">
                        <img src="assets/img/home-2/icon/11.svg" alt="img">
                    </div>
                    <div class="cont">
                        <span>send us email</span>
                        <h4>
                            <a href="mailto:<?php echo $siteEmail; ?>"><?php echo $siteEmail; ?></a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-widget-wrapper footer-widget-wrapper-2">
            <div class="row">
                <div class="col-xl-5 col-md-7 col-lg-6 wow fadeInUp" data-wow-delay=".2s">
                    <div class="single-footer-widget">
                        <div class="footer-contact-image bg-cover" style="background-image: url(assets/img/home-2/vutta.jpg);">
                            <div class="footer-contact-content">
                                <h3> QR <?php echo $sitename; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-5 col-lg-4 ps-lg-5 wow fadeInUp" data-wow-delay=".4s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <h4>Our Services</h4>
                        </div>
                        <ul class="list-area">
                            <li><a href="product.php"><i class="fa-solid fa-chevron-right"></i> Seed Distribution</a></li>
                            <li><a href="product.php"><i class="fa-solid fa-chevron-right"></i> Pesticides</a></li>
                            <li><a href="product.php"><i class="fa-solid fa-chevron-right"></i> Fertilizers</a></li>
                            <li><a href="product.php"><i class="fa-solid fa-chevron-right"></i> Crop Protection</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-xl-2 ps-lg-5 col-md-6 col-lg-4 wow fadeInUp" data-wow-delay=".8s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <h4>Our Company</h4>
                        </div>
                        <ul class="list-area">
                            <li><a href="about.php"><i class="fa-solid fa-chevron-right"></i> About Us</a></li>
                            <li><a href="contact.php"><i class="fa-solid fa-chevron-right"></i> Our Farmers</a></li>
                            <li><a href="product.php"><i class="fa-solid fa-chevron-right"></i> Our Products</a></li>
                            <li><a href="contact.php"><i class="fa-solid fa-chevron-right"></i> Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-lg-2 wow fadeInUp" data-wow-delay=".6s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <h4>ISO Certificate</h4><br>
                            <a href="assets/img/iso.jpg" class="img-popup iso-lightbox-trigger" title="ISO Certificate">
                                <img class="w-100 iso-thumb" src="assets/img/iso.jpg" alt="ISO Certificate">
                                <span class="iso-zoom-icon"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom pb-0 d-flex justify-content-between mobile-credit">
            <div class="footer-wrapper">
                <p>&copy;<?php echo date('Y'); ?> <span><?php echo $sitename; ?></span>, All Rights Reserved.</p>
            </div>
            <div class="footer-wrapper z-3">
                <a href="https://crescitasoftware.com" target="_blank" rel="noopener noreferrer"><p>Created by Crescita Software</p></a>
            </div>
        </div>
    </div>
</footer>

</div><!-- /#smooth-content -->
</div><!-- /#smooth-wrapper -->

<style>
    .whatsapp-float {
        position: fixed;
        right: 30px;
        bottom: 92px;
        z-index: 9998;
        display: grid;
        width: 56px;
        height: 56px;
        place-items: center;
        border-radius: 50%;
        background: #25d366;
        box-shadow: 0 5px 18px rgba(15, 55, 31, 0.28);
        color: #fff;
        font-size: 31px;
        transition: transform 160ms ease, background-color 160ms ease;
    }
    .whatsapp-float:hover {
        transform: translateY(-3px);
        background: #1eae54;
        color: #fff;
    }
    .whatsapp-float:focus-visible {
        outline: 3px solid #174b2b;
        outline-offset: 3px;
    }
    @media (max-width: 575px) {
        .whatsapp-float {
            right: 18px;
            bottom: 82px;
            width: 52px;
            height: 52px;
            font-size: 29px;
        }
        .mobile-credit {
            flex-direction: column;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
    }
</style>
<a class="whatsapp-float" href="<?php echo htmlspecialchars($whatsappUrl, ENT_QUOTES, 'UTF-8'); ?>"
    target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp" title="Chat on WhatsApp">
    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
</a>

<!-- Scripts -->
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/ajax-mail.js"></script>
<script src="assets/js/viewport.jquery.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/gsap.min.js"></script>
<script src="assets/js/ScrollTrigger.min.js"></script>
<script src="assets/js/ScrollSmoother.min.js"></script>
<script src="assets/js/ScrollToPlugin.min.js"></script>
<script src="assets/js/SplitText.min.js"></script>
<script src="assets/js/TextPlugin.js"></script>
<script src="assets/js/chroma.min.js"></script>
<script src="assets/js/jquery.nice-select.min.js"></script>
<script src="assets/js/jquery.waypoints.js"></script>
<script src="assets/js/jquery.counterup.min.js"></script>
<script src="assets/js/swiper-bundle.min.js"></script>
<script src="assets/js/jquery.meanmenu.min.js"></script>
<script src="assets/js/parallaxie.js"></script>
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
