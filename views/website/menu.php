<?php $pageTitle = $pageTitle ?? 'Menu Manager'; ?>
<?php $currentPage = $currentPage ?? 'website'; ?>
<?php
    $menuItems = $menuItems ?? [];
    $pages = $pages ?? [];
    $errors = $errors ?? [];
    $old = $old ?? [];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Menu Manager</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage website navigation menus</p>
    </div>
    <a href="<?= url('/website') ?>" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Back to Website
    </a>
  </div>

  <!-- Add Menu Item Form -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </div>
        <div>
          <h3 class="text-base font-semibold text-gray-900 dark:text-white">Add Menu Item</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">Create a new navigation menu item</p>
        </div>
      </div>
    </div>
    <form method="POST" action="<?= url('/website/menu') ?>" class="p-6">
      <?= csrf_field() ?>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
          <label for="menu-label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Label <span class="text-red-500">*</span></label>
          <input type="text" id="menu-label" name="label" value="<?= e($old['label'] ?? '') ?>" required placeholder="e.g. About Us" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>
        <div>
          <label for="menu-url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL</label>
          <input type="text" id="menu-url" name="url" value="<?= e($old['url'] ?? '') ?>" placeholder="e.g. /about or https://..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 font-mono">
          <p class="text-xs text-gray-400 mt-1">Or select a page below</p>
        </div>
        <div>
          <label for="menu-page-id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link to Page</label>
          <select id="menu-page-id" name="page_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="">-- Select a page --</option>
            <?php foreach ($pages as $p): ?>
            <option value="<?= e($p['id'] ?? '') ?>" <?= ($old['page_id'] ?? '') == ($p['id'] ?? '') ? 'selected' : '' ?>><?= e($p['title'] ?? 'Untitled') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="menu-parent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parent Item</label>
          <select id="menu-parent" name="parent_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="">-- None (Top Level) --</option>
            <?php foreach ($menuItems as $mi): ?>
            <option value="<?= e($mi['id'] ?? '') ?>" <?= ($old['parent_id'] ?? '') == ($mi['id'] ?? '') ? 'selected' : '' ?>><?= e($mi['label'] ?? '') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="menu-target" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target</label>
          <select id="menu-target" name="target" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="_self" <?= ($old['target'] ?? '_self') === '_self' ? 'selected' : '' ?>>Same Window (_self)</option>
            <option value="_blank" <?= ($old['target'] ?? '') === '_blank' ? 'selected' : '' ?>>New Window (_blank)</option>
          </select>
        </div>
        <div>
          <label for="menu-position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Position</label>
          <select id="menu-position" name="position" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="main" <?= ($old['position'] ?? 'main') === 'main' ? 'selected' : '' ?>>Main Navigation</option>
            <option value="footer" <?= ($old['position'] ?? '') === 'footer' ? 'selected' : '' ?>>Footer</option>
          </select>
        </div>
      </div>

      <div class="flex justify-end mt-4">
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          Add Menu Item
        </button>
      </div>
    </form>
  </div>

  <!-- Menu Items Table -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">All Menu Items</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-10">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
            </th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Label</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">URL / Page</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Position</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Target</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sort</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php foreach($menuItems as $item): ?>
          <?php
            $isActive = !empty($item['is_active']);
            $position = $item['position'] ?? 'main';
            $target = $item['target'] ?? '_self';
            $sortOrder = $item['sort_order'] ?? 0;
            $itemUrl = $item['url'] ?? ($item['page_slug'] ? '/' . $item['page_slug'] : '#');
            $pageName = $item['page_title'] ?? null;
          ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <!-- Drag Handle -->
            <td class="px-4 py-3">
              <div class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-grab active:cursor-grabbing">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
              </div>
            </td>
            <!-- Label -->
            <td class="px-4 py-3">
              <span class="text-sm font-medium text-gray-900 dark:text-white"><?= e($item['label'] ?? 'Untitled') ?></span>
            </td>
            <!-- URL / Page -->
            <td class="px-4 py-3 hidden sm:table-cell">
              <?php if ($pageName): ?>
              <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-xs text-gray-600 dark:text-gray-300"><?= e($pageName) ?></span>
              </div>
              <?php else: ?>
              <code class="text-xs bg-gray-50 dark:bg-gray-900 px-1.5 py-0.5 rounded text-gray-500 dark:text-gray-400 max-w-[200px] inline-block truncate"><?= e($itemUrl) ?></code>
              <?php endif; ?>
            </td>
            <!-- Position -->
            <td class="px-4 py-3 hidden md:table-cell">
              <?php if ($position === 'main'): ?>
              <span class="inline-flex items-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400">Main</span>
              <?php else: ?>
              <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-400">Footer</span>
              <?php endif; ?>
            </td>
            <!-- Target -->
            <td class="px-4 py-3 hidden lg:table-cell">
              <?php if ($target === '_blank'): ?>
              <span class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                New Window
              </span>
              <?php else: ?>
              <span class="text-xs text-gray-400">Same</span>
              <?php endif; ?>
            </td>
            <!-- Sort Order -->
            <td class="px-4 py-3">
              <form method="POST" action="<?= url('/website/menu/' . ($item['id'] ?? '') . '/sort') ?>" class="inline-flex">
                <?= csrf_field() ?>
                <input type="number" name="sort_order" value="<?= $sortOrder ?>" min="0" max="999" class="w-16 px-2 py-1 border border-gray-200 dark:border-gray-600 rounded text-xs text-center bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              </form>
            </td>
            <!-- Active -->
            <td class="px-4 py-3">
              <button onclick="toggleMenuActive('<?= e($item['id'] ?? '') ?>', this)" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 <?= $isActive ? 'bg-emerald-600' : 'bg-gray-200 dark:bg-gray-700' ?>" title="<?= $isActive ? 'Active — click to deactivate' : 'Inactive — click to activate' ?>">
                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out <?= $isActive ? 'translate-x-4' : 'translate-x-0' ?>"></span>
              </button>
            </td>
            <!-- Actions -->
            <td class="px-4 py-3 text-right">
              <div class="flex items-center justify-end gap-1">
                <button onclick="openEditModal(<?= htmlspecialchars(json_encode($item), ENT_QUOTES) ?>)" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="confirmDelete('<?= e($item['id'] ?? '') ?>', '<?= e($item['label'] ?? 'this item') ?>')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Empty State -->
    <?php if(empty($menuItems)): ?>
    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
      <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No menu items yet</p>
      <p class="text-xs text-gray-400 dark:text-gray-500">Add your first menu item above</p>
    </div>
    <?php endif; ?>
  </div>

  <!-- Edit Modal -->
  <div id="edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)closeEditModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Menu Item</h3>
        <button onclick="closeEditModal()" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="edit-form" method="POST" class="p-6 space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" id="edit-id" name="id">

        <div>
          <label for="edit-label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Label <span class="text-red-500">*</span></label>
          <input type="text" id="edit-label" name="label" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>
        <div>
          <label for="edit-url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL</label>
          <input type="text" id="edit-url" name="url" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 font-mono">
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label for="edit-target" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target</label>
            <select id="edit-target" name="target" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="_self">Same Window</option>
              <option value="_blank">New Window</option>
            </select>
          </div>
          <div>
            <label for="edit-position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Position</label>
            <select id="edit-position" name="position" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="main">Main Nav</option>
              <option value="footer">Footer</option>
            </select>
          </div>
          <div>
            <label for="edit-sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
            <input type="number" id="edit-sort" name="sort_order" min="0" max="999" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>

        <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
          <div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Active</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Show or hide this menu item</p>
          </div>
          <label class="relative inline-flex cursor-pointer items-center">
            <input type="checkbox" id="edit-active" name="is_active" value="1" class="peer sr-only">
            <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
          </label>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
          <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">Update</button>
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
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Menu Item</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete "<span id="delete-item-name" class="font-medium"></span>"?</p>
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
    function openEditModal(data) {
      document.getElementById('edit-id').value = data.id || '';
      document.getElementById('edit-label').value = data.label || '';
      document.getElementById('edit-url').value = data.url || '';
      document.getElementById('edit-target').value = data.target || '_self';
      document.getElementById('edit-position').value = data.position || 'main';
      document.getElementById('edit-sort').value = data.sort_order ?? 0;
      document.getElementById('edit-active').checked = !!data.is_active;
      document.getElementById('edit-form').action = '<?= url("/website/menu") ?>/' + data.id;
      document.getElementById('edit-modal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
      document.getElementById('edit-modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    function confirmDelete(id, name) {
      document.getElementById('delete-item-name').textContent = name || 'this item';
      document.getElementById('delete-form').action = '<?= url("/website/menu") ?>/' + id + '/delete';
      document.getElementById('delete-modal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    function toggleMenuActive(id, btn) {
      const isActive = btn.classList.contains('bg-emerald-600');
      const newStatus = isActive ? 0 : 1;

      btn.classList.toggle('bg-emerald-600', !isActive);
      btn.classList.toggle('bg-gray-200', isActive);
      btn.classList.toggle('dark:bg-gray-700', isActive);
      const span = btn.querySelector('span');
      span.classList.toggle('translate-x-4', !isActive);
      span.classList.toggle('translate-x-0', isActive);

      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '<?= url("/website/menu") ?>/' + id + '/toggle';
      form.innerHTML = '<input type="hidden" name="_token" value="<?= csrf_token() ?>"><input type="hidden" name="_method" value="PUT"><input type="hidden" name="is_active" value="' + newStatus + '">';
      document.body.appendChild(form);
      form.submit();
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeEditModal();
        closeDeleteModal();
      }
    });
  </script>
</div>
