<?php
$role = $role ?? [];
$permissionsByModule = $permissionsByModule ?? [];
$assignedPermIds = $assignedPermIds ?? [];
$title = 'Edit Role';
$formAction = url('/roles/' . ($role['id'] ?? ''));
?>

<div class="max-w-2xl mx-auto space-y-6">
  <div class="flex items-center gap-4">
    <a href="<?= url('/roles') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update role and permissions below</p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role Name <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" required value="<?= e($role['name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="display_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Display Name <span class="text-red-500">*</span></label>
            <input type="text" id="display_name" name="display_name" required value="<?= e($role['display_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>

        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
          <textarea id="description" name="description" rows="2" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"><?= e($role['description'] ?? '') ?></textarea>
        </div>

        <div>
          <label for="scope" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Scope</label>
          <select id="scope" name="scope" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="global" <?= ($role['scope'] ?? 'global') === 'global' ? 'selected' : '' ?>>Global</option>
            <option value="school" <?= ($role['scope'] ?? '') === 'school' ? 'selected' : '' ?>>School</option>
            <option value="branch" <?= ($role['scope'] ?? '') === 'branch' ? 'selected' : '' ?>>Branch</option>
            <option value="personal" <?= ($role['scope'] ?? '') === 'personal' ? 'selected' : '' ?>>Personal</option>
          </select>
        </div>

        <?php if (!empty($permissionsByModule)): ?>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permissions</label>
          <div class="space-y-3 max-h-64 overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <?php foreach($permissionsByModule as $module => $perms): ?>
            <div>
              <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider"><?= e(ucfirst($module)) ?></p>
                <button type="button" onclick="this.parentElement.nextElementSibling.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = true)" class="text-[10px] text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 font-medium">Select all</button>
              </div>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5">
                <?php foreach($perms as $perm): ?>
                <label class="flex items-center gap-2 cursor-pointer group">
                  <input type="checkbox" name="permission_ids[]" value="<?= e($perm['id']) ?>" <?= in_array($perm['id'] ?? '', $assignedPermIds) ? 'checked' : '' ?> class="h-3.5 w-3.5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                  <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate"><?= e($perm['display_name'] ?? $perm['name']) ?></span>
                </label>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/roles') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Update Role</button>
      </div>
    </form>
  </div>
</div>
