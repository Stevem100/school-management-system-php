<?php $pageTitle = $pageTitle ?? 'Settings'; ?>
<?php
    $settings = $settings ?? [
        'school_name'    => '',
        'school_address' => '',
        'school_phone'   => '',
        'school_email'   => '',
        'school_website' => '',
        'academic_year'  => '',
        'current_term'   => '',
        'theme'          => 'light',
    ];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Settings</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure system preferences and school information</p>
  </div>

  <form method="POST" action="<?= url('/settings') ?>" class="space-y-6">
    <?= csrf_field() ?>

    <!-- School Information -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0119.5 12c0 .818-.082 1.616-.244 2.386L12 14zm-6.16 3.422A12.083 12.083 0 004.5 12c0-.818.082-1.616.244-2.386L6 14l6 6z"/></svg>
          </div>
          <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">School Information</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Basic details about your school</p>
          </div>
        </div>
      </div>
      <div class="p-6 space-y-4">
        <div>
          <label for="form-school_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School Name</label>
          <input type="text" id="form-school_name" name="school_name" value="<?= e($settings['school_name'] ?? '') ?>" placeholder="e.g. Greenfield Academy" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>
        <div>
          <label for="form-school_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
          <textarea id="form-school_address" name="school_address" rows="2" placeholder="Full school address" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"><?= e($settings['school_address'] ?? '') ?></textarea>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label for="form-school_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
            <input type="text" id="form-school_phone" name="school_phone" value="<?= e($settings['school_phone'] ?? '') ?>" placeholder="+254 700 000 000" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-school_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input type="email" id="form-school_email" name="school_email" value="<?= e($settings['school_email'] ?? '') ?>" placeholder="school@example.com" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-school_website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Website</label>
            <input type="text" id="form-school_website" name="school_website" value="<?= e($settings['school_website'] ?? '') ?>" placeholder="https://www.example.com" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>
      </div>
    </div>

    <!-- Academic Settings -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
          <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Academic Settings</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Configure academic year and term</p>
          </div>
        </div>
      </div>
      <div class="p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Academic Year</label>
            <input type="text" id="form-academic_year" name="academic_year" value="<?= e($settings['academic_year'] ?? '') ?>" placeholder="e.g. 2024-2025" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-current_term" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Term</label>
            <select id="form-current_term" name="current_term" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="" <?= empty($settings['current_term']) ? 'selected' : '' ?>>Select Term</option>
              <option value="Term 1" <?= ($settings['current_term'] ?? '') === 'Term 1' ? 'selected' : '' ?>>Term 1</option>
              <option value="Term 2" <?= ($settings['current_term'] ?? '') === 'Term 2' ? 'selected' : '' ?>>Term 2</option>
              <option value="Term 3" <?= ($settings['current_term'] ?? '') === 'Term 3' ? 'selected' : '' ?>>Term 3</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Appearance -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
          </div>
          <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Appearance</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Customize the look and feel of the system</p>
          </div>
        </div>
      </div>
      <div class="p-6">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Theme</label>
            <p class="text-xs text-gray-400">Choose between light and dark mode</p>
          </div>
          <div class="flex items-center gap-3 bg-gray-100 dark:bg-gray-900 rounded-lg p-1">
            <button type="button" onclick="setTheme('light')" id="theme-light" class="flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors <?= ($settings['theme'] ?? 'light') === 'light' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' ?>">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
              Light
            </button>
            <button type="button" onclick="setTheme('dark')" id="theme-dark" class="flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors <?= ($settings['theme'] ?? 'light') === 'dark' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' ?>">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
              Dark
            </button>
          </div>
          <input type="hidden" id="form-theme" name="theme" value="<?= e($settings['theme'] ?? 'light') ?>">
        </div>
      </div>
    </div>

    <!-- About -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">About</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">System information and version</p>
          </div>
        </div>
      </div>
      <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Application</p>
            <p class="text-sm text-gray-900 dark:text-white font-medium mt-1">School Management ERP</p>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Version</p>
            <p class="text-sm text-gray-900 dark:text-white font-medium mt-1">1.0.0</p>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Framework</p>
            <p class="text-sm text-gray-900 dark:text-white font-medium mt-1">Custom PHP MVC</p>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">PHP Version</p>
            <p class="text-sm text-gray-900 dark:text-white font-medium mt-1"><?= PHP_VERSION ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Save Button -->
    <div class="flex justify-end">
      <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Save Settings
      </button>
    </div>
  </form>
</div>

<script>
  function setTheme(theme) {
    document.getElementById('form-theme').value = theme;

    const lightBtn = document.getElementById('theme-light');
    const darkBtn = document.getElementById('theme-dark');

    if (theme === 'light') {
      lightBtn.className = 'flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm';
      darkBtn.className = 'flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200';
    } else {
      darkBtn.className = 'flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm';
      lightBtn.className = 'flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200';
    }

    // Apply theme preview immediately
    if (theme === 'dark') {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  }
</script>
