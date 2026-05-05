<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Session;
use Core\Request;

/**
 * CommunicationController
 *
 * Manages communication within the school including messages,
 * notifications, and public notices. Supports sending to
 * individuals, groups, or all users.
 */
class CommunicationController extends Controller
{
    /**
     * Display communication overview page.
     * GET /communication
     */
    public function index(): void
    {
        $this->requireAuth();

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 20;

        $userId = $this->currentUserId();
        $filters = ['user_id' => ['eq' => $userId]];

        $result = $this->paginate('notifications', $page, $perPage, $filters, 'created_at.desc');

        $unreadCount = $this->db->count('notifications', [
            'user_id' => ['eq' => $userId],
            'is_read' => ['eq' => false],
        ]);

        $this->renderWithLayout('communication.index', [
            'pageTitle'    => 'Communication',
            'currentPage'  => 'communication',
            'notifications' => $result['data'],
            'pagination'   => [
                'page'       => $result['page'],
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => (($result['page'] - 1) * $perPage) + 1,
                'to'         => min($result['page'] * $perPage, $result['total']),
            ],
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Display messages list (alias for index).
     * GET /communication/messages
     */
    public function messages(): void
    {
        $this->index();
    }

    /**
     * Show create message form.
     * GET /communication/messages/create
     */
    public function createMessage(): void
    {
        $this->requireAuth();

        $this->renderWithLayout('communication.index', [
            'pageTitle'    => 'Send Message',
            'currentPage'  => 'communication',
            'notifications' => [],
            'pagination'   => ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0],
            'unreadCount'  => 0,
        ]);
    }

    /**
     * Store a new message/notification.
     * POST /communication/messages
     */
    public function storeMessage(): void
    {
        $this->requireAuth();

        $validation = $this->validate([
            'title'   => 'required|min:2|max:255',
            'message' => 'required|min:1|max:5000',
            'type'    => 'required',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/communication');
            return;
        }

        $recipientType = $this->input('recipient_type', 'individual');
        $recipientId = $this->input('recipient_id', '');

        $data = [
            'school_id'   => $this->input('school_id', ''),
            'user_id'     => $recipientId ?: null,
            'sender_id'   => $this->currentUserId(),
            'title'       => $this->input('title'),
            'message'     => $this->input('message'),
            'type'        => $this->input('type', 'message'),
            'priority'    => $this->input('priority', 'medium'),
            'is_read'     => false,
        ];

        try {
            $this->db->insert('notifications', $data);
            success_msg('Message sent successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to send message: ' . $e->getMessage());
        }

        $this->redirect('/communication');
    }

    /**
     * Display public notices.
     * GET /communication/notices
     */
    public function notices(): void
    {
        $this->requireAuth();

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 20;

        $filters = ['type' => ['eq' => 'notice']];
        $result = $this->paginate('notifications', $page, $perPage, $filters, 'created_at.desc');

        $this->renderWithLayout('communication.index', [
            'pageTitle'    => 'Notices',
            'currentPage'  => 'communication',
            'notifications' => $result['data'],
            'pagination'   => [
                'page'       => $result['page'],
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => (($result['page'] - 1) * $perPage) + 1,
                'to'         => min($result['page'] * $perPage, $result['total']),
            ],
            'unreadCount' => 0,
        ]);
    }

    /**
     * Show create notice form.
     * GET /communication/notices/create
     */
    public function createNotice(): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean']);

        $this->renderWithLayout('communication.index', [
            'pageTitle'    => 'Create Notice',
            'currentPage'  => 'communication',
            'notifications' => [],
            'pagination'   => ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0],
            'unreadCount'  => 0,
        ]);
    }

    /**
     * Store a new public notice.
     * POST /communication/notices
     */
    public function storeNotice(): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean']);

        $validation = $this->validate([
            'title'   => 'required|min:2|max:255',
            'message' => 'required|min:1|max:5000',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/communication');
            return;
        }

        $data = [
            'school_id'    => $this->input('school_id', ''),
            'user_id'      => null,
            'sender_id'    => $this->currentUserId(),
            'title'        => $this->input('title'),
            'message'      => $this->input('message'),
            'type'         => 'notice',
            'priority'     => $this->input('priority', 'medium'),
            'is_read'      => false,
        ];

        try {
            $this->db->insert('notifications', $data);
            success_msg('Notice created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create notice: ' . $e->getMessage());
        }

        $this->redirect('/communication');
    }

    /**
     * Mark a notification as read.
     * POST /communication/{id}/read
     */
    public function markAsRead(string $id): void
    {
        $this->requireAuth();

        $notification = $this->db->find('notifications', $id);
        if (!$notification) {
            if (Request::expectsJson()) {
                $this->error('Notification not found.', 404);
                return;
            }
            error_msg('Notification not found.');
            $this->redirect('/communication');
            return;
        }

        try {
            $this->db->updateById('notifications', $id, ['is_read' => true]);
            if (Request::expectsJson()) {
                $this->success(null, 'Notification marked as read.');
                return;
            }
            success_msg('Notification marked as read.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to mark notification as read.');
        }

        $this->redirect('/communication');
    }

    /**
     * Delete a notification.
     * POST /communication/{id}/delete
     */
    public function delete(string $id): void
    {
        $this->requireAuth();

        $notification = $this->db->find('notifications', $id);
        if (!$notification) {
            error_msg('Notification not found.');
            $this->redirect('/communication');
            return;
        }

        try {
            $this->db->deleteById('notifications', $id);
            success_msg('Notification deleted successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to delete notification: ' . $e->getMessage());
        }

        $this->redirect('/communication');
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: List messages as JSON.
     * GET /api/communication/messages
     */
    public function apiMessages(): void
    {
        $this->requireAuth();

        $search = $this->input('search', '');
        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = (int) ($this->input('per_page', 20) ?: 20);

        $userId = $this->currentUserId();
        $filters = ['user_id' => ['eq' => $userId]];

        if (!empty($search)) {
            $filters['title'] = ['ilike' => '%' . $search . '%'];
        }

        $result = $this->paginate('notifications', $page, $perPage, $filters, 'created_at.desc');

        $unreadCount = $this->db->count('notifications', [
            'user_id' => ['eq' => $userId],
            'is_read' => ['eq' => false],
        ]);

        $this->success([
            'notifications' => $result['data'],
            'unread_count'  => $unreadCount,
            'pagination'    => $result,
        ]);
    }

    /**
     * API: Store a new message.
     * POST /api/communication/messages
     */
    public function apiStoreMessage(): void
    {
        $this->requireAuth();

        $validation = $this->validate([
            'title'   => 'required|min:2|max:255',
            'message' => 'required|min:1|max:5000',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        $data = [
            'school_id'    => $this->input('school_id', ''),
            'user_id'      => $this->input('recipient_id', '') ?: null,
            'sender_id'    => $this->currentUserId(),
            'title'        => $this->input('title'),
            'message'      => $this->input('message'),
            'type'         => $this->input('type', 'message'),
            'priority'     => $this->input('priority', 'medium'),
            'is_read'      => false,
        ];

        try {
            $notification = $this->db->insert('notifications', $data);
            $this->success($notification, 'Message sent successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to send message: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Get a single message by ID.
     * GET /api/communication/messages/{id}
     */
    public function apiShowMessage(string $id): void
    {
        $this->requireAuth();

        $message = $this->db->find('notifications', $id);
        if (!$message) {
            $this->error('Message not found.', 404);
            return;
        }

        $this->success($message);
    }

    /**
     * API: Delete a message.
     * DELETE /api/communication/messages/{id}
     */
    public function apiDeleteMessage(string $id): void
    {
        $this->requireAuth();

        try {
            $this->db->deleteById('notifications', $id);
            $this->success(null, 'Message deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete message: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: List notices.
     * GET /api/communication/notices
     */
    public function apiNotices(): void
    {
        $this->requireAuth();

        $filters = ['type' => ['eq' => 'notice']];
        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = (int) ($this->input('per_page', 20) ?: 20);

        $result = $this->paginate('notifications', $page, $perPage, $filters, 'created_at.desc');
        $this->success($result);
    }

    /**
     * API: Store a new notice.
     * POST /api/communication/notices
     */
    public function apiStoreNotice(): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean']);

        $validation = $this->validate([
            'title'   => 'required|min:2|max:255',
            'message' => 'required|min:1|max:5000',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        $data = [
            'school_id'    => $this->input('school_id', ''),
            'user_id'      => null,
            'sender_id'    => $this->currentUserId(),
            'title'        => $this->input('title'),
            'message'      => $this->input('message'),
            'type'         => 'notice',
            'priority'     => $this->input('priority', 'medium'),
            'is_read'      => false,
        ];

        try {
            $notice = $this->db->insert('notifications', $data);
            $this->success($notice, 'Notice created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create notice: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Update a notice.
     * PUT /api/communication/notices/{id}
     */
    public function apiUpdateNotice(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean']);

        $data = array_filter([
            'title'    => $this->input('title'),
            'message'  => $this->input('message'),
            'priority' => $this->input('priority'),
        ], fn($v) => $v !== null);

        try {
            $updated = $this->db->updateById('notifications', $id, $data);
            $this->success($updated, 'Notice updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update notice: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Delete a notice.
     * DELETE /api/communication/notices/{id}
     */
    public function apiDeleteNotice(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean']);

        try {
            $this->db->deleteById('notifications', $id);
            $this->success(null, 'Notice deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete notice: ' . $e->getMessage(), 500);
        }
    }
}
