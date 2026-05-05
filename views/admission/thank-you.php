<?php
// ─────────────────────────────────────────────────────────────────────────────
// Admission Thank You Page (Standalone — rendered without admin layout)
//
// Expected variables:
//   $settings       — array from admission_settings (optional)
//   $applicationNo  — the generated application number (optional)
//   $applicantName  — applicant's full name (optional)
//   $applicantEmail — applicant's email (optional)
//   $pageTitle      — page title (optional)
// ─────────────────────────────────────────────────────────────────────────────

$settings       = $settings ?? [];
$applicationNo  = $applicationNo ?? '';
$applicantName  = htmlspecialchars($applicantName ?? 'Applicant');
$applicantEmail = htmlspecialchars($applicantEmail ?? '');
$pageTitle      = $pageTitle ?? 'Application Submitted';
$schoolName     = htmlspecialchars($settings['school_name'] ?? config('app_name', 'School Management System'));
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
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out both; }
        .animate-scale-in { animation: scaleIn 0.5s ease-out both; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                    Homepage
                </a>
            </div>
        </div>
    </header>

    <!-- ─── MAIN CONTENT ─────────────────────────────────────────────────────── -->
    <main class="flex-1 flex items-center py-12 sm:py-20 px-4">
        <div class="max-w-2xl mx-auto text-center w-full">

            <!-- Success Icon -->
            <div class="w-24 h-24 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-8 animate-scale-in">
                <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <!-- Heading -->
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4 animate-fade-in-up">
                Application Submitted Successfully!
            </h1>
            <p class="text-gray-600 text-lg mb-2 animate-fade-in-up delay-100">
                Thank you, <strong><?= $applicantName ?></strong>, for your interest in joining our school.
            </p>
            <p class="text-gray-500 mb-8 animate-fade-in-up delay-100">
                Your application has been received and is currently being reviewed by our admissions team.
            </p>

            <!-- Application Number Card -->
            <?php if (!empty($applicationNo)): ?>
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 mb-8 inline-block animate-fade-in-up delay-200">
                <p class="text-sm text-emerald-600 font-medium mb-1">Your Application Number</p>
                <p class="text-3xl font-extrabold text-emerald-800 tracking-wider"><?= htmlspecialchars($applicationNo) ?></p>
                <p class="text-xs text-emerald-500 mt-2">Please save this number for future reference</p>
            </div>
            <?php endif; ?>

            <!-- Confirmation Email -->
            <?php if (!empty($applicantEmail)): ?>
            <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left border border-gray-100 animate-fade-in-up delay-200">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Confirmation Email Sent</h3>
                        <p class="text-gray-600 text-sm">
                            A confirmation email has been sent to <strong><?= $applicantEmail ?></strong> with your application details and further instructions.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Next Steps -->
            <div class="text-left mb-10 animate-fade-in-up delay-300">
                <h2 class="text-xl font-bold text-gray-900 mb-5 text-center">What Happens Next?</h2>
                <div class="space-y-4">
                    <?php
                    $steps = [
                        ['title' => 'Application Review', 'desc' => 'Our admissions team will review your application and all submitted documents.'],
                        ['title' => 'Entrance Assessment', 'desc' => 'Eligible candidates will be invited for an entrance assessment or interview.'],
                        ['title' => 'Offer Letter', 'desc' => 'Successful applicants will receive an offer letter with enrollment instructions.'],
                        ['title' => 'Enrollment Complete', 'desc' => 'Complete the enrollment process by submitting required documents and fees.'],
                    ];
                    ?>
                    <?php foreach ($steps as $idx => $step): ?>
                    <div class="flex items-start gap-4">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                <span class="text-emerald-700 font-bold text-sm"><?= $idx + 1 ?></span>
                            </div>
                            <?php if ($idx < count($steps) - 1): ?>
                                <div class="absolute top-10 left-1/2 -translate-x-1/2 w-0.5 h-8 bg-emerald-200"></div>
                            <?php endif; ?>
                        </div>
                        <div class="pb-2">
                            <h3 class="text-sm font-semibold text-gray-900 mb-0.5"><?= $step['title'] ?></h3>
                            <p class="text-gray-600 text-sm"><?= $step['desc'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up delay-300">
                <a href="/" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-8 py-3 rounded-xl transition-colors shadow-sm text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                    Return to Homepage
                </a>
                <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border border-gray-300 text-gray-700 hover:border-emerald-300 hover:text-emerald-700 font-semibold px-8 py-3 rounded-xl transition-colors text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                    </svg>
                    Contact Admissions
                </a>
            </div>
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
