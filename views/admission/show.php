<?php $pageTitle = $pageTitle ?? 'Application Details'; ?>
<?php
    $application = $application ?? [];
    $formData = $formData ?? [];
    $attachments = $attachments ?? [];
    $sections = $sections ?? [];
    $sectionLabels = $sectionLabels ?? [
        'personal' => 'Personal Information',
        'academic' => 'Academic Information',
        'guardian' => 'Guardian Information',
        'documents' => 'Documents',
        'other' => 'Other',
    ];
    $errors = $errors ?? [];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
        <a href="<?= url('/admission') ?>" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Admission</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="<?= url('/admission/applications') ?>" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Applications</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span>Details</span>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Application Details</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review and manage this admission application</p>
    </div>
    <a href="<?= url('/admission/applications') ?>" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back to Applications
    </a>
  </div>

  <!-- Applicant Info Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
      <div class="flex items-start gap-4">
        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400 text-lg font-bold shrink-0">
          <?= strtoupper(mb_substr(e($application['applicant_name'] ?? 'U'), 0, 1)) ?>
        </div>
        <div>
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white"><?= e($application['applicant_name'] ?? 'Unknown') ?></h2>
          <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500 dark:text-gray-400">
            <?php if (!empty($application['email'])): ?>
            <span class="inline-flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <?= e($application['email']) ?>
            </span>
            <?php endif; ?>
            <?php if (!empty($application['phone'])): ?>
            <span class="inline-flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              <?= e($application['phone']) ?>
            </span>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="flex flex-col items-end gap-2 shrink-0">
        <?php $status = $application['status'] ?? 'pending'; ?>
        <?php if ($status === 'approved'): ?>
        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-3 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400">
          <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
          Approved
        </span>
        <?php elseif ($status === 'rejected'): ?>
        <span class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-900/30 px-3 py-1 text-xs font-semibold text-red-700 dark:text-red-400">
          <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
          Rejected
        </span>
        <?php elseif ($status === 'waitlisted'): ?>
        <span class="inline-flex items-center gap-1 rounded-full bg-purple-100 dark:bg-purple-900/30 px-3 py-1 text-xs font-semibold text-purple-700 dark:text-purple-400">
          <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span>
          Waitlisted
        </span>
        <?php else: ?>
        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 dark:bg-amber-900/30 px-3 py-1 text-xs font-semibold text-amber-700 dark:text-amber-400">
          <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
          Pending
        </span>
        <?php endif; ?>
        <span class="text-xs text-gray-400 dark:text-gray-500"><?= e($application['created_at'] ?? '') ?></span>
      </div>
    </div>

    <!-- Application Metadata -->
    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 grid grid-cols-2 sm:grid-cols-4 gap-4">
      <div>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Application No</p>
        <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5"><?= e($application['application_no'] ?? '—') ?></p>
      </div>
      <div>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Class</p>
        <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5"><?= e($application['class'] ?? '—') ?></p>
      </div>
      <div>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Academic Year</p>
        <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5"><?= e($application['academic_year'] ?? '—') ?></p>
      </div>
      <div>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Term</p>
        <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5"><?= e($application['term'] ?? '—') ?></p>
      </div>
    </div>
  </div>

  <!-- Form Data by Sections -->
  <?php if (!empty($formData) && !empty($sections)): ?>
    <?php foreach ($sections as $section): ?>
      <?php $sectionFields = array_filter($formData, fn($f) => ($f['section'] ?? '') === $section); ?>
      <?php if (empty($sectionFields)) continue; ?>

      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-base font-semibold text-gray-900 dark:text-white"><?= e($sectionLabels[$section] ?? ucfirst($section)) ?></h2>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
          <?php foreach ($sectionFields as $field): ?>
          <div class="px-6 py-3 flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4">
            <div class="sm:w-1/3 shrink-0">
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                <?= e($field['label'] ?? '') ?>
                <?php if (!empty($field['is_required'])): ?>
                <span class="text-red-400">*</span>
                <?php endif; ?>
              </p>
            </div>
            <div class="sm:w-2/3">
              <?php $value = $field['value'] ?? ''; ?>
              <?php if ($field['type'] ?? '' === 'file' && !empty($value)): ?>
                <a href="<?= e($value) ?>" target="_blank" class="inline-flex items-center gap-1.5 text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                  View Attachment
                </a>
              <?php elseif ($field['type'] ?? '' === 'checkbox' && is_array($value)): ?>
                <div class="flex flex-wrap gap-2">
                  <?php foreach ($value as $v): ?>
                  <span class="inline-flex items-center rounded bg-gray-100 dark:bg-gray-700 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:text-gray-300"><?= e($v) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php elseif ($field['type'] ?? '' === 'textarea'): ?>
                <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap"><?= nl2br(e($value)) ?></p>
              <?php else: ?>
                <p class="text-sm text-gray-900 dark:text-white"><?= e($value) ?: '<span class="text-gray-400 dark:text-gray-500">Not provided</span>' ?></p>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <!-- Attachments Section -->
  <?php if (!empty($attachments)): ?>
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
      <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
        Uploaded Attachments
        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-600 dark:text-gray-300"><?= count($attachments) ?></span>
      </h2>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        <?php foreach ($attachments as $attachment): ?>
        <a href="<?= e($attachment['url'] ?? '#') ?>" target="_blank" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-700 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors group">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30 shrink-0">
            <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-900 dark:text-white truncate"><?= e($attachment['name'] ?? 'Attachment') ?></p>
            <p class="text-xs text-gray-400 dark:text-gray-500"><?= e($attachment['size'] ?? '') ?></p>
          </div>
          <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Review Section -->
  <div id="review" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
      <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        Application Review
      </h2>
    </div>
    <div class="p-6">
      <?php if ($status === 'approved' || $status === 'rejected' || $status === 'waitlisted'): ?>
        <!-- Already Reviewed -->
        <div class="flex items-start gap-3 mb-4">
          <div class="flex h-8 w-8 items-center justify-center rounded-full shrink-0 <?= $status === 'approved' ? 'bg-emerald-100 dark:bg-emerald-900/30' : ($status === 'rejected' ? 'bg-red-100 dark:bg-red-900/30' : 'bg-purple-100 dark:bg-purple-900/30') ?>">
            <?php if ($status === 'approved'): ?>
            <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?php elseif ($status === 'rejected'): ?>
            <svg class="h-4 w-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <?php else: ?>
            <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?php endif; ?>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">This application has been <?= e(ucfirst($status)) ?></p>
            <?php if (!empty($application['review_notes'])): ?>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= e($application['review_notes']) ?></p>
            <?php endif; ?>
            <?php if (!empty($application['reviewed_at'])): ?>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Reviewed on <?= e($application['reviewed_at']) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Re-review buttons -->
        <form method="POST" action="<?= url('/admission/applications/' . ($application['id'] ?? '') . '/review') ?>" class="space-y-4">
          <?= csrf_field() ?>
          <div>
            <label for="review_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Update Review Notes</label>
            <textarea id="review_notes" name="review_notes" rows="3" placeholder="Add additional notes about this review..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"><?= e($application['review_notes'] ?? '') ?></textarea>
          </div>
          <div class="flex flex-wrap gap-2">
            <button type="submit" name="action" value="approve" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              Approve
            </button>
            <button type="submit" name="action" value="waitlist" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors shadow-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Waitlist
            </button>
            <button type="submit" name="action" value="reject" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors shadow-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              Reject
            </button>
          </div>
        </form>
      <?php else: ?>
        <!-- Pending Review -->
        <form method="POST" action="<?= url('/admission/applications/' . ($application['id'] ?? '') . '/review') ?>" class="space-y-4">
          <?= csrf_field() ?>
          <div>
            <label for="review_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Review Notes</label>
            <textarea id="review_notes" name="review_notes" rows="3" placeholder="Add any notes about your decision (optional)..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"><?= e(old('review_notes') ?? '') ?></textarea>
            <?php if (!empty($errors['review_notes'])): ?>
            <p class="mt-1 text-xs text-red-500 dark:text-red-400"><?= e($errors['review_notes']) ?></p>
            <?php endif; ?>
          </div>
          <div class="flex flex-wrap gap-2">
            <button type="submit" name="action" value="approve" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              Approve Application
            </button>
            <button type="submit" name="action" value="waitlist" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors shadow-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Add to Waitlist
            </button>
            <button type="submit" name="action" value="reject" onclick="return confirm('Are you sure you want to reject this application?')" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors shadow-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              Reject Application
            </button>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  // Scroll to review section if hash is present
  if (window.location.hash === '#review') {
    document.getElementById('review')?.scrollIntoView({ behavior: 'smooth' });
  }
</script>
