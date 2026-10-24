<!-- Breadcrumb Section Start -->
                <div class="breadcrumb-wrapper bg-cover" style="background-image: url('assets/img/breadcrumb.png');">
                    <div class="top-image">
                        <img src="assets/img/breadcrumb-2.jpg" alt="img">
                    </div>
                    <div class="container">
                        <div class="page-heading">
                            <div class="breadcrumb-sub-title">
                                <h1 class="text-white wow fadeInUp" data-wow-delay=".3s">contact us</h1>
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
                                    contact us
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
               
                
                <!-- Contact-Info Section Start -->
                <div class="contact-info-section section-padding fix">
                    <div class="container">
                        <div class="contact-info-wrapper">
                            <div class="contact-info-left-content">
                                <div class="section-title mb-0">
                                    <span class="sub-title-3">
                                        get in touch
                                    </span>
                                    <h2 class="text-anim">
                                        Reach Out And Let’s Build <br> Something Great
                                    </h2>
                                </div>
                                <div class="info-icon-item">
                                    <div class="icon-item wow fadeInUp" data-wow-delay=".6s">
                                    <div class="icon">
                                        <img src="assets/img/home-2/icon/10.svg" alt="img">
                                    </div>
                                    <div class="cont">
                                        <span>call for details</span>
                                        <h3>
                                            <a href="tel:<?php echo $sitePhone; ?>"><?php echo $sitePhone; ?></a>
                                        </h3>
                                    </div>
                                </div>
                                <div class="icon-item wow fadeInUp" data-wow-delay=".8s">
                                    <div class="icon">
                                        <img src="assets/img/home-2/icon/11.svg" alt="img">
                                    </div>
                                    <div class="cont">
                                        <span>send us email</span>
                                        <h3>
                                            <a href="mailto:<?php echo $siteEmail; ?>"><?php echo $siteEmail; ?></a>
                                        </h3>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="info-right-content">
                                <p>
                                    We combine advanced technology with modern farming practices to deliver smarter agricultural solutions. Our drone-powered approach ensures precision, efficiency, and sustainability in every field.
                                </p>
                                <div class="icon-item wow fadeInUp" data-wow-delay=".6s">
                                    <div class="icon">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div class="cont">
                                        <span>our address</span>
                                        <h3>
                                            <?php echo $siteAddress; ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact-Section Start -->
                <div class="contact-section section-padding fix section-bg-3">
                    <div class="left-shape float-bob-x">
                        <img src="assets/img/inner-page/service/tree.png" alt="img">
                    </div>
                    <div class="maize-shape float-bob-x">
                        <img src="assets/img/inner-page/service/maize.png" alt="img">
                    </div>
                    <div class="container">
                        <div class="contact-wrapper">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="contact-content">
                                        <div class="contact-form">
                                            <form id="contact-form" method="post" action="contact.php">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                                <?php if (is_array($contactNotice) && !empty($contactNotice['message'])): ?>
                                                    <div class="form-message <?php echo !empty($contactNotice['success']) ? 'success' : 'error'; ?>"
                                                        role="<?php echo !empty($contactNotice['success']) ? 'status' : 'alert'; ?>">
                                                        <?php echo htmlspecialchars((string) $contactNotice['message'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="form-message" role="status" aria-live="polite"></div>
                                                <?php endif; ?>
                                                <div class="row g-3">
                                                <div class="col-lg-12">
                                                    <div class="form-clt">
                                                        <span>your Name</span>
                                                        <input type="text" name="name" id="name" placeholder="Your Name" maxlength="100" required
                                                            value="<?php echo htmlspecialchars((string) ($contactFormValues['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-clt">
                                                    <span>your email</span>
                                                    <input type="email" name="email" id="email2" placeholder="email@example.com" maxlength="150" required
                                                        value="<?php echo htmlspecialchars((string) ($contactFormValues['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-clt">
                                                    <span>contact number</span>
                                                    <input type="tel" name="phone" id="number" placeholder="+91 ..." maxlength="20"
                                                        value="<?php echo htmlspecialchars((string) ($contactFormValues['phone'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-clt">
                                                    <span>subject</span>
                                                    <input type="text" name="subject" id="subject" placeholder="Subject" maxlength="200"
                                                        value="<?php echo htmlspecialchars((string) ($contactFormValues['subject'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-clt">
                                                    <span>Message</span>
                                                    <textarea name="message" id="message" cols="30" rows="10" placeholder="write message. . ." required><?php echo htmlspecialchars((string) ($contactFormValues['message'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-clt">
                                                    <button type="submit" value="Send Message" class="theme-btn theme-btn-3">Send Message</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="contact-map">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3667.302900601132!2d79.9262978!3d23.1814146!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3981ae5b6b6b6b6b%3A0x123456789abcdef!2sAMPL%20AGRO%20MARKET%20PVT%20LTD!5e0!3m2!1sen!2sin!4v1700000000000" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Drone-Specification-Section Start -->
                <div class="drone-specification-section section-padding fix">
                    <div class="gallery-wrapper mt-0">
                        <div class="gallery-track">
                            <div class="gallery-item">
                                <img src="assets/img/home-3/drone/drone-4.jpg" alt="img">
                            </div>
                            <div class="gallery-item">
                               <img src="assets/img/home-3/drone/drone-5.jpg" alt="img">
                            </div>
                            <div class="gallery-item">
                                <img src="assets/img/home-3/drone/drone-6.jpg" alt="img">
                            </div>
                            <div class="gallery-item">
                               <img src="assets/img/home-3/drone/drone-7.jpg" alt="img">
                            </div>
                        </div>
                        <!-- Floating Circle -->
                        <div class="drag-circle">
                            <span class="arrow left"><i class="fa-solid fa-chevron-left"></i></span>
                            <span class="text">DRAG</span>
                            <span class="arrow right"><i class="fa-solid fa-chevron-right"></i></span>
                        </div>
                    </div>
                </div>
