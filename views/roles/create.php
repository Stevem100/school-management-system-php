<?php
$role = $role ?? [];
$permissions = $permissions ?? [];
$title = 'Create Role';
$formAction = url('/roles');

$allPermissions = $permissions ?? [
    'students.view' => 'View Students',
    'students.create' => 'Create Students',
    'students.edit' => 'Edit Students',
    'students.delete' => 'Delete Students',
    'classes.view' => 'View Classes',
    'classes.create' => 'Create Classes',
    'classes.edit' => 'Edit Classes',
    'classes.delete' => 'Delete Classes',
    'fees.view' => 'View Fees',
    'fees.create' => 'Create Fees',
    'fees.edit' => 'Edit Fees',
    'exams.view' => 'View Exams',
    'exams.create' => 'Create Exams',
    'exams.edit' => 'Edit Exams',
    'attendance.view' => 'View Attendance',
    'attendance.create' => 'Record Attendance',
    'reports.view' => 'View Reports',
    'settings.manage' => 'Manage Settings',
    'users.manage' => 'Manage Users',
    'roles.manage' => 'Manage Roles',
];
?>

<div class="max-w-2xl mx-auto space-y-6">
  <!-- Page Header -->
  <div class="flex items-center gap-4">
    <a href="<?= url('/roles') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create a new role and assign permissions</p>
    </div>
  </div>

  <!-- Form Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>

      <div class="p-6 space-y-5">
        <!-- Role Details -->
        <div class="flex items-center gap-2 mb-1">
          <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          </div>
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Role Details</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role Name <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" required value="<?= e($role['name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400" placeholder="e.g. Teacher, Admin, Parent">
          </div>
          <div>
            <label for="guard_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Guard Name</label>
            <input type="text" id="guard_name" name="guard_name" value="<?= e($role['guard_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400" placeholder="e.g. teacher (system identifier)">
          </div>
        </div>

        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
          <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none" placeholder="Briefly describe what this role can do"><?= e($role['description'] ?? '') ?></textarea>
        </div>

        <!-- Permissions -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
              </div>
              <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Permissions</h2>
            </div>
            <div class="flex items-center gap-2">
              <button type="button" onclick="toggleAllPermissions(true)" class="text-xs font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 transition-colors">Select All</button>
              <span class="text-gray-300 dark:text-gray-600">|</span>
              <button type="button" onclick="toggleAllPermissions(false)" class="text-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 transition-colors">Deselect All</button>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" id="permissions-grid">
            <?php
              // Group permissions by module
              $grouped = [];
              foreach ($allPermissions as $key => $label) {
                $parts = explode('.', $key);
                $module = $parts[0];
                if (!isset($grouped[$module])) {
                  $grouped[$module] = [];
                }
                $grouped[$module][$key] = $label;
              }
            ?>
            <?php foreach ($grouped as $module => $perms): ?>
              <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-3 space-y-2">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider"><?= e(ucfirst($module)) ?></p>
                <?php foreach ($perms as $permKey => $permLabel): ?>
                  <?php
                    $rolePerms = $role['permissions'] ?? [];
                    $isChecked = is_array($rolePerms) && in_array($permKey, $rolePerms);
                  ?>
                  <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="permissions[]" value="<?= e($permKey)" <?= $isChecked ? 'checked' : '' ?> class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900">
                    <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?= e($permLabel) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/roles') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
          <span class="inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Role
          </span>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleAllPermissions(checked) {
  document.querySelectorAll('#permissions-grid input[type="checkbox"]').forEach(function(cb) {
    cb.checked = checked;
  });
}
</script>
