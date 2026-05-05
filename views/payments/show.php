<?php
$payment = $payment ?? [];
$student = $payment['student'] ?? [];
$status = strtolower($payment['status'] ?? 'completed');
$statusColors = ['completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300', 'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300', 'failed' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300', 'refunded' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'];
$methodLabels = ['cash' => 'Cash', 'mpesa' => 'M-Pesa', 'bank_transfer' => 'Bank Transfer', 'cheque' => 'Cheque'];
$method = $payment['payment_method'] ?? 'cash';
?>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/payments') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Receipt</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e($payment['receipt_number'] ?? 'N/A') ?></p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium capitalize <?= $statusColors[$status] ?? $statusColors['pending'] ?>"><?= ucfirst($status) ?></span>
      <a href="<?= url('/payments/' . ($payment['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
      </a>
    </div>
  </div>

  <!-- Receipt Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 text-center">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white">Payment Receipt</h2>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Receipt #: <span class="font-mono font-semibold text-emerald-600 dark:text-emerald-400"><?= e($payment['receipt_number'] ?? 'N/A') ?></span></p>
    </div>
    <div class="p-6">
      <div class="text-center mb-6 pb-6 border-b border-dashed border-gray-200 dark:border-gray-700">
        <p class="text-4xl font-bold text-emerald-600 dark:text-emerald-400">KES <?= number_format($payment['amount'] ?? 0) ?></p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e($methodLabels[$method] ?? ucfirst(str_replace('_', ' ', $method))) ?></p>
      </div>
      <div class="grid grid-cols-2 gap-y-4 gap-x-8">
        <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Student</p><p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) ?></p></div>
        <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Admission No</p><p class="text-sm font-mono text-gray-700 dark:text-gray-300 mt-1"><?= e($student['admission_no'] ?? '—') ?></p></div>
        <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Payment Date</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($payment['payment_date'] ?? '—') ?></p></div>
        <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Method</p><p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($methodLabels[$method] ?? ucfirst(str_replace('_', ' ', $method))) ?></p></div>
        <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Transaction Ref</p><p class="text-sm font-mono text-gray-700 dark:text-gray-300 mt-1"><?= e($payment['transaction_ref'] ?? '—') ?></p></div>
        <div><p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Status</p>
          <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium mt-1 capitalize <?= $statusColors[$status] ?? $statusColors['pending'] ?>"><?= ucfirst($status) ?></span>
        </div>
      </div>
    </div>
  </div>
</div>
