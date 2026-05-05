<?php
$records = $records ?? [];
$date = $date ?? '';
$class = $class ?? [];
$totalPresent = 0;
$totalAbsent = 0;
$totalLate = 0;
foreach ($records as $r) {
    if (($r['status'] ?? '') === 'present') $totalPresent++;
    if (($r['status'] ?? '') === 'absent') $totalAbsent++;
    if (($r['status'] ?? '') === 'late') $totalLate++;
}
$total = count($records);
$rate = $total > 0 ? round((($totalPresent + $totalLate) / $total) * 100, 1) : 0;
?>

<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/attendance') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Attendance History</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e($class['name'] ?? 'All Classes') ?> · <?= e($date ?? date('Y-m-d')) ?></p>
      </div>
    </div>
  </div>

  <!-- Summary -->
  <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
        </div>
        <div><p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $totalPresent ?></p><p class="text-xs text-gray-500 dark:text-gray-400">Present</p></div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900">
          <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <div><p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $totalAbsent ?></p><p class="text-xs text-gray-500 dark:text-gray-400">Absent</p></div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
          <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div><p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $totalLate ?></p><p class="text-xs text-gray-500 dark:text-gray-400">Late</p></div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
          <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div><p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $rate ?>%</p><p class="text-xs text-gray-500 dark:text-gray-400">Rate</p></div>
      </div>
    </div>
  </div>

  <!-- Records Table -->
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Admission No</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php if (!empty($records)): ?>
            <?php foreach($records as $r): ?>
            <?php
              $s = $r['student'] ?? [];
              $status = $r['status'] ?? 'present';
              $statusColors = ['present' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'absent' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'late' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'];
            ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
              <td class="px-4 py-3"><div class="flex items-center gap-2"><div class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 text-[10px] font-bold text-emerald-700 dark:text-emerald-300"><?= strtoupper(mb_substr($s['first_name'] ?? 'U', 0, 1)) ?></div><span class="text-sm font-medium text-gray-900 dark:text-white"><?= e(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? '')) ?></span></div></td>
              <td class="px-4 py-3 text-sm font-mono text-gray-600 dark:text-gray-300"><?= e($s['admission_no'] ?? '—') ?></td>
              <td class="px-4 py-3"><span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium <?= $statusColors[$status] ?? $statusColors['present'] ?>"><?= ucfirst($status) ?></span></td>
              <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400"><?= e($r['time'] ?? '—') ?></td>
              <td class="px-4 py-3 text-right">
                <a href="<?= url('/attendance/' . ($r['id'] ?? '') . '/edit') ?>" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
          <tr><td colspan="5" class="px-4 py-12 text-center">
            <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No attendance records found</p>
          </td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
