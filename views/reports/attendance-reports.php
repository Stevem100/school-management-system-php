<?php $pageTitle = $pageTitle ?? 'Attendance Reports'; ?>
<?php
  $records = $records ?? [];
  $summary = $summary ?? [];
  $classes = $classes ?? [];
  $filters = $filters ?? [];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Attendance Reports</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Student attendance analytics and summaries</p>
    </div>
    <a href="<?= url('/reports/export') ?>/attendance?<?= http_build_query($filters) ?>" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Export
    </a>
  </div>

  <!-- Summary Stats -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Present</p>
          <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400"><?= number_format((float) ($summary['present_percent'] ?? 0), 1) ?>%</p>
          <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"><?= number_format((int) ($summary['present_days'] ?? 0)) ?> days</p>
        </div>
      </div>
      <!-- Progress bar -->
      <div class="mt-3 w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: <?= min(100, (float) ($summary['present_percent'] ?? 0)) ?>%"></div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900">
          <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Absent</p>
          <p class="text-xl font-bold text-red-600 dark:text-red-400"><?= number_format((float) ($summary['absent_percent'] ?? 0), 1) ?>%</p>
          <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"><?= number_format((int) ($summary['absent_days'] ?? 0)) ?> days</p>
        </div>
      </div>
      <!-- Progress bar -->
      <div class="mt-3 w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
        <div class="bg-red-500 h-1.5 rounded-full" style="width: <?= min(100, (float) ($summary['absent_percent'] ?? 0)) ?>%"></div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900">
          <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Late</p>
          <p class="text-xl font-bold text-yellow-600 dark:text-yellow-400"><?= number_format((float) ($summary['late_percent'] ?? 0), 1) ?>%</p>
          <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"><?= number_format((int) ($summary['late_days'] ?? 0)) ?> days</p>
        </div>
      </div>
      <!-- Progress bar -->
      <div class="mt-3 w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
        <div class="bg-yellow-500 h-1.5 rounded-full" style="width: <?= min(100, (float) ($summary['late_percent'] ?? 0)) ?>%"></div>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
    <form method="GET" action="<?= url('/reports/attendance') ?>" class="flex flex-col sm:flex-row gap-3 items-end flex-wrap">
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
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Date From</label>
        <input type="date" name="date_from" value="<?= e($filters['date_from'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      </div>
      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Date To</label>
        <input type="date" name="date_to" value="<?= e($filters['date_to'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      </div>
      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
        <select name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          <option value="">All Status</option>
          <option value="present" <?= ($filters['status'] ?? '') === 'present' ? 'selected' : '' ?>>Present</option>
          <option value="absent" <?= ($filters['status'] ?? '') === 'absent' ? 'selected' : '' ?>>Absent</option>
          <option value="late" <?= ($filters['status'] ?? '') === 'late' ? 'selected' : '' ?>>Late</option>
        </select>
      </div>
      <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium shadow-sm">Apply</button>
        <a href="<?= url('/reports/attendance') ?>" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Reset</a>
      </div>
    </form>
  </div>

  <!-- Attendance Table -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
      <p class="text-xs text-gray-500 dark:text-gray-400"><?= count($records) ?> records found</p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Present Days</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Absent Days</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Late Days</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Attendance %</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php if (!empty($records)): ?>
            <?php $idx = 0; foreach ($records as $r): $idx++; ?>
            <?php
              $attendancePercent = (float) ($r['attendance_percent'] ?? $r['percentage'] ?? 0);
              if ($attendancePercent >= 85) {
                $percentClass = 'text-emerald-600 dark:text-emerald-400';
                $barColor = 'bg-emerald-500';
              } elseif ($attendancePercent >= 70) {
                $percentClass = 'text-yellow-600 dark:text-yellow-400';
                $barColor = 'bg-yellow-500';
              } else {
                $percentClass = 'text-red-600 dark:text-red-400';
                $barColor = 'bg-red-500';
              }
            ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
              <td class="px-4 py-3 text-gray-500 dark:text-gray-400"><?= $idx ?></td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <div class="h-8 w-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                    <?= strtoupper(mb_substr($r['student_name'] ?? $r['name'] ?? 'S', 0, 1)) ?>
                  </div>
                  <span class="font-medium text-gray-900 dark:text-white"><?= e($r['student_name'] ?? $r['name'] ?? 'Student #' . ($r['student_id'] ?? '')) ?></span>
                </div>
              </td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden sm:table-cell">
                <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                  <?= (int) ($r['present_days'] ?? $r['present'] ?? 0) ?>
                </span>
              </td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden sm:table-cell">
                <span class="inline-flex items-center gap-1 text-red-600 dark:text-red-400">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                  <?= (int) ($r['absent_days'] ?? $r['absent'] ?? 0) ?>
                </span>
              </td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden md:table-cell">
                <span class="inline-flex items-center gap-1 text-yellow-600 dark:text-yellow-400">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  <?= (int) ($r['late_days'] ?? $r['late'] ?? 0) ?>
                </span>
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <span class="font-semibold text-sm <?= $percentClass ?>"><?= number_format($attendancePercent, 1) ?>%</span>
                  <div class="flex-1 max-w-[80px] bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 hidden sm:block">
                    <div class="<?= $barColor ?> h-1.5 rounded-full transition-all" style="width: <?= min(100, $attendancePercent) ?>%"></div>
                  </div>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="px-4 py-16 text-center">
                <svg class="mx-auto w-16 h-16 mb-4 text-gray-300 dark:text-gray-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No attendance records found</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Adjust the filters to see attendance data</p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
