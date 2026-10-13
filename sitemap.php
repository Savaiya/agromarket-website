<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/admin/config.php';

header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=3600');

$baseUrl = rtrim(SITE_URL, '/');
$urls = [
    ['loc' => $baseUrl . '/index.php', 'lastmod' => gmdate('Y-m-d'), 'changefreq' => 'weekly', 'priority' => '1.0'],
    ['loc' => $baseUrl . '/blogs.php', 'lastmod' => gmdate('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9'],
    ['loc' => $baseUrl . '/blogs-grid.php', 'lastmod' => gmdate('Y-m-d'), 'changefreq' => 'weekly', 'priority' => '0.7'],
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
            'lastmod' => $timestamp === false ? gmdate('Y-m-d') : gmdate('Y-m-d', $timestamp),
            'changefreq' => 'monthly',
            'priority' => '0.8',
        ];
    }
} catch (PDOException $exception) {
    error_log('The XML sitemap blog URLs could not be loaded from MySQL.');
} catch (RuntimeException $exception) {
    error_log('The XML sitemap blog URLs could not be loaded.');
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
