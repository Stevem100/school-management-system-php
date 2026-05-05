<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * ModuleController
 *
 * Manages application modules. Modules represent the major features
 * and sections of the school ERP system.
 */
class ModuleController extends Controller
{
    /**
     * List all modules.
     * GET /modules
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'Admin']);

        $page = (int) ($this->input('page', 1) ?? 1);
        $search = $this->input('search', '');
        $statusFilter = $this->input('status', '');
        $perPage = 20;

        $filters = [];
        if (!empty($search)) {
            $filters['display_name'] = ['ilike' => '%' . $search . '%'];
        }
        if (!empty($statusFilter)) {
            $filters['is_active'] = ['eq' => $statusFilter === 'active' ? true : false];
        }

        $result = $this->paginate('modules', $page, $perPage, $filters, 'sort_order.asc');

        $this->renderWithLayout('modules.index', [
            'pageTitle'   => 'Modules',
            'currentPage' => 'modules',
            'modules'     => $result['data'],
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
     * Show the create module form.
     * GET /modules/create
     */
    public function create(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin']);

        $result = $this->paginate('modules', 1, 20, [], 'sort_order.asc');

        $this->renderWithLayout('modules.index', [
            'pageTitle'   => 'Add Module',
            'currentPage' => 'modules',
            'modules'     => $result['data'],
            'pagination'  => ['totalPages' => 0, 'total' => 0],
            'search'      => '',
        ]);
    }

    /**
     * Save a new module.
     * POST /modules
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin']);

        $validation = $this->validate([
            'name'         => 'required|min:2|max:100',
            'display_name' => 'required|min:2|max:255',
            'route'        => 'required|max:255',
            'description'  => 'max:500',
            'icon'         => 'max:100',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/modules');
            return;
        }

        // Check for duplicate module name
        $existing = $this->db->single('modules', ['name' => ['eq' => $this->input('name')]]);
        if ($existing) {
            error_msg('A module with this name already exists.');
            $this->redirect('/modules');
            return;
        }

        $data = [
            'name'         => $this->input('name'),
            'display_name' => $this->input('display_name'),
            'description'  => $this->input('description'),
            'icon'         => $this->input('icon'),
            'route'        => $this->input('route'),
            'is_active'    => $this->input('is_active', true),
            'sort_order'   => (int) ($this->input('sort_order', 0) ?? 0),
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
        $this->requireRole(['Super Admin']);

        $module = $this->db->find('modules', $id);

        if (!$module) {
            error_msg('Module not found.');
            $this->redirect('/modules');
            return;
        }

        $result = $this->paginate('modules', 1, 20, [], 'sort_order.asc');

        $this->renderWithLayout('modules.index', [
            'pageTitle'   => 'Edit Module',
            'currentPage' => 'modules',
            'modules'     => $result['data'],
            'editModule'  => $module,
            'pagination'  => [
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
        $this->requireRole(['Super Admin']);

        $module = $this->db->find('modules', $id);

        if (!$module) {
            error_msg('Module not found.');
            $this->redirect('/modules');
            return;
        }

        $validation = $this->validate([
            'name'         => 'required|min:2|max:100',
            'display_name' => 'required|min:2|max:255',
            'route'        => 'required|max:255',
            'description'  => 'max:500',
            'icon'         => 'max:100',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/modules');
            return;
        }

        $data = [
            'name'         => $this->input('name'),
            'display_name' => $this->input('display_name'),
            'description'  => $this->input('description'),
            'icon'         => $this->input('icon'),
            'route'        => $this->input('route'),
            'is_active'    => $this->input('is_active', true),
            'sort_order'   => (int) ($this->input('sort_order', 0) ?? 0),
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
        $this->requireRole(['Super Admin']);

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
        $this->requireRole(['Super Admin']);

        $module = $this->db->find('modules', $id);

        if (!$module) {
            $this->error('Module not found.', 404);
            return;
        }

        try {
            $newStatus = !($module['is_active'] ?? true);
            $this->db->updateById('modules', $id, ['is_active' => $newStatus]);
            $this->success(['is_active' => $newStatus], 'Module status updated.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update module status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * JSON list of modules.
     */
    public function apiIndex(): void
    {
        $this->requireAuth();

        $search = $this->input('search', '');
        $status = $this->input('status', '');
        $page = (int) ($this->input('page', 1) ?? 1);
        $perPage = (int) ($this->input('per_page', 20) ?? 20);

        $filters = [];
        if (!empty($search)) {
            $filters['display_name'] = ['ilike' => '%' . $search . '%'];
        }
        if (!empty($status)) {
            $filters['is_active'] = ['eq' => $status === 'active' ? true : false];
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
        $this->requireRole(['Super Admin']);

        $validation = $this->validate([
            'name'         => 'required|min:2|max:100',
            'display_name' => 'required|min:2|max:255',
            'route'        => 'required|max:255',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        $data = [
            'name'         => $this->input('name'),
            'display_name' => $this->input('display_name'),
            'description'  => $this->input('description'),
            'icon'         => $this->input('icon'),
            'route'        => $this->input('route'),
            'is_active'    => $this->input('is_active', true),
            'sort_order'   => (int) ($this->input('sort_order', 0) ?? 0),
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
        $this->requireRole(['Super Admin']);

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
            'is_active'    => $this->input('is_active'),
            'sort_order'   => $this->input('sort_order'),
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
        $this->requireRole(['Super Admin']);

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
