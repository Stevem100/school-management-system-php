<?php
$branch = $branch ?? [];
$school = $school ?? [];
$stats = $stats ?? [];
$userCount = $stats['user_count'] ?? 0;
$classCount = $stats['class_count'] ?? 0;
$studentCount = $stats['student_count'] ?? 0;
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/branches') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($branch['name'] ?? 'Branch Details') ?></h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e($school['name'] ?? 'School') ?> · <?= e($branch['address'] ?? 'No address') ?></p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <?php $status = $branch['status'] ?? 'active'; ?>
      <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium <?= $status === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' ?>">
        <span class="h-1.5 w-1.5 rounded-full <?= $status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
        <?= ucfirst($status) ?>
      </span>
      <a href="<?= url('/branches/' . ($branch['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
      </a>
    </div>
  </div>

  <!-- Stats Row -->
  <div class="grid grid-cols-3 gap-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($userCount) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Users</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
          <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($classCount) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Classes</p>
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
  </div>

  <!-- Branch Info -->
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Branch Information</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-6">
      <div>
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Branch Name</p>
        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($branch['name'] ?? '—') ?></p>
      </div>
      <div>
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">School</p>
        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($school['name'] ?? '—') ?></p>
      </div>
      <div>
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Email</p>
        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($branch['email'] ?? '—') ?></p>
      </div>
      <div>
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Phone</p>
        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($branch['phone'] ?? '—') ?></p>
      </div>
      <div class="sm:col-span-2">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Address</p>
        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($branch['address'] ?? '—') ?></p>
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
</div>
