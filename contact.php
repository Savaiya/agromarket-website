<?php
$pageTitle = 'Contact Us';
require_once __DIR__ . '/admin/config.php';

$contactNotice = $_SESSION['contact_form_notice'] ?? null;
$contactFormValues = $_SESSION['contact_form_values'] ?? [];
unset($_SESSION['contact_form_notice'], $_SESSION['contact_form_values']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isAjaxRequest = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    $respondToContact = static function (bool $success, string $message, int $statusCode = 200) use ($isAjaxRequest): void {
        if ($isAjaxRequest) {
            http_response_code($statusCode);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        $_SESSION['contact_form_notice'] = ['success' => $success, 'message' => $message];
        $_SESSION['contact_form_values'] = [
            'name' => is_string($_POST['name'] ?? null) ? trim($_POST['name']) : '',
            'email' => is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '',
            'phone' => is_string($_POST['phone'] ?? null) ? trim($_POST['phone']) : '',
            'subject' => is_string($_POST['subject'] ?? null) ? trim($_POST['subject']) : '',
            'message' => is_string($_POST['message'] ?? null) ? trim($_POST['message']) : '',
        ];
        header('Location: contact.php#contact-form');
        exit;
    };

    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($submittedToken) || !hash_equals(csrfToken(), $submittedToken)) {
        $respondToContact(false, 'Your form session expired. Please reload the page and try again.', 403);
    }

    $formValues = [];
    foreach (['name', 'email', 'phone', 'subject', 'message'] as $field) {
        $value = $_POST[$field] ?? '';
        if (!is_string($value)) {
            $respondToContact(false, 'Please submit valid form values.', 400);
        }
        $formValues[$field] = trim($value);
    }

    $validationErrors = [];
    if ($formValues['name'] === '' || mb_strlen($formValues['name']) > 100) {
        $validationErrors[] = 'Enter your name (up to 100 characters).';
    }
    if (
        $formValues['email'] === ''
        || mb_strlen($formValues['email']) > 150
        || filter_var($formValues['email'], FILTER_VALIDATE_EMAIL) === false
    ) {
        $validationErrors[] = 'Enter a valid email address (up to 150 characters).';
    }
    if (mb_strlen($formValues['phone']) > 20) {
        $validationErrors[] = 'The contact number must be 20 characters or fewer.';
    }
    if (mb_strlen($formValues['subject']) > 200) {
        $validationErrors[] = 'The subject must be 200 characters or fewer.';
    }
    if ($formValues['message'] === '') {
        $validationErrors[] = 'Enter a message.';
    }
    if ($validationErrors) {
        $respondToContact(false, implode(' ', $validationErrors), 422);
    }

    try {
        $statement = getDB()->prepare(
            'INSERT INTO leads (id, name, email, phone, subject, message, status, created_at, updated_at)
             VALUES (:id, :name, :email, :phone, :subject, :message, \'new\', UTC_TIMESTAMP(), UTC_TIMESTAMP())'
        );
        $statement->execute([
            'id' => newAdminId(),
            'name' => $formValues['name'],
            'email' => $formValues['email'],
            'phone' => $formValues['phone'] === '' ? null : $formValues['phone'],
            'subject' => $formValues['subject'] === '' ? null : $formValues['subject'],
            'message' => $formValues['message'],
        ]);
    } catch (PDOException $exception) {
        error_log('Contact lead could not be saved to MySQL.');
        $respondToContact(false, 'We could not save your message right now. Please try again later.', 503);
    } catch (RuntimeException $exception) {
        error_log('Contact form database operation failed.');
        $respondToContact(false, 'The contact service is temporarily unavailable. Please try again later.', 503);
    }

    $respondToContact(true, 'Thank you. Your message has been sent successfully.');
}

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
                                            <a href="tel:<?php echo SITE_PHONE; ?>"><?php echo SITE_PHONE; ?></a>
                                        </h3>
                                    </div>
                                </div>
                                <div class="icon-item wow fadeInUp" data-wow-delay=".8s">
                                    <div class="icon">
                                        <img src="assets/img/home-2/icon/11.svg" alt="img">
                                    </div>
                                    <div class="cont">
                                        <span>call for details</span>
                                        <h3>
                                            <a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a>
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
                                            <?php echo SITE_ADDRESS; ?>
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
                

<?php require_once 'includes/footer.php'; ?>
