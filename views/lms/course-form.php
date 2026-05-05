<?php $pageTitle = $pageTitle ?? ($course ?? null ? 'Edit Course' : 'Create Course'); ?>
<?php
  $course = $course ?? null;
  $classes = $classes ?? [];
  $teachers = $teachers ?? [];
  $isEdit = $course !== null;
  $formAction = $isEdit ? url('/lms/courses/' . $course['id']) : url('/lms/courses');
  $formMethod = $isEdit ? 'POST' : 'POST';
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $isEdit ? 'Edit Course' : 'Create Course' ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= $isEdit ? 'Update course details' : 'Add a new online course' ?></p>
    </div>
    <a href="<?= url('/lms/courses') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to Courses
    </a>
  </div>

  <!-- Course Form -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>" enctype="multipart/form-data">
      <?php if ($isEdit): ?>
        <input type="hidden" name="_method" value="PUT">
      <?php endif; ?>
      <?= csrf_field() ?>

      <div class="p-6 space-y-5">
        <!-- Title -->
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Title <span class="text-red-500">*</span></label>
          <input type="text" id="title" name="title" required value="<?= e($course['title'] ?? '') ?>" placeholder="e.g. Introduction to Mathematics" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
          <textarea id="description" name="description" rows="4" placeholder="Course description and objectives..." class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"><?= e($course['description'] ?? '') ?></textarea>
        </div>

        <!-- Class & Teacher -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class <span class="text-red-500">*</span></label>
            <select id="class_id" name="class_id" required class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select Class</option>
              <?php foreach ($classes as $cls): ?>
                <option value="<?= e($cls['id']) ?>" <?= isset($course['class_id']) && $course['class_id'] == $cls['id'] ? 'selected' : '' ?>><?= e($cls['name'] ?? $cls['class_name'] ?? "Class #{$cls['id']}") ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teacher <span class="text-red-500">*</span></label>
            <select id="teacher_id" name="teacher_id" required class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select Teacher</option>
              <?php foreach ($teachers as $t): ?>
                <option value="<?= e($t['id']) ?>" <?= isset($course['teacher_id']) && $course['teacher_id'] == $t['id'] ? 'selected' : '' ?>><?= e($t['name'] ?? "Teacher #{$t['id']}") ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Thumbnail -->
        <div>
          <label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thumbnail</label>
          <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-900/30 dark:file:text-emerald-400 hover:file:bg-emerald-100 dark:hover:file:bg-emerald-900/50">
          <?php if ($isEdit && !empty($course['thumbnail'])): ?>
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Current: <?= e($course['thumbnail']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Dates -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date <span class="text-red-500">*</span></label>
            <input type="date" id="start_date" name="start_date" required value="<?= e($course['start_date'] ?? '') ?>" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
            <input type="date" id="end_date" name="end_date" value="<?= e($course['end_date'] ?? '') ?>" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>

        <!-- Status -->
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
          <select id="status" name="status" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="draft" <?= ($course['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="active" <?= ($course['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="archived" <?= ($course['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
          </select>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:justify-end gap-3">
        <a href="<?= url('/lms/courses') ?>" class="px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-center">
          Cancel
        </a>
        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
          <?= $isEdit ? 'Update Course' : 'Save Course' ?>
        </button>
      </div>
    </form>
  </div>
</div>
