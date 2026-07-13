<?php
require_once 'config.php';

// Destroy session
session_destroy();
session_unset();

// Redirect to login
header('Location: login.php');
exit;