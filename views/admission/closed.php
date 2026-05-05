<?php
// ─────────────────────────────────────────────────────────────────────────────
// Admissions Closed Page (Standalone — rendered without admin layout)
//
// Expected variables:
//   $settings  — array from admission_settings
//   $pageTitle — page title (optional)
// ─────────────────────────────────────────────────────────────────────────────

$settings   = $settings ?? [];
$pageTitle  = $pageTitle ?? 'Admissions Closed';
$schoolName = htmlspecialchars($settings['school_name'] ?? config('app_name', 'School Management System'));
$endDate    = $settings['end_date'] ?? '';
$startDate  = $settings['start_date'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> — <?= $schoolName ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out both; }
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0; }
            100% { transform: scale(0.8); opacity: 0; }
        }
        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 3px solid #ef4444;
            animation: pulse-ring 2s ease-out infinite;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- ─── HEADER ───────────────────────────────────────────────────────────── -->
    <header class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center shadow-sm">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0119.5 12c0 .818-.082 1.616-.244 2.386L12 14zm-6.16 3.422A12.083 12.083 0 004.5 12c0-.818.082-1.616.244-2.386L6 14l6 6z"/>
                        </svg>
                    </div>
                    <span class="text-base font-bold text-gray-900"><?= $schoolName ?></span>
                </a>
                <a href="/" class="text-sm text-gray-500 hover:text-emerald-600 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </header>

    <!-- ─── MAIN CONTENT ─────────────────────────────────────────────────────── -->
    <main class="flex-1 flex items-center justify-center py-16 sm:py-24 px-4">
        <div class="max-w-xl mx-auto text-center animate-fade-in-up">

            <!-- Icon -->
            <div class="relative w-24 h-24 mx-auto mb-8 pulse-ring">
                <div class="w-24 h-24 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
            </div>

            <!-- Message -->
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Admissions Are Currently Closed</h1>
            <p class="text-gray-600 text-lg mb-2">
                We are not accepting applications at this time.
            </p>

            <?php if (!empty($startDate)): ?>
                <p class="text-gray-600 mb-6">
                    Admissions are expected to open on <strong class="text-emerald-700"><?= date('F j, Y', strtotime($startDate)) ?></strong>.
                </p>
            <?php endif; ?>

            <?php if (!empty($endDate)): ?>
                <p class="text-gray-500 text-sm mb-6">
                    The last admission window closed on <?= date('F j, Y', strtotime($endDate)) ?>.
                </p>
            <?php endif; ?>

            <!-- Contact CTA -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8 border border-gray-100 text-left">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Need More Information?</h3>
                        <p class="text-gray-600 text-sm">
                            Feel free to <a href="/contact" class="text-emerald-600 hover:text-emerald-700 font-medium underline">contact our admissions team</a> for information about upcoming admission cycles or to be added to our notification list.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <a href="/" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-8 py-3 rounded-xl transition-colors shadow-sm text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                </svg>
                Return to Homepage
            </a>
        </div>
    </main>

    <!-- ─── FOOTER ───────────────────────────────────────────────────────────── -->
    <footer class="bg-emerald-950 text-emerald-100 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-emerald-400">
                <p>&copy; <?= date('Y') ?> <?= $schoolName ?>. All rights reserved.</p>
                <a href="/" class="hover:text-white transition-colors">Back to Homepage</a>
            </div>
        </div>
    </footer>
</body>
</html>
