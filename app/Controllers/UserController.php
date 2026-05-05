<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * UserController
 *
 * Manages user CRUD operations including role assignment.
 * Users are associated with schools and branches.
 */
class UserController extends Controller
{
    /**
     * List all users with search, filters, and pagination.
     * GET /users
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $page = (int) ($this->input('page', 1) ?? 1);
        $search = $this->input('search', '');
        $schoolFilter = $this->input('school_id', '');
        $roleFilter = $this->input('role_id', '');
        $statusFilter = $this->input('status', '');
        $perPage = 15;

        $filters = [];

        // Auto-filter by user's school scope
        $user = $this->currentUser();
        if ($user && isset($user['school_id']) && $user['school_id'] !== null) {
            $filters['school_id'] = ['eq' => $user['school_id']];
            $schoolFilter = $user['school_id'];
        } elseif (!empty($schoolFilter)) {
            $filters['school_id'] = ['eq' => $schoolFilter];
        }

        if (!empty($search)) {
            $filters['first_name'] = ['ilike' => '%' . $search . '%'];
        }
        if (!empty($statusFilter)) {
            $filters['is_active'] = ['eq' => $statusFilter === 'active' ? true : false];
        }

        $result = $this->paginate('users', $page, $perPage, $filters, 'first_name.asc');

        // Enrich users with their roles and school/branch names
        $enrichedUsers = [];
        foreach ($result['data'] as $u) {
            $roles = $this->db->select('user_roles', ['user_id' => ['eq' => $u['id']]], null, null, null, 'role_id,roles(name,display_name)');

            $roleNames = [];
            foreach ($roles as $r) {
                $role = $r['roles'] ?? null;
                if ($role) {
                    $roleNames[] = $role['displayName'] ?? $role['name'] ?? '';
                }
            }

            $u['roleNames'] = $roleNames;

            // Fetch school and branch names
            if (!empty($u['school_id'])) {
                $school = $this->db->find('schools', $u['school_id']);
                $u['schoolName'] = $school['name'] ?? 'N/A';
            } else {
                $u['schoolName'] = 'N/A';
            }

            if (!empty($u['branch_id'])) {
                $branch = $this->db->find('branches', $u['branch_id']);
                $u['branchName'] = $branch['name'] ?? 'N/A';
            } else {
                $u['branchName'] = 'N/A';
            }

            $enrichedUsers[] = $u;
        }

        // Fetch dropdown data
        $schools = $this->db->select('schools', [], 'name.asc');
        $allRoles = $this->db->select('roles', [], 'name.asc');

        $this->renderWithLayout('users.index', [
            'pageTitle'    => 'Users',
            'currentPage'  => 'users',
            'users'        => $enrichedUsers,
            'schools'      => $schools,
            'allRoles'     => $allRoles,
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
     * Show the create user form.
     * GET /users/create
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $schools = $this->db->select('schools', [], 'name.asc');
        $allRoles = $this->db->select('roles', [], 'name.asc');

        $result = $this->paginate('users', 1, 15, [], 'first_name.asc');

        $this->renderWithLayout('users.index', [
            'pageTitle'    => 'Add User',
            'currentPage'  => 'users',
            'users'        => [],
            'schools'      => $schools,
            'allRoles'     => $allRoles,
            'schoolFilter' => '',
            'pagination'   => ['totalPages' => 0, 'total' => 0],
            'search'       => '',
        ]);
    }

    /**
     * Save a new user with role assignment.
     * POST /users
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $validation = $this->validate([
            'first_name' => 'required|min:2|max:100',
            'last_name'  => 'required|min:2|max:100',
            'email'      => 'required|email',
            'phone'      => 'max:20',
            'password'   => 'required|min:8',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/users');
            return;
        }

        // Check for duplicate email
        $existing = $this->db->single('users', ['email' => ['eq' => $this->input('email')]]);
        if ($existing) {
            error_msg('A user with this email already exists.');
            $this->redirect('/users');
            return;
        }

        // Enforce school scope
        $user = $this->currentUser();
        $schoolId = $this->input('school_id');
        $branchId = $this->input('branch_id');

        if ($user && isset($user['school_id']) && $user['school_id'] !== null) {
            $schoolId = $user['school_id'];
        }

        $data = [
            'first_name' => $this->input('first_name'),
            'last_name'  => $this->input('last_name'),
            'email'      => $this->input('email'),
            'phone'      => $this->input('phone'),
            'passwordHash' => hash('sha256', $this->input('password') . (string) config('password_salt', '_school_erp_salt')),
            'schoolId'    => $schoolId,
            'branchId'    => $branchId,
            'isActive'    => $this->input('is_active', true),
        ];

        try {
            $newUser = $this->db->insert('users', $data);

            // Assign roles
            $roleIds = $this->input('role_ids', []);
            if (is_array($roleIds) && !empty($roleIds)) {
                foreach ($roleIds as $roleId) {
                    $this->db->insert('user_roles', [
                        'user_id' => $newUser['id'],
                        'role_id' => $roleId,
                    ]);
                }
            }

            success_msg('User created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create user: ' . $e->getMessage());
        }

        $this->redirect('/users');
    }

    /**
     * Show the edit user form.
     * GET /users/{id}/edit
     */
    public function edit(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $editUser = $this->db->find('users', $id);

        if (!$editUser) {
            error_msg('User not found.');
            $this->redirect('/users');
            return;
        }

        // School-level users can only edit users in their school
        $user = $this->currentUser();
        if ($user && isset($user['school_id']) && $user['school_id'] !== null) {
            if ($editUser['school_id'] !== $user['school_id']) {
                error_msg('You do not have permission to edit this user.');
                $this->redirect('/users');
                return;
            }
        }

        // Get user's current roles
        $userRoles = $this->db->select('user_roles', ['user_id' => ['eq' => $id]], null, null, null, 'role_id');
        $assignedRoleIds = array_column($userRoles, 'roleId');

        $result = $this->paginate('users', 1, 15, [], 'first_name.asc');
        $schools = $this->db->select('schools', [], 'name.asc');
        $allRoles = $this->db->select('roles', [], 'name.asc');

        $this->renderWithLayout('users.index', [
            'pageTitle'        => 'Edit User',
            'currentPage'      => 'users',
            'users'            => [],
            'editUser'         => $editUser,
            'assignedRoleIds'  => $assignedRoleIds,
            'schools'          => $schools,
            'allRoles'         => $allRoles,
            'schoolFilter'     => '',
            'pagination'       => ['totalPages' => 0, 'total' => 0],
            'search'           => '',
        ]);
    }

    /**
     * Update a user with role assignment.
     * POST /users/{id}
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $editUser = $this->db->find('users', $id);

        if (!$editUser) {
            error_msg('User not found.');
            $this->redirect('/users');
            return;
        }

        $user = $this->currentUser();
        if ($user && isset($user['school_id']) && $user['school_id'] !== null) {
            if ($editUser['school_id'] !== $user['school_id']) {
                error_msg('You do not have permission to update this user.');
                $this->redirect('/users');
                return;
            }
        }

        $validation = $this->validate([
            'first_name' => 'required|min:2|max:100',
            'last_name'  => 'required|min:2|max:100',
            'email'      => 'required|email',
            'phone'      => 'max:20',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/users');
            return;
        }

        // Check email uniqueness (excluding current user)
        $email = $this->input('email');
        if ($email !== $editUser['email']) {
            $existing = $this->db->single('users', ['email' => ['eq' => $email]]);
            if ($existing && $existing['id'] !== $id) {
                error_msg('A user with this email already exists.');
                $this->redirect('/users');
                return;
            }
        }

        $schoolId = $this->input('school_id');
        if ($user && isset($user['school_id']) && $user['school_id'] !== null) {
            $schoolId = $user['school_id'];
        }

        $data = [
            'first_name' => $this->input('first_name'),
            'last_name'  => $this->input('last_name'),
            'email'      => $email,
            'phone'      => $this->input('phone'),
            'school_id'  => $schoolId,
            'branch_id'  => $this->input('branch_id'),
            'is_active'  => $this->input('is_active', true),
        ];

        // Update password only if provided
        $password = $this->input('password');
        if (!empty($password)) {
            $data['passwordHash'] = hash('sha256', $password . (string) config('password_salt', '_school_erp_salt'));
        }

        try {
            $this->db->updateById('users', $id, $data);

            // Update roles: delete existing, then insert new
            $this->db->delete('user_roles', ['user_id' => ['eq' => $id]]);
            $roleIds = $this->input('role_ids', []);
            if (is_array($roleIds) && !empty($roleIds)) {
                foreach ($roleIds as $roleId) {
                    $this->db->insert('user_roles', [
                        'user_id' => $id,
                        'role_id' => $roleId,
                    ]);
                }
            }

            success_msg('User updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update user: ' . $e->getMessage());
        }

        $this->redirect('/users');
    }

    /**
     * Delete a user.
     * POST /users/{id}/delete
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin']);

        $editUser = $this->db->find('users', $id);

        if (!$editUser) {
            error_msg('User not found.');
            $this->redirect('/users');
            return;
        }

        // Prevent self-deletion
        $currentUser = $this->currentUser();
        if ($currentUser && $currentUser['id'] === $id) {
            error_msg('You cannot delete your own account.');
            $this->redirect('/users');
            return;
        }

        try {
            // Delete user roles first
            $this->db->delete('user_roles', ['user_id' => ['eq' => $id]]);
            $this->db->deleteById('users', $id);
            success_msg('User deleted successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to delete user: ' . $e->getMessage());
        }

        $this->redirect('/users');
    }

    /**
     * JSON list of users with filters.
     */
    public function apiIndex(): void
    {
        $this->requireAuth();

        $search = $this->input('search', '');
        $schoolId = $this->input('school_id', '');
        $roleId = $this->input('role_id', '');
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
            $filters['first_name'] = ['ilike' => '%' . $search . '%'];
        }
        if (!empty($status)) {
            $filters['is_active'] = ['eq' => $status === 'active' ? true : false];
        }

        $result = $this->paginate('users', $page, $perPage, $filters, 'first_name.asc');

        $this->success($result);
    }

    /**
     * JSON create user.
     */
    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $validation = $this->validate([
            'first_name' => 'required|min:2|max:100',
            'last_name'  => 'required|min:2|max:100',
            'email'      => 'required|email',
            'password'   => 'required|min:8',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        $data = [
            'first_name' => $this->input('first_name'),
            'last_name'  => $this->input('last_name'),
            'email'      => $this->input('email'),
            'phone'      => $this->input('phone'),
            'passwordHash' => hash('sha256', $this->input('password') . (string) config('password_salt', '_school_erp_salt')),
            'schoolId'    => $this->input('school_id'),
            'branchId'    => $this->input('branch_id'),
            'isActive'    => $this->input('is_active', true),
        ];

        try {
            $newUser = $this->db->insert('users', $data);

            $roleIds = $this->input('role_ids', []);
            if (is_array($roleIds)) {
                foreach ($roleIds as $roleId) {
                    $this->db->insert('user_roles', [
                        'user_id' => $newUser['id'],
                        'role_id' => $roleId,
                    ]);
                }
            }

            $this->success($newUser, 'User created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON update user.
     */
    public function apiUpdate(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin', 'School Admin']);

        $editUser = $this->db->find('users', $id);
        if (!$editUser) {
            $this->error('User not found.', 404);
            return;
        }

        $data = array_filter([
            'first_name' => $this->input('first_name'),
            'last_name'  => $this->input('last_name'),
            'email'      => $this->input('email'),
            'phone'      => $this->input('phone'),
            'school_id'  => $this->input('school_id'),
            'branch_id'  => $this->input('branch_id'),
            'is_active'  => $this->input('is_active'),
        ], fn($v) => $v !== null);

        $password = $this->input('password');
        if (!empty($password)) {
            $data['passwordHash'] = hash('sha256', $password . (string) config('password_salt', '_school_erp_salt'));
        }

        try {
            $updated = $this->db->updateById('users', $id, $data);

            $roleIds = $this->input('role_ids');
            if (is_array($roleIds)) {
                $this->db->delete('user_roles', ['user_id' => ['eq' => $id]]);
                foreach ($roleIds as $roleId) {
                    $this->db->insert('user_roles', [
                        'user_id' => $id,
                        'role_id' => $roleId,
                    ]);
                }
            }

            $this->success($updated, 'User updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON delete user.
     */
    public function apiDelete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin']);

        $editUser = $this->db->find('users', $id);
        if (!$editUser) {
            $this->error('User not found.', 404);
            return;
        }

        $currentUser = $this->currentUser();
        if ($currentUser && $currentUser['id'] === $id) {
            $this->error('You cannot delete your own account.', 403);
            return;
        }

        try {
            $this->db->delete('user_roles', ['user_id' => ['eq' => $id]]);
            $this->db->deleteById('users', $id);
            $this->success(null, 'User deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete user: ' . $e->getMessage(), 500);
        }
    }
}
