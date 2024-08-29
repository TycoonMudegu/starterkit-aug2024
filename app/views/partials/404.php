<?php require_once 'app/views/head.php'?>

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

<div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-center text-white">
    <h1 class="text-7xl font-extrabold text-neon-pink glitch">404</h1>
    <h2 class="text-2xl mt-4 glitch">Page Not Found</h2>
    <p class="mt-6 max-w-md">
        Oops! Seems you have an error. i think its probably something in your routes. change it and have fun... rember to edit this pager again
        <br>
        <a href="Home" class="mt-4 inline-block text-neon-blue hover:underline">
            Return to safety
        </a>
    </p>
</div>
