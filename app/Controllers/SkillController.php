<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * SkillController
 *
 * Manages CBC (Competency-Based Curriculum) skills including
 * strands and sub-strands for tracking student competencies.
 */
class SkillController extends Controller
{
    /**
     * List all skills with optional filters.
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.view');

        $filters = [];
        $search = $this->input('search', '');
        $category = $this->input('category', '');
        $status = $this->input('status', '');

        if ($search !== '') {
            $filters['name'] = ['ilike' => "%{$search}%"];
        }
        if ($category !== '') {
            $filters['category'] = ['eq' => $category];
        }
        if ($status !== '') {
            $filters['status'] = ['eq' => $status];
        }

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 25;

        $result = $this->paginate('skills', $page, $perPage, $filters, 'name.asc');

        $this->renderWithLayout('skills.index', [
            'pageTitle'    => 'Skills',
            'currentPage' => 'skills',
            'skills'    => $result['data'],
            'total'     => $result['total'],
            'page'      => $result['page'],
            'perPage'   => $result['perPage'],
            'lastPage'  => $result['lastPage'],
            'search'    => $search,
            'category'  => $category,
            'status'    => $status,
        ]);
    }

    /**
     * Show the create skill form.
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $this->view('skills.form', [
            'skill' => null,
            'mode'  => 'create',
        ]);
    }

    /**
     * Store a new skill.
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $validation = $this->validate([
            'name'     => 'required|min:2|max:150',
            'code'     => 'required|min:2|max:20',
            'category' => 'required',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed.', 422, $validation['errors']);
        }

        $data = [
            'schoolId'    => $this->input('school_id', ''),
            'name'        => $this->input('name'),
            'code'        => strtoupper($this->input('code')),
            'category'    => $this->input('category'),
            'description' => $this->input('description', ''),
            'strand'      => $this->input('strand', ''),
            'subStrand'   => $this->input('sub_strand', ''),
            'status'      => $this->input('status', 'active'),
        ];

        try {
            $this->db->insert('skills', $data);
            $this->success(null, 'Skill created successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to create skill: ' . $e->getMessage());
        }
    }

    /**
     * Show the edit skill form.
     */
    public function edit(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.edit');

        $skill = $this->db->find('skills', $id);
        if (!$skill) {
            $this->error('Skill not found.', 404);
        }

        $this->view('skills.form', [
            'skill' => $skill,
            'mode'  => 'edit',
        ]);
    }

    /**
     * Update an existing skill.
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $skill = $this->db->find('skills', $id);
        if (!$skill) {
            $this->error('Skill not found.', 404);
        }

        $validation = $this->validate([
            'name'     => 'required|min:2|max:150',
            'code'     => 'required|min:2|max:20',
            'category' => 'required',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed.', 422, $validation['errors']);
        }

        $data = [
            'name'        => $this->input('name'),
            'code'        => strtoupper($this->input('code')),
            'category'    => $this->input('category'),
            'description' => $this->input('description', ''),
            'strand'      => $this->input('strand', ''),
            'subStrand'   => $this->input('sub_strand', ''),
            'status'      => $this->input('status', 'active'),
        ];

        try {
            $this->db->updateById('skills', $id, $data);
            $this->success(null, 'Skill updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update skill: ' . $e->getMessage());
        }
    }

    /**
     * Delete a skill.
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.delete');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $skill = $this->db->find('skills', $id);
        if (!$skill) {
            $this->error('Skill not found.', 404);
        }

        try {
            $this->db->deleteById('skills', $id);
            $this->success(null, 'Skill deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete skill: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: List all skills as JSON.
     */
    public function apiIndex(): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.view');

        $filters = [];
        $category = $this->input('category', '');
        $status = $this->input('status', '');

        if ($category !== '') $filters['category'] = ['eq' => $category];
        if ($status !== '') $filters['status'] = ['eq' => $status];

        $skills = $this->db->select('skills', $filters, 'name.asc');
        $this->success($skills);
    }

    /**
     * API: Get a single skill by ID.
     */
    public function apiShow(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.view');

        $skill = $this->db->find('skills', $id);
        if (!$skill) {
            $this->error('Skill not found.', 404);
            return;
        }

        $this->success($skill);
    }

    /**
     * API: Store a new skill.
     */
    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $data = [
            'schoolId'    => $this->input('school_id', ''),
            'name'        => $this->input('name'),
            'code'        => strtoupper($this->input('code')),
            'category'    => $this->input('category'),
            'description' => $this->input('description', ''),
            'strand'      => $this->input('strand', ''),
            'subStrand'   => $this->input('sub_strand', ''),
            'status'      => $this->input('status', 'active'),
        ];

        if (empty($data['name']) || empty($data['code']) || empty($data['category'])) {
            $this->error('Name, code, and category are required.', 422);
            return;
        }

        try {
            $result = $this->db->insert('skills', $data);
            $this->success($result, 'Skill created successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to create skill: ' . $e->getMessage());
        }
    }

    /**
     * API: Update a skill.
     */
    public function apiUpdate(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $data = [];
        $fields = ['name', 'code', 'category', 'description', 'strand', 'subStrand', 'status'];
        foreach ($fields as $field) {
            $value = $this->input($field);
            if ($value !== null) {
                $data[$field] = ($field === 'code') ? strtoupper($value) : $value;
            }
        }

        if (empty($data)) {
            $this->error('No fields to update.', 422);
            return;
        }

        try {
            $result = $this->db->updateById('skills', $id, $data);
            $this->success($result, 'Skill updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update skill: ' . $e->getMessage());
        }
    }

    /**
     * API: Delete a skill.
     */
    public function apiDelete(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.delete');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        try {
            $this->db->deleteById('skills', $id);
            $this->success(null, 'Skill deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete skill: ' . $e->getMessage());
        }
    }
}
