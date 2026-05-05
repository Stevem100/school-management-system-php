<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Session;

/**
 * WebsiteController
 *
 * Manages the public-facing website module including pages, menus,
 * media files, and global website settings. Provides a CMS-like
 * interface for administrators to control the school's website.
 */
class WebsiteController extends Controller
{
    // ─────────────────────────────────────────────────────────
    //  Pages & Settings Overview
    // ─────────────────────────────────────────────────────────

    /**
     * Website dashboard — list all pages and show settings status.
     * GET /website
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.view');

        $pages = $this->fetchPages();
        $settings = $this->fetchWebsiteSettings();
        $stats = $this->fetchWebsiteStats();

        $this->renderWithLayout('website.index', [
            'pageTitle'   => 'Website Manager',
            'currentPage' => 'website',
            'pages'       => $pages,
            'settings'    => $settings,
            'stats'       => $stats,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  Website Settings
    // ─────────────────────────────────────────────────────────

    /**
     * Show website settings form.
     * GET /website/settings
     */
    public function settings(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.edit');

        $settings = $this->fetchWebsiteSettings();

        $this->renderWithLayout('website.index', [
            'pageTitle'   => 'Website Settings',
            'currentPage' => 'website',
            'pages'       => [],
            'settings'    => $settings,
            'stats'       => $this->fetchWebsiteStats(),
        ]);
    }

    /**
     * Save website settings.
     * POST /website/settings
     */
    public function saveSettings(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.edit');

        $data = [
            'site_name'            => $this->input('site_name', ''),
            'site_description'     => $this->input('site_description', ''),
            'site_url'             => $this->input('site_url', ''),
            'site_logo'            => $this->input('site_logo', ''),
            'favicon'              => $this->input('favicon', ''),
            'primary_color'        => $this->input('primary_color', '#1a56db'),
            'secondary_color'      => $this->input('secondary_color', '#6b7280'),
            'footer_text'          => $this->input('footer_text', ''),
            'meta_keywords'        => $this->input('meta_keywords', ''),
            'meta_description'     => $this->input('meta_description', ''),
            'google_analytics_id'  => $this->input('google_analytics_id', ''),
            'social_facebook'      => $this->input('social_facebook', ''),
            'social_twitter'       => $this->input('social_twitter', ''),
            'social_instagram'     => $this->input('social_instagram', ''),
            'social_linkedin'      => $this->input('social_linkedin', ''),
            'social_youtube'       => $this->input('social_youtube', ''),
            'contact_email'        => $this->input('contact_email', ''),
            'contact_phone'        => $this->input('contact_phone', ''),
            'contact_address'      => $this->input('contact_address', ''),
            'updated_by'           => $this->currentUserId(),
            'updated_at'           => date('Y-m-d H:i:s'),
        ];

        try {
            // Check if settings row already exists
            $existing = $this->db->single('website_settings', ['id' => ['eq' => 1]]);

            if ($existing) {
                $this->db->updateById('website_settings', $existing['id'], $data);
                success_msg('Website settings updated successfully.');
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['is_active'] = 1;
                $this->db->insert('website_settings', $data);
                success_msg('Website settings saved successfully.');
            }
        } catch (\RuntimeException $e) {
            error_msg('Failed to save settings: ' . $e->getMessage());
        }

        $this->redirect('/website/settings');
    }

    // ─────────────────────────────────────────────────────────
    //  Pages CRUD
    // ─────────────────────────────────────────────────────────

    /**
     * List all website pages with status.
     * GET /website/pages
     */
    public function pages(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.view');

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 20;
        $search = $this->input('search', '');
        $statusFilter = $this->input('status', '');

        $filters = [];
        if (!empty($search)) {
            $filters['title'] = ['ilike' => '%' . $search . '%'];
        }
        if ($statusFilter === 'published') {
            $filters['status'] = ['eq' => 'published'];
        } elseif ($statusFilter === 'draft') {
            $filters['status'] = ['eq' => 'draft'];
        }

        $result = $this->paginate('website_pages', $page, $perPage, $filters, 'updated_at.desc');

        $this->renderWithLayout('website.pages', [
            'pageTitle'   => 'Website Pages',
            'currentPage' => 'website',
            'pages'       => $result['data'],
            'pagination'  => [
                'page'       => $result['page'],
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => (($result['page'] - 1) * $perPage) + 1,
                'to'         => min($result['page'] * $perPage, $result['total']),
            ],
            'search' => $search,
            'status' => $statusFilter,
        ]);
    }

    /**
     * Show create page form.
     * GET /website/pages/create
     */
    public function createPage(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.create');

        $this->renderWithLayout('website.pages', [
            'pageTitle'   => 'Create Page',
            'currentPage' => 'website',
            'pages'       => [],
            'pagination'  => ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0],
            'search'      => '',
            'status'      => '',
        ]);
    }

    /**
     * Store a new page.
     * POST /website/pages
     */
    public function storePage(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.create');

        $validation = $this->validate([
            'title'   => 'required|min:2|max:255',
            'content' => 'required|min:1',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/website/pages/create');
            return;
        }

        $title = $this->input('title');
        $slug = $this->input('slug', '');
        if (empty($slug)) {
            $slug = slug($title);
        } else {
            $slug = slug($slug);
        }

        // Ensure slug is unique
        $existing = $this->db->single('website_pages', ['slug' => ['eq' => $slug]]);
        if ($existing) {
            $slug = $slug . '-' . time();
        }

        $data = [
            'title'       => $title,
            'slug'        => $slug,
            'content'     => $this->input('content'),
            'meta_title'  => $this->input('meta_title', $title),
            'meta_description' => $this->input('meta_description', ''),
            'meta_keywords'    => $this->input('meta_keywords', ''),
            'status'      => $this->input('status', 'draft'),
            'sort_order'  => (int) ($this->input('sort_order', 0) ?: 0),
            'author_id'   => $this->currentUserId(),
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        try {
            $this->db->insert('website_pages', $data);
            success_msg('Page created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create page: ' . $e->getMessage());
        }

        $this->redirect('/website/pages');
    }

    /**
     * Edit page form.
     * GET /website/pages/{id}/edit
     */
    public function editPage(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.edit');

        $page = $this->db->find('website_pages', $id);
        if (!$page) {
            error_msg('Page not found.');
            $this->redirect('/website/pages');
            return;
        }

        $this->renderWithLayout('website.pages', [
            'pageTitle'   => 'Edit Page',
            'currentPage' => 'website',
            'pages'       => [],
            'editPage'    => $page,
            'pagination'  => ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0],
            'search'      => '',
            'status'      => '',
        ]);
    }

    /**
     * Update a page.
     * POST /website/pages/{id}
     */
    public function updatePage(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.edit');

        $page = $this->db->find('website_pages', $id);
        if (!$page) {
            error_msg('Page not found.');
            $this->redirect('/website/pages');
            return;
        }

        $validation = $this->validate([
            'title'   => 'required|min:2|max:255',
            'content' => 'required|min:1',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/website/pages/' . $id . '/edit');
            return;
        }

        $title = $this->input('title');
        $slug = $this->input('slug', '');
        if (empty($slug)) {
            $slug = slug($title);
        } else {
            $slug = slug($slug);
        }

        // Ensure slug uniqueness (exclude current page)
        $slugExists = $this->db->raw(
            "SELECT COUNT(*) as cnt FROM website_pages WHERE slug = ? AND id != ?",
            [$slug, $id]
        );
        if (!empty($slugExists) && (int) ($slugExists[0]['cnt'] ?? 0) > 0) {
            $slug = $slug . '-' . time();
        }

        $data = [
            'title'            => $title,
            'slug'             => $slug,
            'content'          => $this->input('content'),
            'meta_title'       => $this->input('meta_title', $title),
            'meta_description' => $this->input('meta_description', ''),
            'meta_keywords'    => $this->input('meta_keywords', ''),
            'status'           => $this->input('status', $page['status'] ?? 'draft'),
            'sort_order'       => (int) ($this->input('sort_order', 0) ?: 0),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        try {
            $this->db->updateById('website_pages', $id, $data);
            success_msg('Page updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update page: ' . $e->getMessage());
        }

        $this->redirect('/website/pages');
    }

    /**
     * Delete a page.
     * POST /website/pages/{id}/delete
     */
    public function deletePage(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.delete');

        $page = $this->db->find('website_pages', $id);
        if (!$page) {
            error_msg('Page not found.');
            $this->redirect('/website/pages');
            return;
        }

        try {
            $this->db->deleteById('website_pages', $id);
            success_msg('Page deleted successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to delete page: ' . $e->getMessage());
        }

        $this->redirect('/website/pages');
    }

    // ─────────────────────────────────────────────────────────
    //  Menu Management
    // ─────────────────────────────────────────────────────────

    /**
     * Manage menu items.
     * GET /website/menu
     */
    public function menu(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.manage');

        $menuItems = $this->fetchMenuItems();
        $pages = $this->fetchPages();

        $this->renderWithLayout('website.menu', [
            'pageTitle'   => 'Menu Management',
            'currentPage' => 'website',
            'menuItems'   => $menuItems,
            'pages'       => $pages,
        ]);
    }

    /**
     * Add a menu item.
     * POST /website/menu
     */
    public function addMenuItem(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.manage');

        $validation = $this->validate([
            'label' => 'required|min:1|max:100',
            'url'   => 'max:500',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/website/menu');
            return;
        }

        // Generate sort_order as the last item + 1
        $maxOrder = $this->db->raw("SELECT COALESCE(MAX(sort_order), 0) as max_order FROM website_menu_items");
        $nextOrder = (int) ($maxOrder[0]['maxOrder'] ?? 0) + 1;

        $data = [
            'label'      => $this->input('label'),
            'url'        => $this->input('url', ''),
            'page_id'    => $this->input('page_id', '') ?: null,
            'parent_id'  => $this->input('parent_id', '') ?: null,
            'target'     => $this->input('target', '_self'),
            'icon_class' => $this->input('icon_class', ''),
            'is_active'  => (int) ($this->input('is_active', 1) ?: 1),
            'sort_order' => $nextOrder,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $this->db->insert('website_menu_items', $data);
            success_msg('Menu item added successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to add menu item: ' . $e->getMessage());
        }

        $this->redirect('/website/menu');
    }

    /**
     * Update a menu item.
     * POST /website/menu/{id}
     */
    public function updateMenuItem(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.manage');

        $menuItem = $this->db->find('website_menu_items', $id);
        if (!$menuItem) {
            error_msg('Menu item not found.');
            $this->redirect('/website/menu');
            return;
        }

        $validation = $this->validate([
            'label' => 'required|min:1|max:100',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/website/menu');
            return;
        }

        $data = [
            'label'      => $this->input('label'),
            'url'        => $this->input('url', ''),
            'page_id'    => $this->input('page_id', '') ?: null,
            'parent_id'  => $this->input('parent_id', '') ?: null,
            'target'     => $this->input('target', '_self'),
            'icon_class' => $this->input('icon_class', ''),
            'is_active'  => (int) ($this->input('is_active', 1) ?: 1),
            'sort_order' => (int) ($this->input('sort_order', $menuItem['sortOrder'] ?? 0) ?: 0),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $this->db->updateById('website_menu_items', $id, $data);
            success_msg('Menu item updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update menu item: ' . $e->getMessage());
        }

        $this->redirect('/website/menu');
    }

    /**
     * Delete a menu item.
     * POST /website/menu/{id}/delete
     */
    public function deleteMenuItem(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.delete');

        $menuItem = $this->db->find('website_menu_items', $id);
        if (!$menuItem) {
            error_msg('Menu item not found.');
            $this->redirect('/website/menu');
            return;
        }

        try {
            // Move child items to root level (set parent_id to null)
            $this->db->update('website_menu_items', [
                'parent_id' => null,
                'updated_at' => date('Y-m-d H:i:s'),
            ], ['parent_id' => ['eq' => $id]]);

            $this->db->deleteById('website_menu_items', $id);
            success_msg('Menu item deleted successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to delete menu item: ' . $e->getMessage());
        }

        $this->redirect('/website/menu');
    }

    // ─────────────────────────────────────────────────────────
    //  Media Management
    // ─────────────────────────────────────────────────────────

    /**
     * Manage media files.
     * GET /website/media
     */
    public function media(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.manage');

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 24;
        $typeFilter = $this->input('type', '');
        $search = $this->input('search', '');

        $filters = [];
        if (!empty($typeFilter)) {
            $filters['file_type'] = ['eq' => $typeFilter];
        }
        if (!empty($search)) {
            $filters['file_name'] = ['ilike' => '%' . $search . '%'];
        }

        $result = $this->paginate('website_media', $page, $perPage, $filters, 'created_at.desc');

        $this->renderWithLayout('website.media', [
            'pageTitle'   => 'Media Library',
            'currentPage' => 'website',
            'media'       => $result['data'],
            'pagination'  => [
                'page'       => $result['page'],
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => (($result['page'] - 1) * $perPage) + 1,
                'to'         => min($result['page'] * $perPage, $result['total']),
            ],
            'type'   => $typeFilter,
            'search' => $search,
        ]);
    }

    /**
     * Handle file upload.
     * POST /website/media/upload
     */
    public function uploadMedia(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.create');

        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            if ($this->isAjaxRequest()) {
                $this->error('No file uploaded or upload error.', 400);
                return;
            }
            error_msg('No file uploaded or upload error.');
            $this->redirect('/website/media');
            return;
        }

        $file = $_FILES['file'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'application/pdf', 'video/mp4', 'video/webm'];
        $maxSize = 10 * 1024 * 1024; // 10 MB

        if (!in_array($file['type'], $allowedTypes, true)) {
            if ($this->isAjaxRequest()) {
                $this->error('File type not allowed.', 422);
                return;
            }
            error_msg('File type not allowed. Supported: JPEG, PNG, GIF, WebP, SVG, PDF, MP4, WebM.');
            $this->redirect('/website/media');
            return;
        }

        if ($file['size'] > $maxSize) {
            if ($this->isAjaxRequest()) {
                $this->error('File too large. Maximum size is 10 MB.', 422);
                return;
            }
            error_msg('File too large. Maximum size is 10 MB.');
            $this->redirect('/website/media');
            return;
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/media/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = basename($file['name']);
        $uniqueName = uniqid('media_', true) . '.' . $extension;
        $filePath = $uploadDir . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            if ($this->isAjaxRequest()) {
                $this->error('Failed to save uploaded file.', 500);
                return;
            }
            error_msg('Failed to save uploaded file.');
            $this->redirect('/website/media');
            return;
        }

        // Determine category from MIME type
        $fileType = $this->determineFileType($file['type']);
        $fileSize = $file['size'];
        $dimensions = null;

        if (str_starts_with($file['type'], 'image/')) {
            $imageInfo = @getimagesize($filePath);
            if ($imageInfo !== false) {
                $dimensions = $imageInfo[0] . 'x' . $imageInfo[1];
            }
        }

        $data = [
            'file_name'   => $fileName,
            'file_path'   => '/uploads/media/' . $uniqueName,
            'file_size'   => $fileSize,
            'file_type'   => $fileType,
            'mime_type'   => $file['type'],
            'dimensions'  => $dimensions,
            'alt_text'    => $this->input('alt_text', ''),
            'uploaded_by' => $this->currentUserId(),
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        try {
            $media = $this->db->insert('website_media', $data);

            if ($this->isAjaxRequest()) {
                $this->success($media, 'File uploaded successfully.', 201);
                return;
            }

            success_msg('File uploaded successfully.');
        } catch (\RuntimeException $e) {
            if ($this->isAjaxRequest()) {
                $this->error('Failed to save file record: ' . $e->getMessage(), 500);
                return;
            }
            error_msg('Failed to save file record: ' . $e->getMessage());
        }

        $this->redirect('/website/media');
    }

    /**
     * Delete a media file.
     * POST /website/media/{id}/delete
     */
    public function deleteMedia(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.delete');

        $media = $this->db->find('website_media', $id);
        if (!$media) {
            if ($this->isAjaxRequest()) {
                $this->error('Media file not found.', 404);
                return;
            }
            error_msg('Media file not found.');
            $this->redirect('/website/media');
            return;
        }

        try {
            // Attempt to delete the physical file
            $basePath = dirname(__DIR__, 2) . '/public';
            $filePath = $basePath . ($media['filePath'] ?? '');
            if (file_exists($filePath) && is_file($filePath)) {
                unlink($filePath);
            }

            $this->db->deleteById('website_media', $id);

            if ($this->isAjaxRequest()) {
                $this->success(null, 'Media file deleted successfully.');
                return;
            }

            success_msg('Media file deleted successfully.');
        } catch (\RuntimeException $e) {
            if ($this->isAjaxRequest()) {
                $this->error('Failed to delete media file: ' . $e->getMessage(), 500);
                return;
            }
            error_msg('Failed to delete media file: ' . $e->getMessage());
        }

        $this->redirect('/website/media');
    }

    // ─────────────────────────────────────────────────────────
    //  Toggle & Publish
    // ─────────────────────────────────────────────────────────

    /**
     * Activate or deactivate the website.
     * POST /website/toggle
     */
    public function toggleWebsite(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.manage');

        try {
            $settings = $this->db->single('website_settings', ['id' => ['eq' => 1]]);

            if (!$settings) {
                $this->error('Website settings not found. Please configure settings first.', 404);
                return;
            }

            $currentStatus = (bool) ($settings['isActive'] ?? true);
            $newStatus = !$currentStatus;

            $this->db->updateById('website_settings', $settings['id'], [
                'is_active'  => $newStatus,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $this->success([
                'is_active' => $newStatus,
            ], $newStatus ? 'Website activated successfully.' : 'Website deactivated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to toggle website status: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Publish or unpublish a page.
     * POST /website/pages/{id}/publish
     */
    public function publishPage(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.edit');

        $page = $this->db->find('website_pages', $id);
        if (!$page) {
            $this->error('Page not found.', 404);
            return;
        }

        try {
            $currentStatus = $page['status'] ?? 'draft';
            $newStatus = ($currentStatus === 'published') ? 'draft' : 'published';

            $this->db->updateById('website_pages', $id, [
                'status'     => $newStatus,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $this->success([
                'status' => $newStatus,
            ], $newStatus === 'published' ? 'Page published successfully.' : 'Page unpublished successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update page status: ' . $e->getMessage(), 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Preview
    // ─────────────────────────────────────────────────────────

    /**
     * Preview a page without layout (admin preview).
     * GET /website/preview/{slug}
     */
    public function previewPage(string $slug): void
    {
        $this->requireAuth();

        $page = $this->db->single('website_pages', ['slug' => ['eq' => $slug]]);
        if (!$page) {
            http_response_code(404);
            echo '<h1>Page not found</h1><p>The requested page could not be found.</p>';
            return;
        }

        // Render as a standalone preview without the admin layout
        $settings = $this->fetchWebsiteSettings();

        $this->view('website.preview', [
            'page'     => $page,
            'settings' => $settings,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  Public Website Pages (no auth required)
    // ─────────────────────────────────────────────────────────

    /**
     * Render a view using the public website layout.
     */
    private function renderPublic(string $viewPath, array $data = []): void
    {
        $siteSettings = $this->fetchWebsiteSettings();
        $menuItems = $this->db->select('website_menu_items', ['isActive' => ['eq' => 1]], 'sortOrder.asc');

        $dotPath = str_replace('.', '/', $viewPath);
        $viewFile = BASE_PATH . '/views/' . $dotPath . '.php';
        $layoutFile = BASE_PATH . '/views/layouts/website.php';

        // Capture the view content
        ob_start();
        if (file_exists($viewFile)) {
            extract($data, EXTR_SKIP);
            include $viewFile;
        }
        $content = ob_get_clean();

        // Pass to website layout
        $layoutData = array_merge($data, [
            'content'     => $content,
            'siteSettings' => $siteSettings,
            'menuItems'   => $menuItems,
            'showHero'    => ($viewPath === 'website.public.home'),
        ]);

        if (file_exists($layoutFile)) {
            extract($layoutData, EXTR_SKIP);
            include $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Public homepage.
     * GET /p
     */
    public function publicHome(): void
    {
        $classes = [];
        try {
            $classes = $this->db->select('classes', ['status' => ['eq' => 'active']], 'name.asc', 20);
        } catch (\RuntimeException $e) {}

        $this->renderPublic('website.public.home', [
            'classes' => $classes,
        ]);
    }

    /**
     * Public about page.
     * GET /p/about
     */
    public function publicAbout(): void
    {
        $this->renderPublic('website.public.about', []);
    }

    /**
     * Public contact page.
     * GET /p/contact
     */
    public function publicContact(): void
    {
        $this->renderPublic('website.public.contact', []);
    }

    /**
     * Public classes page.
     * GET /p/classes
     */
    public function publicClasses(): void
    {
        $classes = [];
        try {
            $classes = $this->db->select('classes', ['status' => ['eq' => 'active']], 'name.asc');
        } catch (\RuntimeException $e) {}

        $this->renderPublic('website.public.classes', [
            'classes' => $classes,
        ]);
    }

    /**
     * Public dynamic page by slug.
     * GET /p/page/{slug}
     */
    public function publicPage(string $slug): void
    {
        $page = $this->db->single('website_pages', ['slug' => ['eq' => $slug], 'status' => ['eq' => 'published']]);
        if (!$page) {
            http_response_code(404);
            echo '<h1>Page not found</h1>';
            return;
        }

        $this->renderPublic('website.public.page', [
            'page' => $page,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: List all pages as JSON.
     * GET /api/website/pages
     */
    public function apiPages(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.view');

        $search = $this->input('search', '');
        $status = $this->input('status', '');
        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = (int) ($this->input('per_page', 20) ?: 20);

        $filters = [];
        if (!empty($search)) {
            $filters['title'] = ['ilike' => '%' . $search . '%'];
        }
        if (!empty($status)) {
            $filters['status'] = ['eq' => $status];
        }

        $result = $this->paginate('website_pages', $page, $perPage, $filters, 'updated_at.desc');
        $this->success($result);
    }

    /**
     * API: Create a page.
     * POST /api/website/pages
     */
    public function apiStorePage(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.create');

        $validation = $this->validate([
            'title'   => 'required|min:2|max:255',
            'content' => 'required|min:1',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed.', 422, $validation['errors']);
            return;
        }

        $title = $this->input('title');
        $slugValue = $this->input('slug', '');
        $slug = !empty($slugValue) ? slug($slugValue) : slug($title);

        $existing = $this->db->single('website_pages', ['slug' => ['eq' => $slug]]);
        if ($existing) {
            $slug = $slug . '-' . time();
        }

        $data = [
            'title'       => $title,
            'slug'        => $slug,
            'content'     => $this->input('content'),
            'meta_title'  => $this->input('meta_title', $title),
            'meta_description' => $this->input('meta_description', ''),
            'meta_keywords'    => $this->input('meta_keywords', ''),
            'status'      => $this->input('status', 'draft'),
            'sort_order'  => (int) ($this->input('sort_order', 0) ?: 0),
            'author_id'   => $this->currentUserId(),
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        try {
            $page = $this->db->insert('website_pages', $data);
            $this->success($page, 'Page created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create page: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Update a page.
     * POST /api/website/pages/{id}
     */
    public function apiUpdatePage(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.edit');

        $page = $this->db->find('website_pages', $id);
        if (!$page) {
            $this->error('Page not found.', 404);
            return;
        }

        $data = array_filter([
            'title'            => $this->input('title'),
            'slug'             => $this->input('slug'),
            'content'          => $this->input('content'),
            'meta_title'       => $this->input('meta_title'),
            'meta_description' => $this->input('meta_description'),
            'meta_keywords'    => $this->input('meta_keywords'),
            'status'           => $this->input('status'),
            'sort_order'       => $this->input('sort_order'),
        ], fn($v) => $v !== null);

        if (!empty($data)) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        try {
            $updated = $this->db->updateById('website_pages', $id, $data);
            $this->success($updated, 'Page updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update page: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Delete a page.
     * DELETE /api/website/pages/{id}
     */
    public function apiDeletePage(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.delete');

        try {
            $this->db->deleteById('website_pages', $id);
            $this->success(null, 'Page deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete page: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: List menu items as JSON.
     * GET /api/website/menu
     */
    public function apiMenu(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.manage');

        $menuItems = $this->fetchMenuItems();
        $this->success($menuItems);
    }

    /**
     * API: Save full menu structure (reorder/add/update).
     * POST /api/website/menu/bulk
     */
    public function apiBulkMenu(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.manage');

        $items = $this->input('items', []);
        if (!is_array($items) || empty($items)) {
            $this->error('No menu items provided.', 422);
            return;
        }

        try {
            foreach ($items as $index => $item) {
                $id = $item['id'] ?? '';
                if (empty($id)) {
                    continue;
                }

                $this->db->updateById('website_menu_items', $id, [
                    'parent_id'  => !empty($item['parent_id']) ? $item['parent_id'] : null,
                    'sort_order' => $index,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $this->success(null, 'Menu updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update menu: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: List media files as JSON.
     * GET /api/website/media
     */
    public function apiMedia(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.manage');

        $type = $this->input('type', '');
        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = (int) ($this->input('per_page', 24) ?: 24);

        $filters = [];
        if (!empty($type)) {
            $filters['file_type'] = ['eq' => $type];
        }

        $result = $this->paginate('website_media', $page, $perPage, $filters, 'created_at.desc');
        $this->success($result);
    }

    /**
     * API: Get website settings as JSON.
     * GET /api/website/settings
     */
    public function apiSettings(): void
    {
        $this->requireAuth();
        $this->requirePermission('modules.view');

        $settings = $this->fetchWebsiteSettings();
        $this->success($settings);
    }

    // ─────────────────────────────────────────────────────────
    //  Private Helpers
    // ─────────────────────────────────────────────────────────

    /**
     * Fetch all website pages.
     *
     * @return array
     */
    private function fetchPages(): array
    {
        try {
            return $this->db->select('website_pages', [], 'sort_order.asc,title.asc');
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    /**
     * Fetch website settings.
     *
     * @return array
     */
    private function fetchWebsiteSettings(): array
    {
        try {
            $settings = $this->db->single('website_settings', ['id' => ['eq' => 1]]);
            return $settings ?? [];
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    /**
     * Fetch menu items ordered by sort_order, with parent-child structure.
     *
     * @return array
     */
    private function fetchMenuItems(): array
    {
        try {
            $items = $this->db->select('website_menu_items', [], 'sort_order.asc');
            return $this->buildMenuTree($items);
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    /**
     * Build a hierarchical menu tree from a flat list.
     *
     * @param array $items Flat list of menu items
     * @param mixed $parentId Parent ID for recursion
     * @return array
     */
    private function buildMenuTree(array $items, $parentId = null): array
    {
        $tree = [];
        foreach ($items as $item) {
            $itemParentId = $item['parentId'] ?? null;
            if ($itemParentId === $parentId || (empty($itemParentId) && $parentId === null)) {
                $children = $this->buildMenuTree($items, $item['id']);
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }

    /**
     * Fetch website statistics.
     *
     * @return array
     */
    private function fetchWebsiteStats(): array
    {
        try {
            $totalPages = $this->db->count('website_pages');
            $publishedPages = $this->db->count('website_pages', ['status' => ['eq' => 'published']]);
            $draftPages = $this->db->count('website_pages', ['status' => ['eq' => 'draft']]);
            $menuItems = $this->db->count('website_menu_items');
            $activeMenuItems = $this->db->count('website_menu_items', ['is_active' => ['eq' => 1]]);
            $totalMedia = $this->db->count('website_media');

            $isActive = true;
            $settings = $this->fetchWebsiteSettings();
            if (!empty($settings)) {
                $isActive = (bool) ($settings['isActive'] ?? true);
            }

            return [
                'total_pages'        => $totalPages,
                'published_pages'    => $publishedPages,
                'draft_pages'        => $draftPages,
                'menu_items'         => $menuItems,
                'active_menu_items'  => $activeMenuItems,
                'total_media'        => $totalMedia,
                'is_active'          => $isActive,
            ];
        } catch (\RuntimeException $e) {
            return [
                'total_pages'       => 0,
                'published_pages'   => 0,
                'draft_pages'       => 0,
                'menu_items'        => 0,
                'active_menu_items' => 0,
                'total_media'       => 0,
                'is_active'         => true,
            ];
        }
    }

    /**
     * Determine the file type category from a MIME type.
     *
     * @param string $mimeType
     * @return string
     */
    private function determineFileType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }
        if (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }
        if ($mimeType === 'application/pdf') {
            return 'document';
        }
        return 'other';
    }

    /**
     * Check if the current request is an AJAX/API request.
     *
     * @return bool
     */
    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
