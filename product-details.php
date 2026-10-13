<?php
$pageTitle = 'Coconut Oil Jar';
require_once 'includes/header.php';
?>

<!-- Breadcrumb Section Start -->
<div class="breadcrumb-wrapper bg-cover" style="background-image: url('assets/img/breadcrumb.png');">
    <div class="top-image">
        <img src="assets/img/breadcrumb-2.jpg" alt="Product details background">
    </div>
    <div class="container">
        <div class="page-heading">
            <div class="breadcrumb-sub-title">
                <h1 class="text-white wow fadeInUp" data-wow-delay=".3s">Coconut Oil Jar</h1>
            </div>
            <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".5s">
                <li><a href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                <li>:</li>
                <li><a href="product.php">Our Products</a></li>
                <li>:</li>
                <li>Coconut Oil Jar</li>
            </ul>
        </div>
    </div>
</div>

<!-- Product Details Section Start -->
<section class="shop-details-section section-padding fix section-bg">
    <div class="container">
        <div class="shop-details-wrapper">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="shop-details-image">
                        <div class="tab-content">
                            <div id="product-thumb-1" class="tab-pane fade show active">
                                <div class="shop-thumb">
                                    <img src="assets/img/inner-page/shop/shop-details-1.jpg" alt="Coconut Oil Jar view one">
                                </div>
                            </div>
                            <div id="product-thumb-2" class="tab-pane fade">
                                <div class="shop-thumb">
                                    <img src="assets/img/inner-page/shop/shop-details-2.jpg" alt="Coconut Oil Jar view two">
                                </div>
                            </div>
                            <div id="product-thumb-3" class="tab-pane fade">
                                <div class="shop-thumb">
                                    <img src="assets/img/inner-page/shop/shop-details-3.jpg" alt="Coconut Oil Jar view three">
                                </div>
                            </div>
                            <div id="product-thumb-4" class="tab-pane fade">
                                <div class="shop-thumb">
                                    <img src="assets/img/inner-page/shop/shop-details-4.jpg" alt="Coconut Oil Jar view four">
                                </div>
                            </div>
                            <div id="product-thumb-5" class="tab-pane fade">
                                <div class="shop-thumb">
                                    <img src="assets/img/inner-page/shop/shop-details-5.jpg" alt="Coconut Oil Jar view five">
                                </div>
                            </div>
                        </div>
                        <ul class="nav">
                            <li class="nav-item">
                                <a href="#product-thumb-1" data-bs-toggle="tab" class="nav-link ps-0 active">
                                    <img src="assets/img/inner-page/shop/small-1.jpg" alt="Coconut Oil Jar thumbnail one">
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#product-thumb-2" data-bs-toggle="tab" class="nav-link">
                                    <img src="assets/img/inner-page/shop/small-2.jpg" alt="Coconut Oil Jar thumbnail two">
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#product-thumb-3" data-bs-toggle="tab" class="nav-link">
                                    <img src="assets/img/inner-page/shop/small-3.jpg" alt="Coconut Oil Jar thumbnail three">
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#product-thumb-4" data-bs-toggle="tab" class="nav-link">
                                    <img src="assets/img/inner-page/shop/small-4.jpg" alt="Coconut Oil Jar thumbnail four">
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#product-thumb-5" data-bs-toggle="tab" class="nav-link">
                                    <img src="assets/img/inner-page/shop/small-5.jpg" alt="Coconut Oil Jar thumbnail five">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="product-details-content">
                        <h2 class="pb-3">Coconut Oil Jar</h2>
                        <div class="star pb-3" aria-label="5 out of 5 stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <span>(3 Customer Reviews)</span>
                        </div>
                        <p class="mb-3">A premium organic coconut oil jar, thoughtfully packaged for everyday culinary and wellness use.</p>
                        <div class="price-list">
                            <h3>$155.00 <span class="text-muted fs-5"><del>$23.85</del></span></h3>
                        </div>
                        <div class="cart-wrp">
                            <div class="cart-quantity">
                                <form id="productQuantity" method="POST" class="quantity" action="#">
                                    <input type="button" value="-" class="qtyminus minus" aria-label="Decrease quantity">
                                    <input type="text" name="quantity" value="1" class="qty" aria-label="Quantity">
                                    <input type="button" value="+" class="qtyplus plus" aria-label="Increase quantity">
                                </form>
                            </div>
                            <a href="#" class="icon" aria-label="Add to wishlist"><i class="far fa-heart"></i></a>
                            <div class="social-profile">
                                <span class="plus-btn"><i class="far fa-share"></i></span>
                                <ul>
                                    <li><a href="#" aria-label="Share on Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#" aria-label="Share on Twitter"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#" aria-label="Share on YouTube"><i class="fab fa-youtube"></i></a></li>
                                    <li><a href="#" aria-label="Share on Instagram"><i class="fab fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="shop-btn">
                            <a href="shop-cart.php" class="theme-btn theme-btn-3"><span>Add to Cart</span></a>
                            <a href="checkout.php" class="theme-btn"><span>Buy Now</span></a>
                        </div>
                        <h3 class="details-info"><span>SKU:</span> <a href="#">#WE443</a></h3>
                        <h3 class="details-info"><span>Categories:</span> <a href="product.php">Organic</a>, <a href="product.php">Food</a>, <a href="product.php">Natural</a></h3>
                        <h3 class="details-info style-2"><span>Tags:</span> <a href="product.php"><b>coconut</b> <b>oil</b> <b>organic</b></a></h3>
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
                    <a href="#description" data-bs-toggle="tab" class="nav-link ps-0 active"><h3>Description</h3></a>
                </li>
                <li class="nav-item">
                    <a href="#additional" data-bs-toggle="tab" class="nav-link"><h3>Additional Information</h3></a>
                </li>
                <li class="nav-item">
                    <a href="#review" data-bs-toggle="tab" class="nav-link"><h3>Reviews (03)</h3></a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="description" class="tab-pane fade show active">
                    <div class="description-items">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6">
                                <div class="description-content">
                                    <h3>Product descriptions</h3>
                                    <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                                    <p>In ut ullamcorper leo, eget euismod orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum ultricies aliquam. Donec ultricies nec, pellentesque consectetur adipiscing elit. Ieuismod orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
                                    <div class="description-list-items d-flex justify-content-between">
                                        <ul class="description-list">
                                            <li>Type <span>Veg Box, Organic</span></li>
                                            <li>Size <span>400ml</span></li>
                                            <li>Brand <span>Fosroc</span></li>
                                        </ul>
                                        <ul class="description-list">
                                            <li>Organic <span>100%</span></li>
                                            <li>Availability <span>In stock</span></li>
                                            <li>Shipping <span>Free</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 mt-5 mt-lg-0">
                                <div class="description-image">
                                    <img src="assets/img/inner-page/shop/shop-details-6.jpg" alt="Coconut Oil Jar details illustration">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="additional" class="tab-pane fade">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><td>Type</td><td>Veg Box, Organic</td></tr>
                                <tr><td>Size</td><td>400ml</td></tr>
                                <tr><td>Brand</td><td>Fosroc</td></tr>
                                <tr><td>Organic</td><td>100%</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="review" class="tab-pane fade">
                    <div class="review-items">
                        <div class="admin-items d-flex flex-wrap flex-md-nowrap align-items-center pb-4">
                            <div class="admin-img pb-4 pb-md-0 me-4"><img src="assets/img/inner-page/shop/client.png" alt="Thomas Walkar"></div>
                            <div class="content p-4">
                                <div class="head-content pb-1">
                                    <div class="content1"><h4>Thomas Walkar</h4><div class="star"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div></div>
                                    <span>22 April 2022 at 10:00am</span>
                                </div>
                                <p>Delivered with enthusiasm and arranged with care.</p>
                            </div>
                        </div>
                        <div class="admin-items d-flex flex-wrap flex-md-nowrap align-items-center pb-4">
                            <div class="admin-img pb-4 pb-md-0 me-4"><img src="assets/img/inner-page/shop/client-2.png" alt="Crish Thomas"></div>
                            <div class="content p-4">
                                <div class="head-content pb-1">
                                    <div class="content1"><h4>Crish Thomas</h4><div class="star"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div></div>
                                    <span>22 April 2022 at 10:00am</span>
                                </div>
                                <p>Delivered with enthusiasm and arranged with care.</p>
                            </div>
                        </div>
                        <div class="admin-items d-flex flex-wrap flex-md-nowrap align-items-center pb-4">
                            <div class="admin-img pb-4 pb-md-0 me-4"><img src="assets/img/inner-page/shop/client.png" alt="Millem Jakson"></div>
                            <div class="content p-4">
                                <div class="head-content pb-1">
                                    <div class="content1"><h4>Millem Jakson</h4><div class="star"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div></div>
                                    <span>23 April 2022 at 10:00am</span>
                                </div>
                                <p>Delivered with enthusiasm and arranged with care.</p>
                            </div>
                        </div>
                        <div class="review-title mt-5 py-15 mb-30">
                            <h4>Add a review</h4>
                            <div class="rate-now d-flex align-items-center"><p>Rate this product? *</p><div class="star"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div></div>
                        </div>
                        <div class="review-form">
                            <form action="#" id="product-review-form" method="POST">
                                <div class="row g-4">
                                    <div class="col-lg-6"><div class="form-clt"><input type="text" name="name" id="review-name" placeholder="Full Name"></div></div>
                                    <div class="col-lg-6"><div class="form-clt"><input type="email" name="email" id="review-email" placeholder="Email Address"></div></div>
                                    <div class="col-lg-12"><div class="form-clt-big form-clt"><textarea name="message" id="review-message" placeholder="Review"></textarea></div></div>
                                    <div class="col-lg-6"><button type="submit" class="theme-btn theme-btn-3 hover-color">Submit</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trending Products Section Start -->
<section class="shop-section section-padding fix pt-0">
    <div class="container">
        <div class="section-title text-center">
            <span class="sub-title-3">Our Trending Products</span>
            <h2 class="text-anim">Trending Organic Products</h2>
        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".2" data-fade-from="left">
                <div class="shop-card-items"><div class="shop-image"><img src="assets/img/home-4/shop-1.jpg" alt="Fresh Strawberry"><span class="discount">New</span><ul class="shop-icon"><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a></li></ul></div><div class="shop-content"><h3><a href="shop-details.php">Fresh Strawberry</a></h3><p class="price"><span class="prev-price">$20.00</span>$10.99</p></div></div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".4" data-fade-from="left">
                <div class="shop-card-items"><div class="shop-image"><img src="assets/img/home-4/shop-2.jpg" alt="Green Broccoli"><span class="discount">New</span><ul class="shop-icon"><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a></li></ul></div><div class="shop-content"><h3><a href="shop-details.php">Green Broccoli</a></h3><p class="price"><span class="prev-price">$20.00</span>$10.99</p></div></div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".6" data-fade-from="left">
                <div class="shop-card-items"><div class="shop-image"><img src="assets/img/home-4/shop-3.jpg" alt="Sou Red Cherry"><span class="discount">New</span><ul class="shop-icon"><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a></li></ul></div><div class="shop-content"><h3><a href="shop-details.php">Sou Red Cherry</a></h3><p class="price"><span class="prev-price">$30.00</span>$10.99</p></div></div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".8" data-fade-from="left">
                <div class="shop-card-items"><div class="shop-image"><img src="assets/img/home-4/shop-4.jpg" alt="Fresh Orange"><span class="discount">New</span><ul class="shop-icon"><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a></li></ul></div><div class="shop-content"><h3><a href="shop-details.php">Fresh Orange</a></h3><p class="price"><span class="prev-price">$35.00</span>$12.99</p></div></div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".2" data-fade-from="left">
                <div class="shop-card-items"><div class="shop-image"><img src="assets/img/home-4/shop-5.jpg" alt="Green Apple"><span class="discount">New</span><ul class="shop-icon"><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a></li></ul></div><div class="shop-content"><h3><a href="shop-details.php">Green Apple</a></h3><p class="price"><span class="prev-price">$35.00</span>$12.99</p></div></div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".4" data-fade-from="left">
                <div class="shop-card-items"><div class="shop-image"><img src="assets/img/home-4/shop-6.jpg" alt="Green Tomato"><span class="discount">New</span><ul class="shop-icon"><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a></li></ul></div><div class="shop-content"><h3><a href="shop-details.php">Green Tomato</a></h3><p class="price"><span class="prev-price">$25.00</span>$15.99</p></div></div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".6" data-fade-from="left">
                <div class="shop-card-items"><div class="shop-image"><img src="assets/img/home-4/shop-7.jpg" alt="Fresh Pineapple"><span class="discount">New</span><ul class="shop-icon"><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a></li></ul></div><div class="shop-content"><h3><a href="shop-details.php">Fresh Pineapple</a></h3><p class="price"><span class="prev-price">$30.00</span>$17.99</p></div></div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 tp_fade_anim" data-delay=".8" data-fade-from="left">
                <div class="shop-card-items"><div class="shop-image"><img src="assets/img/home-4/shop-8.jpg" alt="Fresh Mango"><span class="discount">New</span><ul class="shop-icon"><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-shopping-basket"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-heart"></i></a></li><li><a href="shop-cart.php" class="icon-btn"><i class="far fa-eye"></i></a></li></ul></div><div class="shop-content"><h3><a href="shop-details.php">Fresh Mango</a></h3><p class="price"><span class="prev-price">$25.00</span>$17.99</p></div></div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
