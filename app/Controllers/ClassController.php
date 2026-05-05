<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * ClassController
 *
 * Manages school classes: listing, creating, editing, and deleting.
 * Supports filtering by grade level, academic year, and search.
 */
class ClassController extends Controller
{
    /**
     * List all classes with optional filters.
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.view');

        $filters = [];
        $search = $this->input('search', '');
        $gradeLevel = $this->input('grade_level', '');
        $academicYear = $this->input('academic_year', '');
        $status = $this->input('status', '');

        if ($search !== '') {
            $filters['name'] = ['ilike' => "%{$search}%"];
        }
        if ($gradeLevel !== '') {
            $filters['gradeLevel'] = ['eq' => $gradeLevel];
        }
        if ($academicYear !== '') {
            $filters['academicYear'] = ['eq' => $academicYear];
        }
        if ($status !== '') {
            $filters['status'] = ['eq' => $status];
        }

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 20;

        $result = $this->paginate('classes', $page, $perPage, $filters, 'name.asc');

        // Enrich with class teacher names
        $classes = $result['data'];
        foreach ($classes as &$class) {
            $teacherId = $class['classTeacherId'] ?? null;
            if ($teacherId) {
                $teacher = $this->db->single('users', ['id' => ['eq' => $teacherId]]);
                $class['classTeacherName'] = $teacher
                    ? trim(($teacher['firstName'] ?? '') . ' ' . ($teacher['lastName'] ?? ''))
                    : 'Unassigned';
            } else {
                $class['classTeacherName'] = 'Unassigned';
            }
        }
        unset($class);

        $this->renderWithLayout('classes.index', [
            'pageTitle'     => 'Classes',
            'currentPage'  => 'classes',
            'classes'       => $classes,
            'total'         => $result['total'],
            'page'          => $result['page'],
            'perPage'       => $result['perPage'],
            'lastPage'      => $result['lastPage'],
            'search'        => $search,
            'gradeLevel'    => $gradeLevel,
            'academicYear'  => $academicYear,
            'status'        => $status,
        ]);
    }

    /**
     * Show the create class form (returns form partial via modal).
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.create');

        $teachers = $this->db->select('users', ['role' => ['eq' => 'Teacher']], 'firstName.asc');

        $this->view('classes.form', [
            'class'    => null,
            'teachers' => $teachers,
            'mode'     => 'create',
        ]);
    }

    /**
     * Store a new class.
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $validation = $this->validate([
            'name'         => 'required|min:2|max:100',
            'grade_level'  => 'required',
            'section'      => 'required|min:1|max:10',
            'capacity'     => 'required|numeric',
            'academic_year'=> 'required',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed.', 422, $validation['errors']);
        }

        $data = [
            'schoolId'       => $this->input('school_id', ''),
            'branchId'       => $this->input('branch_id', ''),
            'name'           => $this->input('name'),
            'gradeLevel'     => $this->input('grade_level'),
            'section'        => $this->input('section'),
            'capacity'       => (int) $this->input('capacity', 0),
            'classTeacherId' => $this->input('class_teacher_id') ?: null,
            'academicYear'   => $this->input('academic_year'),
            'status'         => $this->input('status', 'active'),
        ];

        try {
            $this->db->insert('classes', $data);
            $this->success(null, 'Class created successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to create class: ' . $e->getMessage());
        }
    }

    /**
     * Show the edit class form.
     */
    public function edit(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.edit');

        $class = $this->db->find('classes', $id);
        if (!$class) {
            $this->error('Class not found.', 404);
        }

        $teachers = $this->db->select('users', ['role' => ['eq' => 'Teacher']], 'firstName.asc');

        $this->view('classes.form', [
            'class'    => $class,
            'teachers' => $teachers,
            'mode'     => 'edit',
        ]);
    }

    /**
     * Update an existing class.
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $class = $this->db->find('classes', $id);
        if (!$class) {
            $this->error('Class not found.', 404);
        }

        $validation = $this->validate([
            'name'         => 'required|min:2|max:100',
            'grade_level'  => 'required',
            'section'      => 'required|min:1|max:10',
            'capacity'     => 'required|numeric',
            'academic_year'=> 'required',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed.', 422, $validation['errors']);
        }

        $data = [
            'name'           => $this->input('name'),
            'gradeLevel'     => $this->input('grade_level'),
            'section'        => $this->input('section'),
            'capacity'       => (int) $this->input('capacity', 0),
            'classTeacherId' => $this->input('class_teacher_id') ?: null,
            'academicYear'   => $this->input('academic_year'),
            'status'         => $this->input('status', 'active'),
        ];

        try {
            $this->db->updateById('classes', $id, $data);
            $this->success(null, 'Class updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update class: ' . $e->getMessage());
        }
    }

    /**
     * Delete a class.
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.delete');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $class = $this->db->find('classes', $id);
        if (!$class) {
            $this->error('Class not found.', 404);
        }

        try {
            $this->db->deleteById('classes', $id);
            $this->success(null, 'Class deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete class: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: List all classes as JSON.
     */
    public function apiIndex(): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.view');

        $filters = [];
        $gradeLevel = $this->input('grade_level', '');
        $academicYear = $this->input('academic_year', '');
        $status = $this->input('status', '');

        if ($gradeLevel !== '') {
            $filters['gradeLevel'] = ['eq' => $gradeLevel];
        }
        if ($academicYear !== '') {
            $filters['academicYear'] = ['eq' => $academicYear];
        }
        if ($status !== '') {
            $filters['status'] = ['eq' => $status];
        }

        $classes = $this->db->select('classes', $filters, 'name.asc');

        $this->success($classes, 'Classes retrieved successfully.');
    }

    /**
     * API: Get a single class by ID.
     */
    public function apiShow(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.view');

        $class = $this->db->find('classes', $id);
        if (!$class) {
            $this->error('Class not found.', 404);
            return;
        }

        $this->success($class);
    }

    /**
     * API: Store a new class (JSON).
     */
    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $data = [
            'schoolId'       => $this->input('school_id', ''),
            'branchId'       => $this->input('branch_id', ''),
            'name'           => $this->input('name'),
            'gradeLevel'     => $this->input('grade_level'),
            'section'        => $this->input('section'),
            'capacity'       => (int) $this->input('capacity', 0),
            'classTeacherId' => $this->input('class_teacher_id') ?: null,
            'academicYear'   => $this->input('academic_year'),
            'status'         => $this->input('status', 'active'),
        ];

        if (empty($data['name']) || empty($data['gradeLevel'])) {
            $this->error('Name and grade level are required.', 422);
            return;
        }

        try {
            $result = $this->db->insert('classes', $data);
            $this->success($result, 'Class created successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to create class: ' . $e->getMessage());
        }
    }

    /**
     * API: Update a class (JSON).
     */
    public function apiUpdate(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $data = [];
        $fields = ['name', 'gradeLevel', 'section', 'capacity', 'classTeacherId', 'academicYear', 'status'];
        foreach ($fields as $field) {
            $value = $this->input($field);
            if ($value !== null) {
                $data[$field] = $field === 'capacity' ? (int) $value : $value;
            }
        }

        if (empty($data)) {
            $this->error('No fields to update.', 422);
            return;
        }

        try {
            $result = $this->db->updateById('classes', $id, $data);
            $this->success($result, 'Class updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update class: ' . $e->getMessage());
        }
    }

    /**
     * API: Delete a class (JSON).
     */
    public function apiDelete(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('academic.delete');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        try {
            $this->db->deleteById('classes', $id);
            $this->success(null, 'Class deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete class: ' . $e->getMessage());
        }
    }
}
