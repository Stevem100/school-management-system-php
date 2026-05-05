<?php
$result = $result ?? [];
$student = $result['student'] ?? [];
$exam = $result['exam'] ?? [];
$subject = $result['exam']['subject'] ?? [];
$percentage = ($result['total_marks'] ?? 0) > 0 ? round(($result['marks_obtained'] ?? 0) / ($result['total_marks'] ?? 1) * 100, 1) : 0;
$passed = ($result['marks_obtained'] ?? 0) >= ($result['passing_marks'] ?? 0);
?>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/results') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Result Details</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) ?> · <?= e($exam['name'] ?? '') ?></p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium <?= $passed ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' ?>">
        <span class="h-1.5 w-1.5 rounded-full <?= $passed ? 'bg-emerald-500' : 'bg-red-500' ?>"></span>
        <?= $passed ? 'Passed' : 'Failed' ?>
      </span>
      <a href="<?= url('/results/' . ($result['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
      </a>
    </div>
  </div>

  <!-- Score Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Score Card</h3>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div class="space-y-1">
          <p class="text-3xl font-bold text-gray-900 dark:text-white"><?= number_format($result['marks_obtained'] ?? 0) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Marks Obtained</p>
        </div>
        <div class="space-y-1">
          <p class="text-3xl font-bold text-gray-500 dark:text-gray-400"><?= number_format($result['total_marks'] ?? 0) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Total Marks</p>
        </div>
        <div class="space-y-1">
          <p class="text-3xl font-bold <?= $percentage >= 50 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' ?>"><?= number_format($percentage, 1) ?>%</p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Percentage</p>
        </div>
        <div class="space-y-1">
          <p class="text-3xl font-bold text-violet-600 dark:text-violet-400"><?= e($result['grade'] ?? '—') ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Grade</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Details -->
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Result Information</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Student</p><p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Admission No</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1 font-mono"><?= e($student['admission_no'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Exam</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($exam['name'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Subject</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($subject['name'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Passing Marks</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= number_format($result['passing_marks'] ?? 0) ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Remarks</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($result['remarks'] ?? '—') ?></p></div>
    </div>
  </div>
</div>
