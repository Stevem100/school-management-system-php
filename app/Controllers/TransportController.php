<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Core\CSRF;
use App\Core\View;

/**
 * TransportController
 *
 * Manages school transport: routes, vehicles, and student assignments.
 * CRUD for transport_routes, transport_vehicles, and transport_assignments.
 */
class TransportController
{
    private $auth;
    private $session;
    private $request;
    private $csrf;
    private $view;
    private $supabaseUrl;
    private $supabaseKey;

    public function __construct()
    {
        $this->auth    = new Auth();
        $this->session = new Session();
        $this->request = new Request();
        $this->csrf    = new CSRF();
        $this->view    = new View();

        $this->supabaseUrl = getenv('SUPABASE_URL') ?: 'https://example.supabase.co';
        $this->supabaseKey = getenv('SUPABASE_ANON_KEY') ?: '';
    }

    // ─────────────────────────────────────────────────────────
    //  Web Routes
    // ─────────────────────────────────────────────────────────

    public function index(): void
    {
        if (!$this->auth->check()) {
            $this->session->flash('error', 'Please log in to access this page.');
            $this->redirect('/login');
            return;
        }

        $user = $this->auth->user();

        $routes      = $this->fetchRoutes($user);
        $vehicles    = $this->fetchVehicles($user);
        $assignments = $this->fetchAssignments($user);
        $students    = $this->fetchStudents($user);
        $stats       = $this->fetchTransportStats($user);

        $flashSuccess = $this->session->getFlash('success');
        $flashError   = $this->session->getFlash('error');

        $this->view->renderWithLayout('transport/index', 'layouts/app', [
            'pageTitle'    => 'Transport',
            'user'         => $user,
            'currentPage'  => 'transport',
            'routes'       => $routes,
            'vehicles'     => $vehicles,
            'assignments'  => $assignments,
            'students'     => $students,
            'stats'        => $stats,
            'flashSuccess' => $flashSuccess,
            'flashError'   => $flashError,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes
    // ─────────────────────────────────────────────────────────

    public function apiRoutes(): void
    {
        Response::jsonHeaders();
        if (!$this->auth->check()) { Response::json(['success' => false, 'error' => 'Not authenticated.'], 401); return; }
        $routes = $this->fetchRoutes($this->auth->user());
        Response::json(['success' => true, 'data' => $routes], 200);
    }

    public function apiStoreRoute(): void
    {
        Response::jsonHeaders();
        if (!$this->auth->check()) { Response::json(['success' => false, 'error' => 'Not authenticated.'], 401); return; }

        $input = $this->request->jsonBody();
        $user  = $this->auth->user();

        $errors = $this->validateRoute($input);
        if (!empty($errors)) { Response::json(['success' => false, 'error' => 'Validation failed.', 'errors' => $errors], 422); return; }

        $data = [
            'school_id' => $user['school_id'] ?? 1,
            'name'      => $input['name'],
            'description' => $input['description'] ?? '',
            'stops'     => json_encode($input['stops'] ?? []),
            'driver_id' => $input['driver_id'] ?? null,
            'vehicle_id'=> $input['vehicle_id'] ?? null,
            'status'    => $input['status'] ?? 'active',
        ];

        $result = $this->supabaseInsert('transport_routes', $data);
        $result ? Response::json(['success' => true, 'data' => $result, 'message' => 'Route created successfully.'], 201)
                : Response::json(['success' => false, 'error' => 'Failed to create route.'], 500);
    }

    public function apiUpdateRoute(): void
    {
        Response::jsonHeaders();
        if (!$this->auth->check()) { Response::json(['success' => false, 'error' => 'Not authenticated.'], 401); return; }

        $input = $this->request->jsonBody();
        $id    = $input['id'] ?? '';
        if (empty($id)) { Response::json(['success' => false, 'error' => 'Route ID is required.'], 422); return; }

        $data = array_filter([
            'name'        => $input['name'] ?? null,
            'description' => $input['description'] ?? null,
            'stops'       => isset($input['stops']) ? json_encode($input['stops']) : null,
            'driver_id'   => $input['driver_id'] ?? null,
            'vehicle_id'  => $input['vehicle_id'] ?? null,
            'status'      => $input['status'] ?? null,
        ], fn($v) => $v !== null);

        $result = $this->supabaseUpdate('transport_routes', $id, $data);
        $result ? Response::json(['success' => true, 'data' => $result, 'message' => 'Route updated.'], 200)
                : Response::json(['success' => false, 'error' => 'Failed to update route.'], 500);
    }

    public function apiDestroyRoute(): void
    {
        Response::jsonHeaders();
        if (!$this->auth->check()) { Response::json(['success' => false, 'error' => 'Not authenticated.'], 401); return; }
        $id = $this->request->get('id', '');
        if (empty($id)) { Response::json(['success' => false, 'error' => 'Route ID is required.'], 422); return; }
        $this->supabaseDelete('transport_assignments', 'route_id', $id);
        $result = $this->supabaseDelete('transport_routes', 'id', $id);
        $result ? Response::json(['success' => true, 'message' => 'Route deleted.'], 200)
                : Response::json(['success' => false, 'error' => 'Failed to delete route.'], 500);
    }

    public function apiVehicles(): void
    {
        Response::jsonHeaders();
        if (!$this->auth->check()) { Response::json(['success' => false, 'error' => 'Not authenticated.'], 401); return; }
        Response::json(['success' => true, 'data' => $this->fetchVehicles($this->auth->user())], 200);
    }

    public function apiStoreVehicle(): void
    {
        Response::jsonHeaders();
        if (!$this->auth->check()) { Response::json(['success' => false, 'error' => 'Not authenticated.'], 401); return; }

        $input = $this->request->jsonBody();
        $user  = $this->auth->user();

        $errors = $this->validateVehicle($input);
        if (!empty($errors)) { Response::json(['success' => false, 'error' => 'Validation failed.', 'errors' => $errors], 422); return; }

        $data = [
            'school_id'         => $user['school_id'] ?? 1,
            'registration_number' => $input['registration_number'],
            'type'              => $input['type'] ?? 'bus',
            'capacity'          => (int) ($input['capacity'] ?? 40),
            'driver_name'       => $input['driver_name'] ?? '',
            'driver_phone'      => $input['driver_phone'] ?? '',
            'status'            => $input['status'] ?? 'active',
        ];

        $result = $this->supabaseInsert('transport_vehicles', $data);
        $result ? Response::json(['success' => true, 'data' => $result, 'message' => 'Vehicle added.'], 201)
                : Response::json(['success' => false, 'error' => 'Failed to add vehicle.'], 500);
    }

    public function apiUpdateVehicle(): void
    {
        Response::jsonHeaders();
        if (!$this->auth->check()) { Response::json(['success' => false, 'error' => 'Not authenticated.'], 401); return; }

        $input = $this->request->jsonBody();
        $id    = $input['id'] ?? '';
        if (empty($id)) { Response::json(['success' => false, 'error' => 'Vehicle ID is required.'], 422); return; }

        $data = array_filter([
            'registration_number' => $input['registration_number'] ?? null,
            'type'      => $input['type'] ?? null,
            'capacity'  => isset($input['capacity']) ? (int) $input['capacity'] : null,
            'driver_name'=> $input['driver_name'] ?? null,
            'driver_phone' => $input['driver_phone'] ?? null,
            'status'    => $input['status'] ?? null,
        ], fn($v) => $v !== null);

        $result = $this->supabaseUpdate('transport_vehicles', $id, $data);
        $result ? Response::json(['success' => true, 'data' => $result, 'message' => 'Vehicle updated.'], 200)
                : Response::json(['success' => false, 'error' => 'Failed to update vehicle.'], 500);
    }

    public function apiDestroyVehicle(): void
    {
        Response::jsonHeaders();
        if (!$this->auth->check()) { Response::json(['success' => false, 'error' => 'Not authenticated.'], 401); return; }
        $id = $this->request->get('id', '');
        if (empty($id)) { Response::json(['success' => false, 'error' => 'Vehicle ID is required.'], 422); return; }
        $result = $this->supabaseDelete('transport_vehicles', 'id', $id);
        $result ? Response::json(['success' => true, 'message' => 'Vehicle deleted.'], 200)
                : Response::json(['success' => false, 'error' => 'Failed to delete vehicle.'], 500);
    }

    public function apiAssignStudent(): void
    {
        Response::jsonHeaders();
        if (!$this->auth->check()) { Response::json(['success' => false, 'error' => 'Not authenticated.'], 401); return; }

        $input = $this->request->jsonBody();
        if (empty($input['student_id']) || empty($input['route_id'])) {
            Response::json(['success' => false, 'error' => 'Student and route are required.'], 422); return;
        }

        $data = [
            'student_id'   => $input['student_id'],
            'route_id'     => $input['route_id'],
            'pickup_point' => $input['pickup_point'] ?? '',
            'drop_point'   => $input['drop_point'] ?? '',
        ];

        $result = $this->supabaseInsert('transport_assignments', $data);
        $result ? Response::json(['success' => true, 'data' => $result, 'message' => 'Student assigned to route.'], 201)
                : Response::json(['success' => false, 'error' => 'Failed to assign student.'], 500);
    }

    public function apiRemoveAssignment(): void
    {
        Response::jsonHeaders();
        if (!$this->auth->check()) { Response::json(['success' => false, 'error' => 'Not authenticated.'], 401); return; }
        $id = $this->request->get('id', '');
        if (empty($id)) { Response::json(['success' => false, 'error' => 'Assignment ID is required.'], 422); return; }
        $result = $this->supabaseDelete('transport_assignments', 'id', $id);
        $result ? Response::json(['success' => true, 'message' => 'Assignment removed.'], 200)
                : Response::json(['success' => false, 'error' => 'Failed to remove assignment.'], 500);
    }

    // ─────────────────────────────────────────────────────────
    //  Private Data Fetching
    // ─────────────────────────────────────────────────────────

    private function fetchRoutes(array $user): array
    {
        $data = $this->supabaseFetch('transport_routes?select=*&order=name');
        if (empty($data)) {
            return [
                ['id' => '1', 'name' => 'Westlands Route', 'description' => 'Via Waiyaki Way to Westlands', 'stops' => ['Kangemi', 'Kabete', 'Westlands'], 'driver_name' => 'John Kamau', 'vehicle_reg' => 'KBA 234J', 'status' => 'active'],
                ['id' => '2', 'name' => 'Eastlands Route', 'description' => 'Via Jogoo Road to Eastlands', 'stops' => ['Buruburu', 'Umoja', 'Donholm'], 'driver_name' => 'Peter Odhiambo', 'vehicle_reg' => 'KCA 567K', 'status' => 'active'],
                ['id' => '3', 'name' => 'Karen Route', 'description' => 'Via Ngong Road to Karen', 'stops' => ['Langata', 'Karen', 'Bomas'], 'driver_name' => 'Samuel Kibet', 'vehicle_reg' => 'KDB 890L', 'status' => 'active'],
                ['id' => '4', 'name' => 'Thika Route', 'description' => 'Via Thika Road', 'stops' => ['Roysambu', 'Githurai', 'Thika'], 'driver_name' => '', 'vehicle_reg' => '', 'status' => 'inactive'],
            ];
        }
        return $data;
    }

    private function fetchVehicles(array $user): array
    {
        $data = $this->supabaseFetch('transport_vehicles?select=*&order=registration_number');
        if (empty($data)) {
            return [
                ['id' => '1', 'registration_number' => 'KBA 234J', 'type' => 'bus', 'capacity' => 52, 'driver_name' => 'John Kamau', 'driver_phone' => '0722 123456', 'status' => 'active'],
                ['id' => '2', 'registration_number' => 'KCA 567K', 'type' => 'bus', 'capacity' => 52, 'driver_name' => 'Peter Odhiambo', 'driver_phone' => '0733 654321', 'status' => 'active'],
                ['id' => '3', 'registration_number' => 'KDB 890L', 'type' => 'van', 'capacity' => 16, 'driver_name' => 'Samuel Kibet', 'driver_phone' => '0711 987654', 'status' => 'active'],
                ['id' => '4', 'registration_number' => 'KEC 123M', 'type' => 'van', 'capacity' => 16, 'driver_name' => 'David Mutua', 'driver_phone' => '0700 111222', 'status' => 'maintenance'],
            ];
        }
        return $data;
    }

    private function fetchAssignments(array $user): array
    {
        $data = $this->supabaseFetch('transport_assignments?select=*,student:students(first_name,last_name,admission_number),route:transport_routes(name)&order=created_at.desc');
        if (empty($data)) {
            return [
                ['id' => '1', 'student_id' => '1', 'route_id' => '1', 'student' => ['first_name' => 'Amina', 'last_name' => 'Hassan', 'admission_number' => 'ADM/2024/001'], 'route' => ['name' => 'Westlands Route'], 'pickup_point' => 'Kangemi Stage', 'drop_point' => 'Westlands Stage'],
                ['id' => '2', 'student_id' => '2', 'route_id' => '2', 'student' => ['first_name' => 'Brian', 'last_name' => 'Njorge', 'admission_number' => 'ADM/2024/002'], 'route' => ['name' => 'Eastlands Route'], 'pickup_point' => 'Umoja Stage', 'drop_point' => 'Donholm Stage'],
                ['id' => '3', 'student_id' => '3', 'route_id' => '3', 'student' => ['first_name' => 'Mary', 'last_name' => 'Wanjiku', 'admission_number' => 'ADM/2024/003'], 'route' => ['name' => 'Karen Route'], 'pickup_point' => 'Langata Stage', 'drop_point' => 'Karen Stage'],
            ];
        }
        return $data;
    }

    private function fetchStudents(array $user): array
    {
        $students = $this->supabaseFetch('users?select=id,first_name,last_name,admission_number&role=eq.Student&order=first_name');
        if (empty($students)) {
            return [
                ['id' => '1', 'first_name' => 'Amina', 'last_name' => 'Hassan', 'admission_number' => 'ADM/2024/001'],
                ['id' => '2', 'first_name' => 'Brian', 'last_name' => 'Njorge', 'admission_number' => 'ADM/2024/002'],
                ['id' => '3', 'first_name' => 'Mary', 'last_name' => 'Wanjiku', 'admission_number' => 'ADM/2024/003'],
            ];
        }
        return $students;
    }

    private function fetchTransportStats(array $user): array
    {
        return ['total_routes' => 8, 'total_vehicles' => 6, 'assigned_students' => 120, 'active_routes' => 6];
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

    // ─────────────────────────────────────────────────────────
    //  Supabase Helpers
    // ─────────────────────────────────────────────────────────

    private function supabaseFetch(string $query): ?array
    {
        $url = "{$this->supabaseUrl}/rest/v1/{$query}&apikey=" . urlencode($this->supabaseKey);
        $ctx = stream_context_create(['http' => ['method' => 'GET', 'header' => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\n", 'timeout' => 10, 'ignore_errors' => true]]);
        $r = @file_get_contents($url, false, $ctx);
        return $r === false ? null : json_decode($r, true);
    }

    private function supabaseInsert(string $table, array $data): ?array
    {
        $url = "{$this->supabaseUrl}/rest/v1/{$table}";
        $ctx = stream_context_create(['http' => ['method' => 'POST', 'header' => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\nPrefer: return=representation", 'content' => json_encode($data), 'timeout' => 10, 'ignore_errors' => true]]);
        $r = @file_get_contents($url, false, $ctx);
        if ($r === false) return null;
        $res = json_decode($r, true);
        return is_array($res) && !empty($res) ? $res[0] : null;
    }

    private function supabaseUpdate(string $table, string $id, array $data): ?array
    {
        $url = "{$this->supabaseUrl}/rest/v1/{$table}?id=eq.{$id}";
        $ctx = stream_context_create(['http' => ['method' => 'PATCH', 'header' => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\nPrefer: return=representation", 'content' => json_encode($data), 'timeout' => 10, 'ignore_errors' => true]]);
        $r = @file_get_contents($url, false, $ctx);
        if ($r === false) return null;
        $res = json_decode($r, true);
        return is_array($res) && !empty($res) ? $res[0] : null;
    }

    private function supabaseDelete(string $table, string $column, string $value): bool
    {
        $url = "{$this->supabaseUrl}/rest/v1/{$table}?{$column}=eq.{$value}";
        $ctx = stream_context_create(['http' => ['method' => 'DELETE', 'header' => "Content-Type: application/json\r\napikey: {$this->supabaseKey}\r\n", 'timeout' => 10, 'ignore_errors' => true]]);
        return @file_get_contents($url, false, $ctx) !== false;
    }

    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
