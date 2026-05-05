<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * BranchController
 *
 * Manages school branches CRUD operations. Branches belong to schools
 * and can be filtered by the user's school-level scope.
 */
class BranchController extends Controller
{
    /**
     * List all branches with search, school filter, and pagination.
     * GET /branches
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $page = (int) ($this->input('page', 1) ?? 1);
        $search = $this->input('search', '');
        $schoolFilter = $this->input('school_id', '');
        $perPage = 15;

        $filters = [];

        // If user has school-level scope, auto-filter by their school
        $user = $this->currentUser();
        if ($user && isset($user['school_id']) && $user['school_id'] !== null) {
            $filters['school_id'] = ['eq' => $user['school_id']];
            $schoolFilter = $user['school_id'];
        } elseif (!empty($schoolFilter)) {
            $filters['school_id'] = ['eq' => $schoolFilter];
        }

        if (!empty($search)) {
            $filters['name'] = ['ilike' => '%' . $search . '%'];
        }

        $result = $this->paginate('branches', $page, $perPage, $filters, 'name.asc');

        // Fetch all schools for the dropdown
        $schools = $this->db->select('schools', [], 'name.asc');

        $this->renderWithLayout('branches.index', [
            'pageTitle'    => 'Branches',
            'currentPage'  => 'branches',
            'branches'     => $result['data'],
            'schools'      => $schools,
            'schoolFilter' => $schoolFilter,
            'pagination'   => [
                'page'       => $result['page'],
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => (($result['page'] - 1) * $perPage) + 1,
                'to'         => min($result['page'] * $perPage, $result['total']),
            ],
            'search' => $search,
        ]);
    }

    /**
     * Show the create branch form.
     * GET /branches/create
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $schools = $this->db->select('schools', [], 'name.asc');

        $result = $this->paginate('branches', 1, 15, [], 'name.asc');

        $this->renderWithLayout('branches.index', [
            'pageTitle'    => 'Add Branch',
            'currentPage'  => 'branches',
            'branches'     => $result['data'],
            'schools'      => $schools,
            'schoolFilter' => '',
            'pagination'   => ['totalPages' => 0, 'total' => 0],
            'search'       => '',
        ]);
    }

    /**
     * Save a new branch.
     * POST /branches
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $validation = $this->validate([
            'school_id' => 'required',
            'name'      => 'required|min:2|max:255',
            'code'      => 'required|min:2|max:50',
            'email'     => 'email',
            'phone'     => 'max:20',
            'address'   => 'max:500',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/branches');
            return;
        }

        // School-level users can only create branches in their school
        $user = $this->currentUser();
        $schoolId = $this->input('school_id');
        if ($user && isset($user['school_id']) && $user['school_id'] !== null) {
            $schoolId = $user['school_id'];
        }

        $data = [
            'school_id' => $schoolId,
            'name'      => $this->input('name'),
            'code'      => strtoupper($this->input('code')),
            'email'     => $this->input('email'),
            'phone'     => $this->input('phone'),
            'address'   => $this->input('address'),
            'status'    => $this->input('status', 'active'),
        ];

        try {
            $this->db->insert('branches', $data);
            success_msg('Branch created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create branch: ' . $e->getMessage());
        }

        $this->redirect('/branches');
    }

    /**
     * Show the edit branch form.
     * GET /branches/{id}/edit
     */
    public function edit(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $branch = $this->db->find('branches', $id);

        if (!$branch) {
            error_msg('Branch not found.');
            $this->redirect('/branches');
            return;
        }

        // School-level users can only edit their own school's branches
        $user = $this->currentUser();
        if ($user && isset($user['school_id']) && $user['school_id'] !== null) {
            if ($branch['schoolId'] !== $user['school_id']) {
                error_msg('You do not have permission to edit this branch.');
                $this->redirect('/branches');
                return;
            }
        }

        $result = $this->paginate('branches', 1, 15, [], 'name.asc');
        $schools = $this->db->select('schools', [], 'name.asc');

        $this->renderWithLayout('branches.index', [
            'pageTitle'    => 'Edit Branch',
            'currentPage'  => 'branches',
            'branches'     => $result['data'],
            'branch'       => $branch,
            'schools'      => $schools,
            'schoolFilter' => '',
            'pagination'   => [
                'page'       => 1,
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => 1,
                'to'         => min(15, $result['total']),
            ],
            'search' => '',
        ]);
    }

    /**
     * Update a branch.
     * POST /branches/{id}
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $branch = $this->db->find('branches', $id);

        if (!$branch) {
            error_msg('Branch not found.');
            $this->redirect('/branches');
            return;
        }

        // School-level users can only update their own school's branches
        $user = $this->currentUser();
        if ($user && isset($user['school_id']) && $user['school_id'] !== null) {
            if ($branch['schoolId'] !== $user['school_id']) {
                error_msg('You do not have permission to update this branch.');
                $this->redirect('/branches');
                return;
            }
        }

        $validation = $this->validate([
            'school_id' => 'required',
            'name'      => 'required|min:2|max:255',
            'code'      => 'required|min:2|max:50',
            'email'     => 'email',
            'phone'     => 'max:20',
            'address'   => 'max:500',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/branches');
            return;
        }

        $schoolId = $this->input('school_id');
        if ($user && isset($user['school_id']) && $user['school_id'] !== null) {
            $schoolId = $user['school_id'];
        }

        $data = [
            'school_id' => $schoolId,
            'name'      => $this->input('name'),
            'code'      => strtoupper($this->input('code')),
            'email'     => $this->input('email'),
            'phone'     => $this->input('phone'),
            'address'   => $this->input('address'),
            'status'    => $this->input('status', 'active'),
        ];

        try {
            $this->db->updateById('branches', $id, $data);
            success_msg('Branch updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update branch: ' . $e->getMessage());
        }

        $this->redirect('/branches');
    }

    /**
     * Delete a branch.
     * POST /branches/{id}/delete
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin']);

        $branch = $this->db->find('branches', $id);

        if (!$branch) {
            error_msg('Branch not found.');
            $this->redirect('/branches');
            return;
        }

        try {
            $this->db->deleteById('branches', $id);
            success_msg('Branch deleted successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to delete branch: ' . $e->getMessage());
        }

        $this->redirect('/branches');
    }

    /**
     * JSON list of branches with filters.
     */
    public function apiIndex(): void
    {
        $this->requireAuth();

        $search = $this->input('search', '');
        $schoolId = $this->input('school_id', '');
        $status = $this->input('status', '');
        $page = (int) ($this->input('page', 1) ?? 1);
        $perPage = (int) ($this->input('per_page', 15) ?? 15);

        $filters = [];

        $user = $this->currentUser();
        if ($user && isset($user['school_id']) && $user['school_id'] !== null) {
            $filters['school_id'] = ['eq' => $user['school_id']];
        } elseif (!empty($schoolId)) {
            $filters['school_id'] = ['eq' => $schoolId];
        }

        if (!empty($search)) {
            $filters['name'] = ['ilike' => '%' . $search . '%'];
        }
        if (!empty($status)) {
            $filters['status'] = ['eq' => $status];
        }

        $result = $this->paginate('branches', $page, $perPage, $filters, 'name.asc');

        $this->success($result);
    }

    /**
     * JSON create branch.
     */
    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $validation = $this->validate([
            'school_id' => 'required',
            'name'      => 'required|min:2|max:255',
            'code'      => 'required|min:2|max:50',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        $data = [
            'school_id' => $this->input('school_id'),
            'name'      => $this->input('name'),
            'code'      => strtoupper($this->input('code')),
            'email'     => $this->input('email'),
            'phone'     => $this->input('phone'),
            'address'   => $this->input('address'),
            'status'    => $this->input('status', 'active'),
        ];

        try {
            $branch = $this->db->insert('branches', $data);
            $this->success($branch, 'Branch created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create branch: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON update branch.
     */
    public function apiUpdate(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $branch = $this->db->find('branches', $id);
        if (!$branch) {
            $this->error('Branch not found.', 404);
            return;
        }

        $data = array_filter([
            'school_id' => $this->input('school_id'),
            'name'      => $this->input('name'),
            'code'      => $this->input('code'),
            'email'     => $this->input('email'),
            'phone'     => $this->input('phone'),
            'address'   => $this->input('address'),
            'status'    => $this->input('status'),
        ], fn($v) => $v !== null);

        try {
            $updated = $this->db->updateById('branches', $id, $data);
            $this->success($updated, 'Branch updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update branch: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON delete branch.
     */
    public function apiDelete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin']);

        $branch = $this->db->find('branches', $id);
        if (!$branch) {
            $this->error('Branch not found.', 404);
            return;
        }

        try {
            $this->db->deleteById('branches', $id);
            $this->success(null, 'Branch deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete branch: ' . $e->getMessage(), 500);
        }
    }
}
