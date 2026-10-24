<?php

require_once __DIR__ . '/../../../config/config.php';

class DashboardController
{
    public function handle(): array
    {
        requireLogin();

        try {
            $db = getDB();
            $leadStats = $db->query(
                "SELECT COUNT(*) AS total, SUM(status = 'new') AS new_count,
                        SUM(status = 'converted') AS converted_count
                 FROM leads"
            )->fetch();
            $blogStats = $db->query(
                "SELECT COUNT(*) AS total, SUM(status = 'published') AS published_count
                 FROM blogs"
            )->fetch();

            return [
                'totalLeads' => ['count' => (int) $leadStats['total']],
                'newLeads' => ['count' => (int) $leadStats['new_count']],
                'convertedLeads' => ['count' => (int) $leadStats['converted_count']],
                'totalBlogs' => ['count' => (int) $blogStats['total']],
                'publishedBlogs' => ['count' => (int) $blogStats['published_count']],
                'errorMessage' => null,
            ];
        } catch (RuntimeException $exception) {
            error_log('Admin MySQL dashboard data could not be loaded: ' . $exception->getMessage());
            return [
                'totalLeads' => ['count' => 0],
                'newLeads' => ['count' => 0],
                'convertedLeads' => ['count' => 0],
                'totalBlogs' => ['count' => 0],
                'publishedBlogs' => ['count' => 0],
                'errorMessage' => 'Dashboard data could not be loaded. Check the MySQL configuration and tables.',
            ];
        }
    }
}
