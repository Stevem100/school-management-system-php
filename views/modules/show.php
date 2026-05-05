<?php
$module = $module ?? [];
$permissions = $module['permissions'] ?? [];
$roleCount = $module['role_count'] ?? count($permissions);
?>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/modules') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div class="flex items-center gap-3">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($module['name'] ?? 'Module Details') ?></h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e($module['slug'] ?? '') ?></p>
        </div>
      </div>
    </div>
    <a href="<?= url('/modules/' . ($module['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Edit
    </a>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Module Information</h3>
      <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium <?= ($module['is_active'] ?? 1) ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>">
        <span class="h-1.5 w-1.5 rounded-full <?= ($module['is_active'] ?? 1) ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
        <?= ($module['is_active'] ?? 1) ? 'Active' : 'Inactive' ?>
      </span>
    </div>
    <div class="p-5 space-y-4">
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Module Name</p><p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($module['name'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Slug</p><p class="text-sm font-mono text-gray-700 dark:text-gray-300 mt-1"><?= e($module['slug'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Description</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($module['description'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Sort Order</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= number_format($module['sort_order'] ?? 0) ?></p></div>
    </div>
  </div>

  <!-- Permissions -->
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Permissions (<?= count($permissions) ?>)</h3>
    </div>
    <div class="p-5 max-h-96 overflow-y-auto">
      <?php if (!empty($permissions)): ?>
      <div class="divide-y divide-gray-100 dark:divide-gray-700">
        <?php foreach($permissions as $perm): ?>
        <div class="flex items-center justify-between py-3">
          <div>
            <p class="text-sm font-medium text-gray-900 dark:text-white"><?= e($perm['display_name'] ?? $perm['name'] ?? '') ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 font-mono"><?= e($perm['name'] ?? '') ?></p>
          </div>
          <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 text-[10px] font-medium text-emerald-700 dark:text-emerald-300"><?= e(ucfirst($perm['action'] ?? '')) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <p class="text-sm text-gray-400 dark:text-gray-500">No permissions defined for this module</p>
      <?php endif; ?>
    </div>
  </div>
</div>
