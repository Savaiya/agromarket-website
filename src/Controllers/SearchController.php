<?php

namespace App\Controllers;

use PDOException;
use RuntimeException;

class SearchController
{
    public function search(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');

        $query = trim((string) ($_GET['q'] ?? ''));
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
                       AND (title LIKE :q OR short_description LIKE :q OR content LIKE :q)
                     ORDER BY COALESCE(published_at, created_at) DESC, created_at DESC
                     LIMIT 8"
                );
                $like = '%' . $query . '%';
                $statement->execute(['q' => $like]);

                foreach ($statement->fetchAll() as $blog) {
                    $blogs[] = [
                        'title' => $blog['title'],
                        'category' => 'Blog',
                        'image' => $blog['featured_image'] ?: 'assets/img/inner-page/news/02.jpg',
                        'url' => 'blogs-details.php?slug=' . rawurlencode($blog['slug']),
                    ];
                }
            } catch (PDOException|RuntimeException $e) {
                error_log('Search query failed against MySQL: ' . $e->getMessage());
                // Fallback to static catalog without failing HTTP status
            }
        }

        echo json_encode(['products' => $products, 'blogs' => $blogs]);
        exit;
    }
}
