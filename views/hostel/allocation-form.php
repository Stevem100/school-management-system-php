<?php
$students = $students ?? [];
$rooms = $rooms ?? [];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Allocate Room</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Assign a student to a hostel room and bed</p>
        </div>
        <a href="/hostel/allocations" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Allocations
        </a>
    </div>
</div>

<!-- Allocation Form -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <form method="POST" action="/hostel/allocations" class="p-6 space-y-6">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Left Column -->
            <div class="space-y-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Student & Room</h3>

                <div>
                    <label for="student_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Student <span class="text-red-500">*</span></label>
                    <select id="student_id" name="student_id" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">Select a Student</option>
                        <?php foreach ($students as $st): ?>
                            <option value="<?= htmlspecialchars($st['id']) ?>">
                                <?= htmlspecialchars(($st['first_name'] ?? '') . ' ' . ($st['last_name'] ?? '') . ' (' . ($st['admission_number'] ?? '') . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="room_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Room <span class="text-red-500">*</span></label>
                    <select id="room_id" name="room_id" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">Select a Room</option>
                        <?php foreach ($rooms as $rm): ?>
                            <?php
                                $capacity = (int)($rm['capacity'] ?? 1);
                                $occupied = (int)($rm['occupied'] ?? 0);
                                $hasSpace = $occupied < $capacity;
                            ?>
                            <?php if ($hasSpace): ?>
                                <option value="<?= htmlspecialchars($rm['id']) ?>" data-capacity="<?= $capacity ?>" data-occupied="<?= $occupied ?>">
                                    <?= htmlspecialchars($rm['name'] . ' — ' . $rm['room_type'] . ' (' . $occupied . '/' . $capacity . ' beds)') ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="bed_no" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Bed Number <span class="text-red-500">*</span></label>
                    <input type="number" id="bed_no" name="bed_no" required min="1" value="1" placeholder="Bed number" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Assign the specific bed number within the room</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Schedule & Notes</h3>

                <div>
                    <label for="check_in_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Check-in Date <span class="text-red-500">*</span></label>
                    <input type="date" id="check_in_date" name="check_in_date" required value="<?= date('Y-m-d') ?>" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>

                <div>
                    <label for="check_out_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Check-out Date</label>
                    <input type="date" id="check_out_date" name="check_out_date" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty for open-ended allocation</p>
                </div>

                <div>
                    <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Additional notes about this allocation..." class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white resize-y"></textarea>
                </div>
            </div>
        </div>

        <!-- Room Preview -->
        <div id="room-preview" class="hidden rounded-lg border border-emerald-200 bg-emerald-50 dark:border-emerald-800 dark:bg-emerald-900/20 p-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium text-emerald-800 dark:text-emerald-300">Room Details</span>
            </div>
            <p id="room-preview-text" class="text-xs text-emerald-700 dark:text-emerald-400"></p>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
            <a href="/hostel/allocations" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                Cancel
            </a>
            <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Allocate Room
                </span>
            </button>
        </div>
    </form>
</div>

<script>
    const roomSelect = document.getElementById('room_id');
    const bedInput = document.getElementById('bed_no');
    const roomPreview = document.getElementById('room-preview');
    const roomPreviewText = document.getElementById('room-preview-text');

    roomSelect.addEventListener('change', function() {
        const selected = roomSelect.options[roomSelect.selectedIndex];
        if (roomSelect.value) {
            const capacity = parseInt(selected.dataset.capacity || 1);
            const occupied = parseInt(selected.dataset.occupied || 0);
            const available = capacity - occupied;
            roomPreview.classList.remove('hidden');
            roomPreviewText.textContent = `${available} bed(s) available out of ${capacity} total. Occupied: ${occupied}.`;
            bedInput.max = capacity;
            if (parseInt(bedInput.value) > capacity) {
                bedInput.value = occupied + 1;
            }
        } else {
            roomPreview.classList.add('hidden');
        }
    });
</script>
