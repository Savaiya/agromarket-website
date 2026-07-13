<?php
require_once 'admin/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name    = htmlspecialchars($_POST['fname']);
    $email   = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $subject = htmlspecialchars($_POST['subject'] ?? 'New Lead From Contact Form');
    $phone   = htmlspecialchars($_POST['phone'] ?? '');

    // Save to database
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO leads (name, email, phone, subject, message, status, created_at) VALUES (?, ?, ?, ?, ?, 'new', NOW())");
        $stmt->execute([$name, $email, $phone, $subject, $message]);
    } catch (Exception $e) {
        // Log error or ignore if database fails but we still want to send mail
    }

    $to = "info@agromarket.co.in";
    $mail_subject = "New Lead: " . $name;

    // ✅ GOOD LOOKING MAIL UI
    $body = '
    <html>
    <head>
      <style>
        body {
          background:#f4f6f8;
          font-family: Arial, sans-serif;
          padding:20px;
        }
        .card {
          max-width:600px;
          margin:auto;
          background:#ffffff;
          border-radius:10px;
          padding:20px;
          box-shadow:0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
          color:#2e7d32;
          border-bottom:1px solid #eee;
          padding-bottom:10px;
        }
        .row {
          margin:12px 0;
        }
        .label {
          font-weight:bold;
          color:#555;
        }
        .message-box {
          background:#f1f1f1;
          padding:12px;
          border-radius:6px;
          margin-top:6px;
          white-space:pre-line;
        }
        .footer {
          margin-top:20px;
          font-size:12px;
          color:#888;
          text-align:center;
        }
      </style>
    </head>
    <body>
      <div class="card">
        <h2>📩 New Contact Lead</h2>

        <div class="row">
          <span class="label">Name:</span><br>
          '.$name.'
        </div>

        <div class="row">
          <span class="label">Email:</span><br>
          '.$email.'
        </div>

        <div class="row">
          <span class="label">Message:</span>
          <div class="message-box">
            '.$message.'
          </div>
        </div>

        <div class="footer">
          This message was sent from AgroMarket Contact Form
        </div>
      </div>
    </body>
    </html>
    ';

    // ✅ REQUIRED FOR HTML MAIL
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: AgroMarket <info@agromarket.co.in>\r\n";

    if (mail($to, $mail_subject, $body, $headers)) {
        header("Location: contact.php?status=success");
        exit;
    } else {
        header("Location: contact.php?status=error");
        exit;
    }
}
?>
