<?php
// ─────────────────────────────────────────────────────────────────────────────
// Admission Thank You Page
//
// Expected variables:
//   $siteSettings     — array of website_settings
//   $menuItems        — array of navigation menu items
//   $applicationNo    — the generated application number
//   $applicantName    — applicant's full name
//   $applicantEmail   — applicant's email
// ─────────────────────────────────────────────────────────────────────────────

$siteSettings   = $siteSettings ?? [];
$menuItems      = $menuItems ?? [];
$applicationNo  = $applicationNo ?? '';
$applicantName  = htmlspecialchars($applicantName ?? 'Applicant');
$applicantEmail = htmlspecialchars($applicantEmail ?? '');

ob_start();
?>

<!-- ─── SUCCESS SECTION ───────────────────────────────────────────────────── -->
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        <!-- Success Icon -->
        <div class="w-24 h-24 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-8 animate-count-up">
            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <!-- Heading -->
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">
            Application Submitted Successfully!
        </h1>
        <p class="text-gray-600 text-lg mb-2">
            Thank you, <strong><?= $applicantName ?></strong>, for your interest in joining our school.
        </p>
        <p class="text-gray-500 mb-8">
            Your application has been received and is currently being reviewed by our admissions team.
        </p>

        <!-- Application Number Card -->
        <?php if (!empty($applicationNo)): ?>
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 mb-8 inline-block">
            <p class="text-sm text-emerald-600 font-medium mb-1">Your Application Number</p>
            <p class="text-3xl font-extrabold text-emerald-800 tracking-wider"><?= htmlspecialchars($applicationNo) ?></p>
            <p class="text-xs text-emerald-500 mt-2">Please save this number for future reference</p>
        </div>
        <?php endif; ?>

        <!-- Confirmation Email -->
        <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left border border-gray-100">
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

        <!-- Next Steps -->
        <div class="text-left mb-10">
            <h2 class="text-xl font-bold text-gray-900 mb-5 text-center">What Happens Next?</h2>
            <div class="space-y-4">
                <?php
                $steps = [
                    ['icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Application Review', 'desc' => 'Our admissions team will review your application and all submitted documents.'],
                    ['icon' => 'M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5M4.5 15.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z', 'title' => 'Entrance Assessment', 'desc' => 'Eligible candidates will be invited for an entrance assessment or interview.'],
                    ['icon' => 'M4.5 12.75l6 6 9-13.5', 'title' => 'Offer Letter', 'desc' => 'Successful applicants will receive an offer letter with enrollment instructions.'],
                    ['icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z', 'title' => 'Enrollment Complete', 'desc' => 'Complete the enrollment process by submitting required documents and fees.'],
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
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
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
</section>

<!-- ─── QUICK LINKS ───────────────────────────────────────────────────────── -->
<section class="py-10 bg-gray-50 border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-3 gap-4 text-center">
            <a href="/classes" class="bg-white rounded-xl p-5 border border-gray-100 hover:border-emerald-200 hover:shadow-sm transition-all group">
                <div class="w-10 h-10 mx-auto rounded-lg bg-emerald-50 flex items-center justify-center mb-3 group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">Explore Programs</h3>
                <p class="text-xs text-gray-500">Browse our classes and programs</p>
            </a>
            <a href="/about" class="bg-white rounded-xl p-5 border border-gray-100 hover:border-emerald-200 hover:shadow-sm transition-all group">
                <div class="w-10 h-10 mx-auto rounded-lg bg-emerald-50 flex items-center justify-center mb-3 group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">About Our School</h3>
                <p class="text-xs text-gray-500">Learn about our mission and values</p>
            </a>
            <a href="/contact" class="bg-white rounded-xl p-5 border border-gray-100 hover:border-emerald-200 hover:shadow-sm transition-all group">
                <div class="w-10 h-10 mx-auto rounded-lg bg-emerald-50 flex items-center justify-center mb-3 group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">Get in Touch</h3>
                <p class="text-xs text-gray-500">Reach out to our admissions team</p>
            </a>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>

<?php
$_layoutData = [
    'siteSettings' => $siteSettings,
    'menuItems'    => $menuItems,
    'content'      => $content,
    'showHero'     => false,
    'pageTitle'    => 'Application Submitted',
];
extract($_layoutData, EXTR_SKIP);
require dirname(__DIR__) . '/../layouts/website.php';
?>
