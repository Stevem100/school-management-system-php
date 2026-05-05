<?php
$stats = isset($stats) ? $stats : [
    'total_conversations' => 0,
    'total_messages'      => 0,
    'active_users'        => 0,
    'total_tokens'        => 0,
];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">AI Analytics</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitor AI usage, conversations, and engagement metrics</p>
        </div>
        <div class="mt-3 sm:mt-0 flex items-center gap-2">
            <select class="rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                <option>Last 7 days</option>
                <option selected>Last 30 days</option>
                <option>Last 90 days</option>
                <option>This Year</option>
            </select>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
                <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($stats['total_conversations']) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Conversations</p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($stats['total_messages']) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Messages</p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($stats['active_users']) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Active Users</p>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
                <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($stats['total_tokens']) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tokens Used</p>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
    
    <!-- Usage Over Time -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">AI Usage Over Time</h3>
        <div class="flex items-center justify-center h-64 rounded-lg bg-gray-50 dark:bg-gray-800/50">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">Usage chart will appear here</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Requires AI usage data</p>
            </div>
        </div>
    </div>

    <!-- Top Topics -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Popular Topics</h3>
        <div class="space-y-4">
            <div class="flex items-center gap-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900 text-sm font-bold text-emerald-600 dark:text-emerald-400">1</div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Mathematics</p>
                        <span class="text-xs text-gray-500 dark:text-gray-400">42%</span>
                    </div>
                    <div class="mt-1 h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                        <div class="h-2 rounded-full bg-emerald-500" style="width: 42%"></div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900 text-sm font-bold text-blue-600 dark:text-blue-400">2</div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Science</p>
                        <span class="text-xs text-gray-500 dark:text-gray-400">28%</span>
                    </div>
                    <div class="mt-1 h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                        <div class="h-2 rounded-full bg-blue-500" style="width: 28%"></div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900 text-sm font-bold text-amber-600 dark:text-amber-400">3</div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">English</p>
                        <span class="text-xs text-gray-500 dark:text-gray-400">18%</span>
                    </div>
                    <div class="mt-1 h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                        <div class="h-2 rounded-full bg-amber-500" style="width: 18%"></div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900 text-sm font-bold text-violet-600 dark:text-violet-400">4</div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">History</p>
                        <span class="text-xs text-gray-500 dark:text-gray-400">12%</span>
                    </div>
                    <div class="mt-1 h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                        <div class="h-2 rounded-full bg-violet-500" style="width: 12%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Conversations Table -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Recent Conversations</h3>
        <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">User</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Topic</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Messages</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Date</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-600 dark:bg-emerald-900 dark:text-emerald-400">BN</div>
                            <span class="font-medium text-gray-900 dark:text-white">Brian Njoroge</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">Math - Fractions</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">12</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">Today, 2:30 PM</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">Active</span>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600 dark:bg-blue-900 dark:text-blue-400">SM</div>
                            <span class="font-medium text-gray-900 dark:text-white">Sarah Muthoni</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">Science - Water Cycle</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">8</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">Today, 11:15 AM</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">Completed</span>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-xs font-bold text-amber-600 dark:bg-amber-900 dark:text-amber-400">KO</div>
                            <span class="font-medium text-gray-900 dark:text-white">Kevin Otieno</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">English - Grammar</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">5</td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400">Yesterday</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">Completed</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="border-t border-gray-100 px-6 py-3 dark:border-gray-800">
        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">No data to display. AI analytics will populate as conversations occur.</p>
    </div>
</div>
