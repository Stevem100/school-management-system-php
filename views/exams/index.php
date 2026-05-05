<?php $pageTitle = 'Exams Management'; ?>
<?php
$exams = $exams ?? [];
$classes = $classes ?? [];
$total = $total ?? 0;
$page = $page ?? 1;
$lastPage = $lastPage ?? 1;
$search = $search ?? '';
$type = $type ?? '';
$status = $status ?? '';
$classId = $classId ?? '';

$examTypes = ['midterm' => 'Midterm', 'final' => 'Final', 'quiz' => 'Quiz', 'assignment' => 'Assignment'];
$examStatuses = ['draft' => 'Draft', 'published' => 'Published', 'completed' => 'Completed'];

function getStatusBadge($st) {
    switch ($st) {
        case 'draft': return 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300';
        case 'published': return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300';
        case 'completed': return 'bg-violet-100 text-violet-700 dark:bg-violet-900 dark:text-violet-300';
        default: return 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300';
    }
}

function getTypeBadge($tp) {
    switch ($tp) {
        case 'midterm': return 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300';
        case 'final': return 'bg-rose-100 text-rose-700 dark:bg-rose-900 dark:text-rose-300';
        case 'quiz': return 'bg-sky-100 text-sky-700 dark:bg-sky-900 dark:text-sky-300';
        case 'assignment': return 'bg-violet-100 text-violet-700 dark:bg-violet-900 dark:text-violet-300';
        default: return 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300';
    }
}
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Exams</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Create and manage exams, schedules, and grading criteria</p>
    </div>
    <button onclick="openModal('create')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Exam
    </button>
  </div>

  <!-- Filters -->
  <form method="GET" action="<?= url('exams') ?>" class="flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search exams..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
    </div>
    <select name="type" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Types</option>
      <?php foreach($examTypes as $key => $label): ?>
        <option value="<?= e($key) ?>" <?= $type === $key ? 'selected' : '' ?>><?= e($label) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="status" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Status</option>
      <?php foreach($examStatuses as $key => $label): ?>
        <option value="<?= e($key) ?>" <?= $status === $key ? 'selected' : '' ?>><?= e($label) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="class_id" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Classes</option>
      <?php foreach($classes as $cls): ?>
        <option value="<?= e($cls['id'] ?? '') ?>" <?= $classId === ($cls['id'] ?? '') ? 'selected' : '' ?>><?= e($cls['name'] ?? '') ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">Filter</button>
  </form>

  <!-- Data Table Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
      <p class="text-sm text-gray-500 dark:text-gray-400">
        Showing <span class="font-medium text-gray-700 dark:text-gray-300"><?= $total ?></span> exams
      </p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-900">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Exam Name</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Class</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Marks</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pass Marks</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dates</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php foreach($exams as $ex): ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white"><?= e($ex['name'] ?? '') ?></td>
            <td class="px-4 py-3 text-sm">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= getTypeBadge($ex['type'] ?? '') ?>"><?= ucfirst($ex['type'] ?? '') ?></span>
            </td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?= e($ex['subjectName'] ?? 'N/A') ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?= e($ex['className'] ?? 'N/A') ?></td>
            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white"><?= e($ex['totalMarks'] ?? 0) ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?= e($ex['passingMarks'] ?? 0) ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
              <span class="whitespace-nowrap"><?= formatDate($ex['startDate'] ?? null, 'M d') ?> - <?= formatDate($ex['endDate'] ?? null, 'M d, Y') ?></span>
            </td>
            <td class="px-4 py-3 text-sm">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= getStatusBadge($ex['status'] ?? '') ?>"><?= ucfirst($ex['status'] ?? '') ?></span>
            </td>
            <td class="px-4 py-3 text-right">
              <div class="flex items-center justify-end gap-2">
                <button onclick="openModal('edit', '<?= e($ex['id'] ?? '') ?>')" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors" title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="confirmDelete('<?= e($ex['id'] ?? '') ?>', '<?= e($ex['name'] ?? '') ?>')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php if(empty($exams)): ?>
    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
      <svg class="w-12 h-12 mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <p class="text-sm font-medium">No exams found</p>
      <p class="text-xs mt-1">Try adjusting your filters or create a new exam</p>
    </div>
    <?php endif; ?>

    <?php if($lastPage > 1): ?>
    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
      <p class="text-sm text-gray-500 dark:text-gray-400">Page <?= $page ?> of <?= $lastPage ?></p>
      <div class="flex items-center gap-2">
        <?php if($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type) ?>&status=<?= urlencode($status) ?>&class_id=<?= urlencode($classId) ?>" class="px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Previous</a>
        <?php endif; ?>
        <?php if($page < $lastPage): ?>
        <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type) ?>&status=<?= urlencode($status) ?>&class_id=<?= urlencode($classId) ?>" class="px-3 py-1.5 text-sm bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">Next</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Create/Edit Modal -->
<div id="examModal" class="fixed inset-0 z-50 hidden">
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
  <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg relative z-10 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Exam</h3>
        <button onclick="closeModal()" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="examForm" onsubmit="submitForm(event)">
        <input type="hidden" name="id" id="examId" value="">
        <input type="hidden" name="_token" value="<?= csrf_token() ?>">
        <div class="px-6 py-4 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Exam Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="examName" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. Term 1 Mathematics Exam">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type <span class="text-red-500">*</span></label>
              <select name="type" id="examType" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">Select type</option>
                <option value="midterm">Midterm</option>
                <option value="final">Final</option>
                <option value="quiz">Quiz</option>
                <option value="assignment">Assignment</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
              <select name="status" id="examStatus" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="completed">Completed</option>
              </select>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject <span class="text-red-500">*</span></label>
              <select name="subject_id" id="examSubject" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">Loading...</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class <span class="text-red-500">*</span></label>
              <select name="class_id" id="examClass" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">Loading...</option>
              </select>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Marks <span class="text-red-500">*</span></label>
              <input type="number" name="total_marks" id="examTotal" required min="1" step="0.5" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. 100">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pass Marks <span class="text-red-500">*</span></label>
              <input type="number" name="passing_marks" id="examPass" required min="0" step="0.5" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. 50">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date <span class="text-red-500">*</span></label>
              <input type="date" name="start_date" id="examStart" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date <span class="text-red-500">*</span></label>
              <input type="date" name="end_date" id="examEnd" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>
          </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
          <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</button>
          <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">Save Exam</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
  <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm relative z-10">
      <div class="p-6 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900 mb-4">
          <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Exam</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete <strong id="deleteItemName" class="text-gray-700 dark:text-gray-300"></strong>?</p>
        <div class="flex items-center justify-center gap-3">
          <button onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</button>
          <button onclick="executeDelete()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let deleteTargetId = null;

function loadDropdowns() {
  fetch('/api/subjects?status=active').then(r => r.json()).then(res => {
    if (res.success && res.data) {
      const sel = document.getElementById('examSubject');
      sel.innerHTML = '<option value="">Select subject</option>';
      res.data.forEach(s => { sel.innerHTML += `<option value="${s.id}">${s.name} (${s.code})</option>`; });
    }
  });
  fetch('/api/classes?status=active').then(r => r.json()).then(res => {
    if (res.success && res.data) {
      const sel = document.getElementById('examClass');
      sel.innerHTML = '<option value="">Select class</option>';
      res.data.forEach(c => { sel.innerHTML += `<option value="${c.id}">${c.name}</option>`; });
    }
  });
}

function openModal(mode, id) {
  document.getElementById('examModal').classList.remove('hidden');
  document.getElementById('examForm').reset();
  document.getElementById('examId').value = '';
  document.getElementById('examStatus').value = 'draft';
  loadDropdowns();

  if (mode === 'edit' && id) {
    document.getElementById('modalTitle').textContent = 'Edit Exam';
    fetch('/api/exams/' + id)
      .then(r => r.json())
      .then(res => {
        if (res.success && res.data) {
          const ex = res.data;
          document.getElementById('examId').value = ex.id || '';
          document.getElementById('examName').value = ex.name || '';
          document.getElementById('examType').value = ex.type || '';
          document.getElementById('examStatus').value = ex.status || 'draft';
          document.getElementById('examSubject').value = ex.subjectId || '';
          document.getElementById('examClass').value = ex.classId || '';
          document.getElementById('examTotal').value = ex.totalMarks || '';
          document.getElementById('examPass').value = ex.passingMarks || '';
          document.getElementById('examStart').value = (ex.startDate || '').split('T')[0];
          document.getElementById('examEnd').value = (ex.endDate || '').split('T')[0];
        }
      });
  } else {
    document.getElementById('modalTitle').textContent = 'Add Exam';
  }
}

function closeModal() { document.getElementById('examModal').classList.add('hidden'); }

function submitForm(e) {
  e.preventDefault();
  const form = document.getElementById('examForm');
  const formData = new FormData(form);
  const id = formData.get('id');
  const url = id ? '/api/exams/' + id : '/api/exams';
  const method = id ? 'PUT' : 'POST';
  fetch(url, {
    method, headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      name: formData.get('name'), type: formData.get('type'),
      subject_id: formData.get('subject_id'), class_id: formData.get('class_id'),
      total_marks: parseFloat(formData.get('total_marks')), passing_marks: parseFloat(formData.get('passing_marks')),
      start_date: formData.get('start_date'), end_date: formData.get('end_date'),
      status: formData.get('status')
    })
  })
  .then(r => r.json())
  .then(res => { if (res.success) window.location.href = '<?= url("exams") ?>'; else alert(res.error || 'Error'); })
  .catch(err => alert('Request failed: ' + err.message));
}

function confirmDelete(id, name) {
  deleteTargetId = id;
  document.getElementById('deleteItemName').textContent = name;
  document.getElementById('deleteModal').classList.remove('hidden');
}
function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); deleteTargetId = null; }
function executeDelete() {
  if (!deleteTargetId) return;
  fetch('/api/exams/' + deleteTargetId, { method: 'DELETE' })
    .then(r => r.json())
    .then(res => { if (res.success) window.location.href = '<?= url("exams") ?>'; else { alert(res.error); closeDeleteModal(); } });
}
</script>
