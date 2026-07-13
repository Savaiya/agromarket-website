<?php include 'include/header.php'; ?>

<?php if (isset($_GET['status'])): ?>
  <?php if ($_GET['status'] == 'success'): ?>
    <p id="form-message" style="color:green;">
      Thank you! Your message has been sent.
    </p>
  <?php elseif ($_GET['status'] == 'error'): ?>
    <p id="form-message" style="color:red;">
      Oops! Message could not be sent.
    </p>
  <?php endif; ?>
<?php endif; ?>



    <!--==============================
    Breadcumb
    ============================== -->
    <div class="breadcumb-wrapper" data-bg-src="aassets/img/index/main2.webp">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Contact</h1>
            </div>
            <div class="breadcumb-menu-wrap">
                <ul class="breadcumb-menu">
                    <li><a href="index.php">Home</a></li>
                    <li>Contact</li>
                </ul>
            </div>
        </div>
    </div>
    <!--==============================
    Contact Area
    ============================== -->
    <section class="contact-layout1 space">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="title-area wow fadeInUp wow-animated" data-wow-delay="0.3s">
                        <span class="sec-subtitle">CONTACT US</span>
                        <h2 class="sec-title">We're Here to Help You. Get in Touch with Our Team!</h2>
                    </div>
                    <div class="vs-comment-form">
                        <div id="respond" class="comment-respond">
                            <div class="form-title">
                                <p class="form-text">Please fill out the form below and one of our recruitment specialists will back in touch shortly.</p>
                            </div>
                            <form action="mail-direct.php" method="post" class="form-style3 ">
                                <div class="row">
                                    <div class="col-12  form-group">
                                        <textarea name="message" class="form-control" placeholder="Message" required=""></textarea>
                                      </div>
                                    <div class="col-md-6 form-group">
                                    <input name="fname" type="text" class="form-control" placeholder="Name" required="">
                                  </div>
                                  <div class="col-6 form-group">
                                    <input name="email" type="email" class="form-control" placeholder="Email Address" required="">
                                  </div>
                                  <div class="col-12 ">
                                    <div class="custom-checkbox notice">
                                        <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes">
                                        <label for="wp-comment-cookies-consent"> Save my name, email, and website in this browser for the next time I comment.</label>
                                    </div>
                                </div>
                                  <div class="col-12 form-group">
                                    <button class="vs-btn" type="submit">
                                      Send Message
                                    </button>
                                  </div>
                                </div>
                            </form>
                            <p class="form-messages mb-0 mt-3"></p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="contact-left">
                         <!--<div class="auther-inner">
                             <div class="auther-img">
                                <img src="assets/img/about/about-author.png" alt="about">
                            </div> 
                            <div class="auther-content">
                                <h6 class="name">Parv Yadav</h6>
                                <span class="designation">Founder & Managing Director</span>
                              
                            </div>
                        </div>-->
                        <div class="team-media">
                            <h2 class="contact-title">Professional Skills</h2>
                            <div class="media-style1">
                                <div class="media-icon"><img src="assets/img/icon/icon-1-1.png" alt="icon"></div>
                                <div class="media-body">
                                    <h3 class="media-title">Phone No:</h3>
                                    <p class="media-info"><a href="tel:+88013004451">+91 99939 55051</a> <br> Mon - Sat: 10.00 to 07.00</p>
                                </div>
                            </div>
                            <div class="media-style1">
                                <div class="media-icon"><img src="assets/img/icon/icon-1-2.png" alt="icon"></div>
                                <div class="media-body">
                                    <h3 class="media-title">Email Address:</h3>
                                    <p class="media-info"><a href="mailto:example@domain.com">info@agromarket.co.in</a> <br> </p>
                                </div>
                            </div>
                            <div class="media-style1">
                                <div class="media-icon"><img src="assets/img/icon/icon-1-3.png" alt="icon"></div>
                                <div class="media-body">
                                    <h3 class="media-title">Locatoin:</h3>
                                    <p class="media-info">3rd Floor, PU4 Vijay Nagar, Near Indo Thai Security, Indore - 452010. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="map">
<iframe
  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3667.302900601132!2d79.9262978!3d23.1814146!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3981ae5b6b6b6b6b%3A0x123456789abcdef!2sAMPL%20AGRO%20MARKET%20PVT%20LTD!5e0!3m2!1sen!2sin!4v1700000000000"
  width="100%"
  height="450"
  style="border:0;"
  allowfullscreen=""
  loading="lazy"
  referrerpolicy="no-referrer-when-downgrade">
</iframe>
            </div>
        </div>
    </section>
    <!--==============================
    Faq Area
    ============================== -->
    <section class="faq-layout1 space-bottom">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-12">
                    <div class="title-area text-center wow fadeInUp wow-animated" data-wow-delay="0.3s">
                        <span class="sec-subtitle">Any Question Please?</span>
                        <h2 class="sec-title">Clearly Answered About AMPL Agro Market
</h2>
                    </div>
                    <div class="accordion-style1">
                        <div class="accordion" id="accordionExample">
                          <div class="accordion-item">
                            <h2 class="accordion-header">
                              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                What is AMPL Agro Market Private Limited?

                              </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                AMPL Agro Market Pvt. Ltd. is a fast-growing agri-trading and branded agri-input company committed to empowering agri-retailers, farmers, and agricultural enterprises across India.


                              </div>
                            </div>
                          </div>
                          <div class="accordion-item">
                            <h2 class="accordion-header">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                              Who founded the company and when?

                              </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                   The company was founded by Parv Yadav in 2023 as a bootstrapped venture.


                                </div>
                            </div>
                          </div>
                          <div class="accordion-item">
                            <h2 class="accordion-header">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                               Where is the company headquartered?

                              </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                  The headquarters are in Jabalpur, Madhya Pradesh, with additional branch offices in Jabalpur and Indore.


                                </div>
                            </div>
                          </div>
                          <div class="accordion-item">
                            <h2 class="accordion-header">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                What types of products does AMPL offer?

                              </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    AMPL offers a wide range of premium agricultural inputs, including: <br>

1. Bio Plant Protectors: E.g., Fire-101, Xperia. <br>

 
2.Plant Growth Regulators (PGR): E.g., Shaktishali, Head Shot. <br>

 
3.Insecticides: E.g., Blast 505, L-Like, Ampletap, Fertinox, Current 404. <br>

 
4.Bio-Fertilizers: E.g., AgroRaja. <br>

 
5.Other Inputs: Seeds, fertilizers, pesticides, and herbicides.
                                </div>
                            </div>


                                    <!-- <div class="accordion-item">
                            <h2 class="accordion-body">
                              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#coll" aria-expanded="false" aria-controls="coll">
                               Does AMPL manufacture its own products?


                              </button>
                            </h2>
                            <div id="coll" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                Yes, besides trading, AMPL manufactures and distributes its own branded products like “Fire-101”, “Shaktishali”, and “Blast 505”.



                              </div>
                            </div>
                          </div>


                                 <div class="accordion-item">
                            <h2 class="accordion-body">
                              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#One" aria-expanded="false" aria-controls="One">
                              Who are the target customers for AMPL?



                              </button>
                            </h2>
                            <div id="One" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                The company primarily serves agri-retailers, dealers, wholesalers, bulk buyers, and farmers.





                              </div>
                            </div>
                          </div> -->
                          </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </section>
<script>
document.addEventListener("DOMContentLoaded", function () {
  setTimeout(function () {
    var msg = document.getElementById("form-message");
    if (msg) {
      msg.style.display = "none";
    }
  }, 6000); // 6 seconds
});
</script>


    <!--==============================
			Footer Area
	==============================-->
   <?php include 'include/footer.php'; ?>