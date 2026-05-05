<?php
$subject = $subject ?? [];
$classes = $classes ?? [];
$teacher = $teacher ?? [];
?>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/subjects') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($subject['name'] ?? 'Subject Details') ?></h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e($subject['code'] ?? '') ?></p>
      </div>
    </div>
    <a href="<?= url('/subjects/' . ($subject['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Edit
    </a>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Subject Information</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Subject Name</p><p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($subject['name'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Code</p><p class="text-sm font-mono text-emerald-700 dark:text-emerald-400 mt-1"><?= e($subject['code'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Type</p>
        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium mt-1 <?= ($subject['type'] ?? 'core') === 'core' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400' ?>"><?= ucfirst($subject['type'] ?? 'core') ?></span>
      </div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Credit Hours</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= number_format($subject['credit_hours'] ?? 0) ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Status</p>
        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium mt-1 <?= ($subject['status'] ?? 'active') === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>">
          <span class="h-1.5 w-1.5 rounded-full <?= ($subject['status'] ?? 'active') === 'active' ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span><?= ucfirst($subject['status'] ?? 'active') ?>
        </span>
      </div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Teacher</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e(trim(($teacher['firstName'] ?? '') . ' ' . ($teacher['lastName'] ?? '')) ?: '—') ?></p></div>
      <div class="sm:col-span-2 lg:col-span-3"><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Description</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($subject['description'] ?? '—') ?></p></div>
    </div>
  </div>
</div>
