<?php $pageTitle = $pageTitle ?? 'Compose Message'; ?>
<?php $users = $users ?? []; ?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Compose Message</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Send a new message</p>
    </div>
    <a href="<?= url('/communication/messages') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to Messages
    </a>
  </div>

  <!-- Message Form -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= url('/communication/messages') ?>">
      <?= csrf_field() ?>

      <div class="p-6 space-y-5">
        <!-- Recipient -->
        <div>
          <label for="recipient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recipient <span class="text-red-500">*</span></label>
          <select id="recipient_id" name="recipient_id" required class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="">Select Recipient</option>
            <?php foreach ($users as $u): ?>
              <option value="<?= e($u['id']) ?>"><?= e($u['name'] ?? $u['first_name'] . ' ' . ($u['last_name'] ?? '') . ' (' . ($u['role'] ?? 'User') . ')') ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Subject -->
        <div>
          <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject <span class="text-red-500">*</span></label>
          <input type="text" id="subject" name="subject" required placeholder="Message subject" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>

        <!-- Content -->
        <div>
          <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message <span class="text-red-500">*</span></label>
          <textarea id="content" name="content" required rows="8" placeholder="Write your message here..." class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"></textarea>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:justify-end gap-3">
        <a href="<?= url('/communication/messages') ?>" class="px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-center">
          Cancel
        </a>
        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm inline-flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
          Send Message
        </button>
      </div>
    </form>
  </div>
</div>
