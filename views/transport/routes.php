<?php
$routes = $routes ?? [];
$pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Transport Routes</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage school transport routes and schedules</p>
        </div>
        <a href="/transport/routes/create" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Route
        </a>
    </div>
</div>

<!-- Routes Table -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">Start Point</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">End Point</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden md:table-cell">Distance</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden md:table-cell">Fare</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php if (!empty($routes)): ?>
                    <?php foreach ($routes as $route): ?>
                        <?php
                            $routeStatus = strtolower($route['status'] ?? 'active');
                            $statusColor = $routeStatus === 'active'
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300';
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
                                        <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($route['name'] ?? '') ?></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($route['description'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden sm:table-cell">
                                <div class="flex items-center gap-1.5">
                                    <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                                    <?= htmlspecialchars($route['start_point'] ?? '—') ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden sm:table-cell">
                                <div class="flex items-center gap-1.5">
                                    <div class="h-2 w-2 rounded-full bg-red-500"></div>
                                    <?= htmlspecialchars($route['end_point'] ?? '—') ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden md:table-cell"><?= htmlspecialchars($route['distance'] ?? '—') ?> km</td>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white hidden md:table-cell"><?= htmlspecialchars($route['fare'] ?? '—') ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium capitalize <?= $statusColor ?>">
                                    <span class="h-1.5 w-1.5 rounded-full <?= $routeStatus === 'active' ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
                                    <?= htmlspecialchars($routeStatus) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="/transport/routes/<?= htmlspecialchars($route['id'] ?? '') ?>/edit" class="rounded-lg p-1.5 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/50 dark:hover:text-emerald-400 transition-colors" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button onclick="if(confirm('Are you sure you want to delete this route?')){document.getElementById('delete-route-<?= htmlspecialchars($route['id'] ?? '') ?>').submit();}" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors" title="Delete">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <form id="delete-route-<?= htmlspecialchars($route['id'] ?? '') ?>" method="POST" action="/transport/routes/<?= htmlspecialchars($route['id'] ?? '') ?>" class="hidden">
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No routes found</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Get started by adding your first transport route</p>
                            <a href="/transport/routes/create" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Add Route
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
            Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> routes
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
