<?php
$module = $module ?? [];
$classes = $classes ?? [];
$title = 'Create Module';
$formAction = url('/modules');

$creditOptions = [1, 2, 3, 4, 5, 6, 8, 10];
$types = ['core' => 'Core', 'elective' => 'Elective', 'optional' => 'Optional'];
$categories = ['Sciences' => 'Sciences', 'Mathematics' => 'Mathematics', 'Languages' => 'Languages', 'Humanities' => 'Humanities', 'Technical' => 'Technical', 'Creative Arts' => 'Creative Arts', 'Physical Education' => 'Physical Education', 'Other' => 'Other'];
?>

<div class="max-w-2xl mx-auto space-y-6">
  <!-- Page Header -->
  <div class="flex items-center gap-4">
    <a href="<?= url('/modules') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create a new learning module or subject</p>
    </div>
  </div>

  <!-- Form Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>

      <div class="p-6 space-y-5">
        <!-- Module Details -->
        <div class="flex items-center gap-2 mb-1">
          <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
          </div>
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Module Details</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
          <div class="sm:col-span-2">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Module Name <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" required value="<?= e($module['name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400" placeholder="e.g. Mathematics, English, Physics">
          </div>
          <div>
            <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Module Code <span class="text-red-500">*</span></label>
            <input type="text" id="code" name="code" required value="<?= e($module['code'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400" placeholder="e.g. MAT-101">
          </div>
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
            <select id="category" name="category" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select category</option>
              <?php foreach ($categories as $k => $v): ?>
              <option value="<?= e($k) ?>" <?= ($module['category'] ?? '') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
            <select id="type" name="type" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach ($types as $k => $v): ?>
              <option value="<?= e($k) ?>" <?= ($module['type'] ?? 'core') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="credits" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Credits</label>
            <select id="credits" name="credits" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach ($creditOptions as $c): ?>
              <option value="<?= $c ?>" <?= ($module['credits'] ?? 3) == $c ? 'selected' : '' ?>><?= $c ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
          <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none" placeholder="Describe the module content and objectives"><?= e($module['description'] ?? '') ?></textarea>
        </div>

        <!-- Classes Assignment -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
          <div class="flex items-center gap-2 mb-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
              <svg class="h-4 w-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Assign to Classes</h2>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            <?php foreach ($classes as $c): ?>
              <?php
                $assignedClasses = is_array($module['classes'] ?? null) ? $module['classes'] : [];
                $isChecked = in_array($c['id'], $assignedClasses);
              ?>
              <label class="flex items-center gap-2 p-2.5 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                <input type="checkbox" name="class_ids[]" value="<?= e($c['id']) ?>" <?= $isChecked ? 'checked' : '' ?> class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900">
                <span class="text-sm text-gray-700 dark:text-gray-300"><?= e($c['name']) ?></span>
              </label>
            <?php endforeach; ?>
            <?php if (empty($classes)): ?>
              <p class="col-span-full text-sm text-gray-400 dark:text-gray-500">No classes available. Please create a class first.</p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Status -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
          <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="active" <?= ($module['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
              <option value="draft" <?= ($module['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
              <option value="archived" <?= ($module['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/modules') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
          <span class="inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Module
          </span>
        </button>
      </div>
    </form>
  </div>
</div>
