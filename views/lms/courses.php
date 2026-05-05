<?php $pageTitle = $pageTitle ?? 'Courses'; ?>
<?php $courses = $courses ?? []; ?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Courses</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage online courses</p>
    </div>
    <a href="<?= url('/lms/courses/create') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Create Course
    </a>
  </div>

  <!-- Courses Table -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Class</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Teacher</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Start Date</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $c): ?>
            <?php
              $status = $c['status'] ?? 'draft';
              if ($status === 'active') {
                $statusClass = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400';
                $statusDot = 'bg-emerald-500';
              } elseif ($status === 'archived') {
                $statusClass = 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400';
                $statusDot = 'bg-gray-400';
              } else {
                $statusClass = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400';
                $statusDot = 'bg-yellow-500';
              }
            ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
              <td class="px-4 py-3">
                <div class="font-medium text-gray-900 dark:text-white"><?= e($c['title'] ?? 'Untitled') ?></div>
                <?php if (!empty($c['description'])): ?>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[250px] mt-0.5"><?= e($c['description']) ?></div>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden md:table-cell"><?= e($c['class_name'] ?? $c['class_id'] ?? '—') ?></td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden sm:table-cell"><?= e($c['teacher_name'] ?? $c['teacher_id'] ?? '—') ?></td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center gap-1.5 rounded-full <?= $statusClass ?> px-2.5 py-1 text-xs font-medium">
                  <span class="h-1.5 w-1.5 rounded-full <?= $statusDot ?>"></span>
                  <?= ucfirst(e($status)) ?>
                </span>
              </td>
              <td class="px-4 py-3 text-gray-500 dark:text-gray-400 hidden lg:table-cell"><?= formatDate($c['start_date'] ?? null) ?></td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-1">
                  <a href="<?= url('/lms/courses/' . ($c['id'] ?? '') . '/edit') ?>" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </a>
                  <form method="POST" action="<?= url('/lms/courses/' . ($c['id'] ?? '') . '/delete') ?>" onsubmit="return confirm('Are you sure you want to delete this course?')" class="inline">
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
              <td colspan="6" class="px-4 py-16 text-center">
                <svg class="mx-auto w-16 h-16 mb-4 text-gray-300 dark:text-gray-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No courses found</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Get started by creating your first course</p>
                <a href="<?= url('/lms/courses/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-sm font-medium">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  Create Course
                </a>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
