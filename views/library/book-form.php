<?php
$book = $book ?? null;
$isEdit = !empty($book);
$formAction = $isEdit ? "/library/books/{$book['id']}" : "/library/books";
$formMethod = $isEdit ? 'POST' : 'POST';
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?= $isEdit ? 'Edit Book' : 'Add New Book' ?></h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= $isEdit ? 'Update book information' : 'Add a new book to the library' ?></p>
        </div>
        <a href="/library/books" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Books
        </a>
    </div>
</div>

<!-- Book Form -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <form method="POST" action="<?= htmlspecialchars($formAction) ?>" class="p-6 space-y-6">
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Left Column -->
            <div class="space-y-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Book Details</h3>

                <div>
                    <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" required value="<?= htmlspecialchars($book['title'] ?? '') ?>" placeholder="Enter book title" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>

                <div>
                    <label for="author" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Author <span class="text-red-500">*</span></label>
                    <input type="text" id="author" name="author" required value="<?= htmlspecialchars($book['author'] ?? '') ?>" placeholder="Enter author name" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>

                <div>
                    <label for="isbn" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">ISBN</label>
                    <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>" placeholder="e.g. 978-0134685991" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>

                <div>
                    <label for="publisher" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Publisher</label>
                    <input type="text" id="publisher" name="publisher" value="<?= htmlspecialchars($book['publisher'] ?? '') ?>" placeholder="Enter publisher name" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Classification & Stock</h3>

                <div>
                    <label for="category" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                    <select id="category" name="category" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="General" <?= ($book['category'] ?? '') === 'General' ? 'selected' : '' ?>>General</option>
                        <option value="Mathematics" <?= ($book['category'] ?? '') === 'Mathematics' ? 'selected' : '' ?>>Mathematics</option>
                        <option value="Sciences" <?= ($book['category'] ?? '') === 'Sciences' ? 'selected' : '' ?>>Sciences</option>
                        <option value="English" <?= ($book['category'] ?? '') === 'English' ? 'selected' : '' ?>>English</option>
                        <option value="Languages" <?= ($book['category'] ?? '') === 'Languages' ? 'selected' : '' ?>>Languages</option>
                        <option value="Humanities" <?= ($book['category'] ?? '') === 'Humanities' ? 'selected' : '' ?>>Humanities</option>
                        <option value="Fiction" <?= ($book['category'] ?? '') === 'Fiction' ? 'selected' : '' ?>>Fiction</option>
                        <option value="Reference" <?= ($book['category'] ?? '') === 'Reference' ? 'selected' : '' ?>>Reference</option>
                    </select>
                </div>

                <div>
                    <label for="total_copies" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Total Copies <span class="text-red-500">*</span></label>
                    <input type="number" id="total_copies" name="total_copies" required min="1" value="<?= htmlspecialchars($book['total_copies'] ?? 1) ?>" placeholder="1" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea id="description" name="description" rows="5" placeholder="Brief description of the book..." class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white resize-y"><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
            <a href="/library/books" class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                Cancel
            </a>
            <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <?= $isEdit ? 'Update Book' : 'Save Book' ?>
                </span>
            </button>
        </div>
    </form>
</div>
