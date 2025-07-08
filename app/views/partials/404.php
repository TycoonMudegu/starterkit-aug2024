<?php require_once 'app/views/head.php';

// build the full URL (scheme + host + URI)
$scheme     = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host       = $_SERVER['HTTP_HOST'];
$requestUri = $_SERVER['REQUEST_URI'];
$currentUrl = sprintf('%s://%s%s', $scheme, $host, $requestUri);

?>

<!-- app/pages/404.php -->

<style>
    /* public/assets/style.css */
.glitch {
    position: relative;
    color: #fff;
    font-family: 'Press Start 2P', cursive;
    animation: glitch 1s infinite;
}

.glitch::before, .glitch::after {
    content: attr(data-text);
    position: absolute;
    left: 0;
    color: #ff007f;
    clip: rect(0, 100%, 0, 0);
}

.glitch::before {
    animation: glitch 1s infinite linear;
    animation-delay: .2s;
}

.glitch::after {
    animation: glitch 1.5s infinite linear;
    animation-delay: .4s;
}

</style>

<div class="flex flex-col items-center justify-center min-h-screen text-center text-white bg-gray-900">
    <h1 class="font-extrabold text-7xl text-neon-pink glitch" data-text="404">404</h1>
    <h2 class="mt-4 text-2xl glitch" data-text="Page Not Found">Page Not Found</h2>
    <p class="max-w-md mt-6">
        Oops! Looks like something went wrong with routing.  
        <br>
        <strong>Requested URL:</strong><br>
        <code><?php echo htmlspecialchars($currentUrl, ENT_QUOTES, 'UTF-8'); ?></code>
        <br><br>
        Hint: check your routes configuration, then come back and update this page!
    </p>
    <a href="/" class="inline-block mt-4 text-neon-blue hover:underline">
        Return to safety
    </a>
</div>
