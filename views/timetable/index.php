<?php $pageTitle = 'Timetable'; ?>
<?php
$classes = $classes ?? [];
$teachers = $teachers ?? [];
$subjects = $subjects ?? [];
$grid = $grid ?? [];
$days = $days ?? ['Monday','Tuesday','Wednesday','Thursday','Friday'];
$periods = $periods ?? ['08:00','09:00','10:00','11:00','12:00','14:00','15:00'];
$classId = $classId ?? '';
$teacherId = $teacherId ?? '';
$selectedClassName = $selectedClassName ?? '';
$selectedTeacherName = $selectedTeacherName ?? '';

// Color palette for subject blocks
$subjectColors = [
    'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-900/40 dark:text-emerald-300 dark:border-emerald-700',
    'bg-violet-100 text-violet-800 border-violet-300 dark:bg-violet-900/40 dark:text-violet-300 dark:border-violet-700',
    'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-900/40 dark:text-amber-300 dark:border-amber-700',
    'bg-sky-100 text-sky-800 border-sky-300 dark:bg-sky-900/40 dark:text-sky-300 dark:border-sky-700',
    'bg-rose-100 text-rose-800 border-rose-300 dark:bg-rose-900/40 dark:text-rose-300 dark:border-rose-700',
    'bg-teal-100 text-teal-800 border-teal-300 dark:bg-teal-900/40 dark:text-teal-300 dark:border-teal-700',
    'bg-orange-100 text-orange-800 border-orange-300 dark:bg-orange-900/40 dark:text-orange-300 dark:border-orange-700',
    'bg-pink-100 text-pink-800 border-pink-300 dark:bg-pink-900/40 dark:text-pink-300 dark:border-pink-700',
];
?>

<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Timetable</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">View and manage the weekly class schedule</p>
    </div>
    <?php if(!empty($classId)): ?>
    <button onclick="openSlotModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Slot
    </button>
    <?php endif; ?>
  </div>

  <!-- Filters -->
  <form method="GET" action="<?= url('timetable') ?>" class="flex flex-col sm:flex-row gap-3">
    <select name="class_id" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent flex-1 sm:flex-none sm:min-w-[200px]">
      <option value="">Filter by Class</option>
      <?php foreach($classes as $cls): ?>
        <option value="<?= e($cls['id'] ?? '') ?>" <?= $classId === ($cls['id'] ?? '') ? 'selected' : '' ?>><?= e($cls['name'] ?? '') ?></option>
      <?php endforeach; ?>
    </select>
    <select name="teacher_id" class="px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent flex-1 sm:flex-none sm:min-w-[200px]">
      <option value="">Filter by Teacher</option>
      <?php foreach($teachers as $tch): ?>
        <?php $tName = trim(($tch['firstName'] ?? '') . ' ' . ($tch['lastName'] ?? '')); ?>
        <option value="<?= e($tch['id'] ?? '') ?>" <?= $teacherId === ($tch['id'] ?? '') ? 'selected' : '' ?>><?= e($tName) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">View</button>
  </form>

  <?php if(empty($classId) && empty($teacherId)): ?>
  <!-- Empty State -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
      <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Select a Class or Teacher</p>
      <p class="text-sm mt-1">Choose a filter above to view the weekly timetable</p>
    </div>
  </div>
  <?php else: ?>
  <!-- Timetable Grid -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
      <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
        <?php if(!empty($selectedClassName)): ?>
          Class: <?= e($selectedClassName) ?>
        <?php elseif(!empty($selectedTeacherName)): ?>
          Teacher: <?= e($selectedTeacherName) ?>
        <?php endif; ?>
      </h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <thead class="bg-gray-50 dark:bg-gray-900">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-r border-gray-100 dark:border-gray-700 w-24">Time</th>
            <?php foreach($days as $day): ?>
              <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-r border-gray-100 dark:border-gray-700 last:border-r-0">
                <?= substr($day, 0, 3) ?>
              </th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($periods as $i => $time): ?>
          <tr>
            <td class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border-b border-r border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 whitespace-nowrap">
              <?= e($time) ?>
            </td>
            <?php foreach($days as $day): ?>
            <?php
              $slot = $grid[$day][$time] ?? null;
              $colorIdx = 0;
              if ($slot) {
                $subjName = $slot['subjectName'] ?? 'N/A';
                $subjCode = $slot['subjectCode'] ?? '';
                $tchName = $slot['teacherName'] ?? '';
                $room = $slot['room'] ?? '';
                $colorIdx = crc32($subjCode ?: $subjName) % count($subjectColors);
              }
              $color = $subjectColors[abs($colorIdx)] ?? $subjectColors[0];
            ?>
            <td class="px-1 py-1 border-b border-r border-gray-100 dark:border-gray-700 last:border-r-0 align-top">
              <?php if($slot): ?>
              <div class="rounded-lg border p-2 min-h-[60px] <?= $color ?> cursor-pointer group relative" onclick="openSlotModal('<?= e($slot['id'] ?? '') ?>')">
                <div class="flex items-center justify-between mb-1">
                  <span class="text-xs font-bold"><?= e($subjCode) ?></span>
                  <div class="hidden group-hover:flex items-center gap-1">
                    <button onclick="event.stopPropagation(); openSlotModal('<?= e($slot['id'] ?? '') ?>')" class="p-0.5 hover:bg-white/30 rounded">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button onclick="event.stopPropagation(); deleteSlot('<?= e($slot['id'] ?? '') ?>')" class="p-0.5 hover:bg-white/30 rounded text-red-600">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                  </div>
                </div>
                <p class="text-xs font-medium leading-tight"><?= e($subjName) ?></p>
                <?php if($room): ?>
                  <p class="text-[10px] mt-0.5 opacity-70"><?= e($room) ?></p>
                <?php endif; ?>
                <?php if($tchName): ?>
                  <p class="text-[10px] mt-0.5 opacity-70"><?= e($tchName) ?></p>
                <?php endif; ?>
                <p class="text-[10px] opacity-50"><?= e($slot['startTime'] ?? '') ?> - <?= e($slot['endTime'] ?? '') ?></p>
              </div>
              <?php else: ?>
              <div class="min-h-[60px] flex items-center justify-center">
                <button onclick="openSlotModalNew('<?= e($day) ?>', '<?= e($time) ?>')" class="p-1 text-gray-300 dark:text-gray-600 hover:text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
              </div>
              <?php endif; ?>
            </td>
            <?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- Add/Edit Slot Modal -->
<div id="slotModal" class="fixed inset-0 z-50 hidden">
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeSlotModal()"></div>
  <div class="fixed inset-0 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md relative z-10">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 id="slotModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Timetable Slot</h3>
        <button onclick="closeSlotModal()" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="slotForm" onsubmit="submitSlot(event)">
        <input type="hidden" id="slotId" value="">
        <div class="px-6 py-4 space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Day <span class="text-red-500">*</span></label>
            <select name="day_of_week" id="slotDay" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($days as $day): ?>
              <option value="<?= e($day) ?>"><?= e($day) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time <span class="text-red-500">*</span></label>
              <select name="start_time" id="slotStart" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <?php foreach($periods as $p): ?>
                <option value="<?= e($p) ?>"><?= e($p) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time <span class="text-red-500">*</span></label>
              <select name="end_time" id="slotEnd" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                <?php foreach($periods as $p): ?>
                <option value="<?= e($p) ?>"><?= e($p) ?></option>
                <?php endforeach; ?>
                <option value="16:00">16:00</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject <span class="text-red-500">*</span></label>
            <select name="subject_id" id="slotSubject" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($subjects as $subj): ?>
              <option value="<?= e($subj['id'] ?? '') ?>"><?= e($subj['name'] ?? '') ?> (<?= e($subj['code'] ?? '') ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teacher <span class="text-red-500">*</span></label>
            <select name="teacher_id" id="slotTeacher" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <?php foreach($teachers as $tch): ?>
              <?php $tName = trim(($tch['firstName'] ?? '') . ' ' . ($tch['lastName'] ?? '')); ?>
              <option value="<?= e($tch['id'] ?? '') ?>"><?= e($tName) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Room</label>
            <input type="text" name="room" id="slotRoom" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent" placeholder="e.g. Room 101">
          </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
          <button type="button" onclick="closeSlotModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">Cancel</button>
          <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">Save Slot</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openSlotModal(id) {
  document.getElementById('slotModal').classList.remove('hidden');
  document.getElementById('slotForm').reset();
  document.getElementById('slotId').value = '';

  if (id) {
    document.getElementById('slotModalTitle').textContent = 'Edit Slot';
    fetch('/api/timetable?class_id=<?= urlencode($classId) ?>')
      .then(r => r.json())
      .then(res => {
        if (res.success && res.data) {
          const slot = res.data.find(s => s.id === id);
          if (slot) {
            document.getElementById('slotId').value = slot.id;
            document.getElementById('slotDay').value = slot.dayOfWeek || '';
            document.getElementById('slotStart').value = slot.startTime || '';
            document.getElementById('slotEnd').value = slot.endTime || '';
            document.getElementById('slotSubject').value = slot.subjectId || '';
            document.getElementById('slotTeacher').value = slot.teacherId || '';
            document.getElementById('slotRoom').value = slot.room || '';
          }
        }
      });
  } else {
    document.getElementById('slotModalTitle').textContent = 'Add Slot';
  }
}

function openSlotModalNew(day, time) {
  openSlotModal();
  document.getElementById('slotDay').value = day;
  document.getElementById('slotStart').value = time;
}

function closeSlotModal() { document.getElementById('slotModal').classList.add('hidden'); }

function submitSlot(e) {
  e.preventDefault();
  const id = document.getElementById('slotId').value;
  const url = id ? '/api/timetable/' + id : '/api/timetable';
  const method = id ? 'PUT' : 'POST';
  const formData = new FormData(document.getElementById('slotForm'));

  fetch(url, {
    method, headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      class_id: '<?= e($classId) ?>',
      day_of_week: formData.get('day_of_week'),
      start_time: formData.get('start_time'),
      end_time: formData.get('end_time'),
      subject_id: formData.get('subject_id'),
      teacher_id: formData.get('teacher_id'),
      room: formData.get('room'),
    })
  })
  .then(r => r.json())
  .then(res => { if (res.success) window.location.reload(); else alert(res.error || 'Error'); })
  .catch(err => alert('Failed: ' + err.message));
}

function deleteSlot(id) {
  if (!confirm('Delete this timetable slot?')) return;
  fetch('/api/timetable/' + id, { method: 'DELETE' })
    .then(r => r.json())
    .then(res => { if (res.success) window.location.reload(); else alert(res.error || 'Failed'); });
}
</script>
