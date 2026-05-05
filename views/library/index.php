<?php
$books = isset($books) ? $books : [];
$borrowals = isset($borrowals) ? $borrowals : [];
$students = isset($students) ? $students : [];
$stats = isset($stats) ? $stats : [];

$totalBooks = $stats['total_books'] ?? 0;
$availableBooks = $stats['available_books'] ?? 0;
$borrowedBooks = $stats['borrowed_books'] ?? 0;
$overdueBooks = $stats['overdue_books'] ?? 0;
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Library</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage books, inventory, and student borrowals</p>
        </div>
        <button onclick="openBookModal()" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Book
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($totalBooks) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Books</p>
        </div>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900">
            <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($availableBooks) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Available</p>
        </div>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
            <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($borrowedBooks) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Borrowed</p>
        </div>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 transition-shadow hover:shadow-md">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900">
            <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= number_format($overdueBooks) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Overdue</p>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="mb-4 border-b border-gray-200 dark:border-gray-800">
    <nav class="-mb-px flex gap-6" role="tablist">
        <button onclick="switchTab('books')" id="tab-books" class="tab-btn border-b-2 border-emerald-500 px-1 pb-3 text-sm font-medium text-emerald-600 dark:text-emerald-400" role="tab" aria-selected="true">
            <span class="flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Books
            </span>
        </button>
        <button onclick="switchTab('borrowals')" id="tab-borrowals" class="tab-btn border-b-2 border-transparent px-1 pb-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" role="tab" aria-selected="false">
            <span class="flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Borrowals
            </span>
        </button>
    </nav>
</div>

<!-- Books Tab -->
<div id="panel-books" class="tab-panel">
    <!-- Search -->
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative flex-1 max-w-sm">
            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="bookSearch" placeholder="Search by title, author, or ISBN..." class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500">
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">ISBN</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Title</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Author</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Category</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400">Available/Total</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Location</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800" id="booksTableBody">
                    <?php if (!empty($books)): ?>
                        <?php foreach ($books as $book): ?>
                            <?php
                                $available = (int)($book['available_copies'] ?? 0);
                                $total = (int)($book['total_copies'] ?? 1);
                                $ratio = $total > 0 ? $available / $total : 0;
                                $statusColor = $available > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400';
                                $barColor = $ratio > 0.5 ? 'bg-emerald-500' : ($ratio > 0 ? 'bg-amber-500' : 'bg-red-500');
                            ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-4 py-3 font-mono text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($book['isbn'] ?? 'N/A') ?></td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($book['title'] ?? '') ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($book['publisher'] ?? '') ?></div>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($book['author'] ?? '') ?></td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300"><?= htmlspecialchars($book['category'] ?? 'General') ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-16 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                                <div class="h-full rounded-full <?= $barColor ?>" style="width: <?= $ratio * 100 ?>%"></div>
                                            </div>
                                            <span class="<?= $statusColor ?> text-xs font-semibold"><?= $available ?>/<?= $total ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs font-mono"><?= htmlspecialchars($book['shelf_location'] ?? '—') ?></td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button onclick="editBook(<?= htmlspecialchars(json_encode($book)) ?>)" class="rounded-lg p-1.5 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/50 dark:hover:text-emerald-400 transition-colors" title="Edit">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button onclick="deleteBook('<?= htmlspecialchars($book['id'] ?? '') ?>')" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors" title="Delete">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No books found</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Add your first book to the library</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Borrowals Tab -->
<div id="panel-borrowals" class="tab-panel hidden">
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative flex-1 max-w-sm">
            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="borrowalSearch" placeholder="Search borrowals..." class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500">
        </div>
        <button onclick="openBorrowModal()" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            Issue Book
        </button>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Book</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Student</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Borrowed</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Due Date</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Fine</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800" id="borrowalsTableBody">
                    <?php if (!empty($borrowals)): ?>
                        <?php foreach ($borrowals as $b): ?>
                            <?php
                                $borrowStatus = strtolower($b['status'] ?? 'borrowed');
                                $statusColors = [
                                    'borrowed' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
                                    'returned' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                                    'overdue'  => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                ];
                                $sc = $statusColors[$borrowStatus] ?? $statusColors['borrowed'];
                                $student = $b['student'] ?? [];
                                $book = $b['book'] ?? [];
                            ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($book['title'] ?? '') ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($book['author'] ?? '') ?></div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($student['admission_number'] ?? '') ?></div>
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= formatDate($b['borrowed_date'] ?? '') ?></td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= formatDate($b['due_date'] ?? '') ?></td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize <?= $sc ?>"><?= htmlspecialchars($borrowStatus) ?></span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <?php if ((float)($b['fine'] ?? 0) > 0): ?>
                                        <span class="font-semibold text-red-600 dark:text-red-400">KES <?= number_format((float)$b['fine']) ?></span>
                                    <?php else: ?>
                                        <span class="text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <?php if ($borrowStatus !== 'returned'): ?>
                                        <button onclick="returnBook('<?= htmlspecialchars($b['id'] ?? '') ?>')" class="rounded-lg px-3 py-1.5 text-xs font-medium text-emerald-600 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-900/50 transition-colors">
                                            Return
                                        </button>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No borrowals found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Book Modal -->
<div id="bookModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeBookModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl dark:bg-gray-900 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                <h3 id="bookModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Book</h3>
                <button onclick="closeBookModal()" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="bookForm" class="space-y-4 px-6 py-4" onsubmit="saveBook(event)">
                <input type="hidden" id="bookId" value="">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Title *</label>
                        <input type="text" id="bookTitle" required placeholder="Book title" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Author *</label>
                        <input type="text" id="bookAuthor" required placeholder="Author name" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">ISBN</label>
                        <input type="text" id="bookIsbn" placeholder="e.g. 978-0134685991" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Publisher</label>
                        <input type="text" id="bookPublisher" placeholder="Publisher name" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                        <select id="bookCategory" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="General">General</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Sciences">Sciences</option>
                            <option value="English">English</option>
                            <option value="Languages">Languages</option>
                            <option value="Humanities">Humanities</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Reference">Reference</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Total Copies *</label>
                        <input type="number" id="bookCopies" required min="1" value="1" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Shelf Location</label>
                        <input type="text" id="bookLocation" placeholder="e.g. A1-03" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeBookModal()" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">Save Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Borrow Book Modal -->
<div id="borrowModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeBorrowModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-xl dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Issue Book</h3>
                <button onclick="closeBorrowModal()" class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="borrowForm" class="space-y-4 px-6 py-4" onsubmit="issueBook(event)">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Book *</label>
                    <select id="borrowBook" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">Select Book</option>
                        <?php foreach ($books as $bk): ?>
                            <?php if ((int)($bk['available_copies'] ?? 0) > 0): ?>
                                <option value="<?= htmlspecialchars($bk['id']) ?>"><?= htmlspecialchars($bk['title'] . ' by ' . $bk['author']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Student *</label>
                    <select id="borrowStudent" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">Select Student</option>
                        <?php foreach ($students as $st): ?>
                            <option value="<?= htmlspecialchars($st['id']) ?>"><?= htmlspecialchars(($st['first_name'] ?? '') . ' ' . ($st['last_name'] ?? '') . ' (' . ($st['admission_number'] ?? '') . ')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date *</label>
                    <input type="date" id="borrowDueDate" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeBorrowModal()" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">Cancel</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">Issue Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tab switching
    function switchTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('border-emerald-500', 'text-emerald-600', 'dark:text-emerald-400');
            b.classList.add('border-transparent', 'text-gray-500');
        });
        document.getElementById('panel-' + tab).classList.remove('hidden');
        const activeBtn = document.getElementById('tab-' + tab);
        activeBtn.classList.add('border-emerald-500', 'text-emerald-600', 'dark:text-emerald-400');
        activeBtn.classList.remove('border-transparent', 'text-gray-500');
    }

    // Book modal
    function openBookModal() {
        document.getElementById('bookModal').classList.remove('hidden');
        document.getElementById('bookForm').reset();
        document.getElementById('bookId').value = '';
        document.getElementById('bookModalTitle').textContent = 'Add Book';
    }
    function closeBookModal() { document.getElementById('bookModal').classList.add('hidden'); }

    function editBook(book) {
        document.getElementById('bookModal').classList.remove('hidden');
        document.getElementById('bookModalTitle').textContent = 'Edit Book';
        document.getElementById('bookId').value = book.id || '';
        document.getElementById('bookTitle').value = book.title || '';
        document.getElementById('bookAuthor').value = book.author || '';
        document.getElementById('bookIsbn').value = book.isbn || '';
        document.getElementById('bookPublisher').value = book.publisher || '';
        document.getElementById('bookCategory').value = book.category || 'General';
        document.getElementById('bookCopies').value = book.total_copies || 1;
        document.getElementById('bookLocation').value = book.shelf_location || '';
    }

    function saveBook(e) {
        e.preventDefault();
        const data = {
            id: document.getElementById('bookId').value,
            title: document.getElementById('bookTitle').value,
            author: document.getElementById('bookAuthor').value,
            isbn: document.getElementById('bookIsbn').value,
            publisher: document.getElementById('bookPublisher').value,
            category: document.getElementById('bookCategory').value,
            total_copies: parseInt(document.getElementById('bookCopies').value),
            shelf_location: document.getElementById('bookLocation').value,
        };
        console.log('Saving book:', data);
        closeBookModal();
    }

    function deleteBook(id) {
        if (confirm('Are you sure you want to delete this book?')) { console.log('Deleting book:', id); }
    }

    // Borrow modal
    function openBorrowModal() {
        document.getElementById('borrowModal').classList.remove('hidden');
        document.getElementById('borrowForm').reset();
        // Set default due date to 14 days from now
        const due = new Date(); due.setDate(due.getDate() + 14);
        document.getElementById('borrowDueDate').value = due.toISOString().split('T')[0];
    }
    function closeBorrowModal() { document.getElementById('borrowModal').classList.add('hidden'); }

    function issueBook(e) {
        e.preventDefault();
        const data = {
            book_id: document.getElementById('borrowBook').value,
            student_id: document.getElementById('borrowStudent').value,
            due_date: document.getElementById('borrowDueDate').value,
        };
        console.log('Issuing book:', data);
        closeBorrowModal();
    }

    function returnBook(id) {
        if (confirm('Confirm book return?')) { console.log('Returning book:', id); }
    }

    // Search
    document.getElementById('bookSearch')?.addEventListener('input', function(e) {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('#booksTableBody tr').forEach(r => { r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none'; });
    });
</script>
