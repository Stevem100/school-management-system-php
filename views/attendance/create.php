<?php
$attendance = $attendance ?? [];
$classes = $classes ?? [];
$students = $students ?? [];
$title = 'Record Attendance';
$formAction = url('/attendance');

$statuses = [
    'present'    => ['label' => 'Present',    'color' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'dot' => 'bg-emerald-500'],
    'absent'     => ['label' => 'Absent',     'color' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'dot' => 'bg-red-500'],
    'late'       => ['label' => 'Late',       'color' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'dot' => 'bg-amber-500'],
    'excused'    => ['label' => 'Excused',    'color' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'dot' => 'bg-blue-500'],
    'suspended'  => ['label' => 'Suspended',  'color' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400', 'dot' => 'bg-gray-500'],
];
?>

<div class="max-w-4xl mx-auto space-y-6">
  <!-- Page Header -->
  <div class="flex items-center gap-4">
    <a href="<?= url('/attendance') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Record student attendance for a class</p>
    </div>
  </div>

  <!-- Form Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>" id="attendance-form">
      <?= csrf_field() ?>

      <div class="p-6 space-y-5">
        <!-- Date & Class Selection -->
        <div class="flex items-center gap-2 mb-1">
          <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Attendance Details</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
          <div>
            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date <span class="text-red-500">*</span></label>
            <input type="date" id="date" name="date" required value="<?= e($attendance['date'] ?? date('Y-m-d')) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class <span class="text-red-500">*</span></label>
            <select id="class_id" name="class_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select class</option>
              <?php foreach ($classes as $c): ?>
              <option value="<?= e($c['id']) ?>" <?= ($attendance['class_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="session" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session</label>
            <select id="session" name="session" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="morning" <?= ($attendance['session'] ?? 'morning') === 'morning' ? 'selected' : '' ?>>Morning</option>
              <option value="afternoon" <?= ($attendance['session'] ?? '') === 'afternoon' ? 'selected' : '' ?>>Afternoon</option>
              <option value="full_day" <?= ($attendance['session'] ?? '') === 'full_day' ? 'selected' : '' ?>>Full Day</option>
            </select>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="flex flex-wrap items-center gap-2 border-t border-gray-100 dark:border-gray-700 pt-4">
          <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Quick Mark:</span>
          <button type="button" onclick="markAll('present')" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-emerald-100 text-emerald-700 hover:bg-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:hover:bg-emerald-900/50 transition-colors">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span> All Present
          </button>
          <button type="button" onclick="markAll('absent')" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 transition-colors">
            <span class="h-2 w-2 rounded-full bg-red-500"></span> All Absent
          </button>
          <button type="button" onclick="markAll('late')" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-amber-100 text-amber-700 hover:bg-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:hover:bg-amber-900/50 transition-colors">
            <span class="h-2 w-2 rounded-full bg-amber-500"></span> All Late
          </button>
        </div>

        <!-- Student Attendance List -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Student List</h3>

          <?php if (!empty($students)): ?>
          <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-8">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Admission No</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Remarks</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                  <?php foreach ($students as $i => $s): ?>
                  <?php
                    $studentId = $s['id'];
                    $prevStatus = $attendance['records'][$studentId]['status'] ?? 'present';
                    $prevRemarks = $attendance['records'][$studentId]['remarks'] ?? '';
                  ?>
                  <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-400"><?= $i + 1 ?></td>
                    <td class="px-4 py-3">
                      <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400 text-xs font-bold">
                          <?= strtoupper(mb_substr(e($s['first_name'] ?? 'U'), 0, 1)) ?>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white"><?= e($s['first_name'] ?? '') ?> <?= e($s['last_name'] ?? '') ?></span>
                      </div>
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                      <span class="inline-flex items-center rounded bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-600 dark:text-gray-300"><?= e($s['admission_no'] ?? '—') ?></span>
                    </td>
                    <td class="px-4 py-3">
                      <div class="flex items-center justify-center">
                        <select name="records[<?= $studentId ?>][status]" class="px-2 py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-xs font-medium bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" onchange="updateRowStyle(this)">
                          <?php foreach ($statuses as $sk => $sv): ?>
                          <option value="<?= $sk ?>" <?= $prevStatus === $sk ? 'selected' : '' ?>><?= $sv['label'] ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                      <input type="text" name="records[<?= $studentId ?>][remarks]" value="<?= e($prevRemarks) ?>" class="w-full px-2 py-1.5 border border-gray-200 dark:border-gray-700 rounded-lg text-xs bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400" placeholder="Optional remarks">
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php else: ?>
          <!-- Empty State -->
          <div class="flex flex-col items-center justify-center py-12 text-gray-400 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
            <svg class="w-12 h-12 mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No students found</p>
            <p class="text-xs text-gray-400 dark:text-gray-500">Please select a class to load students, or the class has no enrolled students.</p>
          </div>
          <?php endif; ?>

          <!-- Summary -->
          <?php if (!empty($students)): ?>
          <div class="mt-4 flex flex-wrap items-center gap-4 text-xs">
            <span class="text-gray-500 dark:text-gray-400">Total: <span class="font-semibold text-gray-700 dark:text-gray-300"><?= count($students) ?></span></span>
            <span id="summary-present" class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Present: <span class="font-semibold">0</span></span>
            <span id="summary-absent" class="inline-flex items-center gap-1 text-red-600 dark:text-red-400"><span class="h-2 w-2 rounded-full bg-red-500"></span> Absent: <span class="font-semibold">0</span></span>
            <span id="summary-late" class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400"><span class="h-2 w-2 rounded-full bg-amber-500"></span> Late: <span class="font-semibold">0</span></span>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/attendance') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
          <span class="inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Attendance
          </span>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function markAll(status) {
  document.querySelectorAll('select[name*="[status]"]').forEach(function(select) {
    select.value = status;
    updateRowStyle(select);
  });
  updateSummary();
}

function updateRowStyle(select) {
  var row = select.closest('tr');
  if (!row) return;
  row.classList.remove('bg-red-50', 'dark:bg-red-900/10', 'bg-amber-50', 'dark:bg-amber-900/10', 'bg-emerald-50', 'dark:bg-emerald-900/10');
  if (select.value === 'absent') row.classList.add('bg-red-50', 'dark:bg-red-900/10');
  else if (select.value === 'late') row.classList.add('bg-amber-50', 'dark:bg-amber-900/10');
  else if (select.value === 'present') row.classList.add('bg-emerald-50', 'dark:bg-emerald-900/10');
  updateSummary();
}

function updateSummary() {
  var counts = { present: 0, absent: 0, late: 0, excused: 0, suspended: 0 };
  document.querySelectorAll('select[name*="[status]"]').forEach(function(select) {
    if (counts.hasOwnProperty(select.value)) counts[select.value]++;
  });
  var pEl = document.getElementById('summary-present');
  var aEl = document.getElementById('summary-absent');
  var lEl = document.getElementById('summary-late');
  if (pEl) pEl.querySelector('span:last-child').textContent = counts.present;
  if (aEl) aEl.querySelector('span:last-child').textContent = counts.absent;
  if (lEl) lEl.querySelector('span:last-child').textContent = counts.late;
}

// Initialize summary on page load
document.addEventListener('DOMContentLoaded', function() {
  updateSummary();
  document.querySelectorAll('select[name*="[status]"]').forEach(function(select) {
    updateRowStyle(select);
  });
});
</script>
