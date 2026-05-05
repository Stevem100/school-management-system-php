<?php

declare(strict_types=1);

namespace Core;

/**
 * Base Controller
 *
 * All application controllers should extend this class. It provides
 * convenient access to the database, request, response, auth,
 * and common helper methods.
 */
abstract class Controller
{
    /** @var Database Database instance */
    protected Database $db;

    /**
     * Create a new controller instance.
     *
     * Automatically initializes the MySQL PDO Database connection from app config.
     */
    public function __construct()
    {
        $this->db = new Database(
            (string) config('db_host', 'localhost'),
            (string) config('db_port', '3306'),
            (string) config('db_name', 'school_erp'),
            (string) config('db_user', 'root'),
            (string) config('db_password', ''),
        );
    }

    /**
     * Render a view with optional data.
     *
     * @param string $path View path (dot notation: 'layouts.app' → views/layouts/app.php)
     * @param array  $data Data to pass to the view
     * @return void
     */
    protected function view(string $path, array $data = []): void
    {
        Response::view($path, $data);
    }

    /**
     * Render a view inside the application layout.
     *
     * Captures the view output and passes it as $content to views/layouts/app.php
     * along with the provided $data array.
     *
     * @param string $viewPath View path (dot notation: 'dashboard.index' → views/dashboard/index.php)
     * @param array  $data     Data to pass to both the view and layout
     * @return void
     */
    protected function renderWithLayout(string $viewPath, array $data = []): void
    {
        // Convert dot notation to file path
        $dotPath = str_replace('.', '/', $viewPath);
        $viewFile = BASE_PATH . '/views/' . $dotPath . '.php';
        $layoutFile = BASE_PATH . '/views/layouts/app.php';

        // Capture the view content
        ob_start();
        if (file_exists($viewFile)) {
            extract($data, EXTR_SKIP);
            include $viewFile;
        }
        $content = ob_get_clean();

        // Pass content to layout
        $layoutData = array_merge($data, ['content' => $content]);

        if (file_exists($layoutFile)) {
            extract($layoutData, EXTR_SKIP);
            include $layoutFile;
        }
    }

    /**
     * Return a JSON response.
     *
     * @param mixed $data   Data to encode
     * @param int   $status HTTP status code
     * @return void
     */
    protected function json($data, int $status = 200): void
    {
        Response::json($data, $status);
    }

    /**
     * Return a JSON success response.
     *
     * @param mixed  $data    Response data
     * @param string $message Success message
     * @param int    $status  HTTP status code
     * @return void
     */
    protected function success($data = null, string $message = 'Success', int $status = 200): void
    {
        Response::success($data, $message, $status);
    }

    /**
     * Return a JSON error response.
     *
     * @param string $message Error message
     * @param int    $status  HTTP status code
     * @param mixed  $errors  Additional error details
     * @return void
     */
    protected function error(string $message = 'Error', int $status = 400, $errors = null): void
    {
        Response::error($message, $status, $errors);
    }

    /**
     * Redirect to a URL.
     *
     * @param string $url Target URL
     * @return void
     */
    protected function redirect(string $url): void
    {
        Response::redirect($url);
    }

    /**
     * Redirect back to the previous page.
     *
     * @return void
     */
    protected function back(): void
    {
        Response::back();
    }

    /**
     * Get an Auth instance.
     *
     * @return Auth
     */
    protected function auth(): Auth
    {
        return new Auth($this->db);
    }

    /**
     * Require authentication. Redirects to /login if not authenticated.
     *
     * @return void
     */
    protected function requireAuth(): void
    {
        $auth = $this->auth();

        if (!$auth->check()) {
            // For AJAX/API requests, return JSON error
            if (Request::isAjax() || Request::expectsJson()) {
                Response::unauthorized('Authentication required.');
            }

            // For web requests, store intended URL and redirect to login
            Session::set('intended_url', Request::path());
            Session::flash('error', 'Please log in to access this page.');
            Response::redirect('/login');
        }
    }

    /**
     * Require specific role(s). Returns 403 if user doesn't have the role.
     *
     * @param string|array $roles One or more required role names
     * @return void
     */
    protected function requireRole($roles): void
    {
        $this->requireAuth();

        $auth = $this->auth();
        $rolesArray = is_array($roles) ? $roles : [$roles];

        if (!$auth->hasAnyRole($rolesArray)) {
            // For AJAX/API requests, return JSON error
            if (Request::isAjax() || Request::expectsJson()) {
                Response::forbidden('You do not have permission to access this resource.');
            }

            Session::flash('error', 'You do not have permission to access this page.');
            Response::redirect('/dashboard');
        }
    }

    /**
     * Get input from the request.
     *
     * @param string $key     Input key
     * @param mixed  $default Default value
     * @return mixed
     */
    protected function input(string $key, $default = null)
    {
        return Request::input($key, $default);
    }

    /**
     * Get all input data.
     *
     * @return array
     */
    protected function all(): array
    {
        return Request::all();
    }

    /**
     * Get the current authenticated user.
     *
     * @return array|null
     */
    protected function currentUser(): ?array
    {
        return Session::get('user');
    }

    /**
     * Get the current user's ID.
     *
     * @return string|null
     */
    protected function currentUserId(): mixed
    {
        $user = $this->currentUser();
        return $user['id'] ?? null;
    }

    /**
     * Validate required fields from the request.
     *
     * @param array $rules Associative array: field => 'required'|'email'|'numeric'|...
     * @return array       ['valid' => bool, 'errors' => array]
     */
    protected function validate(array $rules): array
    {
        $errors = [];
        $data = Request::all();

        foreach ($rules as $field => $ruleString) {
            $fieldRules = explode('|', $ruleString);
            $value = $data[$field] ?? null;
            $label = ucfirst(str_replace('_', ' ', $field));

            foreach ($fieldRules as $rule) {
                // Handle "required" rule
                if ($rule === 'required' && ($value === null || $value === '')) {
                    $errors[$field] = "{$label} is required.";
                    break;
                }

                // Handle "email" rule
                if ($rule === 'email' && $value !== null && $value !== '') {
                    if (!filter_var((string) $value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field] = "{$label} must be a valid email address.";
                    }
                }

                // Handle "numeric" rule
                if ($rule === 'numeric' && $value !== null && $value !== '') {
                    if (!is_numeric((string) $value)) {
                        $errors[$field] = "{$label} must be a number.";
                    }
                }

                // Handle "min:n" rule
                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    if ($value !== null && strlen((string) $value) < $min) {
                        $errors[$field] = "{$label} must be at least {$min} characters.";
                    }
                }

                // Handle "max:n" rule
                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if ($value !== null && strlen((string) $value) > $max) {
                        $errors[$field] = "{$label} must not exceed {$max} characters.";
                    }
                }

                // Handle "unique:table,column" rule
                if (str_starts_with($rule, 'unique:')) {
                    $parts = explode(',', substr($rule, 7));
                    $table = $parts[0] ?? '';
                    $column = $parts[1] ?? $field;

                    if ($value !== null && $value !== '') {
                        try {
                            $existing = $this->db->single($table, [
                                $column => ['eq' => $value],
                            ]);
                            if ($existing !== null) {
                                $errors[$field] = "{$label} already exists.";
                            }
                        } catch (\RuntimeException $e) {
                            // Silently ignore DB errors during validation
                        }
                    }
                }

                // Handle "confirmed" rule (field_confirmation must match)
                if ($rule === 'confirmed') {
                    $confirmation = $data[$field . '_confirmation'] ?? null;
                    if ($value !== null && $value !== $confirmation) {
                        $errors[$field] = "{$label} confirmation does not match.";
                    }
                }
            }
        }

        return [
            'valid'  => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Paginate database query results.
     *
     * @param string $table   Table name
     * @param int    $page    Current page number (1-based)
     * @param int    $perPage Items per page
     * @param array  $filters Filter conditions
     * @param string|null $order Ordering string
     * @return array   ['data' => [], 'total' => int, 'page' => int, 'per_page' => int, 'last_page' => int]
     */
    protected function paginate(
        string $table,
        int $page = 1,
        int $perPage = 15,
        array $filters = [],
        ?string $order = null
    ): array {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $data = $this->db->select($table, $filters, $order, $perPage, $offset);
        $total = $this->db->count($table, $filters);
        $lastPage = (int) ceil($total / $perPage);

        return [
            'data'      => $data,
            'total'     => $total,
            'page'      => $page,
            'perPage'   => $perPage,
            'lastPage'  => $lastPage,
        ];
    }
}
