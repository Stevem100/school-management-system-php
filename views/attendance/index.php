<?php $pageTitle = 'Attendance'; ?>
<?php
$classes = $classes ?? [];
$students = $students ?? [];
$attendance = $attendance ?? [];
$selectedClass = $selectedClass ?? null;
$date = $date ?? date('Y-m-d');
$classId = $classId ?? '';
$summary = $summary ?? ['present' => 0, 'absent' => 0, 'late' => 0, 'excused' => 0, 'total' => 0, 'rate' => 0];

$statuses = ['present' => 'Present', 'absent' => 'Absent', 'late' => 'Late', 'excused' => 'Excused'];

function statusColor($st) {
    switch ($st) {
        case 'present': return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300 border-emerald-300 dark:border-emerald-700';
        case 'absent':  return 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 border-red-300 dark:border-red-700';
        case 'late':    return 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300 border-amber-300 dark:border-amber-700';
        case 'excused': return 'bg-sky-100 text-sky-700 dark:bg-sky-900 dark:text-sky-300 border-sky-300 dark:border-sky-700';
        default:        return 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 border-gray-300 dark:border-gray-600';
    }
}
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Attendance</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Record and track daily student attendance</p>
    </div>
  </div>

  <!-- Date Picker & Class Selector -->
  <form method="GET" action="<?= url('attendance') ?>" class="flex flex-col sm:flex-row gap-3">
    <div class="flex items-center gap-2">
      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      <input type="date" name="date" value="<?= e($date) ?>" class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
    </div>
    <select name="class_id" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent flex-1 sm:flex-none sm:min-w-[200px]">
      <option value="">Select a class</option>
      <?php foreach($classes as $cls): ?>
        <option value="<?= e($cls['id'] ?? '') ?>" <?= $classId === ($cls['id'] ?? '') ? 'selected' : '' ?>><?= e($cls['name'] ?? '') ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium">
      Load Students
    </button>
  </form>

  <?php if(!empty($students)): ?>
  <!-- Summary Cards -->
  <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 text-center">
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Students</p>
      <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= count($students) ?></p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 text-center">
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Present</p>
      <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400"><?= $summary['present'] ?></p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 text-center">
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Absent</p>
      <p class="text-2xl font-bold text-red-600 dark:text-red-400"><?= $summary['absent'] ?></p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 text-center">
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Late</p>
      <p class="text-2xl font-bold text-amber-600 dark:text-amber-400"><?= $summary['late'] ?></p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 text-center">
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Attendance Rate</p>
      <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $summary['rate'] ?>%</p>
    </div>
  </div>

  <!-- Attendance Entry Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
          <?= e($selectedClass['name'] ?? 'Class') ?> &mdash; <?= formatDate($date, 'l, F j, Y') ?>
        </h3>
        <?php if($summary['total'] > 0 && $summary['total'] === count($students)): ?>
          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300">Recorded</span>
        <?php endif; ?>
      </div>
      <button onclick="saveAttendance()" id="saveBtn" class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Save Attendance
      </button>
    </div>
    <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-900 sticky top-0 z-10">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">#</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student Name</th>
            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Remarks</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php $index = 1; ?>
          <?php foreach($students as $student): ?>
          <?php
            $sid = $student['id'] ?? '';
            $sName = trim(($student['firstName'] ?? '') . ' ' . ($student['lastName'] ?? ''));
            $existing = $attendance[$sid] ?? null;
            $currentStatus = $existing['status'] ?? '';
            $currentRemarks = $existing['remarks'] ?? '';
          ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors" data-student-id="<?= e($sid) ?>">
            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400"><?= $index++ ?></td>
            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white"><?= e($sName) ?></td>
            <td class="px-4 py-3">
              <div class="flex items-center justify-center gap-2 flex-wrap">
                <?php foreach($statuses as $stKey => $stLabel): ?>
                <label class="inline-flex items-center">
                  <input type="radio" name="attendance_<?= e($sid) ?>" value="<?= $stKey ?>" <?= $currentStatus === $stKey ? 'checked' : '' ?> class="peer sr-only">
                  <span class="px-3 py-1 text-xs font-medium border rounded-lg cursor-pointer transition-all peer-checked:ring-2 peer-checked:ring-offset-1 dark:peer-checked:ring-offset-gray-800 <?= statusColor($stKey) ?> peer-checked:ring-<?= $stKey === 'present' ? 'emerald-500' : ($stKey === 'absent' ? 'red-500' : ($stKey === 'late' ? 'amber-500' : 'sky-500')) ?> opacity-60 peer-checked:opacity-100">
                    <?= $stLabel ?>
                  </span>
                </label>
                <?php endforeach; ?>
              </div>
            </td>
            <td class="px-4 py-3">
              <input type="text" data-remarks="<?= e($sid) ?>" value="<?= e($currentRemarks) ?>" class="w-full px-2 py-1 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="Optional...">
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php else: ?>
  <!-- Empty State -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
      <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
      <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Select a Class</p>
      <p class="text-sm mt-1">Choose a class above to start recording attendance</p>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- Save Success Toast (hidden by default) -->
<div id="saveToast" class="fixed bottom-6 right-6 z-50 hidden">
  <div class="flex items-center gap-3 px-4 py-3 bg-emerald-600 text-white rounded-lg shadow-lg">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span class="text-sm font-medium" id="toastMessage">Attendance saved successfully!</span>
  </div>
</div>

<script>
function saveAttendance() {
  const classId = '<?= e($classId) ?>';
  const date = '<?= e($date) ?>';
  if (!classId || !date) { alert('Please select a class and date'); return; }

  const rows = document.querySelectorAll('tr[data-student-id]');
  const attendanceRecords = [];

  rows.forEach(row => {
    const studentId = row.dataset.studentId;
    const selected = row.querySelector('input[type="radio"]:checked');
    const remarks = row.querySelector('input[data-remarks]')?.value || '';

    attendanceRecords.push({
      student_id: studentId,
      status: selected ? selected.value : 'absent',
      remarks: remarks,
    });
  });

  const btn = document.getElementById('saveBtn');
  btn.disabled = true;
  btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Saving...';

  fetch('/api/attendance', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ class_id: classId, date: date, attendance: attendanceRecords })
  })
  .then(r => r.json())
  .then(res => {
    btn.disabled = false;
    btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Save Attendance';
    if (res.success) {
      showToast(res.message || 'Attendance saved successfully!');
      setTimeout(() => window.location.reload(), 1000);
    } else {
      alert(res.error || 'Failed to save attendance');
    }
  })
  .catch(err => {
    btn.disabled = false;
    btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Save Attendance';
    alert('Request failed: ' + err.message);
  });
}

function showToast(message) {
  const toast = document.getElementById('saveToast');
  document.getElementById('toastMessage').textContent = message;
  toast.classList.remove('hidden');
  setTimeout(() => toast.classList.add('hidden'), 3000);
}
</script>
