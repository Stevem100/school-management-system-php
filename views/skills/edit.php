<?php
$skill = $skill ?? [];
$title = 'Edit Skill';
$formAction = url('/skills/' . ($skill['id'] ?? ''));
$levels = ['Beginner','Intermediate','Advanced','Expert'];
$categories = ['Communication','Problem Solving','Critical Thinking','Leadership','Technical','Creative'];
?>

<div class="max-w-2xl mx-auto space-y-6">
  <div class="flex items-center gap-4">
    <a href="<?= url('/skills') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update skill details below</p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Skill Name <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" required value="<?= e($skill['name'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code <span class="text-red-500">*</span></label>
            <input type="text" id="code" name="code" required value="<?= e($skill['code'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent uppercase">
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
            <select id="category" name="category" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select category</option>
              <?php foreach($categories as $c): ?>
              <option value="<?= e($c) ?>" <?= ($skill['category'] ?? '') === $c ? 'selected' : '' ?>><?= e($c) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Level</label>
            <select id="level" name="level" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($levels as $l): ?>
              <option value="<?= e($l) ?>" <?= ($skill['level'] ?? '') === $l ? 'selected' : '' ?>><?= e($l) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="strand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Strand</label>
            <input type="text" id="strand" name="strand" value="<?= e($skill['strand'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="sub_strand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sub-strand</label>
            <input type="text" id="sub_strand" name="sub_strand" value="<?= e($skill['sub_strand'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
          <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"><?= e($skill['description'] ?? '') ?></textarea>
        </div>
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
          <select id="status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="active" <?= ($skill['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= ($skill['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
          </select>
        </div>
      </div>
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/skills') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Update Skill</button>
      </div>
    </form>
  </div>
</div>
