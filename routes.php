<?php
session_start();

// Get the current URI
$uri = $_SERVER['REQUEST_URI'];

// Extract the path before the query string
$path = strstr($uri, '?', true) ?: $uri;

// Define the base directory
$baseDir = '/mudegu.online';

// Map URIs to corresponding PHP files
$routes = [
    $baseDir . '/' => 'app/views/Home.php',

    $baseDir . '/Home' => 'app/views/Home.php',



    
    // Add more routes as needed
];

// Check if the current URI is in the routes array
if (array_key_exists($path, $routes)) {
    require_once $routes[$path];
} elseif ($path == $baseDir . 'Home' || $path == $baseDir . '/') {
    require_once 'app/views/Home.php';
} else {
    // If no route matches, include the 404 page
    require_once 'app/views/partials/404.php';
}




