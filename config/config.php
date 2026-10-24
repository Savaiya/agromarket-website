<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();

$env = static function (string $key, string $default = ''): string {
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    return is_string($value) ? $value : $default;
};

$config = [
    'site' => [
        'name' => 'AgroMarket Private Limited',
        'tagline' => 'AgroMarket',
        'url' => 'http://localhost/agro',
        'author' => 'Parv Yadav',
        'description' => 'AgroMarket Website',
    ],
    'contact' => [
        'phone' => '+91 99939 55051',
        'email' => 'info@agromarket.co.in',
        'address' => '3rd Floor, PU4 Vijay Nagar, Near Indo Thai Security, Indore - 452010.',
        'hours' => 'ISO No.-INQ/AN-25713/129663/1225',
    ],
    'assets' => 'assets',
    'title_suffix' => 'AgroMarket Private Limited - AgroMarket HTML Template',
    'admin' => [
        'site_name' => 'AgroMarket Admin',
    ],
    'database' => [
        'host' => $env('AGROMARKET_MYSQL_HOST', '127.0.0.1'),
        'database' => $env('AGROMARKET_MYSQL_DATABASE', 'agromarket'),
        'username' => $env('AGROMARKET_MYSQL_USERNAME', 'root'),
        'password' => $env('AGROMARKET_MYSQL_PASSWORD'),
    ],
    'smtp' => [
        'host' => $env('SMTP_HOST'),
        'port' => (int) $env('SMTP_PORT', '587'),
        'username' => $env('SMTP_USERNAME'),
        'password' => preg_replace('/\s+/', '', $env('SMTP_PASSWORD')),
        'encryption' => $env('SMTP_ENCRYPTION', 'tls'),
        'from_email' => $env('SMTP_FROM_EMAIL'),
        'from_name' => $env('SMTP_FROM_NAME', 'AgroMarket'),
    ],
];

$adminSiteName = $config['admin']['site_name'];

ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_samesite', 'Lax');
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', '1');
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../admin/database.php';

function adminUrl(string $path = ''): string
{
    $adminPath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/admin/index.php'));
    $adminBase = rtrim($adminPath, '/');

    return $adminBase . '/' . ltrim($path, '/');
}

function isLoggedIn()
{
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: ' . adminUrl('login.php'));
        exit;
    }
}

function getAdminUser()
{
    if (!isLoggedIn()) {
        return null;
    }

    return [
        'id' => $_SESSION['admin_id'],
        'name' => $_SESSION['admin_name'],
        'email' => $_SESSION['admin_email'],
    ];
}

function csrfToken()
{
    if (empty($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['admin_csrf_token'];
}

function requireValidCsrfToken()
{
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($submittedToken) || !hash_equals(csrfToken(), $submittedToken)) {
        http_response_code(403);
        exit('Invalid request token. Please go back, reload the page, and try again.');
    }
}

return $config;
