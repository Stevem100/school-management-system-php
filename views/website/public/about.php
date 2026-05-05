<?php
// ─────────────────────────────────────────────────────────────────────────────
// About Page
//
// Expected variables:
//   $siteSettings — array of website_settings
//   $menuItems    — array of navigation menu items
//   $settings     — array of general school settings
// ─────────────────────────────────────────────────────────────────────────────

$siteSettings = $siteSettings ?? [];
$menuItems    = $menuItems ?? [];
$settings     = $settings ?? [];

$aboutUs    = $siteSettings['about_us'] ?? '';
$mission    = $siteSettings['mission'] ?? '';
$vision     = $siteSettings['vision'] ?? '';
$values     = $siteSettings['values'] ?? '';

ob_start();
?>

<!-- ─── PAGE HEADER ───────────────────────────────────────────────────────── -->
<section class="bg-emerald-900 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 relative z-10">
        <div class="max-w-3xl">
            <p class="text-emerald-300 text-sm font-semibold uppercase tracking-wider mb-3">About Us</p>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-4">Our Story, Our Mission</h1>
            <p class="text-emerald-200 text-lg max-w-xl">
                Discover the rich heritage and forward-thinking vision that drives our commitment to educational excellence.
            </p>
        </div>
    </div>
    <div class="absolute -bottom-8 -right-8 w-64 h-64 rounded-full bg-emerald-800/30"></div>
</section>

<!-- ─── BREADCRUMB ────────────────────────────────────────────────────────── -->
<nav class="bg-white border-b border-gray-100" aria-label="Breadcrumb">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <ol class="flex items-center gap-2 text-sm text-gray-500">
            <li><a href="/" class="hover:text-emerald-600 transition-colors">Home</a></li>
            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></li>
            <li class="text-emerald-600 font-medium">About Us</li>
        </ol>
    </div>
</nav>

<!-- ─── SCHOOL HISTORY ────────────────────────────────────────────────────── -->
<section class="py-16 sm:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div>
                <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-3">Our History</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">Building Tomorrow's Leaders Since <?= date('Y') - 25 ?></h2>
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>
                        <?= !empty($aboutUs) ? $aboutUs : 'Founded with a vision to transform education, our school has been a beacon of learning and innovation for over two decades. What started as a small institution with just 50 students has grown into a thriving educational community.' ?>
                    </p>
                    <p>
                        Our founders believed that every child deserves access to world-class education. This belief continues to guide everything we do, from curriculum design to campus facilities. Over the years, we have consistently adapted to the evolving needs of education while maintaining our core values of integrity, excellence, and compassion.
                    </p>
                    <p>
                        Today, we serve over 2,500 students with a team of 150+ dedicated educators, preparing the next generation of thinkers, innovators, and leaders who will shape the future.
                    </p>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-emerald-100 shadow-xl">
                    <img src="/assets/images/hero-banner.jpg" alt="School campus" class="w-full h-full object-cover" onerror="this.style.display='none'">
                </div>
                <!-- Timeline badge -->
                <div class="absolute -bottom-4 left-6 bg-white rounded-xl shadow-lg border border-gray-100 px-5 py-4">
                    <div class="flex items-center gap-4">
                        <div class="text-center">
                            <p class="text-3xl font-extrabold text-emerald-600"><?= date('Y') - 25 ?></p>
                            <p class="text-xs text-gray-500">Founded</p>
                        </div>
                        <div class="w-px h-12 bg-gray-200"></div>
                        <div class="text-center">
                            <p class="text-3xl font-extrabold text-emerald-600">2500+</p>
                            <p class="text-xs text-gray-500">Students</p>
                        </div>
                        <div class="w-px h-12 bg-gray-200"></div>
                        <div class="text-center">
                            <p class="text-3xl font-extrabold text-emerald-600">150+</p>
                            <p class="text-xs text-gray-500">Faculty</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── MISSION & VISION ──────────────────────────────────────────────────── -->
<section class="py-16 sm:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-3">Our Purpose</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Mission & Vision</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Mission -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Our Mission</h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?= !empty($mission) ? $mission : 'To provide a nurturing and stimulating educational environment that empowers every student to achieve their full potential. We strive to develop critical thinkers, creative problem-solvers, and responsible citizens who contribute positively to society.' ?>
                    </p>
                </div>
            </div>

            <!-- Vision -->
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Our Vision</h3>
                    <p class="text-gray-600 leading-relaxed">
                        <?= !empty($vision) ? $vision : 'To be recognized as a center of academic excellence that produces globally competitive graduates equipped with knowledge, skills, and values necessary to thrive in an ever-changing world and make meaningful contributions to humanity.' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── CORE VALUES ───────────────────────────────────────────────────────── -->
<section class="py-16 sm:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-3">What We Stand For</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Our Core Values</h2>
            <?php if (empty($values)): ?>
            <p class="text-gray-600">These guiding principles shape every aspect of our educational approach and community.</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($values)): ?>
            <div class="max-w-3xl mx-auto">
                <div class="bg-emerald-50 rounded-2xl p-8 text-gray-700 leading-relaxed">
                    <?= $values ?>
                </div>
            </div>
        <?php else: ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $coreValues = [
                ['icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z', 'title' => 'Integrity', 'desc' => 'We uphold honesty, transparency, and ethical conduct in all our endeavors.'],
                ['icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z', 'title' => 'Excellence', 'desc' => 'We pursue the highest standards in teaching, learning, and personal development.'],
                ['icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z', 'title' => 'Inclusivity', 'desc' => 'We celebrate diversity and create a welcoming environment for all students.'],
                ['icon' => 'M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18', 'title' => 'Innovation', 'desc' => 'We embrace creativity and forward-thinking approaches to education.'],
                ['icon' => 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z', 'title' => 'Compassion', 'desc' => 'We foster empathy, kindness, and a sense of social responsibility.'],
                ['icon' => 'M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6', 'title' => 'Resilience', 'desc' => 'We build strength of character and the ability to overcome challenges.'],
            ];
            ?>
            <?php foreach ($coreValues as $idx => $v): ?>
            <div class="text-center p-6 rounded-xl border border-gray-100 hover:border-emerald-200 hover:shadow-sm transition-all">
                <div class="w-14 h-14 mx-auto rounded-xl bg-emerald-50 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $v['icon'] ?>"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2"><?= $v['title'] ?></h3>
                <p class="text-gray-600 text-sm leading-relaxed"><?= $v['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ─── LEADERSHIP TEAM ───────────────────────────────────────────────────── -->
<section class="py-16 sm:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-3">Our People</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Leadership Team</h2>
            <p class="text-gray-600">Meet the visionary leaders who guide our school toward excellence every day.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $team = [
                ['name' => 'Dr. Robert Williams', 'role' => 'Principal', 'desc' => 'Ph.D. in Education with 20+ years of experience in academic leadership.'],
                ['name' => 'Prof. Sarah Mitchell', 'role' => 'Vice Principal', 'desc' => 'M.Ed. in Curriculum Development, dedicated to innovative teaching methodologies.'],
                ['name' => 'Mr. James Anderson', 'role' => 'Academic Director', 'desc' => 'Expert in STEM education with a passion for nurturing scientific inquiry.'],
                ['name' => 'Ms. Linda Chen', 'role' => 'Student Affairs', 'desc' => 'M.A. in Psychology, committed to student wellbeing and holistic development.'],
            ];
            ?>
            <?php foreach ($team as $idx => $member): ?>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-md transition-shadow">
                <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                    <span class="text-2xl font-bold text-emerald-700"><?= strtoupper(substr($member['name'], 0, 1)) . strtoupper(substr($member['name'], strpos($member['name'], ' ') + 1, 1)) ?></span>
                </div>
                <h3 class="text-base font-semibold text-gray-900"><?= htmlspecialchars($member['name']) ?></h3>
                <p class="text-emerald-600 text-sm font-medium mb-3"><?= htmlspecialchars($member['role']) ?></p>
                <p class="text-gray-500 text-xs leading-relaxed"><?= htmlspecialchars($member['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ─── ACHIEVEMENTS ──────────────────────────────────────────────────────── -->
<section class="py-14 bg-emerald-900 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-white">Our Achievements</h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <p class="text-4xl sm:text-5xl font-extrabold text-white" data-counter="98" data-suffix="%">0</p>
                <p class="mt-2 text-emerald-300 text-sm">Board Exam Pass Rate</p>
            </div>
            <div class="text-center">
                <p class="text-4xl sm:text-5xl font-extrabold text-white" data-counter="50" data-suffix="+">0</p>
                <p class="mt-2 text-emerald-300 text-sm">National Awards</p>
            </div>
            <div class="text-center">
                <p class="text-4xl sm:text-5xl font-extrabold text-white" data-counter="500" data-suffix="+">0</p>
                <p class="mt-2 text-emerald-300 text-sm">College Placements</p>
            </div>
            <div class="text-center">
                <p class="text-4xl sm:text-5xl font-extrabold text-white" data-counter="15" data-suffix="">0</p>
                <p class="mt-2 text-emerald-300 text-sm">Sports Championships</p>
            </div>
        </div>
    </div>
</section>

<!-- ─── CTA ────────────────────────────────────────────────────────────────── -->
<section class="py-16 sm:py-20 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Join Our School Family</h2>
        <p class="text-gray-600 text-lg mb-8">We welcome you to be part of our vibrant learning community. Apply today and give your child the gift of quality education.</p>
        <a href="/admission/form" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-8 py-3.5 rounded-xl transition-colors shadow-sm text-base">
            Apply for Admission
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </a>
    </div>
</section>

<?php $content = ob_get_clean(); ?>

<?php
$_layoutData = [
    'siteSettings' => $siteSettings,
    'menuItems'    => $menuItems,
    'content'      => $content,
    'showHero'     => false,
    'pageTitle'    => 'About Us',
];
extract($_layoutData, EXTR_SKIP);
require dirname(__DIR__) . '/../layouts/website.php';
?>
