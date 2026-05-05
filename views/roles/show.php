<?php
$role = $role ?? [];
$permissions = $permissions ?? [];
$usersWithRole = $usersWithRole ?? [];
$scopeColors = [
    'global'   => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
    'school'   => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
    'branch'   => 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400',
    'personal' => 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400',
];
$scope = $role['scope'] ?? 'global';
$scopeClass = $scopeColors[$scope] ?? $scopeColors['global'];
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/roles') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div class="flex items-center gap-3">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($role['display_name'] ?? $role['name'] ?? 'Role Details') ?></h1>
          <div class="flex items-center gap-2 mt-0.5">
            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium <?= $scopeClass ?>"><?= e(ucfirst($scope)) ?> Scope</span>
            <span class="text-xs text-gray-400"><?= count($permissions) ?> permission<?= count($permissions) !== 1 ? 's' : '' ?></span>
          </div>
        </div>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <a href="<?= url('/roles/' . ($role['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Role Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Role Information</h3>
      </div>
      <div class="p-5 space-y-4">
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Display Name</p>
          <p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($role['display_name'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Internal Name</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 font-mono"><?= e($role['name'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Description</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($role['description'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Scope</p>
          <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium mt-1 <?= $scopeClass ?>"><?= e(ucfirst($scope)) ?></span>
        </div>
      </div>
    </div>

    <!-- Permissions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Permissions (<?= count($permissions) ?>)</h3>
      </div>
      <div class="p-5 max-h-96 overflow-y-auto">
        <?php if (!empty($permissions)): ?>
        <div class="flex flex-wrap gap-2">
          <?php foreach($permissions as $perm): ?>
          <span class="inline-flex items-center gap-1 rounded-md bg-gray-50 dark:bg-gray-900 px-2.5 py-1 text-xs font-medium text-gray-700 dark:text-gray-300">
            <svg class="h-3 w-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <?= e($perm['display_name'] ?? $perm['name'] ?? '') ?>
          </span>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-sm text-gray-400 dark:text-gray-500">No permissions assigned</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Users with this role -->
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Users with this Role (<?= count($usersWithRole) ?>)</h3>
    </div>
    <?php if (!empty($usersWithRole)): ?>
    <div class="divide-y divide-gray-100 dark:divide-gray-700">
      <?php foreach($usersWithRole as $u): ?>
      <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 text-xs font-semibold text-emerald-700 dark:text-emerald-300"><?= strtoupper(substr($u['first_name'] ?? 'U', 0, 1)) ?></div>
        <div class="min-w-0 flex-1">
          <p class="text-sm font-medium text-gray-900 dark:text-white"><?= e(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? '')) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400"><?= e($u['email'] ?? '') ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
      <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/></svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No users assigned</p>
    </div>
    <?php endif; ?>
  </div>
</div>
