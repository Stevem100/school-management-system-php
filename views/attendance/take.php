<?php
$students = $students ?? [];
$classes = $classes ?? [];
$classId = $classId ?? '';
$date = $date ?? date('Y-m-d');
?>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/attendance') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Take Attendance</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mark attendance for students</p>
      </div>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= url('/attendance/take') ?>">
      <?= csrf_field() ?>
      <div class="p-6 border-b border-gray-100 dark:border-gray-700 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class <span class="text-red-500">*</span></label>
            <select id="class_id" name="class_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select class</option>
              <?php foreach($classes as $c): ?>
              <option value="<?= e($c['id']) ?>" <?= ($classId ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date <span class="text-red-500">*</span></label>
            <input type="date" id="date" name="date" required value="<?= e($date) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>
      </div>

      <!-- Student List -->
      <?php if (!empty($students)): ?>
      <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-96 overflow-y-auto">
        <?php foreach($students as $s): ?>
        <div class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
          <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 text-xs font-bold text-emerald-700 dark:text-emerald-300 shrink-0"><?= strtoupper(mb_substr($s['first_name'] ?? 'U', 0, 1)) ?></div>
          <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-900 dark:text-white"><?= e(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? '')) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400"><?= e($s['admission_no'] ?? '') ?></p>
          </div>
          <div class="flex items-center gap-3 shrink-0">
            <label class="flex items-center gap-1.5 cursor-pointer">
              <input type="radio" name="attendance[<?= e($s['id'] ?? '') ?>]" value="present" checked class="h-4 w-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
              <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Present</span>
            </label>
            <label class="flex items-center gap-1.5 cursor-pointer">
              <input type="radio" name="attendance[<?= e($s['id'] ?? '') ?>]" value="absent" class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500">
              <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Absent</span>
            </label>
            <label class="flex items-center gap-1.5 cursor-pointer">
              <input type="radio" name="attendance[<?= e($s['id'] ?? '') ?>]" value="late" class="h-4 w-4 text-amber-600 border-gray-300 focus:ring-amber-500">
              <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Late</span>
            </label>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/attendance') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Save Attendance</button>
      </div>
      <?php else: ?>
      <div class="flex flex-col items-center justify-center py-16 text-gray-400">
        <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Select a class to take attendance</p>
        <p class="text-xs text-gray-400 dark:text-gray-500">Choose a class above to see the student list</p>
      </div>
      <?php endif; ?>
    </form>
  </div>
</div>
