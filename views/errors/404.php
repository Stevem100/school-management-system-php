<?php $pageTitle = $pageTitle ?? 'Page Not Found'; ?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 - Page Not Found | School Management ERP</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    }
  </script>
  <style>
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    .float-animation {
      animation: float 3s ease-in-out infinite;
    }
  </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center px-4">
  <div class="text-center max-w-md mx-auto">
    <!-- 404 Illustration -->
    <div class="float-animation mb-8">
      <div class="inline-flex items-center justify-center w-28 h-28 rounded-full bg-emerald-100 dark:bg-emerald-900/30">
        <svg class="w-14 h-14 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
    </div>

    <!-- Error Code -->
    <div class="mb-4">
      <span class="text-7xl font-extrabold bg-gradient-to-r from-emerald-600 to-emerald-400 bg-clip-text text-transparent">404</span>
    </div>

    <!-- Message -->
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Page Not Found</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
      Sorry, the page you are looking for doesn't exist or has been moved. Please check the URL or navigate back to the dashboard.
    </p>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
      <a href="<?= url('/') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Go to Dashboard
      </a>
      <button onclick="history.back()" class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Go Back
      </button>
    </div>

    <!-- Footer -->
    <p class="text-xs text-gray-400 dark:text-gray-600 mt-10">School Management ERP &copy; <?= date('Y') ?></p>
  </div>
</body>
</html>
