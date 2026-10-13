<?php
/**
 * includes/header.php
 * Common site header – included at the top of every page.
 *
 * Expects $pageTitle to be set before this include.
 * Falls back to TITLE_SUFFIX if not set.
 */
require_once __DIR__ . '/../config.php';

$title = isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' . SITE_NAME : TITLE_SUFFIX;
$isAdmin = basename(dirname($_SERVER['SCRIPT_FILENAME'])) === 'admin'
    || (isset($pageTitle) && $pageTitle === 'Admin');
?>

<!DOCTYPE html>
<html lang="en">
    <!--<< Header Area >>-->
    <head>
        <!-- ========== Meta Tags ========== -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="<?php echo SITE_AUTHOR; ?>">
        <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>">
        <!-- ======== Page title ============ -->
        <title><?php echo $title; ?></title>
        <!--<< Favicon >>-->
        <link rel="shortcut icon" href="assets/img/favicon.svg">
        <!--<< Bootstrap min.css >>-->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <!--<< All Min Css >>-->
        <link rel="stylesheet" href="assets/css/all.min.css">
        <!--<< Animate.css >>-->
        <link rel="stylesheet" href="assets/css/animate.css">
        <!--<< Magnific Popup.css >>-->
        <link rel="stylesheet" href="assets/css/magnific-popup.css">
        <!--<< MeanMenu.css >>-->
        <link rel="stylesheet" href="assets/css/meanmenu.css">
        <!--<< Swiper Bundle.css >>-->
        <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
        <!--<< Nice Select.css >>-->
        <link rel="stylesheet" href="assets/css/nice-select.css">
        <!--<< Main.css >>-->
        <link rel="stylesheet" href="assets/css/main.css">
        <style>
            @media (max-width: 450px) {
                #mobile-menu > ul > li.has-dropdown > .submenu {
                    width: 190px;
                }
            }
        </style>
    </head>
    <body class="bg-white">

        <?php if (!$isAdmin): ?>
            <!-- Preloader Start -->
            <div id="agri-preloader">
                <div class="preloader-content">
                    <img src="assets/img/logo/logo.webp"
                        alt="AgroMarket"
                        class="preloader-logo">
                    <h4>Loading AMPL...</h4>
                </div>
            </div>

            <!-- Back To Top Start -->
            <button id="back-top" class="back-to-top theme-bg-2">
                <i class="fa-regular fa-arrow-up"></i>
            </button>

            <!-- GT MouseCursor Start -->
            <div class="mouseCursor cursor-outer"></div>
            <div class="mouseCursor cursor-inner"></div>

            <!-- Offcanvas Area Start -->
            <div class="fix-area">
                <div class="offcanvas__info">
                <div class="offcanvas__wrapper">
                    <div class="offcanvas__content">
                        <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                            <div class="offcanvas__logo ">
                                <a href="index.php">
                                    <img src="assets/img/logo/logo.webp" alt="logo-img">
                                </a>
                            </div>
                            <div class="offcanvas__close">
                                <button>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text d-none d-xl-block">
                            Nullam dignissim, ante scelerisque the is euismod fermentum odio sem semper the is erat, a feugiat leo urna eget eros. Duis Aenean a imperdiet risus.
                        </p>
                        <div class="mobile-menu fix mb-3"></div>
                        <div class="offcanvas__contact d-xl-block">
                            <h4 class="d-xl-block">Contact Info</h4>
                            <ul class="d-xl-block">
                                <li class="d-flex align-items-center">
                                    <div class="offcanvas__contact-icon style-2">
                                        <i class="fal fa-map-marker-alt"></i>
                                    </div>
                                    <div class="offcanvas__contact-text">
                                        <a target="_blank" href="#"><?php echo SITE_ADDRESS; ?></a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <div class="offcanvas__contact-icon style-2 mr-15">
                                        <i class="fal fa-envelope"></i>
                                    </div>
                                    <div class="offcanvas__contact-text">
                                        <a href="mailto:<?php echo SITE_EMAIL; ?>"><span><?php echo SITE_EMAIL; ?></span></a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <div class="offcanvas__contact-icon style-2 mr-15">
                                        <i class="fal fa-clock"></i>
                                    </div>
                                    <div class="offcanvas__contact-text">
                                        <a target="_blank" href="#"><?php echo SITE_HOURS; ?></a>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <div class="offcanvas__contact-icon style-2 mr-15">
                                        <i class="far fa-phone"></i>
                                    </div>
                                    <div class="offcanvas__contact-text">
                                        <a href="tel:<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a>
                                    </div>
                                </li>
                            </ul>
                            <div class="social-icon d-flex align-items-center style-2">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-youtube"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offcanvas__overlay"></div>
        <?php endif; ?>

        <!-- Header Section Start -->
        <header id="header-sticky" class="header-1">
            <div class="container-fluid">
                <div class="mega-menu-wrapper">
                    <div class="header-main">
                        <div class="header-left">
                            <div class="logo">
                                <a href="index.php" class="header-logo">
                                    <img src="assets/img/logo/logo.webp" alt="logo-img">
                                </a>
                                <a href="index.php" class="header-logo-2">
                                    <img src="assets/img/logo/logo.webp" alt="logo-img">
                                </a>
                            </div>
                        </div>
                        <?php if (!$isAdmin): ?>
                            <div class="mean__menu-wrapper">
                                <div class="main-menu">
                                    <nav id="mobile-menu">
                                        <ul>
                                            <li class="menu-thumb">
                                            <a href="index.php">Home</a>
                                        </li>
                                        <li class="d-xl-none">
                                            <a href="index.php" class="border-none">Home</a>
                                            
                                        </li>
                                        <li><a href="about.php">About Us</a></li>
                                        <li><a href="product.php">Product</a></li>
                                        <li class="has-dropdown">
                                            <a href="blogs.php">Blog</a>                                            
                                        </li>
                                        <li><a href="contact.php">Contact Us</a></li>
                                        <li><a href="privacy.php">Privacy Policy</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!$isAdmin): ?>
                            <div class="header-right d-flex justify-content-end align-items-center">
                                <div class="icon-items">
                                    <div class="menu_search">
                                        <button class="search_btn"><i class="far fa-search"></i></button>
                                    </div>
                                    <!-- <a href="shop-details.php"><i class="fa-solid fa-cart-shopping"></i></a> -->
                                </div>
                                <div class="header-btn">
                                    <span>
                                        <i class="fa-solid fa-phone-volume"></i>
                                        <a href="tel:<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a>
                                    </span>
                                    <div class="header-button">
                                        <a href="contact.php" class="theme-btn-2 style-btns">Get In Touch</a>
                                    </div>
                                    <div class="header__hamburger d-xl-none my-auto">
                                        <div class="sidebar__toggle">
                                            <i class="fas fa-bars"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <?php if (!$isAdmin): ?>
            <!-- start: Search Popup -->
        <div class="search_popup">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="search_wrapper">
                            <div class="search_top d-flex align-items-center">
                                <div class="search_logo">
                                    <a href="index.php">
                                        <img src="assets/img/header/logo.webp" alt="<?php echo htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8'); ?>">
                                    </a>
                                </div>
                                <div class="search_close">
                                    <button type="button" class="search_close_btn">
                                        <i class="fa-thin fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="search_form">
                                <form action="#" id="globalSearchForm">
                                    <div class="search_input">
                                        <input class="search-input-field" id="globalSearchInput" name="q" type="search"
                                            placeholder="Search products and blogs..." autocomplete="off"
                                            aria-label="Search products and blogs" aria-controls="globalSearchResults">
                                        <span class="search-focus-border"></span>
                                        <button type="submit">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.55 18.1C14.272 18.1 18.1 14.272 18.1 9.55C18.1 4.82797 14.272 1 9.55 1C4.82797 1 1 4.82797 1 9.55C1 14.272 4.82797 18.1 9.55 18.1Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M19.0002 19.0002L17.2002 17.2002" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                                <div class="global-search-results" id="globalSearchResults" aria-live="polite" hidden></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="search-popup-overlay bg-theme-2"></div>
        <?php endif; ?>

        <div id="smooth-wrapper">
            <div id="smooth-content">
