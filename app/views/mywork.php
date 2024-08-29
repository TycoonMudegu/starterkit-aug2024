
<?php require_once 'app/views/partials/navbar.php'?>

<section class="font-sans overflow-hidden bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
  <div class="container mx-auto px-4 py-16">
    <!-- Header -->
    <header class="mb-16 relative">
      <h1 class="text-6xl font-bold text-gray-800 dark:text-gray-100 mb-6">My Projects</h1>
      <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl">
        A showcase of my frontend development work, featuring responsive designs and interactive user experiences.
      </p>
      <!-- Decorative element -->
      <div class="absolute top-0 right-0 w-20 h-20 border-4 border-gray-200 dark:border-gray-700 rounded-full opacity-50"></div>
    </header>

    <!-- Project Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Project Card 1 -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105">
        <div class="h-48 bg-gray-100 dark:bg-gray-700 relative">
          <!-- Project image or 3D element would go here -->
          <div class="absolute bottom-4 left-4 w-8 h-8 bg-amber-400 rounded-full animate-pulse"></div>
        </div>
        <div class="p-6">
          <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">Project One</h3>
          <p class="text-gray-600 dark:text-gray-300 mb-4">A responsive web application with modern UI/UX design.</p>
          <a href="#" class="text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300 font-semibold">View Project →</a>
        </div>
      </div>

      <!-- Project Card 2 -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105">
        <div class="h-48 bg-gray-100 dark:bg-gray-700 relative">
          <!-- Project image or 3D element would go here -->
          <div class="absolute top-4 right-4 w-12 h-12 border-4 border-gray-300 dark:border-gray-600 transform rotate-45"></div>
        </div>
        <div class="p-6">
          <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">Project Two</h3>
          <p class="text-gray-600 dark:text-gray-300 mb-4">An interactive dashboard with real-time data visualization.</p>
          <a href="#" class="text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300 font-semibold">View Project →</a>
        </div>
      </div>

      <!-- Project Card 3 -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105">
        <div class="h-48 bg-gray-100 dark:bg-gray-700 relative">
          <!-- Project image or 3D element would go here -->
          <div class="absolute bottom-4 right-4 w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-full opacity-30"></div>
        </div>
        <div class="p-6">
          <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">Project Three</h3>
          <p class="text-gray-600 dark:text-gray-300 mb-4">A mobile-first e-commerce platform with seamless checkout.</p>
          <a href="#" class="text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300 font-semibold">View Project →</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Floating navigation -->
  <nav class="fixed top-8 right-8 z-20">
    <ul class="flex space-x-6">
      <li><a href="#" class="text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 transition-colors duration-300">Home</a></li>
      <li><a href="#" class="text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 transition-colors duration-300">About</a></li>
      <li><a href="#" class="text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 transition-colors duration-300">Contact</a></li>
    </ul>
  </nav>

  <!-- Decorative background element -->
  <div class="fixed bottom-0 left-0 w-1/3 h-1/3 bg-gray-100 dark:bg-gray-800 opacity-20 rounded-tr-full -z-10"></div>
</section>

<button id="toggle-button" class="fade-in w-16 h-16 rounded-full bg-blue-500 dark:bg-blue-700 text-white dark:text-gray-200 flex items-center justify-center fixed bottom-4 left-4 transition-transform duration-300 ease-in-out transform hover:scale-105 active:scale-95">
        <i id="sun-icon" class="fas fa-sun text-xl"></i>
        <i id="moon-icon" class="fas fa-moon text-xl hidden"></i>
    </button>

    <div id="tooltip" class="tooltip">
        <p id="tooltip-text" class="text-gray-700 dark:text-gray-300"></p>
    </div>