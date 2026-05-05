<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Session;

/**
 * ProfileController
 *
 * Manages the authenticated user's own profile — viewing and updating
 * personal information, email, phone, and password.
 */
class ProfileController extends Controller
{
    /**
     * Show the current user's profile.
     * GET /profile
     */
    public function index(): void
    {
        $this->requireAuth();

        $userId = Session::get('user')['id'] ?? null;

        if (!$userId) {
            error_msg('Unable to load profile. Please log in again.');
            $this->redirect('/login');
            return;
        }

        $user = $this->db->find('users', $userId);

        if (!$user) {
            error_msg('User not found.');
            $this->redirect('/login');
            return;
        }

        // Fetch user roles
        $roles = $this->db->raw(
            "SELECT r.id, r.name
             FROM roles r
             INNER JOIN user_roles ur ON r.id = ur.role_id
             WHERE ur.user_id = ?
             ORDER BY r.name ASC",
            [$userId]
        );

        // Fetch branch info if assigned
        $branch = null;
        $school = null;
        $profile = $this->db->single('student_profiles', ['user_id' => ['eq' => $userId]]);
        if ($profile && !empty($profile['branch_id'])) {
            $branch = $this->db->find('branches', $profile['branch_id']);
            if ($branch && !empty($branch['school_id'])) {
                $school = $this->db->find('schools', $branch['school_id']);
            }
        }

        $this->renderWithLayout('profile.index', [
            'pageTitle'   => 'My Profile',
            'currentPage' => 'profile',
            'user'        => $user,
            'roles'       => $roles,
            'branch'      => $branch,
            'school'      => $school,
        ]);
    }

    /**
     * Update the current user's profile.
     * POST /profile
     */
    public function update(): void
    {
        $this->requireAuth();

        $userId = Session::get('user')['id'] ?? null;

        if (!$userId) {
            error_msg('Unable to update profile. Please log in again.');
            $this->redirect('/login');
            return;
        }

        $user = $this->db->find('users', $userId);

        if (!$user) {
            error_msg('User not found.');
            $this->redirect('/profile');
            return;
        }

        // Validate basic profile fields
        $validation = $this->validate([
            'first_name' => 'required|min:1|max:100',
            'last_name'  => 'required|min:1|max:100',
            'email'      => 'required|email',
            'phone'      => 'max:20',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/profile');
            return;
        }

        // Check email uniqueness (excluding current user)
        $email = $this->input('email');
        if ($email !== $user['email']) {
            $existing = $this->db->single('users', ['email' => ['eq' => $email]]);
            if ($existing) {
                error_msg('Email address is already in use by another account.');
                $this->redirect('/profile');
                return;
            }
        }

        try {
            $data = [
                'first_name' => $this->input('first_name'),
                'last_name'  => $this->input('last_name'),
                'email'      => $email,
                'phone'      => $this->input('phone'),
            ];

            // Handle password change if provided
            $currentPassword = $this->input('current_password');
            $newPassword = $this->input('new_password');
            $confirmPassword = $this->input('confirm_password');

            if (!empty($newPassword)) {
                if (empty($currentPassword)) {
                    error_msg('Current password is required to set a new password.');
                    $this->redirect('/profile');
                    return;
                }

                $hashedCurrent = hash('sha256', $currentPassword . (string) config('password_salt', '_school_erp_salt'));
                if (!hash_equals($hashedCurrent, (string) ($user['passwordHash'] ?? ''))) {
                    error_msg('Current password is incorrect.');
                    $this->redirect('/profile');
                    return;
                }

                if (strlen($newPassword) < 8) {
                    error_msg('New password must be at least 8 characters long.');
                    $this->redirect('/profile');
                    return;
                }

                if ($newPassword !== $confirmPassword) {
                    error_msg('New password and confirmation do not match.');
                    $this->redirect('/profile');
                    return;
                }

                $data['passwordHash'] = hash('sha256', $newPassword . (string) config('password_salt', '_school_erp_salt'));
            }

            $this->db->updateById('users', $userId, $data);

            // Update session data
            $updatedUser = $this->db->find('users', $userId);
            Session::set('user', $updatedUser);

            success_msg('Profile updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update profile: ' . $e->getMessage());
        }

        $this->redirect('/profile');
    }
}
