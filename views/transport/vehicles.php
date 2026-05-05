<?php
$vehicles = $vehicles ?? [];
$pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Vehicles</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage school transport vehicles and drivers</p>
        </div>
        <a href="/transport/vehicles/create" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Vehicle
        </a>
    </div>
</div>

<!-- Vehicles Table -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Vehicle No</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Type</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">Capacity</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden md:table-cell">Driver</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden lg:table-cell">Route</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php if (!empty($vehicles)): ?>
                    <?php foreach ($vehicles as $v): ?>
                        <?php
                            $vType = strtolower($v['type'] ?? 'bus');
                            $vStatus = strtolower($v['status'] ?? 'active');
                            $typeColors = [
                                'bus' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                                'van' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300',
                                'minibus' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
                            ];
                            $vStatusColors = [
                                'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'maintenance' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'inactive' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                            ];
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                                        <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-mono font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($v['registration_number'] ?? '') ?></span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($v['model'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium capitalize <?= $typeColors[$vType] ?? $typeColors['bus'] ?>"><?= htmlspecialchars($vType) ?></span>
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white hidden sm:table-cell"><?= number_format((int)($v['capacity'] ?? 0)) ?></td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                <div class="text-gray-700 dark:text-gray-300"><?= htmlspecialchars($v['driver_name'] ?? 'N/A') ?></div>
                                <?php if (!empty($v['driver_phone'])): ?>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($v['driver_phone']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden lg:table-cell"><?= htmlspecialchars($v['route_name'] ?? 'Unassigned') ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium capitalize <?= $vStatusColors[$vStatus] ?? $vStatusColors['active'] ?>">
                                    <span class="h-1.5 w-1.5 rounded-full <?= $vStatus === 'active' ? 'bg-emerald-500' : ($vStatus === 'maintenance' ? 'bg-amber-500' : 'bg-gray-400') ?>"></span>
                                    <?= htmlspecialchars($vStatus) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="/transport/vehicles/<?= htmlspecialchars($v['id'] ?? '') ?>/edit" class="rounded-lg p-1.5 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/50 dark:hover:text-emerald-400 transition-colors" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button onclick="if(confirm('Are you sure you want to delete this vehicle?')){document.getElementById('delete-vehicle-<?= htmlspecialchars($v['id'] ?? '') ?>').submit();}" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors" title="Delete">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <form id="delete-vehicle-<?= htmlspecialchars($v['id'] ?? '') ?>" method="POST" action="/transport/vehicles/<?= htmlspecialchars($v['id'] ?? '') ?>" class="hidden">
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No vehicles found</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Get started by adding your first vehicle</p>
                            <a href="/transport/vehicles/create" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Add Vehicle
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
            Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> vehicles
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
