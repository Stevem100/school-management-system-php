<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Session;

/**
 * ModuleController
 *
 * Manages application modules. Modules represent the major features
 * and sections of the school ERP system.
 * Each module shows its linked permissions and which roles have access.
 */
class ModuleController extends Controller
{
    /**
     * List all modules with their permissions and role assignments.
     * GET /modules
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $page = (int) ($this->input('page', 1) ?: 1);
        $search = $this->input('search', '');
        $statusFilter = $this->input('status', '');
        $perPage = 20;

        $filters = [];
        if (!empty($search)) {
            $filters['name'] = ['ilike' => '%' . $search . '%'];
        }
        if ($statusFilter === 'active') {
            $filters['is_active'] = ['eq' => 1];
        } elseif ($statusFilter === 'inactive') {
            $filters['is_active'] = ['eq' => 0];
        }

        $result = $this->paginate('modules', $page, $perPage, $filters, 'sort_order.asc');

        // Load all roles
        $roles = [];
        try {
            $roles = $this->db->select('roles', [], 'level.desc');
        } catch (\RuntimeException $e) {
            // Fall back to empty
        }

        // Load all permissions grouped by module
        $permissionsByModule = [];
        try {
            $allPermissions = $this->db->select('permissions', []);
            foreach ($allPermissions as $perm) {
                $module = $perm['module'] ?? 'unknown';
                if (!isset($permissionsByModule[$module])) {
                    $permissionsByModule[$module] = [];
                }
                $permissionsByModule[$module][] = $perm;
            }
        } catch (\RuntimeException $e) {
            // Fall back to empty
        }

        // Load role-permission mappings (role_id => [permission_names])
        $rolePermissions = [];
        try {
            $rp = $this->db->raw(
                "SELECT rp.role_id, p.name as perm_name
                 FROM role_permissions rp
                 INNER JOIN permissions p ON rp.permission_id = p.id"
            );
            foreach ($rp as $row) {
                $rid = $row['role_id'];
                if (!isset($rolePermissions[$rid])) {
                    $rolePermissions[$rid] = [];
                }
                $rolePermissions[$rid][] = $row['perm_name'];
            }
        } catch (\RuntimeException $e) {
            // Fall back to empty
        }

        // Build a map: role_id => role_name for quick lookup
        $roleMap = [];
        foreach ($roles as $r) {
            $roleMap[$r['id']] = $r['name'];
        }

        $this->renderWithLayout('modules.index', [
            'pageTitle'          => 'Modules',
            'currentPage'        => 'modules',
            'modules'            => $result['data'],
            'roles'              => $roles,
            'permissionsByModule'=> $permissionsByModule,
            'rolePermissions'    => $rolePermissions,
            'roleMap'            => $roleMap,
            'pagination'         => [
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
     * Show the create module form.
     * GET /modules/create
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin']);

        $this->index(); // Reuse index with empty form
    }

    /**
     * Save a new module.
     * POST /modules
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin']);

        $validation = $this->validate([
            'name'        => 'required|min:2|max:100',
            'description' => 'max:500',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/modules');
            return;
        }

        $existing = $this->db->single('modules', ['name' => ['eq' => $this->input('name')]]);
        if ($existing) {
            error_msg('A module with this name already exists.');
            $this->redirect('/modules');
            return;
        }

        $data = [
            'name'         => $this->input('name'),
            'display_name' => $this->input('display_name', $this->input('name')),
            'description'  => $this->input('description'),
            'icon'         => $this->input('icon', ''),
            'route'        => $this->input('route', ''),
            'version'      => $this->input('version', '1.0.0'),
            'sort_order'   => (int) ($this->input('sort_order', 0) ?: 0),
            'is_core'      => (int) ($this->input('is_core', 0) ?: 0),
            'is_active'    => (int) ($this->input('is_active', 1) ?: 1),
        ];

        try {
            $this->db->insert('modules', $data);
            success_msg('Module created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create module: ' . $e->getMessage());
        }

        $this->redirect('/modules');
    }

    /**
     * Show the edit module form.
     * GET /modules/{id}/edit
     */
    public function edit(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin']);

        $module = $this->db->find('modules', $id);
        if (!$module) {
            error_msg('Module not found.');
            $this->redirect('/modules');
            return;
        }

        // Load data for index view, plus editModule
        $roles = [];
        $permissionsByModule = [];
        $rolePermissions = [];
        $roleMap = [];
        try {
            $roles = $this->db->select('roles', [], 'level.desc');
            $allPermissions = $this->db->select('permissions', []);
            foreach ($allPermissions as $perm) {
                $module = $perm['module'] ?? 'unknown';
                if (!isset($permissionsByModule[$module])) {
                    $permissionsByModule[$module] = [];
                }
                $permissionsByModule[$module][] = $perm;
            }
            $rp = $this->db->raw(
                "SELECT rp.role_id, p.name as perm_name
                 FROM role_permissions rp
                 INNER JOIN permissions p ON rp.permission_id = p.id"
            );
            foreach ($rp as $row) {
                $rid = $row['role_id'];
                if (!isset($rolePermissions[$rid])) {
                    $rolePermissions[$rid] = [];
                }
                $rolePermissions[$rid][] = $row['perm_name'];
            }
            foreach ($roles as $r) {
                $roleMap[$r['id']] = $r['name'];
            }
        } catch (\RuntimeException $e) {
            // Fall back to empty
        }

        $result = $this->paginate('modules', 1, 20, [], 'sort_order.asc');

        $this->renderWithLayout('modules.index', [
            'pageTitle'          => 'Edit Module',
            'currentPage'        => 'modules',
            'modules'            => $result['data'],
            'editModule'         => $module,
            'roles'              => $roles,
            'permissionsByModule'=> $permissionsByModule,
            'rolePermissions'    => $rolePermissions,
            'roleMap'            => $roleMap,
            'pagination'         => [
                'page'       => 1,
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => 1,
                'to'         => min(20, $result['total']),
            ],
            'search' => '',
        ]);
    }

    /**
     * Update a module.
     * POST /modules/{id}
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin']);

        $module = $this->db->find('modules', $id);
        if (!$module) {
            error_msg('Module not found.');
            $this->redirect('/modules');
            return;
        }

        $data = [
            'name'         => $this->input('name'),
            'display_name' => $this->input('display_name', $this->input('name')),
            'description'  => $this->input('description'),
            'icon'         => $this->input('icon', ''),
            'route'        => $this->input('route', ''),
            'version'      => $this->input('version', '1.0.0'),
            'sort_order'   => (int) ($this->input('sort_order', 0) ?: 0),
            'is_core'      => (int) ($this->input('is_core', 0) ?: 0),
            'is_active'    => (int) ($this->input('is_active', 1) ?: 1),
        ];

        try {
            $this->db->updateById('modules', $id, $data);
            success_msg('Module updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update module: ' . $e->getMessage());
        }

        $this->redirect('/modules');
    }

    /**
     * Delete a module.
     * POST /modules/{id}/delete
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin']);

        $module = $this->db->find('modules', $id);
        if (!$module) {
            error_msg('Module not found.');
            $this->redirect('/modules');
            return;
        }

        try {
            $this->db->deleteById('modules', $id);
            success_msg('Module deleted successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to delete module: ' . $e->getMessage());
        }

        $this->redirect('/modules');
    }

    /**
     * Toggle module active status.
     * POST /modules/{id}/toggle
     */
    public function toggle(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin']);

        $module = $this->db->find('modules', $id);
        if (!$module) {
            $this->error('Module not found.', 404);
            return;
        }

        try {
            $newStatus = !(($module['isActive'] ?? true) ? true : false);
            $this->db->updateById('modules', $id, ['is_active' => $newStatus]);
            $this->success(['is_active' => $newStatus], 'Module status updated.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update module status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update permissions for a module+role combination.
     * POST /modules/permissions
     */
    public function updatePermissions(): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin']);

        $roleId = (int) ($this->input('role_id', 0) ?: 0);
        $moduleName = $this->input('module', '');
        $action = $this->input('action', ''); // 'grant' or 'revoke'
        $permissionName = $this->input('permission', '');

        if (!$roleId || !$moduleName || !$action || !$permissionName) {
            $this->error('Missing required parameters.', 400);
            return;
        }

        try {
            // Find the permission ID
            $perm = $this->db->single('permissions', ['name' => ['eq' => $permissionName]]);
            if (!$perm) {
                $this->error('Permission not found.', 404);
                return;
            }

            if ($action === 'grant') {
                // Check if already exists
                $existing = $this->db->single('role_permissions', [
                    'role_id' => ['eq' => $roleId],
                    'permission_id' => ['eq' => $perm['id']],
                ]);
                if (!$existing) {
                    $this->db->insert('role_permissions', [
                        'role_id' => $roleId,
                        'permission_id' => $perm['id'],
                    ]);
                }
                $this->success(null, 'Permission granted.');
            } else {
                // Revoke
                $this->db->delete('role_permissions', [
                    'role_id' => ['eq' => $roleId],
                    'permission_id' => ['eq' => $perm['id']],
                ]);
                $this->success(null, 'Permission revoked.');
            }
        } catch (\RuntimeException $e) {
            $this->error('Failed to update permissions: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON list of modules with permissions.
     */
    public function apiIndex(): void
    {
        $this->requireAuth();

        $search = $this->input('search', '');
        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = (int) ($this->input('per_page', 20) ?: 20);

        $filters = [];
        if (!empty($search)) {
            $filters['name'] = ['ilike' => '%' . $search . '%'];
        }

        $result = $this->paginate('modules', $page, $perPage, $filters, 'sort_order.asc');
        $this->success($result);
    }

    /**
     * JSON create module.
     */
    public function apiStore(): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin']);

        $validation = $this->validate(['name' => 'required|min:2|max:100']);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        $data = [
            'name'         => $this->input('name'),
            'display_name' => $this->input('display_name', $this->input('name')),
            'description'  => $this->input('description'),
            'icon'         => $this->input('icon', ''),
            'route'        => $this->input('route', ''),
            'version'      => $this->input('version', '1.0.0'),
            'sort_order'   => (int) ($this->input('sort_order', 0) ?: 0),
            'is_core'      => (int) ($this->input('is_core', 0) ?: 0),
            'is_active'    => (int) ($this->input('is_active', 1) ?: 1),
        ];

        try {
            $module = $this->db->insert('modules', $data);
            $this->success($module, 'Module created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create module: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON update module.
     */
    public function apiUpdate(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin']);

        $module = $this->db->find('modules', $id);
        if (!$module) {
            $this->error('Module not found.', 404);
            return;
        }

        $data = array_filter([
            'name'         => $this->input('name'),
            'display_name' => $this->input('display_name'),
            'description'  => $this->input('description'),
            'icon'         => $this->input('icon'),
            'route'        => $this->input('route'),
            'version'      => $this->input('version'),
            'sort_order'   => $this->input('sort_order'),
            'is_core'      => $this->input('is_core'),
            'is_active'    => $this->input('is_active'),
        ], fn($v) => $v !== null);

        try {
            $updated = $this->db->updateById('modules', $id, $data);
            $this->success($updated, 'Module updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update module: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON delete module.
     */
    public function apiDelete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin']);

        $module = $this->db->find('modules', $id);
        if (!$module) {
            $this->error('Module not found.', 404);
            return;
        }

        try {
            $this->db->deleteById('modules', $id);
            $this->success(null, 'Module deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete module: ' . $e->getMessage(), 500);
        }
    }
}
