<?php
$user = $user ?? [];
$schools = $schools ?? [];
$allRoles = $allRoles ?? [];
$assignedRoleIds = $assignedRoleIds ?? [];
$title = 'Edit User';
$formAction = url('/users/' . ($user['id'] ?? ''));
?>

<div class="max-w-2xl mx-auto space-y-6">
  <div class="flex items-center gap-4">
    <a href="<?= url('/users') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update user details below</p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name <span class="text-red-500">*</span></label>
            <input type="text" id="first_name" name="first_name" required value="<?= e($user['first_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name <span class="text-red-500">*</span></label>
            <input type="text" id="last_name" name="last_name" required value="<?= e($user['last_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" required value="<?= e($user['email'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
            <input type="text" id="phone" name="phone" value="<?= e($user['phone'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="+254 700 000 000">
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
          <input type="password" id="password" name="password" minlength="8" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Leave blank to keep current">
          <p class="text-xs text-gray-400 mt-1">Leave blank to keep the current password</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="school_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School</label>
            <select id="school_id" name="school_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select school</option>
              <?php foreach($schools as $s): ?>
              <option value="<?= e($s['id']) ?>" <?= ($user['school_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select id="is_active" name="is_active" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="1" <?= ($user['is_active'] ?? 1) ? 'selected' : '' ?>>Active</option>
              <option value="0" <?= !($user['is_active'] ?? 1) ? 'selected' : '' ?>>Inactive</option>
            </select>
          </div>
        </div>

        <?php if (!empty($allRoles)): ?>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Roles</label>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-40 overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 p-3">
            <?php foreach($allRoles as $role): ?>
            <label class="flex items-center gap-2 cursor-pointer group">
              <input type="checkbox" name="role_ids[]" value="<?= e($role['id']) ?>" <?= in_array($role['id'] ?? '', $assignedRoleIds) ? 'checked' : '' ?> class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
              <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors"><?= e($role['display_name'] ?? $role['name']) ?></span>
            </label>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/users') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Update User</button>
      </div>
    </form>
  </div>
</div>
