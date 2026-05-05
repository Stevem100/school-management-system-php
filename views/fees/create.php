<?php
$fee = $fee ?? [];
$classes = $classes ?? [];
$title = 'Create Fee Structure';
$formAction = url('/fees');
$terms = ['Term 1','Term 2','Term 3'];
$academicYears = ['2024-2025','2025-2026','2023-2024'];
?>

<div class="max-w-2xl mx-auto space-y-6">
  <div class="flex items-center gap-4">
    <a href="<?= url('/fees') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Define fee structure for a class</p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>
      <div class="p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class <span class="text-red-500">*</span></label>
            <select id="class_id" name="class_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select class</option>
              <?php foreach($classes as $c): ?>
              <option value="<?= e($c['id']) ?>"><?= e($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="term" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Term <span class="text-red-500">*</span></label>
            <select id="term" name="term" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($terms as $t): ?>
              <option value="<?= e($t) ?>"><?= e($t) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year <span class="text-red-500">*</span></label>
            <select id="academic_year" name="academic_year" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($academicYears as $ay): ?>
              <option value="<?= e($ay) ?>"><?= e($ay) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="total_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Amount (KES) <span class="text-red-500">*</span></label>
            <input type="number" id="total_amount" name="total_amount" required min="0" step="100" value="<?= e($fee['total_amount'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. 45000">
          </div>
        </div>
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
          <textarea id="description" name="description" rows="2" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none" placeholder="Brief description of the fee structure"><?= e($fee['description'] ?? '') ?></textarea>
        </div>
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
          <select id="status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="active" <?= ($fee['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="draft" <?= ($fee['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="archived" <?= ($fee['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
          </select>
        </div>
      </div>
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/fees') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Create Fee Structure</button>
      </div>
    </form>
  </div>
</div>
