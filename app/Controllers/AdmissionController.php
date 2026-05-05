<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Session;

/**
 * AdmissionController
 *
 * Manages the full admission lifecycle: portal settings, dynamic form fields,
 * application intake, review workflow, and CSV export. The public-facing
 * admission form (form / submitApplication) does not require authentication.
 */
class AdmissionController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    //  Dashboard & Settings
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Admission dashboard with settings overview and application counts.
     * GET /admission
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requirePermission('students.view');

        // Fetch the single settings row
        $settings = $this->getSettings();

        // Count applications by status
        $totalApplications = (int) ($this->db->count('admission_applications') ?? 0);
        $pendingCount = (int) ($this->db->count('admission_applications', ['status' => ['eq' => 'pending']]) ?? 0);
        $approvedCount = (int) ($this->db->count('admission_applications', ['status' => ['eq' => 'approved']]) ?? 0);
        $rejectedCount = (int) ($this->db->count('admission_applications', ['status' => ['eq' => 'rejected']]) ?? 0);
        $waitlistedCount = (int) ($this->db->count('admission_applications', ['status' => ['eq' => 'waitlisted']]) ?? 0);

        // Recent 5 applications
        $recentApplications = $this->db->select('admission_applications', [], 'created_at.desc', 5, 0);

        // Count active form fields
        $fieldCount = (int) ($this->db->count('admission_fields', ['is_active' => ['eq' => 1]]) ?? 0);

        $this->renderWithLayout('admission.index', [
            'pageTitle'            => 'Admission Dashboard',
            'currentPage'          => 'admission',
            'settings'             => $settings,
            'totalApplications'    => $totalApplications,
            'pendingCount'         => $pendingCount,
            'approvedCount'        => $approvedCount,
            'rejectedCount'        => $rejectedCount,
            'waitlistedCount'      => $waitlistedCount,
            'recentApplications'   => $recentApplications,
            'fieldCount'           => $fieldCount,
        ]);
    }

    /**
     * Show admission settings form (academic year, dates, fees, instructions).
     * GET /admission/settings
     */
    public function settings(): void
    {
        $this->requireAuth();
        $this->requirePermission('students.manage');

        $settings = $this->getSettings();

        // Fetch classes for the class selection dropdown
        $classes = $this->db->select('classes', ['status' => ['eq' => 'active']], 'name.asc', 200);

        $this->renderWithLayout('admission.settings', [
            'pageTitle'   => 'Admission Settings',
            'currentPage' => 'admission',
            'settings'    => $settings,
            'classes'     => $classes,
        ]);
    }

    /**
     * Save / update admission_settings.
     * POST /admission/settings
     */
    public function saveSettings(): void
    {
        $this->requireAuth();
        $this->requirePermission('students.manage');

        $validation = $this->validate([
            'academic_year'   => 'required|min:4|max:20',
            'start_date'      => 'required',
            'end_date'        => 'required',
            'application_fee' => 'numeric',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/admission/settings');
            return;
        }

        $data = [
            'academic_year'    => $this->input('academic_year'),
            'start_date'       => $this->input('start_date'),
            'end_date'         => $this->input('end_date'),
            'application_fee'  => $this->input('application_fee', 0),
            'instructions'     => $this->input('instructions', ''),
            'max_applications' => (int) $this->input('max_applications', 0),
            'classes_offered'  => is_array($this->input('classes_offered'))
                ? implode(',', $this->input('classes_offered'))
                : $this->input('classes_offered', ''),
            'updated_by'       => $this->currentUserId(),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        try {
            $existing = $this->db->single('admission_settings', ['id' => ['eq' => 1]]);

            if ($existing) {
                $this->db->updateById('admission_settings', $existing['id'], $data);
            } else {
                $data['is_active'] = 0;
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('admission_settings', $data);
            }

            success_msg('Admission settings saved successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to save admission settings: ' . $e->getMessage());
        }

        $this->redirect('/admission/settings');
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Dynamic Form Fields
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * List all dynamic form fields grouped by section.
     * GET /admission/fields
     */
    public function fields(): void
    {
        $this->requireAuth();
        $this->requirePermission('students.manage');

        $allFields = $this->db->select('admission_fields', [], 'sort_order.asc,label.asc', 500);

        // Group by section
        $grouped = [];
        foreach ($allFields as $field) {
            $section = $field['section'] ?? 'General';
            $grouped[$section][] = $field;
        }

        $this->renderWithLayout('admission.fields', [
            'pageTitle'   => 'Admission Form Fields',
            'currentPage' => 'admission',
            'fields'      => $allFields,
            'grouped'     => $grouped,
        ]);
    }

    /**
     * Show the add-field form.
     * GET /admission/fields/create
     */
    public function addField(): void
    {
        $this->requireAuth();
        $this->requirePermission('students.manage');

        $fieldTypes = $this->getFieldTypes();

        $this->view('admission.field-form', [
            'pageTitle'   => 'Add Admission Field',
            'currentPage' => 'admission',
            'field'       => null,
            'fieldTypes'  => $fieldTypes,
            'mode'        => 'create',
        ]);
    }

    /**
     * Create a new admission field.
     * POST /admission/fields
     */
    public function storeField(): void
    {
        $this->requireAuth();
        $this->requirePermission('students.manage');

        $validation = $this->validate([
            'label'      => 'required|min:2|max:200',
            'field_type' => 'required',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect('/admission/fields/create');
            return;
        }

        $fieldType = $this->input('field_type');
        $allowedTypes = array_keys($this->getFieldTypes());

        if (!in_array($fieldType, $allowedTypes, true)) {
            error_msg('Invalid field type.');
            $this->redirect('/admission/fields/create');
            return;
        }

        $maxFieldSize = 255;
        if (in_array($fieldType, ['textarea'], true)) {
            $maxFieldSize = 5000;
        } elseif (in_array($fieldType, ['number'], true)) {
            $maxFieldSize = 30;
        }

        $data = [
            'label'          => $this->input('label'),
            'field_type'     => $fieldType,
            'section'        => $this->input('section', 'General'),
            'placeholder'    => $this->input('placeholder', ''),
            'help_text'      => $this->input('help_text', ''),
            'options'        => is_array($this->input('options'))
                ? json_encode($this->input('options'))
                : $this->input('options', ''),
            'default_value'  => $this->input('default_value', ''),
            'is_required'    => (int) $this->input('is_required', 0),
            'is_active'      => 1,
            'sort_order'     => (int) $this->input('sort_order', 0),
            'max_size'       => (int) $this->input('max_size', $maxFieldSize),
            'validation_rules' => $this->input('validation_rules', ''),
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        try {
            $this->db->insert('admission_fields', $data);
            success_msg('Admission field created successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to create admission field: ' . $e->getMessage());
        }

        $this->redirect('/admission/fields');
    }

    /**
     * Show the edit-field form.
     * GET /admission/fields/{id}/edit
     */
    public function editField(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('students.manage');

        $field = $this->db->find('admission_fields', $id);

        if (!$field) {
            error_msg('Admission field not found.');
            $this->redirect('/admission/fields');
            return;
        }

        // Decode JSON options for display
        if (!empty($field['options'])) {
            $decoded = json_decode($field['options'], true);
            if (is_array($decoded)) {
                $field['options_array'] = $decoded;
            } else {
                // Fallback: split by newline or comma
                $field['options_array'] = preg_split('/[\n,]+/', $field['options']);
                $field['options_array'] = array_map('trim', $field['options_array']);
                $field['options_array'] = array_filter($field['options_array']);
            }
        } else {
            $field['options_array'] = [];
        }

        $fieldTypes = $this->getFieldTypes();

        $this->view('admission.field-form', [
            'pageTitle'   => 'Edit Admission Field',
            'currentPage' => 'admission',
            'field'       => $field,
            'fieldTypes'  => $fieldTypes,
            'mode'        => 'edit',
        ]);
    }

    /**
     * Update an admission field.
     * POST /admission/fields/{id}
     */
    public function updateField(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('students.manage');

        $field = $this->db->find('admission_fields', $id);

        if (!$field) {
            error_msg('Admission field not found.');
            $this->redirect('/admission/fields');
            return;
        }

        $validation = $this->validate([
            'label'      => 'required|min:2|max:200',
            'field_type' => 'required',
        ]);

        if (!$validation['valid']) {
            error_msg(implode(' ', $validation['errors']));
            $this->redirect("/admission/fields/{$id}/edit");
            return;
        }

        $fieldType = $this->input('field_type');
        $allowedTypes = array_keys($this->getFieldTypes());

        if (!in_array($fieldType, $allowedTypes, true)) {
            error_msg('Invalid field type.');
            $this->redirect("/admission/fields/{$id}/edit");
            return;
        }

        $maxFieldSize = 255;
        if (in_array($fieldType, ['textarea'], true)) {
            $maxFieldSize = 5000;
        } elseif (in_array($fieldType, ['number'], true)) {
            $maxFieldSize = 30;
        }

        $data = [
            'label'            => $this->input('label'),
            'field_type'       => $fieldType,
            'section'          => $this->input('section', 'General'),
            'placeholder'      => $this->input('placeholder', ''),
            'help_text'        => $this->input('help_text', ''),
            'options'          => is_array($this->input('options'))
                ? json_encode($this->input('options'))
                : $this->input('options', ''),
            'default_value'    => $this->input('default_value', ''),
            'is_required'      => (int) $this->input('is_required', 0),
            'is_active'        => (int) $this->input('is_active', 1),
            'sort_order'       => (int) $this->input('sort_order', 0),
            'max_size'         => (int) $this->input('max_size', $maxFieldSize),
            'validation_rules' => $this->input('validation_rules', ''),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        try {
            $this->db->updateById('admission_fields', $id, $data);
            success_msg('Admission field updated successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to update admission field: ' . $e->getMessage());
        }

        $this->redirect('/admission/fields');
    }

    /**
     * Delete an admission field.
     * POST /admission/fields/{id}/delete
     */
    public function deleteField(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('students.delete');

        $field = $this->db->find('admission_fields', $id);

        if (!$field) {
            error_msg('Admission field not found.');
            $this->redirect('/admission/fields');
            return;
        }

        try {
            // Delete related form data entries
            $this->db->raw('DELETE FROM admission_form_data WHERE field_id = ?', [$id]);

            // Delete the field itself
            $this->db->deleteById('admission_fields', $id);
            success_msg('Admission field deleted successfully.');
        } catch (\RuntimeException $e) {
            error_msg('Failed to delete admission field: ' . $e->getMessage());
        }

        $this->redirect('/admission/fields');
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Applications
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * List all admission applications with filters (status, class).
     * GET /admission/applications
     */
    public function applications(): void
    {
        $this->requireAuth();
        $this->requirePermission('students.view');

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $search = $this->input('search', '');
        $status = $this->input('status', '');
        $classId = $this->input('class_id', '');
        $academicYear = $this->input('academic_year', '');

        // Build WHERE clause
        $where = '1=1';
        $params = [];

        if ($search !== '') {
            $where .= ' AND (a.applicant_name LIKE ? OR a.applicant_email LIKE ? OR a.application_no LIKE ?)';
            $like = '%' . $search . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if ($status !== '') {
            $where .= ' AND a.status = ?';
            $params[] = $status;
        }

        if ($classId !== '') {
            $where .= ' AND a.class_id = ?';
            $params[] = $classId;
        }

        if ($academicYear !== '') {
            $where .= ' AND a.academic_year = ?';
            $params[] = $academicYear;
        }

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM admission_applications a WHERE {$where}";
        $countResult = $this->db->raw($countSql, $params);
        $total = (int) ($countResult[0]['total'] ?? 0);

        // Fetch paginated applications
        $sql = "SELECT a.*, c.name as class_name
                FROM admission_applications a
                LEFT JOIN classes c ON a.class_id = c.id
                WHERE {$where}
                ORDER BY a.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";

        $applications = $this->db->raw($sql, $params);

        // Fetch classes and academic years for filter dropdowns
        $classes = $this->db->select('classes', [], 'name.asc', 200);
        $academicYears = $this->db->raw(
            'SELECT DISTINCT academic_year FROM admission_applications ORDER BY academic_year DESC'
        );

        $lastPage = max(1, (int) ceil($total / $perPage));

        $this->renderWithLayout('admission.applications', [
            'pageTitle'      => 'Admission Applications',
            'currentPage'    => 'admission',
            'applications'   => $applications,
            'classes'        => $classes,
            'academicYears'  => $academicYears,
            'pagination'     => [
                'page'       => $page,
                'totalPages' => $lastPage,
                'total'      => $total,
                'from'       => $total > 0 ? (($page - 1) * $perPage) + 1 : 0,
                'to'         => min($page * $perPage, $total),
            ],
            'search'        => $search,
            'status'        => $status,
            'classId'       => $classId,
            'academicYear'  => $academicYear,
        ]);
    }

    /**
     * View a single application with all form data.
     * GET /admission/applications/{id}
     */
    public function showApplication(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('students.view');

        $application = $this->db->find('admission_applications', $id);

        if (!$application) {
            error_msg('Application not found.');
            $this->redirect('/admission/applications');
            return;
        }

        // Fetch all form data for this application, grouped by section
        $formDataSql = "
            SELECT fd.*, af.label, af.field_type, af.section, af.options, af.help_text
            FROM admission_form_data fd
            INNER JOIN admission_fields af ON fd.field_id = af.id
            WHERE fd.application_id = ?
            ORDER BY af.sort_order ASC, af.label ASC
        ";
        $formData = $this->db->raw($formDataSql, [$id]);

        // Group form data by section
        $groupedData = [];
        foreach ($formData as $row) {
            $section = $row['section'] ?? 'General';
            $groupedData[$section][] = $row;
        }

        // Fetch attachments
        $attachments = $this->db->select(
            'admission_attachments',
            ['application_id' => ['eq' => $id]],
            'created_at.asc',
            100
        );

        // Fetch class name
        $className = 'N/A';
        if (!empty($application['class_id'])) {
            $class = $this->db->find('classes', $application['class_id']);
            if ($class) {
                $className = $class['name'] ?? 'N/A';
            }
        }

        // Fetch reviewed by user name
        $reviewerName = 'N/A';
        if (!empty($application['reviewed_by'])) {
            $reviewer = $this->db->find('users', $application['reviewed_by']);
            if ($reviewer) {
                $reviewerName = trim(
                    ($reviewer['first_name'] ?? '') . ' ' . ($reviewer['last_name'] ?? '')
                );
            }
        }

        $this->renderWithLayout('admission.show-application', [
            'pageTitle'     => 'Application #' . e($application['application_no']),
            'currentPage'   => 'admission',
            'application'   => $application,
            'formData'      => $formData,
            'groupedData'   => $groupedData,
            'attachments'   => $attachments,
            'className'     => $className,
            'reviewerName'  => $reviewerName,
        ]);
    }

    /**
     * Review an application (approve / reject / waitlist).
     * POST /admission/applications/{id}/review
     */
    public function reviewApplication(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('students.manage');

        $application = $this->db->find('admission_applications', $id);

        if (!$application) {
            if ($this->isAjaxRequest()) {
                $this->error('Application not found.', 404);
                return;
            }
            error_msg('Application not found.');
            $this->redirect('/admission/applications');
            return;
        }

        $status = $this->input('status', '');
        $allowedStatuses = ['approved', 'rejected', 'waitlisted'];

        if (!in_array($status, $allowedStatuses, true)) {
            if ($this->isAjaxRequest()) {
                $this->error('Invalid review status. Allowed: approved, rejected, waitlisted.', 422);
                return;
            }
            error_msg('Invalid review status.');
            $this->redirect('/admission/applications/' . $id);
            return;
        }

        $reviewNotes = $this->input('review_notes', '');

        try {
            $this->db->updateById('admission_applications', $id, [
                'status'       => $status,
                'review_notes' => $reviewNotes,
                'reviewed_by'  => $this->currentUserId(),
                'reviewed_at'  => date('Y-m-d H:i:s'),
            ]);

            $statusLabel = ucfirst($status);
            success_msg("Application {$statusLabel} successfully.");

            if ($this->isAjaxRequest()) {
                $this->success(null, "Application {$statusLabel} successfully.");
                return;
            }
        } catch (\RuntimeException $e) {
            if ($this->isAjaxRequest()) {
                $this->error('Failed to review application: ' . $e->getMessage());
                return;
            }
            error_msg('Failed to review application: ' . $e->getMessage());
        }

        $this->redirect('/admission/applications/' . $id);
    }

    /**
     * Delete an admission application.
     * POST /admission/applications/{id}/delete
     */
    public function deleteApplication(string $id): void
    {
        $this->requireAuth();
        $this->requirePermission('students.delete');

        $application = $this->db->find('admission_applications', $id);

        if (!$application) {
            if ($this->isAjaxRequest()) {
                $this->error('Application not found.', 404);
                return;
            }
            error_msg('Application not found.');
            $this->redirect('/admission/applications');
            return;
        }

        try {
            // Delete associated form data
            $this->db->raw('DELETE FROM admission_form_data WHERE application_id = ?', [$id]);

            // Delete associated attachments (and their files from disk)
            $attachments = $this->db->select(
                'admission_attachments',
                ['application_id' => ['eq' => $id]],
                '',
                500
            );
            foreach ($attachments as $attachment) {
                // Remove file from disk if it exists
                $filePath = dirname(dirname(__DIR__)) . '/public/uploads/admissions/' . ($attachment['file_path'] ?? '');
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $this->db->deleteById('admission_attachments', $attachment['id']);
            }

            // Delete the application itself
            $this->db->deleteById('admission_applications', $id);

            success_msg('Application deleted successfully.');

            if ($this->isAjaxRequest()) {
                $this->success(null, 'Application deleted successfully.');
                return;
            }
        } catch (\RuntimeException $e) {
            if ($this->isAjaxRequest()) {
                $this->error('Failed to delete application: ' . $e->getMessage());
                return;
            }
            error_msg('Failed to delete application: ' . $e->getMessage());
        }

        $this->redirect('/admission/applications');
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Portal Toggle & Export
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Open or close the admission portal.
     * POST /admission/toggle
     */
    public function toggleAdmission(): void
    {
        $this->requireAuth();
        $this->requirePermission('students.manage');

        try {
            $existing = $this->db->single('admission_settings', ['id' => ['eq' => 1]]);

            if ($existing) {
                $newStatus = ((int) ($existing['is_active'] ?? 0)) === 1 ? 0 : 1;
                $this->db->updateById('admission_settings', $existing['id'], [
                    'is_active'  => $newStatus,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $message = $newStatus === 1
                    ? 'Admission portal opened successfully.'
                    : 'Admission portal closed successfully.';

                success_msg($message);
                $this->success(['is_active' => $newStatus], $message);
            } else {
                // Create settings row with portal open
                $this->db->insert('admission_settings', [
                    'is_active'  => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                success_msg('Admission portal opened successfully.');
                $this->success(['is_active' => 1], 'Admission portal opened successfully.');
            }
        } catch (\RuntimeException $e) {
            error_msg('Failed to toggle admission portal: ' . $e->getMessage());
            $this->error('Failed to toggle admission portal: ' . $e->getMessage());
        }
    }

    /**
     * Export applications as CSV.
     * GET /admission/export
     */
    public function exportApplications(): void
    {
        $this->requireAuth();
        $this->requirePermission('students.view');

        // Apply same filters as applications list
        $status = $this->input('status', '');
        $classId = $this->input('class_id', '');
        $academicYear = $this->input('academic_year', '');

        $where = '1=1';
        $params = [];

        if ($status !== '') {
            $where .= ' AND a.status = ?';
            $params[] = $status;
        }
        if ($classId !== '') {
            $where .= ' AND a.class_id = ?';
            $params[] = $classId;
        }
        if ($academicYear !== '') {
            $where .= ' AND a.academic_year = ?';
            $params[] = $academicYear;
        }

        $sql = "SELECT a.*, c.name as class_name
                FROM admission_applications a
                LEFT JOIN classes c ON a.class_id = c.id
                WHERE {$where}
                ORDER BY a.created_at DESC";

        $applications = $this->db->raw($sql, $params);

        if (empty($applications)) {
            error_msg('No applications to export.');
            $this->redirect('/admission/applications');
            return;
        }

        // Fetch all active fields for column headers
        $activeFields = $this->db->select(
            'admission_fields',
            ['is_active' => ['eq' => 1]],
            'sort_order.asc,label.asc',
            200
        );

        // Build CSV
        $filename = 'admission_applications_' . date('Y-m-d_His') . '.csv';

        // Set CSV headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // Write BOM for UTF-8 Excel compatibility
        fwrite($output, "\xEF\xBB\xBF");

        // Build header row
        $headers = [
            'Application No',
            'Applicant Name',
            'Applicant Email',
            'Applicant Phone',
            'Class',
            'Academic Year',
            'Status',
            'Submitted At',
            'Reviewed By',
            'Review Notes',
        ];

        // Add dynamic field labels
        foreach ($activeFields as $af) {
            $headers[] = $af['label'];
        }

        fputcsv($output, $headers);

        // Write data rows
        foreach ($applications as $app) {
            // Fetch form data for this application
            $formDataSql = "
                SELECT fd.field_id, fd.field_value
                FROM admission_form_data fd
                WHERE fd.application_id = ?
            ";
            $formDataRows = $this->db->raw($formDataSql, [$app['id']]);

            // Map field values by field_id
            $fieldValues = [];
            foreach ($formDataRows as $fdr) {
                $fieldValues[$fdr['field_id']] = $fdr['field_value'];
            }

            // Get reviewer name
            $reviewerName = '';
            if (!empty($app['reviewed_by'])) {
                $reviewer = $this->db->find('users', $app['reviewed_by']);
                if ($reviewer) {
                    $reviewerName = trim(
                        ($reviewer['first_name'] ?? '') . ' ' . ($reviewer['last_name'] ?? '')
                    );
                }
            }

            $row = [
                $app['application_no'] ?? '',
                $app['applicant_name'] ?? '',
                $app['applicant_email'] ?? '',
                $app['applicant_phone'] ?? '',
                $app['class_name'] ?? '',
                $app['academic_year'] ?? '',
                $app['status'] ?? '',
                $app['created_at'] ?? '',
                $reviewerName,
                $app['review_notes'] ?? '',
            ];

            // Add dynamic field values
            foreach ($activeFields as $af) {
                $row[] = $fieldValues[$af['id']] ?? '';
            }

            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Public Admission Form (No Auth Required)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Render the public admission form with all active fields.
     * No authentication required — uses website frontend layout.
     * GET /admission/form
     */
    public function form(): void
    {
        // Check if admission portal is active
        $settings = $this->getSettings();
        $isActive = (int) ($settings['is_active'] ?? 0) === 1;

        if (!$isActive) {
            // Admission portal is closed
            $this->view('admission.closed', [
                'pageTitle' => 'Admissions Closed',
                'settings'  => $settings,
            ]);
            return;
        }

        // Check admission dates
        $now = date('Y-m-d');
        $startDate = $settings['start_date'] ?? null;
        $endDate = $settings['end_date'] ?? null;

        if ($startDate && $now < $startDate) {
            $this->view('admission.not-yet-open', [
                'pageTitle'  => 'Admissions Not Yet Open',
                'settings'   => $settings,
                'start_date' => $startDate,
            ]);
            return;
        }

        if ($endDate && $now > $endDate) {
            $this->view('admission.closed', [
                'pageTitle' => 'Admissions Closed',
                'settings'  => $settings,
            ]);
            return;
        }

        // Fetch all active fields grouped by section
        $allFields = $this->db->select(
            'admission_fields',
            ['is_active' => ['eq' => 1]],
            'sort_order.asc,label.asc',
            200
        );

        $groupedFields = [];
        foreach ($allFields as $field) {
            $section = $field['section'] ?? 'General';

            // Decode options for select/radio/checkbox types
            if (in_array($field['field_type'], ['select', 'radio', 'checkbox'], true)
                && !empty($field['options'])
            ) {
                $decoded = json_decode($field['options'], true);
                if (is_array($decoded)) {
                    $field['options_array'] = $decoded;
                } else {
                    $field['options_array'] = preg_split('/[\n,]+/', $field['options']);
                    $field['options_array'] = array_map('trim', $field['options_array']);
                    $field['options_array'] = array_filter($field['options_array']);
                }
            } else {
                $field['options_array'] = [];
            }

            $groupedFields[$section][] = $field;
        }

        // Fetch classes offered (from settings or all active classes)
        $classes = [];
        $classesOffered = $settings['classes_offered'] ?? '';
        if (!empty($classesOffered)) {
            $classIds = array_filter(array_map('trim', explode(',', $classesOffered)));
            if (!empty($classIds)) {
                $placeholders = implode(',', array_fill(0, count($classIds), '?'));
                $classesSql = "SELECT * FROM classes WHERE id IN ({$placeholders}) AND status = 'active' ORDER BY name ASC";
                $classes = $this->db->raw($classesSql, array_values($classIds));
            }
        }

        if (empty($classes)) {
            $classes = $this->db->select('classes', ['status' => ['eq' => 'active']], 'name.asc', 200);
        }

        $this->view('admission.form', [
            'pageTitle'      => 'Admission Application',
            'settings'       => $settings,
            'groupedFields'  => $groupedFields,
            'classes'        => $classes,
            'academic_year'  => $settings['academic_year'] ?? '',
        ]);
    }

    /**
     * Process admission form submission from the public form.
     * No authentication required.
     * POST /admission/submit
     */
    public function submitApplication(): void
    {
        // Check if admission portal is active
        $settings = $this->getSettings();
        $isActive = (int) ($settings['is_active'] ?? 0) === 1;

        if (!$isActive) {
            $this->error('Admissions are currently closed.', 403);
            return;
        }

        // Validate required applicant fields
        $validation = $this->validate([
            'applicant_name'  => 'required|min:2|max:200',
            'applicant_email' => 'required|email|max:200',
            'applicant_phone' => 'max:30',
            'class_id'        => 'required',
            'academic_year'   => 'required',
        ]);

        if (!$validation['valid']) {
            $errors = implode(' ', $validation['errors']);
            $this->error($errors, 422);
            return;
        }

        // Validate dynamic fields
        $activeFields = $this->db->select(
            'admission_fields',
            ['is_active' => ['eq' => 1]],
            'sort_order.asc',
            200
        );

        $dynamicErrors = [];
        foreach ($activeFields as $field) {
            if ((int) ($field['is_required'] ?? 0) === 1) {
                $value = $this->input('field_' . $field['id']);
                if ($value === null || $value === '') {
                    $label = $field['label'] ?? $field['id'];
                    $dynamicErrors[] = "{$label} is required.";
                }
            }

            // Validate email-type fields
            if ($field['field_type'] === 'email') {
                $emailValue = $this->input('field_' . $field['id']);
                if (!empty($emailValue) && !filter_var($emailValue, FILTER_VALIDATE_EMAIL)) {
                    $label = $field['label'] ?? $field['id'];
                    $dynamicErrors[] = "{$label} must be a valid email address.";
                }
            }

            // Validate number-type fields
            if ($field['field_type'] === 'number') {
                $numValue = $this->input('field_' . $field['id']);
                if (!empty($numValue) && !is_numeric($numValue)) {
                    $label = $field['label'] ?? $field['id'];
                    $dynamicErrors[] = "{$label} must be a valid number.";
                }
            }
        }

        if (!empty($dynamicErrors)) {
            $this->error(implode(' ', $dynamicErrors), 422);
            return;
        }

        // Check for duplicate application (same email + academic year + class)
        $email = $this->input('applicant_email');
        $academicYear = $this->input('academic_year');
        $classId = $this->input('class_id');

        $duplicateSql = "SELECT COUNT(*) as cnt FROM admission_applications 
                         WHERE applicant_email = ? AND academic_year = ? AND class_id = ? AND status != 'rejected'";
        $duplicateResult = $this->db->raw($duplicateSql, [$email, $academicYear, $classId]);

        if ((int) ($duplicateResult[0]['cnt'] ?? 0) > 0) {
            $this->error('You have already submitted an application for this class and academic year.', 409);
            return;
        }

        // Generate application number: ADM/YYYY/NNNN
        $year = date('Y');
        $countSql = "SELECT COUNT(*) as cnt FROM admission_applications WHERE application_no LIKE ?";
        $countResult = $this->db->raw($countSql, ["ADM/{$year}/%"]);
        $sequence = (int) ($countResult[0]['cnt'] ?? 0) + 1;
        $applicationNo = "ADM/{$year}/" . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);

        try {
            // Create the application record
            $application = $this->db->insert('admission_applications', [
                'application_no'  => $applicationNo,
                'applicant_name'  => $this->input('applicant_name'),
                'applicant_email' => $email,
                'applicant_phone' => $this->input('applicant_phone', ''),
                'class_id'        => $classId,
                'academic_year'   => $academicYear,
                'status'          => 'pending',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            $applicationId = $application['id'] ?? $application['id'] ?? null;

            if (!$applicationId) {
                $this->error('Failed to create application record.', 500);
                return;
            }

            // Save dynamic field data
            foreach ($activeFields as $field) {
                $fieldKey = 'field_' . $field['id'];
                $value = $this->input($fieldKey);

                // Handle checkbox arrays: join to comma-separated string
                if ($field['field_type'] === 'checkbox' && is_array($value)) {
                    $value = implode(', ', $value);
                }

                // Skip empty optional fields
                if ($value === null || $value === '') {
                    continue;
                }

                // Handle file uploads
                if ($field['field_type'] === 'file' && isset($_FILES[$fieldKey]) && $_FILES[$fieldKey]['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = $this->handleFileUpload($_FILES[$fieldKey], $applicationId, $field['id']);

                    if ($uploadResult !== false) {
                        $value = $uploadResult['file_path'];
                        // Attachment is already saved in handleFileUpload
                    } else {
                        continue; // Skip failed uploads
                    }
                }

                $this->db->insert('admission_form_data', [
                    'application_id' => $applicationId,
                    'field_id'       => $field['id'],
                    'field_value'    => is_string($value) ? $value : json_encode($value),
                    'created_at'     => date('Y-m-d H:i:s'),
                ]);
            }

            // Store application number in session for success page
            Session::flash('application_no', $applicationNo);
            Session::flash('application_success', true);

            // Redirect or return JSON
            if ($this->isAjaxRequest()) {
                $this->success([
                    'application_no'  => $applicationNo,
                    'application_id'  => $applicationId,
                ], 'Application submitted successfully.');
            } else {
                $this->redirect('/admission/form?success=1&ref=' . urlencode($applicationNo));
            }
        } catch (\RuntimeException $e) {
            $this->error('Failed to submit application: ' . $e->getMessage(), 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Private Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Get the current admission settings (single row).
     *
     * @return array
     */
    private function getSettings(): array
    {
        try {
            $row = $this->db->single('admission_settings', ['id' => ['eq' => 1]]);
            if ($row) {
                return $row;
            }
        } catch (\RuntimeException $e) {
            // Table might not exist yet
        }

        return [
            'id'                => null,
            'academic_year'     => '',
            'start_date'        => '',
            'end_date'          => '',
            'application_fee'   => 0,
            'instructions'      => '',
            'max_applications'  => 0,
            'classes_offered'   => '',
            'is_active'         => 0,
            'created_at'        => null,
            'updated_at'        => null,
        ];
    }

    /**
     * Get the allowed field types with their labels.
     *
     * @return array<string, string>
     */
    private function getFieldTypes(): array
    {
        return [
            'text'     => 'Text',
            'email'    => 'Email',
            'phone'    => 'Phone',
            'textarea' => 'Text Area',
            'select'   => 'Select (Dropdown)',
            'radio'    => 'Radio Button',
            'checkbox' => 'Checkbox',
            'date'     => 'Date Picker',
            'number'   => 'Number',
            'file'     => 'File Upload',
        ];
    }

    /**
     * Check if the current request is an AJAX/JSON request.
     *
     * @return bool
     */
    private function isAjaxRequest(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Handle a file upload for an admission field.
     *
     * Creates the uploads directory if needed, generates a unique filename,
     * moves the file, and saves an attachment record.
     *
     * @param array  $file           The $_FILES entry
     * @param string $applicationId  The application ID
     * @param string $fieldId        The admission field ID
     * @return array|false  Array with file_path on success, false on failure
     */
    private function handleFileUpload(array $file, string $applicationId, string $fieldId)
    {
        $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/admissions/' . $applicationId;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $originalName = basename($file['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $filename = $safeName . '_' . time() . '.' . $extension;
        $targetPath = $uploadDir . '/' . $filename;

        // Validate file type (basic whitelist)
        $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'txt', 'xls', 'xlsx'];
        if (!in_array(strtolower($extension), $allowedExtensions, true)) {
            return false;
        }

        // Validate file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return false;
        }

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $relativePath = $applicationId . '/' . $filename;
            $fileSize = filesize($targetPath);

            // Save attachment record
            $this->db->insert('admission_attachments', [
                'application_id' => $applicationId,
                'field_id'       => $fieldId,
                'original_name'  => $originalName,
                'file_path'      => $relativePath,
                'file_size'      => $fileSize,
                'file_type'      => $file['type'],
                'created_at'     => date('Y-m-d H:i:s'),
            ]);

            return [
                'file_path' => $relativePath,
                'filename'  => $filename,
            ];
        }

        return false;
    }
}
