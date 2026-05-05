<?php $pageTitle = $pageTitle ?? (($mode ?? 'create') === 'edit' ? 'Edit Form Field' : 'Add Form Field'); ?>
<?php
    $mode = $mode ?? 'create';
    $field = $field ?? null;
    $sections = $sections ?? ['personal', 'academic', 'guardian', 'documents', 'other'];
    $types = $types ?? ['text', 'email', 'phone', 'textarea', 'select', 'radio', 'checkbox', 'date', 'number', 'file'];
    $errors = $errors ?? [];

    $sectionLabels = $sectionLabels ?? [
        'personal' => 'Personal Information',
        'academic' => 'Academic Information',
        'guardian' => 'Guardian Information',
        'documents' => 'Documents',
        'other' => 'Other',
    ];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
        <a href="<?= url('/admission') ?>" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Admission</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="<?= url('/admission/fields') ?>" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Form Fields</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span><?= $mode === 'edit' ? 'Edit' : 'Add' ?></span>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $mode === 'edit' ? 'Edit Form Field' : 'Add Form Field' ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= $mode === 'edit' ? 'Update the field configuration for the admission form' : 'Configure a new field for the admission application form' ?></p>
    </div>
  </div>

  <form method="POST" action="<?= $mode === 'edit' ? url('/admission/fields/' . ($field['id'] ?? '')) : url('/admission/fields') ?>" class="space-y-6">
    <?= csrf_field() ?>
    <?php if ($mode === 'edit'): ?>
    <input type="hidden" name="_method" value="PUT">
    <?php endif; ?>

    <!-- Field Configuration -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
          <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
          Field Configuration
        </h2>
      </div>
      <div class="p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="field_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Field Name <span class="text-red-500">*</span></label>
            <input type="text" id="field_name" name="field_name" required value="<?= e($field['field_name'] ?? '') ?>" placeholder="e.g. first_name" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 font-mono">
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Unique identifier (lowercase, underscores only)</p>
            <?php if (!empty($errors['field_name'])): ?>
            <p class="mt-1 text-xs text-red-500 dark:text-red-400"><?= e($errors['field_name']) ?></p>
            <?php endif; ?>
          </div>
          <div>
            <label for="field_label" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Field Label <span class="text-red-500">*</span></label>
            <input type="text" id="field_label" name="field_label" required value="<?= e($field['field_label'] ?? '') ?>" placeholder="e.g. First Name" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Display label shown on the form</p>
            <?php if (!empty($errors['field_label'])): ?>
            <p class="mt-1 text-xs text-red-500 dark:text-red-400"><?= e($errors['field_label']) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="field_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Field Type <span class="text-red-500">*</span></label>
            <select id="field_type" name="field_type" required onchange="toggleFieldOptions()" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select Type</option>
              <?php foreach ($types as $t): ?>
              <option value="<?= e($t) ?>" <?= ($field['field_type'] ?? '') === $t ? 'selected' : '' ?>><?= e(ucfirst($t)) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['field_type'])): ?>
            <p class="mt-1 text-xs text-red-500 dark:text-red-400"><?= e($errors['field_type']) ?></p>
            <?php endif; ?>
          </div>
          <div>
            <label for="section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Section <span class="text-red-500">*</span></label>
            <select id="section" name="section" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select Section</option>
              <?php foreach ($sections as $s): ?>
              <option value="<?= e($s) ?>" <?= ($field['section'] ?? '') === $s ? 'selected' : '' ?>><?= e($sectionLabels[$s] ?? ucfirst($s)) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['section'])): ?>
            <p class="mt-1 text-xs text-red-500 dark:text-red-400"><?= e($errors['section']) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Field Options (shown for select/radio/checkbox) -->
        <div id="field-options-wrapper" class="<?= in_array($field['field_type'] ?? '', ['select', 'radio', 'checkbox']) ? '' : 'hidden' ?>">
          <label for="field_options" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Field Options</label>
          <textarea id="field_options" name="field_options" rows="4" placeholder="Enter one option per line&#10;e.g.&#10;Option A&#10;Option B&#10;Option C" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none"><?= e($field['field_options'] ?? '') ?></textarea>
          <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">One option per line. Only required for Select, Radio, and Checkbox types.</p>
          <?php if (!empty($errors['field_options'])): ?>
          <p class="mt-1 text-xs text-red-500 dark:text-red-400"><?= e($errors['field_options']) ?></p>
          <?php endif; ?>
        </div>

        <div>
          <label for="placeholder" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Placeholder</label>
          <input type="text" id="placeholder" name="placeholder" value="<?= e($field['placeholder'] ?? '') ?>" placeholder="e.g. Enter your first name" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>

        <div>
          <label for="validation_rules" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Validation Rules</label>
          <input type="text" id="validation_rules" name="validation_rules" value="<?= e($field['validation_rules'] ?? '') ?>" placeholder="e.g. min:3|max:50|alpha" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 font-mono">
          <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Pipe-separated validation rules (e.g., min:3|max:50|numeric)</p>
        </div>

        <div>
          <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
          <input type="number" id="sort_order" name="sort_order" value="<?= e($field['sort_order'] ?? 0) ?>" min="0" placeholder="0" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 sm:w-32">
          <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Lower numbers appear first within the section</p>
        </div>
      </div>
    </div>

    <!-- Field Options & Toggles -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
          <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
          Field Behavior
        </h2>
      </div>
      <div class="p-6 space-y-5">
        <!-- Required Toggle -->
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Required Field</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Applicants must fill in this field to submit</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="is_required" value="1" <?= (!empty($field['is_required'])) ? 'checked' : '' ?> class="sr-only peer">
            <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 peer-focus:ring-offset-2 dark:peer-focus:ring-offset-gray-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
          </label>
        </div>

        <div class="border-t border-gray-100 dark:border-gray-700"></div>

        <!-- Active Toggle -->
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Active Field</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Inactive fields are hidden from the admission form</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="is_active" value="1" <?= ($field === null || !empty($field['is_active'])) ? 'checked' : '' ?> class="sr-only peer">
            <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 peer-focus:ring-offset-2 dark:peer-focus:ring-offset-gray-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
          </label>
        </div>
      </div>
    </div>

    <!-- Submit Actions -->
    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
      <a href="<?= url('/admission/fields') ?>" class="px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-center">
        Cancel
      </a>
      <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm inline-flex items-center gap-2 justify-center">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <?= $mode === 'edit' ? 'Update Field' : 'Create Field' ?>
      </button>
    </div>
  </form>
</div>

<script>
  function toggleFieldOptions() {
    const type = document.getElementById('field_type').value;
    const wrapper = document.getElementById('field-options-wrapper');
    if (['select', 'radio', 'checkbox'].includes(type)) {
      wrapper.classList.remove('hidden');
    } else {
      wrapper.classList.add('hidden');
    }
  }

  // Initialize on page load
  toggleFieldOptions();
</script>
