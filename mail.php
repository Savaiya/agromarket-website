<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = $_POST['fname'];
    $email   = $_POST['email'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        // SMTP Config (Hostinger)
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@agromarket.co.in'; // 🔴 Hostinger email
        $mail->Password   = '@Parv#123';        // 🔴 email password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Mail Headers
        $mail->setFrom('info@agromarket.co.in', 'Agro Market Website');
        $mail->addAddress('info@agromarket.co.in'); // receiver

        // Reply user email
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Message';
        $mail->Body = "
            <h3>New Contact Message</h3>
            <p><b>Name:</b> $name</p>
            <p><b>Email:</b> $email</p>
            <p><b>Message:</b><br>$message</p>
        ";

        $mail->send();
        echo "success";

    } catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
}
?>
