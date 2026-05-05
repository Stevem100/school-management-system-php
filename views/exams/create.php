<?php
$exam = $exam ?? [];
$classes = $classes ?? [];
$subjects = $subjects ?? [];
$title = 'Create Exam';
$formAction = url('/exes');

$types = ['mid_term' => 'Mid-Term', 'end_term' => 'End of Term', 'final' => 'Final Exam', 'quiz' => 'Quiz', 'cat' => 'Continuous Assessment', 'practical' => 'Practical', 'mock' => 'Mock Exam', 'other' => 'Other'];
$statuses = ['draft' => 'Draft', 'scheduled' => 'Scheduled', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
$academicYears = ['2024-2025','2025-2026','2023-2024'];
$terms = ['Term 1','Term 2','Term 3'];
?>

<div class="max-w-2xl mx-auto space-y-6">
  <!-- Page Header -->
  <div class="flex items-center gap-4">
    <a href="<?= url('/exams') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create a new exam or assessment</p>
    </div>
  </div>

  <!-- Form Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>

      <div class="p-6 space-y-5">
        <!-- Exam Details -->
        <div class="flex items-center gap-2 mb-1">
          <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
            <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          </div>
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Exam Details</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
          <div class="sm:col-span-2">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Exam Name <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" required value="<?= e($exam['name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400" placeholder="e.g. Form 4 Mid-Term Mathematics Exam">
          </div>
          <div>
            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Exam Type <span class="text-red-500">*</span></label>
            <select id="type" name="type" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select type</option>
              <?php foreach ($types as $k => $v): ?>
              <option value="<?= e($k) ?>" <?= ($exam['type'] ?? '') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject <span class="text-red-500">*</span></label>
            <select id="subject_id" name="subject_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select subject</option>
              <?php foreach ($subjects as $s): ?>
              <option value="<?= e($s['id']) ?>" <?= ($exam['subject_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= e($s['name'] ?? $s['code'] ?? $s['id']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Schedule -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
          <div class="flex items-center gap-2 mb-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
              <svg class="h-4 w-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Schedule & Duration</h2>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date <span class="text-red-500">*</span></label>
              <input type="datetime-local" id="start_date" name="start_date" required value="<?= e($exam['start_date'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
              <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date <span class="text-red-500">*</span></label>
              <input type="datetime-local" id="end_date" name="end_date" required value="<?= e($exam['end_date'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
              <label for="duration_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration (minutes)</label>
              <input type="number" id="duration_minutes" name="duration_minutes" min="5" value="<?= e($exam['duration_minutes'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. 120">
            </div>
            <div>
              <label for="total_marks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Marks</label>
              <input type="number" id="total_marks" name="total_marks" min="1" value="<?= e($exam['total_marks'] ?? 100) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. 100">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
            <div>
              <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class <span class="text-red-500">*</span></label>
              <select id="class_id" name="class_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">Select class</option>
                <?php foreach ($classes as $c): ?>
                <option value="<?= e($c['id']) ?>" <?= ($exam['class_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label for="room" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Exam Room</label>
              <input type="text" id="room" name="room" value="<?= e($exam['room'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400" placeholder="e.g. Hall A, Room 12">
            </div>
          </div>
        </div>

        <!-- Academic Context -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
          <div class="flex items-center gap-2 mb-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
              <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Academic Context</h2>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year</label>
              <select id="academic_year" name="academic_year" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <?php foreach ($academicYears as $ay): ?>
                <option value="<?= e($ay) ?>" <?= ($exam['academic_year'] ?? '') === $ay ? 'selected' : '' ?>><?= e($ay) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label for="term" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Term</label>
              <select id="term" name="term" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <?php foreach ($terms as $t): ?>
                <option value="<?= e($t) ?>" <?= ($exam['term'] ?? '') === $t ? 'selected' : '' ?>><?= e($t) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <!-- Notes -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
          <div>
            <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Instructions / Notes</label>
            <textarea id="instructions" name="instructions" rows="3" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none" placeholder="Special instructions for students taking this exam..."><?= e($exam['instructions'] ?? '') ?></textarea>
          </div>
        </div>

        <!-- Status -->
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
          <select id="status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <?php foreach ($statuses as $k => $v): ?>
            <option value="<?= e($k) ?>" <?= ($exam['status'] ?? 'draft') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/exams') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
          <span class="inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Exam
          </span>
        </button>
      </div>
    </form>
  </div>
</div>
