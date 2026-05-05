<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : '' ?>School Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                    },
                },
            },
        }
    </script>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        /* Sidebar transition */
        .sidebar-transition {
            transition: width 0.3s ease, transform 0.3s ease;
        }
        /* Fade in animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 antialiased">
    <div class="flex min-h-screen flex-col">

        <!-- Header -->
        <?php include __DIR__ . '/header.php'; ?>

        <div class="flex flex-1">

            <!-- Sidebar -->
            <?php include __DIR__ . '/sidebar.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-4 lg:p-6 transition-all duration-300 animate-fade-in">
                <?php if (isset($flashSuccess) && $flashSuccess): ?>
                    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-200" role="alert">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span><?= htmlspecialchars($flashSuccess) ?></span>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto rounded p-1 hover:bg-emerald-100 dark:hover:bg-emerald-900">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($flashError) && $flashError): ?>
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200" role="alert">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span><?= htmlspecialchars($flashError) ?></span>
                            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto rounded p-1 hover:bg-red-100 dark:hover:bg-red-900">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <?= isset($content) ? $content : '' ?>
            </main>
        </div>

        <!-- Footer -->
        <footer class="border-t border-gray-200 bg-white px-4 py-4 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col items-center justify-between gap-2 text-center text-sm text-gray-500 dark:text-gray-400 sm:flex-row sm:text-left">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0119.5 12c0 .818-.082 1.616-.244 2.386L12 14zm-6.16 3.422A12.083 12.083 0 004.5 12c0-.818.082-1.616.244-2.386L6 14l6 6z"/>
                    </svg>
                    <span>&copy; <?= date('Y') ?> School Management System ERP</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-emerald-600 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-emerald-600 transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-emerald-600 transition-colors">Support</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 z-40 bg-black/50 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        // Toggle sidebar collapse on desktop
        function toggleSidebarCollapse() {
            const sidebar = document.getElementById('sidebar');
            const main = document.querySelector('main');
            sidebar.classList.toggle('sidebar-collapsed');
        }

        // Dark mode toggle
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }

        // Init theme from localStorage
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            }
        })();

        // Toggle submenu
        function toggleSubmenu(id) {
            const submenu = document.getElementById(id);
            const arrow = document.getElementById(id + '-arrow');
            if (submenu) {
                submenu.classList.toggle('hidden');
            }
            if (arrow) {
                arrow.classList.toggle('rotate-180');
            }
        }
    </script>
</body>
</html>
