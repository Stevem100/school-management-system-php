<?php $pageTitle = $pageTitle ?? 'Academic Reports'; ?>
<?php
  $results = $results ?? [];
  $classes = $classes ?? [];
  $exams = $exams ?? [];
  $subjects = $subjects ?? [];
  $summary = $summary ?? [];
  $filters = $filters ?? [];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Academic Reports</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View exam results and academic performance</p>
    </div>
  </div>

  <!-- Filters -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
    <form method="GET" action="<?= url('/reports/academic') ?>" class="flex flex-col sm:flex-row gap-3 items-end">
      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Class</label>
        <select name="class_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          <option value="">All Classes</option>
          <?php foreach ($classes as $cls): ?>
            <option value="<?= e($cls['id']) ?>" <?= ($filters['class_id'] ?? '') == $cls['id'] ? 'selected' : '' ?>><?= e($cls['name'] ?? "Class #{$cls['id']}") ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Exam</label>
        <select name="exam_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          <option value="">All Exams</option>
          <?php foreach ($exams as $ex): ?>
            <option value="<?= e($ex['id']) ?>" <?= ($filters['exam_id'] ?? '') == $ex['id'] ? 'selected' : '' ?>><?= e($ex['name'] ?? $ex['title'] ?? "Exam #{$ex['id']}") ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Subject</label>
        <select name="subject_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          <option value="">All Subjects</option>
          <?php foreach ($subjects as $sub): ?>
            <option value="<?= e($sub['id']) ?>" <?= ($filters['subject_id'] ?? '') == $sub['id'] ? 'selected' : '' ?>><?= e($sub['name'] ?? "Subject #{$sub['id']}") ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium shadow-sm">Apply</button>
        <a href="<?= url('/reports/academic') ?>" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Reset</a>
      </div>
    </form>
  </div>

  <!-- Summary Stats -->
  <?php if (!empty($summary)): ?>
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
          <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Average Score</p>
          <p class="text-xl font-bold text-gray-900 dark:text-white"><?= number_format((float) ($summary['average'] ?? 0), 1) ?>%</p>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Highest Score</p>
          <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400"><?= number_format((float) ($summary['highest'] ?? 0), 1) ?>%</p>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900">
          <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Lowest Score</p>
          <p class="text-xl font-bold text-red-600 dark:text-red-400"><?= number_format((float) ($summary['lowest'] ?? 0), 1) ?>%</p>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Results Table -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
      <p class="text-xs text-gray-500 dark:text-gray-400"><?= count($results) ?> records found</p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Exam</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Subject</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Marks</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grade</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php if (!empty($results)): ?>
            <?php $idx = 0; foreach ($results as $r): $idx++; ?>
            <?php
              $marks = (float) ($r['marks_obtained'] ?? $r['marks'] ?? 0);
              $total = (float) ($r['total_marks'] ?? 100);
              $percentage = $total > 0 ? ($marks / $total) * 100 : 0;
              $grade = $r['grade'] ?? '';

              // Auto-generate grade color
              if ($percentage >= 80) {
                $gradeClass = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400';
              } elseif ($percentage >= 60) {
                $gradeClass = 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400';
              } elseif ($percentage >= 40) {
                $gradeClass = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400';
              } else {
                $gradeClass = 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400';
              }
            ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
              <td class="px-4 py-3 text-gray-500 dark:text-gray-400"><?= $idx ?></td>
              <td class="px-4 py-3 font-medium text-gray-900 dark:text-white"><?= e($r['student_name'] ?? $r['name'] ?? 'Student #' . ($r['student_id'] ?? '')) ?></td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden md:table-cell"><?= e($r['exam_name'] ?? $r['exam_id'] ?? '—') ?></td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden sm:table-cell"><?= e($r['subject_name'] ?? $r['subject_id'] ?? '—') ?></td>
              <td class="px-4 py-3">
                <span class="font-medium text-gray-900 dark:text-white"><?= number_format($marks, 1) ?></span>
                <span class="text-gray-400 dark:text-gray-500 text-xs">/ <?= number_format($total, 0) ?></span>
                <span class="text-xs text-gray-400 dark:text-gray-500 ml-1">(<?= number_format($percentage, 0) ?>%)</span>
              </td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center rounded-full <?= $gradeClass ?> px-2.5 py-1 text-xs font-semibold"><?= e($grade) ?: '—' ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="px-4 py-16 text-center">
                <svg class="mx-auto w-16 h-16 mb-4 text-gray-300 dark:text-gray-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No results found</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Select filters and apply to view academic results</p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
