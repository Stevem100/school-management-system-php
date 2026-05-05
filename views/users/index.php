<?php $pageTitle = $pageTitle ?? 'Users'; ?>
<?php
    $users = $users ?? [];
    $editUser = $editUser ?? null;
    $assignedRoleIds = $assignedRoleIds ?? [];
    $schools = $schools ?? [];
    $allRoles = $allRoles ?? [];
    $schoolFilter = $schoolFilter ?? '';
    $search = $search ?? '';
    $pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage system users, roles, and access control</p>
    </div>
    <button onclick="openModal('create')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add User
    </button>
  </div>

  <!-- Search & Filter Bar -->
  <div class="flex flex-col sm:flex-row gap-3">
    <form method="GET" action="<?= url('/users') ?>" class="relative flex-1">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search by name or email..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400">
    </form>
    <?php if (!empty($schools) && empty($schoolFilter)): ?>
    <select name="school_id" onchange="window.location.href='<?= url('/users') ?>?school_id='+this.value" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Schools</option>
      <?php foreach($schools as $s): ?>
      <option value="<?= e($s['id']) ?>"><?= e($s['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <?php endif; ?>
    <?php if (!empty($search)): ?>
    <a href="<?= url('/users') ?>" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      Clear
    </a>
    <?php endif; ?>
  </div>

  <!-- Data Table -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Email</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Roles</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">School</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Branch</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden xl:table-cell">Last Login</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php foreach($users as $item): ?>
          <?php
            $fullName = e(trim(($item['first_name'] ?? '') . ' ' . ($item['last_name'] ?? '')));
            $initials = strtoupper(substr($item['first_name'] ?? 'U', 0, 1) . substr($item['last_name'] ?? '', 0, 1));
            $isActive = $item['is_active'] ?? true;
            $roleNames = $item['roleNames'] ?? [];
          ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <?php if (!empty($item['avatar'])): ?>
                <img src="<?= e($item['avatar']) ?>" alt="<?= $fullName ?>" class="w-8 h-8 rounded-full object-cover">
                <?php else: ?>
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 text-xs font-semibold text-emerald-700 dark:text-emerald-300"><?= $initials ?></div>
                <?php endif; ?>
                <div>
                  <p class="text-sm font-medium text-gray-900 dark:text-white"><?= $fullName ?></p>
                  <?php if (empty($roleNames)): ?>
                  <p class="text-xs text-gray-400 dark:text-gray-500 sm:hidden"><?= e($item['email'] ?? '') ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hidden sm:table-cell"><?= e($item['email'] ?? '—') ?></td>
            <td class="px-4 py-3 hidden md:table-cell">
              <div class="flex flex-wrap gap-1">
                <?php if (!empty($roleNames)): ?>
                  <?php foreach(array_slice($roleNames, 0, 2) as $rn): ?>
                  <span class="inline-flex items-center rounded-full bg-violet-100 dark:bg-violet-900/30 px-2 py-0.5 text-xs font-medium text-violet-700 dark:text-violet-400"><?= e($rn) ?></span>
                  <?php endforeach; ?>
                  <?php if (count($roleNames) > 2): ?>
                  <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-600 dark:text-gray-400">+<?= count($roleNames) - 2 ?></span>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="text-xs text-gray-400 dark:text-gray-500">No roles</span>
                <?php endif; ?>
              </div>
            </td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hidden lg:table-cell"><?= e($item['schoolName'] ?? '—') ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hidden lg:table-cell"><?= e($item['branchName'] ?? '—') ?></td>
            <td class="px-4 py-3">
              <?php if ($isActive): ?>
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
            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 hidden xl:table-cell">
              <?php if (!empty($item['last_login'])): ?>
              <?= formatDate($item['last_login'], 'M d, Y H:i') ?>
              <?php else: ?>
              <span class="text-gray-400 dark:text-gray-500">Never</span>
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
    <?php if(empty($users)): ?>
    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
      <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
      </svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No users found</p>
      <p class="text-xs text-gray-400 dark:text-gray-500">Add your first user to get started</p>
    </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if(isset($pagination) && $pagination['totalPages'] > 1): ?>
    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-700">
      <p class="text-sm text-gray-500 dark:text-gray-400">Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> users</p>
      <div class="flex gap-1">
        <?php if($pagination['page'] > 1): ?>
        <a href="?page=<?= $pagination['page'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <?php endif; ?>
        <?php
          $start = max(1, $pagination['page'] - 2);
          $end = min($pagination['totalPages'], $pagination['page'] + 2);
        ?>
        <?php for($i = $start; $i <= $end; $i++): ?>
        <a href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors <?= $i == $pagination['page'] ? 'bg-emerald-600 text-white shadow-sm' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if($pagination['page'] < $pagination['totalPages']): ?>
        <a href="?page=<?= $pagination['page'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Create/Edit Modal -->
  <div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)closeModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Add User</h3>
        <button onclick="closeModal()" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="modal-form" method="POST" action="<?= url('/users') ?>" class="p-6 space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="form-id">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-first-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name <span class="text-red-500">*</span></label>
            <input type="text" id="form-first-name" name="first_name" required placeholder="John" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-last-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name <span class="text-red-500">*</span></label>
            <input type="text" id="form-last-name" name="last_name" required placeholder="Doe" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" id="form-email" name="email" required placeholder="john@example.com" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
            <input type="text" id="form-phone" name="phone" placeholder="+254 700 000 000" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-school-id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School</label>
            <select id="form-school-id" name="school_id" onchange="loadBranches(this.value)" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select school</option>
              <?php foreach($schools as $s): ?>
              <option value="<?= e($s['id']) ?>"><?= e($s['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="form-branch-id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Branch</label>
            <select id="form-branch-id" name="branch_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select branch</option>
            </select>
          </div>
        </div>

        <div id="password-field">
          <label for="form-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password <span class="text-red-500">*</span></label>
          <input type="password" id="form-password" name="password" <?= ($editUser) ? '' : 'required' ?> placeholder="<?= ($editUser) ? 'Leave blank to keep current' : 'Minimum 8 characters' ?>" minlength="8" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>

        <div>
          <label for="form-status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
          <select id="form-status" name="is_active" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>

        <!-- Role Assignment -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Roles</label>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-40 overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 p-3">
            <?php if (!empty($allRoles)): ?>
              <?php foreach($allRoles as $role): ?>
              <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="role_ids[]" value="<?= e($role['id']) ?>" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors"><?= e($role['display_name'] ?? $role['name']) ?></span>
              </label>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="col-span-3 text-sm text-gray-400 dark:text-gray-500">No roles available. Create roles first.</p>
            <?php endif; ?>
          </div>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
          <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">Save User</button>
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
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete User</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
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
    const editRoleIds = <?= json_encode($assignedRoleIds) ?>;

    function openModal(mode, data = null) {
      const modal = document.getElementById('modal');
      const title = document.getElementById('modal-title');
      const form = document.getElementById('modal-form');

      // Reset role checkboxes
      form.querySelectorAll('input[name="role_ids[]"]').forEach(cb => cb.checked = false);

      if (mode === 'edit' && data) {
        title.textContent = 'Edit User';
        document.getElementById('form-id').value = data.id || '';
        document.getElementById('form-first-name').value = data.first_name || '';
        document.getElementById('form-last-name').value = data.last_name || '';
        document.getElementById('form-email').value = data.email || '';
        document.getElementById('form-phone').value = data.phone || '';
        document.getElementById('form-school-id').value = data.school_id || '';
        document.getElementById('form-password').value = '';
        document.getElementById('form-password').required = false;
        document.getElementById('form-password').placeholder = 'Leave blank to keep current';
        document.getElementById('form-status').value = data.is_active ? '1' : '0';
        form.action = '<?= url("/users") ?>/' + data.id;

        // Load branches for the selected school
        if (data.school_id) {
          loadBranches(data.school_id, data.branch_id);
        }

        // Check assigned roles (would be populated server-side for edit)
        // For now this is handled via assignedRoleIds from the edit page
      } else {
        title.textContent = 'Add User';
        form.reset();
        document.getElementById('form-id').value = '';
        document.getElementById('form-password').required = true;
        document.getElementById('form-password').placeholder = 'Minimum 8 characters';
        form.action = '<?= url("/users") ?>';
        document.getElementById('form-branch-id').innerHTML = '<option value="">Select branch</option>';
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
      document.getElementById('delete-form').action = '<?= url("/users") ?>/' + id + '/delete';
      deleteModal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    function loadBranches(schoolId, selectedBranchId) {
      const branchSelect = document.getElementById('form-branch-id');
      branchSelect.innerHTML = '<option value="">Loading...</option>';

      if (!schoolId) {
        branchSelect.innerHTML = '<option value="">Select branch</option>';
        return;
      }

      fetch('<?= url("/branches") ?>?school_id=' + schoolId + '&per_page=100')
        .then(r => r.text())
        .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          const rows = doc.querySelectorAll('tbody tr');
          branchSelect.innerHTML = '<option value="">Select branch</option>';
          rows.forEach(row => {
            const name = row.querySelector('td:nth-child(1) span')?.textContent?.trim() || '';
            const onclick = row.querySelector('button[onclick*="openModal"]')?.getAttribute('onclick') || '';
            const match = onclick.match(/openModal\('edit',\s*(\{[^}]+\})/);
            if (match) {
              try {
                const data = JSON.parse(match[1]);
                const option = document.createElement('option');
                option.value = data.id || '';
                option.textContent = name;
                if (data.id === selectedBranchId) option.selected = true;
                branchSelect.appendChild(option);
              } catch(e) {}
            }
          });
        })
        .catch(() => {
          branchSelect.innerHTML = '<option value="">Select branch</option>';
        });
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeModal();
        closeDeleteModal();
      }
    });
  </script>
</div>
