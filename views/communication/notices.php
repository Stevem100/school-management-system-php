<?php $pageTitle = $pageTitle ?? 'Notices'; ?>
<?php $notices = $notices ?? []; ?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notices</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">School announcements</p>
    </div>
    <a href="<?= url('/communication/notices/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Create Notice
    </a>
  </div>

  <!-- Notices Table -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Type</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Priority</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Target</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Published At</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php if (!empty($notices)): ?>
            <?php foreach ($notices as $n): ?>
            <?php
              $type = $n['notice_type'] ?? $n['type'] ?? 'general';
              $priority = $n['priority'] ?? 'normal';
              $target = $n['target_audience'] ?? $n['target'] ?? 'all';
              $status = $n['status'] ?? 'published';

              // Type badge
              if ($type === 'urgent') {
                $typeClass = 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400';
              } elseif ($type === 'event') {
                $typeClass = 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400';
              } else {
                $typeClass = 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400';
              }

              // Priority badge
              if ($priority === 'high') {
                $priorityClass = 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400';
                $priorityDot = 'bg-orange-500';
              } elseif ($priority === 'low') {
                $priorityClass = 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400';
                $priorityDot = 'bg-gray-400';
              } else {
                $priorityClass = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400';
                $priorityDot = 'bg-yellow-500';
              }

              // Target label
              $targetLabels = [
                'all' => 'Everyone',
                'teachers' => 'Teachers',
                'students' => 'Students',
                'parents' => 'Parents',
              ];
              $targetLabel = $targetLabels[$target] ?? ucfirst($target);

              // Status badge
              if ($status === 'published') {
                $statusClass = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400';
              } elseif ($status === 'draft') {
                $statusClass = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400';
              } else {
                $statusClass = 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400';
              }
            ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
              <td class="px-4 py-3">
                <div class="font-medium text-gray-900 dark:text-white"><?= e($n['title'] ?? 'Untitled') ?></div>
                <?php if (!empty($n['content'])): ?>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[250px] mt-0.5"><?= e($n['content']) ?></div>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3 hidden md:table-cell">
                <span class="inline-flex items-center rounded-full <?= $typeClass ?> px-2.5 py-1 text-xs font-medium"><?= ucfirst(e($type)) ?></span>
              </td>
              <td class="px-4 py-3 hidden sm:table-cell">
                <span class="inline-flex items-center gap-1.5 rounded-full <?= $priorityClass ?> px-2.5 py-1 text-xs font-medium">
                  <span class="h-1.5 w-1.5 rounded-full <?= $priorityDot ?>"></span>
                  <?= ucfirst(e($priority)) ?>
                </span>
              </td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden lg:table-cell"><?= e($targetLabel) ?></td>
              <td class="px-4 py-3 text-gray-500 dark:text-gray-400 hidden lg:table-cell"><?= !empty($n['published_at']) ? formatDate($n['published_at']) : (!empty($n['created_at']) ? formatDate($n['created_at']) : '—') ?></td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center rounded-full <?= $statusClass ?> px-2.5 py-1 text-xs font-medium"><?= ucfirst(e($status)) ?></span>
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-1">
                  <a href="<?= url('/communication/notices/' . ($n['id'] ?? '') . '/edit') ?>" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </a>
                  <form method="POST" action="<?= url('/communication/notices/' . ($n['id'] ?? '') . '/delete') ?>" onsubmit="return confirm('Are you sure you want to delete this notice?')" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="px-4 py-16 text-center">
                <svg class="mx-auto w-16 h-16 mb-4 text-gray-300 dark:text-gray-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No notices found</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Create a notice to share school announcements</p>
                <a href="<?= url('/communication/notices/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  Create Notice
                </a>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
