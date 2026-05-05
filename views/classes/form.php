<?php
$class = $class ?? null;
$teachers = $teachers ?? [];
$mode = $mode ?? 'create';

$gradeLevels = ['Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Form 1','Form 2','Form 3','Form 4'];
$academicYears = ['2024-2025','2025-2026','2023-2024'];

$isEdit = ($mode === 'edit' && $class !== null);
$title = $isEdit ? 'Edit Class' : 'Create Class';
$formAction = $isEdit ? url('classes/' . $class['id']) : url('classes');
?>

<div class="max-w-2xl mx-auto space-y-6">
  <!-- Page Header -->
  <div class="flex items-center gap-4">
    <a href="<?= url('classes') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= $isEdit ? 'Update class details below' : 'Add a new class to the school' ?></p>
    </div>
  </div>

  <!-- Form Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>
      <div class="p-6 space-y-5">
        <!-- Class Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class Name <span class="text-red-500">*</span></label>
          <input type="text" id="name" name="name" required value="<?= e($class['name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. Class 8A">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Grade Level -->
          <div>
            <label for="grade_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grade Level <span class="text-red-500">*</span></label>
            <select id="grade_level" name="grade_level" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select grade</option>
              <?php foreach($gradeLevels as $gl): ?>
              <option value="<?= e($gl) ?>" <?= isset($class['gradeLevel']) && $class['gradeLevel'] === $gl ? 'selected' : '' ?>><?= e($gl) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Section -->
          <div>
            <label for="section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Section <span class="text-red-500">*</span></label>
            <input type="text" id="section" name="section" required value="<?= e($class['section'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. A">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Capacity -->
          <div>
            <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Capacity <span class="text-red-500">*</span></label>
            <input type="number" id="capacity" name="capacity" required min="1" value="<?= e($class['capacity'] ?? 40) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. 40">
          </div>

          <!-- Academic Year -->
          <div>
            <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year <span class="text-red-500">*</span></label>
            <select id="academic_year" name="academic_year" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select year</option>
              <?php foreach($academicYears as $ay): ?>
              <option value="<?= e($ay) ?>" <?= isset($class['academicYear']) && $class['academicYear'] === $ay ? 'selected' : '' ?>><?= e($ay) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Class Teacher -->
        <div>
          <label for="class_teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class Teacher</label>
          <select id="class_teacher_id" name="class_teacher_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="">Select teacher</option>
            <?php foreach($teachers as $t): ?>
            <option value="<?= e($t['id'] ?? '') ?>" <?= isset($class['classTeacherId']) && $class['classTeacherId'] == $t['id'] ? 'selected' : '' ?>><?= e(trim(($t['firstName'] ?? '') . ' ' . ($t['lastName'] ?? ''))) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Status -->
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
          <select id="status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="active" <?= ($class['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= isset($class['status']) && $class['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            <option value="archived" <?= isset($class['status']) && $class['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
          </select>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('classes') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm"><?= $isEdit ? 'Update Class' : 'Create Class' ?></button>
      </div>
    </form>
  </div>
</div>
