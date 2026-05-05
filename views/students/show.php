<?php
$student = $student ?? [];
$branch = $branch ?? [];
$class = $class ?? [];
$attendance = $attendance ?? [];
$fees = $fees ?? [];
$results = $results ?? [];
$fullName = trim(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? ''));
$isActive = ($student['status'] ?? 'active') === 'active';
$attendanceRate = $attendance['rate'] ?? 0;
$totalPaid = $fees['total_paid'] ?? 0;
$totalDue = $fees['total_due'] ?? 0;
$avgGrade = $results['average'] ?? 0;
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
      <a href="<?= url('/students') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <div class="flex items-center gap-3">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 text-lg font-bold text-emerald-700 dark:text-emerald-300"><?= strtoupper(mb_substr($student['first_name'] ?? 'U', 0, 1)) ?></div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= e($fullName) ?></h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5"><?= e($student['admission_no'] ?? '') ?> · <?= e($class['name'] ?? 'Unassigned') ?></p>
        </div>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium <?= $isActive ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' ?>">
        <span class="h-1.5 w-1.5 rounded-full <?= $isActive ? 'bg-emerald-500' : 'bg-red-500' ?>"></span>
        <?= $isActive ? 'Active' : 'Inactive' ?>
      </span>
      <a href="<?= url('/students/' . ($student['id'] ?? '') . '/edit') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
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
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($attendanceRate, 1) ?>%</p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Attendance</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
          <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">KES <?= number_format($totalPaid) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Fees Paid</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900">
          <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">KES <?= number_format($totalDue) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Balance Due</p>
        </div>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
          <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
          <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($avgGrade, 1) ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400">Avg Grade</p>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Personal Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Personal Information</h3>
      </div>
      <div class="p-5 space-y-4">
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Full Name</p>
          <p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($fullName) ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Email</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($student['email'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Phone</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($student['phone'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Date of Birth</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($student['dob'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Gender</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= ucfirst($student['gender'] ?? '—') ?></p>
        </div>
      </div>
    </div>

    <!-- Academic Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Academic Information</h3>
      </div>
      <div class="p-5 space-y-4">
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Admission No</p>
          <p class="text-sm font-medium text-emerald-700 dark:text-emerald-400 mt-1 font-mono"><?= e($student['admission_no'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Class</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($class['name'] ?? 'Unassigned') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Branch</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($branch['name'] ?? 'Unassigned') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Status</p>
          <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium mt-1 <?= $isActive ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' ?>"><?= $isActive ? 'Active' : 'Inactive' ?></span>
        </div>
      </div>
    </div>

    <!-- Guardian Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 px-5 py-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Guardian Information</h3>
      </div>
      <div class="p-5 space-y-4">
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Guardian Name</p>
          <p class="text-sm font-medium text-gray-900 dark:text-white mt-1"><?= e($student['guardian_name'] ?? '—') ?></p>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Guardian Phone</p>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><?= e($student['guardian_phone'] ?? '—') ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
