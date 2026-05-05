<?php $pageTitle = $pageTitle ?? ($mode === 'edit' ? 'Edit Page' : 'Create Page'); ?>
<?php $currentPage = $currentPage ?? 'website'; ?>
<?php
    $page = $page ?? null;
    $mode = $mode ?? 'create';
    $isEdit = $mode === 'edit' && $page !== null;
    $templates = $templates ?? ['default' => 'Default', 'full-width' => 'Full Width', 'sidebar' => 'With Sidebar', 'landing' => 'Landing Page'];
    $existingPages = $existingPages ?? [];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <div class="flex items-center gap-2">
        <a href="<?= url('/website/pages') ?>" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $isEdit ? 'Edit Page' : 'Create Page' ?></h1>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= $isEdit ? 'Update page content and settings' : 'Add a new page to your website' ?></p>
        </div>
      </div>
    </div>
  </div>

  <form method="POST" action="<?= $isEdit ? url('/website/pages/' . $page['id']) : url('/website/pages') ?>" class="space-y-6">
    <?= csrf_field() ?>
    <?php if ($isEdit): ?>
    <input type="hidden" name="_method" value="PUT">
    <?php endif; ?>

    <!-- Content Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
            <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Page Content</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Main page details and body content</p>
          </div>
        </div>
      </div>
      <div class="p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title <span class="text-red-500">*</span></label>
            <input type="text" id="title" name="title" value="<?= e($page['title'] ?? '') ?>" required placeholder="e.g. About Us" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL Slug <span class="text-red-500">*</span></label>
            <input type="text" id="slug" name="slug" value="<?= e($page['slug'] ?? '') ?>" required placeholder="e.g. about-us" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 font-mono">
            <p class="text-xs text-gray-400 mt-1">Auto-generated from title</p>
          </div>
        </div>

        <div>
          <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content</label>
          <textarea id="content" name="content" rows="12" placeholder="Write your page content here. HTML is supported." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-y"><?= e($page['content'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <!-- SEO & Meta Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </div>
          <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">SEO & Meta</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Search engine optimization settings</p>
          </div>
        </div>
      </div>
      <div class="p-6 space-y-4">
        <div>
          <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Title</label>
          <input type="text" id="meta_title" name="meta_title" value="<?= e($page['meta_title'] ?? '') ?>" placeholder="Override the page title for search engines" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          <p class="text-xs text-gray-400 mt-1">Leave blank to use page title</p>
        </div>
        <div>
          <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Description</label>
          <textarea id="meta_description" name="meta_description" rows="3" placeholder="A brief description for search engine results (150-160 characters)" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"><?= e($page['meta_description'] ?? '') ?></textarea>
        </div>
      </div>
    </div>

    <!-- Page Options -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
            <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
          </div>
          <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Page Options</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Configure display, template, and behavior</p>
          </div>
        </div>
      </div>
      <div class="p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="template" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Template</label>
            <select name="template" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach ($templates as $tplKey => $tplLabel): ?>
              <option value="<?= e($tplKey) ?>" <?= ($page['template'] ?? 'default') === $tplKey ? 'selected' : '' ?>><?= e($tplLabel) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="draft" <?= ($page['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
              <option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="featured_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Featured Image URL</label>
            <input type="text" name="featured_image" value="<?= e($page['featured_image'] ?? '') ?>" placeholder="https://example.com/image.jpg" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="menu_label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Menu Label</label>
            <input type="text" name="menu_label" value="<?= e($page['menu_label'] ?? '') ?>" placeholder="e.g. About" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
            <p class="text-xs text-gray-400 mt-1">Display name in navigation (leave blank to use title)</p>
          </div>
        </div>

        <!-- Checkboxes -->
        <div class="space-y-3 pt-2">
          <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Show in Menu</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">Add this page to the website navigation menu</p>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
              <input type="checkbox" name="show_in_menu" value="1" class="peer sr-only" <?= (!empty($page['show_in_menu'])) ? 'checked' : '' ?>>
              <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
            </label>
          </div>

          <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Set as Homepage</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">Make this page the default homepage</p>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
              <input type="checkbox" name="is_homepage" value="1" class="peer sr-only" <?= (!empty($page['is_homepage'])) ? 'checked' : '' ?>>
              <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
            </label>
          </div>
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
      <a href="<?= url('/website/pages') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Pages
      </a>
      <div class="flex gap-3">
        <button type="submit" name="status" value="draft" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
          Save Draft
        </button>
        <button type="submit" name="status" value="published" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          <?= $isEdit ? 'Update Page' : 'Publish Page' ?>
        </button>
      </div>
    </div>
  </form>
</div>

<script>
  // Auto-generate slug from title
  const titleInput = document.getElementById('title');
  const slugInput = document.getElementById('slug');

  titleInput.addEventListener('input', function() {
    // Only auto-generate if slug hasn't been manually edited
    if (!slugInput.dataset.manual) {
      slugInput.value = this.value
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_]+/g, '-')
        .replace(/--+/g, '-')
        .replace(/^-+|-+$/g, '');
    }
  });

  slugInput.addEventListener('input', function() {
    this.dataset.manual = 'true';
  });
</script>
