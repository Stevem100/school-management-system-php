<?php
$user = $user ?? [];
$roles = $roles ?? [];
$school = $school ?? [];
$branch = $branch ?? [];
$fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
$initials = strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? '', 0, 1));
$isActive = $user['is_active'] ?? true;
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/users') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div class="flex items-center gap-3">
        <?php if (!empty($user['avatar'])): ?>
        <img src="<?= e($user['avatar']) ?>" alt="<?= e($fullName) ?>" class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
        <?php else: ?>
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 text-lg font-semibold text-emerald-700 dark:text-emerald-300"><?= $initials ?></div>
        <?php endif; ?>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($fullName) ?></h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5"><?= e($user['email'] ?? '') ?></p>
        </div>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium <?= $isActive ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>">
        <span class="h-1.5 w-1.5 rounded-full <?= $isActive ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
        <?= $isActive ? 'Active' : 'Inactive' ?>
      </span>
      <a href="<?= url('/users/' . ($user['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
      </a>
    </div>
  </div>

  <!-- User Info Cards -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Profile Information</h3>
      </div>
      <div class="p-5 space-y-4">
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Full Name</p>
          <p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($fullName) ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Email</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($user['email'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Phone</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($user['phone'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Status</p>
          <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium mt-1 <?= $isActive ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>">
            <span class="h-1.5 w-1.5 rounded-full <?= $isActive ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
            <?= $isActive ? 'Active' : 'Inactive' ?>
          </span>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Last Login</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($user['last_login'] ?? 'Never') ?></p>
        </div>
      </div>
    </div>

    <!-- Organization -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Organization</h3>
      </div>
      <div class="p-5 space-y-4">
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">School</p>
          <p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($school['name'] ?? $user['schoolName'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Branch</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($branch['name'] ?? $user['branchName'] ?? '—') ?></p>
        </div>
      </div>
    </div>

    <!-- Roles -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Assigned Roles</h3>
      </div>
      <div class="p-5">
        <?php if (!empty($roles)): ?>
        <div class="flex flex-wrap gap-2">
          <?php foreach($roles as $role): ?>
          <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-100 dark:bg-violet-900/30 px-3 py-1 text-xs font-medium text-violet-700 dark:text-violet-400">
            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <?= e($role['display_name'] ?? $role['name'] ?? '') ?>
          </span>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-sm text-gray-400 dark:text-gray-500">No roles assigned</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
