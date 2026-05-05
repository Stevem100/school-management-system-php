<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * HostelController
 *
 * Manages hostel rooms and bed allocations. Provides CRUD
 * operations for rooms and handles student hostel assignments.
 */
class HostelController extends Controller
{
    /**
     * Display hostel management overview.
     * GET /hostel
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $filters = [];
        $search = $this->input('search', '');
        $block = $this->input('block', '');
        $status = $this->input('status', '');

        if ($search !== '') {
            $filters['name'] = ['ilike' => "%{$search}%"];
        }
        if ($block !== '') {
            $filters['block'] = ['eq' => $block];
        }
        if ($status !== '') {
            $filters['status'] = ['eq' => $status];
        }

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 15;

        $result = $this->paginate('hostel_rooms', $page, $perPage, $filters, 'room_number.asc');

        $this->renderWithLayout('hostel.index', [
            'pageTitle'   => 'Hostel Management',
            'currentPage' => 'hostel',
            'rooms'       => $result['data'],
            'pagination'  => [
                'page'       => $result['page'],
                'totalPages' => $result['lastPage'],
                'total'      => $result['total'],
                'from'       => (($result['page'] - 1) * $perPage) + 1,
                'to'         => min($result['page'] * $perPage, $result['total']),
            ],
            'search' => $search,
            'block'  => $block,
            'status' => $status,
        ]);
    }

    /**
     * Display rooms list (alias for index).
     * GET /hostel/rooms
     */
    public function rooms(): void
    {
        $this->index();
    }

    /**
     * Show the create room form.
     * GET /hostel/rooms/create
     */
    public function createRoom(): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $result = $this->paginate('hostel_rooms', 1, 15, [], 'room_number.asc');

        $this->renderWithLayout('hostel.index', [
            'pageTitle'   => 'Add Room',
            'currentPage' => 'hostel',
            'rooms'       => $result['data'],
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
     * Save a new room.
     * POST /hostel/rooms
     */
    public function storeRoom(): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $validation = $this->validate([
            'name'        => 'required|min:2|max:150',
            'room_number' => 'required|min:1|max:20',
            'capacity'    => 'required|numeric',
            'floor'       => 'numeric',
            'block'       => 'required|min:1|max:50',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/hostel');
            return;
        }

        $data = [
            'school_id'         => $this->input('school_id', ''),
            'name'              => $this->input('name'),
            'room_number'       => $this->input('room_number'),
            'capacity'          => (int) $this->input('capacity', 0),
            'current_occupancy' => 0,
            'floor'             => $this->input('floor', '1'),
            'block'             => $this->input('block'),
            'amenities'         => $this->input('amenities', ''),
            'status'            => $this->input('status', 'available'),
        ];

        try {
            $this->db->insert('hostel_rooms', $data);
            success_msg('Room created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create room: ' . $e->getMessage());
        }

        $this->redirect('/hostel');
    }

    /**
     * Show a single room.
     * GET /hostel/rooms/{id}
     */
    public function showRoom(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $room = $this->db->find('hostel_rooms', $id);

        if (!$room) {
            error_msg('Room not found.');
            $this->redirect('/hostel');
            return;
        }

        $this->renderWithLayout('hostel.index', [
            'pageTitle'   => 'Room Details',
            'currentPage' => 'hostel',
            'rooms'       => [$room],
            'pagination'  => ['totalPages' => 1, 'total' => 1, 'page' => 1, 'from' => 1, 'to' => 1],
            'search'      => '',
        ]);
    }

    /**
     * Show the edit room form.
     * GET /hostel/rooms/{id}/edit
     */
    public function editRoom(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $room = $this->db->find('hostel_rooms', $id);

        if (!$room) {
            error_msg('Room not found.');
            $this->redirect('/hostel');
            return;
        }

        $result = $this->paginate('hostel_rooms', 1, 15, [], 'room_number.asc');

        $this->renderWithLayout('hostel.index', [
            'pageTitle'   => 'Edit Room',
            'currentPage' => 'hostel',
            'rooms'       => $result['data'],
            'room'        => $room,
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
     * Update a room.
     * POST /hostel/rooms/{id}
     */
    public function updateRoom(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $room = $this->db->find('hostel_rooms', $id);

        if (!$room) {
            error_msg('Room not found.');
            $this->redirect('/hostel');
            return;
        }

        $validation = $this->validate([
            'name'        => 'required|min:2|max:150',
            'room_number' => 'required|min:1|max:20',
            'capacity'    => 'required|numeric',
            'floor'       => 'numeric',
            'block'       => 'required|min:1|max:50',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/hostel');
            return;
        }

        $data = [
            'name'      => $this->input('name'),
            'room_number' => $this->input('room_number'),
            'capacity'  => (int) $this->input('capacity', 0),
            'floor'     => $this->input('floor', '1'),
            'block'     => $this->input('block'),
            'amenities' => $this->input('amenities', ''),
            'status'    => $this->input('status', 'available'),
        ];

        try {
            $this->db->updateById('hostel_rooms', $id, $data);
            success_msg('Room updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update room: ' . $e->getMessage());
        }

        $this->redirect('/hostel');
    }

    /**
     * Delete a room.
     * POST /hostel/rooms/{id}/delete
     */
    public function deleteRoom(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.delete');
        $this->requireRole(['SuperAdmin']);

        $room = $this->db->find('hostel_rooms', $id);

        if (!$room) {
            error_msg('Room not found.');
            $this->redirect('/hostel');
            return;
        }

        try {
            $this->db->deleteById('hostel_rooms', $id);
            success_msg('Room deleted successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to delete room: ' . $e->getMessage());
        }

        $this->redirect('/hostel');
    }

    /**
     * Display room allocations.
     * GET /hostel/allocations
     */
    public function allocations(): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $this->renderWithLayout('hostel.index', [
            'pageTitle'   => 'Room Allocations',
            'currentPage' => 'hostel',
            'rooms'       => [],
            'pagination'  => ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0],
            'search'      => '',
        ]);
    }

    /**
     * Show create allocation form.
     * GET /hostel/allocations/create
     */
    public function createAllocation(): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $this->renderWithLayout('hostel.index', [
            'pageTitle'   => 'Allocate Room',
            'currentPage' => 'hostel',
            'rooms'       => [],
            'pagination'  => ['totalPages' => 0, 'total' => 0, 'page' => 1, 'from' => 0, 'to' => 0],
            'search'      => '',
        ]);
    }

    /**
     * Store a room allocation.
     * POST /hostel/allocations
     */
    public function storeAllocation(): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $data = [
            'room_id'   => $this->input('room_id'),
            'student_id' => $this->input('student_id'),
            'bed_number' => $this->input('bed_number'),
            'status'    => $this->input('status', 'active'),
        ];

        try {
            $this->db->insert('hostel_allocations', $data);
            success_msg('Room allocated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to allocate room: ' . $e->getMessage());
        }

        $this->redirect('/hostel/allocations');
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: List all rooms as JSON.
     * GET /api/hostel/rooms
     */
    public function apiRooms(): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.view');

        $search = $this->input('search', '');
        $block = $this->input('block', '');
        $status = $this->input('status', '');
        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = (int) ($this->input('per_page', 15) ?: 15);

        $filters = [];
        if (!empty($search)) {
            $filters['name'] = ['ilike' => '%' . $search . '%'];
        }
        if (!empty($block)) {
            $filters['block'] = ['eq' => $block];
        }
        if (!empty($status)) {
            $filters['status'] = ['eq' => $status];
        }

        $result = $this->paginate('hostel_rooms', $page, $perPage, $filters, 'room_number.asc');
        $this->success($result);
    }

    /**
     * API: Get a single room by ID.
     * GET /api/hostel/rooms/{id}
     */
    public function apiShowRoom(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.view');

        $room = $this->db->find('hostel_rooms', $id);
        if (!$room) {
            $this->error('Room not found.', 404);
            return;
        }

        $this->success($room);
    }

    /**
     * API: Create a new room.
     * POST /api/hostel/rooms
     */
    public function apiStoreRoom(): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $validation = $this->validate([
            'name'        => 'required|min:2|max:150',
            'room_number' => 'required|min:1|max:20',
            'capacity'    => 'required|numeric',
            'block'       => 'required|min:1|max:50',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed', 422, $validation['errors']);
            return;
        }

        $data = [
            'school_id'         => $this->input('school_id', ''),
            'name'              => $this->input('name'),
            'room_number'       => $this->input('room_number'),
            'capacity'          => (int) $this->input('capacity', 0),
            'current_occupancy' => 0,
            'floor'             => $this->input('floor', '1'),
            'block'             => $this->input('block'),
            'amenities'         => $this->input('amenities', ''),
            'status'            => $this->input('status', 'available'),
        ];

        try {
            $room = $this->db->insert('hostel_rooms', $data);
            $this->success($room, 'Room created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create room: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Update a room.
     * PUT /api/hostel/rooms/{id}
     */
    public function apiUpdateRoom(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.edit');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $room = $this->db->find('hostel_rooms', $id);
        if (!$room) {
            $this->error('Room not found.', 404);
            return;
        }

        $data = array_filter([
            'name'        => $this->input('name'),
            'room_number' => $this->input('room_number'),
            'capacity'    => $this->input('capacity') !== null ? (int) $this->input('capacity') : null,
            'floor'       => $this->input('floor'),
            'block'       => $this->input('block'),
            'amenities'   => $this->input('amenities'),
            'status'      => $this->input('status'),
        ], fn($v) => $v !== null);

        try {
            $updated = $this->db->updateById('hostel_rooms', $id, $data);
            $this->success($updated, 'Room updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update room: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Delete a room.
     * DELETE /api/hostel/rooms/{id}
     */
    public function apiDeleteRoom(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.delete');
        $this->requireRole(['SuperAdmin']);

        $room = $this->db->find('hostel_rooms', $id);
        if (!$room) {
            $this->error('Room not found.', 404);
            return;
        }

        try {
            $this->db->deleteById('hostel_rooms', $id);
            $this->success(null, 'Room deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete room: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: List room allocations.
     * GET /api/hostel/allocations
     */
    public function apiAllocations(): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.view');

        $filters = [];
        $status = $this->input('status', '');
        if (!empty($status)) {
            $filters['status'] = ['eq' => $status];
        }

        $allocations = $this->db->select('hostel_allocations', $filters, 'created_at.desc');
        $this->success($allocations);
    }

    /**
     * API: Store a room allocation.
     * POST /api/hostel/allocations
     */
    public function apiStoreAllocation(): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.create');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        $data = [
            'room_id'    => $this->input('room_id'),
            'student_id' => $this->input('student_id'),
            'bed_number' => $this->input('bed_number'),
            'status'     => $this->input('status', 'active'),
        ];

        try {
            $allocation = $this->db->insert('hostel_allocations', $data);
            $this->success($allocation, 'Room allocated successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to allocate room: ' . $e->getMessage(), 500);
        }
    }

    /**
     * API: Delete a room allocation.
     * DELETE /api/hostel/allocations/{id}
     */
    public function apiDeleteAllocation(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('hostel.delete');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'HostelManager']);

        try {
            $this->db->deleteById('hostel_allocations', $id);
            $this->success(null, 'Allocation removed successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to remove allocation: ' . $e->getMessage(), 500);
        }
    }
}
