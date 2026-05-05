<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * FeeController
 *
 * Manages fee structures and fee items for the school.
 * Uses MySQL database for all CRUD operations.
 */
class FeeController extends Controller
{
    /**
     * Fee structures index page.
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.view');

        $feeStructures = $this->fetchFeeStructures();
        $stats = $this->fetchFeeStats();
        $classes = $this->fetchClasses();

        $this->renderWithLayout('fees/index', [
            'pageTitle'     => 'Fee Management',
            'currentPage'   => 'fees',
            'feeStructures' => $feeStructures,
            'stats'         => $stats,
            'classes'       => $classes,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes (JSON responses)
    // ─────────────────────────────────────────────────────────

    public function apiIndex(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.view');
        $this->success($this->fetchFeeStructures());
    }

    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin']);

        $input = $this->requestJson();
        $errors = $this->validateFeeStructure($input);

        if (!empty($errors)) {
            $this->error('Validation failed.', 422, $errors);
            return;
        }

        try {
            $data = [
                'school_id'     => $this->currentUserId(),
                'branch_id'     => $input['branch_id'] ?? null,
                'class_id'      => $input['class_id'],
                'term'          => $input['term'],
                'academic_year' => $input['academic_year'],
                'total_amount'  => $input['total_amount'],
                'description'   => $input['description'] ?? '',
                'status'        => $input['status'] ?? 'active',
            ];

            $result = $this->db->insert('fee_structures', $data);

            if (!empty($input['items']) && is_array($input['items'])) {
                foreach ($input['items'] as $item) {
                    $this->db->insert('fee_items', [
                        'fee_structure_id' => $result['id'] ?? 0,
                        'name'             => $item['name'],
                        'amount'           => $item['amount'],
                        'description'      => $item['description'] ?? '',
                    ]);
                }
            }

            $this->success($result, 'Fee structure created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create fee structure: ' . $e->getMessage(), 500);
        }
    }

    public function apiUpdate(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin']);

        $input = $this->requestJson();
        $id = $input['id'] ?? '';

        if (empty($id)) {
            $this->error('Fee structure ID is required.', 422);
            return;
        }

        try {
            $data = array_filter([
                'class_id'      => $input['class_id'] ?? null,
                'term'          => $input['term'] ?? null,
                'academic_year' => $input['academic_year'] ?? null,
                'total_amount'  => $input['total_amount'] ?? null,
                'description'   => $input['description'] ?? null,
                'status'        => $input['status'] ?? null,
            ], fn($v) => $v !== null);

            $result = $this->db->updateById('fee_structures', $id, $data);
            $this->success($result, 'Fee structure updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update fee structure: ' . $e->getMessage(), 500);
        }
    }

    public function apiDestroy(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.delete');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $id = $this->input('id', '');

        if (empty($id)) {
            $this->error('Fee structure ID is required.', 422);
            return;
        }

        try {
            // Delete associated fee items first
            $items = $this->db->select('fee_items', ['fee_structure_id' => ['eq' => $id]]);
            foreach ($items as $item) {
                $this->db->deleteById('fee_items', $item['id']);
            }

            $this->db->deleteById('fee_structures', $id);
            $this->success(null, 'Fee structure deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete fee structure: ' . $e->getMessage(), 500);
        }
    }

    public function apiItems(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.view');

        $feeStructureId = $this->input('id', '');

        if (empty($feeStructureId)) {
            $this->error('Fee structure ID is required.', 422);
            return;
        }

        try {
            $items = $this->db->select('fee_items', ['fee_structure_id' => ['eq' => $feeStructureId]], 'name.asc');
            $this->success($items);
        } catch (\RuntimeException $e) {
            $this->error('Failed to fetch fee items: ' . $e->getMessage(), 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Data Fetching
    // ─────────────────────────────────────────────────────────

    private function fetchFeeStructures(): array
    {
        try {
            return $this->db->raw(
                "SELECT fs.*, c.name as class_name
                 FROM fee_structures fs
                 LEFT JOIN classes c ON fs.class_id = c.id
                 ORDER BY fs.created_at DESC"
            );
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    private function fetchFeeStats(): array
    {
        try {
            $collected = $this->db->raw("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'completed'");
            $outstanding = $this->db->raw("SELECT COUNT(*) as cnt FROM fee_structures WHERE status = 'active'");
            $pending = $this->db->raw("SELECT COUNT(*) as cnt FROM payments WHERE status = 'pending'");

            return [
                'total_collected'  => (float) ($collected[0]['total'] ?? 0),
                'outstanding'      => (int) ($outstanding[0]['cnt'] ?? 0),
                'total_structures' => 0,
                'pending_payments' => (int) ($pending[0]['cnt'] ?? 0),
            ];
        } catch (\RuntimeException $e) {
            return ['total_collected' => 0, 'outstanding' => 0, 'total_structures' => 0, 'pending_payments' => 0];
        }
    }

    private function fetchClasses(): array
    {
        try {
            return $this->db->select('classes', [], 'name.asc');
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Validation
    // ─────────────────────────────────────────────────────────

    private function validateFeeStructure(array $input): array
    {
        $errors = [];
        if (empty($input['class_id'])) $errors['class_id'] = 'Class is required.';
        if (empty($input['term'])) $errors['term'] = 'Term is required.';
        if (empty($input['academic_year'])) $errors['academic_year'] = 'Academic year is required.';
        if (empty($input['total_amount']) || !is_numeric($input['total_amount']) || (float) $input['total_amount'] <= 0) {
            $errors['total_amount'] = 'Total amount must be a positive number.';
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
    //  Web CRUD Stubs (routes redirect to index)
    // ─────────────────────────────────────────────────────────

    public function create(): void { $this->index(); }
    public function store(): void { $this->redirect('/fees'); }
    public function show(string $id): void { $this->index(); }
    public function edit(string $id): void { $this->index(); }
    public function update(string $id): void { $this->redirect('/fees'); }
    public function delete(string $id): void { $this->redirect('/fees'); }

    // Missing API methods
    public function apiShow(): void
    {
        $this->requireAuth();
        $this->requirePermission('finance.view');
        $id = $this->input('id', '');
        $item = $this->db->find('fee_structures', $id);
        $this->success($item);
    }

    public function apiDelete(): void { $this->apiDestroy(); }
}
