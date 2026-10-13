<?php

define('ADMIN_SITE_NAME', 'AgroMarket Admin');

$localConfigCandidates = [
    dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'config.local.php',
    dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config.local.php',
    dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'config.local.php',
    __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.local.php',
];

$localConfigPath = null;
foreach ($localConfigCandidates as $candidate) {
    if (is_file($candidate)) {
        $localConfigPath = $candidate;
        break;
    }
}

$localConfig = $localConfigPath !== null ? require $localConfigPath : [];

if (!is_array($localConfig)) {
    throw new RuntimeException('Project-local configuration must return an array.');
}

$mysqlHost = $localConfig['mysql_host'] ?? (getenv('AGROMARKET_MYSQL_HOST') ?: '127.0.0.1');
$mysqlDatabase = $localConfig['mysql_database'] ?? (getenv('AGROMARKET_MYSQL_DATABASE') ?: 'agromarket_admin');
$mysqlUsername = $localConfig['mysql_username'] ?? (getenv('AGROMARKET_MYSQL_USERNAME') ?: '');
$mysqlPassword = $localConfig['mysql_password'] ?? (getenv('AGROMARKET_MYSQL_PASSWORD') ?: '');

define('AGROMARKET_MYSQL_HOST', $mysqlHost);
define('AGROMARKET_MYSQL_DATABASE', $mysqlDatabase);
define('AGROMARKET_MYSQL_USERNAME', $mysqlUsername);
define('AGROMARKET_MYSQL_PASSWORD', $mysqlPassword);

ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_samesite', 'Lax');
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', '1');
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/database.php';

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
