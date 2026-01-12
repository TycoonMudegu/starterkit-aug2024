<?php
session_start();

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Define ENV() helper if not already defined
function env_var($key, $default = null) {
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    $value = getenv($key);
    return $value !== false ? $value : $default;
}


// Determine application environment
$appEnv = getenv('APP_ENV');

// Load .env in local/development environments
if (!in_array($appEnv, ['production', 'staging'])) {
    require_once 'vendor/autoload.php';

    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        
    } catch (Dotenv\Exception\InvalidPathException $e) {
        error_log("Warning: .env file not found or unreadable: " . $e->getMessage());
        // Optionally: die("Development environment requires a .env file.");
    }
} else {
    require_once  'vendor/autoload.php';
}

// Include routes
include 'routes.php';
