<?php
$exam = $exam ?? [];
$subjects = $subjects ?? [];
$classes = $classes ?? [];
$title = 'Edit Exam';
$formAction = url('/exams/' . ($exam['id'] ?? ''));
$academicYears = ['2024-2025','2025-2026','2023-2024'];
$terms = ['Term 1','Term 2','Term 3'];
?>

<div class="max-w-2xl mx-auto space-y-6">
  <div class="flex items-center gap-4">
    <a href="<?= url('/exams') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update exam details below</p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Exam Name <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" required value="<?= e($exam['name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type <span class="text-red-500">*</span></label>
            <select id="type" name="type" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="" disabled <?= empty($exam['type']) ? 'selected' : '' ?>>Select type</option>
              <?php foreach(['quiz','test','midterm','final','practical'] as $t): ?>
              <option value="<?= e($t) ?>" <?= ($exam['type'] ?? '') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
            <select id="subject_id" name="subject_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select subject</option>
              <?php foreach($subjects as $s): ?>
              <option value="<?= e($s['id']) ?>" <?= ($exam['subject_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class</label>
            <select id="class_id" name="class_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select class</option>
              <?php foreach($classes as $c): ?>
              <option value="<?= e($c['id']) ?>" <?= ($exam['class_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="total_marks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Marks <span class="text-red-500">*</span></label>
            <input type="number" id="total_marks" name="total_marks" required min="1" value="<?= e($exam['total_marks'] ?? 100) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="passing_marks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Passing Marks <span class="text-red-500">*</span></label>
            <input type="number" id="passing_marks" name="passing_marks" required min="0" value="<?= e($exam['passing_marks'] ?? 50) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date <span class="text-red-500">*</span></label>
            <input type="date" id="start_date" name="start_date" required value="<?= e($exam['start_date'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date <span class="text-red-500">*</span></label>
            <input type="date" id="end_date" name="end_date" required value="<?= e($exam['end_date'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year</label>
            <select id="academic_year" name="academic_year" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($academicYears as $ay): ?>
              <option value="<?= e($ay) ?>" <?= ($exam['academic_year'] ?? '') === $ay ? 'selected' : '' ?>><?= e($ay) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="term" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Term</label>
            <select id="term" name="term" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($terms as $t): ?>
              <option value="<?= e($t) ?>" <?= ($exam['term'] ?? '') === $t ? 'selected' : '' ?>><?= e($t) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
          <select id="status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="active" <?= ($exam['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= ($exam['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
          </select>
        </div>
      </div>
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/exams') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Update Exam</button>
      </div>
    </form>
  </div>
</div>
