<?php
$fee = $fee ?? [];
$class = $fee['class'] ?? [];
$items = $fee['items'] ?? [];
$totalPaid = $fee['total_paid'] ?? 0;
$balance = ($fee['total_amount'] ?? 0) - $totalPaid;
?>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/fees') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Fee Structure Details</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e($class['name'] ?? 'N/A') ?> · <?= e($fee['term'] ?? '') ?> · <?= e($fee['academic_year'] ?? '') ?></p>
      </div>
    </div>
    <a href="<?= url('/fees/' . ($fee['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Edit
    </a>
  </div>

  <!-- Stats -->
  <div class="grid grid-cols-3 gap-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <p class="text-xs text-gray-500 dark:text-gray-400">Total Amount</p>
      <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">KES <?= number_format($fee['total_amount'] ?? 0) ?></p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <p class="text-xs text-gray-500 dark:text-gray-400">Total Paid</p>
      <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">KES <?= number_format($totalPaid) ?></p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <p class="text-xs text-gray-500 dark:text-gray-400">Balance</p>
      <p class="text-2xl font-bold <?= $balance > 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' ?> mt-1">KES <?= number_format($balance) ?></p>
    </div>
  </div>

  <!-- Info -->
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Fee Information</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Class</p><p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($class['name'] ?? 'N/A') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Term</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($fee['term'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Academic Year</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($fee['academic_year'] ?? '—') ?></p></div>
      <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Status</p>
        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium mt-1 <?= ($fee['status'] ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' ?>"><?= ucfirst($fee['status'] ?? 'active') ?></span>
      </div>
      <div class="sm:col-span-2 lg:col-span-3"><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Description</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($fee['description'] ?? '—') ?></p></div>
    </div>
  </div>

  <!-- Fee Items -->
  <?php if (!empty($items)): ?>
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Fee Items</h3>
    </div>
    <div class="divide-y divide-gray-100 dark:divide-gray-700">
      <?php foreach($items as $item): ?>
      <div class="flex items-center justify-between px-5 py-3">
        <span class="text-sm text-gray-900 dark:text-white"><?= e($item['name'] ?? '') ?></span>
        <span class="text-sm font-semibold text-gray-900 dark:text-white">KES <?= number_format($item['amount'] ?? 0) ?></span>
      </div>
      <?php endforeach; ?>
      <div class="flex items-center justify-between px-5 py-3 bg-gray-50 dark:bg-gray-900/30">
        <span class="text-sm font-bold text-gray-900 dark:text-white">Total</span>
        <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">KES <?= number_format($fee['total_amount'] ?? 0) ?></span>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
