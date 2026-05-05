<?php
$slot = $slot ?? [];
$classes = $classes ?? [];
$subjects = $subjects ?? [];
$teachers = $teachers ?? [];
$days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
$title = 'Edit Timetable Slot';
$formAction = url('/timetable/' . ($slot['id'] ?? ''));
?>

<div class="max-w-2xl mx-auto space-y-6">
  <div class="flex items-center gap-4">
    <a href="<?= url('/timetable') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update timetable slot below</p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="p-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class <span class="text-red-500">*</span></label>
            <select id="class_id" name="class_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($classes as $c): ?>
              <option value="<?= e($c['id']) ?>" <?= ($slot['class_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject <span class="text-red-500">*</span></label>
            <select id="subject_id" name="subject_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($subjects as $s): ?>
              <option value="<?= e($s['id']) ?>" <?= ($slot['subject_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="day" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Day <span class="text-red-500">*</span></label>
            <select id="day" name="day" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($days as $d): ?>
              <option value="<?= e($d) ?>" <?= ($slot['day'] ?? '') === $d ? 'selected' : '' ?>><?= e($d) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teacher</label>
            <select id="teacher_id" name="teacher_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select teacher</option>
              <?php foreach($teachers as $t): ?>
              <option value="<?= e($t['id']) ?>" <?= ($slot['teacher_id'] ?? '') == $t['id'] ? 'selected' : '' ?>><?= e(trim(($t['firstName'] ?? '') . ' ' . ($t['lastName'] ?? ''))) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
            <input type="time" id="start_time" name="start_time" value="<?= e($slot['start_time'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time</label>
            <input type="time" id="end_time" name="end_time" value="<?= e($slot['end_time'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>
        <div>
          <label for="room" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Room</label>
          <input type="text" id="room" name="room" value="<?= e($slot['room'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. Room 101">
        </div>
      </div>
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/timetable') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">Update Slot</button>
      </div>
    </form>
  </div>
</div>
