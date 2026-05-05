<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * SubjectController
 *
 * Manages school subjects: listing, creating, editing, and deleting.
 * Supports filtering by type (core/elective) and status.
 */
class SubjectController extends Controller
{
    /**
     * List all subjects with optional filters.
     */
    public function index(): void
    {
        $this->requireAuth();

        $filters = [];
        $search = $this->input('search', '');
        $type = $this->input('type', '');
        $status = $this->input('status', '');

        if ($search !== '') {
            $filters['name'] = ['ilike' => "%{$search}%"];
        }
        if ($type !== '') {
            $filters['type'] = ['eq' => $type];
        }
        if ($status !== '') {
            $filters['status'] = ['eq' => $status];
        }

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 20;

        $result = $this->paginate('subjects', $page, $perPage, $filters, 'name.asc');

        $this->view('subjects.index', [
            'pageTitle' => 'Subjects',
            'subjects'  => $result['data'],
            'total'     => $result['total'],
            'page'      => $result['page'],
            'perPage'   => $result['perPage'],
            'lastPage'  => $result['lastPage'],
            'search'    => $search,
            'type'      => $type,
            'status'    => $status,
        ]);
    }

    /**
     * Show the create subject form.
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        $this->view('subjects.form', [
            'subject' => null,
            'mode'    => 'create',
        ]);
    }

    /**
     * Store a new subject.
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        $validation = $this->validate([
            'name'         => 'required|min:2|max:100',
            'code'         => 'required|min:2|max:20',
            'type'         => 'required',
            'credit_hours' => 'required|numeric',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed.', 422, $validation['errors']);
        }

        $data = [
            'schoolId'    => $this->input('school_id', ''),
            'branchId'    => $this->input('branch_id', ''),
            'name'        => $this->input('name'),
            'code'        => strtoupper($this->input('code')),
            'description' => $this->input('description', ''),
            'type'        => $this->input('type'),
            'creditHours' => (int) $this->input('credit_hours', 0),
            'status'      => $this->input('status', 'active'),
        ];

        try {
            $this->db->insert('subjects', $data);
            $this->success(null, 'Subject created successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to create subject: ' . $e->getMessage());
        }
    }

    /**
     * Show the edit subject form.
     */
    public function edit(string $id): void
    {
        $this->requireAuth();

        $subject = $this->db->find('subjects', $id);
        if (!$subject) {
            $this->error('Subject not found.', 404);
        }

        $this->view('subjects.form', [
            'subject' => $subject,
            'mode'    => 'edit',
        ]);
    }

    /**
     * Update an existing subject.
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        $subject = $this->db->find('subjects', $id);
        if (!$subject) {
            $this->error('Subject not found.', 404);
        }

        $validation = $this->validate([
            'name'         => 'required|min:2|max:100',
            'code'         => 'required|min:2|max:20',
            'type'         => 'required',
            'credit_hours' => 'required|numeric',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed.', 422, $validation['errors']);
        }

        $data = [
            'name'        => $this->input('name'),
            'code'        => strtoupper($this->input('code')),
            'description' => $this->input('description', ''),
            'type'        => $this->input('type'),
            'creditHours' => (int) $this->input('credit_hours', 0),
            'status'      => $this->input('status', 'active'),
        ];

        try {
            $this->db->updateById('subjects', $id, $data);
            $this->success(null, 'Subject updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update subject: ' . $e->getMessage());
        }
    }

    /**
     * Delete a subject.
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        $subject = $this->db->find('subjects', $id);
        if (!$subject) {
            $this->error('Subject not found.', 404);
        }

        try {
            $this->db->deleteById('subjects', $id);
            $this->success(null, 'Subject deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete subject: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: List all subjects as JSON.
     */
    public function apiIndex(): void
    {
        $this->requireAuth();

        $filters = [];
        $type = $this->input('type', '');
        $status = $this->input('status', '');

        if ($type !== '') {
            $filters['type'] = ['eq' => $type];
        }
        if ($status !== '') {
            $filters['status'] = ['eq' => $status];
        }

        $subjects = $this->db->select('subjects', $filters, 'name.asc');
        $this->success($subjects);
    }

    /**
     * API: Get a single subject by ID.
     */
    public function apiShow(string $id): void
    {
        $this->requireAuth();

        $subject = $this->db->find('subjects', $id);
        if (!$subject) {
            $this->error('Subject not found.', 404);
            return;
        }

        $this->success($subject);
    }

    /**
     * API: Store a new subject.
     */
    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        $data = [
            'schoolId'    => $this->input('school_id', ''),
            'branchId'    => $this->input('branch_id', ''),
            'name'        => $this->input('name'),
            'code'        => strtoupper($this->input('code')),
            'description' => $this->input('description', ''),
            'type'        => $this->input('type'),
            'creditHours' => (int) $this->input('credit_hours', 0),
            'status'      => $this->input('status', 'active'),
        ];

        if (empty($data['name']) || empty($data['code'])) {
            $this->error('Name and code are required.', 422);
            return;
        }

        try {
            $result = $this->db->insert('subjects', $data);
            $this->success($result, 'Subject created successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to create subject: ' . $e->getMessage());
        }
    }

    /**
     * API: Update a subject.
     */
    public function apiUpdate(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        $data = [];
        $fields = ['name', 'code', 'description', 'type', 'creditHours', 'status'];
        foreach ($fields as $field) {
            $value = $this->input($field);
            if ($value !== null) {
                $data[$field] = ($field === 'code') ? strtoupper($value) : (($field === 'creditHours') ? (int) $value : $value);
            }
        }

        if (empty($data)) {
            $this->error('No fields to update.', 422);
            return;
        }

        try {
            $result = $this->db->updateById('subjects', $id, $data);
            $this->success($result, 'Subject updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update subject: ' . $e->getMessage());
        }
    }

    /**
     * API: Delete a subject.
     */
    public function apiDelete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        try {
            $this->db->deleteById('subjects', $id);
            $this->success(null, 'Subject deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete subject: ' . $e->getMessage());
        }
    }
}
