<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/src/controller/LogoutController.php';

(new LogoutController())->handle();