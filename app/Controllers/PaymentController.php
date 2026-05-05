<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Core\CSRF;
use App\Core\View;

/**
 * PaymentController
 *
 * Manages student fee payments, receipt generation, and payment records.
 * Supports multiple payment methods: cash, M-Pesa, bank transfer, cheque.
 */
class PaymentController
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
     * Payments index page.
     */
    public function index(): void
    {
        if (!$this->auth->check()) {
            $this->session->flash('error', 'Please log in to access this page.');
            $this->redirect('/login');
            return;
        }

        $user = $this->auth->user();

        $payments = $this->fetchPayments($user);
        $students = $this->fetchStudents($user);
        $feeStructures = $this->fetchFeeStructures($user);
        $stats = $this->fetchPaymentStats($user);

        $flashSuccess = $this->session->getFlash('success');
        $flashError   = $this->session->getFlash('error');

        $this->view->renderWithLayout('payments/index', 'layouts/app', [
            'pageTitle'      => 'Payments',
            'user'           => $user,
            'currentPage'    => 'payments',
            'payments'       => $payments,
            'students'       => $students,
            'feeStructures'  => $feeStructures,
            'stats'          => $stats,
            'flashSuccess'   => $flashSuccess,
            'flashError'     => $flashError,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes (JSON responses)
    // ─────────────────────────────────────────────────────────

    /**
     * List all payments as JSON.
     * GET /api/payments
     */
    public function apiIndex(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json(['success' => false, 'error' => 'Not authenticated.'], 401);
            return;
        }

        $user = $this->auth->user();
        $payments = $this->fetchPayments($user);

        Response::json([
            'success' => true,
            'data'    => $payments,
        ], 200);
    }

    /**
     * Record a new payment.
     * POST /api/payments
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

        // Validate
        $errors = $this->validatePayment($input);
        if (!empty($errors)) {
            Response::json(['success' => false, 'error' => 'Validation failed.', 'errors' => $errors], 422);
            return;
        }

        // Generate receipt number
        $receiptNumber = $this->generateReceiptNumber();

        $data = [
            'student_id'      => $input['student_id'],
            'fee_structure_id' => $input['fee_structure_id'] ?? null,
            'amount'          => (float) $input['amount'],
            'payment_method'  => $input['payment_method'],
            'transaction_ref' => $input['transaction_ref'] ?? '',
            'receipt_number'  => $receiptNumber,
            'status'          => 'completed',
            'payment_date'    => date('Y-m-d'),
            'created_by'      => $user['id'] ?? null,
        ];

        $result = $this->supabaseInsert('payments', $data);

        if ($result) {
            Response::json([
                'success' => true,
                'data'    => $result,
                'message' => "Payment recorded successfully. Receipt: {$receiptNumber}",
            ], 201);
        } else {
            Response::json(['success' => false, 'error' => 'Failed to record payment.'], 500);
        }
    }

    /**
     * Update a payment.
     * PUT /api/payments/{id}
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
            Response::json(['success' => false, 'error' => 'Payment ID is required.'], 422);
            return;
        }

        $data = array_filter([
            'amount'          => isset($input['amount']) ? (float) $input['amount'] : null,
            'payment_method'  => $input['payment_method'] ?? null,
            'transaction_ref' => $input['transaction_ref'] ?? null,
            'status'          => $input['status'] ?? null,
        ], fn($v) => $v !== null);

        $result = $this->supabaseUpdate('payments', $id, $data);

        if ($result) {
            Response::json(['success' => true, 'data' => $result, 'message' => 'Payment updated successfully.'], 200);
        } else {
            Response::json(['success' => false, 'error' => 'Failed to update payment.'], 500);
        }
    }

    /**
     * Delete a payment.
     * DELETE /api/payments/{id}
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
            Response::json(['success' => false, 'error' => 'Payment ID is required.'], 422);
            return;
        }

        $result = $this->supabaseDelete('payments', 'id', $id);

        if ($result) {
            Response::json(['success' => true, 'message' => 'Payment deleted successfully.'], 200);
        } else {
            Response::json(['success' => false, 'error' => 'Failed to delete payment.'], 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Private Data Fetching Methods
    // ─────────────────────────────────────────────────────────

    private function fetchPayments(array $user): array
    {
        $data = $this->supabaseFetch('payments?select=*,student:students(id,first_name,last_name,admission_number)&order=payment_date.desc');

        if (empty($data)) {
            return [
                [
                    'id'              => '1',
                    'student_id'      => '1',
                    'student'         => ['first_name' => 'Amina', 'last_name' => 'Hassan', 'admission_number' => 'ADM/2024/001'],
                    'fee_structure_id' => '1',
                    'amount'          => 15000,
                    'payment_method'  => 'mpesa',
                    'transaction_ref' => 'SBK3G5Y8X2',
                    'receipt_number'  => 'RCP-2024-0001',
                    'status'          => 'completed',
                    'payment_date'    => '2024-11-28',
                    'created_at'      => '2024-11-28T10:30:00',
                ],
                [
                    'id'              => '2',
                    'student_id'      => '2',
                    'student'         => ['first_name' => 'Brian', 'last_name' => 'Njorge', 'admission_number' => 'ADM/2024/002'],
                    'fee_structure_id' => '2',
                    'amount'          => 25000,
                    'payment_method'  => 'bank_transfer',
                    'transaction_ref' => 'BANK-2024-001',
                    'receipt_number'  => 'RCP-2024-0002',
                    'status'          => 'completed',
                    'payment_date'    => '2024-11-27',
                    'created_at'      => '2024-11-27T14:15:00',
                ],
                [
                    'id'              => '3',
                    'student_id'      => '3',
                    'student'         => ['first_name' => 'Mary', 'last_name' => 'Wanjiku', 'admission_number' => 'ADM/2024/003'],
                    'fee_structure_id' => '1',
                    'amount'          => 10000,
                    'payment_method'  => 'cash',
                    'transaction_ref' => '',
                    'receipt_number'  => 'RCP-2024-0003',
                    'status'          => 'pending',
                    'payment_date'    => '2024-11-26',
                    'created_at'      => '2024-11-26T09:00:00',
                ],
                [
                    'id'              => '4',
                    'student_id'      => '4',
                    'student'         => ['first_name' => 'James', 'last_name' => 'Ochieng', 'admission_number' => 'ADM/2024/004'],
                    'fee_structure_id' => '3',
                    'amount'          => 20000,
                    'payment_method'  => 'cheque',
                    'transaction_ref' => 'CHQ-2024-001',
                    'receipt_number'  => 'RCP-2024-0004',
                    'status'          => 'failed',
                    'payment_date'    => '2024-11-25',
                    'created_at'      => '2024-11-25T16:45:00',
                ],
                [
                    'id'              => '5',
                    'student_id'      => '5',
                    'student'         => ['first_name' => 'Grace', 'last_name' => 'Muthoni', 'admission_number' => 'ADM/2024/005'],
                    'fee_structure_id' => '2',
                    'amount'          => 30000,
                    'payment_method'  => 'mpesa',
                    'transaction_ref' => 'SBK7H2J9K4',
                    'receipt_number'  => 'RCP-2024-0005',
                    'status'          => 'completed',
                    'payment_date'    => '2024-11-24',
                    'created_at'      => '2024-11-24T11:20:00',
                ],
                [
                    'id'              => '6',
                    'student_id'      => '6',
                    'student'         => ['first_name' => 'Kevin', 'last_name' => 'Otieno', 'admission_number' => 'ADM/2024/006'],
                    'fee_structure_id' => '1',
                    'amount'          => 5000,
                    'payment_method'  => 'cash',
                    'transaction_ref' => '',
                    'receipt_number'  => 'RCP-2024-0006',
                    'status'          => 'refunded',
                    'payment_date'    => '2024-11-20',
                    'created_at'      => '2024-11-20T08:30:00',
                ],
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
                ['id' => '4', 'first_name' => 'James', 'last_name' => 'Ochieng', 'admission_number' => 'ADM/2024/004'],
                ['id' => '5', 'first_name' => 'Grace', 'last_name' => 'Muthoni', 'admission_number' => 'ADM/2024/005'],
                ['id' => '6', 'first_name' => 'Kevin', 'last_name' => 'Otieno', 'admission_number' => 'ADM/2024/006'],
            ];
        }

        return $students;
    }

    private function fetchFeeStructures(array $user): array
    {
        $data = $this->supabaseFetch('fee_structures?select=id,class:classes(name),term,academic_year,total_amount&order=created_at.desc');

        if (empty($data)) {
            return [
                ['id' => '1', 'class' => ['name' => 'Form 1A'], 'term' => 'Term 1', 'academic_year' => '2024-2025', 'total_amount' => 45000],
                ['id' => '2', 'class' => ['name' => 'Form 2A'], 'term' => 'Term 1', 'academic_year' => '2024-2025', 'total_amount' => 48000],
                ['id' => '3', 'class' => ['name' => 'Form 3A'], 'term' => 'Term 1', 'academic_year' => '2024-2025', 'total_amount' => 52000],
            ];
        }

        return $data;
    }

    private function fetchPaymentStats(array $user): array
    {
        return [
            'total_collected'   => 1250000,
            'today_collected'   => 15000,
            'total_transactions' => 186,
            'pending_count'     => 12,
        ];
    }

    // ─────────────────────────────────────────────────────────
    //  Validation
    // ─────────────────────────────────────────────────────────

    private function validatePayment(array $input): array
    {
        $errors = [];

        if (empty($input['student_id'])) {
            $errors['student_id'] = 'Student is required.';
        }
        if (empty($input['amount']) || !is_numeric($input['amount']) || (float) $input['amount'] <= 0) {
            $errors['amount'] = 'Amount must be a positive number.';
        }
        if (empty($input['payment_method'])) {
            $errors['payment_method'] = 'Payment method is required.';
        }

        $validMethods = ['cash', 'mpesa', 'bank_transfer', 'cheque'];
        if (!empty($input['payment_method']) && !in_array($input['payment_method'], $validMethods)) {
            $errors['payment_method'] = 'Invalid payment method.';
        }

        return $errors;
    }

    /**
     * Generate a unique receipt number.
     * Format: RCP-YYYY-NNNN
     */
    private function generateReceiptNumber(): string
    {
        $year = date('Y');
        $random = strtoupper(bin2hex(random_bytes(3)));
        return "RCP-{$year}-{$random}";
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
                'method'        => 'GET',
                'header'        => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\n",
                'timeout'       => 10,
                'ignore_errors' => true,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);
        return $response === false ? null : json_decode($response, true);
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
        if ($response === false) return null;
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
        if ($response === false) return null;
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

        return @file_get_contents($url, false, $context) !== false;
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
