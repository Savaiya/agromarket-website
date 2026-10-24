<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;
use PDOException;
use RuntimeException;

class ContactController
{
    public function index(): void
    {
        $contactNotice = $_SESSION['contact_form_notice'] ?? null;
        $contactFormValues = $_SESSION['contact_form_values'] ?? [];
        unset($_SESSION['contact_form_notice'], $_SESSION['contact_form_values']);

        view('contact', [
            'pageTitle' => 'Contact Us',
            'contactNotice' => $contactNotice,
            'contactFormValues' => $contactFormValues,
        ]);
    }

    public function submit(): void
    {
        $isAjax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';

        $respond = function (bool $success, string $message, int $statusCode = 200) use ($isAjax): void {
            if ($isAjax) {
                http_response_code($statusCode);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => $success, 'message' => $message]);
                exit;
            }

            $_SESSION['contact_form_notice'] = ['success' => $success, 'message' => $message];
            $_SESSION['contact_form_values'] = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'subject' => trim($_POST['subject'] ?? ''),
                'message' => trim($_POST['message'] ?? ''),
            ];
            header('Location: contact.php#contact-form');
            exit;
        };

        // CSRF verification
        $token = $_POST['csrf_token'] ?? '';
        if (!is_string($token) || !hash_equals(csrfToken(), $token)) {
            $respond(false, 'Your session expired. Please reload the page and try again.', 403);
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = [];
        if ($name === '' || mb_strlen($name) > 100) {
            $errors[] = 'Enter your name (up to 100 characters).';
        }
        if ($email === '' || mb_strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }
        if (mb_strlen($phone) > 20) {
            $errors[] = 'Contact number must be 20 characters or fewer.';
        }
        if (mb_strlen($subject) > 200) {
            $errors[] = 'Subject must be 200 characters or fewer.';
        }
        if ($message === '') {
            $errors[] = 'Enter a message.';
        }

        if (!empty($errors)) {
            $respond(false, implode(' ', $errors), 422);
        }

        try {
            $statement = getDB()->prepare(
                'INSERT INTO leads (id, name, email, phone, subject, message, status, created_at, updated_at)
                 VALUES (:id, :name, :email, :phone, :subject, :message, \'new\', UTC_TIMESTAMP(), UTC_TIMESTAMP())'
            );
            $statement->execute([
                'id' => newAdminId(),
                'name' => $name,
                'email' => $email,
                'phone' => $phone === '' ? null : $phone,
                'subject' => $subject === '' ? null : $subject,
                'message' => $message,
            ]);
        } catch (PDOException|RuntimeException $e) {
            error_log('Contact form error saving lead: ' . $e->getMessage());
            $respond(false, 'We could not save your message right now. Please try again later.', 503);
        }

        try {
            $this->sendNotification($name, $email, $subject);
        } catch (PHPMailerException|RuntimeException $e) {
            error_log('Contact form lead was saved, but SMTP notification failed: ' . $e->getMessage());
            $respond(false, 'Your message was saved, but we could not send a confirmation email. Please contact us directly if your request is urgent.', 503);
        }

        $respond(true, 'Thank you. Your message has been sent successfully.');
    }

    private function sendNotification(string $name, string $email, string $subject): void
    {
        $smtp = $GLOBALS['config']['smtp'] ?? [];
        $replyEmail = $GLOBALS['config']['contact']['email'] ?? '';
        $host = $smtp['host'] ?? '';
        $username = $smtp['username'] ?? '';
        $password = $smtp['password'] ?? '';
        $fromEmail = $smtp['from_email'] ?? '';
        $fromName = $smtp['from_name'] ?? 'AgroMarket';
        $port = $smtp['port'] ?? 0;
        $encryption = strtolower((string) ($smtp['encryption'] ?? ''));

        if (
            !is_string($host) || $host === ''
            || !is_string($username) || $username === ''
            || !is_string($password) || $password === ''
            || !is_string($fromEmail) || filter_var($fromEmail, FILTER_VALIDATE_EMAIL) === false
            || !is_string($replyEmail) || filter_var($replyEmail, FILTER_VALIDATE_EMAIL) === false
            || !is_int($port) || $port < 1 || $port > 65535
        ) {
            throw new RuntimeException('Contact SMTP configuration is incomplete or invalid.');
        }

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->Port = $port;
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->WordWrap = 78;

        if ($encryption === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($encryption !== '') {
            throw new RuntimeException('Contact SMTP encryption must be tls, ssl, or empty.');
        }

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($email, $name);
        $mail->addReplyTo($replyEmail, $GLOBALS['config']['site']['name']);
        $mail->Subject = 'We received your message';
        $mail->Body = implode("\n", [
            'Hello ' . $name . ',',
            '',
            'Thank you for contacting AgroMarket. We have received your message and will get back to you soon.',
            '',
            'Your message: ' . ($subject !== '' ? $subject : 'General inquiry'),
            '',
            'If you did not submit this request, you can ignore this email.',
        ]);

        $mail->send();
    }
}
