<?php
require_once __DIR__ . '/admin/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$query = $_GET['q'] ?? '';
if (!is_string($query)) {
    http_response_code(400);
    echo json_encode(['products' => [], 'blogs' => [], 'error' => 'Invalid search query.']);
    exit;
}

$query = trim($query);
if (mb_strlen($query) > 100) {
    $query = mb_substr($query, 0, 100);
}

$productCatalog = [
    ['title' => 'Hybrid Maize Seed', 'category' => 'Seed · Hybrid', 'image' => 'assets/img/inner-page/service/01.jpg'],
    ['title' => 'Organic Compost', 'category' => 'Fertilizer · Organic', 'image' => 'assets/img/inner-page/service/02.jpg'],
    ['title' => 'Crop Protection Spray', 'category' => 'Pesticide · Crop Care', 'image' => 'assets/img/inner-page/service/03.jpg'],
    ['title' => 'Drip Irrigation System', 'category' => 'Irrigation · Drip', 'image' => 'assets/img/inner-page/service/04.jpg'],
    ['title' => 'Vegetable Hybrid Seed', 'category' => 'Seed · Hybrid', 'image' => 'assets/img/inner-page/service/05.jpg'],
    ['title' => 'Balanced NPK Fertilizer', 'category' => 'Fertilizer · NPK', 'image' => 'assets/img/inner-page/service/06.jpg'],
    ['title' => 'Plant Growth Regulator', 'category' => 'Pesticide · Growth', 'image' => 'assets/img/inner-page/service/07.jpg'],
    ['title' => 'Smart Irrigation Controller', 'category' => 'Irrigation · Smart', 'image' => 'assets/img/inner-page/service/04.jpg'],
];

$products = [];
$blogs = [];
if (mb_strlen($query) >= 2) {
    $normalizedQuery = mb_strtolower($query);
    foreach ($productCatalog as $product) {
        if (
            mb_stripos($product['title'], $normalizedQuery) !== false
            || mb_stripos($product['category'], $normalizedQuery) !== false
        ) {
            $products[] = [
                'title' => $product['title'],
                'category' => $product['category'],
                'image' => $product['image'],
                'url' => 'product.php?search=' . rawurlencode($product['title']),
            ];
        }
    }

    try {
        $statement = getDB()->prepare(
            "SELECT title, slug, short_description, featured_image
             FROM blogs
             WHERE status = 'published'
               AND (title LIKE :title_query OR short_description LIKE :description_query OR content LIKE :content_query)
             ORDER BY COALESCE(published_at, created_at) DESC, created_at DESC, id DESC
             LIMIT 8"
        );
        $likeQuery = '%' . $query . '%';
        $statement->execute([
            'title_query' => $likeQuery,
            'description_query' => $likeQuery,
            'content_query' => $likeQuery,
        ]);

        foreach ($statement->fetchAll() as $blog) {
            $blogs[] = [
                'title' => $blog['title'],
                'category' => 'Blog',
                'image' => $blog['featured_image'] ?: 'assets/img/inner-page/news/02.jpg',
                'url' => 'blogs.php?search=' . rawurlencode($query),
            ];
        }
    } catch (PDOException $exception) {
        error_log('Search blog query failed against MySQL.');
        http_response_code(503);
        echo json_encode(['products' => $products, 'blogs' => [], 'error' => 'Blog search is temporarily unavailable.']);
        exit;
    } catch (RuntimeException $exception) {
        error_log('Search blog query could not connect to MySQL: ' . $exception->getMessage());
        http_response_code(503);
        echo json_encode(['products' => $products, 'blogs' => [], 'error' => 'Blog search is temporarily unavailable.']);
        exit;
    }
}

echo json_encode(['products' => $products, 'blogs' => $blogs]);
