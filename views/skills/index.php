<?php $pageTitle = $pageTitle ?? 'Skills'; ?>
<?php
    $skills = $skills ?? [];
    $skill = $skill ?? null;
    $search = $search ?? '';
    $category = $category ?? '';
    $status = $status ?? '';
    $total = $total ?? 0;
    $page = $page ?? 1;
    $perPage = $perPage ?? 25;
    $lastPage = $lastPage ?? 0;
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">CBC Skills</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage Competency-Based Curriculum skills, strands & sub-strands</p>
    </div>
    <button onclick="openModal('create')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Skill
    </button>
  </div>

  <!-- Search & Filters -->
  <div class="flex flex-col sm:flex-row gap-3">
    <form method="GET" action="<?= url('/skills') ?>" class="relative flex-1">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search skills by name..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400">
    </form>
    <select name="category" onchange="this.form.closest('form') || window.location.href='<?= url('/skills') ?>?category='+this.value" class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Categories</option>
      <option value="core_competency" <?= $category === 'core_competency' ? 'selected' : '' ?>>Core Competency</option>
      <option value="basic_literacy" <?= $category === 'basic_literacy' ? 'selected' : '' ?>>Basic Literacy</option>
      <option value="numeracy" <?= $category === 'numeracy' ? 'selected' : '' ?>>Numeracy</option>
      <option value="science" <?= $category === 'science' ? 'selected' : '' ?>>Science & Technology</option>
      <option value="creative_arts" <?= $category === 'creative_arts' ? 'selected' : '' ?>>Creative Arts</option>
      <option value="physical_education" <?= $category === 'physical_education' ? 'selected' : '' ?>>Physical Education</option>
      <option value="social_studies" <?= $category === 'social_studies' ? 'selected' : '' ?>>Social Studies</option>
      <option value="religious_moral" <?= $category === 'religious_moral' ? 'selected' : '' ?>>Religious & Moral</option>
    </select>
    <?php if (!empty($search) || !empty($category)): ?>
    <a href="<?= url('/skills') ?>" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
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
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Skill Name</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Code</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Strand</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden xl:table-cell">Sub-strand</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php foreach($skills as $item): ?>
          <?php
            $cat = $item['category'] ?? '';
            $categoryLabels = [
              'core_competency'     => ['Core Competency', 'bg-emerald-100 dark:bg-emerald-900/30', 'text-emerald-700 dark:text-emerald-400'],
              'basic_literacy'      => ['Basic Literacy', 'bg-blue-100 dark:bg-blue-900/30', 'text-blue-700 dark:text-blue-400'],
              'numeracy'            => ['Numeracy', 'bg-yellow-100 dark:bg-yellow-900/30', 'text-yellow-700 dark:text-yellow-400'],
              'science'             => ['Science & Tech', 'bg-purple-100 dark:bg-purple-900/30', 'text-purple-700 dark:text-purple-400'],
              'creative_arts'       => ['Creative Arts', 'bg-pink-100 dark:bg-pink-900/30', 'text-pink-700 dark:text-pink-400'],
              'physical_education'  => ['Physical Ed.', 'bg-orange-100 dark:bg-orange-900/30', 'text-orange-700 dark:text-orange-400'],
              'social_studies'      => ['Social Studies', 'bg-teal-100 dark:bg-teal-900/30', 'text-teal-700 dark:text-teal-400'],
              'religious_moral'     => ['Religious & Moral', 'bg-indigo-100 dark:bg-indigo-900/30', 'text-indigo-700 dark:text-indigo-400'],
            ];
            $catInfo = $categoryLabels[$cat] ?? ['Other', 'bg-gray-100 dark:bg-gray-700', 'text-gray-600 dark:text-gray-400'];
            $skillStatus = $item['status'] ?? 'active';
          ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
                  <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                  </svg>
                </div>
                <div>
                  <span class="text-sm font-medium text-gray-900 dark:text-white"><?= e($item['name'] ?? 'Untitled') ?></span>
                  <?php if (!empty($item['description'])): ?>
                  <p class="text-xs text-gray-400 dark:text-gray-500 truncate max-w-[200px]"><?= e($item['description']) ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hidden md:table-cell">
              <span class="inline-flex items-center rounded bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-700 dark:text-gray-300"><?= e($item['code'] ?? '') ?></span>
            </td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center rounded-full <?= $catInfo[1] ?> px-2 py-0.5 text-xs font-medium <?= $catInfo[2] ?>"><?= $catInfo[0] ?></span>
            </td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hidden lg:table-cell"><?= e($item['strand'] ?? '—') ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hidden xl:table-cell"><?= e($item['subStrand'] ?? $item['sub_strand'] ?? '—') ?></td>
            <td class="px-4 py-3">
              <?php if ($skillStatus === 'active'): ?>
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
    <?php if(empty($skills)): ?>
    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
      <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
      </svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No skills found</p>
      <p class="text-xs text-gray-400 dark:text-gray-500">Get started by adding your first CBC skill</p>
    </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if($lastPage > 1): ?>
    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-700">
      <p class="text-sm text-gray-500 dark:text-gray-400">Showing <span class="font-medium"><?= max(1, ($page - 1) * $perPage + 1) ?></span> to <span class="font-medium"><?= min($page * $perPage, $total) ?></span> of <span class="font-medium"><?= $total ?></span> skills</p>
      <div class="flex gap-1">
        <?php if($page > 1): ?>
        <a href="?page=<?= $page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($category) ? '&category=' . urlencode($category) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <?php endif; ?>
        <?php
          $start = max(1, $page - 2);
          $end = min($lastPage, $page + 2);
        ?>
        <?php for($i = $start; $i <= $end; $i++): ?>
        <a href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($category) ? '&category=' . urlencode($category) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors <?= $i == $page ? 'bg-emerald-600 text-white shadow-sm' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if($page < $lastPage): ?>
        <a href="?page=<?= $page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($category) ? '&category=' . urlencode($category) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
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
        <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Add Skill</h3>
        <button onclick="closeModal()" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="modal-form" method="POST" action="<?= url('/skills') ?>" class="p-6 space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="form-id">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Skill Name <span class="text-red-500">*</span></label>
            <input type="text" id="form-name" name="name" required placeholder="e.g. Critical Thinking" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code <span class="text-red-500">*</span></label>
            <input type="text" id="form-code" name="code" required placeholder="e.g. CC-001" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 uppercase">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category <span class="text-red-500">*</span></label>
            <select id="form-category" name="category" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select Category</option>
              <option value="core_competency">Core Competency</option>
              <option value="basic_literacy">Basic Literacy</option>
              <option value="numeracy">Numeracy</option>
              <option value="science">Science & Technology</option>
              <option value="creative_arts">Creative Arts</option>
              <option value="physical_education">Physical Education</option>
              <option value="social_studies">Social Studies</option>
              <option value="religious_moral">Religious & Moral</option>
            </select>
          </div>
          <div>
            <label for="form-status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select id="form-status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-strand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Strand</label>
            <input type="text" id="form-strand" name="strand" placeholder="e.g. Communication" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-sub_strand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sub-strand</label>
            <input type="text" id="form-sub_strand" name="sub_strand" placeholder="e.g. Listening" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <div>
          <label for="form-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
          <textarea id="form-description" name="description" rows="2" placeholder="Brief description of the skill" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"></textarea>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
          <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">Save Skill</button>
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
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Skill</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete this skill? This action cannot be undone.</p>
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
        title.textContent = 'Edit Skill';
        document.getElementById('form-id').value = data.id || '';
        document.getElementById('form-name').value = data.name || '';
        document.getElementById('form-code').value = data.code || '';
        document.getElementById('form-category').value = data.category || '';
        document.getElementById('form-strand').value = data.strand || '';
        document.getElementById('form-sub_strand').value = data.subStrand || data.sub_strand || '';
        document.getElementById('form-description').value = data.description || '';
        document.getElementById('form-status').value = data.status || 'active';
        form.action = '<?= url("/skills") ?>/' + data.id;
        form.method = 'POST';
      } else {
        title.textContent = 'Add Skill';
        form.reset();
        document.getElementById('form-id').value = '';
        form.action = '<?= url("/skills") ?>';
        form.method = 'POST';
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
      document.getElementById('delete-form').action = '<?= url("/skills") ?>/' + id + '/delete';
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
