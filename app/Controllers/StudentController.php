<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * StudentController
 *
 * Manages student CRUD operations. Students are stored in the users
 * table and linked to student_profiles for academic-specific data.
 */
class StudentController extends Controller
{
    /**
     * List all students with search and pagination.
     * GET /students
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin', 'Dean', 'Teacher', 'Parent']);

        $page = (int) ($this->input('page', 1) ?? 1);
        $search = $this->input('search', '');
        $perPage = 15;
        $offset = ($page - 1) * $perPage;

        $where = "u.user_type = 'student'";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR sp.admission_no LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM users u LEFT JOIN student_profiles sp ON u.id = sp.user_id WHERE {$where}";
        $countResult = $this->db->raw($countSql, $params);
        $total = (int) ($countResult[0]['total'] ?? 0);

        // Fetch paginated data with JOINs
        $sql = "SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.status,
                       sp.admission_no, sp.dob, sp.gender, sp.guardian_name, sp.guardian_phone,
                       sp.branch_id, sp.class_id,
                       b.name as branch_name
                FROM users u
                LEFT JOIN student_profiles sp ON u.id = sp.user_id
                LEFT JOIN branches b ON sp.branch_id = b.id
                WHERE {$where}
                ORDER BY u.first_name ASC, u.last_name ASC
                LIMIT {$perPage} OFFSET {$offset}";

        $students = $this->db->raw($sql, $params);

        $lastPage = max(1, (int) ceil($total / $perPage));

        $this->renderWithLayout('students.index', [
            'pageTitle'   => 'Students',
            'currentPage' => 'students',
            'students'    => $students,
            'pagination'  => [
                'page'       => $page,
                'totalPages' => $lastPage,
                'total'      => $total,
                'from'       => $total > 0 ? (($page - 1) * $perPage) + 1 : 0,
                'to'         => min($page * $perPage, $total),
            ],
            'search' => $search,
        ]);
    }

    /**
     * Show the create student form.
     * GET /students/create
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin', 'Dean']);

        $branches = $this->db->select('branches', [], 'name.asc');
        $classes = $this->db->select('classes', [], 'name.asc');

        $this->renderWithLayout('students.index', [
            'pageTitle'   => 'Add Student',
            'currentPage' => 'students',
            'students'    => [],
            'branches'    => $branches,
            'classes'     => $classes,
            'pagination'  => ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0],
            'search'      => '',
        ]);
    }

    /**
     * Save a new student.
     * POST /students
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin', 'Dean']);

        $validation = $this->validate([
            'first_name'     => 'required|min:2|max:100',
            'last_name'      => 'required|min:2|max:100',
            'email'          => 'required|email|unique:users,email',
            'phone'          => 'max:20',
            'admission_no'   => 'required|min:2|max:50',
            'dob'            => 'date',
            'gender'         => 'in:male,female,other',
            'guardian_name'  => 'max:200',
            'guardian_phone' => 'max:20',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/students');
            return;
        }

        try {
            // Create user record
            $user = $this->db->insert('users', [
                'first_name' => $this->input('first_name'),
                'last_name'  => $this->input('last_name'),
                'email'      => $this->input('email'),
                'phone'      => $this->input('phone'),
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'user_type'  => 'student',
                'status'     => $this->input('status', 'active'),
            ]);

            // Create student profile
            $this->db->insert('student_profiles', [
                'user_id'        => $user['id'],
                'admission_no'   => $this->input('admission_no'),
                'dob'            => $this->input('dob') ?: null,
                'gender'         => $this->input('gender', 'male'),
                'guardian_name'  => $this->input('guardian_name'),
                'guardian_phone' => $this->input('guardian_phone'),
                'branch_id'      => $this->input('branch_id') ?: null,
                'class_id'       => $this->input('class_id') ?: null,
            ]);

            success_msg('Student created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create student: ' . $e->getMessage());
        }

        $this->redirect('/students');
    }

    /**
     * Show the edit student form.
     * GET /students/{id}/edit
     */
    public function edit(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin', 'Dean']);

        $student = $this->db->find('users', $id);

        if (!$student || $student['user_type'] !== 'student') {
            error_msg('Student not found.');
            $this->redirect('/students');
            return;
        }

        $profile = $this->db->single('student_profiles', ['user_id' => ['eq' => $id]]);
        $branches = $this->db->select('branches', [], 'name.asc');
        $classes = $this->db->select('classes', [], 'name.asc');

        // Merge user + profile into one object
        $studentData = array_merge($student, $profile ?? []);

        $result = $this->paginate('users', 1, 15, ['user_type' => ['eq' => 'student']], 'first_name.asc');

        $this->renderWithLayout('students.index', [
            'pageTitle'   => 'Edit Student',
            'currentPage' => 'students',
            'students'    => $result['data'],
            'student'     => $studentData,
            'branches'    => $branches,
            'classes'     => $classes,
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
     * Update a student.
     * POST /students/{id}
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin', 'Dean']);

        $student = $this->db->find('users', $id);

        if (!$student || $student['user_type'] !== 'student') {
            error_msg('Student not found.');
            $this->redirect('/students');
            return;
        }

        $validation = $this->validate([
            'first_name'     => 'required|min:2|max:100',
            'last_name'      => 'required|min:2|max:100',
            'email'          => 'required|email',
            'phone'          => 'max:20',
            'admission_no'   => 'required|min:2|max:50',
            'dob'            => 'date',
            'gender'         => 'in:male,female,other',
            'guardian_name'  => 'max:200',
            'guardian_phone' => 'max:20',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/students');
            return;
        }

        // Check email uniqueness (excluding current user)
        $email = $this->input('email');
        if ($email !== $student['email']) {
            $existing = $this->db->single('users', ['email' => ['eq' => $email]]);
            if ($existing) {
                error_msg('Email address already exists.');
                $this->redirect('/students');
                return;
            }
        }

        try {
            // Update user record
            $this->db->updateById('users', $id, [
                'first_name' => $this->input('first_name'),
                'last_name'  => $this->input('last_name'),
                'email'      => $email,
                'phone'      => $this->input('phone'),
                'status'     => $this->input('status', 'active'),
            ]);

            // Update or create student profile
            $profile = $this->db->single('student_profiles', ['user_id' => ['eq' => $id]]);
            $profileData = [
                'admission_no'   => $this->input('admission_no'),
                'dob'            => $this->input('dob') ?: null,
                'gender'         => $this->input('gender', 'male'),
                'guardian_name'  => $this->input('guardian_name'),
                'guardian_phone' => $this->input('guardian_phone'),
                'branch_id'      => $this->input('branch_id') ?: null,
                'class_id'       => $this->input('class_id') ?: null,
            ];

            if ($profile) {
                $this->db->updateById('student_profiles', $profile['id'], $profileData);
            } else {
                $profileData['user_id'] = $id;
                $this->db->insert('student_profiles', $profileData);
            }

            success_msg('Student updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update student: ' . $e->getMessage());
        }

        $this->redirect('/students');
    }

    /**
     * Delete a student.
     * POST /students/{id}/delete
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin']);

        $student = $this->db->find('users', $id);

        if (!$student || $student['user_type'] !== 'student') {
            error_msg('Student not found.');
            $this->redirect('/students');
            return;
        }

        try {
            // Delete student profile first
            $profile = $this->db->single('student_profiles', ['user_id' => ['eq' => $id]]);
            if ($profile) {
                $this->db->deleteById('student_profiles', $profile['id']);
            }

            // Delete user record
            $this->db->deleteById('users', $id);
            success_msg('Student deleted successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to delete student: ' . $e->getMessage());
        }

        $this->redirect('/students');
    }

    /**
     * JSON list of students with filters.
     * GET /api/students
     */
    public function apiIndex(): void
    {
        $this->requireAuth();

        $search = $this->input('search', '');
        $status = $this->input('status', '');
        $branchId = $this->input('branch_id', '');
        $page = (int) ($this->input('page', 1) ?? 1);
        $perPage = (int) ($this->input('per_page', 15) ?? 15);
        $offset = ($page - 1) * $perPage;

        $where = "u.user_type = 'student'";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR sp.admission_no LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        if (!empty($status)) {
            $where .= " AND u.status = ?";
            $params[] = $status;
        }
        if (!empty($branchId)) {
            $where .= " AND sp.branch_id = ?";
            $params[] = $branchId;
        }

        // Count
        $countResult = $this->db->raw(
            "SELECT COUNT(*) as total FROM users u LEFT JOIN student_profiles sp ON u.id = sp.user_id WHERE {$where}",
            $params
        );
        $total = (int) ($countResult[0]['total'] ?? 0);

        // Fetch
        $sql = "SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.status,
                       sp.admission_no, sp.dob, sp.gender, sp.guardian_name, sp.guardian_phone,
                       sp.branch_id, b.name as branch_name
                FROM users u
                LEFT JOIN student_profiles sp ON u.id = sp.user_id
                LEFT JOIN branches b ON sp.branch_id = b.id
                WHERE {$where}
                ORDER BY u.first_name ASC
                LIMIT {$perPage} OFFSET {$offset}";

        $students = $this->db->raw($sql, $params);

        $this->success([
            'data'      => $students,
            'page'      => $page,
            'lastPage'  => max(1, (int) ceil($total / $perPage)),
            'total'     => $total,
            'perPage'   => $perPage,
        ]);
    }

    /**
     * JSON create student.
     * POST /api/students
     */
    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin', 'Dean']);

        $validation = $this->validate([
            'first_name'   => 'required|min:2|max:100',
            'last_name'    => 'required|min:2|max:100',
            'email'        => 'required|email',
            'admission_no' => 'required|min:2|max:50',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        try {
            $user = $this->db->insert('users', [
                'first_name' => $this->input('first_name'),
                'last_name'  => $this->input('last_name'),
                'email'      => $this->input('email'),
                'phone'      => $this->input('phone'),
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'user_type'  => 'student',
                'status'     => $this->input('status', 'active'),
            ]);

            $profile = $this->db->insert('student_profiles', [
                'user_id'        => $user['id'],
                'admission_no'   => $this->input('admission_no'),
                'dob'            => $this->input('dob') ?: null,
                'gender'         => $this->input('gender', 'male'),
                'guardian_name'  => $this->input('guardian_name'),
                'guardian_phone' => $this->input('guardian_phone'),
                'branch_id'      => $this->input('branch_id') ?: null,
                'class_id'       => $this->input('class_id') ?: null,
            ]);

            $this->success(array_merge($user, $profile), 'Student created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create student: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON update student.
     * PUT /api/students/{id}
     */
    public function apiUpdate(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin', 'Dean']);

        $student = $this->db->find('users', $id);
        if (!$student || $student['user_type'] !== 'student') {
            $this->error('Student not found.', 404);
            return;
        }

        $validation = $this->validate([
            'first_name'   => 'required|min:2|max:100',
            'last_name'    => 'required|min:2|max:100',
            'email'        => 'required|email',
            'admission_no' => 'required|min:2|max:50',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        try {
            $this->db->updateById('users', $id, [
                'first_name' => $this->input('first_name'),
                'last_name'  => $this->input('last_name'),
                'email'      => $this->input('email'),
                'phone'      => $this->input('phone'),
                'status'     => $this->input('status', 'active'),
            ]);

            $profile = $this->db->single('student_profiles', ['user_id' => ['eq' => $id]]);
            $profileData = [
                'admission_no'   => $this->input('admission_no'),
                'dob'            => $this->input('dob') ?: null,
                'gender'         => $this->input('gender', 'male'),
                'guardian_name'  => $this->input('guardian_name'),
                'guardian_phone' => $this->input('guardian_phone'),
                'branch_id'      => $this->input('branch_id') ?: null,
                'class_id'       => $this->input('class_id') ?: null,
            ];

            if ($profile) {
                $this->db->updateById('student_profiles', $profile['id'], $profileData);
            } else {
                $profileData['user_id'] = $id;
                $this->db->insert('student_profiles', $profileData);
            }

            $this->success(null, 'Student updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update student: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON delete student.
     * DELETE /api/students/{id}
     */
    public function apiDelete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin']);

        $student = $this->db->find('users', $id);
        if (!$student || $student['user_type'] !== 'student') {
            $this->error('Student not found.', 404);
            return;
        }

        try {
            $profile = $this->db->single('student_profiles', ['user_id' => ['eq' => $id]]);
            if ($profile) {
                $this->db->deleteById('student_profiles', $profile['id']);
            }
            $this->db->deleteById('users', $id);
            $this->success(null, 'Student deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete student: ' . $e->getMessage(), 500);
        }
    }
}
