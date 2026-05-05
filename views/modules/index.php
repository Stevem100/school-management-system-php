<?php $pageTitle = $pageTitle ?? 'Modules'; ?>
<?php
    $modules = $modules ?? [];
    $editModule = $editModule ?? null;
    $search = $search ?? '';
    $pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modules</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure application modules and features</p>
    </div>
    <button onclick="openModal('create')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Module
    </button>
  </div>

  <!-- Search & Filter Bar -->
  <div class="flex flex-col sm:flex-row gap-3">
    <form method="GET" action="<?= url('/modules') ?>" class="relative flex-1">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search modules..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400">
    </form>
    <div class="flex gap-2">
      <a href="<?= url('/modules') ?>" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-colors <?= empty($search) ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' ?>">All</a>
      <a href="<?= url('/modules') ?>?status=active" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Active</a>
      <a href="<?= url('/modules') ?>?status=inactive" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Inactive</a>
    </div>
  </div>

  <!-- Module Cards Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    <?php foreach($modules as $item): ?>
    <?php
      $isActive = $item['is_active'] ?? true;
      $displayName = $item['display_name'] ?? $item['name'] ?? 'Untitled';
      $icon = $item['icon'] ?? '';
      $description = $item['description'] ?? '';
      $route = $item['route'] ?? '';
      $sortOrder = $item['sort_order'] ?? 0;

      $iconColors = [
        'students'  => 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400',
        'teachers'  => 'bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400',
        'classes'   => 'bg-violet-100 dark:bg-violet-900 text-violet-600 dark:text-violet-400',
        'exams'     => 'bg-rose-100 dark:bg-rose-900 text-rose-600 dark:text-rose-400',
        'fees'      => 'bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400',
        'attendance'=> 'bg-teal-100 dark:bg-teal-900 text-teal-600 dark:text-teal-400',
        'library'   => 'bg-orange-100 dark:bg-orange-900 text-orange-600 dark:text-orange-400',
        'transport' => 'bg-cyan-100 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-400',
        'hostel'    => 'bg-pink-100 dark:bg-pink-900 text-pink-600 dark:text-pink-400',
        'lms'       => 'bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400',
        'reports'   => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400',
        'default'   => 'bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400',
      ];

      $colorKey = $item['name'] ?? 'default';
      $colorClass = $iconColors[$colorKey] ?? $iconColors['default'];

      // SVG icons map
      $iconSvg = '';
      $nameLower = strtolower($item['name'] ?? '');
      if (strpos($nameLower, 'student') !== false) {
          $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>';
      } elseif (strpos($nameLower, 'exam') !== false) {
          $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>';
      } elseif (strpos($nameLower, 'fee') !== false || strpos($nameLower, 'payment') !== false) {
          $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
      } elseif (strpos($nameLower, 'attend') !== false) {
          $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>';
      } elseif (strpos($nameLower, 'class') !== false) {
          $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>';
      } elseif (strpos($nameLower, 'report') !== false) {
          $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
      } else {
          $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>';
      }
    ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-all group <?= !$isActive ? 'opacity-60' : '' ?>">
      <div class="flex items-start justify-between mb-3">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl <?= $colorClass ?> transition-transform group-hover:scale-110">
          <?php if (!empty($icon)): ?>
          <span class="text-lg"><?= e($icon) ?></span>
          <?php else: ?>
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $iconSvg ?></svg>
          <?php endif; ?>
        </div>
        <div class="flex items-center gap-1.5">
          <!-- Status Toggle -->
          <button onclick="toggleStatus('<?= e($item['id']) ?>', this)" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 <?= $isActive ? 'bg-emerald-600' : 'bg-gray-200 dark:bg-gray-700' ?>" title="<?= $isActive ? 'Active — click to deactivate' : 'Inactive — click to activate' ?>">
            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out <?= $isActive ? 'translate-x-5' : 'translate-x-0' ?>"></span>
          </button>
          <div class="relative" id="actions-menu-<?= e($item['id']) ?>">
            <button onclick="toggleActionsMenu('<?= e($item['id']) ?>')" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
            </button>
            <div id="dropdown-<?= e($item['id']) ?>" class="hidden absolute right-0 top-full mt-1 w-36 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg z-10 py-1">
              <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($item), ENT_QUOTES) ?>); toggleActionsMenu('<?= e($item['id']) ?>')" class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </button>
              <button onclick="confirmDelete('<?= e($item['id']) ?>', '<?= e($displayName) ?>'); toggleActionsMenu('<?= e($item['id']) ?>')" class="w-full text-left px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>

      <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1"><?= e($displayName) ?></h3>
      <?php if (!empty($description)): ?>
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2"><?= e($description) ?></p>
      <?php endif; ?>

      <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-2">
          <?php if ($isActive): ?>
          <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 text-[10px] font-medium text-emerald-700 dark:text-emerald-400">
            <span class="h-1 w-1 rounded-full bg-emerald-500"></span>
            Active
          </span>
          <?php else: ?>
          <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-[10px] font-medium text-gray-600 dark:text-gray-400">
            <span class="h-1 w-1 rounded-full bg-gray-400"></span>
            Inactive
          </span>
          <?php endif; ?>
        </div>
        <span class="text-[10px] text-gray-400 dark:text-gray-500">Sort: <?= $sortOrder ?></span>
      </div>

      <?php if (!empty($route)): ?>
      <div class="mt-2">
        <code class="text-[10px] text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-900 px-1.5 py-0.5 rounded">/<?= e($route) ?></code>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Empty State -->
  <?php if(empty($modules)): ?>
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center py-16">
    <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
    </svg>
    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No modules found</p>
    <p class="text-xs text-gray-400 dark:text-gray-500">Add a module to configure your system features</p>
  </div>
  <?php endif; ?>

  <!-- Pagination -->
  <?php if(isset($pagination) && $pagination['totalPages'] > 1): ?>
  <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <p class="text-sm text-gray-500 dark:text-gray-400">Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> modules</p>
    <div class="flex gap-1">
      <?php if($pagination['page'] > 1): ?>
      <a href="?page=<?= $pagination['page'] - 1 ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <?php endif; ?>
      <?php for($i = 1; $i <= $pagination['totalPages']; $i++): ?>
      <a href="?page=<?= $i ?>" class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors <?= $i == $pagination['page'] ? 'bg-emerald-600 text-white shadow-sm' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300' ?>"><?= $i ?></a>
      <?php endfor; ?>
      <?php if($pagination['page'] < $pagination['totalPages']): ?>
      <a href="?page=<?= $pagination['page'] + 1 ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Create/Edit Modal -->
  <div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)closeModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Add Module</h3>
        <button onclick="closeModal()" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="modal-form" method="POST" action="<?= url('/modules') ?>" class="p-6 space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="form-id">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Module Name <span class="text-red-500">*</span></label>
            <input type="text" id="form-name" name="name" required placeholder="e.g. students" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
            <p class="text-xs text-gray-400 mt-1">Internal identifier (snake_case)</p>
          </div>
          <div>
            <label for="form-display-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Display Name <span class="text-red-500">*</span></label>
            <input type="text" id="form-display-name" name="display_name" required placeholder="e.g. Student Management" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <div>
          <label for="form-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
          <textarea id="form-description" name="description" rows="2" placeholder="Brief description of the module" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-route" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Route <span class="text-red-500">*</span></label>
            <input type="text" id="form-route" name="route" required placeholder="e.g. /students" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Icon (emoji)</label>
            <input type="text" id="form-icon" name="icon" placeholder="e.g. 📚" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-sort-order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
            <input type="number" id="form-sort-order" name="sort_order" min="0" value="0" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <p class="text-xs text-gray-400 mt-1">Lower numbers appear first</p>
          </div>
          <div>
            <label for="form-status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select id="form-status" name="is_active" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
          <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">Save Module</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)closeDeleteModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-sm mx-4">
      <div class="p-6 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
          <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Module</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete "<span id="delete-module-name" class="font-medium"></span>"? This may affect routing and permissions.</p>
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
    function openModal(mode, data = null) {
      const modal = document.getElementById('modal');
      const title = document.getElementById('modal-title');
      const form = document.getElementById('modal-form');

      if (mode === 'edit' && data) {
        title.textContent = 'Edit Module';
        document.getElementById('form-id').value = data.id || '';
        document.getElementById('form-name').value = data.name || '';
        document.getElementById('form-display-name').value = data.display_name || '';
        document.getElementById('form-description').value = data.description || '';
        document.getElementById('form-route').value = data.route || '';
        document.getElementById('form-icon').value = data.icon || '';
        document.getElementById('form-sort-order').value = data.sort_order ?? 0;
        document.getElementById('form-status').value = data.is_active ? '1' : '0';
        form.action = '<?= url("/modules") ?>/' + data.id;
      } else {
        title.textContent = 'Add Module';
        form.reset();
        document.getElementById('form-id').value = '';
        document.getElementById('form-sort-order').value = 0;
        form.action = '<?= url("/modules") ?>';
      }

      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      document.getElementById('modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    function toggleStatus(id, btn) {
      const isActive = btn.classList.contains('bg-emerald-600');
      const newStatus = isActive ? '0' : '1';

      // Optimistic UI update
      btn.classList.toggle('bg-emerald-600', !isActive);
      btn.classList.toggle('bg-gray-200', isActive);
      btn.classList.toggle('dark:bg-gray-700', isActive);
      const span = btn.querySelector('span');
      span.classList.toggle('translate-x-5', !isActive);
      span.classList.toggle('translate-x-0', isActive);

      // Send request via form
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '<?= url("/modules") ?>/' + id;
      form.innerHTML = '<input type="hidden" name="_token" value="<?= csrf_token() ?>"><input type="hidden" name="is_active" value="' + newStatus + '"><input type="hidden" name="name" value="toggle"><input type="hidden" name="display_name" value="toggle"><input type="hidden" name="route" value="toggle">';
      document.body.appendChild(form);
      form.submit();
    }

    function toggleActionsMenu(id) {
      const dropdown = document.getElementById('dropdown-' + id);
      dropdown.classList.toggle('hidden');

      // Close on outside click
      function handleClick(e) {
        if (!document.getElementById('actions-menu-' + id).contains(e.target)) {
          dropdown.classList.add('hidden');
          document.removeEventListener('click', handleClick);
        }
      }
      setTimeout(() => document.addEventListener('click', handleClick), 0);
    }

    function confirmDelete(id, name) {
      document.getElementById('delete-module-name').textContent = name || 'this module';
      const deleteModal = document.getElementById('delete-modal');
      document.getElementById('delete-form').action = '<?= url("/modules") ?>/' + id + '/delete';
      deleteModal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeModal();
        closeDeleteModal();
      }
    });
  </script>
</div>
