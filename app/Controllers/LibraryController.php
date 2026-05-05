<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Core\CSRF;
use App\Core\View;

/**
 * LibraryController
 *
 * Manages library books, inventory, and student borrowals.
 * Supports CRUD for library_books and borrowals tables.
 */
class LibraryController
{
    private $auth;
    private $session;
    private $request;
    private $csrf;
    private $view;
    private $supabaseUrl;
    private $supabaseKey;

    public function __construct()
    {
        $this->auth    = new Auth();
        $this->session = new Session();
        $this->request = new Request();
        $this->csrf    = new CSRF();
        $this->view    = new View();

        $this->supabaseUrl = getenv('SUPABASE_URL') ?: 'https://example.supabase.co';
        $this->supabaseKey = getenv('SUPABASE_ANON_KEY') ?: '';
    }

    // ─────────────────────────────────────────────────────────
    //  Web Routes
    // ─────────────────────────────────────────────────────────

    public function index(): void
    {
        if (!$this->auth->check()) {
            $this->session->flash('error', 'Please log in to access this page.');
            $this->redirect('/login');
            return;
        }

        $user = $this->auth->user();

        $books     = $this->fetchBooks($user);
        $borrowals = $this->fetchBorrowals($user);
        $students  = $this->fetchStudents($user);
        $stats     = $this->fetchLibraryStats($user);

        $flashSuccess = $this->session->getFlash('success');
        $flashError   = $this->session->getFlash('error');

        $this->view->renderWithLayout('library/index', 'layouts/app', [
            'pageTitle'    => 'Library',
            'user'         => $user,
            'currentPage'  => 'library',
            'books'        => $books,
            'borrowals'    => $borrowals,
            'students'     => $students,
            'stats'        => $stats,
            'flashSuccess' => $flashSuccess,
            'flashError'   => $flashError,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes (JSON responses)
    // ─────────────────────────────────────────────────────────

    public function apiIndex(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $user = $this->auth->user();
        $books = $this->fetchBooks($user);

        Response::json(['success' => true, 'data' => $books], 200);
    }

    public function apiStore(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $input = $this->request->jsonBody();
        $user  = $this->auth->user();

        $errors = $this->validateBook($input);
        if (!empty($errors)) {
            Response::json(['success' => false, 'error' => 'Validation failed.', 'errors' => $errors], 422);
            return;
        }

        $data = [
            'school_id'       => $user['school_id'] ?? 1,
            'isbn'            => $input['isbn'] ?? '',
            'title'           => $input['title'],
            'author'          => $input['author'],
            'publisher'       => $input['publisher'] ?? '',
            'category'        => $input['category'] ?? 'General',
            'total_copies'    => (int) ($input['total_copies'] ?? 1),
            'available_copies'=> (int) ($input['total_copies'] ?? 1),
            'shelf_location'  => $input['shelf_location'] ?? '',
            'status'          => $input['status'] ?? 'available',
        ];

        $result = $this->supabaseInsert('library_books', $data);

        if ($result) {
            Response::json(['success' => true, 'data' => $result, 'message' => 'Book added successfully.'], 201);
        } else {
            Response::json(['success' => false, 'error' => 'Failed to add book.'], 500);
        }
    }

    public function apiUpdate(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $input = $this->request->jsonBody();
        $id    = $input['id'] ?? '';

        if (empty($id)) {
            Response::json(['success' => false, 'error' => 'Book ID is required.'], 422);
            return;
        }

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

        $result = $this->supabaseUpdate('library_books', $id, $data);

        if ($result) {
            Response::json(['success' => true, 'data' => $result, 'message' => 'Book updated successfully.'], 200);
        } else {
            Response::json(['success' => false, 'error' => 'Failed to update book.'], 500);
        }
    }

    public function apiDestroy(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $id = $this->request->get('id', '');

        if (empty($id)) {
            Response::json(['success' => false, 'error' => 'Book ID is required.'], 422);
            return;
        }

        $result = $this->supabaseDelete('library_books', 'id', $id);

        if ($result) {
            Response::json(['success' => true, 'message' => 'Book deleted successfully.'], 200);
        } else {
            Response::json(['success' => false, 'error' => 'Failed to delete book.'], 500);
        }
    }

    /**
     * Record a book borrowal.
     * POST /api/library/borrow
     */
    public function apiBorrow(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $input = $this->request->jsonBody();

        if (empty($input['book_id']) || empty($input['student_id'])) {
            Response::json(['success' => false, 'error' => 'Book and student are required.'], 422);
            return;
        }

        $data = [
            'book_id'       => $input['book_id'],
            'student_id'    => $input['student_id'],
            'borrowed_date' => date('Y-m-d'),
            'due_date'      => $input['due_date'] ?? date('Y-m-d', strtotime('+14 days')),
            'status'        => 'borrowed',
            'fine'          => 0,
        ];

        $result = $this->supabaseInsert('borrowals', $data);

        if ($result) {
            // Decrement available copies
            $book = $this->supabaseFetch("library_books?select=available_copies&id=eq.{$input['book_id']}");
            if (!empty($book) && !empty($book[0])) {
                $newAvailable = max(0, (int) $book[0]['available_copies'] - 1);
                $this->supabaseUpdate('library_books', $input['book_id'], ['available_copies' => $newAvailable]);
            }

            Response::json(['success' => true, 'data' => $result, 'message' => 'Book borrowed successfully.'], 201);
        } else {
            Response::json(['success' => false, 'error' => 'Failed to record borrowal.'], 500);
        }
    }

    /**
     * Return a borrowed book.
     * POST /api/library/return
     */
    public function apiReturn(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $input = $this->request->jsonBody();
        $id    = $input['borrowal_id'] ?? '';

        if (empty($id)) {
            Response::json(['success' => false, 'error' => 'Borrowal ID is required.'], 422);
            return;
        }

        $result = $this->supabaseUpdate('borrowals', $id, [
            'returned_date' => date('Y-m-d'),
            'status'        => 'returned',
        ]);

        if ($result) {
            // Increment available copies
            $bookId = $result['book_id'] ?? '';
            if (!empty($bookId)) {
                $book = $this->supabaseFetch("library_books?select=available_copies,total_copies&id=eq.{$bookId}");
                if (!empty($book) && !empty($book[0])) {
                    $newAvailable = min((int) $book[0]['total_copies'], (int) $book[0]['available_copies'] + 1);
                    $this->supabaseUpdate('library_books', $bookId, ['available_copies' => $newAvailable]);
                }
            }

            Response::json(['success' => true, 'message' => 'Book returned successfully.'], 200);
        } else {
            Response::json(['success' => false, 'error' => 'Failed to return book.'], 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Private Data Fetching
    // ─────────────────────────────────────────────────────────

    private function fetchBooks(array $user): array
    {
        $data = $this->supabaseFetch('library_books?select=*&order=title');

        if (empty($data)) {
            return [
                ['id' => '1', 'isbn' => '978-0134685991', 'title' => 'Mathematics Form 1', 'author' => 'K. Muchiri', 'publisher' => 'Kenya Literature Bureau', 'category' => 'Mathematics', 'total_copies' => 30, 'available_copies' => 22, 'shelf_location' => 'A1-03', 'status' => 'available'],
                ['id' => '2', 'isbn' => '978-0134685992', 'title' => 'English Grammar in Use', 'author' => 'R. Murphy', 'publisher' => 'Cambridge Press', 'category' => 'English', 'total_copies' => 25, 'available_copies' => 18, 'shelf_location' => 'B2-05', 'status' => 'available'],
                ['id' => '3', 'isbn' => '978-0134685993', 'title' => 'Biology for Schools', 'author' => 'D. Mackean', 'publisher' => 'Oxford Press', 'category' => 'Sciences', 'total_copies' => 20, 'available_copies' => 20, 'shelf_location' => 'C1-12', 'status' => 'available'],
                ['id' => '4', 'isbn' => '978-0134685994', 'title' => 'Kiswahili Sanifu', 'author' => 'J. Mochi', 'publisher' => 'Longhorn Publishers', 'category' => 'Languages', 'total_copies' => 15, 'available_copies' => 5, 'shelf_location' => 'D3-08', 'status' => 'available'],
                ['id' => '5', 'isbn' => '978-0134685995', 'title' => 'Physics: Principles with Applications', 'author' => 'D. Giancoli', 'publisher' => 'Pearson', 'category' => 'Sciences', 'total_copies' => 18, 'available_copies' => 10, 'shelf_location' => 'C2-04', 'status' => 'available'],
                ['id' => '6', 'isbn' => '978-0134685996', 'title' => 'History of Kenya', 'author' => 'W. Ochieng', 'publisher' => 'East African Publishers', 'category' => 'Humanities', 'total_copies' => 12, 'available_copies' => 0, 'shelf_location' => 'E1-01', 'status' => 'unavailable'],
            ];
        }

        return $data;
    }

    private function fetchBorrowals(array $user): array
    {
        $data = $this->supabaseFetch('borrowals?select=*,book:library_books(title,author),student:students(first_name,last_name,admission_number)&order=borrowed_date.desc');

        if (empty($data)) {
            return [
                ['id' => '1', 'book_id' => '1', 'student_id' => '1', 'book' => ['title' => 'Mathematics Form 1', 'author' => 'K. Muchiri'], 'student' => ['first_name' => 'Amina', 'last_name' => 'Hassan', 'admission_number' => 'ADM/2024/001'], 'borrowed_date' => '2024-11-10', 'due_date' => '2024-11-24', 'returned_date' => null, 'status' => 'overdue', 'fine' => 100],
                ['id' => '2', 'book_id' => '2', 'student_id' => '2', 'book' => ['title' => 'English Grammar in Use', 'author' => 'R. Murphy'], 'student' => ['first_name' => 'Brian', 'last_name' => 'Njorge', 'admission_number' => 'ADM/2024/002'], 'borrowed_date' => '2024-11-20', 'due_date' => '2024-12-04', 'returned_date' => null, 'status' => 'borrowed', 'fine' => 0],
                ['id' => '3', 'book_id' => '4', 'student_id' => '3', 'book' => ['title' => 'Kiswahili Sanifu', 'author' => 'J. Mochi'], 'student' => ['first_name' => 'Mary', 'last_name' => 'Wanjiku', 'admission_number' => 'ADM/2024/003'], 'borrowed_date' => '2024-11-01', 'due_date' => '2024-11-15', 'returned_date' => '2024-11-14', 'status' => 'returned', 'fine' => 0],
                ['id' => '4', 'book_id' => '5', 'student_id' => '4', 'book' => ['title' => 'Physics: Principles', 'author' => 'D. Giancoli'], 'student' => ['first_name' => 'James', 'last_name' => 'Ochieng', 'admission_number' => 'ADM/2024/004'], 'borrowed_date' => '2024-11-18', 'due_date' => '2024-12-02', 'returned_date' => null, 'status' => 'borrowed', 'fine' => 0],
            ];
        }

        return $data;
    }

    private function fetchStudents(array $user): array
    {
        $students = $this->supabaseFetch('users?select=id,first_name,last_name,admission_number&role=eq.Student&order=first_name');
        if (empty($students)) {
            return [
                ['id' => '1', 'first_name' => 'Amina', 'last_name' => 'Hassan', 'admission_number' => 'ADM/2024/001'],
                ['id' => '2', 'first_name' => 'Brian', 'last_name' => 'Njorge', 'admission_number' => 'ADM/2024/002'],
                ['id' => '3', 'first_name' => 'Mary', 'last_name' => 'Wanjiku', 'admission_number' => 'ADM/2024/003'],
            ];
        }
        return $students;
    }

    private function fetchLibraryStats(array $user): array
    {
        return [
            'total_books'     => 1240,
            'available_books' => 890,
            'borrowed_books'  => 310,
            'overdue_books'   => 24,
        ];
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

    // ─────────────────────────────────────────────────────────
    //  Supabase Helpers
    // ─────────────────────────────────────────────────────────

    private function supabaseFetch(string $query): ?array
    {
        $url  = "{$this->supabaseUrl}/rest/v1/{$query}";
        $url .= '&apikey=' . urlencode($this->supabaseKey);
        $context = stream_context_create(['http' => ['method' => 'GET', 'header' => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\n", 'timeout' => 10, 'ignore_errors' => true]]);
        $response = @file_get_contents($url, false, $context);
        return $response === false ? null : json_decode($response, true);
    }

    private function supabaseInsert(string $table, array $data): ?array
    {
        $url = "{$this->supabaseUrl}/rest/v1/{$table}";
        $context = stream_context_create(['http' => ['method' => 'POST', 'header' => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\nPrefer: return=representation", 'content' => json_encode($data), 'timeout' => 10, 'ignore_errors' => true]]);
        $response = @file_get_contents($url, false, $context);
        if ($response === false) return null;
        $result = json_decode($response, true);
        return is_array($result) && !empty($result) ? $result[0] : null;
    }

    private function supabaseUpdate(string $table, string $id, array $data): ?array
    {
        $url = "{$this->supabaseUrl}/rest/v1/{$table}?id=eq.{$id}";
        $context = stream_context_create(['http' => ['method' => 'PATCH', 'header' => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\nPrefer: return=representation", 'content' => json_encode($data), 'timeout' => 10, 'ignore_errors' => true]]);
        $response = @file_get_contents($url, false, $context);
        if ($response === false) return null;
        $result = json_decode($response, true);
        return is_array($result) && !empty($result) ? $result[0] : null;
    }

    private function supabaseDelete(string $table, string $column, string $value): bool
    {
        $url = "{$this->supabaseUrl}/rest/v1/{$table}?{$column}=eq.{$value}";
        $context = stream_context_create(['http' => ['method' => 'DELETE', 'header' => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\n", 'timeout' => 10, 'ignore_errors' => true]]);
        return @file_get_contents($url, false, $context) !== false;
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
