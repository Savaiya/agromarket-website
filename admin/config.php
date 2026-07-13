<?php
require_once 'database.php';
// Admin configuration
define('ADMIN_PATH', __DIR__);
define('SITE_NAME', 'AMPL Agro Market Admin');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'agromarket');
define('DB_USER', 'agromarket');
define('DB_PASS', '8ttBAimMmPPuuPSyMT4Q');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Check if already logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Get current admin user
function getAdminUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['admin_id'],
            'name' => $_SESSION['admin_name'],
            'email' => $_SESSION['admin_email']
        ];
    }
    return null;
}