<?php
$result = $result ?? [];
$students = $students ?? [];
$exams = $exams ?? [];
$classes = $classes ?? [];
$title = 'Create Result';
$formAction = url('/results');
?>

<div class="max-w-2xl mx-auto space-y-6">
  <div class="flex items-center gap-4">
    <a href="<?= url('/results') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Record student exam results</p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>
      <div class="p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student <span class="text-red-500">*</span></label>
            <select id="student_id" name="student_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select student</option>
              <?php foreach($students as $s): ?>
              <option value="<?= e($s['id']) ?>"><?= e(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? '') . ' (' . ($s['admission_no'] ?? '') . ')') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="exam_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Exam <span class="text-red-500">*</span></label>
            <select id="exam_id" name="exam_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select exam</option>
              <?php foreach($exams as $ex): ?>
              <option value="<?= e($ex['id']) ?>"><?= e($ex['name'] ?? '') ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="marks_obtained" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marks Obtained <span class="text-red-500">*</span></label>
            <input type="number" id="marks_obtained" name="marks_obtained" required min="0" value="<?= e($result['marks_obtained'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. 85">
          </div>
          <div>
            <label for="grade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grade</label>
            <select id="grade" name="grade" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select grade</option>
              <?php foreach(['A','A-','B+','B','B-','C+','C','C-','D+','D','E'] as $g): ?>
              <option value="<?= e($g) ?>" <?= ($result['grade'] ?? '') === $g ? 'selected' : '' ?>><?= e($g) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div>
          <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Remarks</label>
          <textarea id="remarks" name="remarks" rows="2" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none" placeholder="Optional remarks"><?= e($result['remarks'] ?? '') ?></textarea>
        </div>
      </div>
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/results') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Create Result</button>
      </div>
    </form>
  </div>
</div>
