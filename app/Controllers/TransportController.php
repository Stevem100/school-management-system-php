<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * TransportController
 *
 * Manages school transport: routes, vehicles, and student assignments.
 * Uses MySQL database for all CRUD operations.
 */
class TransportController extends Controller
{
    /**
     * Transport index page.
     */
    public function index(): void
    {
        $this->requireAuth();

        $routes      = $this->fetchRoutes();
        $vehicles    = $this->fetchVehicles();
        $assignments = $this->fetchAssignments();
        $students    = $this->fetchStudents();
        $stats       = $this->fetchTransportStats();

        $this->renderWithLayout('transport/index', [
            'pageTitle'    => 'Transport',
            'currentPage'  => 'transport',
            'routes'       => $routes,
            'vehicles'     => $vehicles,
            'assignments'  => $assignments,
            'students'     => $students,
            'stats'        => $stats,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes - Routes
    // ─────────────────────────────────────────────────────────

    public function apiRoutes(): void
    {
        $this->requireAuth();
        $this->success($this->fetchRoutes());
    }

    public function apiStoreRoute(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin']);

        $input = $this->requestJson();
        $errors = $this->validateRoute($input);
        if (!empty($errors)) {
            $this->error('Validation failed.', 422, $errors);
            return;
        }

        try {
            $data = [
                'name'        => $input['name'],
                'description' => $input['description'] ?? '',
                'stops'       => json_encode($input['stops'] ?? []),
                'driver_id'   => $input['driver_id'] ?? null,
                'vehicle_id'  => $input['vehicle_id'] ?? null,
                'status'      => $input['status'] ?? 'active',
            ];

            $result = $this->db->insert('transport_routes', $data);
            $this->success($result, 'Route created successfully.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to create route: ' . $e->getMessage(), 500);
        }
    }

    public function apiUpdateRoute(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin']);

        $input = $this->requestJson();
        $id = $input['id'] ?? '';
        if (empty($id)) {
            $this->error('Route ID is required.', 422);
            return;
        }

        try {
            $data = array_filter([
                'name'        => $input['name'] ?? null,
                'description' => $input['description'] ?? null,
                'stops'       => isset($input['stops']) ? json_encode($input['stops']) : null,
                'driver_id'   => $input['driver_id'] ?? null,
                'vehicle_id'  => $input['vehicle_id'] ?? null,
                'status'      => $input['status'] ?? null,
            ], fn($v) => $v !== null);

            $result = $this->db->updateById('transport_routes', $id, $data);
            $this->success($result, 'Route updated.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update route: ' . $e->getMessage(), 500);
        }
    }

    public function apiDestroyRoute(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin']);

        $id = $this->input('id', '');
        if (empty($id)) {
            $this->error('Route ID is required.', 422);
            return;
        }

        try {
            $assignments = $this->db->select('transport_assignments', ['route_id' => ['eq' => $id]]);
            foreach ($assignments as $a) {
                $this->db->deleteById('transport_assignments', $a['id']);
            }
            $this->db->deleteById('transport_routes', $id);
            $this->success(null, 'Route deleted.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete route: ' . $e->getMessage(), 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes - Vehicles
    // ─────────────────────────────────────────────────────────

    public function apiVehicles(): void
    {
        $this->requireAuth();
        $this->success($this->fetchVehicles());
    }

    public function apiStoreVehicle(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin']);

        $input = $this->requestJson();
        $errors = $this->validateVehicle($input);
        if (!empty($errors)) {
            $this->error('Validation failed.', 422, $errors);
            return;
        }

        try {
            $data = [
                'registration_number' => $input['registration_number'],
                'type'              => $input['type'] ?? 'bus',
                'capacity'          => (int) ($input['capacity'] ?? 40),
                'driver_name'       => $input['driver_name'] ?? '',
                'driver_phone'      => $input['driver_phone'] ?? '',
                'status'            => $input['status'] ?? 'active',
            ];

            $result = $this->db->insert('transport_vehicles', $data);
            $this->success($result, 'Vehicle added.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to add vehicle: ' . $e->getMessage(), 500);
        }
    }

    public function apiUpdateVehicle(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin', 'Branch Admin']);

        $input = $this->requestJson();
        $id = $input['id'] ?? '';
        if (empty($id)) {
            $this->error('Vehicle ID is required.', 422);
            return;
        }

        try {
            $data = array_filter([
                'registration_number' => $input['registration_number'] ?? null,
                'type'      => $input['type'] ?? null,
                'capacity'  => isset($input['capacity']) ? (int) $input['capacity'] : null,
                'driver_name'=> $input['driver_name'] ?? null,
                'driver_phone' => $input['driver_phone'] ?? null,
                'status'    => $input['status'] ?? null,
            ], fn($v) => $v !== null);

            $result = $this->db->updateById('transport_vehicles', $id, $data);
            $this->success($result, 'Vehicle updated.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update vehicle: ' . $e->getMessage(), 500);
        }
    }

    public function apiDestroyVehicle(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin']);

        $id = $this->input('id', '');
        if (empty($id)) {
            $this->error('Vehicle ID is required.', 422);
            return;
        }

        try {
            $this->db->deleteById('transport_vehicles', $id);
            $this->success(null, 'Vehicle deleted.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete vehicle: ' . $e->getMessage(), 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes - Assignments
    // ─────────────────────────────────────────────────────────

    public function apiAssignStudent(): void
    {
        $this->requireAuth();

        $input = $this->requestJson();
        if (empty($input['student_id']) || empty($input['route_id'])) {
            $this->error('Student and route are required.', 422);
            return;
        }

        try {
            $data = [
                'student_id'   => $input['student_id'],
                'route_id'     => $input['route_id'],
                'pickup_point' => $input['pickup_point'] ?? '',
                'drop_point'   => $input['drop_point'] ?? '',
            ];

            $result = $this->db->insert('transport_assignments', $data);
            $this->success($result, 'Student assigned to route.', 201);
        } catch (\RuntimeException $e) {
            $this->error('Failed to assign student: ' . $e->getMessage(), 500);
        }
    }

    public function apiRemoveAssignment(): void
    {
        $this->requireAuth();

        $id = $this->input('id', '');
        if (empty($id)) {
            $this->error('Assignment ID is required.', 422);
            return;
        }

        try {
            $this->db->deleteById('transport_assignments', $id);
            $this->success(null, 'Assignment removed.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to remove assignment: ' . $e->getMessage(), 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Data Fetching
    // ─────────────────────────────────────────────────────────

    private function fetchRoutes(): array
    {
        try {
            return $this->db->raw(
                "SELECT tr.*,
                        tv.registration_number as vehicle_reg,
                        tv.driver_name as driver_name
                 FROM transport_routes tr
                 LEFT JOIN transport_vehicles tv ON tr.vehicle_id = tv.id
                 ORDER BY tr.name"
            );
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    private function fetchVehicles(): array
    {
        try {
            return $this->db->select('transport_vehicles', [], 'registration_number.asc');
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    private function fetchAssignments(): array
    {
        try {
            return $this->db->raw(
                "SELECT ta.*,
                        u.first_name as student_first_name, u.last_name as student_last_name,
                        sp.admission_no as student_admission_no,
                        tr.name as route_name
                 FROM transport_assignments ta
                 LEFT JOIN users u ON ta.student_id = u.id
                 LEFT JOIN student_profiles sp ON u.id = sp.user_id
                 LEFT JOIN transport_routes tr ON ta.route_id = tr.id
                 ORDER BY ta.created_at DESC"
            );
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    private function fetchStudents(): array
    {
        try {
            return $this->db->raw(
                "SELECT u.id, u.first_name, u.last_name, sp.admission_no
                 FROM users u
                 LEFT JOIN student_profiles sp ON u.id = sp.user_id
                 WHERE u.user_type = 'student'
                 ORDER BY u.first_name"
            );
        } catch (\RuntimeException $e) {
            return [];
        }
    }

    private function fetchTransportStats(): array
    {
        try {
            $routes = $this->db->raw("SELECT COUNT(*) as cnt FROM transport_routes");
            $vehicles = $this->db->raw("SELECT COUNT(*) as cnt FROM transport_vehicles");
            $assigned = $this->db->raw("SELECT COUNT(*) as cnt FROM transport_assignments");
            $active = $this->db->raw("SELECT COUNT(*) as cnt FROM transport_routes WHERE status = 'active'");

            return [
                'total_routes'    => (int) ($routes[0]['cnt'] ?? 0),
                'total_vehicles'  => (int) ($vehicles[0]['cnt'] ?? 0),
                'assigned_students'=> (int) ($assigned[0]['cnt'] ?? 0),
                'active_routes'   => (int) ($active[0]['cnt'] ?? 0),
            ];
        } catch (\RuntimeException $e) {
            return ['total_routes' => 0, 'total_vehicles' => 0, 'assigned_students' => 0, 'active_routes' => 0];
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Validation
    // ─────────────────────────────────────────────────────────

    private function validateRoute(array $input): array
    {
        $errors = [];
        if (empty($input['name'])) $errors['name'] = 'Route name is required.';
        return $errors;
    }

    private function validateVehicle(array $input): array
    {
        $errors = [];
        if (empty($input['registration_number'])) $errors['registration_number'] = 'Registration number is required.';
        if (empty($input['capacity']) || (int) $input['capacity'] < 1) $errors['capacity'] = 'Capacity must be at least 1.';
        return $errors;
    }

    private function requestJson(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    // ─────────────────────────────────────────────────────────
    //  Web Route Stubs
    // ─────────────────────────────────────────────────────────

    public function routes(): void { $this->index(); }
    public function createRoute(): void { $this->index(); }
    public function storeRoute(): void { $this->redirect('/transport'); }
    public function vehicles(): void { $this->index(); }
    public function createVehicle(): void { $this->index(); }
    public function storeVehicle(): void { $this->redirect('/transport'); }
    public function assignments(): void { $this->index(); }

    // ─────────────────────────────────────────────────────────
    //  Missing API Route Stubs
    // ─────────────────────────────────────────────────────────

    public function apiShowRoute(): void
    {
        $this->requireAuth();
        $id = $this->input('id', '');
        $this->success($this->db->find('transport_routes', $id));
    }

    public function apiDeleteRoute(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin']);
        $id = $this->input('id', '');
        if (empty($id)) { $this->error('Route ID is required.', 422); return; }
        $this->db->deleteById('transport_routes', $id);
        $this->success(null, 'Route deleted.');
    }

    public function apiShowVehicle(): void
    {
        $this->requireAuth();
        $id = $this->input('id', '');
        $this->success($this->db->find('transport_vehicles', $id));
    }

    public function apiDeleteVehicle(): void
    {
        $this->requireAuth();
        $this->requireRole(['Super Admin', 'School Admin']);
        $id = $this->input('id', '');
        if (empty($id)) { $this->error('Vehicle ID is required.', 422); return; }
        $this->db->deleteById('transport_vehicles', $id);
        $this->success(null, 'Vehicle deleted.');
    }
}
