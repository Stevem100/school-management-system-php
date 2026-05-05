<?php
// ─────────────────────────────────────────────────────────────────────────────
// Public Homepage
//
// Expected variables:
//   $classes   — array of class records
//   $settings  — array of school/website settings
//   $siteSettings — array of website_settings
//   $menuItems    — array of navigation menu items
// ─────────────────────────────────────────────────────────────────────────────

$classes      = $classes ?? [];
$settings     = $settings ?? [];
$siteSettings = $siteSettings ?? [];
$menuItems    = $menuItems ?? [];

$siteName  = htmlspecialchars($siteSettings['site_name'] ?? 'Greenfield Academy');
$siteDesc  = htmlspecialchars($siteSettings['site_description'] ?? 'Nurturing Excellence, Building Futures');

// Extract site settings for display
$aboutUs    = $siteSettings['about_us'] ?? '';
$visionText = $siteSettings['vision'] ?? '';

// Demo testimonials (in production these would come from DB)
$testimonials = $testimonials ?? [
    ['name' => 'Sarah Johnson', 'role' => 'Parent', 'quote' => 'The teachers at this school go above and beyond. My daughter has blossomed both academically and personally since joining.'],
    ['name' => 'Michael Chen', 'role' => 'Alumni, Class of 2022', 'quote' => 'The education I received here laid the foundation for my success in college. I will always be grateful for the opportunities.'],
    ['name' => 'Dr. Emily Parker', 'role' => 'Community Partner', 'quote' => 'An exceptional institution that truly prepares students for the challenges of the 21st century. Highly recommended.'],
];

// Demo events (in production from DB)
$events = $events ?? [
    ['title' => 'Annual Science Fair', 'date' => '2025-02-15', 'desc' => 'Students showcase their innovative science projects.'],
    ['title' => 'Parent-Teacher Conference', 'date' => '2025-03-01', 'desc' => 'Discuss your child progress with their teachers.'],
    ['title' => 'Spring Concert', 'date' => '2025-03-20', 'desc' => 'Musical performances by our talented students.'],
];

// Capture this page content, then load into website layout
ob_start();
?>

<!-- ─── ABOUT US SECTION ──────────────────────────────────────────────────── -->
<section id="about" class="py-16 sm:py-20 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Image Side -->
            <div class="relative">
                <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-emerald-100 shadow-xl">
                    <img src="/assets/images/hero-banner.jpg" alt="Students learning together" class="w-full h-full object-cover" onerror="this.style.display='none'">
                    <div class="absolute inset-0 bg-gradient-to-tr from-emerald-900/20 to-transparent"></div>
                </div>
                <!-- Floating stats card -->
                <div class="absolute -bottom-6 -right-4 sm:right-8 bg-white rounded-xl shadow-lg border border-gray-100 p-4 sm:p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">25+</p>
                            <p class="text-xs text-gray-500">Years of Excellence</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Text Side -->
            <div>
                <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-3">About Our School</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight mb-6">
                    Empowering Young Minds<br>Since <?= date('Y') - 25 ?>
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    <?= !empty($aboutUs) ? $aboutUs : 'At our school, we believe every child has unique potential waiting to be unlocked. Our dedicated team of educators creates a nurturing environment where academic excellence meets holistic development.' ?>
                </p>
                <p class="text-gray-600 leading-relaxed mb-6">
                    With state-of-the-art facilities, innovative teaching methods, and a strong focus on character building, we prepare our students not just for exams, but for life.
                </p>
                <div class="flex flex-wrap gap-3">
                    <div class="flex items-center gap-2 bg-emerald-50 px-4 py-2 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium text-emerald-800">CBSE Affiliated</span>
                    </div>
                    <div class="flex items-center gap-2 bg-emerald-50 px-4 py-2 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium text-emerald-800">Smart Classrooms</span>
                    </div>
                    <div class="flex items-center gap-2 bg-emerald-50 px-4 py-2 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium text-emerald-800">Sports Complex</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ─── STATS COUNTER SECTION ─────────────────────────────────────────────── -->
<section class="py-14 bg-emerald-900 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-30"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <p class="text-4xl sm:text-5xl font-extrabold text-white" data-counter="2500" data-suffix="+">0</p>
                <p class="mt-2 text-emerald-300 text-sm font-medium">Students Enrolled</p>
            </div>
            <div class="text-center">
                <p class="text-4xl sm:text-5xl font-extrabold text-white" data-counter="150" data-suffix="+">0</p>
                <p class="mt-2 text-emerald-300 text-sm font-medium">Qualified Teachers</p>
            </div>
            <div class="text-center">
                <p class="text-4xl sm:text-5xl font-extrabold text-white" data-counter="45" data-suffix="+">0</p>
                <p class="mt-2 text-emerald-300 text-sm font-medium">Classes & Sections</p>
            </div>
            <div class="text-center">
                <p class="text-4xl sm:text-5xl font-extrabold text-white" data-counter="25" data-suffix="+">0</p>
                <p class="mt-2 text-emerald-300 text-sm font-medium">Years of Excellence</p>
            </div>
        </div>
    </div>
</section>

<!-- ─── PROGRAMS / CLASSES SECTION ────────────────────────────────────────── -->
<section class="py-16 sm:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-3">Our Programs</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Academic Programs</h2>
            <p class="text-gray-600">We offer comprehensive programs from early childhood through high school, designed to challenge and inspire every student.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            // Display classes if available, otherwise show default programs
            $defaultPrograms = [
                ['name' => 'Pre-Primary', 'desc' => 'Nurturing curiosity and foundational skills in a playful, supportive environment for ages 3-5.', 'icon' => 'M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z', 'color' => 'bg-amber-50 text-amber-600'],
                ['name' => 'Primary School', 'desc' => 'Building strong foundations in literacy, numeracy, and critical thinking through engaging curriculum.', 'icon' => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25', 'color' => 'bg-blue-50 text-blue-600'],
                ['name' => 'Middle School', 'desc' => 'Developing intellectual curiosity and personal responsibility with a rigorous academic program.', 'icon' => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84', 'color' => 'bg-purple-50 text-purple-600'],
                ['name' => 'High School', 'desc' => 'Preparing students for college and careers with advanced coursework and extracurricular activities.', 'icon' => 'M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513', 'color' => 'bg-rose-50 text-rose-600'],
                ['name' => 'Science & Technology', 'desc' => 'STEM-focused labs and innovation programs fostering the next generation of scientists and engineers.', 'icon' => 'M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5', 'color' => 'bg-cyan-50 text-cyan-600'],
                ['name' => 'Arts & Sports', 'desc' => 'Comprehensive arts, music, and athletics programs nurturing creative and physical excellence.', 'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z', 'color' => 'bg-orange-50 text-orange-600'],
            ];

            $programs = !empty($classes) ? array_map(function ($c) {
                return [
                    'name' => htmlspecialchars($c['name'] ?? 'Class'),
                    'desc' => htmlspecialchars($c['description'] ?? 'A comprehensive academic program designed for excellence.'),
                    'icon' => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5',
                    'color' => 'bg-emerald-50 text-emerald-600',
                ];
            }, array_slice($classes, 0, 6)) : $defaultPrograms;
            ?>

            <?php foreach ($programs as $idx => $program): ?>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-emerald-200 transition-all duration-300 group">
                <div class="w-14 h-14 rounded-xl <?= $program['color'] ?> flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $program['icon'] ?>"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2"><?= $program['name'] ?></h3>
                <p class="text-gray-600 text-sm leading-relaxed"><?= $program['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($classes) && count($classes) > 6): ?>
            <div class="text-center mt-10">
                <a href="/classes" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold transition-colors">
                    View All Programs
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ─── WHY CHOOSE US ─────────────────────────────────────────────────────── -->
<section class="py-16 sm:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-3">Why Choose Us</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">What Sets Us Apart</h2>
            <p class="text-gray-600">Our commitment to excellence goes beyond academics. We provide a well-rounded educational experience.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <?php
            $features = [
                ['icon' => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342', 'title' => 'Expert Faculty', 'desc' => 'Highly qualified and passionate teachers dedicated to student success.'],
                ['icon' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z', 'title' => 'Safe Environment', 'desc' => 'Secure campus with modern infrastructure and 24/7 surveillance for student safety.'],
                ['icon' => 'M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z', 'title' => 'Modern Technology', 'desc' => 'Smart classrooms, digital learning tools, and tech-integrated curriculum.'],
                ['icon' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z', 'title' => 'Holistic Growth', 'desc' => 'Focus on academics, sports, arts, and character development for all-round growth.'],
            ];
            ?>
            <?php foreach ($features as $idx => $f): ?>
            <div class="text-center p-6 rounded-xl hover:bg-emerald-50 transition-colors group">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-100 flex items-center justify-center mb-5 group-hover:bg-emerald-200 transition-colors">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $f['icon'] ?>"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2"><?= $f['title'] ?></h3>
                <p class="text-gray-600 text-sm leading-relaxed"><?= $f['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ─── TESTIMONIALS SECTION ─────────────────────────────────────────────── -->
<section class="py-16 sm:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-3">Testimonials</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">What People Say</h2>
            <p class="text-gray-600">Hear from our students, parents, and community partners about their experience.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <?php foreach ($testimonials as $idx => $t): ?>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 relative">
                <!-- Quote mark -->
                <svg class="w-10 h-10 text-emerald-200 mb-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10H0z"/>
                </svg>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">"<?= htmlspecialchars($t['quote']) ?>"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                        <span class="text-emerald-700 font-semibold text-sm"><?= strtoupper(substr($t['name'], 0, 1)) ?></span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($t['name']) ?></p>
                        <p class="text-xs text-gray-500"><?= htmlspecialchars($t['role']) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ─── NEWS & EVENTS SECTION ────────────────────────────────────────────── -->
<section class="py-16 sm:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-3">Stay Updated</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">News & Events</h2>
            <p class="text-gray-600">Keep up with the latest happenings, achievements, and upcoming events at our school.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <?php foreach ($events as $idx => $event): 
                $eventDate = strtotime($event['date'] ?? 'now');
                $month = date('M', $eventDate);
                $day = date('d', $eventDate);
            ?>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="bg-emerald-600 px-4 py-3 flex items-center gap-3">
                    <div class="text-center bg-white rounded-lg px-3 py-1.5">
                        <p class="text-lg font-bold text-emerald-700 leading-none"><?= $day ?></p>
                        <p class="text-xs text-emerald-500 uppercase"><?= $month ?></p>
                    </div>
                    <h3 class="text-white font-semibold text-sm"><?= htmlspecialchars($event['title']) ?></h3>
                </div>
                <div class="p-5">
                    <p class="text-gray-600 text-sm leading-relaxed"><?= htmlspecialchars($event['desc']) ?></p>
                    <a href="#" class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700 text-sm font-medium mt-3 transition-colors">
                        Read More
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ─── CALL TO ACTION SECTION ───────────────────────────────────────────── -->
<section class="py-16 sm:py-20 hero-gradient hero-pattern relative overflow-hidden">
    <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-white/5"></div>
    <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-white/5"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Ready to Give Your Child the Best Education?</h2>
        <p class="text-emerald-100 text-lg mb-8 max-w-2xl mx-auto">
            Admissions are now open for the upcoming academic year. Take the first step towards a brighter future.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/admission/form" class="inline-flex items-center gap-2 bg-white text-emerald-800 font-semibold px-8 py-3.5 rounded-xl hover:bg-emerald-50 transition-colors shadow-lg text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                </svg>
                Apply for Admission
            </a>
            <a href="/contact" class="inline-flex items-center gap-2 border-2 border-white/50 text-white font-semibold px-8 py-3.5 rounded-xl hover:bg-white/10 hover:border-white transition-colors text-base">
                Contact Us
            </a>
        </div>
    </div>
</section>

<?php $content = ob_get_clean(); ?>

<?php
// Now include the website layout with this content
$_layoutData = [
    'siteSettings' => $siteSettings,
    'menuItems'    => $menuItems,
    'content'      => $content,
    'showHero'     => true,
    'pageTitle'    => 'Home',
];
extract($_layoutData, EXTR_SKIP);
require dirname(__DIR__) . '/../layouts/website.php';
?>
