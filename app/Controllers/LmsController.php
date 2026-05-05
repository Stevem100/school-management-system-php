<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * LmsController
 *
 * Manages Learning Management System (LMS) operations including
 * courses, assignments, and student submissions.
 */
class LmsController extends Controller
{
    /**
     * Display LMS overview page.
     * GET /lms
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher', 'Student']);

        $filters = [];
        $search = $this->input('search', '');
        $status = $this->input('status', '');

        if ($search !== '') {
            $filters['title'] = ['ilike' => "%{$search}%"];
        }
        if ($status !== '') {
            $filters['status'] = ['eq' => $status];
        }

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 12;

        $result = $this->paginate('lms_courses', $page, $perPage, $filters, 'created_at.desc');

        $this->renderWithLayout('lms.index', [
            'pageTitle'   => 'Learning Management',
            'currentPage' => 'lms',
            'courses'     => $result['data'],
            'pagination'  => [
                'page'       => $result['page'],
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => (($result['page'] - 1) * $perPage) + 1,
                'to'         => min($result['page'] * $perPage, $result['total']),
            ],
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Display courses list (alias for index).
     * GET /lms/courses
     */
    public function courses(): void
    {
        $this->index();
    }

    /**
     * Show the create course form.
     * GET /lms/courses/create
     */
    public function createCourse(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher']);

        $result = $this->paginate('lms_courses', 1, 12, [], 'created_at.desc');

        $this->renderWithLayout('lms.index', [
            'pageTitle'   => 'Add Course',
            'currentPage' => 'lms',
            'courses'     => $result['data'],
            'pagination'  => [
                'page'       => 1,
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => 1,
                'to'         => min(12, $result['total']),
            ],
            'search' => '',
        ]);
    }

    /**
     * Save a new course.
     * POST /lms/courses
     */
    public function storeCourse(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher']);

        $validation = $this->validate([
            'title'       => 'required|min:2|max:255',
            'description' => 'max:2000',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/lms');
            return;
        }

        $data = [
            'school_id'  => $this->input('school_id', ''),
            'title'      => $this->input('title'),
            'description' => $this->input('description', ''),
            'subject_id' => $this->input('subject_id', ''),
            'teacher_id' => $this->input('teacher_id', ''),
            'status'     => $this->input('status', 'draft'),
        ];

        try {
            $this->db->insert('lms_courses', $data);
            success_msg('Course created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create course: ' . $e->getMessage());
        }

        $this->redirect('/lms');
    }

    /**
     * Show a single course.
     * GET /lms/courses/{id}
     */
    public function showCourse(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.view');

        $course = $this->db->find('lms_courses', $id);
        if (!$course) {
            error_msg('Course not found.');
            $this->redirect('/lms');
            return;
        }

        $this->renderWithLayout('lms.index', [
            'pageTitle'   => 'Course Details',
            'currentPage' => 'lms',
            'courses'     => [$course],
            'pagination'  => ['totalPages' => 1, 'total' => 1, 'page' => 1, 'from' => 1, 'to' => 1],
            'search'      => '',
        ]);
    }

    /**
     * Show the edit course form.
     * GET /lms/courses/{id}/edit
     */
    public function editCourse(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher']);

        $course = $this->db->find('lms_courses', $id);
        if (!$course) {
            error_msg('Course not found.');
            $this->redirect('/lms');
            return;
        }

        $result = $this->paginate('lms_courses', 1, 12, [], 'created_at.desc');

        $this->renderWithLayout('lms.index', [
            'pageTitle'   => 'Edit Course',
            'currentPage' => 'lms',
            'courses'     => $result['data'],
            'course'      => $course,
            'pagination'  => [
                'page'       => 1,
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => 1,
                'to'         => min(12, $result['total']),
            ],
            'search' => '',
        ]);
    }

    /**
     * Update a course.
     * POST /lms/courses/{id}
     */
    public function updateCourse(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher']);

        $course = $this->db->find('lms_courses', $id);
        if (!$course) {
            error_msg('Course not found.');
            $this->redirect('/lms');
            return;
        }

        $validation = $this->validate([
            'title'       => 'required|min:2|max:255',
            'description' => 'max:2000',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/lms');
            return;
        }

        $data = [
            'title'       => $this->input('title'),
            'description' => $this->input('description', ''),
            'subject_id'  => $this->input('subject_id', ''),
            'teacher_id'  => $this->input('teacher_id', ''),
            'status'      => $this->input('status', 'draft'),
        ];

        try {
            $this->db->updateById('lms_courses', $id, $data);
            success_msg('Course updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update course: ' . $e->getMessage());
        }

        $this->redirect('/lms');
    }

    /**
     * Display assignments page.
     * GET /lms/assignments
     */
    public function assignments(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.view');

        $this->renderWithLayout('lms.index', [
            'pageTitle'   => 'Assignments',
            'currentPage' => 'lms',
            'courses'     => [],
            'pagination'  => ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0],
            'search'      => '',
        ]);
    }

    /**
     * Show create assignment form.
     * GET /lms/assignments/create
     */
    public function createAssignment(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher']);

        $this->renderWithLayout('lms.index', [
            'pageTitle'   => 'Create Assignment',
            'currentPage' => 'lms',
            'courses'     => [],
            'pagination'  => ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0],
            'search'      => '',
        ]);
    }

    /**
     * Store a new assignment.
     * POST /lms/assignments
     */
    public function storeAssignment(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher']);

        $data = [
            'course_id'   => $this->input('course_id'),
            'title'       => $this->input('title'),
            'description' => $this->input('description', ''),
            'due_date'    => $this->input('due_date'),
            'max_score'   => $this->input('max_score', 100),
            'status'      => $this->input('status', 'active'),
        ];

        try {
            $this->db->insert('lms_assignments', $data);
            success_msg('Assignment created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create assignment: ' . $e->getMessage());
        }

        $this->redirect('/lms/assignments');
    }

    /**
     * Display submissions page.
     * GET /lms/submissions
     */
    public function submissions(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.view');

        $this->renderWithLayout('lms.index', [
            'pageTitle'   => 'Submissions',
            'currentPage' => 'lms',
            'courses'     => [],
            'pagination'  => ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0],
            'search'      => '',
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: List all courses as JSON.
     * GET /api/lms/courses
     */
    public function apiCourses(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.view');

        $search = $this->input('search', '');
        $status = $this->input('status', '');
        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = (int) ($this->input('per_page', 12) ?: 12);

        $filters = [];
        if (!empty($search)) {
            $filters['title'] = ['ilike' => '%' . $search . '%'];
        }
        if (!empty($status)) {
            $filters['status'] = ['eq' => $status];
        }

        $result = $this->paginate('lms_courses', $page, $perPage, $filters, 'created_at.desc');
        $this->success($result);
    }

    /**
     * API: Get a single course by ID.
     * GET /api/lms/courses/{id}
     */
    public function apiShowCourse(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.view');

        $course = $this->db->find('lms_courses', $id);
        if (!$course) {
            $this->error('Course not found.', 404);
            return;
        }

        $this->success($course);
    }

    /**
     * API: Create a new course.
     * POST /api/lms/courses
     */
    public function apiStoreCourse(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher']);

        $validation = $this->validate([
            'title' => 'required|min:2|max:255',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        $data = [
            'school_id'   => $this->input('school_id', ''),
            'title'       => $this->input('title'),
            'description' => $this->input('description', ''),
            'subject_id'  => $this->input('subject_id', ''),
            'teacher_id'  => $this->input('teacher_id', ''),
            'status'      => $this->input('status', 'draft'),
        ];

        try {
            $course = $this->db->insert('lms_courses', $data);
            $this->success($course, 'Course created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create course: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Update a course.
     * PUT /api/lms/courses/{id}
     */
    public function apiUpdateCourse(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher']);

        $course = $this->db->find('lms_courses', $id);
        if (!$course) {
            $this->error('Course not found.', 404);
            return;
        }

        $data = array_filter([
            'title'       => $this->input('title'),
            'description' => $this->input('description'),
            'subject_id'  => $this->input('subject_id'),
            'teacher_id'  => $this->input('teacher_id'),
            'status'      => $this->input('status'),
        ], fn($v) => $v !== null);

        try {
            $updated = $this->db->updateById('lms_courses', $id, $data);
            $this->success($updated, 'Course updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update course: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Delete a course.
     * DELETE /api/lms/courses/{id}
     */
    public function apiDeleteCourse(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.delete');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $course = $this->db->find('lms_courses', $id);
        if (!$course) {
            $this->error('Course not found.', 404);
            return;
        }

        try {
            $this->db->deleteById('lms_courses', $id);
            $this->success(null, 'Course deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete course: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: List all assignments.
     * GET /api/lms/assignments
     */
    public function apiAssignments(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.view');

        $filters = [];
        $courseId = $this->input('course_id', '');
        if (!empty($courseId)) {
            $filters['course_id'] = ['eq' => $courseId];
        }

        $assignments = $this->db->select('lms_assignments', $filters, 'created_at.desc');
        $this->success($assignments);
    }

    /**
     * API: Create a new assignment.
     * POST /api/lms/assignments
     */
    public function apiStoreAssignment(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher']);

        $data = [
            'course_id'   => $this->input('course_id'),
            'title'       => $this->input('title'),
            'description' => $this->input('description', ''),
            'due_date'    => $this->input('due_date'),
            'max_score'   => $this->input('max_score', 100),
            'status'      => $this->input('status', 'active'),
        ];

        try {
            $assignment = $this->db->insert('lms_assignments', $data);
            $this->success($assignment, 'Assignment created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create assignment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Get a single assignment.
     * GET /api/lms/assignments/{id}
     */
    public function apiShowAssignment(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.view');

        $assignment = $this->db->find('lms_assignments', $id);
        if (!$assignment) {
            $this->error('Assignment not found.', 404);
            return;
        }

        $this->success($assignment);
    }

    /**
     * API: Update an assignment.
     * PUT /api/lms/assignments/{id}
     */
    public function apiUpdateAssignment(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher']);

        $data = array_filter([
            'title'       => $this->input('title'),
            'description' => $this->input('description'),
            'due_date'    => $this->input('due_date'),
            'max_score'   => $this->input('max_score'),
            'status'      => $this->input('status'),
        ], fn($v) => $v !== null);

        try {
            $updated = $this->db->updateById('lms_assignments', $id, $data);
            $this->success($updated, 'Assignment updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update assignment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Delete an assignment.
     * DELETE /api/lms/assignments/{id}
     */
    public function apiDeleteAssignment(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.delete');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Teacher']);

        try {
            $this->db->deleteById('lms_assignments', $id);
            $this->success(null, 'Assignment deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete assignment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Submit an assignment.
     * POST /api/lms/assignments/{id}/submit
     */
    public function apiSubmitAssignment(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.manage');

        $data = [
            'assignment_id' => $id,
            'student_id'    => $this->currentUserId(),
            'content'       => $this->input('content', ''),
            'status'        => 'submitted',
        ];

        try {
            $submission = $this->db->insert('lms_submissions', $data);
            $this->success($submission, 'Assignment submitted successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to submit assignment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: List all submissions.
     * GET /api/lms/submissions
     */
    public function apiSubmissions(): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.view');

        $filters = [];
        $assignmentId = $this->input('assignment_id', '');
        if (!empty($assignmentId)) {
            $filters['assignment_id'] = ['eq' => $assignmentId];
        }

        $submissions = $this->db->select('lms_submissions', $filters, 'created_at.desc');
        $this->success($submissions);
    }

    /**
     * API: Get a single submission.
     * GET /api/lms/submissions/{id}
     */
    public function apiShowSubmission(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('lms.view');

        $submission = $this->db->find('lms_submissions', $id);
        if (!$submission) {
            $this->error('Submission not found.', 404);
            return;
        }

        $this->success($submission);
    }
}
