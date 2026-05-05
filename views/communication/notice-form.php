<?php $pageTitle = $pageTitle ?? 'Create Notice'; ?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Notice</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Publish a new school announcement</p>
    </div>
    <a href="<?= url('/communication/notices') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to Notices
    </a>
  </div>

  <!-- Notice Form -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= url('/communication/notices') ?>">
      <?= csrf_field() ?>

      <div class="p-6 space-y-5">
        <!-- Title -->
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notice Title <span class="text-red-500">*</span></label>
          <input type="text" id="title" name="title" required placeholder="e.g. School Holiday Notice" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>

        <!-- Content -->
        <div>
          <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content <span class="text-red-500">*</span></label>
          <textarea id="content" name="content" required rows="6" placeholder="Write the full notice content here..." class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"></textarea>
        </div>

        <!-- Type & Target -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="notice_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notice Type</label>
            <select id="notice_type" name="notice_type" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="general">General</option>
              <option value="urgent">Urgent</option>
              <option value="event">Event</option>
            </select>
          </div>
          <div>
            <label for="target_audience" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Audience</label>
            <select id="target_audience" name="target_audience" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="all">Everyone</option>
              <option value="teachers">Teachers</option>
              <option value="students">Students</option>
              <option value="parents">Parents</option>
            </select>
          </div>
        </div>

        <!-- Priority & Expires -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
            <select id="priority" name="priority" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="low">Low</option>
              <option value="normal" selected>Normal</option>
              <option value="high">High</option>
            </select>
          </div>
          <div>
            <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expires At</label>
            <input type="date" id="expires_at" name="expires_at" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:justify-end gap-3">
        <a href="<?= url('/communication/notices') ?>" class="px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-center">
          Cancel
        </a>
        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
          Publish Notice
        </button>
      </div>
    </form>
  </div>
</div>
