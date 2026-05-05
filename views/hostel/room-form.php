<?php
$room = $room ?? null;
$isEdit = !empty($room);
$formAction = $isEdit ? "/hostel/rooms/{$room['id']}" : "/hostel/rooms";
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $isEdit ? 'Edit Room' : 'Add New Room' ?></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= $isEdit ? 'Update room information' : 'Add a new room to the hostel' ?></p>
        </div>
        <a href="/hostel/rooms" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Rooms
        </a>
    </div>
</div>

<!-- Room Form -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="p-6 space-y-6">
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Left Column -->
            <div class="space-y-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Room Information</h3>

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Room Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" required value="<?= htmlspecialchars($room['name'] ?? '') ?>" placeholder="e.g. Room 101" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="floor" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Floor <span class="text-red-500">*</span></label>
                        <input type="number" id="floor" name="floor" required min="1" value="<?= htmlspecialchars($room['floor'] ?? 1) ?>" placeholder="1" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label for="room_type" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Room Type <span class="text-red-500">*</span></label>
                        <select id="room_type" name="room_type" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="single" <?= ($room['room_type'] ?? '') === 'single' ? 'selected' : '' ?>>Single</option>
                            <option value="shared" <?= ($room['room_type'] ?? '') === 'shared' ? 'selected' : '' ?>>Shared</option>
                            <option value="dormitory" <?= ($room['room_type'] ?? '') === 'dormitory' ? 'selected' : '' ?>>Dormitory</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="capacity" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Capacity <span class="text-red-500">*</span></label>
                    <input type="number" id="capacity" name="capacity" required min="1" value="<?= htmlspecialchars($room['capacity'] ?? 1) ?>" placeholder="Number of beds" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total number of students that can be accommodated</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Pricing & Amenities</h3>

                <div>
                    <label for="fee_per_month" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Fee per Month <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400">KES</span>
                        <input type="number" id="fee_per_month" name="fee_per_month" required min="0" step="0.01" value="<?= htmlspecialchars($room['fee_per_month'] ?? 0) ?>" placeholder="0.00" class="w-full rounded-lg border border-gray-200 bg-white pl-12 pr-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>

                <div>
                    <label for="amenities" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Amenities</label>
                    <textarea id="amenities" name="amenities" rows="4" placeholder="e.g. Wi-Fi, AC, Hot Water, Study Desk, Wardrobe..." class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white resize-y"><?= htmlspecialchars($room['amenities'] ?? '') ?></textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Comma-separated list of amenities</p>
                </div>

                <div>
                    <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select id="status" name="status" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="available" <?= ($room['status'] ?? '') === 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="occupied" <?= ($room['status'] ?? '') === 'occupied' ? 'selected' : '' ?>>Occupied</option>
                        <option value="full" <?= ($room['status'] ?? '') === 'full' ? 'selected' : '' ?>>Full</option>
                        <option value="maintenance" <?= ($room['status'] ?? '') === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
            <a href="/hostel/rooms" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                Cancel
            </a>
            <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <?= $isEdit ? 'Update Room' : 'Save Room' ?>
                </span>
            </button>
        </div>
    </form>
</div>
