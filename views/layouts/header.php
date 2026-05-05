<?php
$user = isset($user) ? $user : null;
$pageTitle = isset($pageTitle) ? $pageTitle : 'Dashboard';
$notifications = isset($notifications) ? $notifications : [];
$unreadCount = isset($unreadCount) ? $unreadCount : 3;
$branches = isset($branches) ? $branches : [];
$currentBranch = isset($currentBranch) ? $currentBranch : 'Main Campus';
$userRoles = isset($userRoles) ? $userRoles : [];
$initials = $user ? strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? '', 0, 1)) : 'U';
$fullName = $user ? trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) : 'User';
$roleName = $user ? ($user['role'] ?? 'User') : 'User';
?>

<header class="sticky top-0 z-30 border-b border-gray-200 bg-white/80 backdrop-blur-md dark:border-gray-800 dark:bg-gray-900/80">
    <div class="flex h-16 items-center justify-between px-4 lg:px-6">

        <!-- Left: Mobile menu toggle + Page title -->
        <div class="flex items-center gap-3">
            <!-- Mobile menu toggle -->
            <button onclick="toggleSidebar()" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white lg:hidden" aria-label="Toggle menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Desktop sidebar collapse toggle -->
            <button onclick="toggleSidebarCollapse()" class="hidden rounded-lg p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white lg:block" aria-label="Collapse sidebar">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                </svg>
            </button>

            <!-- Page title -->
            <div>
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($pageTitle) ?></h1>
                <p class="hidden text-xs text-gray-500 dark:text-gray-400 sm:block">
                    <?= date('l, F j, Y') ?>
                </p>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2 sm:gap-3">

            <!-- Branch Switcher (SuperAdmin / SchoolAdmin only) -->
            <?php if (in_array('SuperAdmin', $userRoles) || in_array('SchoolAdmin', $userRoles)): ?>
                <div class="relative hidden sm:block">
                    <button onclick="document.getElementById('branchDropdown').classList.toggle('hidden')" class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-1.5 text-sm hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                        <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="max-w-[120px] truncate text-gray-700 dark:text-gray-300"><?= htmlspecialchars($currentBranch) ?></span>
                        <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="branchDropdown" class="hidden absolute right-0 top-full mt-1 w-56 rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800 z-50">
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Branches</div>
                        <?php if (!empty($branches)): ?>
                            <?php foreach ($branches as $branch): ?>
                                <a href="/switch-branch/<?= urlencode($branch['id'] ?? '') ?>" class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-emerald-50 dark:hover:bg-emerald-950 <?= ($currentBranch === ($branch['name'] ?? '')) ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'text-gray-700 dark:text-gray-300' ?>">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span><?= htmlspecialchars($branch['name'] ?? 'Unknown') ?></span>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="px-3 py-2 text-sm text-gray-400">Main Campus</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Dark mode toggle -->
            <button onclick="toggleDarkMode()" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" aria-label="Toggle dark mode">
                <svg class="h-5 w-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            <!-- Notifications -->
            <div class="relative">
                <button onclick="document.getElementById('notifDropdown').classList.toggle('hidden')" class="relative rounded-lg p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800" aria-label="Notifications">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <?php if ($unreadCount > 0): ?>
                        <span class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white"><?= $unreadCount > 9 ? '9+' : $unreadCount ?></span>
                    <?php endif; ?>
                </button>
                <div id="notifDropdown" class="hidden absolute right-0 top-full mt-1 w-80 rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800 z-50">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
                        <a href="/notifications" class="text-xs text-emerald-600 hover:text-emerald-700">Mark all read</a>
                    </div>
                    <?php if (!empty($notifications)): ?>
                        <?php foreach (array_slice($notifications, 0, 5) as $notif): ?>
                            <a href="#" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900">
                                    <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-gray-900 dark:text-white truncate"><?= htmlspecialchars($notif['message'] ?? 'New notification') ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?= htmlspecialchars($notif['time'] ?? 'Just now') ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="px-4 py-8 text-center text-sm text-gray-500">No new notifications</div>
                    <?php endif; ?>
                    <div class="border-t border-gray-100 dark:border-gray-700 px-4 py-2">
                        <a href="/notifications" class="block text-center text-sm text-emerald-600 hover:text-emerald-700">View all notifications</a>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div class="relative">
                <button onclick="document.getElementById('userDropdown').classList.toggle('hidden')" class="flex items-center gap-2 rounded-lg p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-sm font-bold text-white">
                        <?= $initials ?>
                    </div>
                    <div class="hidden text-left sm:block">
                        <p class="text-sm font-medium text-gray-900 dark:text-white max-w-[140px] truncate"><?= htmlspecialchars($fullName) ?></p>
                        <span class="inline-block rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-medium text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300"><?= htmlspecialchars($roleName) ?></span>
                    </div>
                    <svg class="h-4 w-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="userDropdown" class="hidden absolute right-0 top-full mt-1 w-56 rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800 z-50">
                    <!-- User info header -->
                    <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($fullName) ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($user['email'] ?? 'user@school.com') ?></p>
                    </div>
                    <a href="/profile" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/50">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profile
                    </a>
                    <a href="/settings" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/50">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                    <div class="border-t border-gray-100 dark:border-gray-700 mt-1 pt-1">
                        <a href="/logout" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Close dropdowns on outside click -->
<script>
    document.addEventListener('click', function(e) {
        // Branch dropdown
        const branchBtn = e.target.closest('[onclick*="branchDropdown"]');
        const branchDropdown = document.getElementById('branchDropdown');
        if (!branchBtn && branchDropdown && !branchDropdown.contains(e.target)) {
            branchDropdown.classList.add('hidden');
        }
        // Notification dropdown
        const notifBtn = e.target.closest('[onclick*="notifDropdown"]');
        const notifDropdown = document.getElementById('notifDropdown');
        if (!notifBtn && notifDropdown && !notifDropdown.contains(e.target)) {
            notifDropdown.classList.add('hidden');
        }
        // User dropdown
        const userBtn = e.target.closest('[onclick*="userDropdown"]');
        const userDropdown = document.getElementById('userDropdown');
        if (!userBtn && userDropdown && !userDropdown.contains(e.target)) {
            userDropdown.classList.add('hidden');
        }
    });
</script>
