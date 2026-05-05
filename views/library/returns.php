<?php
$issuedBooks = $issuedBooks ?? [];
?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Book Returns</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Process book returns and manage overdue items</p>
        </div>
        <a href="/library/books" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Books
        </a>
    </div>
</div>

<!-- Return Form -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 p-6 mb-6">
    <div class="flex items-center gap-3 mb-5">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900">
            <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Process Return</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Enter the issue ID or select from the table below</p>
        </div>
    </div>

    <form method="POST" action="/library/returns" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label for="issue_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Issue ID <span class="text-red-500">*</span></label>
                <input type="text" id="issue_id" name="issue_id" required placeholder="Enter issue ID" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label for="return_date" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Return Date <span class="text-red-500">*</span></label>
                <input type="date" id="return_date" name="return_date" required value="<?= date('Y-m-d') ?>" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label for="fine_amount" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Fine Amount</label>
                <input type="number" id="fine_amount" name="fine_amount" min="0" step="0.01" value="0" placeholder="0.00" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>
        </div>
        <div>
            <label for="remarks" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Remarks</label>
            <input type="text" id="remarks" name="remarks" placeholder="Optional remarks about the book condition" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
        </div>
        <div class="flex justify-end pt-2">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Process Return
            </button>
        </div>
    </form>
</div>

<!-- Issued Books Table -->
<div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Outstanding Issues</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Books currently issued and awaiting return</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Issue ID</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Book</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Student</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">Issued On</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400 hidden sm:table-cell">Due Date</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">Actions</th>
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
                            ];
                            $sc = $statusColors[$issueStatus] ?? $statusColors['issued'];
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($issue['id'] ?? '') ?></td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($issue['book_title'] ?? '') ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($issue['book_author'] ?? '') ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars(($issue['student_first_name'] ?? '') . ' ' . ($issue['student_last_name'] ?? '')) ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($issue['admission_number'] ?? '') ?></div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 hidden sm:table-cell"><?= htmlspecialchars($issue['issue_date'] ?? '') ?></td>
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <span class="text-gray-700 dark:text-gray-300"><?= htmlspecialchars($issue['due_date'] ?? '') ?></span>
                                <?php if ($issueStatus === 'overdue'): ?>
                                    <span class="ml-2 text-xs text-red-600 dark:text-red-400 font-medium">Overdue</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium capitalize <?= $sc ?>"><?= htmlspecialchars($issueStatus) ?></span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button onclick="quickReturn('<?= htmlspecialchars($issue['id'] ?? '') ?>')" class="rounded-lg px-3 py-1.5 text-xs font-medium text-emerald-600 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-900/50 transition-colors">
                                    Return
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No outstanding issues</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">All books have been returned</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function quickReturn(issueId) {
    document.getElementById('issue_id').value = issueId;
    document.getElementById('return_date').value = '<?= date('Y-m-d') ?>';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
