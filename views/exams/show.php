<?php
$exam = $exam ?? [];
$subject = $subject ?? [];
$class = $class ?? [];
?>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/exams') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($exam['name'] ?? 'Exam Details') ?></h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= ucfirst($exam['type'] ?? '') ?> Exam</p>
      </div>
    </div>
    <a href="<?= url('/exams/' . ($exam['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Edit
    </a>
  </div>

  <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($exam['total_marks'] ?? 0) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Total Marks</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
          <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($exam['passing_marks'] ?? 0) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Passing Marks</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
          <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
          <p class="text-sm font-bold text-gray-900 dark:text-white"><?= e($exam['start_date'] ?? '—') ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Start Date</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900">
          <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
          <p class="text-sm font-bold text-gray-900 dark:text-white"><?= e($exam['end_date'] ?? '—') ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">End Date</p>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Exam Information</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Exam Name</p><p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($exam['name'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Type</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= ucfirst($exam['type'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Subject</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($subject['name'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Class</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($class['name'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Academic Year</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($exam['academic_year'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Term</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($exam['term'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Status</p>
        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium mt-1 <?= ($exam['status'] ?? 'active') === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>"><?= ucfirst($exam['status'] ?? 'active') ?></span>
      </div>
    </div>
  </div>
</div>
