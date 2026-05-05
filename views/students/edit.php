<?php
$student = $student ?? [];
$branches = $branches ?? [];
$classes = $classes ?? [];
$title = 'Edit Student';
$formAction = url('/students/' . ($student['id'] ?? ''));
?>

<div class="max-w-2xl mx-auto space-y-6">
  <div class="flex items-center gap-4">
    <a href="<?= url('/students') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update student details below</p>
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
            <input type="text" id="first_name" name="first_name" required value="<?= e($student['first_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name <span class="text-red-500">*</span></label>
            <input type="text" id="last_name" name="last_name" required value="<?= e($student['last_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" required value="<?= e($student['email'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
            <input type="text" id="phone" name="phone" value="<?= e($student['phone'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="+254 700 000 000">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="admission_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Admission No <span class="text-red-500">*</span></label>
            <input type="text" id="admission_no" name="admission_no" required value="<?= e($student['admission_no'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="dob" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
            <input type="date" id="dob" name="dob" value="<?= e($student['dob'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
            <select id="gender" name="gender" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="male" <?= ($student['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
              <option value="female" <?= ($student['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
              <option value="other" <?= ($student['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
            </select>
          </div>
          <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="active" <?= ($student['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
              <option value="inactive" <?= ($student['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="branch_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Branch</label>
            <select id="branch_id" name="branch_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select branch</option>
              <?php foreach($branches as $b): ?>
              <option value="<?= e($b['id']) ?>" <?= ($student['branch_id'] ?? '') == $b['id'] ? 'selected' : '' ?>><?= e($b['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class</label>
            <select id="class_id" name="class_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select class</option>
              <?php foreach($classes as $c): ?>
              <option value="<?= e($c['id']) ?>" <?= ($student['class_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
          <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Guardian Information</p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="guardian_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Guardian Name</label>
              <input type="text" id="guardian_name" name="guardian_name" value="<?= e($student['guardian_name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
              <label for="guardian_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Guardian Phone</label>
              <input type="text" id="guardian_phone" name="guardian_phone" value="<?= e($student['guardian_phone'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="+254 700 000 000">
            </div>
          </div>
        </div>
      </div>

      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/students') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Update Student</button>
      </div>
    </form>
  </div>
</div>
