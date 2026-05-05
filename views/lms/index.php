<?php $pageTitle = $pageTitle ?? 'Learning Management'; ?>
<?php
    $courses = $courses ?? [];
    $course = $course ?? null;
    $search = $search ?? '';
    $status = $status ?? '';
    $pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Learning Management</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage courses, assignments, and learning materials</p>
    </div>
    <button onclick="openModal('create')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Course
    </button>
  </div>

  <!-- Tabs -->
  <div class="border-b border-gray-200 dark:border-gray-700">
    <nav class="-mb-px flex gap-6" aria-label="Tabs">
      <button onclick="switchTab('courses')" id="tab-courses" class="tab-btn border-b-2 border-emerald-500 py-3 text-sm font-medium text-emerald-600 whitespace-nowrap">Courses</button>
      <button onclick="switchTab('assignments')" id="tab-assignments" class="tab-btn border-b-2 border-transparent py-3 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 whitespace-nowrap">Assignments</button>
      <button onclick="switchTab('submissions')" id="tab-submissions" class="tab-btn border-b-2 border-transparent py-3 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 whitespace-nowrap">Submissions</button>
    </nav>
  </div>

  <!-- Courses Tab -->
  <div id="panel-courses" class="tab-panel">
    <!-- Search -->
    <div class="flex flex-col sm:flex-row gap-3">
      <form method="GET" action="<?= url('/lms') ?>" class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search courses..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400">
      </form>
      <select name="status" onchange="this.form.closest('form') || window.location.href='<?= url('/lms') ?>?status='+this.value" class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        <option value="">All Status</option>
        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
        <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
      </select>
      <?php if (!empty($search) || !empty($status)): ?>
      <a href="<?= url('/lms') ?>" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        Clear
      </a>
      <?php endif; ?>
    </div>

    <!-- Course Cards Grid -->
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <?php foreach($courses as $item): ?>
      <?php
        $courseStatus = $item['status'] ?? 'draft';
        if ($courseStatus === 'active') {
          $statusBg = 'bg-emerald-100 dark:bg-emerald-900/30';
          $statusText = 'text-emerald-700 dark:text-emerald-400';
          $statusDot = 'bg-emerald-500';
          $statusLabel = 'Active';
        } elseif ($courseStatus === 'draft') {
          $statusBg = 'bg-yellow-100 dark:bg-yellow-900/30';
          $statusText = 'text-yellow-700 dark:text-yellow-400';
          $statusDot = 'bg-yellow-500';
          $statusLabel = 'Draft';
        } else {
          $statusBg = 'bg-gray-100 dark:bg-gray-700';
          $statusText = 'text-gray-600 dark:text-gray-400';
          $statusDot = 'bg-gray-400';
          $statusLabel = 'Archived';
        }
      ?>
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
        <!-- Card Header -->
        <div class="p-4 pb-3">
          <div class="flex items-start justify-between gap-2">
            <div class="flex-1 min-w-0">
              <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate"><?= e($item['title'] ?? 'Untitled') ?></h3>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 line-clamp-2"><?= e($item['description'] ?? 'No description') ?></p>
            </div>
            <span class="inline-flex items-center gap-1 shrink-0 rounded-full <?= $statusBg ?> px-2 py-0.5 text-xs font-medium <?= $statusText ?>">
              <span class="h-1.5 w-1.5 rounded-full <?= $statusDot ?>"></span>
              <?= $statusLabel ?>
            </span>
          </div>
        </div>
        <!-- Card Meta -->
        <div class="px-4 py-2 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
          <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
            <?php if (!empty($item['subject_id'])): ?>
            <span class="inline-flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
              <?= e($item['subject_id']) ?>
            </span>
            <?php endif; ?>
            <?php if (!empty($item['teacher_id'])): ?>
            <span class="inline-flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              <?= e($item['teacher_id']) ?>
            </span>
            <?php endif; ?>
          </div>
          <div class="flex items-center gap-1">
            <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($item), ENT_QUOTES) ?>)" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Edit">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            <button onclick="confirmDelete('<?= e($item['id']) ?>')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <?php if(empty($courses)): ?>
    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
      <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
      </svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No courses found</p>
      <p class="text-xs text-gray-400 dark:text-gray-500">Get started by creating your first course</p>
    </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if(isset($pagination) && $pagination['totalPages'] > 1): ?>
    <div class="flex items-center justify-between py-3">
      <p class="text-sm text-gray-500 dark:text-gray-400">Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> courses</p>
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

  <!-- Assignments Tab -->
  <div id="panel-assignments" class="tab-panel hidden">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
      <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
      </svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Assignments</p>
      <p class="text-xs text-gray-400 dark:text-gray-500">Create and manage course assignments</p>
    </div>
  </div>

  <!-- Submissions Tab -->
  <div id="panel-submissions" class="tab-panel hidden">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
      <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
      </svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Submissions</p>
      <p class="text-xs text-gray-400 dark:text-gray-500">View and grade student submissions</p>
    </div>
  </div>

  <!-- Create/Edit Course Modal -->
  <div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)closeModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Add Course</h3>
        <button onclick="closeModal()" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="modal-form" method="POST" action="<?= url('/lms/courses') ?>" class="p-6 space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="form-id">

        <div>
          <label for="form-title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Title <span class="text-red-500">*</span></label>
          <input type="text" id="form-title" name="title" required placeholder="e.g. Introduction to Mathematics" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>

        <div>
          <label for="form-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
          <textarea id="form-description" name="description" rows="3" placeholder="Course description..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
            <input type="text" id="form-subject_id" name="subject_id" placeholder="Subject ID" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teacher</label>
            <input type="text" id="form-teacher_id" name="teacher_id" placeholder="Teacher ID" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <div>
          <label for="form-status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
          <select id="form-status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="draft">Draft</option>
            <option value="active">Active</option>
            <option value="archived">Archived</option>
          </select>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
          <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">Save Course</button>
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
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Course</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete this course? This action cannot be undone.</p>
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

    function openModal(mode, data = null) {
      const modal = document.getElementById('modal');
      const title = document.getElementById('modal-title');
      const form = document.getElementById('modal-form');

      if (mode === 'edit' && data) {
        title.textContent = 'Edit Course';
        document.getElementById('form-id').value = data.id || '';
        document.getElementById('form-title').value = data.title || '';
        document.getElementById('form-description').value = data.description || '';
        document.getElementById('form-subject_id').value = data.subject_id || '';
        document.getElementById('form-teacher_id').value = data.teacher_id || '';
        document.getElementById('form-status').value = data.status || 'draft';
        form.action = '<?= url("/lms/courses") ?>/' + data.id;
        form.method = 'POST';
      } else {
        title.textContent = 'Add Course';
        form.reset();
        document.getElementById('form-id').value = '';
        form.action = '<?= url("/lms/courses") ?>';
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
      document.getElementById('delete-form').action = '<?= url("/lms/courses") ?>/' + id + '/delete';
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
