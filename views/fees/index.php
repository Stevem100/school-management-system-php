<?php
$feeStructures = isset($feeStructures) ? $feeStructures : [];
$stats = isset($stats) ? $stats : [];
$classes = isset($classes) ? $classes : [];

$totalCollected = $stats['total_collected'] ?? 0;
$outstanding = $stats['outstanding'] ?? 0;
$totalStructures = $stats['total_structures'] ?? 0;
$pendingPayments = $stats['pending_payments'] ?? 0;
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Fee Management</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage fee structures, amounts, and billing</p>
        </div>
        <button onclick="openFeeModal()" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Fee Structure
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
                <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white">KES <?= number_format($outstanding) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Outstanding Balance</p>
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
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($totalStructures) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Fee Structures</p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900">
                <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($pendingPayments) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pending Payments</p>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="relative flex-1 max-w-sm">
        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" id="feeSearch" placeholder="Search fee structures..." class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500">
    </div>
    <div class="flex items-center gap-2">
        <select id="filterTerm" class="rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
            <option value="">All Terms</option>
            <option value="Term 1">Term 1</option>
            <option value="Term 2">Term 2</option>
            <option value="Term 3">Term 3</option>
        </select>
        <select id="filterYear" class="rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
            <option value="">All Years</option>
            <option value="2024-2025">2024-2025</option>
            <option value="2023-2024">2023-2024</option>
        </select>
    </div>
</div>

<!-- Fee Structures Table -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Class</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Term</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Academic Year</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Total Amount</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800" id="feeTableBody">
                <?php if (!empty($feeStructures)): ?>
                    <?php foreach ($feeStructures as $fee): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($fee['class_name'] ?? 'N/A') ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]"><?= htmlspecialchars($fee['description'] ?? '') ?></div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <?= htmlspecialchars($fee['term'] ?? '') ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($fee['academic_year'] ?? '') ?></td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">KES <?= number_format((float)($fee['total_amount'] ?? 0)) ?></td>
                            <td class="px-4 py-3">
                                <?php
                                    $status = strtolower($fee['status'] ?? 'active');
                                    $statusColors = [
                                        'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
                                        'draft'  => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                        'archived' => 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300',
                                    ];
                                    $statusClass = $statusColors[$status] ?? $statusColors['active'];
                                ?>
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button onclick="viewFeeItems('<?= htmlspecialchars($fee['id'] ?? '') ?>')" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300 transition-colors" title="View Items">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                        </svg>
                                    </button>
                                    <button onclick="editFeeStructure(<?= htmlspecialchars(json_encode($fee)) ?>)" class="rounded-lg p-1.5 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/50 dark:hover:text-emerald-400 transition-colors" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="deleteFeeStructure('<?= htmlspecialchars($fee['id'] ?? '') ?>')" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors" title="Delete">
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
                        <td colspan="6" class="px-4 py-12 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No fee structures found</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Create your first fee structure to get started</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (!empty($feeStructures) && count($feeStructures) > 10): ?>
        <div class="flex items-center justify-between border-t border-gray-100 px-4 py-3 dark:border-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">Showing <?= count($feeStructures) ?> fee structures</p>
            <div class="flex items-center gap-1">
                <button class="rounded-lg px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-400">Previous</button>
                <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white">1</button>
                <button class="rounded-lg px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-400">2</button>
                <button class="rounded-lg px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 dark:text-gray-400">Next</button>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Add/Edit Fee Structure Modal -->
<div id="feeModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeFeeModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl dark:bg-gray-900 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                <h3 id="feeModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Fee Structure</h3>
                <button onclick="closeFeeModal()" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="feeForm" class="space-y-4 px-6 py-4" onsubmit="saveFeeStructure(event)">
                <input type="hidden" id="feeId" value="">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Class *</label>
                        <select id="feeClass" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Select Class</option>
                            <?php foreach ($classes as $cls): ?>
                                <option value="<?= htmlspecialchars($cls['id']) ?>"><?= htmlspecialchars($cls['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Term *</label>
                        <select id="feeTerm" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Select Term</option>
                            <option value="Term 1">Term 1</option>
                            <option value="Term 2">Term 2</option>
                            <option value="Term 3">Term 3</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Academic Year *</label>
                        <select id="feeYear" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Select Year</option>
                            <option value="2024-2025">2024-2025</option>
                            <option value="2023-2024">2023-2024</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Total Amount (KES) *</label>
                        <input type="number" id="feeAmount" required min="0" step="100" placeholder="e.g. 45000" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="feeStatus" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea id="feeDescription" rows="2" placeholder="Brief description of the fee structure..." class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>
                    </div>
                </div>

                <!-- Fee Line Items -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Fee Items (Line Items)</label>
                        <button type="button" onclick="addFeeItem()" class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Item
                        </button>
                    </div>
                    <div id="feeItemsContainer" class="space-y-2">
                        <div class="fee-item flex items-center gap-2">
                            <input type="text" placeholder="Item name" class="fee-item-name flex-1 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <input type="number" placeholder="Amount" class="fee-item-amount w-28 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <button type="button" onclick="removeFeeItem(this)" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeFeeModal()" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">Cancel</button>
                    <button type="submit" id="feeSubmitBtn" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">Save Fee Structure</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Fee Items View Modal -->
<div id="feeItemsModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeFeeItemsModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-xl dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Fee Items</h3>
                <button onclick="closeFeeItemsModal()" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="feeItemsList" class="px-6 py-4 space-y-3 max-h-96 overflow-y-auto">
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Tuition Fee</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">KES 30,000</span>
                </div>
                <div class="flex items-center justify-between py-2 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Lab Fee</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">KES 5,000</span>
                </div>
                <div class="flex items-center justify-between py-2 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Library Fee</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">KES 2,000</span>
                </div>
                <div class="flex items-center justify-between py-2 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Activity Fee</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">KES 3,000</span>
                </div>
                <div class="flex items-center justify-between py-2 border-t-2 border-gray-200 dark:border-gray-700 pt-3 mt-2">
                    <span class="text-sm font-bold text-gray-900 dark:text-white">Total</span>
                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">KES 40,000</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fee Modal Functions
    function openFeeModal() {
        document.getElementById('feeModal').classList.remove('hidden');
        document.getElementById('feeModalTitle').textContent = 'Add Fee Structure';
        document.getElementById('feeForm').reset();
        document.getElementById('feeId').value = '';
        // Reset to one empty item row
        document.getElementById('feeItemsContainer').innerHTML = createFeeItemRow();
    }

    function closeFeeModal() {
        document.getElementById('feeModal').classList.add('hidden');
    }

    function createFeeItemRow() {
        return `<div class="fee-item flex items-center gap-2">
            <input type="text" placeholder="Item name" class="fee-item-name flex-1 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            <input type="number" placeholder="Amount" class="fee-item-amount w-28 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            <button type="button" onclick="removeFeeItem(this)" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>`;
    }

    function addFeeItem() {
        const container = document.getElementById('feeItemsContainer');
        container.insertAdjacentHTML('beforeend', createFeeItemRow());
    }

    function removeFeeItem(btn) {
        const container = document.getElementById('feeItemsContainer');
        if (container.children.length > 1) {
            btn.closest('.fee-item').remove();
        }
    }

    function editFeeStructure(fee) {
        document.getElementById('feeModal').classList.remove('hidden');
        document.getElementById('feeModalTitle').textContent = 'Edit Fee Structure';
        document.getElementById('feeId').value = fee.id || '';
        document.getElementById('feeClass').value = fee.class_id || '';
        document.getElementById('feeTerm').value = fee.term || '';
        document.getElementById('feeYear').value = fee.academic_year || '';
        document.getElementById('feeAmount').value = fee.total_amount || '';
        document.getElementById('feeStatus').value = fee.status || 'active';
        document.getElementById('feeDescription').value = fee.description || '';
        document.getElementById('feeItemsContainer').innerHTML = createFeeItemRow();
    }

    function saveFeeStructure(e) {
        e.preventDefault();
        // Collect items
        const items = [];
        document.querySelectorAll('.fee-item').forEach(row => {
            const name = row.querySelector('.fee-item-name').value;
            const amount = row.querySelector('.fee-item-amount').value;
            if (name && amount) items.push({ name, amount: parseFloat(amount) });
        });

        const data = {
            id: document.getElementById('feeId').value,
            class_id: document.getElementById('feeClass').value,
            term: document.getElementById('feeTerm').value,
            academic_year: document.getElementById('feeYear').value,
            total_amount: parseFloat(document.getElementById('feeAmount').value),
            status: document.getElementById('feeStatus').value,
            description: document.getElementById('feeDescription').value,
            items: items,
        };

        console.log('Saving fee structure:', data);
        closeFeeModal();
    }

    function deleteFeeStructure(id) {
        if (confirm('Are you sure you want to delete this fee structure? This action cannot be undone.')) {
            console.log('Deleting fee structure:', id);
        }
    }

    function viewFeeItems(id) {
        document.getElementById('feeItemsModal').classList.remove('hidden');
    }

    function closeFeeItemsModal() {
        document.getElementById('feeItemsModal').classList.add('hidden');
    }

    // Search filter
    document.getElementById('feeSearch')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('#feeTableBody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
