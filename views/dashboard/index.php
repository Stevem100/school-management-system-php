<?php
$user = isset($user) ? $user : [];
$stats = isset($stats) ? $stats : [];
$recentActivity = isset($recentActivity) ? $recentActivity : [];
$upcomingExams = isset($upcomingExams) ? $upcomingExams : [];
$userRoles = isset($userRoles) ? $userRoles : [];

$firstName = $user['first_name'] ?? 'User';
$studentCount = $stats['student_count'] ?? 0;
$teacherCount = $stats['teacher_count'] ?? 0;
$classCount = $stats['class_count'] ?? 0;
$revenue = $stats['revenue'] ?? 0;
$studentTrend = $stats['student_trend'] ?? 12.5;
$teacherTrend = $stats['teacher_trend'] ?? 8.2;
$classTrend = $stats['class_trend'] ?? 5.1;
$revenueTrend = $stats['revenue_trend'] ?? 15.3;

$enrollmentData = $stats['enrollment_data'] ?? [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    'values' => [120, 132, 145, 160, 155, 168, 180, 195, 210, 225, 238, 250]
];

$feeData = $stats['fee_data'] ?? [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    'values' => [45000, 52000, 48000, 61000, 55000, 67000, 72000, 68000, 75000, 82000, 78000, 91000]
];
?>

<!-- Welcome Section -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, <?= htmlspecialchars($firstName) ?>!</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Here's what's happening at your school today.</p>
        </div>
        <div class="mt-3 sm:mt-0">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                </span>
                <?= date('l, F j, Y') ?>
            </span>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">
    <!-- Students Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
                <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div class="flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium <?= $studentTrend >= 0 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' ?>">
                <?php if ($studentTrend >= 0): ?>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                <?php else: ?>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                <?php endif; ?>
                <?= abs($studentTrend) ?>%
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($studentCount) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Students</p>
        </div>
    </div>

    <!-- Teachers Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0119.5 12c0 .818-.082 1.616-.244 2.386L12 14zm-6.16 3.422A12.083 12.083 0 004.5 12c0-.818.082-1.616.244-2.386L6 14l6 6z"/>
                </svg>
            </div>
            <div class="flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium <?= $teacherTrend >= 0 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' ?>">
                <?php if ($teacherTrend >= 0): ?>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                <?php else: ?>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                <?php endif; ?>
                <?= abs($teacherTrend) ?>%
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($teacherCount) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Teachers</p>
        </div>
    </div>

    <!-- Classes Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
                <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div class="flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium <?= $classTrend >= 0 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' ?>">
                <?php if ($classTrend >= 0): ?>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                <?php else: ?>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                <?php endif; ?>
                <?= abs($classTrend) ?>%
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($classCount) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Classes</p>
        </div>
    </div>

    <!-- Revenue Card -->
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900">
                <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium <?= $revenueTrend >= 0 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' ?>">
                <?php if ($revenueTrend >= 0): ?>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                <?php else: ?>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                <?php endif; ?>
                <?= abs($revenueTrend) ?>%
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white">KES <?= number_format($revenue) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Revenue</p>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
    <!-- Student Enrollment Chart -->
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Student Enrollment</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Monthly enrollment trend</p>
            </div>
            <div class="flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                +108% YoY
            </div>
        </div>
        <div class="h-64">
            <canvas id="enrollmentChart"></canvas>
        </div>
    </div>

    <!-- Fee Collection Chart -->
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Fee Collection</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Monthly revenue trend</p>
            </div>
            <div class="flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                +15.3% YoY
            </div>
        </div>
        <div class="h-64">
            <canvas id="feeChart"></canvas>
        </div>
    </div>
</div>

<!-- Info Cards Row -->
<div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
    <!-- Recent Activity -->
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
            <a href="#" class="text-xs text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">View all</a>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800 max-h-80 overflow-y-auto">
            <?php if (!empty($recentActivity)): ?>
                <?php foreach ($recentActivity as $i => $activity): ?>
                    <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full <?= $activity['bg_class'] ?? 'bg-emerald-100 dark:bg-emerald-900' ?>">
                            <svg class="h-4 w-4 <?= $activity['icon_class'] ?? 'text-emerald-600' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="<?= $activity['icon_path'] ?? 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z' ?>"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm text-gray-900 dark:text-white"><?= htmlspecialchars($activity['message'] ?? 'Activity recorded') ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?= htmlspecialchars($activity['time'] ?? 'Just now') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-8 w-8 mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    No recent activity
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Upcoming Exams -->
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-800">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Upcoming Exams</h3>
            <a href="/exams" class="text-xs text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">View all</a>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800 max-h-80 overflow-y-auto">
            <?php if (!empty($upcomingExams)): ?>
                <?php foreach ($upcomingExams as $exam): ?>
                    <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                            <span class="text-[10px] font-bold uppercase text-amber-700 dark:text-amber-300"><?= htmlspecialchars($exam['month'] ?? 'Dec') ?></span>
                            <span class="text-lg font-bold text-amber-800 dark:text-amber-200"><?= htmlspecialchars($exam['day'] ?? '15') ?></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($exam['name'] ?? 'Final Exam') ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($exam['subject'] ?? 'All Subjects') ?></p>
                        </div>
                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-medium bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300"><?= htmlspecialchars($exam['type'] ?? 'Final') ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-8 w-8 mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    No upcoming exams
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 mb-6">
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400">Frequently used shortcuts</p>
    </div>
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <a href="/students/create" class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 text-center hover:border-emerald-300 hover:bg-emerald-50 dark:border-gray-700 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/50 transition-all group">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 group-hover:bg-emerald-200 dark:bg-emerald-900 dark:group-hover:bg-emerald-800 transition-colors">
                <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Add Student</span>
        </a>
        <a href="/payments/create" class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 text-center hover:border-emerald-300 hover:bg-emerald-50 dark:border-gray-700 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/50 transition-all group">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 group-hover:bg-rose-200 dark:bg-rose-900 dark:group-hover:bg-rose-800 transition-colors">
                <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Record Payment</span>
        </a>
        <a href="/exams/create" class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 text-center hover:border-emerald-300 hover:bg-emerald-50 dark:border-gray-700 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/50 transition-all group">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 group-hover:bg-violet-200 dark:bg-violet-900 dark:group-hover:bg-violet-800 transition-colors">
                <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Create Exam</span>
        </a>
        <a href="/communication" class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 text-center hover:border-emerald-300 hover:bg-emerald-50 dark:border-gray-700 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/50 transition-all group">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 group-hover:bg-amber-200 dark:bg-amber-900 dark:group-hover:bg-amber-800 transition-colors">
                <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Send Notification</span>
        </a>
    </div>
</div>

<!-- Chart.js Initialization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Detect dark mode
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
    const textColor = isDark ? '#9ca3af' : '#6b7280';

    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: isDark ? '#1f2937' : '#ffffff',
                titleColor: isDark ? '#f9fafb' : '#111827',
                bodyColor: isDark ? '#d1d5db' : '#4b5563',
                borderColor: isDark ? '#374151' : '#e5e7eb',
                borderWidth: 1,
                cornerRadius: 8,
                padding: 12,
                displayColors: false,
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { color: textColor, font: { size: 11 } },
                border: { display: false }
            },
            y: {
                grid: { color: gridColor },
                ticks: { color: textColor, font: { size: 11 } },
                border: { display: false },
                beginAtZero: true,
            }
        }
    };

    // Enrollment Chart
    new Chart(document.getElementById('enrollmentChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($enrollmentData['labels']) ?>,
            datasets: [{
                data: <?= json_encode($enrollmentData['values']) ?>,
                backgroundColor: 'rgba(5, 150, 105, 0.15)',
                hoverBackgroundColor: 'rgba(5, 150, 105, 0.3)',
                borderColor: 'rgba(5, 150, 105, 0.8)',
                borderWidth: 1.5,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    ...commonOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' students';
                        }
                    }
                }
            }
        }
    });

    // Fee Chart
    new Chart(document.getElementById('feeChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($feeData['labels']) ?>,
            datasets: [{
                data: <?= json_encode($feeData['values']) ?>,
                backgroundColor: 'rgba(244, 63, 94, 0.12)',
                hoverBackgroundColor: 'rgba(244, 63, 94, 0.25)',
                borderColor: 'rgba(244, 63, 94, 0.8)',
                borderWidth: 1.5,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    ...commonOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            return 'KES ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                ...commonOptions.scales,
                y: {
                    ...commonOptions.scales.y,
                    ticks: {
                        ...commonOptions.scales.y.ticks,
                        callback: function(value) {
                            return 'KES ' + (value / 1000) + 'k';
                        }
                    }
                }
            }
        }
    });
</script>
