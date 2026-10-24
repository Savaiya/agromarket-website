<?php

require_once __DIR__ . '/../../../config/config.php';

class LogoutController
{
    public function handle(): void
    {
        session_unset();
        session_destroy();

        header('Location: ' . adminUrl('login.php'));
        exit;
    }
}
