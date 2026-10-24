<?php

namespace App\Controllers;

use PDOException;
use RuntimeException;

class HomeController
{
    public function index(): void
    {
        $recentBlogs = [];
        try {
            $recentBlogs = getDB()->query(
                "SELECT title, slug, short_description, featured_image, published_at, created_at
                 FROM blogs
                 WHERE status = 'published'
                 ORDER BY COALESCE(published_at, created_at) DESC, created_at DESC, id DESC
                 LIMIT 4"
            )->fetchAll();
        } catch (PDOException|RuntimeException $e) {
            error_log('Homepage recent blogs could not be loaded: ' . $e->getMessage());
        }

        if (empty($recentBlogs)) {
            $recentBlogs = [
                [
                    'title' => 'Product Sourcing',
                    'short_description' => 'We carefully source products from trusted manufacturers, including our own brand and well-known market brands.',
                    'featured_image' => 'assets/img/home-1/testimonial/03.png',
                    'slug' => '',
                ],
                [
                    'title' => 'Quality Check',
                    'short_description' => 'Every product goes through a strict quality inspection to ensure safety, reliability, and performance.',
                    'featured_image' => 'assets/img/home-1/testimonial/02.png',
                    'slug' => '',
                ],
                [
                    'title' => 'Easy Purchase',
                    'short_description' => 'Customers can buy products easily and quickly, whether retail or bulk.',
                    'featured_image' => 'assets/img/home-1/testimonial/05.png',
                    'slug' => '',
                ],
                [
                    'title' => 'Smart Storage',
                    'short_description' => 'Products are stored in well-managed warehouses to maintain freshness and quality.',
                    'featured_image' => 'assets/img/home-1/testimonial/05.png',
                    'slug' => '',
                ],
            ];
        }

        view('home', [
            'pageTitle' => 'Home - Maize Farming',
            'recentBlogs' => $recentBlogs,
        ]);
    }
}
