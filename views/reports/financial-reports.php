<?php $pageTitle = $pageTitle ?? 'Financial Reports'; ?>
<?php
  $transactions = $transactions ?? [];
  $summary = $summary ?? [];
  $filters = $filters ?? [];
  $methods = $methods ?? ['cash' => 'Cash', 'bank_transfer' => 'Bank Transfer', 'mpesa' => 'M-Pesa', 'cheque' => 'Cheque', 'card' => 'Card'];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Financial Reports</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Track fee payments and financial data</p>
    </div>
    <a href="<?= url('/reports/export') ?>/financial?<?= http_build_query($filters) ?>" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Export
    </a>
  </div>

  <!-- Summary Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Total Revenue</p>
          <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400"><?= formatMoney((float) ($summary['total_revenue'] ?? ($summary['total_collected'] ?? 0))) ?></p>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900">
          <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Pending</p>
          <p class="text-xl font-bold text-yellow-600 dark:text-yellow-400"><?= formatMoney((float) ($summary['pending'] ?? ($summary['total_pending'] ?? 0))) ?></p>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
          <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Collected</p>
          <p class="text-xl font-bold text-blue-600 dark:text-blue-400"><?= formatMoney((float) ($summary['collected'] ?? ($summary['total_collected'] ?? 0))) ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Filters -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
    <form method="GET" action="<?= url('/reports/financial') ?>" class="flex flex-col sm:flex-row gap-3 items-end flex-wrap">
      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Date From</label>
        <input type="date" name="date_from" value="<?= e($filters['date_from'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      </div>
      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Date To</label>
        <input type="date" name="date_to" value="<?= e($filters['date_to'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      </div>
      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Payment Method</label>
        <select name="payment_method" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          <option value="">All Methods</option>
          <?php foreach ($methods as $key => $label): ?>
            <option value="<?= e($key) ?>" <?= ($filters['payment_method'] ?? '') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
        <select name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          <option value="">All Status</option>
          <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
          <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
          <option value="failed" <?= ($filters['status'] ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
        </select>
      </div>
      <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium shadow-sm">Apply</button>
        <a href="<?= url('/reports/financial') ?>" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Reset</a>
      </div>
    </form>
  </div>

  <!-- Transactions Table -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
      <p class="text-xs text-gray-500 dark:text-gray-400"><?= count($transactions) ?> records found</p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Receipt No</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Method</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Date</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php if (!empty($transactions)): ?>
            <?php foreach ($transactions as $t): ?>
            <?php
              $status = $t['status'] ?? 'pending';
              if ($status === 'completed') {
                $statusClass = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400';
              } elseif ($status === 'failed') {
                $statusClass = 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400';
              } else {
                $statusClass = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400';
              }
              $method = $t['payment_method'] ?? 'cash';
              $methodLabel = $methods[$method] ?? ucfirst(e($method));
            ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
              <td class="px-4 py-3 font-mono text-xs text-gray-600 dark:text-gray-300"><?= e($t['receipt_no'] ?? $t['transaction_id'] ?? $t['id'] ?? '—') ?></td>
              <td class="px-4 py-3 font-medium text-gray-900 dark:text-white"><?= e($t['student_name'] ?? $t['name'] ?? 'Student #' . ($t['student_id'] ?? '')) ?></td>
              <td class="px-4 py-3 font-medium text-gray-900 dark:text-white"><?= formatMoney((float) ($t['amount'] ?? 0)) ?></td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden md:table-cell"><?= e($methodLabel) ?></td>
              <td class="px-4 py-3 text-gray-500 dark:text-gray-400 hidden sm:table-cell"><?= !empty($t['date']) ? formatDate($t['date']) : (!empty($t['created_at']) ? formatDate($t['created_at']) : '—') ?></td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center rounded-full <?= $statusClass ?> px-2.5 py-1 text-xs font-medium"><?= ucfirst(e($status)) ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="px-4 py-16 text-center">
                <svg class="mx-auto w-16 h-16 mb-4 text-gray-300 dark:text-gray-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                </svg>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No transactions found</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Adjust the filters to see results</p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
