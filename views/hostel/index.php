<?php $pageTitle = $pageTitle ?? 'Hostel Management'; ?>
<?php
    $rooms = $rooms ?? [];
    $room = $room ?? null;
    $search = $search ?? '';
    $block = $block ?? '';
    $status = $status ?? '';
    $pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hostel Management</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage hostel rooms, occupancy, and allocations</p>
    </div>
    <button onclick="openModal('create')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium shadow-sm">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      Add Room
    </button>
  </div>

  <!-- Tabs -->
  <div class="border-b border-gray-200 dark:border-gray-700">
    <nav class="-mb-px flex gap-6" aria-label="Tabs">
      <button onclick="switchTab('rooms')" id="tab-rooms" class="tab-btn border-b-2 border-emerald-500 py-3 text-sm font-medium text-emerald-600 whitespace-nowrap">Rooms</button>
      <button onclick="switchTab('allocations')" id="tab-allocations" class="tab-btn border-b-2 border-transparent py-3 text-sm font-medium text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 whitespace-nowrap">Allocations</button>
    </nav>
  </div>

  <!-- Rooms Tab -->
  <div id="panel-rooms" class="tab-panel">
    <!-- Search & Filters -->
    <div class="flex flex-col sm:flex-row gap-3">
      <form method="GET" action="<?= url('/hostel') ?>" class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search rooms by name..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400">
      </form>
      <select name="block" onchange="this.form.submit()" class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        <option value="">All Blocks</option>
        <option value="A" <?= $block === 'A' ? 'selected' : '' ?>>Block A</option>
        <option value="B" <?= $block === 'B' ? 'selected' : '' ?>>Block B</option>
        <option value="C" <?= $block === 'C' ? 'selected' : '' ?>>Block C</option>
        <option value="D" <?= $block === 'D' ? 'selected' : '' ?>>Block D</option>
      </select>
      <?php if (!empty($search) || !empty($block)): ?>
      <a href="<?= url('/hostel') ?>" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        Clear
      </a>
      <?php endif; ?>
    </div>

    <!-- Rooms Table -->
    <div class="mt-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 dark:bg-gray-900/50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Room #</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Block</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Floor</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Capacity</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Occupancy</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <?php foreach($rooms as $item): ?>
            <?php
              $capacity = (int) ($item['capacity'] ?? 1);
              $occupancy = (int) ($item['current_occupancy'] ?? 0);
              $percent = $capacity > 0 ? round(($occupancy / $capacity) * 100) : 0;
              if ($percent < 80) {
                $barColor = 'bg-emerald-500';
                $barBg = 'bg-emerald-100 dark:bg-emerald-900/30';
              } elseif ($percent <= 95) {
                $barColor = 'bg-yellow-500';
                $barBg = 'bg-yellow-100 dark:bg-yellow-900/30';
              } else {
                $barColor = 'bg-red-500';
                $barBg = 'bg-red-100 dark:bg-red-900/30';
              }
            ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
              <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                  <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
                    <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                  </div>
                  <span class="text-sm font-medium text-gray-900 dark:text-white"><?= e($item['room_number'] ?? '') ?></span>
                </div>
              </td>
              <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= e($item['name'] ?? '—') ?></td>
              <td class="px-4 py-3 hidden md:table-cell">
                <span class="inline-flex items-center rounded bg-gray-100 dark:bg-gray-700 px-2 py-0.5 text-xs font-medium text-gray-700 dark:text-gray-300">Block <?= e($item['block'] ?? '—') ?></span>
              </td>
              <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 hidden lg:table-cell"><?= e($item['floor'] ?? '—') ?></td>
              <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300"><?= $capacity ?></td>
              <td class="px-4 py-3 hidden sm:table-cell">
                <div class="flex items-center gap-3">
                  <div class="w-24 rounded-full h-2 <?= $barBg ?>">
                    <div class="rounded-full h-2 <?= $barColor ?>" style="width: <?= $percent ?>%"></div>
                  </div>
                  <span class="text-xs font-medium text-gray-600 dark:text-gray-400"><?= $occupancy ?>/<?= $capacity ?></span>
                </div>
              </td>
              <td class="px-4 py-3">
                <?php $roomStatus = $item['status'] ?? 'available'; ?>
                <?php if ($roomStatus === 'available'): ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400">
                  <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                  Available
                </span>
                <?php elseif ($roomStatus === 'occupied'): ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 px-2.5 py-0.5 text-xs font-medium text-yellow-700 dark:text-yellow-400">
                  <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                  Occupied
                </span>
                <?php elseif ($roomStatus === 'full'): ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:text-red-400">
                  <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                  Full
                </span>
                <?php else: ?>
                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 dark:bg-gray-700 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:text-gray-400">
                  <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                  <?= ucfirst(e($roomStatus)) ?>
                </span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-1">
                  <button onclick="openModal('edit', <?= htmlspecialchars(json_encode($item), ENT_QUOTES) ?>)" class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  <button onclick="confirmDelete('<?= e($item['id']) ?>')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <?php if(empty($rooms)): ?>
      <div class="flex flex-col items-center justify-center py-16 text-gray-400">
        <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No rooms found</p>
        <p class="text-xs text-gray-400 dark:text-gray-500">Get started by adding your first room</p>
      </div>
      <?php endif; ?>

      <!-- Pagination -->
      <?php if(isset($pagination) && $pagination['totalPages'] > 1): ?>
      <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-700">
        <p class="text-sm text-gray-500 dark:text-gray-400">Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> rooms</p>
        <div class="flex gap-1">
          <?php if($pagination['page'] > 1): ?>
          <a href="?page=<?= $pagination['page'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
          </a>
          <?php endif; ?>
          <?php
            $start = max(1, $pagination['page'] - 2);
            $end = min($pagination['totalPages'], $pagination['page'] + 2);
          ?>
          <?php for($i = $start; $i <= $end; $i++): ?>
          <a href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors <?= $i == $pagination['page'] ? 'bg-emerald-600 text-white shadow-sm' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300' ?>"><?= $i ?></a>
          <?php endfor; ?>
          <?php if($pagination['page'] < $pagination['totalPages']): ?>
          <a href="?page=<?= $pagination['page'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Allocations Tab -->
  <div id="panel-allocations" class="tab-panel hidden">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
      <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Room Allocations</p>
      <p class="text-xs text-gray-400 dark:text-gray-500">Manage student room assignments and bed allocations</p>
    </div>
  </div>

  <!-- Create/Edit Modal -->
  <div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)closeModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Add Room</h3>
        <button onclick="closeModal()" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form id="modal-form" method="POST" action="<?= url('/hostel/rooms') ?>" class="p-6 space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="form-id">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="form-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Room Name <span class="text-red-500">*</span></label>
            <input type="text" id="form-name" name="name" required placeholder="e.g. Room 101" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-room_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Room Number <span class="text-red-500">*</span></label>
            <input type="text" id="form-room_number" name="room_number" required placeholder="e.g. 101" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label for="form-block" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Block <span class="text-red-500">*</span></label>
            <select id="form-block" name="block" required class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
              <option value="">Select Block</option>
              <option value="A">Block A</option>
              <option value="B">Block B</option>
              <option value="C">Block C</option>
              <option value="D">Block D</option>
            </select>
          </div>
          <div>
            <label for="form-floor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Floor</label>
            <input type="text" id="form-floor" name="floor" value="1" placeholder="e.g. 1" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
          <div>
            <label for="form-capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Capacity <span class="text-red-500">*</span></label>
            <input type="number" id="form-capacity" name="capacity" required min="1" placeholder="e.g. 4" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
          </div>
        </div>

        <div>
          <label for="form-amenities" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amenities</label>
          <input type="text" id="form-amenities" name="amenities" placeholder="e.g. Wi-Fi, AC, Hot Water" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>

        <div>
          <label for="form-status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
          <select id="form-status" name="status" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="available">Available</option>
            <option value="occupied">Occupied</option>
            <option value="full">Full</option>
            <option value="maintenance">Maintenance</option>
          </select>
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
          <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">Save Room</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)closeDeleteModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-sm mx-4">
      <div class="p-6 text-center">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
          <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Room</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete this room? This action cannot be undone.</p>
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
    function switchTab(tab) {
      document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
      document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('border-emerald-500', 'text-emerald-600');
        b.classList.add('border-transparent', 'text-gray-500');
      });
      document.getElementById('panel-' + tab).classList.remove('hidden');
      const btn = document.getElementById('tab-' + tab);
      btn.classList.add('border-emerald-500', 'text-emerald-600');
      btn.classList.remove('border-transparent', 'text-gray-500');
    }

    function openModal(mode, data = null) {
      const modal = document.getElementById('modal');
      const title = document.getElementById('modal-title');
      const form = document.getElementById('modal-form');

      if (mode === 'edit' && data) {
        title.textContent = 'Edit Room';
        document.getElementById('form-id').value = data.id || '';
        document.getElementById('form-name').value = data.name || '';
        document.getElementById('form-room_number').value = data.room_number || '';
        document.getElementById('form-block').value = data.block || '';
        document.getElementById('form-floor').value = data.floor || '1';
        document.getElementById('form-capacity').value = data.capacity || '';
        document.getElementById('form-amenities').value = data.amenities || '';
        document.getElementById('form-status').value = data.status || 'available';
        form.action = '<?= url("/hostel/rooms") ?>/' + data.id;
        form.method = 'POST';
      } else {
        title.textContent = 'Add Room';
        form.reset();
        document.getElementById('form-id').value = '';
        document.getElementById('form-floor').value = '1';
        form.action = '<?= url("/hostel/rooms") ?>';
        form.method = 'POST';
      }

      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      document.getElementById('modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    function confirmDelete(id) {
      const deleteModal = document.getElementById('delete-modal');
      document.getElementById('delete-form').action = '<?= url("/hostel/rooms") ?>/' + id + '/delete';
      deleteModal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeModal();
        closeDeleteModal();
      }
    });
  </script>
</div>
