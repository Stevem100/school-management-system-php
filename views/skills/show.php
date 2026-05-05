<?php
$skill = $skill ?? [];
?>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/skills') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div class="flex items-center gap-3">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($skill['name'] ?? 'Skill Details') ?></h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e($skill['code'] ?? '') ?></p>
        </div>
      </div>
    </div>
    <a href="<?= url('/skills/' . ($skill['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Edit
    </a>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Skill Information</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Skill Name</p><p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($skill['name'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Code</p><p class="text-sm font-mono text-emerald-700 dark:text-emerald-400 mt-1"><?= e($skill['code'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Category</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($skill['category'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Level</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($skill['level'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Strand</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($skill['strand'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Sub-strand</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($skill['sub_strand'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Status</p>
        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium mt-1 <?= ($skill['status'] ?? 'active') === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>"><?= ucfirst($skill['status'] ?? 'active') ?></span>
      </div>
      <div class="sm:col-span-2 lg:col-span-3"><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Description</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($skill['description'] ?? '—') ?></p></div>
    </div>
  </div>
</div>
