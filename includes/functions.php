<?php
/**
 * includes/functions.php
 * Reusable helper functions for the Agriva PHP project.
 */

/**
 * Returns true if the given filename matches the current script.
 * Useful for adding an "active" class to nav links.
 *
 * Usage: class="<?php active('about.php'); ?>"
 */
function active(string $page, string $class = 'active'): void
{
    if (basename($_SERVER['PHP_SELF']) === $page) {
        echo $class;
    }
}

/**
 * Outputs a safe, HTML-encoded string.
 */
function e(string $value): void
{
    echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirects to a given URL and stops execution.
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Returns a formatted page title string.
 */
function pageTitle(string $title): string
{
    return htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . ' | ' . SITE_NAME;
}

/**
 * Validates and sanitizes a plain text input.
 * Returns the cleaned string or an empty string on failure.
 */
function sanitizeText(string $input, int $maxLen = 255): string
{
    $clean = trim(strip_tags($input));
    return mb_substr($clean, 0, $maxLen);
}

/**
 * Validates an email address.
 */
function isValidEmail(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Simple contact-form mailer.
 * Returns true on success, false on failure.
 *
 * @param string $name    Sender name
 * @param string $email   Sender email
 * @param string $subject Message subject
 * @param string $message Message body
 */
function sendContactMail(string $name, string $email, string $subject, string $message): bool
{
    if (!isValidEmail($email)) {
        return false;
    }

    $to      = SITE_EMAIL;
    $subject = sanitizeText($subject, 100);
    $body    = "Name: $name\nEmail: $email\n\n" . sanitizeText($message, 2000);
    $headers = implode("\r\n", [
        'From: ' . sanitizeText($name, 60) . ' <' . $email . '>',
        'Reply-To: ' . $email,
        'X-Mailer: PHP/' . PHP_VERSION,
        'Content-Type: text/plain; charset=UTF-8',
    ]);

    return mail($to, $subject, $body, $headers);
}
