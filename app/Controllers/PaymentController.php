<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * PaymentController
 *
 * Manages student fee payments, receipt generation, and payment records.
 * Supports multiple payment methods: cash, M-Pesa, bank transfer, cheque.
 * Uses MySQL database for all operations.
 */
class PaymentController extends Controller
{
    /**
     * Payments index page.
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.view');

        $payments = $this->fetchPayments();
        $students = $this->fetchStudents();
        $feeStructures = $this->fetchFeeStructures();
        $stats = $this->fetchPaymentStats();

        $this->renderWithLayout('payments/index', [
            'pageTitle'     => 'Payments',
            'currentPage'   => 'payments',
            'payments'      => $payments,
            'students'      => $students,
            'feeStructures' => $feeStructures,
            'stats'         => $stats,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes (JSON responses)
    // ─────────────────────────────────────────────────────────

    public function apiIndex(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.view');
        $this->success($this->fetchPayments());
    }

    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Accountant']);

        $input = $this->requestJson();
        $errors = $this->validatePayment($input);
        if (!empty($errors)) {
            $this->error('Validation failed.', 422, $errors);
            return;
        }

        try {
            $receiptNumber = $this->generateReceiptNumber();

            $data = [
                'student_id'       => $input['student_id'],
                'fee_structure_id' => $input['fee_structure_id'] ?? null,
                'amount'           => (float) $input['amount'],
                'payment_method'   => $input['payment_method'],
                'transaction_ref'  => $input['transaction_ref'] ?? '',
                'receipt_number'   => $receiptNumber,
                'status'           => 'completed',
                'payment_date'     => date('Y-m-d'),
                'created_by'       => $this->currentUserId(),
            ];

            $result = $this->db->insert('payments', $data);
            $this->success($result, "Payment recorded successfully. Receipt: {$receiptNumber}", 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to record payment: ' . $e->getMessage(), 500);
        }
    }

    public function apiUpdate(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Accountant']);

        $input = $this->requestJson();
        $id = $input['id'] ?? '';

        if (empty($id)) {
            $this->error('Payment ID is required.', 422);
            return;
        }

        try {
            $data = array_filter([
                'amount'          => isset($input['amount']) ? (float) $input['amount'] : null,
                'payment_method'  => $input['payment_method'] ?? null,
                'transaction_ref' => $input['transaction_ref'] ?? null,
                'status'          => $input['status'] ?? null,
            ], fn($v) => $v !== null);

            $result = $this->db->updateById('payments', $id, $data);
            $this->success($result, 'Payment updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update payment: ' . $e->getMessage(), 500);
        }
    }

    public function apiDestroy(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.delete');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $id = $this->input('id', '');
        if (empty($id)) {
            $this->error('Payment ID is required.', 422);
            return;
        }

        try {
            $this->db->deleteById('payments', $id);
            $this->success(null, 'Payment deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete payment: ' . $e->getMessage(), 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Data Fetching
    // ─────────────────────────────────────────────────────────

    private function fetchPayments(): array
    {
        try {
            return $this->db->raw(
                "SELECT p.*,
                        u.first_name as student_first_name, u.last_name as student_last_name,
                        sp.admission_no as student_admission_no
                 FROM payments p
                 LEFT JOIN users u ON p.student_id = u.id
                 LEFT JOIN student_profiles sp ON u.id = sp.user_id
                 ORDER BY p.payment_date DESC"
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

    private function fetchFeeStructures(): array
    {
        try {
            return $this->db->raw(
                "SELECT fs.id, c.name as class_name, fs.term, fs.academic_year, fs.total_amount
                 FROM fee_structures fs
                 LEFT JOIN classes c ON fs.class_id = c.id
                 ORDER BY fs.created_at DESC"
            );
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    private function fetchPaymentStats(): array
    {
        try {
            $total = $this->db->raw("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'completed'");
            $today = $this->db->raw("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'completed' AND payment_date = CURDATE()");
            $transactions = $this->db->raw("SELECT COUNT(*) as cnt FROM payments");
            $pending = $this->db->raw("SELECT COUNT(*) as cnt FROM payments WHERE status = 'pending'");

            return [
                'total_collected'    => (float) ($total[0]['total'] ?? 0),
                'today_collected'    => (float) ($today[0]['total'] ?? 0),
                'total_transactions' => (int) ($transactions[0]['cnt'] ?? 0),
                'pending_count'      => (int) ($pending[0]['cnt'] ?? 0),
            ];
        } catch (\RuntimeException $e) {
            return ['total_collected' => 0, 'today_collected' => 0, 'total_transactions' => 0, 'pending_count' => 0];
        }
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

    private function requestJson(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    // ─────────────────────────────────────────────────────────
    //  Web CRUD Stubs
    // ─────────────────────────────────────────────────────────

    public function create(): void { $this->index(); }
    public function store(): void { $this->redirect('/payments'); }
    public function show(string $id): void { $this->index(); }
    public function edit(string $id): void { $this->index(); }
    public function update(string $id): void { $this->redirect('/payments'); }
    public function delete(string $id): void { $this->redirect('/payments'); }
    public function receipt(string $id): void { $this->index(); }

    // Missing API methods
    public function apiShow(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.view');
        $id = $this->input('id', '');
        $item = $this->db->find('payments', $id);
        $this->success($item);
    }

    public function apiDelete(): void { $this->apiDestroy(); }

    public function apiGenerateReceipt(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.view');
        $id = $this->input('id', '');
        $payment = $this->db->find('payments', $id);
        $this->success($payment);
    }
}
