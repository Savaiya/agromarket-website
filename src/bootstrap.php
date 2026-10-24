<?php
/**
 * Application Bootstrap & Helper Functions
 */

// Load Application Configuration
require_once dirname(__DIR__) . '/config/config.php';



// PSR-4 Style Autoloader for App namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

/**
 * Global Database Access Helper
 */
if (!function_exists('getDB')) {
    function getDB(): PDO
    {
        return \App\Database::getInstance()->getConnection();
    }
}

/**
 * HTML Output Escaper
 */
if (!function_exists('e')) {
    function e(string $value): void
    {
        echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Format Page Title
 */
if (!function_exists('pageTitle')) {
    function pageTitle(string $title): string
    {
        return htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . ' | ' . $GLOBALS['config']['site']['name'];
    }
}

/**
 * Navigation Active State Helper
 */
if (!function_exists('active')) {
    function active(string $page, string $class = 'active'): void
    {
        $currentUri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
        $checkUri = trim($page, '/');
        if ($currentUri === $checkUri || basename($_SERVER['PHP_SELF'] ?? '') === $checkUri) {
            echo $class;
        }
    }
}

/**
 * Redirect Helper
 */
if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}

/**
 * CSRF Token Generator & Validator
 */
if (!function_exists('csrfToken')) {
    function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('requireValidCsrfToken')) {
    function requireValidCsrfToken(): void
    {
        $submitted = $_POST['csrf_token'] ?? '';
        if (!is_string($submitted) || !hash_equals(csrfToken(), $submitted)) {
            http_response_code(403);
            exit('Invalid CSRF token. Please reload the page and try again.');
        }
    }
}

/**
 * Generate standard 32-character Hexadecimal UUID
 */
if (!function_exists('newAdminId')) {
    function newAdminId(): string
    {
        return bin2hex(random_bytes(16));
    }
}

if (!function_exists('isAdminId')) {
    function isAdminId($id): bool
    {
        return is_string($id) && preg_match('/^[a-f0-9]{32}$/', $id) === 1;
    }
}

/**
 * View Rendering Helper
 */
function view(string $viewName, array $data = []): void
{
    extract($data);
    $viewFile = dirname(__DIR__) . '/views/pages/' . $viewName . '.php';

    if (!file_exists($viewFile)) {
        http_response_code(404);
        $viewFile = dirname(__DIR__) . '/views/pages/404.php';
    }

    require_once dirname(__DIR__) . '/views/layouts/header.php';
    require $viewFile;
    require_once dirname(__DIR__) . '/views/layouts/footer.php';
}
