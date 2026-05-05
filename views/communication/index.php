<?php $pageTitle = $pageTitle ?? 'Communication'; ?>
<?php
    $notifications = $notifications ?? [];
    $unreadCount = $unreadCount ?? 0;
    $pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Communication</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Send messages and manage notifications</p>
      </div>
      <?php if ($unreadCount > 0): ?>
      <span class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:text-red-400">
        <?= $unreadCount ?> unread
      </span>
      <?php endif; ?>
    </div>
  </div>

  <!-- Tabs -->
  <div class="border-b border-gray-200 dark:border-gray-700">
    <nav class="-mb-px flex gap-6" aria-label="Tabs">
      <button onclick="switchTab('inbox')" id="tab-inbox" class="tab-btn border-b-2 border-emerald-500 py-3 text-sm font-medium text-emerald-600 whitespace-nowrap">Inbox</button>
      <button onclick="switchTab('compose')" id="tab-compose" class="tab-btn border-b-2 border-transparent py-3 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 whitespace-nowrap">Compose</button>
      <button onclick="switchTab('notices')" id="tab-notices" class="tab-btn border-b-2 border-transparent py-3 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 whitespace-nowrap">Notices</button>
    </nav>
  </div>

  <!-- Two Column Layout -->
  <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

    <!-- Right Column: Notifications List (appears first on mobile) -->
    <div id="panel-inbox" class="tab-panel lg:col-span-3">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- List Header -->
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h2>
          <?php if ($unreadCount > 0): ?>
          <a href="<?= url('/communication') ?>?mark_all_read=1" class="text-xs font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">Mark all read</a>
          <?php endif; ?>
        </div>

        <!-- Notification List -->
        <div class="max-h-[600px] overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
          <?php foreach($notifications as $item): ?>
          <?php
            $priority = $item['priority'] ?? 'medium';
            $isRead = (bool) ($item['is_read'] ?? false);

            if ($priority === 'low') {
              $priorityBg = 'bg-gray-100 dark:bg-gray-700';
              $priorityText = 'text-gray-600 dark:text-gray-400';
              $priorityLabel = 'Low';
            } elseif ($priority === 'medium') {
              $priorityBg = 'bg-blue-100 dark:bg-blue-900/30';
              $priorityText = 'text-blue-700 dark:text-blue-400';
              $priorityLabel = 'Medium';
            } elseif ($priority === 'high') {
              $priorityBg = 'bg-orange-100 dark:bg-orange-900/30';
              $priorityText = 'text-orange-700 dark:text-orange-400';
              $priorityLabel = 'High';
            } else {
              $priorityBg = 'bg-red-100 dark:bg-red-900/30';
              $priorityText = 'text-red-700 dark:text-red-400';
              $priorityLabel = 'Urgent';
            }
          ?>
          <div onclick="markAsRead('<?= e($item['id']) ?>')" class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors cursor-pointer <?= !$isRead ? 'bg-emerald-50/50 dark:bg-emerald-900/10' : '' ?>">
            <div class="flex items-start gap-3">
              <!-- Unread Indicator -->
              <div class="mt-1.5 shrink-0">
                <?php if (!$isRead): ?>
                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 block"></span>
                <?php else: ?>
                <span class="h-2.5 w-2.5 rounded-full bg-gray-300 dark:bg-gray-600 block"></span>
                <?php endif; ?>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                  <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate <?= !$isRead ? 'font-semibold' : '' ?>"><?= e($item['title'] ?? 'No Subject') ?></h3>
                  <span class="inline-flex items-center rounded-full <?= $priorityBg ?> px-2 py-0.5 text-[10px] font-medium <?= $priorityText ?> shrink-0"><?= $priorityLabel ?></span>
                </div>
                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400 line-clamp-2"><?= e($item['message'] ?? '') ?></p>
                <div class="mt-1 flex items-center gap-2 text-[10px] text-gray-400 dark:text-gray-500">
                  <span><?= e($item['sender_id'] ? ('User #' . $item['sender_id']) : 'System') ?></span>
                  <span>&middot;</span>
                  <span><?= timeAgo($item['created_at'] ?? null) ?></span>
                </div>
              </div>
              <button onclick="event.stopPropagation(); confirmDelete('<?= e($item['id']) ?>')" class="p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors shrink-0" title="Delete">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
          </div>
          <?php endforeach; ?>

          <!-- Empty State -->
          <?php if(empty($notifications)): ?>
          <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No notifications</p>
            <p class="text-xs text-gray-400 dark:text-gray-500">Your inbox is empty</p>
          </div>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if(isset($pagination) && $pagination['totalPages'] > 1): ?>
        <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-700">
          <p class="text-xs text-gray-500 dark:text-gray-400">
            <?= $pagination['from'] ?>–<?= $pagination['to'] ?> of <?= $pagination['total'] ?>
          </p>
          <div class="flex gap-1">
            <?php if($pagination['page'] > 1): ?>
            <a href="?page=<?= $pagination['page'] - 1 ?>" class="px-2.5 py-1 text-xs rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">&laquo; Prev</a>
            <?php endif; ?>
            <?php if($pagination['page'] < $pagination['totalPages']): ?>
            <a href="?page=<?= $pagination['page'] + 1 ?>" class="px-2.5 py-1 text-xs rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">Next &raquo;</a>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Left Column: Send Notification Form -->
    <div id="panel-compose" class="tab-panel lg:col-span-3">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Send Notification</h2>
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Send a message or notification to recipients</p>
        </div>
        <form method="POST" action="<?= url('/communication/messages') ?>" class="p-6 space-y-4">
          <?= csrf_field() ?>

          <div>
            <label for="form-title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title <span class="text-red-500">*</span></label>
            <input type="text" id="form-title" name="title" required placeholder="Notification title" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>

          <div>
            <label for="form-message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message <span class="text-red-500">*</span></label>
            <textarea id="form-message" name="message" required rows="5" placeholder="Write your message here..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"></textarea>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="form-priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
              <select id="form-priority" name="priority" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
              </select>
            </div>
            <div>
              <label for="form-recipient_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recipient Type</label>
              <select id="form-recipient_type" name="recipient_type" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="individual">Individual</option>
                <option value="class">Class</option>
                <option value="all_students">All Students</option>
                <option value="all_parents">All Parents</option>
                <option value="all_staff">All Staff</option>
                <option value="everyone">Everyone</option>
              </select>
            </div>
          </div>

          <div id="recipient-id-field">
            <label for="form-recipient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recipient ID</label>
            <input type="text" id="form-recipient_id" name="recipient_id" placeholder="Enter recipient user ID" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>

          <div class="flex gap-3 pt-2">
            <button type="button" onclick="document.getElementById('form-message').value='';document.getElementById('form-title').value=''" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Clear</button>
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
              Send
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Notices Panel -->
    <div id="panel-notices" class="tab-panel lg:col-span-3">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
        </svg>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Public Notices</p>
        <p class="text-xs text-gray-400 dark:text-gray-500">School-wide announcements and notices</p>
      </div>
    </div>

    <!-- Sidebar Stats -->
    <div class="lg:col-span-2 hidden lg:block">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Quick Stats</h3>
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs text-gray-500 dark:text-gray-400">Unread</span>
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400"><?= $unreadCount ?></span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-xs text-gray-500 dark:text-gray-400">Total</span>
            <span class="text-xs font-medium text-gray-700 dark:text-gray-300"><?= $pagination['total'] ?? 0 ?></span>
          </div>
          <hr class="border-gray-100 dark:border-gray-700">
          <h4 class="text-xs font-semibold text-gray-900 dark:text-white">Priority Breakdown</h4>
          <div class="space-y-2">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                <span class="text-xs text-gray-500 dark:text-gray-400">Low</span>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                <span class="text-xs text-gray-500 dark:text-gray-400">Medium</span>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                <span class="text-xs text-gray-500 dark:text-gray-400">High</span>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                <span class="text-xs text-gray-500 dark:text-gray-400">Urgent</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)closeDeleteModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-sm mx-4">
      <div class="p-6 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
          <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Notification</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete this notification?</p>
        <div class="flex gap-3">
          <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
          <form id="delete-form" method="POST" class="flex-1">
            <?= csrf_field() ?>
            <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function switchTab(tab) {
      document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
      document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-emerald-500', 'text-emerald-600');
        b.classList.add('border-transparent', 'text-gray-500');
      });
      document.getElementById('panel-' + tab).classList.remove('hidden');
      const btn = document.getElementById('tab-' + tab);
      btn.classList.add('border-emerald-500', 'text-emerald-600');
      btn.classList.remove('border-transparent', 'text-gray-500');
    }

    function markAsRead(id) {
      fetch('<?= url("/communication") ?>/' + id + '/read', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
      }).then(() => {
        window.location.reload();
      }).catch(() => {});
    }

    function confirmDelete(id) {
      const deleteModal = document.getElementById('delete-modal');
      document.getElementById('delete-form').action = '<?= url("/communication") ?>/' + id + '/delete';
      deleteModal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    // Show/hide recipient ID based on type
    document.getElementById('form-recipient_type')?.addEventListener('change', function() {
      const field = document.getElementById('recipient-id-field');
      field.style.display = (this.value === 'individual') ? 'block' : 'none';
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeDeleteModal();
    });
  </script>
</div>
