<?php $pageTitle = $pageTitle ?? 'Admission Form Fields'; ?>
<?php
    $fields = $fields ?? [];
    $sections = $sections ?? ['personal', 'academic', 'guardian', 'documents', 'other'];
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
        <span>Form Fields</span>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admission Form Fields</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage and configure the dynamic fields for the admission application form</p>
    </div>
    <a href="<?= url('/admission/fields/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Field
    </a>
  </div>

  <!-- Fields grouped by section -->
  <?php foreach ($sections as $section): ?>
    <?php $sectionFields = array_filter($fields, fn($f) => ($f['section'] ?? '') === $section); ?>
    <?php if (empty($sectionFields)) continue; ?>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
          <?php
            $sectionIcons = [
              'personal' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
              'academic' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
              'guardian' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
              'documents' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
              'other' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>',
            ];
            $sectionColors = [
              'personal' => 'text-emerald-600 dark:text-emerald-400',
              'academic' => 'text-blue-600 dark:text-blue-400',
              'guardian' => 'text-purple-600 dark:text-purple-400',
              'documents' => 'text-amber-600 dark:text-amber-400',
              'other' => 'text-gray-600 dark:text-gray-400',
            ];
          ?>
          <svg class="w-5 h-5 <?= $sectionColors[$section] ?? 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $sectionIcons[$section] ?? '' ?></svg>
          <?= e($sectionLabels[$section] ?? ucfirst($section)) ?>
          <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-600 dark:text-gray-300"><?= count($sectionFields) ?></span>
        </h2>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-900/50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-8"></th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Label</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Field Name</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Required</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Active</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <?php foreach ($sectionFields as $item): ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors <?= empty($item['is_active']) ? 'opacity-60' : '' ?>">
              <td class="px-4 py-3">
                <div class="flex flex-col gap-0.5">
                  <button onclick="moveField('up', <?= $item['id'] ?? 0 ?>)" class="p-0.5 text-gray-300 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="Move up">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                  </button>
                  <button onclick="moveField('down', <?= $item['id'] ?? 0 ?>)" class="p-0.5 text-gray-300 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="Move down">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                  </button>
                </div>
              </td>
              <td class="px-4 py-3">
                <span class="text-sm font-medium text-gray-900 dark:text-white"><?= e($item['field_label'] ?? '') ?></span>
              </td>
              <td class="px-4 py-3 hidden sm:table-cell">
                <code class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded"><?= e($item['field_name'] ?? '') ?></code>
              </td>
              <td class="px-4 py-3">
                <?php
                  $typeColors = [
                    'text' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
                    'email' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400',
                    'phone' => 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400',
                    'textarea' => 'bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400',
                    'select' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400',
                    'radio' => 'bg-pink-50 dark:bg-pink-900/20 text-pink-700 dark:text-pink-400',
                    'checkbox' => 'bg-cyan-50 dark:bg-cyan-900/20 text-cyan-700 dark:text-cyan-400',
                    'date' => 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400',
                    'number' => 'bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-400',
                    'file' => 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400',
                  ];
                  $type = $item['field_type'] ?? 'text';
                  $badgeClass = $typeColors[$type] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
                ?>
                <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium <?= $badgeClass ?>"><?= e(ucfirst($type)) ?></span>
              </td>
              <td class="px-4 py-3 hidden md:table-cell">
                <?php if (!empty($item['is_required'])): ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:text-red-400">
                  <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                  Required
                </span>
                <?php else: ?>
                <span class="text-xs text-gray-400 dark:text-gray-500">Optional</span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3 hidden md:table-cell">
                <?php if (!empty($item['is_active'])): ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400">
                  <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                  Active
                </span>
                <?php else: ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 dark:bg-gray-700 px-2.5 py-0.5 text-xs font-medium text-gray-500 dark:text-gray-400">
                  <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                  Inactive
                </span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-1">
                  <a href="<?= url('/admission/fields/' . ($item['id'] ?? '') . '/edit') ?>" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </a>
                  <button onclick="confirmDeleteField(<?= $item['id'] ?? 0 ?>, '<?= e($item['field_label'] ?? '') ?>')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endforeach; ?>

  <!-- Empty State -->
  <?php if(empty($fields)): ?>
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
      <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
      </svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No form fields configured</p>
      <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Start building your admission form by adding fields</p>
      <a href="<?= url('/admission/fields/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add First Field
      </a>
    </div>
  </div>
  <?php endif; ?>

  <!-- Delete Confirmation Modal -->
  <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)closeDeleteModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-sm mx-4">
      <div class="p-6 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
          <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Field</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete the field "<span id="delete-field-name" class="font-medium text-gray-700 dark:text-gray-300"></span>"? This action cannot be undone.</p>
        <div class="flex gap-3">
          <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
          <form id="delete-form" method="POST" class="flex-1">
            <?= csrf_field() ?>
            <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function confirmDeleteField(id, name) {
      const deleteModal = document.getElementById('delete-modal');
      document.getElementById('delete-field-name').textContent = name;
      document.getElementById('delete-form').action = '<?= url("/admission/fields") ?>/' + id + '/delete';
      deleteModal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    function moveField(direction, id) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '<?= url("/admission/fields") ?>/' + id + '/reorder';

      const csrfInput = document.createElement('input');
      csrfInput.type = 'hidden';
      csrfInput.name = '_token';
      csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      form.appendChild(csrfInput);

      const dirInput = document.createElement('input');
      dirInput.type = 'hidden';
      dirInput.name = 'direction';
      dirInput.value = direction;
      form.appendChild(dirInput);

      document.body.appendChild(form);
      form.submit();
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeDeleteModal();
      }
    });
  </script>
</div>
