<?php
$payments = isset($payments) ? $payments : [];
$students = isset($students) ? $students : [];
$feeStructures = isset($feeStructures) ? $feeStructures : [];
$stats = isset($stats) ? $stats : [];

$totalCollected = $stats['total_collected'] ?? 0;
$todayCollected = $stats['today_collected'] ?? 0;
$totalTransactions = $stats['total_transactions'] ?? 0;
$pendingCount = $stats['pending_count'] ?? 0;

$paymentMethodIcons = [
    'cash' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
    'mpesa' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
    'bank_transfer' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>',
    'cheque' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>',
];

$paymentMethodLabels = [
    'cash' => 'Cash',
    'mpesa' => 'M-Pesa',
    'bank_transfer' => 'Bank Transfer',
    'cheque' => 'Cheque',
];

$paymentMethodBadgeColors = [
    'cash' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
    'mpesa' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
    'bank_transfer' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
    'cheque' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300',
];

$statusBadgeColors = [
    'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
    'failed' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
    'refunded' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payments</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Track and manage student fee payments</p>
        </div>
        <button onclick="openPaymentModal()" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Record Payment
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
                <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white">KES <?= number_format($totalCollected) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Collected</p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white">KES <?= number_format($todayCollected) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Today</p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
                <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($totalTransactions) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Transactions</p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900">
                <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($pendingCount) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pending</p>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="relative flex-1 max-w-sm">
        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" id="paymentSearch" placeholder="Search by receipt #, student, or reference..." class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500">
    </div>
    <div class="flex items-center gap-2">
        <select id="filterMethod" class="rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
            <option value="">All Methods</option>
            <option value="cash">Cash</option>
            <option value="mpesa">M-Pesa</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cheque">Cheque</option>
        </select>
        <select id="filterStatus" class="rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
            <option value="">All Status</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="failed">Failed</option>
            <option value="refunded">Refunded</option>
        </select>
    </div>
</div>

<!-- Payments Table -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Receipt #</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Student</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Amount</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Method</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Transaction Ref</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800" id="paymentTableBody">
                <?php if (!empty($payments)): ?>
                    <?php foreach ($payments as $payment): ?>
                        <?php
                            $method = $payment['payment_method'] ?? 'cash';
                            $status = strtolower($payment['status'] ?? 'pending');
                            $student = $payment['student'] ?? [];
                            $studentName = ($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '');
                            $admNo = $student['admission_number'] ?? '';
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-mono text-sm font-medium text-emerald-600 dark:text-emerald-400"><?= htmlspecialchars($payment['receipt_number'] ?? 'N/A') ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($studentName) ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($admNo) ?></div>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">KES <?= number_format((float)($payment['amount'] ?? 0)) ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium <?= $paymentMethodBadgeColors[$method] ?? '' ?>">
                                    <?= $paymentMethodIcons[$method] ?? '' ?>
                                    <?= $paymentMethodLabels[$method] ?? ucfirst(str_replace('_', ' ', $method)) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($payment['transaction_ref'] ?? '—') ?></td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= formatDate($payment['payment_date'] ?? '') ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize <?= $statusBadgeColors[$status] ?? $statusBadgeColors['pending'] ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button onclick="viewPayment(<?= htmlspecialchars(json_encode($payment)) ?>)" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300 transition-colors" title="View">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button onclick="editPayment(<?= htmlspecialchars(json_encode($payment)) ?>)" class="rounded-lg p-1.5 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/50 dark:hover:text-emerald-400 transition-colors" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="deletePayment('<?= htmlspecialchars($payment['id'] ?? '') ?>')" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors" title="Delete">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No payments found</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Record your first payment to get started</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Record Payment Modal -->
<div id="paymentModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closePaymentModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl dark:bg-gray-900 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                <h3 id="paymentModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Record Payment</h3>
                <button onclick="closePaymentModal()" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="paymentForm" class="space-y-4 px-6 py-4" onsubmit="savePayment(event)">
                <input type="hidden" id="paymentId" value="">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Student *</label>
                    <select id="paymentStudent" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">Select Student</option>
                        <?php foreach ($students as $s): ?>
                            <option value="<?= htmlspecialchars($s['id']) ?>"><?= htmlspecialchars(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? '') . ' (' . ($s['admission_number'] ?? '') . ')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Fee Structure</label>
                    <select id="paymentFeeStructure" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">Select Fee Structure</option>
                        <?php foreach ($feeStructures as $fs): ?>
                            <option value="<?= htmlspecialchars($fs['id']) ?>"><?= htmlspecialchars(($fs['class']['name'] ?? '') . ' - ' . ($fs['term'] ?? '') . ' ' . ($fs['academic_year'] ?? '')) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Amount (KES) *</label>
                    <input type="number" id="paymentAmount" required min="1" step="100" placeholder="Enter amount" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method *</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="method-option flex items-center gap-2 rounded-lg border border-gray-200 p-3 cursor-pointer hover:border-emerald-300 dark:border-gray-700 dark:hover:border-emerald-700 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-950/50 transition-colors">
                            <input type="radio" name="paymentMethod" value="cash" class="accent-emerald-600" checked>
                            <div class="flex items-center gap-1.5">
                                <?php echo $paymentMethodIcons['cash']; ?>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Cash</span>
                            </div>
                        </label>
                        <label class="method-option flex items-center gap-2 rounded-lg border border-gray-200 p-3 cursor-pointer hover:border-emerald-300 dark:border-gray-700 dark:hover:border-emerald-700 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-950/50 transition-colors">
                            <input type="radio" name="paymentMethod" value="mpesa" class="accent-emerald-600">
                            <div class="flex items-center gap-1.5">
                                <?php echo $paymentMethodIcons['mpesa']; ?>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">M-Pesa</span>
                            </div>
                        </label>
                        <label class="method-option flex items-center gap-2 rounded-lg border border-gray-200 p-3 cursor-pointer hover:border-emerald-300 dark:border-gray-700 dark:hover:border-emerald-700 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-950/50 transition-colors">
                            <input type="radio" name="paymentMethod" value="bank_transfer" class="accent-emerald-600">
                            <div class="flex items-center gap-1.5">
                                <?php echo $paymentMethodIcons['bank_transfer']; ?>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Bank</span>
                            </div>
                        </label>
                        <label class="method-option flex items-center gap-2 rounded-lg border border-gray-200 p-3 cursor-pointer hover:border-emerald-300 dark:border-gray-700 dark:hover:border-emerald-700 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-950/50 transition-colors">
                            <input type="radio" name="paymentMethod" value="cheque" class="accent-emerald-600">
                            <div class="flex items-center gap-1.5">
                                <?php echo $paymentMethodIcons['cheque']; ?>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Cheque</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div id="transactionRefGroup">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction Reference</label>
                    <input type="text" id="paymentTransactionRef" placeholder="Enter transaction/reference number" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closePaymentModal()" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPaymentModal() {
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('paymentForm').reset();
        document.getElementById('paymentId').value = '';
        toggleTransactionRef();
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    function toggleTransactionRef() {
        const method = document.querySelector('input[name="paymentMethod"]:checked')?.value;
        const refGroup = document.getElementById('transactionRefGroup');
        if (method === 'cash') {
            refGroup.classList.add('hidden');
        } else {
            refGroup.classList.remove('hidden');
        }
    }

    document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
        radio.addEventListener('change', toggleTransactionRef);
    });

    function editPayment(payment) {
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('paymentModalTitle').textContent = 'Edit Payment';
        document.getElementById('paymentId').value = payment.id || '';
        document.getElementById('paymentAmount').value = payment.amount || '';
        const methodRadio = document.querySelector(`input[name="paymentMethod"][value="${payment.payment_method}"]`);
        if (methodRadio) methodRadio.checked = true;
        toggleTransactionRef();
        document.getElementById('paymentTransactionRef').value = payment.transaction_ref || '';
    }

    function savePayment(e) {
        e.preventDefault();
        const method = document.querySelector('input[name="paymentMethod"]:checked')?.value;
        const data = {
            id: document.getElementById('paymentId').value,
            student_id: document.getElementById('paymentStudent').value,
            fee_structure_id: document.getElementById('paymentFeeStructure').value,
            amount: parseFloat(document.getElementById('paymentAmount').value),
            payment_method: method,
            transaction_ref: document.getElementById('paymentTransactionRef').value,
        };
        console.log('Saving payment:', data);
        closePaymentModal();
    }

    function viewPayment(payment) {
        alert(`Receipt: ${payment.receipt_number}\nStudent: ${payment.student?.first_name} ${payment.student?.last_name}\nAmount: KES ${payment.amount.toLocaleString()}\nMethod: ${payment.payment_method}`);
    }

    function deletePayment(id) {
        if (confirm('Are you sure you want to delete this payment record?')) {
            console.log('Deleting payment:', id);
        }
    }

    // Search
    document.getElementById('paymentSearch')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('#paymentTableBody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
