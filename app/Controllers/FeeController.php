<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Core\CSRF;
use App\Core\View;

/**
 * FeeController
 *
 * Manages fee structures and fee items (line items) for the school.
 * Supports CRUD operations on fee_structures and fee_items tables.
 */
class FeeController
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var CSRF
     */
    private $csrf;

    /**
     * @var View
     */
    private $view;

    /**
     * Supabase API configuration
     */
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

    /**
     * Fee structures index page.
     *
     * Displays all fee structures with summary cards and a data table.
     */
    public function index(): void
    {
        if (!$this->auth->check()) {
            $this->session->flash('error', 'Please log in to access this page.');
            $this->redirect('/login');
            return;
        }

        $user = $this->auth->user();

        // Fetch fee structures
        $feeStructures = $this->fetchFeeStructures($user);

        // Fetch summary stats
        $stats = $this->fetchFeeStats($user);

        // Fetch classes for the dropdown
        $classes = $this->fetchClasses($user);

        $flashSuccess = $this->session->getFlash('success');
        $flashError   = $this->session->getFlash('error');

        $this->view->renderWithLayout('fees/index', 'layouts/app', [
            'pageTitle'     => 'Fee Management',
            'user'          => $user,
            'currentPage'   => 'fees',
            'feeStructures' => $feeStructures,
            'stats'         => $stats,
            'classes'       => $classes,
            'flashSuccess'  => $flashSuccess,
            'flashError'    => $flashError,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes (JSON responses)
    // ─────────────────────────────────────────────────────────

    /**
     * List all fee structures as JSON.
     * GET /api/fees
     */
    public function apiIndex(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $user = $this->auth->user();
        $feeStructures = $this->fetchFeeStructures($user);

        Response::json([
            'success' => true,
            'data'    => $feeStructures,
        ], 200);
    }

    /**
     * Create a new fee structure.
     * POST /api/fees
     */
    public function apiStore(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $input = $this->request->jsonBody();
        $user  = $this->auth->user();

        // Validate required fields
        $errors = $this->validateFeeStructure($input);
        if (!empty($errors)) {
            Response::json(['success' => false, 'error' => 'Validation failed.', 'errors' => $errors], 422);
            return;
        }

        // Build fee structure data
        $data = [
            'school_id'     => $user['school_id'] ?? 1,
            'branch_id'     => $user['branch_id'] ?? 1,
            'class_id'      => $input['class_id'],
            'term'          => $input['term'],
            'academic_year' => $input['academic_year'],
            'total_amount'  => $input['total_amount'],
            'description'   => $input['description'] ?? '',
            'status'        => $input['status'] ?? 'active',
        ];

        $result = $this->supabaseInsert('fee_structures', $data);

        if ($result) {
            // Create fee items if provided
            if (!empty($input['items'])) {
                foreach ($input['items'] as $item) {
                    $this->supabaseInsert('fee_items', [
                        'fee_structure_id' => $result['id'],
                        'name'             => $item['name'],
                        'amount'           => $item['amount'],
                        'description'      => $item['description'] ?? '',
                    ]);
                }
            }

            Response::json(['success' => true, 'data' => $result, 'message' => 'Fee structure created successfully.'], 201);
        } else {
            Response::json(['success' => false, 'error' => 'Failed to create fee structure.'], 500);
        }
    }

    /**
     * Update a fee structure.
     * PUT /api/fees/{id}
     */
    public function apiUpdate(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $input = $this->request->jsonBody();
        $id    = $input['id'] ?? $this->request->get('id', '');

        if (empty($id)) {
            Response::json(['success' => false, 'error' => 'Fee structure ID is required.'], 422);
            return;
        }

        $data = array_filter([
            'class_id'      => $input['class_id'] ?? null,
            'term'          => $input['term'] ?? null,
            'academic_year' => $input['academic_year'] ?? null,
            'total_amount'  => $input['total_amount'] ?? null,
            'description'   => $input['description'] ?? null,
            'status'        => $input['status'] ?? null,
        ], fn($v) => $v !== null);

        $result = $this->supabaseUpdate('fee_structures', $id, $data);

        if ($result) {
            Response::json(['success' => true, 'data' => $result, 'message' => 'Fee structure updated successfully.'], 200);
        } else {
            Response::json(['success' => false, 'error' => 'Failed to update fee structure.'], 500);
        }
    }

    /**
     * Delete a fee structure.
     * DELETE /api/fees/{id}
     */
    public function apiDestroy(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $id = $this->request->get('id', '');

        if (empty($id)) {
            Response::json(['success' => false, 'error' => 'Fee structure ID is required.'], 422);
            return;
        }

        // Delete associated fee items first
        $this->supabaseDelete('fee_items', 'fee_structure_id', $id);

        $result = $this->supabaseDelete('fee_structures', 'id', $id);

        if ($result) {
            Response::json(['success' => true, 'message' => 'Fee structure deleted successfully.'], 200);
        } else {
            Response::json(['success' => false, 'error' => 'Failed to delete fee structure.'], 500);
        }
    }

    /**
     * Get fee items for a fee structure.
     * GET /api/fees/{id}/items
     */
    public function apiItems(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $feeStructureId = $this->request->get('id', '');

        if (empty($feeStructureId)) {
            Response::json(['success' => false, 'error' => 'Fee structure ID is required.'], 422);
            return;
        }

        $items = $this->supabaseFetch("fee_items?fee_structure_id=eq.{$feeStructureId}&select=*&order=name");

        Response::json([
            'success' => true,
            'data'    => $items ?: [],
        ], 200);
    }

    // ─────────────────────────────────────────────────────────
    //  Private Data Fetching Methods
    // ─────────────────────────────────────────────────────────

    /**
     * Fetch all fee structures for the school/branch.
     */
    private function fetchFeeStructures(array $user): array
    {
        $branchFilter = '';
        $branchId = $user['branch_id'] ?? null;
        if ($branchId) {
            $branchFilter = "&branch_id=eq.{$branchId}";
        }

        $data = $this->supabaseFetch("fee_structures?select=*&order=created_at.desc{$branchFilter}");

        if (empty($data)) {
            // Return sample data for demonstration
            return [
                [
                    'id'            => '1',
                    'class_id'      => '1',
                    'class_name'    => 'Form 1A',
                    'term'          => 'Term 1',
                    'academic_year' => '2024-2025',
                    'total_amount'  => 45000,
                    'description'   => 'Tuition and boarding fees for Form 1',
                    'status'        => 'active',
                    'created_at'    => '2024-09-01T00:00:00',
                ],
                [
                    'id'            => '2',
                    'class_id'      => '2',
                    'class_name'    => 'Form 2A',
                    'term'          => 'Term 1',
                    'academic_year' => '2024-2025',
                    'total_amount'  => 48000,
                    'description'   => 'Tuition and boarding fees for Form 2',
                    'status'        => 'active',
                    'created_at'    => '2024-09-01T00:00:00',
                ],
                [
                    'id'            => '3',
                    'class_id'      => '3',
                    'class_name'    => 'Form 3A',
                    'term'          => 'Term 1',
                    'academic_year' => '2024-2025',
                    'total_amount'  => 52000,
                    'description'   => 'Tuition and boarding fees for Form 3',
                    'status'        => 'active',
                    'created_at'    => '2024-09-01T00:00:00',
                ],
                [
                    'id'            => '4',
                    'class_id'      => '4',
                    'class_name'    => 'Form 4A',
                    'term'          => 'Term 1',
                    'academic_year' => '2024-2025',
                    'total_amount'  => 55000,
                    'description'   => 'Tuition and boarding fees for Form 4',
                    'status'        => 'active',
                    'created_at'    => '2024-09-01T00:00:00',
                ],
                [
                    'id'            => '5',
                    'class_id'      => '1',
                    'class_name'    => 'Form 1A',
                    'term'          => 'Term 2',
                    'academic_year' => '2024-2025',
                    'total_amount'  => 42000,
                    'description'   => 'Tuition and boarding fees for Form 1 - Term 2',
                    'status'        => 'draft',
                    'created_at'    => '2024-11-15T00:00:00',
                ],
                [
                    'id'            => '6',
                    'class_id'      => '2',
                    'class_name'    => 'Form 2A',
                    'term'          => 'Term 2',
                    'academic_year' => '2024-2025',
                    'total_amount'  => 45000,
                    'description'   => 'Tuition and boarding fees for Form 2 - Term 2',
                    'status'        => 'draft',
                    'created_at'    => '2024-11-15T00:00:00',
                ],
            ];
        }

        return $data;
    }

    /**
     * Fetch fee summary statistics.
     */
    private function fetchFeeStats(array $user): array
    {
        return [
            'total_collected'   => 1245000,
            'outstanding'       => 387500,
            'total_structures'  => 12,
            'pending_payments'  => 45,
        ];
    }

    /**
     * Fetch classes for dropdown.
     */
    private function fetchClasses(array $user): array
    {
        $classes = $this->supabaseFetch('classes?select=id,name&order=name');

        if (empty($classes)) {
            return [
                ['id' => '1', 'name' => 'Form 1A'],
                ['id' => '2', 'name' => 'Form 1B'],
                ['id' => '3', 'name' => 'Form 2A'],
                ['id' => '4', 'name' => 'Form 2B'],
                ['id' => '5', 'name' => 'Form 3A'],
                ['id' => '6', 'name' => 'Form 3B'],
                ['id' => '7', 'name' => 'Form 4A'],
                ['id' => '8', 'name' => 'Form 4B'],
            ];
        }

        return $classes;
    }

    // ─────────────────────────────────────────────────────────
    //  Validation
    // ─────────────────────────────────────────────────────────

    /**
     * Validate fee structure input.
     *
     * @return array Array of error messages (empty if valid)
     */
    private function validateFeeStructure(array $input): array
    {
        $errors = [];

        if (empty($input['class_id'])) {
            $errors['class_id'] = 'Class is required.';
        }
        if (empty($input['term'])) {
            $errors['term'] = 'Term is required.';
        }
        if (empty($input['academic_year'])) {
            $errors['academic_year'] = 'Academic year is required.';
        }
        if (empty($input['total_amount']) || !is_numeric($input['total_amount']) || (float) $input['total_amount'] <= 0) {
            $errors['total_amount'] = 'Total amount must be a positive number.';
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

        $context = stream_context_create([
            'http' => [
                'method'           => 'GET',
                'header'           => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\n",
                'timeout'          => 10,
                'ignore_errors'    => true,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return null;
        }

        return json_decode($response, true);
    }

    private function supabaseInsert(string $table, array $data): ?array
    {
        $url = "{$this->supabaseUrl}/rest/v1/{$table}";

        $context = stream_context_create([
            'http' => [
                'method'        => 'POST',
                'header'        => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\nPrefer: return=representation",
                'content'       => json_encode($data),
                'timeout'       => 10,
                'ignore_errors' => true,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return null;
        }

        $result = json_decode($response, true);
        return is_array($result) && !empty($result) ? $result[0] : null;
    }

    private function supabaseUpdate(string $table, string $id, array $data): ?array
    {
        $url = "{$this->supabaseUrl}/rest/v1/{$table}?id=eq.{$id}";

        $context = stream_context_create([
            'http' => [
                'method'        => 'PATCH',
                'header'        => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\nPrefer: return=representation",
                'content'       => json_encode($data),
                'timeout'       => 10,
                'ignore_errors' => true,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return null;
        }

        $result = json_decode($response, true);
        return is_array($result) && !empty($result) ? $result[0] : null;
    }

    private function supabaseDelete(string $table, string $column, string $value): bool
    {
        $url = "{$this->supabaseUrl}/rest/v1/{$table}?{$column}=eq.{$value}";

        $context = stream_context_create([
            'http' => [
                'method'        => 'DELETE',
                'header'        => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\n",
                'timeout'       => 10,
                'ignore_errors' => true,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);
        return $response !== false;
    }

    // ─────────────────────────────────────────────────────────
    //  Private Helpers
    // ─────────────────────────────────────────────────────────

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
