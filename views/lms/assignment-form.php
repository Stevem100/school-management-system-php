<?php $pageTitle = $pageTitle ?? 'Create Assignment'; ?>
<?php $courses = $courses ?? []; ?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Assignment</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add a new course assignment</p>
    </div>
    <a href="<?= url('/lms/assignments') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to Assignments
    </a>
  </div>

  <!-- Assignment Form -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= url('/lms/assignments') ?>">
      <?= csrf_field() ?>

      <div class="p-6 space-y-5">
        <!-- Title -->
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assignment Title <span class="text-red-500">*</span></label>
          <input type="text" id="title" name="title" required placeholder="e.g. Chapter 5 Homework" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>

        <!-- Course -->
        <div>
          <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course <span class="text-red-500">*</span></label>
          <select id="course_id" name="course_id" required class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="">Select Course</option>
            <?php foreach ($courses as $c): ?>
              <option value="<?= e($c['id']) ?>"><?= e($c['title'] ?? $c['name'] ?? "Course #{$c['id']}") ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
          <textarea id="description" name="description" rows="4" placeholder="Assignment instructions, requirements, and guidelines..." class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"></textarea>
        </div>

        <!-- Due Date & Total Marks -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date <span class="text-red-500">*</span></label>
            <input type="date" id="due_date" name="due_date" required class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="total_marks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Marks <span class="text-red-500">*</span></label>
            <input type="number" id="total_marks" name="total_marks" required min="1" placeholder="e.g. 100" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <!-- Status -->
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
          <select id="status" name="status" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="draft">Draft</option>
            <option value="active" selected>Active</option>
            <option value="closed">Closed</option>
          </select>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:justify-end gap-3">
        <a href="<?= url('/lms/assignments') ?>" class="px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-center">
          Cancel
        </a>
        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
          Create Assignment
        </button>
      </div>
    </form>
  </div>
</div>
