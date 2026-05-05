<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Session;

/**
 * SettingController
 *
 * Manages system-wide settings including school information,
 * academic configuration, and display preferences.
 */
class SettingController extends Controller
{
    /**
     * Show the settings page.
     * GET /settings
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.manage');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        // Load all settings from the settings table
        $allSettings = $this->db->select('settings', [], 'key.asc');
        $settings = [];

        foreach ($allSettings as $row) {
            $settings[$row['key']] = $row['value'];
        }

        // Provide defaults
        $defaults = [
            'school_name'          => '',
            'school_address'       => '',
            'school_phone'         => '',
            'school_email'         => '',
            'school_website'       => '',
            'academic_year'        => '',
            'current_term'         => '',
            'theme'                => 'light',
        ];

        $settings = array_merge($defaults, $settings);

        $this->renderWithLayout('settings.index', [
            'pageTitle'   => 'Settings',
            'currentPage' => 'settings',
            'settings'    => $settings,
        ]);
    }

    /**
     * Update system settings.
     * POST /settings
     */
    public function update(): void
    {
        $this->requireAuth();
        $this->requirePermission('schools.manage');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin']);

        $fields = [
            'school_name',
            'school_address',
            'school_phone',
            'school_email',
            'school_website',
            'academic_year',
            'current_term',
            'theme',
        ];

        // Validate school email if provided
        $schoolEmail = $this->input('school_email');
        if (!empty($schoolEmail)) {
            $validation = $this->validate([
                'school_email' => 'email',
            ]);
            if (!$validation['valid']) {
                error_msg(implode(' ', $validation['errors']));
                $this->redirect('/settings');
                return;
            }
        }

        try {
            foreach ($fields as $field) {
                $value = $this->input($field, '');

                // Check if setting already exists
                $existing = $this->db->single('settings', ['key' => ['eq' => $field]]);

                if ($existing) {
                    $this->db->updateById('settings', $existing['id'], [
                        'value' => $value,
                    ]);
                } else {
                    $this->db->insert('settings', [
                        'key'   => $field,
                        'value' => $value,
                    ]);
                }
            }

            // Update theme in session if changed
            $theme = $this->input('theme', 'light');
            Session::set('theme', $theme);

            success_msg('Settings updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update settings: ' . $e->getMessage());
        }

        $this->redirect('/settings');
    }
}
