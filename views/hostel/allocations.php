<?php
$allocations = $allocations ?? [];
$pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hostel Allocations</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage student room assignments and bed allocations</p>
        </div>
        <a href="/hostel/allocations/create" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Allocate Room
        </a>
    </div>
</div>

<!-- Allocations Table -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Student</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">Room</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">Bed No</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden md:table-cell">Check-in</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden lg:table-cell">Check-out</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php if (!empty($allocations)): ?>
                    <?php foreach ($allocations as $alloc): ?>
                        <?php
                            $allocStatus = strtolower($alloc['status'] ?? 'active');
                            $statusMap = [
                                'active' => ['bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'bg-emerald-500', 'Active'],
                                'checked_out' => ['bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300', 'bg-gray-400', 'Checked Out'],
                                'reserved' => ['bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'bg-amber-500', 'Reserved'],
                            ];
                            $st = $statusMap[$allocStatus] ?? $statusMap['active'];
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-violet-100 dark:bg-violet-900 text-violet-600 dark:text-violet-400 text-xs font-bold">
                                        <?= strtoupper(mb_substr($alloc['student_first_name'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars(($alloc['student_first_name'] ?? '') . ' ' . ($alloc['student_last_name'] ?? '')) ?></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($alloc['admission_number'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($alloc['room_name'] ?? '') ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Floor <?= htmlspecialchars($alloc['floor'] ?? '—') ?></div>
                            </td>
                            <td class="px-4 py-3 text-center hidden sm:table-cell">
                                <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-gray-100 dark:bg-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-300"><?= htmlspecialchars($alloc['bed_no'] ?? '—') ?></span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden md:table-cell"><?= htmlspecialchars($alloc['check_in_date'] ?? '') ?></td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden lg:table-cell"><?= htmlspecialchars($alloc['check_out_date'] ?? '—') ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium <?= $st[0] ?>">
                                    <span class="h-1.5 w-1.5 rounded-full <?= $st[1] ?>"></span>
                                    <?= $st[2] ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <?php if ($allocStatus === 'active'): ?>
                                    <button onclick="if(confirm('Check out this student?')){document.getElementById('checkout-<?= htmlspecialchars($alloc['id'] ?? '') ?>').submit();}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-amber-600 hover:bg-amber-50 dark:text-amber-400 dark:hover:bg-amber-900/50 transition-colors">
                                        Check Out
                                    </button>
                                    <?php endif; ?>
                                    <button onclick="if(confirm('Are you sure you want to remove this allocation?')){document.getElementById('delete-alloc-<?= htmlspecialchars($alloc['id'] ?? '') ?>').submit();}" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors" title="Remove">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <form id="delete-alloc-<?= htmlspecialchars($alloc['id'] ?? '') ?>" method="POST" action="/hostel/allocations/<?= htmlspecialchars($alloc['id'] ?? '') ?>" class="hidden">
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                    <form id="checkout-<?= htmlspecialchars($alloc['id'] ?? '') ?>" method="POST" action="/hostel/allocations/<?= htmlspecialchars($alloc['id'] ?? '') ?>/checkout" class="hidden">
                                        <input type="hidden" name="_method" value="PUT">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No allocations found</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Allocate students to hostel rooms</p>
                            <a href="/hostel/allocations/create" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Allocate Room
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-700">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> allocations
        </p>
        <div class="flex gap-1">
            <?php if ($pagination['page'] > 1): ?>
            <a href="?page=<?= $pagination['page'] - 1 ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <?php endif; ?>
            <?php
                $start = max(1, $pagination['page'] - 2);
                $end = min($pagination['totalPages'], $pagination['page'] + 2);
            ?>
            <?php for ($i = $start; $i <= $end; $i++): ?>
            <a href="?page=<?= $i ?>" class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors <?= $i == $pagination['page'] ? 'bg-emerald-600 text-white shadow-sm' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($pagination['page'] < $pagination['totalPages']): ?>
            <a href="?page=<?= $pagination['page'] + 1 ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
