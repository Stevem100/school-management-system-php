<?php
$routes = isset($routes) ? $routes : [];
$vehicles = isset($vehicles) ? $vehicles : [];
$assignments = isset($assignments) ? $assignments : [];
$students = isset($students) ? $students : [];
$stats = isset($stats) ? $stats : [];

$totalRoutes = $stats['total_routes'] ?? 0;
$totalVehicles = $stats['total_vehicles'] ?? 0;
$assignedStudents = $stats['assigned_students'] ?? 0;
$activeRoutes = $stats['active_routes'] ?? 0;
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Transport</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage routes, vehicles, and student transport assignments</p>
        </div>
        <div class="mt-3 sm:mt-0 flex items-center gap-2">
            <button onclick="openRouteModal()" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Route
            </button>
            <button onclick="openVehicleModal()" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Vehicle
            </button>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($totalRoutes) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Routes</p>
        </div>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
            <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($totalVehicles) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Vehicles</p>
        </div>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
            <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($assignedStudents) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Students Assigned</p>
        </div>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($activeRoutes) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Active Routes</p>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="mb-4 border-b border-gray-200 dark:border-gray-800">
    <nav class="-mb-px flex gap-6" role="tablist">
        <button onclick="switchTransportTab('routes')" id="tab-routes" class="transport-tab-btn border-b-2 border-emerald-500 px-1 pb-3 text-sm font-medium text-emerald-600 dark:text-emerald-400" role="tab">
            <span class="flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                Routes
            </span>
        </button>
        <button onclick="switchTransportTab('vehicles')" id="tab-vehicles" class="transport-tab-btn border-b-2 border-transparent px-1 pb-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400" role="tab">
            <span class="flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                Vehicles
            </span>
        </button>
        <button onclick="switchTransportTab('assignments')" id="tab-assignments" class="transport-tab-btn border-b-2 border-transparent px-1 pb-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400" role="tab">
            <span class="flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Assignments
            </span>
        </button>
    </nav>
</div>

<!-- Routes Tab -->
<div id="panel-routes" class="transport-tab-panel">
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Route Name</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Stops</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Driver</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Vehicle</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <?php if (!empty($routes)): ?>
                        <?php foreach ($routes as $route): ?>
                            <?php
                                $routeStops = is_array($route['stops'] ?? null) ? $route['stops'] : json_decode($route['stops'] ?? '[]', true);
                                $stopCount = count($routeStops);
                                $routeStatus = strtolower($route['status'] ?? 'active');
                                $routeStatusColor = $routeStatus === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
                            ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($route['name'] ?? '') ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($route['description'] ?? '') ?></div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        <?= number_format($stopCount) ?> stops
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($route['driver_name'] ?? 'Unassigned') ?></td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($route['vehicle_reg'] ?? 'Unassigned') ?></td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize <?= $routeStatusColor ?>"><?= htmlspecialchars($routeStatus) ?></span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button onclick="editRoute(<?= htmlspecialchars(json_encode($route)) ?>)" class="rounded-lg p-1.5 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/50 dark:hover:text-emerald-400 transition-colors" title="Edit">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button onclick="deleteRoute('<?= htmlspecialchars($route['id'] ?? '') ?>')" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors" title="Delete">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400">No routes found. Add your first route.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Vehicles Tab -->
<div id="panel-vehicles" class="transport-tab-panel hidden">
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Registration</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Type</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400">Capacity</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Driver</th>
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
                                $typeColors = ['bus' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300', 'van' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300'];
                                $vStatusColors = ['active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300', 'maintenance' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300', 'inactive' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'];
                            ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-4 py-3 font-mono font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($v['registration_number'] ?? '') ?></td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium capitalize <?= $typeColors[$vType] ?? '' ?>">
                                        <?php if ($vType === 'bus'): ?>
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2M5 14h14M5 14v3a1 1 0 001 1h1m12-4v3a1 1 0 01-1 1h-1m-6-4v6m0 0H9m6 0h-2m6-14l-1.5 6H8L6.5 4"/></svg>
                                        <?php else: ?>
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"/></svg>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($vType) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white"><?= number_format((int)($v['capacity'] ?? 0)) ?></td>
                                <td class="px-4 py-3">
                                    <div class="text-gray-700 dark:text-gray-300"><?= htmlspecialchars($v['driver_name'] ?? 'N/A') ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($v['driver_phone'] ?? '') ?></div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize <?= $vStatusColors[$vStatus] ?? '' ?>"><?= htmlspecialchars($vStatus) ?></span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button onclick="editVehicle(<?= htmlspecialchars(json_encode($v)) ?>)" class="rounded-lg p-1.5 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/50 dark:hover:text-emerald-400 transition-colors" title="Edit">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button onclick="deleteVehicle('<?= htmlspecialchars($v['id'] ?? '') ?>')" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors" title="Delete">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400">No vehicles found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Assignments Tab -->
<div id="panel-assignments" class="transport-tab-panel hidden">
    <div class="mb-4 flex justify-end">
        <button onclick="openAssignModal()" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Assign Student
        </button>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Student</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Route</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Pickup Point</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Drop Point</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <?php if (!empty($assignments)): ?>
                        <?php foreach ($assignments as $a): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars(($a['student']['first_name'] ?? '') . ' ' . ($a['student']['last_name'] ?? '')) ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($a['student']['admission_number'] ?? '') ?></div>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($a['route']['name'] ?? '') ?></td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($a['pickup_point'] ?? '—') ?></td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($a['drop_point'] ?? '—') ?></td>
                                <td class="px-4 py-3 text-right">
                                    <button onclick="removeAssignment('<?= htmlspecialchars($a['id'] ?? '') ?>')" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors" title="Remove">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400">No assignments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Route Modal -->
<div id="routeModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeRouteModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl dark:bg-gray-900 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                <h3 id="routeModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Route</h3>
                <button onclick="closeRouteModal()" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form class="space-y-4 px-6 py-4" onsubmit="saveRoute(event)">
                <input type="hidden" id="routeId" value="">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Route Name *</label>
                    <input type="text" id="routeName" required placeholder="e.g. Westlands Route" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea id="routeDesc" rows="2" placeholder="Route description..." class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="routeStatus" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeRouteModal()" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">Save Route</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Vehicle Modal -->
<div id="vehicleModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeVehicleModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl dark:bg-gray-900 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                <h3 id="vehicleModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Vehicle</h3>
                <button onclick="closeVehicleModal()" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form class="space-y-4 px-6 py-4" onsubmit="saveVehicle(event)">
                <input type="hidden" id="vehicleId" value="">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Registration # *</label>
                        <input type="text" id="vehicleReg" required placeholder="e.g. KBA 234J" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Type *</label>
                        <select id="vehicleType" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="bus">Bus</option>
                            <option value="van">Van</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Capacity *</label>
                        <input type="number" id="vehicleCapacity" required min="1" value="40" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="vehicleStatus" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="active">Active</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Driver Name</label>
                        <input type="text" id="vehicleDriverName" placeholder="Driver name" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Driver Phone</label>
                        <input type="text" id="vehicleDriverPhone" placeholder="e.g. 0722 123456" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeVehicleModal()" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">Save Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Student Modal -->
<div id="assignModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAssignModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-xl dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assign Student to Route</h3>
                <button onclick="closeAssignModal()" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form class="space-y-4 px-6 py-4" onsubmit="saveAssignment(event)">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Student *</label>
                    <select required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">Select Student</option>
                        <?php foreach ($students as $st): ?>
                            <option value="<?= htmlspecialchars($st['id']) ?>"><?= htmlspecialchars(($st['first_name'] ?? '') . ' ' . ($st['last_name'] ?? '')) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Route *</label>
                    <select required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">Select Route</option>
                        <?php foreach ($routes as $r): ?>
                            <?php if (strtolower($r['status'] ?? 'active') === 'active'): ?>
                                <option value="<?= htmlspecialchars($r['id']) ?>"><?= htmlspecialchars($r['name']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Pickup Point</label>
                        <input type="text" placeholder="Pickup point" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Drop Point</label>
                        <input type="text" placeholder="Drop point" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeAssignModal()" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function switchTransportTab(tab) {
        document.querySelectorAll('.transport-tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.transport-tab-btn').forEach(b => {
            b.classList.remove('border-emerald-500', 'text-emerald-600', 'dark:text-emerald-400');
            b.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById('panel-' + tab).classList.remove('hidden');
        const btn = document.getElementById('tab-' + tab);
        btn.classList.add('border-emerald-500', 'text-emerald-600', 'dark:text-emerald-400');
        btn.classList.remove('border-transparent', 'text-gray-500');
    }

    function openRouteModal() { document.getElementById('routeModal').classList.remove('hidden'); document.getElementById('routeModalTitle').textContent='Add Route'; }
    function closeRouteModal() { document.getElementById('routeModal').classList.add('hidden'); }
    function editRoute(r) { document.getElementById('routeModal').classList.remove('hidden'); document.getElementById('routeModalTitle').textContent='Edit Route'; document.getElementById('routeId').value=r.id||''; document.getElementById('routeName').value=r.name||''; document.getElementById('routeDesc').value=r.description||''; document.getElementById('routeStatus').value=r.status||'active'; }
    function saveRoute(e) { e.preventDefault(); console.log('Saving route'); closeRouteModal(); }
    function deleteRoute(id) { if(confirm('Delete this route?')) console.log('Deleting route:', id); }

    function openVehicleModal() { document.getElementById('vehicleModal').classList.remove('hidden'); document.getElementById('vehicleModalTitle').textContent='Add Vehicle'; }
    function closeVehicleModal() { document.getElementById('vehicleModal').classList.add('hidden'); }
    function editVehicle(v) { document.getElementById('vehicleModal').classList.remove('hidden'); document.getElementById('vehicleModalTitle').textContent='Edit Vehicle'; document.getElementById('vehicleId').value=v.id||''; document.getElementById('vehicleReg').value=v.registration_number||''; document.getElementById('vehicleType').value=v.type||'bus'; document.getElementById('vehicleCapacity').value=v.capacity||40; document.getElementById('vehicleDriverName').value=v.driver_name||''; document.getElementById('vehicleDriverPhone').value=v.driver_phone||''; document.getElementById('vehicleStatus').value=v.status||'active'; }
    function saveVehicle(e) { e.preventDefault(); console.log('Saving vehicle'); closeVehicleModal(); }
    function deleteVehicle(id) { if(confirm('Delete this vehicle?')) console.log('Deleting vehicle:', id); }

    function openAssignModal() { document.getElementById('assignModal').classList.remove('hidden'); }
    function closeAssignModal() { document.getElementById('assignModal').classList.add('hidden'); }
    function saveAssignment(e) { e.preventDefault(); console.log('Saving assignment'); closeAssignModal(); }
    function removeAssignment(id) { if(confirm('Remove this assignment?')) console.log('Removing assignment:', id); }
</script>
