<?php $pageTitle = $pageTitle ?? 'Media Library'; ?>
<?php $currentPage = $currentPage ?? 'website'; ?>
<?php
    $mediaItems = $mediaItems ?? [];
    $categories = $categories ?? [];
    $categoryFilter = $categoryFilter ?? 'all';
    $typeFilter = $typeFilter ?? 'all';
    $search = $search ?? '';
    $pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Media Library</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload and manage images, documents, and media files</p>
    </div>
    <div class="flex items-center gap-3">
      <a href="<?= url('/website') ?>" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back
      </a>
    </div>
  </div>

  <!-- Upload Form -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
          <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
        </div>
        <div>
          <h3 class="text-base font-semibold text-gray-900 dark:text-white">Upload File</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">Add new media to your library</p>
        </div>
      </div>
    </div>
    <form method="POST" action="<?= url('/website/media') ?>" enctype="multipart/form-data" class="p-6">
      <?= csrf_field() ?>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="sm:col-span-1">
          <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File <span class="text-red-500">*</span></label>
          <input type="file" id="file" name="file" required accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.mp4,.mp3,.wav" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-400 dark:hover:file:bg-emerald-900/50 file:transition-colors cursor-pointer">
        </div>
        <div>
          <label for="alt-text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alt Text</label>
          <input type="text" id="alt-text" name="alt_text" placeholder="Description for accessibility" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent placeholder-gray-400">
        </div>
        <div>
          <label for="media-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
          <select id="media-category" name="category" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <option value="general">General</option>
            <option value="gallery">Gallery</option>
            <option value="news">News & Events</option>
            <option value="testimonial">Testimonials</option>
            <option value="document">Documents</option>
            <option value="banner">Banners</option>
            <option value="staff">Staff Photos</option>
          </select>
        </div>
      </div>
      <div class="flex justify-end mt-4">
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
          Upload
        </button>
      </div>
    </form>
  </div>

  <!-- Filters -->
  <div class="flex flex-col sm:flex-row gap-3">
    <form method="GET" action="<?= url('/website/media') ?>" class="relative flex-1">
      <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search media files..." class="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-900 dark:text-gray-100 placeholder-gray-400">
    </form>
    <div class="flex gap-2 flex-wrap">
      <select onchange="window.location.href='<?= url('/website/media') ?>?category='+this.value+'&type=<?= e($typeFilter) ?>'" class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        <option value="all" <?= $categoryFilter === 'all' ? 'selected' : '' ?>>All Categories</option>
        <?php foreach (['general', 'gallery', 'news', 'testimonial', 'document', 'banner', 'staff'] as $cat): ?>
        <option value="<?= $cat ?>" <?= $categoryFilter === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
        <?php endforeach; ?>
      </select>
      <select onchange="window.location.href='<?= url('/website/media') ?>?type='+this.value+'&category=<?= e($categoryFilter) ?>'" class="px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
        <option value="all" <?= $typeFilter === 'all' ? 'selected' : '' ?>>All Types</option>
        <option value="image" <?= $typeFilter === 'image' ? 'selected' : '' ?>>Images</option>
        <option value="document" <?= $typeFilter === 'document' ? 'selected' : '' ?>>Documents</option>
        <option value="video" <?= $typeFilter === 'video' ? 'selected' : '' ?>>Videos</option>
        <option value="audio" <?= $typeFilter === 'audio' ? 'selected' : '' ?>>Audio</option>
      </select>
    </div>
  </div>

  <!-- Media Grid -->
  <?php if (!empty($mediaItems)): ?>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach($mediaItems as $item): ?>
    <?php
      $fileType = $item['file_type'] ?? 'unknown';
      $mimeType = $item['mime_type'] ?? '';
      $fileName = $item['file_name'] ?? 'file';
      $fileSize = $item['file_size'] ?? 0;
      $category = $item['category'] ?? 'general';
      $uploadedAt = $item['created_at'] ?? '';
      $thumbnailUrl = $item['thumbnail_url'] ?? $item['file_url'] ?? '';
      $fileUrl = $item['file_url'] ?? '#';
      $altText = $item['alt_text'] ?? '';

      $isImage = strpos($mimeType, 'image/') === 0;
      $isVideo = strpos($mimeType, 'video/') === 0;
      $isAudio = strpos($mimeType, 'audio/') === 0;
      $isDoc = !$isImage && !$isVideo && !$isAudio;

      // File size formatting
      if ($fileSize >= 1048576) {
          $formattedSize = round($fileSize / 1048576, 1) . ' MB';
      } elseif ($fileSize >= 1024) {
          $formattedSize = round($fileSize / 1024, 1) . ' KB';
      } else {
          $formattedSize = $fileSize . ' B';
      }

      // Type badge colors
      $typeBadge = [
          'image' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
          'video' => 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400',
          'audio' => 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400',
          'document' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
          'unknown' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400',
      ];
      $typeBadgeClass = $isImage ? $typeBadge['image'] : ($isVideo ? $typeBadge['video'] : ($isAudio ? $typeBadge['audio'] : $typeBadge['document']));
      $typeLabel = $isImage ? 'Image' : ($isVideo ? 'Video' : ($isAudio ? 'Audio' : 'Document'));
    ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all group">
      <!-- Thumbnail Area -->
      <div class="relative h-40 bg-gray-100 dark:bg-gray-900 flex items-center justify-center overflow-hidden">
        <?php if ($isImage): ?>
        <img src="<?= e($thumbnailUrl) ?>" alt="<?= e($altText) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        <?php elseif ($isVideo): ?>
        <div class="flex flex-col items-center gap-2 text-gray-400">
          <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
          <span class="text-[10px] uppercase tracking-wider font-medium">Video</span>
        </div>
        <?php elseif ($isAudio): ?>
        <div class="flex flex-col items-center gap-2 text-gray-400">
          <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
          <span class="text-[10px] uppercase tracking-wider font-medium">Audio</span>
        </div>
        <?php else: ?>
        <div class="flex flex-col items-center gap-2 text-gray-400">
          <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span class="text-[10px] uppercase tracking-wider font-medium">Document</span>
        </div>
        <?php endif; ?>

        <!-- Delete Button -->
        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
          <button onclick="confirmDelete('<?= e($item['id'] ?? '') ?>', '<?= e($fileName) ?>')" class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-md text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Delete">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          </button>
        </div>

        <!-- Copy URL Button -->
        <div class="absolute top-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity">
          <button onclick="copyUrl('<?= e($fileUrl) ?>')" class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-md text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors" title="Copy URL">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
          </button>
        </div>
      </div>

      <!-- Info Area -->
      <div class="p-3 space-y-2">
        <p class="text-sm font-medium text-gray-900 dark:text-white truncate" title="<?= e($fileName) ?>"><?= e($fileName) ?></p>
        <div class="flex items-center gap-2 flex-wrap">
          <span class="inline-flex items-center rounded-full <?= $typeBadgeClass ?> px-2 py-0.5 text-[10px] font-medium"><?= $typeLabel ?></span>
          <span class="text-[10px] text-gray-400 dark:text-gray-500"><?= $formattedSize ?></span>
        </div>
        <div class="flex items-center justify-between pt-1 border-t border-gray-100 dark:border-gray-700">
          <span class="text-[10px] text-gray-400 dark:text-gray-500"><?= ucfirst($category) ?></span>
          <span class="text-[10px] text-gray-400 dark:text-gray-500"><?= $uploadedAt ? date('M j, Y', strtotime($uploadedAt)) : '' ?></span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <!-- Empty State -->
  <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center py-16">
    <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No media files found</p>
    <p class="text-xs text-gray-400 dark:text-gray-500">Upload your first file to get started</p>
  </div>
  <?php endif; ?>

  <!-- Pagination -->
  <?php if(isset($pagination) && $pagination['totalPages'] > 1): ?>
  <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <p class="text-sm text-gray-500 dark:text-gray-400">Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> files</p>
    <div class="flex gap-1">
      <?php if($pagination['page'] > 1): ?>
      <a href="?page=<?= $pagination['page'] - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= $categoryFilter !== 'all' ? '&category=' . $categoryFilter : '' ?><?= $typeFilter !== 'all' ? '&type=' . $typeFilter : '' ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
      <?php endif; ?>
      <?php
        $start = max(1, $pagination['page'] - 2);
        $end = min($pagination['totalPages'], $pagination['page'] + 2);
      ?>
      <?php for($i = $start; $i <= $end; $i++): ?>
      <a href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= $categoryFilter !== 'all' ? '&category=' . $categoryFilter : '' ?><?= $typeFilter !== 'all' ? '&type=' . $typeFilter : '' ?>" class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors <?= $i == $pagination['page'] ? 'bg-emerald-600 text-white shadow-sm' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300' ?>"><?= $i ?></a>
      <?php endfor; ?>
      <?php if($pagination['page'] < $pagination['totalPages']): ?>
      <a href="?page=<?= $pagination['page'] + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= $categoryFilter !== 'all' ? '&category=' . $categoryFilter : '' ?><?= $typeFilter !== 'all' ? '&type=' . $typeFilter : '' ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
      <?php endif; ?>
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
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete File</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete "<span id="delete-file-name" class="font-medium"></span>"? This action cannot be undone.</p>
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
    function confirmDelete(id, name) {
      document.getElementById('delete-file-name').textContent = name || 'this file';
      document.getElementById('delete-form').action = '<?= url("/website/media") ?>/' + id + '/delete';
      document.getElementById('delete-modal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    function copyUrl(url) {
      navigator.clipboard.writeText(url).then(() => {
        // Brief visual feedback
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-4 py-2 rounded-lg text-sm shadow-lg z-50 transition-opacity';
        toast.textContent = 'URL copied!';
        document.body.appendChild(toast);
        setTimeout(() => {
          toast.style.opacity = '0';
          setTimeout(() => toast.remove(), 300);
        }, 1500);
      });
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeDeleteModal();
      }
    });
  </script>
</div>
