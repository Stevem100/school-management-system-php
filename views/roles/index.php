<?php $pageTitle = $pageTitle ?? 'Roles & Permissions'; ?>
<?php
    $roles = $roles ?? [];
    $editRole = $editRole ?? null;
    $assignedPermIds = $assignedPermIds ?? [];
    $modules = $modules ?? [];
    $allPermissions = $allPermissions ?? [];
    $permissionsByModule = $permissionsByModule ?? [];
    $search = $search ?? '';
    $pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Roles & Permissions</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage user roles and their access permissions</p>
    </div>
    <button onclick="openModal('create')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Role
    </button>
  </div>

  <!-- Search Bar -->
  <div class="flex flex-col sm:flex-row gap-3">
    <form method="GET" action="<?= url('/roles') ?>" class="relative flex-1">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search roles..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400">
    </form>
    <?php if (!empty($search)): ?>
    <a href="<?= url('/roles') ?>" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      Clear
    </a>
    <?php endif; ?>
  </div>

  <!-- Roles Cards Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    <?php foreach($roles as $role): ?>
    <?php
      $scopeColors = [
        'global'   => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
        'school'   => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
        'branch'   => 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400',
        'personal' => 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400',
      ];
      $scope = $role['scope'] ?? 'global';
      $scopeClass = $scopeColors[$scope] ?? $scopeColors['global'];
      $permCount = count($role['permissions'] ?? []);
      $roleName = $role['display_name'] ?? $role['name'] ?? 'Untitled';
      $roleDesc = $role['description'] ?? '';
    ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow group">
      <div class="flex items-start justify-between mb-3">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800 transition-colors">
            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white"><?= e($roleName) ?></h3>
            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium mt-0.5 <?= $scopeClass ?>">
              <?= e(ucfirst($scope)) ?> Scope
            </span>
          </div>
        </div>
        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
          <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($role), ENT_QUOTES) ?>)" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Edit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          </button>
          <button onclick="confirmDelete('<?= e($role['id']) ?>', '<?= e($roleName) ?>')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          </button>
        </div>
      </div>

      <?php if (!empty($roleDesc)): ?>
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2"><?= e($roleDesc) ?></p>
      <?php endif; ?>

      <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          <span><?= $permCount ?> permission<?= $permCount !== 1 ? 's' : '' ?></span>
        </div>
        <span class="text-[10px] text-gray-400 dark:text-gray-500"><?= e($role['name'] ?? '') ?></span>
      </div>

      <!-- Permission preview (first 4) -->
      <?php if ($permCount > 0): ?>
      <div class="flex flex-wrap gap-1 mt-3">
        <?php foreach(array_slice($role['permissions'], 0, 4) as $perm): ?>
        <span class="inline-flex items-center rounded-md bg-gray-50 dark:bg-gray-900 px-2 py-0.5 text-[10px] font-medium text-gray-600 dark:text-gray-400"><?= e($perm['display_name'] ?? $perm['name'] ?? '') ?></span>
        <?php endforeach; ?>
        <?php if ($permCount > 4): ?>
        <span class="inline-flex items-center rounded-md bg-gray-50 dark:bg-gray-900 px-2 py-0.5 text-[10px] font-medium text-gray-500 dark:text-gray-500">+<?= $permCount - 4 ?> more</span>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Empty State -->
  <?php if(empty($roles)): ?>
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center py-16">
    <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
    </svg>
    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No roles found</p>
    <p class="text-xs text-gray-400 dark:text-gray-500">Create your first role to manage access control</p>
  </div>
  <?php endif; ?>

  <!-- Permission Matrix Section -->
  <?php if (!empty($roles) && !empty($permissionsByModule)): ?>
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
      <h2 class="text-base font-semibold text-gray-900 dark:text-white">Permission Matrix</h2>
      <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Overview of permissions assigned to each role</p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider sticky left-0 bg-gray-50 dark:bg-gray-900/50 min-w-[200px]">Permission</th>
            <?php foreach($roles as $role): ?>
            <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[100px]">
              <span class="block truncate max-w-[100px]" title="<?= e($role['display_name'] ?? $role['name']) ?>"><?= e($role['display_name'] ?? $role['name']) ?></span>
            </th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php foreach($permissionsByModule as $module => $perms): ?>
          <tr>
            <td colspan="<?= count($roles) + 1 ?>" class="px-4 py-2 bg-gray-50/50 dark:bg-gray-900/30">
              <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider"><?= e(ucfirst($module)) ?></span>
            </td>
          </tr>
          <?php foreach($perms as $perm): ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300 sticky left-0 bg-white dark:bg-gray-800"><?= e($perm['display_name'] ?? $perm['name']) ?></td>
            <?php foreach($roles as $role): ?>
            <?php
              $hasPerm = false;
              foreach ($role['permissions'] ?? [] as $rp) {
                if (isset($rp['id']) && $rp['id'] === $perm['id']) {
                  $hasPerm = true;
                  break;
                }
                if (isset($rp['name']) && $rp['name'] === $perm['name']) {
                  $hasPerm = true;
                  break;
                }
              }
            ?>
            <td class="px-3 py-2 text-center">
              <?php if ($hasPerm): ?>
              <svg class="w-4 h-4 mx-auto text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              <?php else: ?>
              <svg class="w-4 h-4 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              <?php endif; ?>
            </td>
            <?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- Pagination -->
  <?php if(isset($pagination) && $pagination['totalPages'] > 1): ?>
  <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-3">
    <p class="text-sm text-gray-500 dark:text-gray-400">Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> roles</p>
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
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Add Role</h3>
        <button onclick="closeModal()" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="modal-form" method="POST" action="<?= url('/roles') ?>" class="p-6 space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="form-id">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role Name <span class="text-red-500">*</span></label>
            <input type="text" id="form-name" name="name" required placeholder="e.g. school_admin" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
            <p class="text-xs text-gray-400 mt-1">Internal identifier (snake_case)</p>
          </div>
          <div>
            <label for="form-display-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Display Name <span class="text-red-500">*</span></label>
            <input type="text" id="form-display-name" name="display_name" required placeholder="e.g. School Admin" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <div>
          <label for="form-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
          <textarea id="form-description" name="description" rows="2" placeholder="Role description" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"></textarea>
        </div>

        <div>
          <label for="form-scope" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Scope</label>
          <select id="form-scope" name="scope" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="global">Global — Access to all schools</option>
            <option value="school">School — Limited to assigned school</option>
            <option value="branch">Branch — Limited to assigned branch</option>
            <option value="personal">Personal — Own data only</option>
          </select>
        </div>

        <!-- Permission Assignment -->
        <?php if (!empty($permissionsByModule)): ?>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permissions</label>
          <div class="space-y-3 max-h-64 overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <?php foreach($permissionsByModule as $module => $perms): ?>
            <div>
              <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"><?= e(ucfirst($module)) ?></p>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5">
                <?php foreach($perms as $perm): ?>
                <label class="flex items-center gap-2 cursor-pointer group">
                  <input type="checkbox" name="permission_ids[]" value="<?= e($perm['id']) ?>" class="h-3.5 w-3.5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                  <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate"><?= e($perm['display_name'] ?? $perm['name']) ?></span>
                </label>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php else: ?>
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 text-center">
          <p class="text-sm text-gray-400 dark:text-gray-500">No permissions available. Create permissions first.</p>
        </div>
        <?php endif; ?>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
          <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">Save Role</button>
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
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Role</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete the "<span id="delete-role-name" class="font-medium"></span>" role? Users with this role will lose their permissions.</p>
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

      form.querySelectorAll('input[name="permission_ids[]"]').forEach(cb => cb.checked = false);

      if (mode === 'edit' && data) {
        title.textContent = 'Edit Role';
        document.getElementById('form-id').value = data.id || '';
        document.getElementById('form-name').value = data.name || '';
        document.getElementById('form-display-name').value = data.display_name || '';
        document.getElementById('form-description').value = data.description || '';
        document.getElementById('form-scope').value = data.scope || 'global';
        form.action = '<?= url("/roles") ?>/' + data.id;

        // Check permissions that belong to this role
        const perms = data.permissions || [];
        form.querySelectorAll('input[name="permission_ids[]"]').forEach(cb => {
          if (perms.some(p => String(p.id) === String(cb.value) || p.name === cb.dataset.name)) {
            cb.checked = true;
          }
        });
      } else {
        title.textContent = 'Add Role';
        form.reset();
        document.getElementById('form-id').value = '';
        form.action = '<?= url("/roles") ?>';
      }

      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      document.getElementById('modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    function confirmDelete(id, name) {
      document.getElementById('delete-role-name').textContent = name || 'this';
      const deleteModal = document.getElementById('delete-modal');
      document.getElementById('delete-form').action = '<?= url("/roles") ?>/' + id + '/delete';
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
