<?php
$class = $class ?? [];
$teacher = $teacher ?? [];
$students = $students ?? [];
$subjects = $subjects ?? [];
$studentCount = count($students);
$subjectCount = count($subjects);
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/classes') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($class['name'] ?? 'Class Details') ?></h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e($class['gradeLevel'] ?? '') ?> · Section <?= e($class['section'] ?? '') ?></p>
      </div>
    </div>
    <a href="<?= url('/classes/' . ($class['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Edit
    </a>
  </div>

  <!-- Stats -->
  <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($studentCount) ?>/<?= number_format($class['capacity'] ?? 0) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Students</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
          <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($subjectCount) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Subjects</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
          <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($class['academicYear'] ?? '—') ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Academic Year</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900">
          <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div>
          <p class="text-sm font-bold text-gray-900 dark:text-white"><?= e(trim(($teacher['firstName'] ?? '') . ' ' . ($teacher['lastName'] ?? '')) ?: 'Unassigned') ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Class Teacher</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Class Info -->
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Class Information</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Class Name</p><p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($class['name'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Grade Level</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($class['gradeLevel'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Section</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($class['section'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Capacity</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= number_format($class['capacity'] ?? 0) ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Academic Year</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($class['academicYear'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Status</p>
        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium mt-1 <?= ($class['status'] ?? 'active') === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>"><?= ucfirst($class['status'] ?? 'active') ?></span>
      </div>
    </div>
  </div>

  <!-- Students List -->
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Enrolled Students (<?= $studentCount ?>)</h3>
    </div>
    <?php if (!empty($students)): ?>
    <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-96 overflow-y-auto">
      <?php foreach($students as $s): ?>
      <div class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 text-xs font-bold text-emerald-700 dark:text-emerald-300"><?= strtoupper(mb_substr($s['first_name'] ?? 'U', 0, 1)) ?></div>
        <div class="min-w-0 flex-1">
          <p class="text-sm font-medium text-gray-900 dark:text-white"><?= e(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? '')) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400"><?= e($s['admission_no'] ?? '') ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
      <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/></svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No students enrolled</p>
    </div>
    <?php endif; ?>
  </div>
</div>
