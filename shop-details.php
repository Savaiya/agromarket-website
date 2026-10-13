<?php
$pageTitle = 'Shop Details';
require_once 'includes/header.php';
?>


                <!-- Breadcrumb Section Start -->
                <div class="breadcrumb-wrapper bg-cover" style="background-image: url('assets/img/breadcrumb.png');">
                    <div class="top-image">
                        <img src="assets/img/breadcrumb-2.jpg" alt="img">
                    </div>
                    <div class="container">
                        <div class="page-heading">
                            <div class="breadcrumb-sub-title">
                                <h1 class="text-white wow fadeInUp" data-wow-delay=".3s">Shop Details</h1>
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
                                    Shop Details
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
               
                 <!-- Shop Details Section Start -->
                <section class="shop-details-section section-padding fix section-bg">
                    <div class="container">
                    <div class="shop-details-wrapper">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="shop-details-image">
                                <div class="tab-content">
                                    <div id="thumb1" class="tab-pane fade show active">
                                        <div class="shop-thumb">
                                            <img src="assets/img/inner-page/shop/shop-details-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div id="thumb2" class="tab-pane fade">
                                        <div class="shop-thumb">
                                            <img src="assets/img/inner-page/shop/shop-details-2.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div id="thumb3" class="tab-pane fade">
                                        <div class="shop-thumb">
                                            <img src="assets/img/inner-page/shop/shop-details-3.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div id="thumb4" class="tab-pane fade">
                                        <div class="shop-thumb">
                                            <img src="assets/img/inner-page/shop/shop-details-4.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div id="thumb5" class="tab-pane fade">
                                        <div class="shop-thumb">
                                            <img src="assets/img/inner-page/shop/shop-details-5.jpg" alt="img">
                                        </div>
                                    </div>
                                    </div>
                                    <ul class="nav">
                                    <li class="nav-item">
                                        <a href="#thumb1" data-bs-toggle="tab" class="nav-link ps-0 active">
                                        <img src="assets/img/inner-page/shop/small-1.jpg" alt="img">
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#thumb2" data-bs-toggle="tab" class="nav-link">
                                            <img src="assets/img/inner-page/shop/small-2.jpg" alt="img">
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#thumb3" data-bs-toggle="tab" class="nav-link">
                                            <img src="assets/img/inner-page/shop/small-3.jpg" alt="img">
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                    <a href="#thumb4" data-bs-toggle="tab" class="nav-link">
                                        <img src="assets/img/inner-page/shop/small-4.jpg" alt="img">
                                    </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#thumb5" data-bs-toggle="tab" class="nav-link">
                                            <img src="assets/img/inner-page/shop/small-5.jpg" alt="img">
                                        </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="product-details-content">
                                    <h2 class="pb-3">Fresh Green Lemon</h2>
                                    <div class="star pb-3">
                                        <a href="#"> <i class="fas fa-star"></i></a>
                                        <a href="#"><i class="fas fa-star"></i></a>
                                        <a href="#"> <i class="fas fa-star"></i></a>
                                        <a href="#"><i class="fas fa-star"></i></a>
                                        <a href="#"><i class="fas fa-star"></i></a>
                                        <span>(25 Customer Review)</span>
                                    </div>
                                    <p class="mb-3">
                                        Involves the creation and upkeep of outdoor spaces, to the blending natural and man-made elements. It includes tasks like planting.
                                    </p>
                                    <div class="price-list">
                                        <h3>$1,260.00</h3>
                                    </div>
                                    <div class="cart-wrp">
                                        <div class="cart-quantity">
                                            <form id='myform' method='POST' class='quantity' action='#'>
                                                <input type='button' value='-' class='qtyminus minus'>
                                                <input type='text' name='quantity' value='0' class='qty'>
                                                <input type='button' value='+' class='qtyplus plus'>
                                            </form>
                                        </div>
                                        <a href="shop-details.php" class="icon">
                                            <i class="far fa-heart"></i>
                                        </a>
                                        <div class="social-profile">
                                            <span class="plus-btn"><i class="far fa-share"></i></span>
                                            <ul>
                                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                                <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="shop-btn">
                                        <a href="shop-cart.php" class="theme-btn theme-btn-3">
                                            <span> Add to cart</span>
                                        </a>
                                        <a href="shop-details.php" class="theme-btn">
                                            <span> Buy now</span>
                                        </a>
                                    </div>
                                    <h3 class="details-info"><span>SKU:</span> <a href="shop-details.php">124224</a></h3>
                                    <h3 class="details-info"><span>Categories:</span> <a href="shop-details.php">Crux Indoor Fast and Easy</a></h3>
                                    <h3 class="details-info style-2"><span>Tags:</span> <a href="shop-details.php"> <b>accessories</b> <b>business</b></a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </section>

                <!-- Single-tab Section Start -->
                <section class="single-tab-section section-padding fix pt-0">
                    <div class="container">
                        <div class="single-tab">
                            <ul class="nav mb-5">
                                <li class="nav-item">
                                    <a href="#description" data-bs-toggle="tab" class="nav-link ps-0 active">
                                        <h3>Description</h3>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#additional" data-bs-toggle="tab" class="nav-link">
                                        <h3>Additional Information  </h3>
                                    </a>
                                </li>
                                <li class="nav-item">
                                <a href="#review" data-bs-toggle="tab" class="nav-link">
                                    <h3>reviews (2)</h3>
                                </a>
                            </li>
                            </ul>
                            <div class="tab-content">
                                <div id="description" class="tab-pane fade show active">
                                <div class="description-items">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6">
                                        <div class="description-content">
                                            <h3>Product descriptions</h3>
                                            <p class="mb-4">
                                                Creating handcrafted paintings involves a wide range of techniques, styles, and materials, so product details can vary significantly depending on the specific artwork and artist. However, here are some common product details that you might find when purchasing or describing a handcrafted painting:
                                            </p>
                                            <p>
                                                When purchasing or selling a handcrafted painting, it's essential to have a clear understanding of these product details to make an informed decision and to provide potential buyers with a comprehensive description of the artwork. Additionally, the value and significance of a handcrafted painting can be influenced by factors like the artist's reputation, the rarity of the piece, and the demand for their work in the
                                                art market.
                                            </p>
                                            <div class="description-list-items d-flex justify-content-between">
                                                <ul class="description-list">
                                                    <li>
                                                    Model wears:
                                                    <span>UK 10/ EU 38/ US 6</span>
                                                    </li>
                                                    <li>
                                                    Occasion:
                                                    <span> Lifestyle, Sport</span>
                                                    </li>
                                                    <li>
                                                    Country:
                                                    <span>Italy</span>
                                                    </li>
                                                </ul>
                                                <ul class="description-list">
                                                    <li>
                                                    Model wears:
                                                    <span>UK 10/ EU 38/ US 6</span>
                                                    </li>
                                                    <li>
                                                    Occasion:
                                                    <span> Lifestyle, Sport</span>
                                                    </li>
                                                    <li>
                                                    Country:
                                                    <span>Italy</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 mt-5 mt-lg-0">
                                        <div class="description-image">
                                            <img src="assets/img/inner-page/shop/shop-details-6.jpg" alt="img">
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div id="additional" class="tab-pane fade">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td>Weight</td>
                                            <td>240 Ton</td>
                                        </tr>
                                        <tr>
                                            <td>Dimensions</td>
                                            <td>20 × 30 × 40 cm</td>
                                        </tr>
                                        <tr>
                                            <td>Colors</td>
                                            <td>Black, Blue, Green</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                                <div id="review" class="tab-pane fade">
                                    <div class="review-items">
                                        <div class="admin-items d-flex flex-wrap flex-md-nowrap align-items-center pb-4">
                                            <div class="admin-img pb-4 pb-md-0 me-4">
                                                <img src="assets/img/inner-page/shop/client.png" alt="image">
                                            </div>
                                            <div class="content p-4">
                                                <div class="head-content pb-1">
                                                    <div class="content1">
                                                        <h4>miklos salsa</h4>
                                                        <div class="star">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        </div>
                                                    </div>
                                                  <span>27June 2025 at 5.44pm</span>
                                                </div>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipiscing elit. Curabitur vulputate vestibulum Phasellus rhoncus dolor eget viverra pretium.Curabitur vulputate vestibulum Phasellus rhoncus dolor eget viverra pretium.
                                            </p>
                                            </div>
                                        </div>
                                        <div class="admin-items d-flex flex-wrap flex-md-nowrap align-items-center pb-4">
                                            <div class="admin-img pb-4 pb-md-0 me-4">
                                                <img src="assets/img/inner-page/shop/client-2.png" alt="image">
                                            </div>
                                            <div class="content p-4">
                                                <div class="head-content pb-1">
                                                    <div class="content1">
                                                        <h4>Ethan Turner</h4>
                                                        <div class="star">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        </div>
                                                    </div>
                                                  <span>27June 2025 at 5.44pm</span>
                                                </div>
                                                <p>
                                                Lorem ipsum dolor sit amet consectetur adipiscing elit. Curabitur vulputate vestibulum Phasellus rhoncus dolor eget viverra pretium.Curabitur vulputate vestibulum Phasellus rhoncus dolor eget viverra pretium.
                                            </p>
                                            </div>
                                        </div>
                                        <div class="review-title mt-5 py-15 mb-30">
                                            <h4>add a review</h4>
                                            <div class="rate-now d-flex align-items-center">
                                                <p>Rate this product? *</p>
                                                <div class="star">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="review-form">
                                        <form action="#" id="contact-form2" method="POST">
                                            <div class="row g-4">
                                                <div class="col-lg-6">
                                                    <div class="form-clt">
                                                    <input type="text" name="name" id="name" placeholder="Full Name">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-clt">
                                                    <input type="text" name="email" id="email1" placeholder="email addres">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 wow fadeInUp" data-wow-delay=".8">
                                                    <div class="form-clt-big form-clt">
                                                    <textarea name="message" id="message" placeholder="message"></textarea> 
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 wow fadeInUp" data-wow-delay=".9">
                                                    <button type="submit" class="theme-btn theme-btn-3 hover-color">
                                                        Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Shop-Section Start -->
                <section class="shop-section section-padding fix pt-0">
                    <div class="container">
                        <div class="section-title text-center">
                            <span class="sub-title-3">Our Trending Products</span>
                            <h2 class="text-anim">
                                Trending Organic Products
                            </h2>
                        </div>
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".2" data-fade-from="left">
                                <div class="shop-card-items">
                                    <div class="shop-image">
                                        <img src="assets/img/home-4/shop-1.jpg" alt="img">
                                        <span class="discount">New</span>
                                        <ul class="shop-icon">
                                           <li>
                                             <a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a>
                                           </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a>
                                            </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="shop-content">
                                        <h3>
                                            <a href="shop-details.php">Fresh Strawberry</a>
                                        </h3>
                                        <p class="price"><span class="prev-price">$20.00</span>$10.99</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".4" data-fade-from="left">
                                <div class="shop-card-items">
                                    <div class="shop-image">
                                        <img src="assets/img/home-4/shop-2.jpg" alt="img">
                                        <span class="discount">New</span>
                                        <ul class="shop-icon">
                                           <li>
                                             <a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a>
                                           </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a>
                                            </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="shop-content">
                                        <h3>
                                            <a href="shop-details.php">Green Broccoli</a>
                                        </h3>
                                        <p class="price"><span class="prev-price">$20.00</span>$10.99</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".6" data-fade-from="left">
                                <div class="shop-card-items">
                                    <div class="shop-image">
                                        <img src="assets/img/home-4/shop-3.jpg" alt="img">
                                        <span class="discount">New</span>
                                        <ul class="shop-icon">
                                           <li>
                                             <a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a>
                                           </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a>
                                            </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="shop-content">
                                        <h3>
                                            <a href="shop-details.php">Sou Red Cherry</a>
                                        </h3>
                                        <p class="price"><span class="prev-price">$30.00</span>$10.99</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".8" data-fade-from="left">
                                <div class="shop-card-items">
                                    <div class="shop-image">
                                        <img src="assets/img/home-4/shop-4.jpg" alt="img">
                                        <span class="discount">New</span>
                                        <ul class="shop-icon">
                                           <li>
                                             <a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a>
                                           </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a>
                                            </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="shop-content">
                                        <h3>
                                            <a href="shop-details.php">Fresh Orange</a>
                                        </h3>
                                        <p class="price"><span class="prev-price">$35.00</span>$12.99</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".2" data-fade-from="left">
                                <div class="shop-card-items">
                                    <div class="shop-image">
                                        <img src="assets/img/home-4/shop-5.jpg" alt="img">
                                        <span class="discount">New</span>
                                        <ul class="shop-icon">
                                           <li>
                                             <a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a>
                                           </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a>
                                            </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="shop-content">
                                        <h3>
                                            <a href="shop-details.php">Green Apple</a>
                                        </h3>
                                        <p class="price"><span class="prev-price">$35.00</span>$12.99</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".4" data-fade-from="left">
                                <div class="shop-card-items">
                                    <div class="shop-image">
                                        <img src="assets/img/home-4/shop-6.jpg" alt="img">
                                        <span class="discount">New</span>
                                        <ul class="shop-icon">
                                           <li>
                                             <a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a>
                                           </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a>
                                            </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="shop-content">
                                        <h3>
                                            <a href="shop-details.php">Green Tomato</a>
                                        </h3>
                                        <p class="price"><span class="prev-price">$25.00</span>$15.99</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".6" data-fade-from="left">
                                <div class="shop-card-items">
                                    <div class="shop-image">
                                        <img src="assets/img/home-4/shop-7.jpg" alt="img">
                                        <span class="discount">New</span>
                                        <ul class="shop-icon">
                                           <li>
                                             <a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a>
                                           </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a>
                                            </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="shop-content">
                                        <h3>
                                            <a href="shop-details.php">Fresh Cabbage</a>
                                        </h3>
                                        <p class="price"><span class="prev-price">$25.00</span>$15.99</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".8" data-fade-from="left">
                                <div class="shop-card-items">
                                    <div class="shop-image">
                                        <img src="assets/img/home-4/shop-8.jpg" alt="img">
                                        <span class="discount">New</span>
                                        <ul class="shop-icon">
                                           <li>
                                             <a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a>
                                           </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a>
                                            </li>
                                            <li>
                                                <a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="shop-content">
                                        <h3>
                                            <a href="shop-details.php">Green Maize</a>
                                        </h3>
                                        <p class="price"><span class="prev-price">$15.00</span>$10.99</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

               
                

<?php require_once 'includes/footer.php'; ?>
