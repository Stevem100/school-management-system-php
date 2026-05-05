<?php
$entry = $entry ?? [];
$classes = $classes ?? [];
$subjects = $subjects ?? [];
$teachers = $teachers ?? [];
$rooms = $rooms ?? [];
$title = 'Add Timetable Entry';
$formAction = url('/timetable');

$days = ['Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday'];

// Generate time slots
$timeSlots = [
  '07:00 - 08:00' => '07:00 – 08:00',
  '08:00 - 09:00' => '08:00 – 09:00',
  '09:00 - 10:00' => '09:00 – 10:00',
  '10:00 - 10:30' => '10:00 – 10:30 (Break)',
  '10:30 - 11:30' => '10:30 – 11:30',
  '11:30 - 12:30' => '11:30 – 12:30',
  '12:30 - 13:30' => '12:30 – 13:30 (Lunch)',
  '13:30 - 14:30' => '13:30 – 14:30',
  '14:30 - 15:30' => '14:30 – 15:30',
  '15:30 - 16:30' => '15:30 – 16:30',
];

$types = ['lesson' => 'Regular Lesson', 'lab' => 'Lab / Practical', 'break' => 'Break', 'assembly' => 'Assembly', 'sports' => 'Sports', 'other' => 'Other'];
$statuses = ['active' => 'Active', 'draft' => 'Draft', 'cancelled' => 'Cancelled'];
$academicYears = ['2024-2025','2025-2026','2023-2024'];
$terms = ['Term 1','Term 2','Term 3'];
?>

<div class="max-w-2xl mx-auto space-y-6">
  <!-- Page Header -->
  <div class="flex items-center gap-4">
    <a href="<?= url('/timetable') ?>" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $title ?></h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add a new entry to the weekly timetable</p>
    </div>
  </div>

  <!-- Form Card -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <form method="POST" action="<?= $formAction ?>">
      <?= csrf_field() ?>

      <div class="p-6 space-y-5">
        <!-- Schedule Info -->
        <div class="flex items-center gap-2 mb-1">
          <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Schedule</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
          <div>
            <label for="day" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Day <span class="text-red-500">*</span></label>
            <select id="day" name="day" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select day</option>
              <?php foreach ($days as $d): ?>
              <option value="<?= e($d) ?>" <?= ($entry['day'] ?? '') === $d ? 'selected' : '' ?>><?= e($d) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="time_slot" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time Slot <span class="text-red-500">*</span></label>
            <select id="time_slot" name="time_slot" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select time slot</option>
              <?php foreach ($timeSlots as $k => $v): ?>
              <option value="<?= e($k) ?>" <?= ($entry['time_slot'] ?? '') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
            <input type="time" id="start_time" name="start_time" value="<?= e($entry['start_time'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
          <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time</label>
            <input type="time" id="end_time" name="end_time" value="<?= e($entry['end_time'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
          </div>
        </div>

        <!-- Class & Subject Assignment -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
          <div class="flex items-center gap-2 mb-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
              <svg class="h-4 w-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Assignment</h2>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class <span class="text-red-500">*</span></label>
              <select id="class_id" name="class_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">Select class</option>
                <?php foreach ($classes as $c): ?>
                <option value="<?= e($c['id']) ?>" <?= ($entry['class_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject <span class="text-red-500">*</span></label>
              <select id="subject_id" name="subject_id" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">Select subject</option>
                <?php foreach ($subjects as $s): ?>
                <option value="<?= e($s['id']) ?>" <?= ($entry['subject_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= e($s['name'] ?? $s['code'] ?? '') ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label for="teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teacher</label>
              <select id="teacher_id" name="teacher_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">Select teacher</option>
                <?php foreach ($teachers as $t): ?>
                <option value="<?= e($t['id']) ?>" <?= ($entry['teacher_id'] ?? '') == $t['id'] ? 'selected' : '' ?>><?= e(($t['first_name'] ?? '') . ' ' . ($t['last_name'] ?? '')) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label for="room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Room / Venue</label>
              <select id="room_id" name="room_id" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <option value="">Select room</option>
                <?php foreach ($rooms as $r): ?>
                <option value="<?= e($r['id']) ?>" <?= ($entry['room_id'] ?? '') == $r['id'] ? 'selected' : '' ?>><?= e($r['name'] ?? $r['room_number'] ?? '') ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <!-- Additional Details -->
        <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
          <div class="flex items-center gap-2 mb-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
              <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Details</h2>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
              <select id="type" name="type" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <?php foreach ($types as $k => $v): ?>
                <option value="<?= e($k) ?>" <?= ($entry['type'] ?? 'lesson') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label for="academic_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year</label>
              <select id="academic_year" name="academic_year" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <?php foreach ($academicYears as $ay): ?>
                <option value="<?= e($ay) ?>" <?= ($entry['academic_year'] ?? '') === $ay ? 'selected' : '' ?>><?= e($ay) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label for="term" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Term</label>
              <select id="term" name="term" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <?php foreach ($terms as $t): ?>
                <option value="<?= e($t) ?>" <?= ($entry['term'] ?? '') === $t ? 'selected' : '' ?>><?= e($t) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="mt-4">
            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
            <textarea id="notes" name="notes" rows="2" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400 resize-none" placeholder="Any additional notes for this timetable entry..."><?= e($entry['notes'] ?? '') ?></textarea>
          </div>

          <div class="mt-4">
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach ($statuses as $k => $v): ?>
              <option value="<?= e($k) ?>" <?= ($entry['status'] ?? 'active') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
        <a href="<?= url('/timetable') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
          <span class="inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Entry
          </span>
        </button>
      </div>
    </form>
  </div>
</div>
