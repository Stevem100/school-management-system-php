<?php
$payment = $payment ?? [];
$students = $students ?? [];
$feeStructures = $feeStructures ?? [];
$title = 'Record Payment';
$formAction = url('/payments');
$methods = ['cash'=>'Cash','mpesa'=>'M-Pesa','bank_transfer'=>'Bank Transfer','cheque'=>'Cheque'];
?>

<div class="max-w-2xl mx-auto space-y-6">
  <div class="flex items-center gap-4">
    <a href="<?= url('/payments') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Record a new fee payment</p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>
      <div class="p-6 space-y-5">
        <div>
          <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student <span class="text-red-500">*</span></label>
          <select id="student_id" name="student_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="">Select student</option>
            <?php foreach($students as $s): ?>
            <option value="<?= e($s['id']) ?>"><?= e(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? '') . ' (' . ($s['admission_no'] ?? '') . ')') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="fee_structure_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fee Structure</label>
          <select id="fee_structure_id" name="fee_structure_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="">Select fee structure</option>
            <?php foreach($feeStructures as $fs): ?>
            <option value="<?= e($fs['id']) ?>"><?= e(($fs['class_name'] ?? '') . ' - ' . ($fs['term'] ?? '') . ' ' . ($fs['academic_year'] ?? '')) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount (KES) <span class="text-red-500">*</span></label>
            <input type="number" id="amount" name="amount" required min="1" step="100" value="<?= e($payment['amount'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Enter amount">
          </div>
          <div>
            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method <span class="text-red-500">*</span></label>
            <select id="payment_method" name="payment_method" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($methods as $k => $v): ?>
              <option value="<?= e($k) ?>" <?= ($payment['payment_method'] ?? 'cash') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div>
          <label for="transaction_ref" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Transaction Reference</label>
          <input type="text" id="transaction_ref" name="transaction_ref" value="<?= e($payment['transaction_ref'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Transaction/reference number">
        </div>
        <div>
          <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Date</label>
          <input type="date" id="payment_date" name="payment_date" value="<?= e($payment['payment_date'] ?? date('Y-m-d')) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        </div>
      </div>
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/payments') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Record Payment</button>
      </div>
    </form>
  </div>
</div>
