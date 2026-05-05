<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

/**
 * ExamController
 *
 * Manages school exams: listing, creating, editing, and deleting.
 * Supports filtering by type, status, and class.
 */
class ExamController extends Controller
{
    /**
     * List all exams with optional filters.
     */
    public function index(): void
    {
        $this->requireAuth();

        $filters = [];
        $search = $this->input('search', '');
        $type = $this->input('type', '');
        $status = $this->input('status', '');
        $classId = $this->input('class_id', '');

        if ($search !== '') {
            $filters['name'] = ['ilike' => "%{$search}%"];
        }
        if ($type !== '') {
            $filters['type'] = ['eq' => $type];
        }
        if ($status !== '') {
            $filters['status'] = ['eq' => $status];
        }
        if ($classId !== '') {
            $filters['classId'] = ['eq' => $classId];
        }

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 20;

        $result = $this->paginate('exams', $page, $perPage, $filters, 'startDate.asc');

        // Enrich with subject and class names
        $exams = $result['data'];
        foreach ($exams as &$exam) {
            $subjectId = $exam['subjectId'] ?? null;
            if ($subjectId) {
                $subject = $this->db->find('subjects', $subjectId);
                $exam['subjectName'] = $subject['name'] ?? 'N/A';
            } else {
                $exam['subjectName'] = 'N/A';
            }

            $classIdVal = $exam['classId'] ?? null;
            if ($classIdVal) {
                $class = $this->db->find('classes', $classIdVal);
                $exam['className'] = $class['name'] ?? 'N/A';
            } else {
                $exam['className'] = 'N/A';
            }
        }
        unset($exam);

        // Fetch classes for filter dropdown
        $classes = $this->db->select('classes', [], 'name.asc', 100);

        $this->view('exams.index', [
            'pageTitle' => 'Exams',
            'exams'     => $exams,
            'classes'   => $classes,
            'total'     => $result['total'],
            'page'      => $result['page'],
            'perPage'   => $result['perPage'],
            'lastPage'  => $result['lastPage'],
            'search'    => $search,
            'type'      => $type,
            'status'    => $status,
            'classId'   => $classId,
        ]);
    }

    /**
     * Show the create exam form.
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal', 'Teacher']);

        $subjects = $this->db->select('subjects', ['status' => ['eq' => 'active']], 'name.asc');
        $classes = $this->db->select('classes', ['status' => ['eq' => 'active']], 'name.asc');

        $this->view('exams.form', [
            'exam'     => null,
            'subjects' => $subjects,
            'classes'  => $classes,
            'mode'     => 'create',
        ]);
    }

    /**
     * Store a new exam.
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal', 'Teacher']);

        $validation = $this->validate([
            'name'         => 'required|min:2|max:150',
            'type'         => 'required',
            'subject_id'   => 'required',
            'class_id'     => 'required',
            'total_marks'  => 'required|numeric',
            'passing_marks'=> 'required|numeric',
            'start_date'   => 'required',
            'end_date'     => 'required',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed.', 422, $validation['errors']);
        }

        $data = [
            'schoolId'     => $this->input('school_id', ''),
            'branchId'     => $this->input('branch_id', ''),
            'name'         => $this->input('name'),
            'type'         => $this->input('type'),
            'subjectId'    => $this->input('subject_id'),
            'classId'      => $this->input('class_id'),
            'totalMarks'   => (float) $this->input('total_marks', 0),
            'passingMarks' => (float) $this->input('passing_marks', 0),
            'startDate'    => $this->input('start_date'),
            'endDate'      => $this->input('end_date'),
            'status'       => $this->input('status', 'draft'),
        ];

        try {
            $this->db->insert('exams', $data);
            $this->success(null, 'Exam created successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to create exam: ' . $e->getMessage());
        }
    }

    /**
     * Show the edit exam form.
     */
    public function edit(string $id): void
    {
        $this->requireAuth();

        $exam = $this->db->find('exams', $id);
        if (!$exam) {
            $this->error('Exam not found.', 404);
        }

        $subjects = $this->db->select('subjects', ['status' => ['eq' => 'active']], 'name.asc');
        $classes = $this->db->select('classes', ['status' => ['eq' => 'active']], 'name.asc');

        $this->view('exams.form', [
            'exam'     => $exam,
            'subjects' => $subjects,
            'classes'  => $classes,
            'mode'     => 'edit',
        ]);
    }

    /**
     * Update an existing exam.
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal', 'Teacher']);

        $exam = $this->db->find('exams', $id);
        if (!$exam) {
            $this->error('Exam not found.', 404);
        }

        $validation = $this->validate([
            'name'         => 'required|min:2|max:150',
            'type'         => 'required',
            'subject_id'   => 'required',
            'class_id'     => 'required',
            'total_marks'  => 'required|numeric',
            'passing_marks'=> 'required|numeric',
            'start_date'   => 'required',
            'end_date'     => 'required',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed.', 422, $validation['errors']);
        }

        $data = [
            'name'         => $this->input('name'),
            'type'         => $this->input('type'),
            'subjectId'    => $this->input('subject_id'),
            'classId'      => $this->input('class_id'),
            'totalMarks'   => (float) $this->input('total_marks', 0),
            'passingMarks' => (float) $this->input('passing_marks', 0),
            'startDate'    => $this->input('start_date'),
            'endDate'      => $this->input('end_date'),
            'status'       => $this->input('status', 'draft'),
        ];

        try {
            $this->db->updateById('exams', $id, $data);
            $this->success(null, 'Exam updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update exam: ' . $e->getMessage());
        }
    }

    /**
     * Delete an exam.
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        $exam = $this->db->find('exams', $id);
        if (!$exam) {
            $this->error('Exam not found.', 404);
        }

        try {
            $this->db->deleteById('exams', $id);
            $this->success(null, 'Exam deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete exam: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: List all exams as JSON.
     */
    public function apiIndex(): void
    {
        $this->requireAuth();

        $filters = [];
        $type = $this->input('type', '');
        $status = $this->input('status', '');
        $classId = $this->input('class_id', '');

        if ($type !== '') {
            $filters['type'] = ['eq' => $type];
        }
        if ($status !== '') {
            $filters['status'] = ['eq' => $status];
        }
        if ($classId !== '') {
            $filters['classId'] = ['eq' => $classId];
        }

        $exams = $this->db->select('exams', $filters, 'startDate.asc');
        $this->success($exams);
    }

    /**
     * API: Get a single exam by ID.
     */
    public function apiShow(string $id): void
    {
        $this->requireAuth();

        $exam = $this->db->find('exams', $id);
        if (!$exam) {
            $this->error('Exam not found.', 404);
            return;
        }

        $this->success($exam);
    }

    /**
     * API: Store a new exam.
     */
    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal', 'Teacher']);

        $data = [
            'schoolId'     => $this->input('school_id', ''),
            'branchId'     => $this->input('branch_id', ''),
            'name'         => $this->input('name'),
            'type'         => $this->input('type'),
            'subjectId'    => $this->input('subject_id'),
            'classId'      => $this->input('class_id'),
            'totalMarks'   => (float) $this->input('total_marks', 0),
            'passingMarks' => (float) $this->input('passing_marks', 0),
            'startDate'    => $this->input('start_date'),
            'endDate'      => $this->input('end_date'),
            'status'       => $this->input('status', 'draft'),
        ];

        if (empty($data['name']) || empty($data['subjectId'])) {
            $this->error('Name, subject, and class are required.', 422);
            return;
        }

        try {
            $result = $this->db->insert('exams', $data);
            $this->success($result, 'Exam created successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to create exam: ' . $e->getMessage());
        }
    }

    /**
     * API: Update an exam.
     */
    public function apiUpdate(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal', 'Teacher']);

        $data = [];
        $fields = ['name', 'type', 'subjectId', 'classId', 'totalMarks', 'passingMarks', 'startDate', 'endDate', 'status'];
        foreach ($fields as $field) {
            $value = $this->input($field);
            if ($value !== null) {
                $data[$field] = in_array($field, ['totalMarks', 'passingMarks']) ? (float) $value : $value;
            }
        }

        if (empty($data)) {
            $this->error('No fields to update.', 422);
            return;
        }

        try {
            $result = $this->db->updateById('exams', $id, $data);
            $this->success($result, 'Exam updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update exam: ' . $e->getMessage());
        }
    }

    /**
     * API: Delete an exam.
     */
    public function apiDelete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        try {
            $this->db->deleteById('exams', $id);
            $this->success(null, 'Exam deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete exam: ' . $e->getMessage());
        }
    }
}
