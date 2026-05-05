<?php $pageTitle = 'Classes Management'; ?>
<?php
$classes = $classes ?? [];
$total = $total ?? 0;
$page = $page ?? 1;
$perPage = $perPage ?? 20;
$lastPage = $lastPage ?? 1;
$search = $search ?? '';
$gradeLevel = $gradeLevel ?? '';
$academicYear = $academicYear ?? '';
$status = $status ?? '';

$gradeLevels = ['Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Form 1','Form 2','Form 3','Form 4'];
$academicYears = ['2024-2025','2025-2026','2023-2024'];
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Classes</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage school classes, sections, and teacher assignments</p>
    </div>
    <button onclick="openModal('create')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Class
    </button>
  </div>

  <!-- Filters -->
  <form method="GET" action="<?= url('classes') ?>" class="flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search classes..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
    </div>
    <select name="grade_level" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Grades</option>
      <?php foreach($gradeLevels as $gl): ?>
        <option value="<?= e($gl) ?>" <?= $gradeLevel === $gl ? 'selected' : '' ?>><?= e($gl) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="academic_year" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Years</option>
      <?php foreach($academicYears as $ay): ?>
        <option value="<?= e($ay) ?>" <?= $academicYear === $ay ? 'selected' : '' ?>><?= e($ay) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="status" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Status</option>
      <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
      <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
      <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archived</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">Filter</button>
  </form>

  <!-- Data Table Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
      <p class="text-sm text-gray-500 dark:text-gray-400">
        Showing <span class="font-medium text-gray-700 dark:text-gray-300"><?= $total ?></span> classes
      </p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-900">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Class Name</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grade</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Section</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Capacity</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Teacher</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Academic Year</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php foreach($classes as $cls): ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white"><?= e($cls['name'] ?? '') ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?= e($cls['gradeLevel'] ?? '') ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?= e($cls['section'] ?? '') ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?= e($cls['capacity'] ?? 0) ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?= e($cls['classTeacherName'] ?? 'Unassigned') ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?= e($cls['academicYear'] ?? '') ?></td>
            <td class="px-4 py-3 text-sm">
              <?php
                $st = $cls['status'] ?? 'active';
                if ($st === 'active') $badge = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300';
                elseif ($st === 'inactive') $badge = 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300';
                else $badge = 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400';
              ?>
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $badge ?>"><?= ucfirst($st) ?></span>
            </td>
            <td class="px-4 py-3 text-right">
              <div class="flex items-center justify-end gap-2">
                <button onclick="openModal('edit', '<?= e($cls['id'] ?? '') ?>')" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors" title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="confirmDelete('<?= e($cls['id'] ?? '') ?>', '<?= e($cls['name'] ?? '') ?>')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <!-- Empty state -->
    <?php if(empty($classes)): ?>
    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
      <svg class="w-12 h-12 mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
      <p class="text-sm font-medium">No classes found</p>
      <p class="text-xs mt-1">Try adjusting your filters or add a new class</p>
    </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if($lastPage > 1): ?>
    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
      <p class="text-sm text-gray-500 dark:text-gray-400">Page <?= $page ?> of <?= $lastPage ?></p>
      <div class="flex items-center gap-2">
        <?php if($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&grade_level=<?= urlencode($gradeLevel) ?>&academic_year=<?= urlencode($academicYear) ?>&status=<?= urlencode($status) ?>" class="px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Previous</a>
        <?php endif; ?>
        <?php if($page < $lastPage): ?>
        <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&grade_level=<?= urlencode($gradeLevel) ?>&academic_year=<?= urlencode($academicYear) ?>&status=<?= urlencode($status) ?>" class="px-3 py-1.5 text-sm bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">Next</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Create/Edit Modal -->
<div id="classModal" class="fixed inset-0 z-50 hidden">
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
  <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg relative z-10 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Class</h3>
        <button onclick="closeModal()" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="classForm" onsubmit="submitForm(event)">
        <input type="hidden" name="id" id="classId" value="">
        <input type="hidden" name="_token" value="<?= csrf_token() ?>">
        <div class="px-6 py-4 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="className" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. Class 8A">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grade Level <span class="text-red-500">*</span></label>
              <select name="grade_level" id="classGrade" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">Select grade</option>
                <?php foreach($gradeLevels as $gl): ?>
                <option value="<?= e($gl) ?>"><?= e($gl) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Section <span class="text-red-500">*</span></label>
              <input type="text" name="section" id="classSection" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. A">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Capacity <span class="text-red-500">*</span></label>
              <input type="number" name="capacity" id="classCapacity" required min="1" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. 40">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year <span class="text-red-500">*</span></label>
              <select name="academic_year" id="classYear" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">Select year</option>
                <?php foreach($academicYears as $ay): ?>
                <option value="<?= e($ay) ?>"><?= e($ay) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class Teacher</label>
            <select name="class_teacher_id" id="classTeacher" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select teacher</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="status" id="classStatus" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="archived">Archived</option>
            </select>
          </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
          <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</button>
          <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">Save Class</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
  <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm relative z-10">
      <div class="p-6 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900 mb-4">
          <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Class</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete <strong id="deleteItemName" class="text-gray-700 dark:text-gray-300"></strong>? This action cannot be undone.</p>
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

function openModal(mode, id) {
  document.getElementById('classModal').classList.remove('hidden');
  document.getElementById('classForm').reset();
  document.getElementById('classId').value = '';
  document.getElementById('classStatus').value = 'active';

  if (mode === 'edit' && id) {
    document.getElementById('modalTitle').textContent = 'Edit Class';
    // Fetch class data via API
    fetch('/api/classes/' + id)
      .then(r => r.json())
      .then(res => {
        if (res.success && res.data) {
          const c = res.data;
          document.getElementById('classId').value = c.id || '';
          document.getElementById('className').value = c.name || '';
          document.getElementById('classGrade').value = c.gradeLevel || '';
          document.getElementById('classSection').value = c.section || '';
          document.getElementById('classCapacity').value = c.capacity || '';
          document.getElementById('classYear').value = c.academicYear || '';
          document.getElementById('classTeacher').value = c.classTeacherId || '';
          document.getElementById('classStatus').value = c.status || 'active';
        }
      });
  } else {
    document.getElementById('modalTitle').textContent = 'Add Class';
  }
}

function closeModal() {
  document.getElementById('classModal').classList.add('hidden');
}

function submitForm(e) {
  e.preventDefault();
  const form = document.getElementById('classForm');
  const formData = new FormData(form);
  const id = formData.get('id');
  const url = id ? '/api/classes/' + id : '/api/classes';
  const method = id ? 'PUT' : 'POST';

  fetch(url, {
    method: method,
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      name: formData.get('name'),
      grade_level: formData.get('grade_level'),
      section: formData.get('section'),
      capacity: parseInt(formData.get('capacity')),
      academic_year: formData.get('academic_year'),
      class_teacher_id: formData.get('class_teacher_id') || null,
      status: formData.get('status')
    })
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) {
      window.location.href = '<?= url("classes") ?>';
    } else {
      alert(res.error || 'An error occurred');
    }
  })
  .catch(err => alert('Request failed: ' + err.message));
}

function confirmDelete(id, name) {
  deleteTargetId = id;
  document.getElementById('deleteItemName').textContent = name;
  document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
  document.getElementById('deleteModal').classList.add('hidden');
  deleteTargetId = null;
}

function executeDelete() {
  if (!deleteTargetId) return;
  fetch('/api/classes/' + deleteTargetId, { method: 'DELETE' })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        window.location.href = '<?= url("classes") ?>';
      } else {
        alert(res.error || 'Failed to delete');
        closeDeleteModal();
      }
    });
}
</script>
