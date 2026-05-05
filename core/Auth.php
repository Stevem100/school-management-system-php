<?php

declare(strict_types=1);

namespace Core;

use Core\Database;
use Core\Session;

/**
 * Authentication System
 *
 * Handles user login/logout, session management with the database,
 * role-based access control, and credential verification.
 *
 * Password hashing: SHA-256(password + salt)
 * Session tokens: uniqid('sess_', true)
 */
class Auth
{
    /** @var Database Database instance for user/session queries */
    private Database $db;

    /** @var string Password salt from configuration */
    private string $salt;

    /**
     * Create a new Auth instance.
     *
     * @param Database $db Database instance
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->salt = (string) config('password_salt', '_school_erp_salt');
    }

    /**
     * Attempt to log in a user with email and password.
     *
     * Verifies credentials against the users table, creates a session token
     * in the sessions table, and stores user data in the PHP session.
     *
     * @param string $email    User email
     * @param string $password Plain text password
     * @return array           ['success' => bool, 'message' => string, 'user' => ?array]
     */
    public function login(string $email, string $password): array
    {
        try {
            // Find user by email with roles
            $user = $this->db->query(
                'users',
                'GET',
                null,
                ['email' => ['eq' => $email]],
                null,
                1
            );

            if (empty($user)) {
                return [
                    'success' => false,
                    'message' => 'Invalid email or password.',
                    'user'    => null,
                ];
            }

            $user = $user[0];

            // Hash the provided password with the same salt
            $hashedPassword = hash('sha256', $password . $this->salt);

            if (!hash_equals($hashedPassword, (string) ($user['passwordHash'] ?? ''))) {
                return [
                    'success' => false,
                    'message' => 'Invalid email or password.',
                    'user'    => null,
                ];
            }

            // Check if account is active
            if (isset($user['isActive']) && $user['isActive'] === false) {
                return [
                    'success' => false,
                    'message' => 'Your account has been deactivated. Please contact the administrator.',
                    'user'    => null,
                ];
            }

            // Fetch user roles from user_roles joined with roles
            $roles = $this->db->query(
                'user_roles',
                'GET',
                null,
                ['user_id' => ['eq' => $user['id']]],
                null,
                null,
                null,
                'role_id,roles(id,name,description,permissions)'
            );

            $roleNames = [];
            $roleData = [];
            if (!empty($roles)) {
                foreach ($roles as $userRole) {
                    if (isset($userRole['roles']) && is_array($userRole['roles'])) {
                        $roleNames[] = $userRole['roles']['name'];
                        $roleData[] = $userRole['roles'];
                    }
                }
            }

            // Create session token
            $token = uniqid('sess_', true);
            $lifetime = (int) config('session_lifetime', 86400);
            $expiresAt = date('Y-m-d H:i:s', time() + $lifetime);

            // Store session token in the sessions table
            $this->db->insert('sessions', [
                'token'     => $token,
                'user_id'   => $user['id'],
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'expires_at' => $expiresAt,
                'is_active'  => true,
            ]);

            // Store user data in PHP session
            Session::set('user', $user);
            Session::set('token', $token);
            Session::set('roles', $roleNames);
            Session::set('role_data', $roleData);

            // Remove sensitive data from stored user
            unset($user['passwordHash']);

            return [
                'success' => true,
                'message' => 'Login successful.',
                'user'    => $user,
                'token'   => $token,
                'roles'   => $roleNames,
            ];

        } catch (\RuntimeException $e) {
            return [
                'success' => false,
                'message' => 'Authentication error: ' . $e->getMessage(),
                'user'    => null,
            ];
        }
    }

    /**
     * Log out the current user.
     *
     * Destroys the session token in the database and clears the PHP session.
     *
     * @return void
     */
    public function logout(): void
    {
        $token = Session::get('token');

        if ($token !== null) {
            try {
                // Deactivate the session token in the database
                $this->db->update('sessions', ['is_active' => false], [
                    'token' => ['eq' => $token],
                ]);
            } catch (\RuntimeException $e) {
                // Log but don't prevent logout on DB error
                error_log('Logout DB error: ' . $e->getMessage());
            }
        }

        // Clear PHP session
        Session::remove('user');
        Session::remove('token');
        Session::remove('roles');
        Session::remove('role_data');
    }

    /**
     * Get the currently authenticated user.
     *
     * Returns the user data stored in the PHP session,
     * or null if not authenticated.
     *
     * @return array|null User data or null
     */
    public function user(): ?array
    {
        return Session::get('user');
    }

    /**
     * Get the current user's ID.
     *
     * @return string|null User ID or null
     */
    public function userId(): ?string
    {
        $user = $this->user();
        return $user['id'] ?? null;
    }

    /**
     * Get the current session token.
     *
     * @return string|null Session token or null
     */
    public function token(): ?string
    {
        return Session::get('token');
    }

    /**
     * Check if a user is currently authenticated.
     *
     * Verifies both PHP session data and the database session token.
     *
     * @return bool
     */
    public function check(): bool
    {
        $user = Session::get('user');
        $token = Session::get('token');

        if ($user === null || $token === null) {
            return false;
        }

        // Optionally verify the token is still active in the database
        try {
            $session = $this->db->single('sessions', [
                'token'     => ['eq' => $token],
                'is_active' => ['eq' => 'true'],
            ]);

            if ($session === null) {
                // Session token expired or deactivated — clear PHP session
                $this->logout();
                return false;
            }

            // Check if session has expired
            if (isset($session['expiresAt']) && strtotime((string) $session['expiresAt']) < time()) {
                $this->db->update('sessions', ['is_active' => false], [
                    'token' => ['eq' => $token],
                ]);
                $this->logout();
                return false;
            }
        } catch (\RuntimeException $e) {
            // If DB check fails, still consider authenticated (graceful degradation)
            error_log('Auth check DB error: ' . $e->getMessage());
        }

        return true;
    }

    /**
     * Check if the current user is a guest (not authenticated).
     *
     * @return bool
     */
    public function guest(): bool
    {
        return !$this->check();
    }

    /**
     * Check if the current user has a specific role.
     *
     * @param string $role Role name to check
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        $roles = Session::get('roles', []);
        return in_array($role, $roles, true);
    }

    /**
     * Check if the current user has any of the given roles.
     *
     * @param array $roles Array of role names
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        $userRoles = Session::get('roles', []);
        return !empty(array_intersect($roles, $userRoles));
    }

    /**
     * Check if the current user has all of the given roles.
     *
     * @param array $roles Array of role names
     * @return bool
     */
    public function hasAllRoles(array $roles): bool
    {
        $userRoles = Session::get('roles', []);
        return empty(array_diff($roles, $userRoles));
    }

    /**
     * Get the current user's roles.
     *
     * @return array Array of role name strings
     */
    public function roles(): array
    {
        return Session::get('roles', []);
    }

    /**
     * Get the current user's full role data.
     *
     * @return array Array of role data arrays
     */
    public function roleData(): array
    {
        return Session::get('role_data', []);
    }

    /**
     * Hash a password using SHA-256 with the configured salt.
     *
     * @param string $password Plain text password
     * @return string          SHA-256 hashed password
     */
    public function hashPassword(string $password): string
    {
        return hash('sha256', $password . $this->salt);
    }

    /**
     * Verify a password against a stored hash.
     *
     * @param string $password Plain text password
     * @param string $hash     Stored SHA-256 hash
     * @return bool
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return hash_equals($hash, $this->hashPassword($password));
    }

    /**
     * Authenticate via API bearer token.
     *
     * Looks up the token in the sessions table and loads the user.
     *
     * @param string $token Bearer token
     * @return array         ['success' => bool, 'message' => string, 'user' => ?array]
     */
    public function authenticateByToken(string $token): array
    {
        try {
            // Find active session by token
            $session = $this->db->single('sessions', [
                'token'     => ['eq' => $token],
                'is_active' => ['eq' => 'true'],
            ]);

            if ($session === null) {
                return [
                    'success' => false,
                    'message' => 'Invalid or expired session token.',
                    'user'    => null,
                ];
            }

            // Check expiry
            if (isset($session['expiresAt']) && strtotime((string) $session['expiresAt']) < time()) {
                $this->db->update('sessions', ['is_active' => false], [
                    'token' => ['eq' => $token],
                ]);
                return [
                    'success' => false,
                    'message' => 'Session token has expired.',
                    'user'    => null,
                ];
            }

            // Fetch the user
            $userId = $session['userId'];
            $user = $this->db->find('users', $userId);

            if ($user === null) {
                return [
                    'success' => false,
                    'message' => 'User not found.',
                    'user'    => null,
                ];
            }

            // Fetch roles
            $roles = $this->db->query(
                'user_roles',
                'GET',
                null,
                ['user_id' => ['eq' => $userId]],
                null,
                null,
                null,
                'role_id,roles(id,name,description,permissions)'
            );

            $roleNames = [];
            $roleData = [];
            if (!empty($roles)) {
                foreach ($roles as $userRole) {
                    if (isset($userRole['roles']) && is_array($userRole['roles'])) {
                        $roleNames[] = $userRole['roles']['name'];
                        $roleData[] = $userRole['roles'];
                    }
                }
            }

            // Store in PHP session
            Session::set('user', $user);
            Session::set('token', $token);
            Session::set('roles', $roleNames);
            Session::set('role_data', $roleData);

            unset($user['passwordHash']);

            return [
                'success' => true,
                'message' => 'Authenticated via token.',
                'user'    => $user,
                'token'   => $token,
                'roles'   => $roleNames,
            ];

        } catch (\RuntimeException $e) {
            return [
                'success' => false,
                'message' => 'Token authentication error: ' . $e->getMessage(),
                'user'    => null,
            ];
        }
    }
}
