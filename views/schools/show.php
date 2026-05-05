<?php
$school = $school ?? [];
$branches = $branches ?? [];
$stats = $stats ?? [];
$branchCount = $stats['branch_count'] ?? count($branches);
$userCount = $stats['user_count'] ?? 0;
$studentCount = $stats['student_count'] ?? 0;
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/schools') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($school['name'] ?? 'School Details') ?></h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e($school['code'] ?? '') ?> · <?= e($school['address'] ?? 'No address') ?></p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <?php $status = $school['status'] ?? 'active'; ?>
      <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium <?= $status === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>">
        <span class="h-1.5 w-1.5 rounded-full <?= $status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
        <?= ucfirst($status) ?>
      </span>
      <a href="<?= url('/schools/' . ($school['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
      </a>
    </div>
  </div>

  <!-- Stats Row -->
  <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($branchCount) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Branches</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
          <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($userCount) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Users</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
          <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($studentCount) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Students</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900">
          <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($school['code'] ?? '—') ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Code</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Details & Branches -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- School Info -->
    <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">School Information</h3>
      </div>
      <div class="p-5 space-y-4">
        <?php if (!empty($school['logo'])): ?>
        <div class="flex justify-center">
          <img src="<?= e($school['logo']) ?>" alt="<?= e($school['name'] ?? 'School') ?>" class="w-20 h-20 rounded-xl object-cover border border-gray-200 dark:border-gray-700">
        </div>
        <?php endif; ?>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">School Name</p>
          <p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($school['name'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Email</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($school['email'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Phone</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($school['phone'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Address</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($school['address'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Status</p>
          <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium mt-1 <?= $status === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>">
            <span class="h-1.5 w-1.5 rounded-full <?= $status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
            <?= ucfirst($status) ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Branches List -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Branches</h3>
        <a href="<?= url('/branches?school_id=' . ($school['id'] ?? '')) ?>" class="text-xs text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">View all</a>
      </div>
      <?php if (!empty($branches)): ?>
      <div class="divide-y divide-gray-100 dark:divide-gray-700">
        <?php foreach($branches as $branch): ?>
        <div class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
          <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900 shrink-0">
            <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-900 dark:text-white truncate"><?= e($branch['name'] ?? '—') ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?= e($branch['address'] ?? '—') ?></p>
          </div>
          <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-medium shrink-0 <?= ($branch['status'] ?? 'active') === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>">
            <span class="h-1 w-1 rounded-full <?= ($branch['status'] ?? 'active') === 'active' ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
            <?= ucfirst($branch['status'] ?? 'active') ?>
          </span>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="flex flex-col items-center justify-center py-12 text-gray-400">
        <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No branches yet</p>
        <p class="text-xs text-gray-400 dark:text-gray-500">Add a branch to this school</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
