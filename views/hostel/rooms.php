<?php
$rooms = $rooms ?? [];
$pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hostel Rooms</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage hostel rooms, capacity, and availability</p>
        </div>
        <a href="/hostel/rooms/create" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Room
        </a>
    </div>
</div>

<!-- Rooms Table -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">Floor</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">Type</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400">Capacity</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400 hidden md:table-cell">Occupied</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden md:table-cell">Fee/Month</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $room): ?>
                        <?php
                            $capacity = (int)($room['capacity'] ?? 1);
                            $occupied = (int)($room['occupied'] ?? 0);
                            $percent = $capacity > 0 ? round(($occupied / $capacity) * 100) : 0;
                            $roomStatus = strtolower($room['status'] ?? 'available');
                            $statusMap = [
                                'available' => ['bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'bg-emerald-500', 'Available'],
                                'occupied' => ['bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'bg-amber-500', 'Occupied'],
                                'full' => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'bg-red-500', 'Full'],
                                'maintenance' => ['bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300', 'bg-gray-400', 'Maintenance'],
                            ];
                            $st = $statusMap[$roomStatus] ?? $statusMap['available'];
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
                                        <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($room['name'] ?? '') ?></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($room['room_number'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden sm:table-cell">
                                <span class="inline-flex items-center rounded bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-700 dark:text-gray-300">Floor <?= htmlspecialchars($room['floor'] ?? '—') ?></span>
                            </td>
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <span class="inline-flex rounded-full bg-gray-100 dark:bg-gray-700 px-2.5 py-1 text-xs font-medium text-gray-700 dark:text-gray-300 capitalize"><?= htmlspecialchars($room['room_type'] ?? 'shared') ?></span>
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white"><?= $capacity ?></td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="h-2 w-16 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                        <div class="h-full rounded-full <?= $percent >= 100 ? 'bg-red-500' : ($percent >= 80 ? 'bg-amber-500' : 'bg-emerald-500') ?>" style="width: <?= min($percent, 100) ?>%"></div>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300"><?= $occupied ?>/<?= $capacity ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white hidden md:table-cell">
                                <?= htmlspecialchars($room['fee_per_month'] ?? '0') ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium <?= $st[0] ?>">
                                    <span class="h-1.5 w-1.5 rounded-full <?= $st[1] ?>"></span>
                                    <?= $st[2] ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="/hostel/rooms/<?= htmlspecialchars($room['id'] ?? '') ?>/edit" class="rounded-lg p-1.5 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/50 dark:hover:text-emerald-400 transition-colors" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button onclick="if(confirm('Are you sure you want to delete this room?')){document.getElementById('delete-room-<?= htmlspecialchars($room['id'] ?? '') ?>').submit();}" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors" title="Delete">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <form id="delete-room-<?= htmlspecialchars($room['id'] ?? '') ?>" method="POST" action="/hostel/rooms/<?= htmlspecialchars($room['id'] ?? '') ?>" class="hidden">
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No rooms found</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Get started by adding your first hostel room</p>
                            <a href="/hostel/rooms/create" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Add Room
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
            Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> rooms
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
