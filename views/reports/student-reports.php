<?php $pageTitle = $pageTitle ?? 'Student Reports'; ?>
<?php
  $students = $students ?? [];
  $classes = $classes ?? [];
  $academicYears = $academicYears ?? [];
  $filters = $filters ?? [];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Student Reports</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View student enrollment and demographic data</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="<?= url('/reports/export') ?>/students?<?= http_build_query($filters) ?>" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export
      </a>
    </div>
  </div>

  <!-- Filters -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
    <form method="GET" action="<?= url('/reports/students') ?>" class="flex flex-col sm:flex-row gap-3 items-end">
      <!-- Search -->
      <div class="flex-1 w-full sm:max-w-xs">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search</label>
        <div class="relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input type="text" name="search" value="<?= e($filters['search'] ?? '') ?>" placeholder="Search students..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>
      </div>

      <!-- Class Filter -->
      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Class</label>
        <select name="class_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          <option value="">All Classes</option>
          <?php foreach ($classes as $cls): ?>
            <option value="<?= e($cls['id']) ?>" <?= ($filters['class_id'] ?? '') == $cls['id'] ? 'selected' : '' ?>><?= e($cls['name'] ?? "Class #{$cls['id']}") ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Academic Year -->
      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Academic Year</label>
        <select name="academic_year" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          <option value="">All Years</option>
          <?php foreach ($academicYears as $year): ?>
            <option value="<?= e($year) ?>" <?= ($filters['academic_year'] ?? '') == $year ? 'selected' : '' ?>><?= e($year) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Status -->
      <div class="w-full sm:w-auto">
        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
        <select name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          <option value="">All Status</option>
          <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
          <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
          <option value="graduated" <?= ($filters['status'] ?? '') === 'graduated' ? 'selected' : '' ?>>Graduated</option>
        </select>
      </div>

      <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium shadow-sm">Apply</button>
        <a href="<?= url('/reports/students') ?>" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Reset</a>
      </div>
    </form>
  </div>

  <!-- Results Table -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
      <p class="text-xs text-gray-500 dark:text-gray-400"><?= count($students) ?> records found</p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student Name</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Admission No</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Class</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Section</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Gender</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php if (!empty($students)): ?>
            <?php $idx = 0; foreach ($students as $s): $idx++; ?>
            <?php
              $status = $s['status'] ?? 'active';
              $statusClass = $status === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : ($status === 'inactive' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400');
            ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
              <td class="px-4 py-3 text-gray-500 dark:text-gray-400"><?= $idx ?></td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <div class="h-8 w-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                    <?= strtoupper(mb_substr($s['name'] ?? $s['first_name'] ?? 'S', 0, 1)) ?>
                  </div>
                  <span class="font-medium text-gray-900 dark:text-white"><?= e($s['name'] ?? ($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? '')) ?></span>
                </div>
              </td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden md:table-cell font-mono text-xs"><?= e($s['admission_no'] ?? '—') ?></td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden sm:table-cell"><?= e($s['class_name'] ?? $s['class_id'] ?? '—') ?></td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden lg:table-cell"><?= e($s['section'] ?? '—') ?></td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden md:table-cell"><?= e($s['gender'] ?? '—') ?></td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center rounded-full <?= $statusClass ?> px-2.5 py-1 text-xs font-medium"><?= ucfirst(e($status)) ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="px-4 py-16 text-center">
                <svg class="mx-auto w-16 h-16 mb-4 text-gray-300 dark:text-gray-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No records found</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Adjust the filters to see results</p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
