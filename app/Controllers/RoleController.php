<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * RoleController
 *
 * Manages roles and their associated permissions.
 * Each role can have multiple permissions assigned through role_permissions.
 */
class RoleController extends Controller
{
    /**
     * List all roles with their permissions.
     * GET /roles
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin']);

        $page = (int) ($this->input('page', 1) ?? 1);
        $search = $this->input('search', '');
        $perPage = 20;

        $filters = [];
        if (!empty($search)) {
            $filters['display_name'] = ['ilike' => '%' . $search . '%'];
        }

        $result = $this->paginate('roles', $page, $perPage, $filters, 'name.asc');

        // Fetch permissions for each role
        $enrichedRoles = [];
        foreach ($result['data'] as $role) {
            $permissions = $this->db->select(
                'role_permissions',
                ['role_id' => ['eq' => $role['id']]],
                null, null, null,
                'permission_id,permissions(name,display_name,module)'
            );

            $role['permissions'] = [];
            foreach ($permissions as $p) {
                $perm = $p['permissions'] ?? null;
                if ($perm) {
                    $role['permissions'][] = $perm;
                }
            }

            $enrichedRoles[] = $role;
        }

        // Fetch all available modules and permissions for the matrix
        $modules = $this->db->select('modules', ['is_active' => ['eq' => true]], 'sort_order.asc');
        $allPermissions = $this->db->select('permissions', [], 'module.asc,name.asc');

        // Group permissions by module
        $permissionsByModule = [];
        foreach ($allPermissions as $perm) {
            $module = $perm['module'] ?? 'General';
            if (!isset($permissionsByModule[$module])) {
                $permissionsByModule[$module] = [];
            }
            $permissionsByModule[$module][] = $perm;
        }

        $this->renderWithLayout('roles.index', [
            'pageTitle'           => 'Roles & Permissions',
            'currentPage'         => 'roles',
            'roles'               => $enrichedRoles,
            'modules'             => $modules,
            'allPermissions'      => $allPermissions,
            'permissionsByModule' => $permissionsByModule,
            'pagination'          => [
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
     * Show the create role form.
     * GET /roles/create
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin']);

        $this->index();
    }

    /**
     * Save a new role with permissions.
     * POST /roles
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin']);

        $validation = $this->validate([
            'name'         => 'required|min:2|max:100',
            'display_name' => 'required|min:2|max:255',
            'description'  => 'max:500',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/roles');
            return;
        }

        // Check for duplicate role name
        $existing = $this->db->single('roles', ['name' => ['eq' => $this->input('name')]]);
        if ($existing) {
            error_msg('A role with this name already exists.');
            $this->redirect('/roles');
            return;
        }

        $data = [
            'name'         => $this->input('name'),
            'display_name' => $this->input('display_name'),
            'description'  => $this->input('description'),
            'scope'        => $this->input('scope', 'global'),
        ];

        try {
            $role = $this->db->insert('roles', $data);

            // Assign permissions
            $permissionIds = $this->input('permission_ids', []);
            if (is_array($permissionIds) && !empty($permissionIds)) {
                foreach ($permissionIds as $permId) {
                    $this->db->insert('role_permissions', [
                        'role_id'       => $role['id'],
                        'permission_id' => $permId,
                    ]);
                }
            }

            success_msg('Role created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create role: ' . $e->getMessage());
        }

        $this->redirect('/roles');
    }

    /**
     * Show the edit role form.
     * GET /roles/{id}/edit
     */
    public function edit(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin']);

        $role = $this->db->find('roles', $id);

        if (!$role) {
            error_msg('Role not found.');
            $this->redirect('/roles');
            return;
        }

        // Get role's current permissions
        $rolePerms = $this->db->select('role_permissions', ['role_id' => ['eq' => $id]], null, null, null, 'permission_id');
        $assignedPermIds = array_column($rolePerms, 'permissionId');

        $page = (int) ($this->input('page', 1) ?? 1);
        $result = $this->paginate('roles', $page, 20, [], 'name.asc');

        $modules = $this->db->select('modules', ['is_active' => ['eq' => true]], 'sort_order.asc');
        $allPermissions = $this->db->select('permissions', [], 'module.asc,name.asc');

        $permissionsByModule = [];
        foreach ($allPermissions as $perm) {
            $module = $perm['module'] ?? 'General';
            if (!isset($permissionsByModule[$module])) {
                $permissionsByModule[$module] = [];
            }
            $permissionsByModule[$module][] = $perm;
        }

        $this->renderWithLayout('roles.index', [
            'pageTitle'           => 'Edit Role',
            'currentPage'         => 'roles',
            'roles'               => $result['data'],
            'editRole'            => $role,
            'assignedPermIds'     => $assignedPermIds,
            'modules'             => $modules,
            'allPermissions'      => $allPermissions,
            'permissionsByModule' => $permissionsByModule,
            'pagination'          => [
                'page'       => $result['page'],
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => 1,
                'to'         => min(20, $result['total']),
            ],
            'search' => '',
        ]);
    }

    /**
     * Update a role with permissions.
     * POST /roles/{id}
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin']);

        $role = $this->db->find('roles', $id);

        if (!$role) {
            error_msg('Role not found.');
            $this->redirect('/roles');
            return;
        }

        $validation = $this->validate([
            'name'         => 'required|min:2|max:100',
            'display_name' => 'required|min:2|max:255',
            'description'  => 'max:500',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/roles');
            return;
        }

        $data = [
            'name'         => $this->input('name'),
            'display_name' => $this->input('display_name'),
            'description'  => $this->input('description'),
            'scope'        => $this->input('scope', 'global'),
        ];

        try {
            $this->db->updateById('roles', $id, $data);

            // Update permissions: delete existing, then insert new
            $this->db->delete('role_permissions', ['role_id' => ['eq' => $id]]);
            $permissionIds = $this->input('permission_ids', []);
            if (is_array($permissionIds) && !empty($permissionIds)) {
                foreach ($permissionIds as $permId) {
                    $this->db->insert('role_permissions', [
                        'role_id'       => $id,
                        'permission_id' => $permId,
                    ]);
                }
            }

            success_msg('Role updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update role: ' . $e->getMessage());
        }

        $this->redirect('/roles');
    }

    /**
     * Delete a role.
     * POST /roles/{id}/delete
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin']);

        $role = $this->db->find('roles', $id);

        if (!$role) {
            error_msg('Role not found.');
            $this->redirect('/roles');
            return;
        }

        // Prevent deleting system roles
        $systemRoles = ['super_admin', 'admin'];
        if (in_array($role['name'], $systemRoles)) {
            error_msg('Cannot delete system roles.');
            $this->redirect('/roles');
            return;
        }

        try {
            $this->db->delete('role_permissions', ['role_id' => ['eq' => $id]]);
            $this->db->delete('user_roles', ['role_id' => ['eq' => $id]]);
            $this->db->deleteById('roles', $id);
            success_msg('Role deleted successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to delete role: ' . $e->getMessage());
        }

        $this->redirect('/roles');
    }

    /**
     * JSON list of roles with permissions.
     */
    public function apiIndex(): void
    {
        $this->requireAuth();

        $search = $this->input('search', '');
        $scope = $this->input('scope', '');
        $page = (int) ($this->input('page', 1) ?? 1);
        $perPage = (int) ($this->input('per_page', 20) ?? 20);

        $filters = [];
        if (!empty($search)) {
            $filters['display_name'] = ['ilike' => '%' . $search . '%'];
        }
        if (!empty($scope)) {
            $filters['scope'] = ['eq' => $scope];
        }

        $result = $this->paginate('roles', $page, $perPage, $filters, 'name.asc');

        $this->success($result);
    }

    /**
     * JSON create role.
     */
    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin']);

        $validation = $this->validate([
            'name'         => 'required|min:2|max:100',
            'display_name' => 'required|min:2|max:255',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        $data = [
            'name'         => $this->input('name'),
            'display_name' => $this->input('display_name'),
            'description'  => $this->input('description'),
            'scope'        => $this->input('scope', 'global'),
        ];

        try {
            $role = $this->db->insert('roles', $data);
            $this->success($role, 'Role created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create role: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON update role.
     */
    public function apiUpdate(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin']);

        $role = $this->db->find('roles', $id);
        if (!$role) {
            $this->error('Role not found.', 404);
            return;
        }

        $data = array_filter([
            'name'         => $this->input('name'),
            'display_name' => $this->input('display_name'),
            'description'  => $this->input('description'),
            'scope'        => $this->input('scope'),
        ], fn($v) => $v !== null);

        try {
            $updated = $this->db->updateById('roles', $id, $data);
            $this->success($updated, 'Role updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update role: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON delete role.
     */
    public function apiDelete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin']);

        $role = $this->db->find('roles', $id);
        if (!$role) {
            $this->error('Role not found.', 404);
            return;
        }

        try {
            $this->db->delete('role_permissions', ['role_id' => ['eq' => $id]]);
            $this->db->delete('user_roles', ['role_id' => ['eq' => $id]]);
            $this->db->deleteById('roles', $id);
            $this->success(null, 'Role deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete role: ' . $e->getMessage(), 500);
        }
    }
}
