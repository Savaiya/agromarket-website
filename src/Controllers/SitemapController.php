<?php

namespace App\Controllers;

use PDOException;
use RuntimeException;
use XMLWriter;

class SitemapController
{
    public function xml(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        header('Cache-Control: public, max-age=3600');

        $baseUrl = rtrim($GLOBALS['siteUrl'], '/');
        $today = gmdate('Y-m-d');

        $urls = [
            ['loc' => $baseUrl . '/index.php', 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => $baseUrl . '/about.php', 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $baseUrl . '/product.php', 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => $baseUrl . '/service-2.php', 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $baseUrl . '/blogs.php', 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => $baseUrl . '/blogs-grid.php', 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => $baseUrl . '/contact.php', 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $baseUrl . '/privacy.php', 'lastmod' => $today, 'changefreq' => 'yearly', 'priority' => '0.5'],
        ];

        try {
            $statement = getDB()->query(
                "SELECT slug, updated_at, published_at
                 FROM blogs
                 WHERE status = 'published' AND slug <> ''
                 ORDER BY COALESCE(published_at, created_at) DESC"
            );
            foreach ($statement->fetchAll() as $blog) {
                $dateValue = $blog['updated_at'] ?? $blog['published_at'] ?? null;
                $timestamp = $dateValue ? strtotime((string) $dateValue) : false;
                $urls[] = [
                    'loc' => $baseUrl . '/blogs-details.php?slug=' . rawurlencode((string) $blog['slug']),
                    'lastmod' => $timestamp === false ? $today : gmdate('Y-m-d', $timestamp),
                    'changefreq' => 'monthly',
                    'priority' => '0.8',
                ];
            }
        } catch (PDOException|RuntimeException $e) {
            error_log('Sitemap error loading blogs: ' . $e->getMessage());
        }

        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($urls as $url) {
            $writer->startElement('url');
            $writer->writeElement('loc', $url['loc']);
            $writer->writeElement('lastmod', $url['lastmod']);
            $writer->writeElement('changefreq', $url['changefreq']);
            $writer->writeElement('priority', $url['priority']);
            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();
        echo $writer->outputMemory();
        exit;
    }
}
