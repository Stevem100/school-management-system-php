<?php $pageTitle = $pageTitle ?? 'Admission Settings'; ?>
<?php
    $settings = $settings ?? [
        'academic_year' => '',
        'term' => '',
        'start_date' => '',
        'end_date' => '',
        'max_applications' => '',
        'application_fee' => '',
        'instructions' => '',
        'thank_you_message' => '',
        'require_documents' => false,
        'require_photo' => false,
        'is_active' => false,
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
        <span>Settings</span>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admission Settings</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure the admission portal parameters and requirements</p>
    </div>
  </div>

  <form method="POST" action="<?= url('/admission/settings') ?>" class="space-y-6">
    <?= csrf_field() ?>

    <!-- Academic Information -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
          <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
          Academic Information
        </h2>
      </div>
      <div class="p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year <span class="text-red-500">*</span></label>
            <input type="text" id="academic_year" name="academic_year" required value="<?= e($settings['academic_year']) ?>" placeholder="e.g. 2024/2025" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
            <?php if (!empty($errors['academic_year'])): ?>
            <p class="mt-1 text-xs text-red-500 dark:text-red-400"><?= e($errors['academic_year']) ?></p>
            <?php endif; ?>
          </div>
          <div>
            <label for="term" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Term <span class="text-red-500">*</span></label>
            <select id="term" name="term" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select Term</option>
              <option value="Term 1" <?= ($settings['term'] ?? '') === 'Term 1' ? 'selected' : '' ?>>Term 1</option>
              <option value="Term 2" <?= ($settings['term'] ?? '') === 'Term 2' ? 'selected' : '' ?>>Term 2</option>
              <option value="Term 3" <?= ($settings['term'] ?? '') === 'Term 3' ? 'selected' : '' ?>>Term 3</option>
            </select>
            <?php if (!empty($errors['term'])): ?>
            <p class="mt-1 text-xs text-red-500 dark:text-red-400"><?= e($errors['term']) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date <span class="text-red-500">*</span></label>
            <input type="date" id="start_date" name="start_date" required value="<?= e($settings['start_date']) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <?php if (!empty($errors['start_date'])): ?>
            <p class="mt-1 text-xs text-red-500 dark:text-red-400"><?= e($errors['start_date']) ?></p>
            <?php endif; ?>
          </div>
          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date <span class="text-red-500">*</span></label>
            <input type="date" id="end_date" name="end_date" required value="<?= e($settings['end_date']) ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <?php if (!empty($errors['end_date'])): ?>
            <p class="mt-1 text-xs text-red-500 dark:text-red-400"><?= e($errors['end_date']) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="max_applications" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Applications</label>
            <input type="number" id="max_applications" name="max_applications" value="<?= e($settings['max_applications']) ?>" placeholder="e.g. 500" min="1" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Leave empty for unlimited</p>
          </div>
          <div>
            <label for="application_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Application Fee</label>
            <input type="number" id="application_fee" name="application_fee" value="<?= e($settings['application_fee']) ?>" placeholder="e.g. 1000" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Leave empty if no fee required</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Portal Messages -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
          <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
          Portal Messages
        </h2>
      </div>
      <div class="p-6 space-y-4">
        <div>
          <label for="instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Application Instructions</label>
          <textarea id="instructions" name="instructions" rows="5" placeholder="Enter instructions that will be displayed to applicants on the admission form..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"><?= e($settings['instructions']) ?></textarea>
          <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">These instructions will be shown at the top of the application form</p>
        </div>
        <div>
          <label for="thank_you_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thank You Message</label>
          <textarea id="thank_you_message" name="thank_you_message" rows="4" placeholder="Message displayed after successful application submission..." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"><?= e($settings['thank_you_message']) ?></textarea>
          <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Displayed after an applicant successfully submits their application</p>
        </div>
      </div>
    </div>

    <!-- Requirements & Toggles -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
          <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          Requirements
        </h2>
      </div>
      <div class="p-6 space-y-5">
        <!-- Require Documents Toggle -->
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Require Documents</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Applicants must upload supporting documents</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="require_documents" value="1" <?= ($settings['require_documents'] ?? false) ? 'checked' : '' ?> class="sr-only peer">
            <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 peer-focus:ring-offset-2 dark:peer-focus:ring-offset-gray-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
          </label>
        </div>

        <div class="border-t border-gray-100 dark:border-gray-700"></div>

        <!-- Require Photo Toggle -->
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Require Passport Photo</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Applicants must upload a passport-sized photograph</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="require_photo" value="1" <?= ($settings['require_photo'] ?? false) ? 'checked' : '' ?> class="sr-only peer">
            <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 peer-focus:ring-offset-2 dark:peer-focus:ring-offset-gray-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
          </label>
        </div>

        <div class="border-t border-gray-100 dark:border-gray-700"></div>

        <!-- Portal Active Toggle -->
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Activate Admission Portal</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Enable the admission portal to accept new applications</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="is_active" value="1" <?= ($settings['is_active'] ?? false) ? 'checked' : '' ?> class="sr-only peer">
            <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 peer-focus:ring-offset-2 dark:peer-focus:ring-offset-gray-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
          </label>
        </div>
      </div>
    </div>

    <!-- Submit Actions -->
    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
      <a href="<?= url('/admission') ?>" class="px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-center">
        Cancel
      </a>
      <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm inline-flex items-center gap-2 justify-center">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Save Settings
      </button>
    </div>
  </form>
</div>
