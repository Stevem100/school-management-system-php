<?php
$books = $books ?? [];
$students = $students ?? [];
$issuedBooks = $issuedBooks ?? [];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Issue Book</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Issue a book to a student from the library</p>
        </div>
        <a href="/library/books" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Books
        </a>
    </div>
</div>

<!-- Issue Form -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 p-6 mb-6">
    <div class="flex items-center gap-3 mb-5">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900">
            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">New Book Issue</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Select a book and student, then set the dates</p>
        </div>
    </div>

    <form method="POST" action="/library/issue" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="book_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Book <span class="text-red-500">*</span></label>
                <select id="book_id" name="book_id" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">Select a Book</option>
                    <?php foreach ($books as $bk): ?>
                        <?php if ((int)($bk['available_copies'] ?? 0) > 0): ?>
                            <option value="<?= htmlspecialchars($bk['id']) ?>">
                                <?= htmlspecialchars($bk['title'] . ' by ' . $bk['author']) ?> (<?= (int)($bk['available_copies'] ?? 0) ?> available)
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
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
                <label for="issue_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Issue Date <span class="text-red-500">*</span></label>
                <input type="date" id="issue_date" name="issue_date" required value="<?= date('Y-m-d') ?>" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label for="due_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date <span class="text-red-500">*</span></label>
                <input type="date" id="due_date" name="due_date" required value="<?= date('Y-m-d', strtotime('+14 days')) ?>" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>
        </div>
        <div class="flex justify-end pt-2">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Issue Book
            </button>
        </div>
    </form>
</div>

<!-- Currently Issued Books -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Currently Issued Books</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Book</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Student</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">Issue Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">Due Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <?php if (!empty($issuedBooks)): ?>
                    <?php foreach ($issuedBooks as $issue): ?>
                        <?php
                            $issueStatus = strtolower($issue['status'] ?? 'issued');
                            $statusColors = [
                                'issued' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                                'overdue' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                'returned' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                            ];
                            $sc = $statusColors[$issueStatus] ?? $statusColors['issued'];
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($issue['book_title'] ?? '') ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($issue['book_author'] ?? '') ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars(($issue['student_first_name'] ?? '') . ' ' . ($issue['student_last_name'] ?? '')) ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($issue['admission_number'] ?? '') ?></div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden sm:table-cell"><?= htmlspecialchars($issue['issue_date'] ?? '') ?></td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden sm:table-cell"><?= htmlspecialchars($issue['due_date'] ?? '') ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize <?= $sc ?>"><?= htmlspecialchars($issueStatus) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No books currently issued</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Use the form above to issue a book</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
