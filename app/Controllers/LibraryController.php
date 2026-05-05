<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * LibraryController
 *
 * Manages library books, inventory, and student borrowals.
 * Uses MySQL database for all CRUD operations.
 */
class LibraryController extends Controller
{
    /**
     * Library index page.
     */
    public function index(): void
    {
        $this->requireAuth();

        $books     = $this->fetchBooks();
        $borrowals = $this->fetchBorrowals();
        $students  = $this->fetchStudents();
        $stats     = $this->fetchLibraryStats();

        $this->renderWithLayout('library/index', [
            'pageTitle'    => 'Library',
            'currentPage'  => 'library',
            'books'        => $books,
            'borrowals'    => $borrowals,
            'students'     => $students,
            'stats'        => $stats,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes
    // ─────────────────────────────────────────────────────────

    public function apiIndex(): void
    {
        $this->requireAuth();
        $this->success($this->fetchBooks());
    }

    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin', 'Librarian']);

        $input = $this->requestJson();
        $errors = $this->validateBook($input);
        if (!empty($errors)) {
            $this->error('Validation failed.', 422, $errors);
            return;
        }

        try {
            $data = [
                'isbn'             => $input['isbn'] ?? '',
                'title'            => $input['title'],
                'author'           => $input['author'],
                'publisher'        => $input['publisher'] ?? '',
                'category'         => $input['category'] ?? 'General',
                'total_copies'     => (int) ($input['total_copies'] ?? 1),
                'available_copies' => (int) ($input['total_copies'] ?? 1),
                'shelf_location'   => $input['shelf_location'] ?? '',
                'status'           => $input['status'] ?? 'available',
            ];

            $result = $this->db->insert('library_books', $data);
            $this->success($result, 'Book added successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to add book: ' . $e->getMessage(), 500);
        }
    }

    public function apiUpdate(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin', 'Librarian']);

        $input = $this->requestJson();
        $id = $input['id'] ?? '';

        if (empty($id)) {
            $this->error('Book ID is required.', 422);
            return;
        }

        try {
            $data = array_filter([
                'isbn'           => $input['isbn'] ?? null,
                'title'          => $input['title'] ?? null,
                'author'         => $input['author'] ?? null,
                'publisher'      => $input['publisher'] ?? null,
                'category'       => $input['category'] ?? null,
                'total_copies'   => isset($input['total_copies']) ? (int) $input['total_copies'] : null,
                'shelf_location' => $input['shelf_location'] ?? null,
                'status'         => $input['status'] ?? null,
            ], fn($v) => $v !== null);

            $result = $this->db->updateById('library_books', $id, $data);
            $this->success($result, 'Book updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update book: ' . $e->getMessage(), 500);
        }
    }

    public function apiDestroy(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin']);

        $id = $this->input('id', '');
        if (empty($id)) {
            $this->error('Book ID is required.', 422);
            return;
        }

        try {
            $this->db->deleteById('library_books', $id);
            $this->success(null, 'Book deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete book: ' . $e->getMessage(), 500);
        }
    }

    public function apiBorrow(): void
    {
        $this->requireAuth();

        $input = $this->requestJson();
        if (empty($input['book_id']) || empty($input['student_id'])) {
            $this->error('Book and student are required.', 422);
            return;
        }

        try {
            $data = [
                'book_id'       => $input['book_id'],
                'student_id'    => $input['student_id'],
                'borrowed_date' => date('Y-m-d'),
                'due_date'      => $input['due_date'] ?? date('Y-m-d', strtotime('+14 days')),
                'status'        => 'borrowed',
                'fine'          => 0,
            ];

            $result = $this->db->insert('borrowals', $data);

            // Decrement available copies
            $book = $this->db->find('library_books', $input['book_id']);
            if ($book) {
                $newAvailable = max(0, (int) ($book['available_copies'] ?? 0) - 1);
                $this->db->updateById('library_books', $input['book_id'], ['available_copies' => $newAvailable]);
            }

            $this->success($result, 'Book borrowed successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to record borrowal: ' . $e->getMessage(), 500);
        }
    }

    public function apiReturn(): void
    {
        $this->requireAuth();

        $input = $this->requestJson();
        $id = $input['borrowal_id'] ?? '';

        if (empty($id)) {
            $this->error('Borrowal ID is required.', 422);
            return;
        }

        try {
            $borrowal = $this->db->find('borrowals', $id);
            if (!$borrowal) {
                $this->error('Borrowal record not found.', 404);
                return;
            }

            $this->db->updateById('borrowals', $id, [
                'returned_date' => date('Y-m-d'),
                'status'        => 'returned',
            ]);

            // Increment available copies
            if (!empty($borrowal['book_id'])) {
                $book = $this->db->find('library_books', $borrowal['book_id']);
                if ($book) {
                    $newAvailable = min((int) ($book['total_copies'] ?? 0), (int) ($book['available_copies'] ?? 0) + 1);
                    $this->db->updateById('library_books', $borrowal['book_id'], ['available_copies' => $newAvailable]);
                }
            }

            $this->success(null, 'Book returned successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to return book: ' . $e->getMessage(), 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Data Fetching
    // ─────────────────────────────────────────────────────────

    private function fetchBooks(): array
    {
        try {
            return $this->db->select('library_books', [], 'title.asc');
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    private function fetchBorrowals(): array
    {
        try {
            return $this->db->raw(
                "SELECT b.*,
                        lb.title as book_title, lb.author as book_author,
                        u.first_name as student_first_name, u.last_name as student_last_name,
                        sp.admission_no as student_admission_no
                 FROM borrowals b
                 LEFT JOIN library_books lb ON b.book_id = lb.id
                 LEFT JOIN users u ON b.student_id = u.id
                 LEFT JOIN student_profiles sp ON u.id = sp.user_id
                 ORDER BY b.borrowed_date DESC"
            );
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    private function fetchStudents(): array
    {
        try {
            return $this->db->raw(
                "SELECT u.id, u.first_name, u.last_name, sp.admission_no
                 FROM users u
                 LEFT JOIN student_profiles sp ON u.id = sp.user_id
                 WHERE u.user_type = 'student'
                 ORDER BY u.first_name"
            );
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    private function fetchLibraryStats(): array
    {
        try {
            $total = $this->db->raw("SELECT COALESCE(SUM(total_copies), 0) as cnt FROM library_books");
            $available = $this->db->raw("SELECT COALESCE(SUM(available_copies), 0) as cnt FROM library_books");
            $borrowed = $this->db->raw("SELECT COUNT(*) as cnt FROM borrowals WHERE status = 'borrowed'");
            $overdue = $this->db->raw("SELECT COUNT(*) as cnt FROM borrowals WHERE status = 'overdue' OR due_date < CURDATE()");

            return [
                'total_books'     => (int) ($total[0]['cnt'] ?? 0),
                'available_books' => (int) ($available[0]['cnt'] ?? 0),
                'borrowed_books'  => (int) ($borrowed[0]['cnt'] ?? 0),
                'overdue_books'   => (int) ($overdue[0]['cnt'] ?? 0),
            ];
        } catch (\RuntimeException $e) {
            return ['total_books' => 0, 'available_books' => 0, 'borrowed_books' => 0, 'overdue_books' => 0];
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Validation
    // ─────────────────────────────────────────────────────────

    private function validateBook(array $input): array
    {
        $errors = [];
        if (empty($input['title'])) $errors['title'] = 'Book title is required.';
        if (empty($input['author'])) $errors['author'] = 'Author is required.';
        if (empty($input['total_copies']) || (int) $input['total_copies'] < 1) {
            $errors['total_copies'] = 'Total copies must be at least 1.';
        }
        return $errors;
    }

    private function requestJson(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    // ─────────────────────────────────────────────────────────
    //  Web Route Stubs
    // ─────────────────────────────────────────────────────────

    public function books(): void { $this->index(); }
    public function createBook(): void { $this->index(); }
    public function storeBook(): void { $this->redirect('/library'); }
    public function showBook(string $id): void { $this->index(); }
    public function editBook(string $id): void { $this->index(); }
    public function updateBook(string $id): void { $this->redirect('/library'); }
    public function deleteBook(string $id): void { $this->redirect('/library'); }
    public function issue(): void { $this->index(); }
    public function storeIssue(): void { $this->redirect('/library'); }
    public function returns(): void { $this->index(); }

    // ─────────────────────────────────────────────────────────
    //  API Route Stubs (renamed methods from routes)
    // ─────────────────────────────────────────────────────────

    public function apiBooks(): void { $this->apiIndex(); }
    public function apiStoreBook(): void { $this->apiStore(); }
    public function apiShowBook(): void
    {
        $this->requireAuth();
        $id = $this->input('id', '');
        $this->success($this->db->find('library_books', $id));
    }
    public function apiUpdateBook(): void { $this->apiUpdate(); }
    public function apiDeleteBook(): void { $this->apiDestroy(); }
    public function apiIssues(): void
    {
        $this->requireAuth();
        $this->success($this->fetchBorrowals());
    }
    public function apiStoreIssue(): void { $this->apiBorrow(); }
    public function apiReturnBook(string $id): void { $this->apiReturn(); }
}
