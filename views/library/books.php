<?php
$books = $books ?? [];
$pagination = $pagination ?? ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Library Books</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Browse and manage library books</p>
        </div>
        <a href="/library/books/create" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Book
        </a>
    </div>
</div>

<!-- Books Table -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Title</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Author</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">ISBN</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden md:table-cell">Category</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400">Total</th>
                    <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400">Available</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as $book): ?>
                        <?php
                            $available = (int)($book['available_copies'] ?? 0);
                            $total = (int)($book['total_copies'] ?? 1);
                            $isAvailable = $available > 0;
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($book['title'] ?? '') ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($book['publisher'] ?? '') ?></div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($book['author'] ?? '') ?></td>
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <span class="font-mono text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($book['isbn'] ?? 'N/A') ?></span>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300"><?= htmlspecialchars($book['category'] ?? 'General') ?></span>
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white"><?= $total ?></td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-semibold <?= $isAvailable ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' ?>"><?= $available ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ($isAvailable): ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Available
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-900/30 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:text-red-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        Unavailable
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="/library/books/<?= htmlspecialchars($book['id'] ?? '') ?>/edit" class="rounded-lg p-1.5 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/50 dark:hover:text-emerald-400 transition-colors" title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button onclick="if(confirm('Are you sure you want to delete this book?')){document.getElementById('delete-form-<?= htmlspecialchars($book['id'] ?? '') ?>').submit();}" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/50 dark:hover:text-red-400 transition-colors" title="Delete">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    <form id="delete-form-<?= htmlspecialchars($book['id'] ?? '') ?>" method="POST" action="/library/books/<?= htmlspecialchars($book['id'] ?? '') ?>" class="hidden">
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No books found</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Add your first book to the library</p>
                            <a href="/library/books/create" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Add Book
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
    <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 dark:border-gray-700">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Showing <span class="font-medium"><?= $pagination['from'] ?></span> to <span class="font-medium"><?= $pagination['to'] ?></span> of <span class="font-medium"><?= $pagination['total'] ?></span> books
        </p>
        <div class="flex gap-1">
            <?php if ($pagination['page'] > 1): ?>
            <a href="?page=<?= $pagination['page'] - 1 ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <?php endif; ?>
            <?php
                $start = max(1, $pagination['page'] - 2);
                $end = min($pagination['totalPages'], $pagination['page'] + 2);
            ?>
            <?php for ($i = $start; $i <= $end; $i++): ?>
            <a href="?page=<?= $i ?>" class="px-3 py-1.5 text-sm rounded-lg font-medium transition-colors <?= $i == $pagination['page'] ? 'bg-emerald-600 text-white shadow-sm' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($pagination['page'] < $pagination['totalPages']): ?>
            <a href="?page=<?= $pagination['page'] + 1 ?>" class="px-3 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
