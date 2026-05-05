<?php $pageTitle = $pageTitle ?? 'Website Settings'; ?>
<?php $currentPage = $currentPage ?? 'website'; ?>
<?php
    $settings = $settings ?? [];
    $activeTab = $activeTab ?? 'general';
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Website Settings</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure your school website appearance and behavior</p>
    </div>
  </div>

  <form method="POST" action="<?= url('/website/settings') ?>" class="space-y-6">
    <?= csrf_field() ?>

    <!-- Tabs Navigation -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
        <nav class="flex -mb-px">
          <button type="button" onclick="switchTab('general')" id="tab-general" class="tab-btn flex items-center gap-2 px-5 py-3.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap <?= $activeTab === 'general' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            General
          </button>
          <button type="button" onclick="switchTab('appearance')" id="tab-appearance" class="tab-btn flex items-center gap-2 px-5 py-3.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap <?= $activeTab === 'appearance' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
            Appearance
          </button>
          <button type="button" onclick="switchTab('social')" id="tab-social" class="tab-btn flex items-center gap-2 px-5 py-3.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap <?= $activeTab === 'social' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
            Social Media
          </button>
          <button type="button" onclick="switchTab('seo')" id="tab-seo" class="tab-btn flex items-center gap-2 px-5 py-3.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap <?= $activeTab === 'seo' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            SEO
          </button>
          <button type="button" onclick="switchTab('advanced')" id="tab-advanced" class="tab-btn flex items-center gap-2 px-5 py-3.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap <?= $activeTab === 'advanced' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Advanced
          </button>
        </nav>
      </div>

      <!-- Tab: General -->
      <div id="panel-general" class="tab-panel p-6 space-y-5 <?= $activeTab !== 'general' ? 'hidden' : '' ?>">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="site_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Title <span class="text-red-500">*</span></label>
            <input type="text" id="site_title" name="site_title" value="<?= e($settings['site_title'] ?? '') ?>" placeholder="e.g. Greenfield Academy" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="site_tagline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Tagline</label>
            <input type="text" id="site_tagline" name="site_tagline" value="<?= e($settings['site_tagline'] ?? '') ?>" placeholder="e.g. Nurturing Tomorrow's Leaders" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <!-- Toggle Options -->
        <div class="space-y-3 pt-2">
          <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Feature Visibility</p>

          <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Website Active</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">Enable or disable the public website</p>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
              <input type="checkbox" name="is_active" value="1" class="peer sr-only" <?= ($settings['is_active'] ?? 1) ? 'checked' : '' ?>>
              <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
            </label>
          </div>

          <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Show Admission Link</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">Display an admission inquiry link on the website</p>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
              <input type="checkbox" name="show_admission_link" value="1" class="peer sr-only" <?= ($settings['show_admission_link'] ?? 0) ? 'checked' : '' ?>>
              <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
            </label>
          </div>

          <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Show Contact Form</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">Enable the contact form on the website</p>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
              <input type="checkbox" name="show_contact_form" value="1" class="peer sr-only" <?= ($settings['show_contact_form'] ?? 0) ? 'checked' : '' ?>>
              <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
            </label>
          </div>

          <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Show Gallery</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">Display the photo gallery section</p>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
              <input type="checkbox" name="show_gallery" value="1" class="peer sr-only" <?= ($settings['show_gallery'] ?? 0) ? 'checked' : '' ?>>
              <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
            </label>
          </div>

          <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Show Testimonials</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">Display the testimonials section</p>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
              <input type="checkbox" name="show_testimonials" value="1" class="peer sr-only" <?= ($settings['show_testimonials'] ?? 0) ? 'checked' : '' ?>>
              <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
            </label>
          </div>

          <div class="flex items-center justify-between rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-white">Show News & Events</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">Display the news and events section</p>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
              <input type="checkbox" name="show_news_events" value="1" class="peer sr-only" <?= ($settings['show_news_events'] ?? 0) ? 'checked' : '' ?>>
              <div class="h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-emerald-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-full"></div>
            </label>
          </div>
        </div>
      </div>

      <!-- Tab: Appearance -->
      <div id="panel-appearance" class="tab-panel p-6 space-y-5 <?= $activeTab !== 'appearance' ? 'hidden' : '' ?>">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label for="primary_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Primary Color</label>
            <div class="flex items-center gap-2">
              <input type="color" name="primary_color" value="<?= e($settings['primary_color'] ?? '#059669') ?>" class="h-9 w-12 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer bg-transparent p-0.5">
              <input type="text" value="<?= e($settings['primary_color'] ?? '#059669') ?>" class="flex-1 px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-mono" disabled>
            </div>
          </div>
          <div>
            <label for="secondary_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Secondary Color</label>
            <div class="flex items-center gap-2">
              <input type="color" name="secondary_color" value="<?= e($settings['secondary_color'] ?? '#1f2937') ?>" class="h-9 w-12 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer bg-transparent p-0.5">
              <input type="text" value="<?= e($settings['secondary_color'] ?? '#1f2937') ?>" class="flex-1 px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-mono" disabled>
            </div>
          </div>
          <div>
            <label for="accent_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Accent Color</label>
            <div class="flex items-center gap-2">
              <input type="color" name="accent_color" value="<?= e($settings['accent_color'] ?? '#f59e0b') ?>" class="h-9 w-12 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer bg-transparent p-0.5">
              <input type="text" value="<?= e($settings['accent_color'] ?? '#f59e0b') ?>" class="flex-1 px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-mono" disabled>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="header_layout" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Header Layout</label>
            <select name="header_layout" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="default" <?= ($settings['header_layout'] ?? 'default') === 'default' ? 'selected' : '' ?>>Default (Logo Left)</option>
              <option value="centered" <?= ($settings['header_layout'] ?? '') === 'centered' ? 'selected' : '' ?>>Centered Logo</option>
              <option value="split" <?= ($settings['header_layout'] ?? '') === 'split' ? 'selected' : '' ?>>Split (Logo + Contact)</option>
              <option value="minimal" <?= ($settings['header_layout'] ?? '') === 'minimal' ? 'selected' : '' ?>>Minimal</option>
            </select>
          </div>
          <div>
            <label for="logo_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo URL</label>
            <input type="text" name="logo_url" value="<?= e($settings['logo_url'] ?? '') ?>" placeholder="https://example.com/logo.png" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <div>
          <label for="favicon_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Favicon URL</label>
          <input type="text" name="favicon_url" value="<?= e($settings['favicon_url'] ?? '') ?>" placeholder="https://example.com/favicon.ico" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>

        <div>
          <label for="footer_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Footer Text</label>
          <textarea name="footer_text" rows="2" placeholder="e.g. &copy; 2024 Greenfield Academy. All rights reserved." class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"><?= e($settings['footer_text'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- Tab: Social Media -->
      <div id="panel-social" class="tab-panel p-6 space-y-5 <?= $activeTab !== 'social' ? 'hidden' : '' ?>">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              <span class="inline-flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Facebook URL
              </span>
            </label>
            <input type="url" name="facebook_url" value="<?= e($settings['facebook_url'] ?? '') ?>" placeholder="https://facebook.com/yourschool" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="twitter_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              <span class="inline-flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gray-800 dark:text-gray-200" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                Twitter / X URL
              </span>
            </label>
            <input type="url" name="twitter_url" value="<?= e($settings['twitter_url'] ?? '') ?>" placeholder="https://x.com/yourschool" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              <span class="inline-flex items-center gap-1.5">
                <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                Instagram URL
              </span>
            </label>
            <input type="url" name="instagram_url" value="<?= e($settings['instagram_url'] ?? '') ?>" placeholder="https://instagram.com/yourschool" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="youtube_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              <span class="inline-flex items-center gap-1.5">
                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                YouTube URL
              </span>
            </label>
            <input type="url" name="youtube_url" value="<?= e($settings['youtube_url'] ?? '') ?>" placeholder="https://youtube.com/@yourschool" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>
      </div>

      <!-- Tab: SEO -->
      <div id="panel-seo" class="tab-panel p-6 space-y-5 <?= $activeTab !== 'seo' ? 'hidden' : '' ?>">
        <div>
          <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Description</label>
          <textarea name="meta_description" rows="3" placeholder="A brief description of your school website for search engines (150-160 characters recommended)" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"><?= e($settings['meta_description'] ?? '') ?></textarea>
          <p class="text-xs text-gray-400 mt-1"><?= strlen($settings['meta_description'] ?? '') ?>/160 characters</p>
        </div>
        <div>
          <label for="meta_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Keywords</label>
          <input type="text" name="meta_keywords" value="<?= e($settings['meta_keywords'] ?? '') ?>" placeholder="e.g. school, academy, education, best school" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          <p class="text-xs text-gray-400 mt-1">Separate keywords with commas</p>
        </div>
        <div>
          <label for="google_analytics_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Google Analytics ID</label>
          <input type="text" name="google_analytics_id" value="<?= e($settings['google_analytics_id'] ?? '') ?>" placeholder="e.g. G-XXXXXXXXXX" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          <p class="text-xs text-gray-400 mt-1">Enter your Google Analytics Measurement ID</p>
        </div>
      </div>

      <!-- Tab: Advanced -->
      <div id="panel-advanced" class="tab-panel p-6 space-y-5 <?= $activeTab !== 'advanced' ? 'hidden' : '' ?>">
        <div>
          <label for="custom_css" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Custom CSS</label>
          <textarea name="custom_css" rows="8" placeholder="/* Add your custom CSS here */
body {
  /* your custom styles */
}" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 font-mono"><?= e($settings['custom_css'] ?? '') ?></textarea>
        </div>
        <div>
          <label for="custom_js" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Custom JavaScript</label>
          <textarea name="custom_js" rows="8" placeholder="// Add your custom JavaScript here
console.log('Custom JS loaded');" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 font-mono"><?= e($settings['custom_js'] ?? '') ?></textarea>
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
  function switchTab(tabName) {
    // Hide all panels
    document.querySelectorAll('.tab-panel').forEach(panel => {
      panel.classList.add('hidden');
    });

    // Deactivate all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.classList.remove('border-emerald-600', 'text-emerald-600', 'dark:text-emerald-400');
      btn.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
    });

    // Show selected panel
    const panel = document.getElementById('panel-' + tabName);
    if (panel) {
      panel.classList.remove('hidden');
    }

    // Activate selected tab
    const tab = document.getElementById('tab-' + tabName);
    if (tab) {
      tab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
      tab.classList.add('border-emerald-600', 'text-emerald-600', 'dark:text-emerald-400');
    }
  }

  // Sync color pickers with their text inputs
  document.querySelectorAll('input[type="color"]').forEach(picker => {
    picker.addEventListener('input', function() {
      const textInput = this.parentElement.querySelector('input[type="text"]');
      if (textInput) {
        textInput.value = this.value;
      }
    });
  });
</script>
