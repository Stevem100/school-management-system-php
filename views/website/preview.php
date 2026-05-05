<?php
// ─────────────────────────────────────────────────────────────────────────────
// Website Page Preview (Standalone — no admin layout, basic HTML wrapper)
//
// Expected variables:
//   $page     — array, the website page (id, title, slug, content, status, etc.)
//   $settings — array, website settings
// ─────────────────────────────────────────────────────────────────────────────

$page     = $page ?? [];
$settings = $settings ?? [];

$pageTitle    = htmlspecialchars($page['title'] ?? 'Page Preview');
$pageContent  = $page['content'] ?? '';
$pageStatus   = $page['status'] ?? 'draft';
$pageSlug     = $page['slug'] ?? '';
$pageId       = $page['id'] ?? '';
$updatedAt    = $page['updated_at'] ?? $page['created_at'] ?? '';
$siteName     = htmlspecialchars($settings['site_name'] ?? config('app_name', 'School Management System'));
$siteLogo     = $settings['site_logo'] ?? '/assets/images/logo.svg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: <?= $pageTitle ?> — <?= $siteName ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7',
                            400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857',
                            800: '#065f46', 900: '#064e3b', 950: '#022c22',
                        },
                    },
                },
            },
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }

        /* Preview frame styles */
        .preview-frame {
            background: white;
            border-radius: 0 0 12px 12px;
            overflow: auto;
        }
        .preview-frame img {
            max-width: 100%;
            height: auto;
        }
        .preview-frame table {
            width: 100%;
            border-collapse: collapse;
        }
        .preview-frame table th,
        .preview-frame table td {
            border: 1px solid #e5e7eb;
            padding: 8px 12px;
            text-align: left;
        }
        .preview-frame table th {
            background: #f9fafb;
            font-weight: 600;
        }

        /* Browser chrome */
        .browser-bar {
            background: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
        }
        .browser-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        /* Responsive toggles */
        .device-btn.active {
            background-color: #10b981;
            color: white;
        }

        /* Dark mode preview */
        .preview-dark .preview-frame {
            background: #111827;
            color: #f9fafb;
        }
        .preview-dark .preview-frame h1,
        .preview-dark .preview-frame h2,
        .preview-dark .preview-frame h3,
        .preview-dark .preview-frame h4,
        .preview-dark .preview-frame h5,
        .preview-dark .preview-frame h6 {
            color: #ffffff;
        }
        .preview-dark .preview-frame p,
        .preview-dark .preview-frame span,
        .preview-dark .preview-frame div {
            color: #e5e7eb;
        }
        .preview-dark .preview-frame table th {
            background: #1f2937;
            color: #f9fafb;
        }
        .preview-dark .preview-frame table td {
            border-color: #374151;
            color: #d1d5db;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 antialiased min-h-screen flex flex-col">

    <!-- ─── TOOLBAR ─────────────────────────────────────────────────────────── -->
    <div class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-screen-2xl mx-auto px-4 py-3">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">

                <!-- Left: Title & Info -->
                <div class="flex items-center gap-3 min-w-0">
                    <a href="/website/pages" class="shrink-0 inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-emerald-600 transition-colors bg-gray-100 hover:bg-emerald-50 rounded-lg px-3 py-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                        </svg>
                        <span class="hidden sm:inline">Back to Editor</span>
                    </a>
                    <div class="min-w-0">
                        <h1 class="text-sm font-semibold text-gray-900 truncate">Preview: <?= $pageTitle ?></h1>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                                <?= $pageStatus === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>">
                                <span class="w-1.5 h-1.5 rounded-full <?= $pageStatus === 'published' ? 'bg-emerald-500' : 'bg-amber-500' ?>"></span>
                                <?= ucfirst($pageStatus) ?>
                            </span>
                            <?php if (!empty($pageSlug)): ?>
                                <span>/p/page/<?= htmlspecialchars($pageSlug) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($updatedAt)): ?>
                                <span>Updated <?= date('M j, Y g:i A', strtotime($updatedAt)) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Right: Controls -->
                <div class="flex items-center gap-2 shrink-0">
                    <!-- Device Toggle -->
                    <div class="flex items-center bg-gray-100 rounded-lg p-0.5">
                        <button onclick="setDevice('desktop')" class="device-btn active p-1.5 rounded-md transition-colors" data-device="desktop" title="Desktop">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/>
                            </svg>
                        </button>
                        <button onclick="setDevice('tablet')" class="device-btn p-1.5 rounded-md text-gray-500 hover:text-gray-700 transition-colors" data-device="tablet" title="Tablet">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5h3m-6.75 2.25h10.5a2.25 2.25 0 002.25-2.25v-15a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 4.5v15a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </button>
                        <button onclick="setDevice('mobile')" class="device-btn p-1.5 rounded-md text-gray-500 hover:text-gray-700 transition-colors" data-device="mobile" title="Mobile">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" id="darkModeBtn" class="p-1.5 rounded-lg bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors" title="Toggle dark mode preview">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>
                        </svg>
                    </button>

                    <!-- Edit Link -->
                    <?php if (!empty($pageId)): ?>
                    <a href="/website/pages/<?= $pageId ?>/edit" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                        </svg>
                        Edit Page
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ─── PREVIEW AREA ─────────────────────────────────────────────────────── -->
    <main class="flex-1 flex items-start justify-center py-6 px-4 sm:px-6 lg:px-8">
        <div id="previewWrapper" class="w-full transition-all duration-300 max-w-5xl">

            <!-- Browser Chrome -->
            <div class="bg-white rounded-t-xl shadow-sm border border-gray-200 border-b-0 overflow-hidden">
                <div class="browser-bar px-4 py-3">
                    <div class="flex items-center gap-3">
                        <!-- Traffic lights -->
                        <div class="flex items-center gap-1.5">
                            <div class="browser-dot bg-red-400"></div>
                            <div class="browser-dot bg-amber-400"></div>
                            <div class="browser-dot bg-emerald-400"></div>
                        </div>
                        <!-- URL bar -->
                        <div class="flex-1 bg-white rounded-md px-3 py-1.5 text-sm text-gray-500 border border-gray-200 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                            <span class="truncate"><?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'localhost') ?>/p/page/<?= htmlspecialchars($pageSlug) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content Frame -->
            <div id="previewContainer" class="preview-frame shadow-sm border border-gray-200 border-t-0 rounded-b-xl" style="min-height: 600px;">
                <div class="p-6 sm:p-8 lg:p-12">
                    <!-- Page Title -->
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6"><?= $pageTitle ?></h1>
                    <!-- Page Content -->
                    <div class="prose prose-sm sm:prose-base max-w-none">
                        <?= $pageContent ?>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        // Device switching
        function setDevice(device) {
            const wrapper = document.getElementById('previewWrapper');
            const btns = document.querySelectorAll('.device-btn');

            btns.forEach(btn => {
                btn.classList.remove('active');
                if (!btn.classList.contains('active')) {
                    btn.classList.add('text-gray-500');
                }
            });

            const activeBtn = document.querySelector(`.device-btn[data-device="${device}"]`);
            if (activeBtn) {
                activeBtn.classList.add('active');
                activeBtn.classList.remove('text-gray-500');
            }

            switch (device) {
                case 'mobile':
                    wrapper.classList.remove('max-w-3xl', 'max-w-5xl');
                    wrapper.classList.add('max-w-sm');
                    break;
                case 'tablet':
                    wrapper.classList.remove('max-w-sm', 'max-w-5xl');
                    wrapper.classList.add('max-w-3xl');
                    break;
                default:
                    wrapper.classList.remove('max-w-sm', 'max-w-3xl');
                    wrapper.classList.add('max-w-5xl');
            }
        }

        // Dark mode toggle
        let isDark = false;
        function toggleDarkMode() {
            isDark = !isDark;
            const container = document.getElementById('previewContainer');
            const btn = document.getElementById('darkModeBtn');
            if (isDark) {
                container.classList.add('preview-dark');
                btn.classList.add('bg-emerald-600', 'text-white');
                btn.classList.remove('bg-gray-100', 'text-gray-500');
            } else {
                container.classList.remove('preview-dark');
                btn.classList.remove('bg-emerald-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-500');
            }
        }
    </script>
</body>
</html>
