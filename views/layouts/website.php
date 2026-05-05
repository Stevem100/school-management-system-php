<?php
// ─────────────────────────────────────────────────────────────────────────────
// Frontend Website Layout
//
// Clean, modern school website layout — light theme with emerald accents.
// Completely separate from the admin dark theme layout.
//
// Expected variables:
//   $siteSettings  — array from website_settings table
//   $menuItems     — array of navigation menu items (from website_menu_items)
//   $content       — injected view HTML string
//   $showHero      — (bool) whether to render the hero banner (default: false)
//   $pageTitle     — page title string (optional)
// ─────────────────────────────────────────────────────────────────────────────

$siteSettings  = $siteSettings ?? [];
$menuItems     = $menuItems ?? [];
$content       = $content ?? '';
$showHero      = $showHero ?? false;
$pageTitle     = $pageTitle ?? '';

$siteName     = htmlspecialchars($siteSettings['site_name'] ?? 'Greenfield Academy');
$siteDesc     = htmlspecialchars($siteSettings['site_description'] ?? 'Nurturing Excellence, Building Futures');
$siteLogo     = $siteSettings['site_logo'] ?? '/assets/images/logo.svg';
$favicon      = $siteSettings['favicon'] ?? '';
$metaKeywords = htmlspecialchars($siteSettings['meta_keywords'] ?? 'school, education, admission, academy');
$metaDesc     = htmlspecialchars($siteSettings['meta_description'] ?? $siteDesc);
$contactPhone = htmlspecialchars($siteSettings['contact_phone'] ?? '+1 (555) 123-4567');
$contactEmail = htmlspecialchars($siteSettings['contact_email'] ?? 'info@greenfieldacademy.edu');
$contactAddr  = htmlspecialchars($siteSettings['contact_address'] ?? '123 Education Lane, Academic City, AC 12345');
$footerText   = htmlspecialchars($siteSettings['footer_text'] ?? '');
$socialFB     = $siteSettings['social_facebook'] ?? '';
$socialTW     = $siteSettings['social_twitter'] ?? '';
$socialIG     = $siteSettings['social_instagram'] ?? '';
$socialLI     = $siteSettings['social_linkedin'] ?? '';
$socialYT     = $siteSettings['social_youtube'] ?? '';

$heroTitle    = $heroTitle ?? $siteName;
$heroTagline  = $heroTagline ?? $siteDesc;

$fullTitle = (!empty($pageTitle) ? htmlspecialchars($pageTitle) . ' — ' : '') . $siteName;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $fullTitle ?></title>
    <meta name="description" content="<?= $metaDesc ?>">
    <meta name="keywords" content="<?= $metaKeywords ?>">
    <?php if (!empty($favicon)): ?>
        <link rel="icon" href="<?= htmlspecialchars($favicon) ?>">
    <?php endif; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50:  '#ecfdf5',
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes countUp {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out both; }
        .animate-fade-in { animation: fadeIn 0.5s ease-out both; }
        .animate-slide-down { animation: slideDown 0.3s ease-out both; }
        .animate-count-up { animation: countUp 0.5s ease-out both; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }

        .hero-gradient {
            background: linear-gradient(135deg, #064e3b 0%, #059669 50%, #34d399 100%);
        }
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

    <!-- ═══════════════════════════════════════════════════════════════════════
         TOP BAR
    ═══════════════════════════════════════════════════════════════════════ -->
    <div class="bg-emerald-900 text-white text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between py-2 gap-2">
                <!-- Contact Info -->
                <div class="flex items-center gap-4 sm:gap-6 flex-wrap justify-center">
                    <a href="tel:<?= $contactPhone ?>" class="flex items-center gap-1.5 hover:text-emerald-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                        <span><?= $contactPhone ?></span>
                    </a>
                    <a href="mailto:<?= $contactEmail ?>" class="flex items-center gap-1.5 hover:text-emerald-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                        <span><?= $contactEmail ?></span>
                    </a>
                </div>

                <!-- Social Links -->
                <div class="flex items-center gap-3">
                    <?php if (!empty($socialFB)): ?>
                        <a href="<?= htmlspecialchars($socialFB) ?>" target="_blank" rel="noopener" class="hover:text-emerald-300 transition-colors" aria-label="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($socialTW)): ?>
                        <a href="<?= htmlspecialchars($socialTW) ?>" target="_blank" rel="noopener" class="hover:text-emerald-300 transition-colors" aria-label="Twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($socialIG)): ?>
                        <a href="<?= htmlspecialchars($socialIG) ?>" target="_blank" rel="noopener" class="hover:text-emerald-300 transition-colors" aria-label="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($socialLI)): ?>
                        <a href="<?= htmlspecialchars($socialLI) ?>" target="_blank" rel="noopener" class="hover:text-emerald-300 transition-colors" aria-label="LinkedIn">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($socialYT)): ?>
                        <a href="<?= htmlspecialchars($socialYT) ?>" target="_blank" rel="noopener" class="hover:text-emerald-300 transition-colors" aria-label="YouTube">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════════════
         HEADER / NAVBAR
    ═══════════════════════════════════════════════════════════════════════ -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 shrink-0">
                    <img src="<?= htmlspecialchars($siteLogo) ?>" alt="<?= $siteName ?>" class="h-10 lg:h-12 w-auto object-contain">
                    <div class="hidden sm:block">
                        <p class="text-lg lg:text-xl font-bold text-emerald-900 leading-tight"><?= $siteName ?></p>
                        <p class="text-xs text-gray-500 leading-tight"><?= $siteDesc ?></p>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-1" aria-label="Main navigation">
                    <?php foreach ($menuItems as $item):
                        $label = htmlspecialchars($item['label'] ?? '');
                        $url   = htmlspecialchars($item['url'] ?? '#');
                        $children = $item['children'] ?? [];
                        $target = $item['target'] ?? '_self';
                        $hasChildren = !empty($children);
                    ?>
                        <?php if ($hasChildren): ?>
                            <div class="relative group">
                                <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:text-emerald-700 hover:bg-emerald-50 transition-colors">
                                    <?= $label ?>
                                    <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="invisible group-hover:visible opacity-0 group-hover:opacity-100 absolute top-full left-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-2 animate-slide-down z-50">
                                    <?php foreach ($children as $child):
                                        $childLabel = htmlspecialchars($child['label'] ?? '');
                                        $childUrl   = htmlspecialchars($child['url'] ?? '#');
                                        $childTarget = $child['target'] ?? '_self';
                                    ?>
                                        <a href="<?= $childUrl ?>" target="<?= $childTarget ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                            <?= $childLabel ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="<?= $url ?>" target="<?= $target ?>" class="px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:text-emerald-700 hover:bg-emerald-50 transition-colors">
                                <?= $label ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </nav>

                <!-- Right: Admission CTA + Mobile Toggle -->
                <div class="flex items-center gap-3">
                    <a href="/admission/form" class="hidden sm:inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L6.75 2.906m9.944 18.3l.26-1.477m-2.605-14.772l-.26-1.477"/>
                        </svg>
                        Apply Now
                    </a>

                    <!-- Mobile Hamburger -->
                    <button id="mobileMenuToggle" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors" aria-label="Toggle mobile menu">
                        <svg id="menuIconOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                        <svg id="menuIconClose" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden border-t border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
                <?php foreach ($menuItems as $item):
                    $label = htmlspecialchars($item['label'] ?? '');
                    $url   = htmlspecialchars($item['url'] ?? '#');
                    $children = $item['children'] ?? [];
                    $hasChildren = !empty($children);
                ?>
                    <?php if ($hasChildren): ?>
                        <div>
                            <button onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                <?= $label ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="hidden pl-4 space-y-1 mt-1">
                                <?php foreach ($children as $child):
                                    $childLabel = htmlspecialchars($child['label'] ?? '');
                                    $childUrl   = htmlspecialchars($child['url'] ?? '#');
                                ?>
                                    <a href="<?= $childUrl ?>" class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                        <?= $childLabel ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= $url ?>" class="block px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                            <?= $label ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="pt-3 border-t border-gray-100 mt-3">
                    <a href="/admission/form" class="flex items-center justify-center gap-2 w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L6.75 2.906m9.944 18.3l.26-1.477m-2.605-14.772l-.26-1.477"/>
                        </svg>
                        Apply Now
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════════════════════════════════════════════
         HERO SECTION (only on homepage)
    ═══════════════════════════════════════════════════════════════════════ -->
    <?php if ($showHero): ?>
    <section class="hero-gradient hero-pattern relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 lg:py-36 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-extrabold text-white leading-tight animate-fade-in-up">
                    Welcome to<br class="sm:hidden"> <?= $heroTitle ?>
                </h1>
                <p class="mt-4 sm:mt-6 text-lg sm:text-xl text-emerald-100 max-w-2xl mx-auto animate-fade-in-up delay-100">
                    <?= $heroTagline ?>
                </p>
                <div class="mt-8 sm:mt-10 flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up delay-200">
                    <a href="/admission/form" class="inline-flex items-center gap-2 bg-white text-emerald-800 font-semibold px-8 py-3.5 rounded-xl hover:bg-emerald-50 transition-colors shadow-lg text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                        </svg>
                        Apply Now
                    </a>
                    <a href="#about" class="inline-flex items-center gap-2 border-2 border-white/50 text-white font-semibold px-8 py-3.5 rounded-xl hover:bg-white/10 hover:border-white transition-colors text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                        </svg>
                        Learn More
                    </a>
                </div>
            </div>
        </div>
        <!-- Decorative circles -->
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-32 -left-32 w-[500px] h-[500px] rounded-full bg-white/5"></div>
    </section>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════════════════════════════════════
         FLASH MESSAGES
    ═══════════════════════════════════════════════════════════════════════ -->
    <?php if (isset($flashSuccess) && $flashSuccess): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 animate-slide-down" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span><?= htmlspecialchars($flashSuccess) ?></span>
                    <button onclick="this.closest('[role=alert]').remove()" class="ml-auto p-1 rounded hover:bg-emerald-100" aria-label="Dismiss">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (isset($flashError) && $flashError): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 animate-slide-down" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span><?= htmlspecialchars($flashError) ?></span>
                    <button onclick="this.closest('[role=alert]').remove()" class="ml-auto p-1 rounded hover:bg-red-100" aria-label="Dismiss">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════════════════════════ -->
    <main>
        <?= $content ?>
    </main>

    <!-- ═══════════════════════════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════════════════════════ -->
    <footer class="bg-emerald-950 text-emerald-100 mt-auto">
        <!-- Main Footer -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 lg:gap-12">

                <!-- Column 1: About -->
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <img src="<?= htmlspecialchars($siteLogo) ?>" alt="<?= $siteName ?>" class="h-10 w-auto object-contain brightness-0 invert">
                        <span class="text-lg font-bold text-white"><?= $siteName ?></span>
                    </div>
                    <p class="text-emerald-300 text-sm leading-relaxed">
                        <?= !empty($footerText) ? $footerText : 'We are committed to providing quality education that nurtures young minds and prepares them for a bright future. Join our community of learners and achievers.' ?>
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h3 class="text-white font-semibold text-base mb-5">Quick Links</h3>
                    <ul class="space-y-2.5">
                        <?php foreach (array_slice($menuItems, 0, 8) as $item):
                            $label = htmlspecialchars($item['label'] ?? '');
                            $url   = htmlspecialchars($item['url'] ?? '#');
                        ?>
                            <li>
                                <a href="<?= $url ?>" class="text-emerald-300 hover:text-white text-sm transition-colors inline-flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                                    </svg>
                                    <?= $label ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Column 3: Contact -->
                <div>
                    <h3 class="text-white font-semibold text-base mb-5">Contact Us</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 0115 0z"/>
                            </svg>
                            <span class="text-emerald-300 text-sm"><?= $contactAddr ?></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                            <a href="tel:<?= $contactPhone ?>" class="text-emerald-300 hover:text-white text-sm transition-colors"><?= $contactPhone ?></a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                            <a href="mailto:<?= $contactEmail ?>" class="text-emerald-300 hover:text-white text-sm transition-colors"><?= $contactEmail ?></a>
                        </li>
                    </ul>

                    <!-- Social Icons in Footer -->
                    <?php if (!empty($socialFB) || !empty($socialTW) || !empty($socialIG) || !empty($socialLI) || !empty($socialYT)): ?>
                        <div class="flex items-center gap-3 mt-5 pt-5 border-t border-emerald-800/50">
                            <?php if (!empty($socialFB)): ?>
                                <a href="<?= htmlspecialchars($socialFB) ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-lg bg-emerald-900 hover:bg-emerald-800 text-emerald-300 hover:text-white transition-colors" aria-label="Facebook">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($socialTW)): ?>
                                <a href="<?= htmlspecialchars($socialTW) ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-lg bg-emerald-900 hover:bg-emerald-800 text-emerald-300 hover:text-white transition-colors" aria-label="Twitter">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($socialIG)): ?>
                                <a href="<?= htmlspecialchars($socialIG) ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-lg bg-emerald-900 hover:bg-emerald-800 text-emerald-300 hover:text-white transition-colors" aria-label="Instagram">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($socialLI)): ?>
                                <a href="<?= htmlspecialchars($socialLI) ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-lg bg-emerald-900 hover:bg-emerald-800 text-emerald-300 hover:text-white transition-colors" aria-label="LinkedIn">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($socialYT)): ?>
                                <a href="<?= htmlspecialchars($socialYT) ?>" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-lg bg-emerald-900 hover:bg-emerald-800 text-emerald-300 hover:text-white transition-colors" aria-label="YouTube">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Copyright Bar -->
        <div class="border-t border-emerald-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-emerald-400">
                    <p>&copy; <?= date('Y') ?> <?= $siteName ?>. All rights reserved.</p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- ═══════════════════════════════════════════════════════════════════════
         JAVASCRIPT
    ═══════════════════════════════════════════════════════════════════════ -->
    <script>
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIconOpen = document.getElementById('menuIconOpen');
        const menuIconClose = document.getElementById('menuIconClose');

        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
                menuIconOpen.classList.toggle('hidden');
                menuIconClose.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            });
        }

        // Close mobile menu on resize
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024 && mobileMenu && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
                if (menuIconOpen) menuIconOpen.classList.remove('hidden');
                if (menuIconClose) menuIconClose.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Animated counter (for stats sections)
        function animateCounters() {
            const counters = document.querySelectorAll('[data-counter]');
            counters.forEach(function (counter) {
                if (counter.dataset.animated === 'true') return;

                const rect = counter.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    counter.dataset.animated = 'true';
                    const target = parseInt(counter.dataset.counter, 10);
                    const duration = 2000;
                    const start = 0;
                    const startTime = performance.now();

                    function update(currentTime) {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        const easeOut = 1 - Math.pow(1 - progress, 3);
                        const current = Math.round(start + (target - start) * easeOut);
                        counter.textContent = current.toLocaleString() + (counter.dataset.suffix || '');

                        if (progress < 1) {
                            requestAnimationFrame(update);
                        }
                    }

                    requestAnimationFrame(update);
                }
            });
        }

        window.addEventListener('scroll', animateCounters);
        window.addEventListener('load', animateCounters);

        // Scroll-to-top button
        const scrollBtn = document.createElement('button');
        scrollBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>';
        scrollBtn.className = 'fixed bottom-6 right-6 z-40 w-11 h-11 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 opacity-0 translate-y-4 pointer-events-none';
        scrollBtn.setAttribute('aria-label', 'Scroll to top');
        document.body.appendChild(scrollBtn);

        window.addEventListener('scroll', function () {
            if (window.scrollY > 400) {
                scrollBtn.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                scrollBtn.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
            } else {
                scrollBtn.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                scrollBtn.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            }
        });

        scrollBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>
