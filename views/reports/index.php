<?php $pageTitle = $pageTitle ?? 'Reports'; ?>
<?php
    $reportData = $reportData ?? null;
    $reportType = $reportType ?? '';
    $reportTitle = $reportTitle ?? '';
    $filters = $filters ?? [];
    $summary = $summary ?? null;
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reports</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Generate and export school reports and analytics</p>
  </div>

  <!-- Report Type Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Student Enrollment -->
    <a href="<?= url('/reports/students') ?>" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-700 transition-all group">
      <div class="flex items-center gap-3 mb-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800 transition-colors">
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Student Enrollment</h3>
      </div>
      <p class="text-xs text-gray-500 dark:text-gray-400">View student enrollment data by class, status, and date range.</p>
    </a>

    <!-- Fee Collection -->
    <a href="<?= url('/reports/financial') ?>" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-700 transition-all group">
      <div class="flex items-center gap-3 mb-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900 group-hover:bg-yellow-200 dark:group-hover:bg-yellow-800 transition-colors">
          <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Fee Collection</h3>
      </div>
      <p class="text-xs text-gray-500 dark:text-gray-400">Track fee payments, pending balances, and collection summaries.</p>
    </a>

    <!-- Attendance -->
    <a href="<?= url('/reports/attendance') ?>" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-700 transition-all group">
      <div class="flex items-center gap-3 mb-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors">
          <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Attendance</h3>
      </div>
      <p class="text-xs text-gray-500 dark:text-gray-400">Generate attendance reports by class, date range, and status.</p>
    </a>

    <!-- Exam Results -->
    <a href="<?= url('/reports/academic') ?>" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-700 transition-all group">
      <div class="flex items-center gap-3 mb-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900 group-hover:bg-purple-200 dark:group-hover:bg-purple-800 transition-colors">
          <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Exam Results</h3>
      </div>
      <p class="text-xs text-gray-500 dark:text-gray-400">View academic performance reports by exam, class, and subject.</p>
    </a>
  </div>

  <!-- Report Filters & Results (shown when a report is generated) -->
  <?php if ($reportData !== null): ?>
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Report Header -->
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h2 class="text-sm font-semibold text-gray-900 dark:text-white"><?= e($reportTitle) ?></h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?= count($reportData) ?> records found</p>
      </div>
      <div class="flex gap-2">
        <a href="<?= url('/reports/export') ?>/<?= e($reportType) ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-xs font-medium shadow-sm">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Export CSV
        </a>
        <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 dark:border-gray-700 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
          Print
        </button>
      </div>
    </div>

    <!-- Parameter Filters -->
    <div class="px-6 py-3 bg-gray-50 dark:bg-gray-900/30 border-b border-gray-100 dark:border-gray-700">
      <form method="GET" action="<?= url('/reports') ?>/<?= e($reportType) ?>" class="flex flex-wrap gap-3 items-end">
        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Date From</label>
          <input type="date" name="date_from" value="<?= e($filters['date_from'] ?? '') ?>" class="px-2.5 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-xs bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Date To</label>
          <input type="date" name="date_to" value="<?= e($filters['date_to'] ?? '') ?>" class="px-2.5 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-xs bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>
        <?php if ($reportType === 'enrollment' || $reportType === 'results' || $reportType === 'attendance'): ?>
        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Class</label>
          <input type="text" name="class_id" value="<?= e($filters['class_id'] ?? '') ?>" placeholder="Class ID" class="px-2.5 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-xs bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 w-32">
        </div>
        <?php endif; ?>
        <?php if ($reportType === 'results'): ?>
        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Subject</label>
          <input type="text" name="subject_id" value="<?= e($filters['subject_id'] ?? '') ?>" placeholder="Subject ID" class="px-2.5 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-xs bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 w-32">
        </div>
        <?php endif; ?>
        <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-xs font-medium shadow-sm">
          Apply Filters
        </button>
        <a href="<?= url('/reports') ?>/<?= e($reportType) ?>" class="px-3 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Reset</a>
      </form>
    </div>

    <!-- Summary Cards (for fee report) -->
    <?php if ($reportType === 'fees' && $summary): ?>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4">
      <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-3">
        <p class="text-xs text-gray-500 dark:text-gray-400">Total Collected</p>
        <p class="text-lg font-semibold text-emerald-700 dark:text-emerald-400"><?= formatMoney($summary['total_collected'] ?? 0) ?></p>
      </div>
      <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-3">
        <p class="text-xs text-gray-500 dark:text-gray-400">Total Pending</p>
        <p class="text-lg font-semibold text-yellow-700 dark:text-yellow-400"><?= formatMoney($summary['total_pending'] ?? 0) ?></p>
      </div>
      <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3">
        <p class="text-xs text-gray-500 dark:text-gray-400">Total Records</p>
        <p class="text-lg font-semibold text-gray-700 dark:text-gray-300"><?= number_format($summary['total_records'] ?? 0) ?></p>
      </div>
    </div>
    <?php endif; ?>

    <!-- Results Table -->
    <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-900/50 sticky top-0">
          <?php if ($reportType === 'enrollment'): ?>
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Email</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Class</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Enrolled</th>
          </tr>
          <?php elseif ($reportType === 'fees'): ?>
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Method</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Date</th>
          </tr>
          <?php elseif ($reportType === 'attendance'): ?>
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Class</th>
          </tr>
          <?php elseif ($reportType === 'results'): ?>
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Subject</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Marks</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Grade</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
          </tr>
          <?php endif; ?>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php $idx = 0; ?>
          <?php foreach($reportData as $row): $idx++; ?>
          <?php if ($reportType === 'enrollment'): ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400"><?= $idx ?></td>
            <td class="px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white"><?= e($row['name'] ?? '—') ?></td>
            <td class="px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hidden md:table-cell"><?= e($row['email'] ?? '—') ?></td>
            <td class="px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300"><?= e($row['class_id'] ?? '—') ?></td>
            <td class="px-4 py-2.5">
              <?php $s = $row['status'] ?? 'active'; ?>
              <span class="inline-flex items-center gap-1 rounded-full <?= $s === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?> px-2 py-0.5 text-xs font-medium"><?= ucfirst(e($s)) ?></span>
            </td>
            <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell"><?= formatDate($row['created_at'] ?? null) ?></td>
          </tr>
          <?php elseif ($reportType === 'fees'): ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400"><?= $idx ?></td>
            <td class="px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white"><?= e($row['student_id'] ?? '—') ?></td>
            <td class="px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white"><?= formatMoney((float) ($row['amount'] ?? 0)) ?></td>
            <td class="px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hidden md:table-cell"><?= e($row['payment_method'] ?? '—') ?></td>
            <td class="px-4 py-2.5">
              <?php $s = $row['status'] ?? 'pending'; ?>
              <?php $feeClass = $s === 'completed' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : ($s === 'failed' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'); ?>
              <span class="inline-flex items-center gap-1 rounded-full <?= $feeClass ?> px-2 py-0.5 text-xs font-medium"><?= ucfirst(e($s)) ?></span>
            </td>
            <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400 hidden lg:table-cell"><?= formatDate($row['created_at'] ?? null) ?></td>
          </tr>
          <?php elseif ($reportType === 'attendance'): ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400"><?= $idx ?></td>
            <td class="px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white"><?= e($row['student_id'] ?? '—') ?></td>
            <td class="px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300"><?= e($row['date'] ?? '—') ?></td>
            <td class="px-4 py-2.5">
              <?php $s = $row['status'] ?? 'present'; ?>
              <?php $attClass = $s === 'present' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : ($s === 'absent' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'); ?>
              <span class="inline-flex items-center gap-1 rounded-full <?= $attClass ?> px-2 py-0.5 text-xs font-medium"><?= ucfirst(e($s)) ?></span>
            </td>
            <td class="px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hidden md:table-cell"><?= e($row['class_id'] ?? '—') ?></td>
          </tr>
          <?php elseif ($reportType === 'results'): ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400"><?= $idx ?></td>
            <td class="px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white"><?= e($row['student_id'] ?? '—') ?></td>
            <td class="px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hidden md:table-cell"><?= e($row['subject_id'] ?? '—') ?></td>
            <td class="px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white"><?= (int) ($row['marks_obtained'] ?? 0) ?></td>
            <td class="px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hidden md:table-cell"><?= e($row['grade'] ?? '—') ?></td>
            <td class="px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400"><?= (int) ($row['total_marks'] ?? 0) ?></td>
          </tr>
          <?php endif; ?>
          <?php endforeach; ?>

          <?php if(empty($reportData)): ?>
          <tr>
            <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-400">No records found for the selected filters.</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>
</div>
