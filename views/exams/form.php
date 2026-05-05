<?php
$exam = $exam ?? null;
$subjects = $subjects ?? [];
$classes = $classes ?? [];
$mode = $mode ?? 'create';

$isEdit = ($mode === 'edit' && $exam !== null);
$title = $isEdit ? 'Edit Exam' : 'Create Exam';
$formAction = $isEdit ? url('exams/' . $exam['id']) : url('exams');
?>

<div class="max-w-2xl mx-auto space-y-6">
  <!-- Page Header -->
  <div class="flex items-center gap-4">
    <a href="<?= url('exams') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= $isEdit ? 'Update exam details below' : 'Schedule a new exam' ?></p>
    </div>
  </div>

  <!-- Form Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>
      <div class="p-6 space-y-5">
        <!-- Exam Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Exam Name <span class="text-red-500">*</span></label>
          <input type="text" id="name" name="name" required value="<?= e($exam['name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. Term 1 Mathematics Exam">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Exam Type -->
          <div>
            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type <span class="text-red-500">*</span></label>
            <select id="type" name="type" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select type</option>
              <option value="term" <?= ($exam['type'] ?? '') === 'term' ? 'selected' : '' ?>>Term Exam</option>
              <option value="midterm" <?= ($exam['type'] ?? '') === 'midterm' ? 'selected' : '' ?>>Midterm</option>
              <option value="final" <?= ($exam['type'] ?? '') === 'final' ? 'selected' : '' ?>>Final</option>
              <option value="quiz" <?= ($exam['type'] ?? '') === 'quiz' ? 'selected' : '' ?>>Quiz</option>
              <option value="assignment" <?= ($exam['type'] ?? '') === 'assignment' ? 'selected' : '' ?>>Assignment</option>
              <option value="practical" <?= ($exam['type'] ?? '') === 'practical' ? 'selected' : '' ?>>Practical</option>
            </select>
          </div>

          <!-- Status -->
          <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="upcoming" <?= ($exam['status'] ?? 'upcoming') === 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
              <option value="ongoing" <?= ($exam['status'] ?? '') === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
              <option value="completed" <?= ($exam['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
              <option value="cancelled" <?= ($exam['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Subject -->
          <div>
            <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject <span class="text-red-500">*</span></label>
            <select id="subject_id" name="subject_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select subject</option>
              <?php foreach($subjects as $subj): ?>
              <option value="<?= e($subj['id'] ?? '') ?>" <?= isset($exam['subjectId']) && $exam['subjectId'] == $subj['id'] ? 'selected' : '' ?>><?= e($subj['name'] ?? '') ?> (<?= e($subj['code'] ?? '') ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Class -->
          <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class <span class="text-red-500">*</span></label>
            <select id="class_id" name="class_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select class</option>
              <?php foreach($classes as $cls): ?>
              <option value="<?= e($cls['id'] ?? '') ?>" <?= isset($exam['classId']) && $exam['classId'] == $cls['id'] ? 'selected' : '' ?>><?= e($cls['name'] ?? '') ?> <?= e($cls['section'] ?? '') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Total Marks -->
          <div>
            <label for="total_marks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Marks <span class="text-red-500">*</span></label>
            <input type="number" id="total_marks" name="total_marks" required min="1" step="0.5" value="<?= e($exam['totalMarks'] ?? 100) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. 100">
          </div>

          <!-- Passing Marks -->
          <div>
            <label for="passing_marks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pass Marks <span class="text-red-500">*</span></label>
            <input type="number" id="passing_marks" name="passing_marks" required min="0" step="0.5" value="<?= e($exam['passingMarks'] ?? 50) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. 50">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Start Date -->
          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date <span class="text-red-500">*</span></label>
            <input type="datetime-local" id="start_date" name="start_date" required value="<?= e(isset($exam['startDate']) ? str_replace(' ', 'T', $exam['startDate']) : '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>

          <!-- End Date -->
          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date <span class="text-red-500">*</span></label>
            <input type="datetime-local" id="end_date" name="end_date" required value="<?= e(isset($exam['endDate']) ? str_replace(' ', 'T', $exam['endDate']) : '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>

        <!-- Academic Year & Term -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year</label>
            <input type="text" id="academic_year" name="academic_year" value="<?= e($exam['academicYear'] ?? date('Y')) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. 2025">
          </div>
          <div>
            <label for="term" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Term</label>
            <select id="term" name="term" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="Term 1" <?= ($exam['term'] ?? '') === 'Term 1' ? 'selected' : '' ?>>Term 1</option>
              <option value="Term 2" <?= ($exam['term'] ?? '') === 'Term 2' ? 'selected' : '' ?>>Term 2</option>
              <option value="Term 3" <?= ($exam['term'] ?? '') === 'Term 3' ? 'selected' : '' ?>>Term 3</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('exams') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm"><?= $isEdit ? 'Update Exam' : 'Create Exam' ?></button>
      </div>
    </form>
  </div>
</div>
