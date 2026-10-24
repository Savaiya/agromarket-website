<?php

namespace App\Controllers;

use PDOException;
use RuntimeException;

class BlogController
{
    private array $fallbackSets;

    public function __construct()
    {
        $this->fallbackSets = require dirname(__DIR__, 2) . '/includes/blog-fallbacks.php';
        require_once dirname(__DIR__, 2) . '/includes/blog-associations.php';
    }

    public function index(): void
    {
        $blogSearch = trim((string) ($_GET['search'] ?? ''));
        $blogCategory = trim((string) ($_GET['category'] ?? ''));
        $requestedBlogSlug = trim((string) ($_GET['slug'] ?? ''));
        $showSelectedBlog = $requestedBlogSlug !== '';
        $selectedBlogSlug = $showSelectedBlog ? $requestedBlogSlug : '';
        $selectedBlog = null;

        $fallbackBlogs = $this->fallbackSets['standard'];
        $categories = [
            ['name' => 'Agriculture', 'slug' => 'agriculture', 'post_count' => 8],
            ['name' => 'Vegetable Farming', 'slug' => 'vegetable-farming', 'post_count' => 2],
            ['name' => 'Economy Solution', 'slug' => 'economy-solution', 'post_count' => 10],
            ['name' => 'Harvests Innovations', 'slug' => 'harvests-innovations', 'post_count' => 15],
            ['name' => 'Organic Food', 'slug' => 'organic-food', 'post_count' => 12],
            ['name' => 'Fresh Vegetables', 'slug' => 'fresh-vegetables', 'post_count' => 7],
        ];
        $popularTags = [
            ['tag' => 'Agriculture', 'count' => 1],
            ['tag' => 'Organic', 'count' => 1],
            ['tag' => 'Technology', 'count' => 1],
            ['tag' => 'Sustainability', 'count' => 1],
            ['tag' => 'Harvest', 'count' => 1],
            ['tag' => 'Farming', 'count' => 1],
            ['tag' => 'Crop Care', 'count' => 1],
            ['tag' => 'Agribusiness', 'count' => 1],
            ['tag' => 'Food Security', 'count' => 1],
            ['tag' => 'Innovation', 'count' => 1],
        ];
        $recentPosts = array_slice(array_merge($fallbackBlogs, $this->fallbackSets['grid']), 0, 5);

        $blogs = [];
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        $totalPages = 1;

        try {
            $db = getDB();
            // Fetch live blogs if available
            $where = ["status = 'published'"];
            $params = [];

            if ($blogSearch !== '') {
                $where[] = "(title LIKE :search OR short_description LIKE :search OR content LIKE :search)";
                $params['search'] = "%{$blogSearch}%";
            }

            $whereSql = implode(' AND ', $where);
            $countStmt = $db->prepare("SELECT COUNT(*) FROM blogs WHERE {$whereSql}");
            $countStmt->execute($params);
            $totalCount = (int) $countStmt->fetchColumn();
            $blogsPerPage = 5;
            $totalPages = max(1, (int) ceil($totalCount / $blogsPerPage));
            $offset = ($currentPage - 1) * $blogsPerPage;

            $stmt = $db->prepare(
                "SELECT id, title, slug, short_description, featured_image, author, published_at, created_at
                 FROM blogs
                 WHERE {$whereSql}
                 ORDER BY COALESCE(published_at, created_at) DESC, created_at DESC
                 LIMIT {$blogsPerPage} OFFSET {$offset}"
            );
            $stmt->execute($params);
            $blogs = $stmt->fetchAll();

            if (!empty($blogs)) {
                $blogIds = array_column($blogs, 'id');
                $associations = loadBlogAssociations($db, $blogIds);
                foreach ($blogs as &$item) {
                    $item['categories'] = $associations[$item['id']]['categories'] ?? [];
                    $item['tags'] = $associations[$item['id']]['tags'] ?? [];
                }
                unset($item);
            }
        } catch (PDOException|RuntimeException $e) {
            error_log('BlogController index DB error: ' . $e->getMessage());
        }

        if (empty($blogs) && $blogSearch === '' && $blogCategory === '') {
            $blogs = $fallbackBlogs;
        }

        view('blogs', [
            'pageTitle' => 'Blog Standard',
            'blogs' => $blogs,
            'categories' => $categories,
            'popularTags' => $popularTags,
            'recentPosts' => $recentPosts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'blogSearch' => $blogSearch,
            'blogCategory' => $blogCategory,
        ]);
    }

    public function details(): void
    {
        $slug = trim((string) ($_GET['slug'] ?? ''));
        $blog = null;
        $suggestedBlogs = [];

        if ($slug !== '') {
            try {
                $db = getDB();
                $stmt = $db->prepare(
                    "SELECT b.id, b.title, b.slug, b.short_description, b.content, b.featured_image,
                            b.author, b.published_at, b.created_at, c.name AS category_name
                     FROM blogs b
                     LEFT JOIN blog_categories c ON c.id = b.category_id
                     WHERE b.slug = :slug AND b.status = 'published'
                     LIMIT 1"
                );
                $stmt->execute(['slug' => $slug]);
                $blog = $stmt->fetch() ?: null;

                if ($blog) {
                    $associations = loadBlogAssociations($db, [$blog['id']]);
                    $blog['categories'] = $associations[$blog['id']]['categories'] ?? [];
                    $blog['tags'] = $associations[$blog['id']]['tags'] ?? [];

                    $sugStmt = $db->prepare(
                        "SELECT title, slug, short_description, featured_image, published_at, created_at
                         FROM blogs
                         WHERE status = 'published' AND slug <> :slug
                         ORDER BY COALESCE(published_at, created_at) DESC
                         LIMIT 5"
                    );
                    $sugStmt->execute(['slug' => $slug]);
                    $suggestedBlogs = $sugStmt->fetchAll();
                }
            } catch (PDOException|RuntimeException $e) {
                error_log('BlogController details DB error: ' . $e->getMessage());
            }
        }

        if (!$blog) {
            $allFallbacks = array_merge($this->fallbackSets['standard'], $this->fallbackSets['grid']);
            foreach ($allFallbacks as $item) {
                if ($item['slug'] === $slug) {
                    $blog = $item;
                    $blog['categories'] = [['name' => $item['category_name'] ?? 'Agriculture', 'slug' => 'agriculture']];
                    $blog['tags'] = [['name' => 'General', 'slug' => 'general']];
                    break;
                }
            }
            if (!$blog && !empty($allFallbacks)) {
                $blog = $allFallbacks[0];
            }
        }

        view('blogs-details', [
            'pageTitle' => $blog['title'] ?? 'Blog Details',
            'blog' => $blog,
            'suggestedBlogs' => $suggestedBlogs,
            'slug' => $slug,
        ]);
    }

    public function grid(): void
    {
        view('blogs-grid', [
            'pageTitle' => 'Blog Grid',
            'fallbackSets' => $this->fallbackSets,
        ]);
    }
}
