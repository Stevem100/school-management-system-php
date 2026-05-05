<?php $pageTitle = $pageTitle ?? 'Branches'; ?>
<?php
    $branches = $branches ?? [];
    $branch = $branch ?? null;
    $schools = $schools ?? [];
    $schoolFilter = $schoolFilter ?? '';
    $search = $search ?? '';
    $pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Branches</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage school branches and campuses</p>
    </div>
    <button onclick="openModal('create')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Branch
    </button>
  </div>

  <!-- Search & Filter Bar -->
  <div class="flex flex-col sm:flex-row gap-3">
    <form method="GET" action="<?= url('/branches') ?>" class="relative flex-1">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search branches..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400">
    </form>
    <select name="school_id" onchange="window.location.href='<?= url('/branches') ?>?school_id='+this.value" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Schools</option>
      <?php foreach($schools as $s): ?>
      <option value="<?= e($s['id']) ?>" <?= $schoolFilter == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Data Table -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Branch Name</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">School</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Address</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Phone</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php foreach($branches as $item): ?>
          <?php
            // Fetch school name for this branch
            $branchSchool = null;
            if (!empty($item['school_id'])) {
                foreach ($schools as $s) {
                    if ($s['id'] === $item['school_id']) {
                        $branchSchool = $s;
                        break;
                    }
                }
            }
          ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
                  <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                  </svg>
                </div>
                <span class="text-sm font-medium text-gray-900 dark:text-white"><?= e($item['name']) ?></span>
              </div>
            </td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
              <span class="inline-flex items-center rounded bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-700 dark:text-gray-300"><?= e($item['code']) ?></span>
            </td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hidden md:table-cell"><?= e($branchSchool['name'] ?? '—') ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hidden lg:table-cell max-w-xs truncate"><?= e($item['address'] ?? '—') ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hidden sm:table-cell"><?= e($item['phone'] ?? '—') ?></td>
            <td class="px-4 py-3">
              <?php $status = $item['status'] ?? 'active'; ?>
              <?php if ($status === 'active'): ?>
              <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Active
              </span>
              <?php else: ?>
              <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 dark:bg-gray-700 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:text-gray-400">
                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                Inactive
              </span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 text-right">
              <div class="flex items-center justify-end gap-1">
                <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($item), ENT_QUOTES) ?>)" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="confirmDelete('<?= e($item['id']) ?>')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete">
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
    <?php if(empty($branches)): ?>
    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
      <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
      </svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No branches found</p>
      <p class="text-xs text-gray-400 dark:text-gray-500">Add a branch to get started</p>
    </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if(isset($pagination) && $pagination['totalPages'] > 1): ?>
    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-700">
      <p class="text-sm text-gray-500 dark:text-gray-400">Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> branches</p>
      <div class="flex gap-1">
        <?php if($pagination['page'] > 1): ?>
        <a href="?page=<?= $pagination['page'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($schoolFilter) ? '&school_id=' . urlencode($schoolFilter) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <?php endif; ?>
        <?php
          $start = max(1, $pagination['page'] - 2);
          $end = min($pagination['totalPages'], $pagination['page'] + 2);
        ?>
        <?php for($i = $start; $i <= $end; $i++): ?>
        <a href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($schoolFilter) ? '&school_id=' . urlencode($schoolFilter) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors <?= $i == $pagination['page'] ? 'bg-emerald-600 text-white shadow-sm' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if($pagination['page'] < $pagination['totalPages']): ?>
        <a href="?page=<?= $pagination['page'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($schoolFilter) ? '&school_id=' . urlencode($schoolFilter) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Create/Edit Modal -->
  <div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)closeModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Add Branch</h3>
        <button onclick="closeModal()" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="modal-form" method="POST" action="<?= url('/branches') ?>" class="p-6 space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="form-id">

        <div>
          <label for="form-school-id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School <span class="text-red-500">*</span></label>
          <select id="form-school-id" name="school_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="">Select a school</option>
            <?php foreach($schools as $s): ?>
            <option value="<?= e($s['id']) ?>"><?= e($s['name']) ?> (<?= e($s['code']) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Branch Name <span class="text-red-500">*</span></label>
            <input type="text" id="form-name" name="name" required placeholder="e.g. Main Campus" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Branch Code <span class="text-red-500">*</span></label>
            <input type="text" id="form-code" name="code" required placeholder="e.g. MC01" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 uppercase">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input type="email" id="form-email" name="email" placeholder="branch@school.com" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
            <input type="text" id="form-phone" name="phone" placeholder="+254 700 000 000" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <div>
          <label for="form-address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
          <textarea id="form-address" name="address" rows="2" placeholder="Branch address" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"></textarea>
        </div>

        <div>
          <label for="form-status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
          <select id="form-status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
          <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">Save Branch</button>
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
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Branch</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete this branch? This action cannot be undone.</p>
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
        title.textContent = 'Edit Branch';
        document.getElementById('form-id').value = data.id || '';
        document.getElementById('form-school-id').value = data.school_id || '';
        document.getElementById('form-name').value = data.name || '';
        document.getElementById('form-code').value = data.code || '';
        document.getElementById('form-email').value = data.email || '';
        document.getElementById('form-phone').value = data.phone || '';
        document.getElementById('form-status').value = data.status || 'active';
        document.getElementById('form-address').value = data.address || '';
        form.action = '<?= url("/branches") ?>/' + data.id;
      } else {
        title.textContent = 'Add Branch';
        form.reset();
        document.getElementById('form-id').value = '';
        form.action = '<?= url("/branches") ?>';
      }

      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      document.getElementById('modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    function confirmDelete(id) {
      const deleteModal = document.getElementById('delete-modal');
      document.getElementById('delete-form').action = '<?= url("/branches") ?>/' + id + '/delete';
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
