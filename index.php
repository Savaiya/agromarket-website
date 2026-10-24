<?php
/**
 * AMPL Agro Market - Front Controller & Application Entry Point
 */

require_once __DIR__ . '/src/bootstrap.php';

use App\Router;
use App\Controllers\HomeController;
use App\Controllers\PageController;
use App\Controllers\ProductController;
use App\Controllers\BlogController;
use App\Controllers\ContactController;
use App\Controllers\SearchController;
use App\Controllers\SitemapController;

$router = new Router();

// Core Navigation
$router->get('/', [HomeController::class, 'index']);
$router->get('/home', [HomeController::class, 'index']);

// Company Pages
$router->get('/about', [PageController::class, 'about']);
$router->get('/about-2', [PageController::class, 'about2']);
$router->get('/services', [PageController::class, 'services']);
$router->get('/service-2', [PageController::class, 'services']);
$router->get('/service-details', [PageController::class, 'serviceDetails']);
$router->get('/faq', [PageController::class, 'faq']);
$router->get('/privacy', [PageController::class, 'privacy']);
$router->get('/pricing', [PageController::class, 'pricing']);
$router->get('/team', [PageController::class, 'team']);
$router->get('/team-details', [PageController::class, 'teamDetails']);
$router->get('/project', [PageController::class, 'project']);
$router->get('/project-details', [PageController::class, 'projectDetails']);
$router->get('/checkout', [PageController::class, 'checkout']);
$router->get('/shop-cart', [PageController::class, 'shopCart']);
$router->get('/shop-details', [PageController::class, 'shopDetails']);
$router->get('/shop-grid', [PageController::class, 'shopGrid']);

// Products & Catalog
$router->get('/product', [ProductController::class, 'index']);
$router->get('/products', [ProductController::class, 'index']);
$router->get('/product-details', [ProductController::class, 'details']);

// Blogs & Articles
$router->get('/blogs', [BlogController::class, 'index']);
$router->get('/blogs-grid', [BlogController::class, 'grid']);
$router->get('/blogs-details', [BlogController::class, 'details']);

// Contact & Lead Capture
$router->get('/contact', [ContactController::class, 'index']);
$router->post('/contact', [ContactController::class, 'submit']);

// APIs & Utilities
$router->any('/search', [SearchController::class, 'search']);
$router->get('/sitemap.xml', [SitemapController::class, 'xml']);
$router->get('/sitemap', [SitemapController::class, 'xml']);

$router->dispatch();