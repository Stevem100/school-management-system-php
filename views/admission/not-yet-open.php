<?php
// ─────────────────────────────────────────────────────────────────────────────
// Admissions Not Yet Open Page (Standalone — rendered without admin layout)
//
// Expected variables:
//   $settings   — array from admission_settings
//   $start_date — the date admissions will open (Y-m-d format)
//   $pageTitle  — page title (optional)
// ─────────────────────────────────────────────────────────────────────────────

$settings   = $settings ?? [];
$start_date = $start_date ?? '';
$pageTitle  = $pageTitle ?? 'Admissions Not Yet Open';
$schoolName = htmlspecialchars($settings['school_name'] ?? config('app_name', 'School Management System'));
$academicYear = htmlspecialchars($settings['academic_year'] ?? '');

// Calculate days until opening
$daysUntil = '';
if (!empty($start_date)) {
    $now = new DateTime('today');
    $open = new DateTime($start_date);
    $diff = $now->diff($open);
    $daysUntil = $diff->days;
}
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
        @keyframes pulse-soft {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        .pulse-soft { animation: pulse-soft 3s ease-in-out infinite; }
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
            <div class="w-24 h-24 mx-auto mb-8">
                <div class="w-24 h-24 rounded-full bg-amber-100 flex items-center justify-center pulse-soft">
                    <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/>
                    </svg>
                </div>
            </div>

            <!-- Message -->
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Admissions Open Soon!</h1>
            <p class="text-gray-600 text-lg mb-8">
                Our admission portal is not yet open for applications. Please check back soon!
            </p>

            <!-- Date Card -->
            <?php if (!empty($start_date)): ?>
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-8 mb-8 inline-block">
                <p class="text-sm text-emerald-600 font-medium mb-2">Admissions Open On</p>
                <p class="text-4xl font-extrabold text-emerald-800 tracking-tight">
                    <?= date('F j, Y', strtotime($start_date)) ?>
                </p>
                <?php if ($daysUntil !== '' && $daysUntil > 0): ?>
                <p class="text-sm text-emerald-600 mt-3 flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span><?= $daysUntil ?> day<?= $daysUntil !== 1 ? 's' : '' ?> to go</span>
                </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($academicYear)): ?>
            <p class="text-gray-500 text-sm mb-6">
                Academic Year: <strong><?= $academicYear ?></strong>
            </p>
            <?php endif; ?>

            <!-- Info Box -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left border border-gray-100">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Get Notified</h3>
                        <p class="text-gray-600 text-sm">
                            Want to be notified when admissions open? <a href="/contact" class="text-emerald-600 hover:text-emerald-700 font-medium underline">Contact us</a> to join our mailing list and receive updates about the admission process.
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
