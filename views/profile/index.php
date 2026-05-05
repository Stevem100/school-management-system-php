<?php $pageTitle = $pageTitle ?? 'My Profile'; ?>
<?php
    $user = $user ?? [];
    $roles = $roles ?? [];
    $branch = $branch ?? null;
    $school = $school ?? null;
    $userRoles = $userRoles ?? [];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your account settings and personal information</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Info Card -->
    <div class="lg:col-span-1">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 h-24"></div>
        <div class="px-6 pb-6 -mt-10">
          <div class="flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 border-4 border-white dark:border-gray-800 text-emerald-600 dark:text-emerald-400 text-2xl font-bold">
            <?= strtoupper(mb_substr($user['first_name'] ?? 'U', 0, 1)) ?>
          </div>
          <div class="mt-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
              <?= e($user['first_name'] ?? '') ?> <?= e($user['last_name'] ?? '') ?>
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400"><?= e($user['email'] ?? '') ?></p>
            <?php if (!empty($user['phone'])): ?>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5"><?= e($user['phone']) ?></p>
            <?php endif; ?>
          </div>

          <!-- Roles -->
          <div class="mt-4">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Roles</p>
            <div class="flex flex-wrap gap-1.5">
              <?php if (!empty($roles)): ?>
                <?php foreach($roles as $role): ?>
                <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400">
                  <?= e($role['display_name'] ?? $role['name'] ?? '') ?>
                </span>
                <?php endforeach; ?>
              <?php elseif (!empty($userRoles)): ?>
                <?php foreach($userRoles as $role): ?>
                <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400">
                  <?= e(is_array($role) ? ($role['display_name'] ?? $role['name'] ?? $role) : $role) ?>
                </span>
                <?php endforeach; ?>
              <?php else: ?>
                <span class="text-xs text-gray-400">No roles assigned</span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Branch / School Info -->
          <?php if ($branch || $school): ?>
          <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Organization</p>
            <?php if ($school): ?>
            <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
              <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0119.5 12c0 .818-.082 1.616-.244 2.386L12 14zm-6.16 3.422A12.083 12.083 0 004.5 12c0-.818.082-1.616.244-2.386L6 14l6 6z"/></svg>
              <?= e($school['name'] ?? '') ?>
            </div>
            <?php endif; ?>
            <?php if ($branch): ?>
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mt-1">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
              <?= e($branch['name'] ?? '') ?>
            </div>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <!-- Account Status -->
          <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-2">
              <?php $status = $user['status'] ?? 'active'; ?>
              <?php if ($status === 'active'): ?>
              <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
              <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Active Account</span>
              <?php else: ?>
              <span class="h-2 w-2 rounded-full bg-red-500"></span>
              <span class="text-xs text-red-600 dark:text-red-400 font-medium">Inactive Account</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="lg:col-span-2">
      <form method="POST" action="<?= url('/profile') ?>" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <?= csrf_field() ?>

        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Profile</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update your personal information below</p>
        </div>

        <div class="p-6 space-y-5">
          <!-- Name Fields -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="form-first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name <span class="text-red-500">*</span></label>
              <input type="text" id="form-first_name" name="first_name" required value="<?= e($user['first_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
              <label for="form-last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name <span class="text-red-500">*</span></label>
              <input type="text" id="form-last_name" name="last_name" required value="<?= e($user['last_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
          </div>

          <!-- Contact Fields -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="form-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address <span class="text-red-500">*</span></label>
              <input type="email" id="form-email" name="email" required value="<?= e($user['email'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
              <label for="form-phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
              <input type="text" id="form-phone" name="phone" value="<?= e($user['phone'] ?? '') ?>" placeholder="+254 700 000 000" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
            </div>
          </div>

          <!-- Password Change -->
          <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
            <div class="flex items-center gap-2 mb-4">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Change Password</p>
            </div>
            <p class="text-xs text-gray-400 mb-4">Leave blank if you don't want to change your password</p>

            <div class="space-y-4">
              <div>
                <label for="form-current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Password</label>
                <input type="password" id="form-current_password" name="current_password" placeholder="Enter current password" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label for="form-new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                  <input type="password" id="form-new_password" name="new_password" placeholder="Min 8 characters" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
                </div>
                <div>
                  <label for="form-confirm_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm New Password</label>
                  <input type="password" id="form-confirm_password" name="confirm_password" placeholder="Repeat new password" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl border-t border-gray-200 dark:border-gray-700 flex justify-end">
          <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
