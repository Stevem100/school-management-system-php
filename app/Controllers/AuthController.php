<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Auth;
use Core\Request;
use Core\Session;
use Core\Response;

/**
 * AuthController
 *
 * Handles authentication: login, logout, session management.
 * Uses MySQL database via Core\Auth and Core\Session.
 */
class AuthController extends Controller
{
    /**
     * Show the login page.
     * If the user is already authenticated, redirect to /dashboard.
     */
    public function login(): void
    {
        // If already logged in, redirect
        if ($this->auth()->check()) {
            $this->redirect('/dashboard');
            return;
        }

        $error    = Session::getFlash('error', '');
        $oldEmail = Session::getFlash('old_email', '');
        $csrfToken = csrf_token();

        // Render standalone login view (no layout wrapper)
        $viewFile = dirname(dirname(__DIR__)) . '/views/auth/login.php';
        if (file_exists($viewFile)) {
            extract([
                'error'     => $error,
                'oldEmail'  => $oldEmail,
                'csrfToken' => $csrfToken,
                'appName'   => config('app_name', 'School Management System'),
            ], EXTR_SKIP);
            require $viewFile;
        } else {
            echo '<h1>Login page not found</h1>';
        }
    }

    /**
     * Process login form POST.
     */
    public function doLogin(): void
    {
        // CSRF validation
        if (!verify_csrf()) {
            Session::flash('error', 'Invalid security token. Please try again.');
            $this->redirect('/login');
            return;
        }

        $email    = $this->input('email', '');
        $password = $this->input('password', '');

        // Basic validation
        if (empty($email) || empty($password)) {
            Session::flash('error', 'Please enter both email and password.');
            Session::flash('old_email', $email);
            $this->redirect('/login');
            return;
        }

        // Sanitize email
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please enter a valid email address.');
            Session::flash('old_email', $email);
            $this->redirect('/login');
            return;
        }

        // Attempt login
        $auth = $this->auth();
        $result = $auth->login($email, $password);

        if ($result['success']) {
            Session::flash('success', 'Welcome back! You have been logged in successfully.');
            $this->redirect('/dashboard');
        } else {
            Session::flash('error', $result['message'] ?? 'Invalid email or password. Please try again.');
            Session::flash('old_email', $email);
            $this->redirect('/login');
        }
    }

    /**
     * Logout the current user and redirect to /login.
     */
    public function logout(): void
    {
        $this->auth()->logout();
        Session::flash('success', 'You have been logged out successfully.');
        $this->redirect('/login');
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes (JSON responses)
    // ─────────────────────────────────────────────────────────

    /**
     * JSON Login endpoint.
     * POST /api/auth/login
     */
    public function apiLogin(): void
    {
        $input = $this->requestJson();

        $email    = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->error('Email and password are required.', 422);
            return;
        }

        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Please provide a valid email address.', 422);
            return;
        }

        $auth = $this->auth();
        $result = $auth->login($email, $password);

        if ($result['success']) {
            $this->success([
                'token' => $result['token'] ?? Session::id(),
                'user'  => $result['user'] ?? $auth->user(),
                'roles' => $result['roles'] ?? [],
            ], 'Login successful.');
        } else {
            $this->error($result['message'] ?? 'Invalid email or password.', 401);
        }
    }

    /**
     * Return current authenticated user as JSON.
     * GET /api/auth/me
     */
    public function apiMe(): void
    {
        $auth = $this->auth();

        if (!$auth->check()) {
            $this->error('Not authenticated.', 401);
            return;
        }

        $this->success($auth->user());
    }

    /**
     * JSON Logout endpoint.
     * POST /api/auth/logout
     */
    public function apiLogout(): void
    {
        $this->auth()->logout();
        $this->success(null, 'Logged out successfully.');
    }

    // ─────────────────────────────────────────────────────────
    //  Registration (placeholder)
    // ─────────────────────────────────────────────────────────

    public function register(): void
    {
        $this->redirect('/login');
    }

    public function doRegister(): void
    {
        $this->redirect('/login');
    }

    public function apiRegister(): void
    {
        $this->error('Registration is not available via API.', 403);
    }

    public function forgotPassword(): void
    {
        $this->redirect('/login');
    }

    public function doForgotPassword(): void
    {
        $this->redirect('/login');
    }

    public function apiForgotPassword(): void
    {
        $this->error('Please contact the administrator to reset your password.', 403);
    }

    public function resetPassword(): void
    {
        $this->redirect('/login');
    }

    public function doResetPassword(): void
    {
        $this->redirect('/login');
    }

    public function apiResetPassword(): void
    {
        $this->error('Please contact the administrator to reset your password.', 403);
    }

    // ─────────────────────────────────────────────────────────
    //  Private Helpers
    // ─────────────────────────────────────────────────────────

    private function requestJson(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}
