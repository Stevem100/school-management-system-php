<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Core\CSRF;
use App\Core\View;

/**
 * AuthController
 *
 * Handles authentication: login, logout, session management.
 * All authentication is proxied through the Supabase REST API.
 */
class AuthController
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var CSRF
     */
    private $csrf;

    /**
     * @var View
     */
    private $view;

    /**
     * Supabase API configuration
     */
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

    /**
     * Show the login page.
     * If the user is already authenticated, redirect to /dashboard.
     */
    public function login(): void
    {
        // If already logged in, redirect
        if ($this->auth->check()) {
            $this->redirect('/dashboard');
            return;
        }

        $error    = $this->session->getFlash('error');
        $oldEmail = $this->session->getFlash('old_email');
        $csrfToken = $this->csrf->generate();

        // Render standalone login view (no layout wrapper)
        $this->view->render('auth/login', [
            'error'      => $error,
            'oldEmail'   => $oldEmail,
            'csrfToken'  => $csrfToken,
        ]);
    }

    /**
     * Process login form POST.
     *
     * Validates email & password, attempts authentication via Auth::login().
     * On success → redirect to /dashboard.
     * On failure → flash error, redirect back to /login with old input.
     */
    public function doLogin(): void
    {
        // CSRF validation
        $token = $this->request->post('_token', '');
        if (!$this->csrf->validate($token)) {
            $this->session->flash('error', 'Invalid security token. Please try again.');
            $this->redirect('/login');
            return;
        }

        $email    = $this->request->post('email', '');
        $password = $this->request->post('password', '');

        // Basic validation
        if (empty($email) || empty($password)) {
            $this->session->flash('error', 'Please enter both email and password.');
            $this->session->flash('old_email', $email);
            $this->redirect('/login');
            return;
        }

        // Sanitize email
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->flash('error', 'Please enter a valid email address.');
            $this->session->flash('old_email', $email);
            $this->redirect('/login');
            return;
        }

        // Attempt login
        $result = $this->auth->login($email, $password);

        if ($result['success']) {
            $this->session->flash('success', 'Welcome back! You have been logged in successfully.');
            $this->redirect('/dashboard');
        } else {
            $this->session->flash('error', $result['error'] ?? 'Invalid email or password. Please try again.');
            $this->session->flash('old_email', $email);
            $this->redirect('/login');
        }
    }

    /**
     * Logout the current user and redirect to /login.
     */
    public function logout(): void
    {
        $this->auth->logout();
        $this->session->flash('success', 'You have been logged out successfully.');
        $this->redirect('/login');
    }

    // ─────────────────────────────────────────────────────────
    //  API Routes (JSON responses)
    // ─────────────────────────────────────────────────────────

    /**
     * JSON Login endpoint.
     * POST /api/auth/login
     *
     * Expected JSON body: { "email": "...", "password": "..." }
     *
     * Returns:
     *  - Success: { "success": true, "data": { "token": "...", "user": {...} } }
     *  - Failure: { "success": false, "error": "..." }
     */
    public function apiLogin(): void
    {
        Response::jsonHeaders();

        $input = $this->request->jsonBody();

        $email    = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            Response::json([
                'success' => false,
                'error'   => 'Email and password are required.',
            ], 422);
            return;
        }

        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::json([
                'success' => false,
                'error'   => 'Please provide a valid email address.',
            ], 422);
            return;
        }

        $result = $this->auth->login($email, $password);

        if ($result['success']) {
            Response::json([
                'success' => true,
                'data'    => [
                    'token' => $result['token'] ?? $this->session->getId(),
                    'user'  => $result['user']  ?? $this->auth->user(),
                ],
            ], 200);
        } else {
            Response::json([
                'success' => false,
                'error'   => $result['error'] ?? 'Invalid email or password.',
            ], 401);
        }
    }

    /**
     * Return current authenticated user as JSON.
     * GET /api/auth/me
     */
    public function apiMe(): void
    {
        Response::jsonHeaders();

        if (!$this->auth->check()) {
            Response::json([
                'success' => false,
                'error'   => 'Not authenticated.',
            ], 401);
            return;
        }

        $user = $this->auth->user();

        Response::json([
            'success' => true,
            'data'    => $user,
        ], 200);
    }

    /**
     * JSON Logout endpoint.
     * POST /api/auth/logout
     */
    public function apiLogout(): void
    {
        Response::jsonHeaders();

        $this->auth->logout();

        Response::json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ], 200);
    }

    // ─────────────────────────────────────────────────────────
    //  Private Helpers
    // ─────────────────────────────────────────────────────────

    /**
     * Redirect to a given URL.
     */
    private function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
