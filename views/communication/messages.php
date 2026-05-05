<?php $pageTitle = $pageTitle ?? 'Messages'; ?>
<?php $messages = $messages ?? []; ?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Messages</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Internal messaging</p>
    </div>
    <a href="<?= url('/communication/messages/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
      Compose
    </a>
  </div>

  <!-- Messages List -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <?php if (!empty($messages)): ?>
    <div class="max-h-[600px] overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
      <?php foreach ($messages as $m): ?>
      <?php
        $isRead = (bool) ($m['is_read'] ?? false);
      ?>
      <a href="<?= url('/communication/messages/' . ($m['id'] ?? '')) ?>" class="block px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors <?= !$isRead ? 'bg-emerald-50/50 dark:bg-emerald-900/10' : '' ?>">
        <div class="flex items-start gap-3">
          <!-- Unread Indicator -->
          <div class="mt-2 shrink-0">
            <?php if (!$isRead): ?>
            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 block"></span>
            <?php else: ?>
            <span class="h-2.5 w-2.5 rounded-full bg-transparent block"></span>
            <?php endif; ?>
          </div>

          <!-- Avatar -->
          <div class="h-10 w-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-sm font-semibold text-emerald-700 dark:text-emerald-400 shrink-0">
            <?= strtoupper(mb_substr($m['sender_name'] ?? $m['from'] ?? 'U', 0, 1)) ?>
          </div>

          <!-- Content -->
          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-2">
              <h3 class="text-sm <?= !$isRead ? 'font-semibold' : 'font-medium' ?> text-gray-900 dark:text-white truncate"><?= e($m['sender_name'] ?? $m['from'] ?? 'Unknown') ?></h3>
              <span class="text-xs text-gray-400 dark:text-gray-500 shrink-0"><?= timeAgo($m['created_at'] ?? null) ?></span>
            </div>
            <p class="text-sm <?= !$isRead ? 'font-semibold text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300' ?> truncate mt-0.5"><?= e($m['subject'] ?? 'No Subject') ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5"><?= e($m['content'] ?? $m['message'] ?? '') ?></p>
          </div>

          <!-- Status Badge -->
          <?php if (!$isRead): ?>
          <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 text-[10px] font-medium text-emerald-700 dark:text-emerald-400 shrink-0">New</span>
          <?php endif; ?>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($pagination) && ($pagination['totalPages'] ?? 0) > 1): ?>
    <div class="flex items-center justify-between px-5 py-3 border-t border-gray-100 dark:border-gray-700">
      <p class="text-xs text-gray-500 dark:text-gray-400">
        Showing <?= $pagination['from'] ?? 0 ?>–<?= $pagination['to'] ?? 0 ?> of <?= $pagination['total'] ?? 0 ?>
      </p>
      <div class="flex gap-1">
        <?php if (($pagination['page'] ?? 1) > 1): ?>
        <a href="?page=<?= ($pagination['page'] ?? 1) - 1 ?>" class="px-2.5 py-1 text-xs rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">&laquo; Prev</a>
        <?php endif; ?>
        <?php if (($pagination['page'] ?? 1) < ($pagination['totalPages'] ?? 1)): ?>
        <a href="?page=<?= ($pagination['page'] ?? 1) + 1 ?>" class="px-2.5 py-1 text-xs rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">Next &raquo;</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center py-16">
      <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
      </svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No messages</p>
      <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Your inbox is empty</p>
      <a href="<?= url('/communication/messages/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Compose Message
      </a>
    </div>
    <?php endif; ?>
  </div>
</div>
