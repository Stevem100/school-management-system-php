<?php $pageTitle = 'Exam Results'; ?>
<?php
$results = $results ?? [];
$exams = $exams ?? [];
$classes = $classes ?? [];
$subjects = $subjects ?? [];
$students = $students ?? [];
$total = $total ?? 0;
$page = $page ?? 1;
$lastPage = $lastPage ?? 1;
$examId = $examId ?? '';
$classId = $classId ?? '';
$subjectId = $subjectId ?? '';
$studentId = $studentId ?? '';

function getGradeColor($grade) {
    switch (strtoupper($grade ?? '')) {
        case 'A': return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300';
        case 'B': return 'bg-sky-100 text-sky-700 dark:bg-sky-900 dark:text-sky-300';
        case 'C': return 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300';
        case 'D': return 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300';
        case 'F': return 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300';
        default: return 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300';
    }
}
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Exam Results</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View, enter, and manage student exam results</p>
    </div>
    <button onclick="openBulkEntry()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
      Bulk Entry
    </button>
  </div>

  <!-- Filters -->
  <form method="GET" action="<?= url('results') ?>" class="flex flex-col sm:flex-row gap-3">
    <select name="exam_id" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Exams</option>
      <?php foreach($exams as $ex): ?>
        <option value="<?= e($ex['id'] ?? '') ?>" <?= $examId === ($ex['id'] ?? '') ? 'selected' : '' ?>><?= e($ex['name'] ?? '') ?></option>
      <?php endforeach; ?>
    </select>
    <select name="class_id" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Classes</option>
      <?php foreach($classes as $cls): ?>
        <option value="<?= e($cls['id'] ?? '') ?>" <?= $classId === ($cls['id'] ?? '') ? 'selected' : '' ?>><?= e($cls['name'] ?? '') ?></option>
      <?php endforeach; ?>
    </select>
    <select name="subject_id" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
      <option value="">All Subjects</option>
      <?php foreach($subjects as $subj): ?>
        <option value="<?= e($subj['id'] ?? '') ?>" <?= $subjectId === ($subj['id'] ?? '') ? 'selected' : '' ?>><?= e($subj['name'] ?? '') ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">Filter</button>
  </form>

  <!-- Summary Cards -->
  <?php if(!empty($results)): ?>
  <?php
    $totalStudents = count($results);
    $avgPercentage = array_sum(array_column($results, 'percentage')) / max($totalStudents, 1);
    $passCount = count(array_filter($results, fn($r) => ($r['percentage'] ?? 0) >= 50));
    $gradeA = count(array_filter($results, fn($r) => ($r['calculatedGrade'] ?? '') === 'A'));
  ?>
  <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Students</p>
      <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $totalStudents ?></p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Average Score</p>
      <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($avgPercentage, 1) ?>%</p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pass Rate</p>
      <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400"><?= $totalStudents > 0 ? round(($passCount / $totalStudents) * 100) : 0 ?>%</p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Grade A Students</p>
      <p class="text-2xl font-bold text-violet-600 dark:text-violet-400"><?= $gradeA ?></p>
    </div>
  </div>
  <?php endif; ?>

  <!-- Data Table Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
      <p class="text-sm text-gray-500 dark:text-gray-400">
        Showing <span class="font-medium text-gray-700 dark:text-gray-300"><?= $total ?></span> results
      </p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-900">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Exam</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Marks</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Percentage</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grade</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Remarks</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php foreach($results as $res): ?>
          <?php
            $pct = $res['percentage'] ?? 0;
            $pctColor = $pct >= 80 ? 'text-emerald-600' : ($pct >= 60 ? 'text-sky-600' : ($pct >= 50 ? 'text-amber-600' : 'text-red-600'));
          ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white"><?= e($res['studentName'] ?? '') ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?= e($res['examName'] ?? '') ?></td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?= e($res['subjectName'] ?? '') ?></td>
            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white"><?= e($res['marksObtained'] ?? 0) ?></td>
            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400"><?= e($res['totalMarks'] ?? 100) ?></td>
            <td class="px-4 py-3 text-sm font-bold <?= $pctColor ?>"><?= number_format($pct, 1) ?>%</td>
            <td class="px-4 py-3 text-sm">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?= getGradeColor($res['calculatedGrade'] ?? $res['grade'] ?? '') ?>"><?= e(strtoupper($res['calculatedGrade'] ?? $res['grade'] ?? '')) ?></span>
            </td>
            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 max-w-[150px] truncate"><?= e($res['remarks'] ?? '-') ?></td>
            <td class="px-4 py-3 text-right">
              <div class="flex items-center justify-end gap-2">
                <button onclick="editResult('<?= e($res['id'] ?? '') ?>', '<?= e($res['marksObtained'] ?? '') ?>', '<?= e($res['remarks'] ?? '') ?>')" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors" title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button onclick="deleteResult('<?= e($res['id'] ?? '') ?>')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Delete">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php if(empty($results)): ?>
    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
      <svg class="w-12 h-12 mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
      <p class="text-sm font-medium">No results found</p>
      <p class="text-xs mt-1">Select an exam and use Bulk Entry to record results</p>
    </div>
    <?php endif; ?>

    <?php if($lastPage > 1): ?>
    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
      <p class="text-sm text-gray-500 dark:text-gray-400">Page <?= $page ?> of <?= $lastPage ?></p>
      <div class="flex items-center gap-2">
        <?php if($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>&exam_id=<?= urlencode($examId) ?>&class_id=<?= urlencode($classId) ?>&subject_id=<?= urlencode($subjectId) ?>" class="px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Previous</a>
        <?php endif; ?>
        <?php if($page < $lastPage): ?>
        <a href="?page=<?= $page + 1 ?>&exam_id=<?= urlencode($examId) ?>&class_id=<?= urlencode($classId) ?>&subject_id=<?= urlencode($subjectId) ?>" class="px-3 py-1.5 text-sm bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">Next</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Bulk Entry Modal -->
<div id="bulkModal" class="fixed inset-0 z-50 hidden">
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeBulkModal()"></div>
  <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-3xl relative z-10 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bulk Result Entry</h3>
        <button onclick="closeBulkModal()" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <div class="px-6 py-4">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Exam <span class="text-red-500">*</span></label>
          <select id="bulkExamId" onchange="loadBulkStudents()" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="">Select exam</option>
            <?php foreach($exams as $ex): ?>
              <option value="<?= e($ex['id'] ?? '') ?>"><?= e($ex['name'] ?? '') ?> (<?= e($ex['className'] ?? '') ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div id="bulkStudentList" class="space-y-2 max-h-96 overflow-y-auto"></div>
      </div>
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <button onclick="closeBulkModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</button>
        <button onclick="saveBulkResults()" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">Save All Results</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Result Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden">
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
  <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm relative z-10">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Result</h3>
        <button onclick="closeEditModal()" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="editForm" onsubmit="saveEditResult(event)">
        <input type="hidden" id="editResultId" value="">
        <div class="px-6 py-4 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marks Obtained <span class="text-red-500">*</span></label>
            <input type="number" id="editMarks" step="0.5" min="0" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Remarks</label>
            <input type="text" id="editRemarks" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Optional remarks...">
          </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
          <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</button>
          <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openBulkEntry() {
  document.getElementById('bulkModal').classList.remove('hidden');
  document.getElementById('bulkStudentList').innerHTML = '<p class="text-sm text-gray-400 text-center py-8">Select an exam to load students</p>';
}

function closeBulkModal() { document.getElementById('bulkModal').classList.add('hidden'); }

function loadBulkStudents() {
  const examId = document.getElementById('bulkExamId').value;
  const container = document.getElementById('bulkStudentList');
  if (!examId) { container.innerHTML = '<p class="text-sm text-gray-400 text-center py-8">Select an exam to load students</p>'; return; }

  container.innerHTML = '<p class="text-sm text-gray-400 text-center py-8">Loading...</p>';
  fetch('/api/exam-results/' + examId)
    .then(r => r.json())
    .then(res => {
      if (!res.success || !res.data) { container.innerHTML = '<p class="text-sm text-red-400 text-center py-8">Failed to load</p>'; return; }
      const { studentResults, totalMarks } = res.data;
      if (studentResults.length === 0) { container.innerHTML = '<p class="text-sm text-gray-400 text-center py-8">No students found for this exam</p>'; return; }

      let html = `
        <div class="grid grid-cols-12 gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-900 rounded-lg text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
          <div class="col-span-5">Student</div>
          <div class="col-span-3">Marks (/${totalMarks})</div>
          <div class="col-span-4">Remarks</div>
        </div>`;
      studentResults.forEach((s, i) => {
        html += `
        <div class="grid grid-cols-12 gap-2 items-center" data-student-id="${s.studentId}" data-result-id="${s.resultId || ''}">
          <div class="col-span-5 text-sm text-gray-900 dark:text-white truncate">
            <span class="font-medium">${s.studentName}</span>
          </div>
          <div class="col-span-3">
            <input type="number" class="bulk-marks w-full px-2 py-1.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" data-student="${s.studentId}" value="${s.marksObtained ?? ''}" min="0" max="${totalMarks}" step="0.5">
          </div>
          <div class="col-span-4">
            <input type="text" class="bulk-remarks w-full px-2 py-1.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" data-student="${s.studentId}" value="${s.remarks || ''}" placeholder="Remarks">
          </div>
        </div>`;
      });
      container.innerHTML = html;
    })
    .catch(() => { container.innerHTML = '<p class="text-sm text-red-400 text-center py-8">Error loading students</p>'; });
}

function saveBulkResults() {
  const examId = document.getElementById('bulkExamId').value;
  if (!examId) { alert('Please select an exam'); return; }

  const rows = document.querySelectorAll('#bulkStudentList [data-student-id]');
  const results = [];
  rows.forEach(row => {
    results.push({
      student_id: row.dataset.studentId,
      marks_obtained: row.querySelector('.bulk-marks').value,
      remarks: row.querySelector('.bulk-remarks').value,
    });
  });

  fetch('/api/results/bulk', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ exam_id: examId, results })
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) window.location.href = '<?= url("results") ?>?exam_id=' + examId;
    else alert(res.error || 'Failed to save');
  })
  .catch(err => alert('Request failed: ' + err.message));
}

function editResult(id, marks, remarks) {
  document.getElementById('editResultId').value = id;
  document.getElementById('editMarks').value = marks;
  document.getElementById('editRemarks').value = remarks;
  document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }

function saveEditResult(e) {
  e.preventDefault();
  const id = document.getElementById('editResultId').value;
  fetch('/api/results/' + id, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      marks_obtained: parseFloat(document.getElementById('editMarks').value),
      remarks: document.getElementById('editRemarks').value,
    })
  })
  .then(r => r.json())
  .then(res => { if (res.success) window.location.reload(); else alert(res.error || 'Failed'); });
}

function deleteResult(id) {
  if (!confirm('Delete this result?')) return;
  fetch('/api/results/' + id, { method: 'DELETE' })
    .then(r => r.json())
    .then(res => { if (res.success) window.location.reload(); else alert(res.error || 'Failed'); });
}
</script>
