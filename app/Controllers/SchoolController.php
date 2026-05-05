<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * SchoolController
 *
 * Manages schools CRUD operations. Schools are the top-level
 * organizational entities in the ERP system.
 */
class SchoolController extends Controller
{
    /**
     * List all schools with search and pagination.
     * GET /schools
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.view');
        $this->requireRole(['SuperAdmin']);

        $page = (int) ($this->input('page', 1) ?? 1);
        $search = $this->input('search', '');
        $perPage = 15;

        $filters = [];
        if (!empty($search)) {
            $filters['name'] = ['ilike' => '%' . $search . '%'];
        }

        $result = $this->paginate('schools', $page, $perPage, $filters, 'name.asc');

        $this->renderWithLayout('schools.index', [
            'pageTitle'   => 'Schools',
            'currentPage' => 'schools',
            'schools'     => $result['data'],
            'pagination'  => [
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
     * Show the create school form.
     * GET /schools/create
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.create');
        $this->requireRole(['SuperAdmin']);

        $this->renderWithLayout('schools.index', [
            'pageTitle'   => 'Add School',
            'currentPage' => 'schools',
            'schools'     => [],
            'pagination'  => ['totalPages' => 0, 'total' => 0],
            'search'      => '',
        ]);
    }

    /**
     * Save a new school.
     * POST /schools
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.create');
        $this->requireRole(['SuperAdmin']);

        $validation = $this->validate([
            'name'    => 'required|min:2|max:255',
            'code'    => 'required|min:2|max:50|unique:schools,code',
            'email'   => 'email',
            'phone'   => 'max:20',
            'address' => 'max:500',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/schools');
            return;
        }

        $data = [
            'name'    => $this->input('name'),
            'code'    => strtoupper($this->input('code')),
            'email'   => $this->input('email'),
            'phone'   => $this->input('phone'),
            'address' => $this->input('address'),
            'logo'    => $this->input('logo'),
            'status'  => $this->input('status', 'active'),
        ];

        try {
            $this->db->insert('schools', $data);
            success_msg('School created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create school: ' . $e->getMessage());
        }

        $this->redirect('/schools');
    }

    /**
     * Show the edit school form.
     * GET /schools/{id}/edit
     */
    public function edit(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.edit');
        $this->requireRole(['SuperAdmin']);

        $school = $this->db->find('schools', $id);

        if (!$school) {
            error_msg('School not found.');
            $this->redirect('/schools');
            return;
        }

        $result = $this->paginate('schools', 1, 15, [], 'name.asc');

        $this->renderWithLayout('schools.index', [
            'pageTitle'   => 'Edit School',
            'currentPage' => 'schools',
            'schools'     => $result['data'],
            'school'      => $school,
            'pagination'  => [
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
     * Update a school.
     * POST /schools/{id}
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.edit');
        $this->requireRole(['SuperAdmin']);

        $school = $this->db->find('schools', $id);

        if (!$school) {
            error_msg('School not found.');
            $this->redirect('/schools');
            return;
        }

        $validation = $this->validate([
            'name'    => 'required|min:2|max:255',
            'code'    => 'required|min:2|max:50',
            'email'   => 'email',
            'phone'   => 'max:20',
            'address' => 'max:500',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/schools');
            return;
        }

        $code = $this->input('code');
        if ($code !== $school['code']) {
            $existing = $this->db->single('schools', ['code' => ['eq' => $code]]);
            if ($existing) {
                error_msg('School code already exists.');
                $this->redirect('/schools');
                return;
            }
        }

        $data = [
            'name'    => $this->input('name'),
            'code'    => strtoupper($code),
            'email'   => $this->input('email'),
            'phone'   => $this->input('phone'),
            'address' => $this->input('address'),
            'logo'    => $this->input('logo'),
            'status'  => $this->input('status', 'active'),
        ];

        try {
            $this->db->updateById('schools', $id, $data);
            success_msg('School updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update school: ' . $e->getMessage());
        }

        $this->redirect('/schools');
    }

    /**
     * Delete a school.
     * POST /schools/{id}/delete
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.delete');
        $this->requireRole(['SuperAdmin']);

        $school = $this->db->find('schools', $id);

        if (!$school) {
            error_msg('School not found.');
            $this->redirect('/schools');
            return;
        }

        try {
            $this->db->deleteById('schools', $id);
            success_msg('School deleted successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to delete school: ' . $e->getMessage());
        }

        $this->redirect('/schools');
    }

    /**
     * JSON list of schools with filters.
     * GET /api/schools
     */
    public function apiIndex(): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.view');

        $search = $this->input('search', '');
        $status = $this->input('status', '');
        $page = (int) ($this->input('page', 1) ?? 1);
        $perPage = (int) ($this->input('per_page', 15) ?? 15);

        $filters = [];
        if (!empty($search)) {
            $filters['name'] = ['ilike' => '%' . $search . '%'];
        }
        if (!empty($status)) {
            $filters['status'] = ['eq' => $status];
        }

        $result = $this->paginate('schools', $page, $perPage, $filters, 'name.asc');

        $this->success($result);
    }

    /**
     * JSON create school.
     * POST /api/schools
     */
    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.create');
        $this->requireRole(['SuperAdmin']);

        $validation = $this->validate([
            'name'    => 'required|min:2|max:255',
            'code'    => 'required|min:2|max:50',
            'email'   => 'email',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        $data = [
            'name'    => $this->input('name'),
            'code'    => strtoupper($this->input('code')),
            'email'   => $this->input('email'),
            'phone'   => $this->input('phone'),
            'address' => $this->input('address'),
            'logo'    => $this->input('logo'),
            'status'  => $this->input('status', 'active'),
        ];

        try {
            $school = $this->db->insert('schools', $data);
            $this->success($school, 'School created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create school: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON update school.
     * PUT /api/schools/{id}
     */
    public function apiUpdate(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.edit');
        $this->requireRole(['SuperAdmin']);

        $school = $this->db->find('schools', $id);
        if (!$school) {
            $this->error('School not found.', 404);
            return;
        }

        $validation = $this->validate([
            'name' => 'required|min:2|max:255',
            'code' => 'required|min:2|max:50',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        $data = array_filter([
            'name'    => $this->input('name'),
            'code'    => strtoupper($this->input('code')),
            'email'   => $this->input('email'),
            'phone'   => $this->input('phone'),
            'address' => $this->input('address'),
            'logo'    => $this->input('logo'),
            'status'  => $this->input('status'),
        ], fn($v) => $v !== null);

        try {
            $updated = $this->db->updateById('schools', $id, $data);
            $this->success($updated, 'School updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update school: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON delete school.
     * DELETE /api/schools/{id}
     */
    public function apiDelete(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.delete');
        $this->requireRole(['SuperAdmin']);

        $school = $this->db->find('schools', $id);
        if (!$school) {
            $this->error('School not found.', 404);
            return;
        }

        try {
            $this->db->deleteById('schools', $id);
            $this->success(null, 'School deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete school: ' . $e->getMessage(), 500);
        }
    }
}
