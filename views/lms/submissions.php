<?php $pageTitle = $pageTitle ?? 'Submissions'; ?>
<?php $submissions = $submissions ?? []; ?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Submissions</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View and grade student submissions</p>
    </div>
  </div>

  <!-- Submissions Table -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900/50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Assignment</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Submitted At</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Marks</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php if (!empty($submissions)): ?>
            <?php foreach ($submissions as $s): ?>
            <?php
              $status = $s['status'] ?? 'submitted';
              if ($status === 'graded') {
                $statusClass = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400';
              } elseif ($status === 'late') {
                $statusClass = 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400';
              } elseif ($status === 'returned') {
                $statusClass = 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400';
              } else {
                $statusClass = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400';
              }
            ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors" id="row-<?= e($s['id'] ?? '') ?>">
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <div class="h-8 w-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                    <?= strtoupper(mb_substr($s['student_name'] ?? $s['name'] ?? 'S', 0, 1)) ?>
                  </div>
                  <span class="font-medium text-gray-900 dark:text-white"><?= e($s['student_name'] ?? $s['name'] ?? 'Student #' . ($s['student_id'] ?? '')) ?></span>
                </div>
              </td>
              <td class="px-4 py-3 text-gray-600 dark:text-gray-300 hidden md:table-cell"><?= e($s['assignment_title'] ?? $s['assignment_id'] ?? '—') ?></td>
              <td class="px-4 py-3 text-gray-500 dark:text-gray-400 hidden sm:table-cell"><?= !empty($s['submitted_at']) ? formatDate($s['submitted_at']) : '—' ?></td>
              <td class="px-4 py-3">
                <?php if (!empty($s['marks'])): ?>
                  <span class="font-medium text-gray-900 dark:text-white"><?= (float) $s['marks'] ?></span>
                  <span class="text-gray-400 dark:text-gray-500 text-xs">/ <?= (int) ($s['total_marks'] ?? 100) ?></span>
                <?php else: ?>
                  <span class="text-gray-400 dark:text-gray-500">—</span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center rounded-full <?= $statusClass ?> px-2.5 py-1 text-xs font-medium"><?= ucfirst(e($status)) ?></span>
              </td>
              <td class="px-4 py-3 text-right">
                <?php if ($status !== 'graded'): ?>
                <button onclick="openGradeModal(<?= htmlspecialchars(json_encode($s), ENT_QUOTES) ?>)" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors text-xs font-medium shadow-sm">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                  Grade
                </button>
                <?php else: ?>
                  <span class="text-xs text-gray-400 dark:text-gray-500">Graded</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="px-4 py-16 text-center">
                <svg class="mx-auto w-16 h-16 mb-4 text-gray-300 dark:text-gray-600 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No submissions found</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Student submissions will appear here once received</p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Grade Modal -->
<div id="grade-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)closeGradeModal()">
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md mx-4">
    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grade Submission</h3>
      <button onclick="closeGradeModal()" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <form id="grade-form" method="POST" action="" class="p-6 space-y-4">
      <?= csrf_field() ?>
      <input type="hidden" name="submission_id" id="grade-submission-id">

      <div>
        <p class="text-sm text-gray-600 dark:text-gray-300">Student: <span class="font-medium text-gray-900 dark:text-white" id="grade-student-name">—</span></p>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Assignment: <span class="font-medium text-gray-900 dark:text-white" id="grade-assignment-title">—</span></p>
      </div>

      <div>
        <label for="grade-marks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marks <span class="text-red-500">*</span></label>
        <input type="number" id="grade-marks" name="marks" required min="0" placeholder="Enter marks" class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Out of <span id="grade-total-marks">100</span></p>
      </div>

      <div>
        <label for="grade-feedback" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Feedback</label>
        <textarea id="grade-feedback" name="feedback" rows="3" placeholder="Optional feedback for the student..." class="w-full px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"></textarea>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="button" onclick="closeGradeModal()" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">Submit Grade</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openGradeModal(submission) {
    document.getElementById('grade-submission-id').value = submission.id || '';
    document.getElementById('grade-student-name').textContent = submission.student_name || submission.name || 'Student';
    document.getElementById('grade-assignment-title').textContent = submission.assignment_title || 'Assignment';
    document.getElementById('grade-total-marks').textContent = submission.total_marks || 100;
    document.getElementById('grade-marks').value = submission.marks || '';
    document.getElementById('grade-marks').max = submission.total_marks || 100;
    document.getElementById('grade-feedback').value = submission.feedback || '';
    document.getElementById('grade-form').action = '<?= url("/lms/submissions") ?>/' + (submission.id || '') + '/grade';
    document.getElementById('grade-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeGradeModal() {
    document.getElementById('grade-modal').classList.add('hidden');
    document.body.style.overflow = '';
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeGradeModal();
  });
</script>
