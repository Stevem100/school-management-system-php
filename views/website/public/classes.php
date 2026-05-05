<?php
// ─────────────────────────────────────────────────────────────────────────────
// Classes / Programs Page
//
// Expected variables:
//   $classes      — array of class records
//   $siteSettings — array of website_settings
//   $menuItems    — array of navigation menu items
// ─────────────────────────────────────────────────────────────────────────────

$classes      = $classes ?? [];
$siteSettings = $siteSettings ?? [];
$menuItems    = $menuItems ?? [];

// Get unique grade levels for filtering
$gradeLevels = [];
foreach ($classes as $cls) {
    $level = $cls['grade_level'] ?? $cls['section'] ?? 'All';
    if (!in_array($level, $gradeLevels)) {
        $gradeLevels[] = $level;
    }
}
sort($gradeLevels);

ob_start();
?>

<!-- ─── PAGE HEADER ───────────────────────────────────────────────────────── -->
<section class="bg-emerald-900 relative overflow-hidden">
    <div class="absolute inset-0 hero-pattern opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 relative z-10">
        <div class="max-w-3xl">
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 text-sm text-emerald-300">
                    <li><a href="/" class="hover:text-white transition-colors">Home</a></li>
                    <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></li>
                    <li class="text-white font-medium">Classes & Programs</li>
                </ol>
            </nav>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-4">Our Classes & Programs</h1>
            <p class="text-emerald-200 text-lg max-w-xl">
                Explore our comprehensive range of academic programs designed to inspire excellence at every level.
            </p>
        </div>
    </div>
    <div class="absolute -bottom-8 -right-8 w-64 h-64 rounded-full bg-emerald-800/30"></div>
</section>

<!-- ─── CLASSES CONTENT ──────────────────────────────────────────────────── -->
<section class="py-12 sm:py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Filter Bar -->
        <?php if (count($gradeLevels) > 1): ?>
        <div class="mb-8 flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium text-gray-700">Filter by level:</span>
            <button onclick="filterClasses('all')" class="filter-btn active px-4 py-1.5 text-sm font-medium rounded-full border transition-colors" data-filter="all">All</button>
            <?php foreach ($gradeLevels as $level): ?>
                <button onclick="filterClasses('<?= htmlspecialchars($level) ?>')" class="filter-btn px-4 py-1.5 text-sm font-medium rounded-full border border-gray-300 text-gray-600 hover:border-emerald-300 hover:text-emerald-700 transition-colors" data-filter="<?= htmlspecialchars($level) ?>">
                    <?= htmlspecialchars($level) ?>
                </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($classes)): ?>
            <!-- Empty state -->
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">No Classes Listed</h2>
                <p class="text-gray-500 mb-6">There are currently no classes or programs available. Please check back later.</p>
                <a href="/" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-colors text-sm">
                    Return to Homepage
                </a>
            </div>
        <?php else: ?>
            <!-- Classes Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6" id="classesGrid">
                <?php foreach ($classes as $cls):
                    $classId    = $cls['id'] ?? '';
                    $name       = htmlspecialchars($cls['name'] ?? 'Class');
                    $section    = htmlspecialchars($cls['section'] ?? '');
                    $gradeLevel = htmlspecialchars($cls['grade_level'] ?? $section);
                    $capacity   = $cls['capacity'] ?? 0;
                    $desc       = htmlspecialchars($cls['description'] ?? '');
                    $status     = $cls['status'] ?? '';
                    $teacher    = htmlspecialchars($cls['teacher_id'] ?? '');
                    $room       = htmlspecialchars($cls['room'] ?? '');
                ?>
                <div class="class-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:border-emerald-200 transition-all duration-300 group" data-grade="<?= htmlspecialchars($cls['grade_level'] ?? $cls['section'] ?? 'all') ?>">
                    <!-- Card Header -->
                    <div class="bg-emerald-50 px-6 py-4 border-b border-emerald-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-emerald-900 group-hover:text-emerald-700 transition-colors"><?= $name ?></h3>
                            <?php if ($status === 'active'): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            <?php elseif ($status === 'inactive'): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    Inactive
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <p class="text-gray-600 text-sm leading-relaxed mb-5">
                            <?= !empty($desc) ? $desc : 'A comprehensive academic program designed to foster academic excellence and holistic development.' ?>
                        </p>

                        <!-- Class Details -->
                        <div class="space-y-2.5">
                            <?php if (!empty($section)): ?>
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                                </svg>
                                <span class="text-gray-500">Section:</span>
                                <span class="text-gray-900 font-medium"><?= $section ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ($capacity > 0): ?>
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                </svg>
                                <span class="text-gray-500">Capacity:</span>
                                <span class="text-gray-900 font-medium"><?= $capacity ?> Students</span>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($room)): ?>
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 7.5h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                                </svg>
                                <span class="text-gray-500">Room:</span>
                                <span class="text-gray-900 font-medium"><?= $room ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <a href="/admission/form" class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 text-sm font-medium transition-colors">
                            Apply for Admission
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary -->
            <div class="mt-10 text-center">
                <p class="text-gray-500 text-sm">
                    Showing <strong><?= count($classes) ?></strong> classes and programs.
                    For more details about specific programs, please
                    <a href="/contact" class="text-emerald-600 hover:text-emerald-700 font-medium">contact our admissions office</a>.
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ─── CTA ────────────────────────────────────────────────────────────────── -->
<section class="py-14 hero-gradient hero-pattern relative overflow-hidden">
    <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-white/5"></div>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Ready to Enroll?</h2>
        <p class="text-emerald-100 mb-6">Start your application today and join our community of learners.</p>
        <a href="/admission/form" class="inline-flex items-center gap-2 bg-white text-emerald-800 font-semibold px-8 py-3.5 rounded-xl hover:bg-emerald-50 transition-colors shadow-lg text-base">
            Apply Now
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </a>
    </div>
</section>

<?php if (count($gradeLevels) > 1): ?>
<script>
function filterClasses(grade) {
    // Update button styles
    document.querySelectorAll('.filter-btn').forEach(function(btn) {
        if (btn.dataset.filter === grade) {
            btn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');
            btn.classList.remove('border-gray-300', 'text-gray-600');
        } else {
            btn.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600');
            btn.classList.add('border-gray-300', 'text-gray-600');
        }
    });

    // Filter cards
    const cards = document.querySelectorAll('.class-card');
    let visibleCount = 0;

    cards.forEach(function(card) {
        if (grade === 'all' || card.dataset.grade === grade) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Show/hide empty state message
    let emptyMsg = document.getElementById('filterEmptyMsg');
    if (visibleCount === 0 && emptyMsg) {
        emptyMsg.style.display = '';
    } else if (emptyMsg) {
        emptyMsg.style.display = 'none';
    }
}

// Initialize the "All" button as active
document.addEventListener('DOMContentLoaded', function() {
    const allBtn = document.querySelector('.filter-btn[data-filter="all"]');
    if (allBtn) {
        allBtn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');
    }
});
</script>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>

<?php
$_layoutData = [
    'siteSettings' => $siteSettings,
    'menuItems'    => $menuItems,
    'content'      => $content,
    'showHero'     => false,
    'pageTitle'    => 'Classes & Programs',
];
extract($_layoutData, EXTR_SKIP);
require dirname(__DIR__) . '/../layouts/website.php';
?>
