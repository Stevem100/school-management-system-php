<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * ReportController
 *
 * Generates and exports various reports for the school ERP
 * including enrollment, fees, attendance, and academic results.
 */
class ReportController extends Controller
{
    /**
     * Display the report selection page.
     * GET /reports
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->requirePermission('reports.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Accountant']);

        $this->renderWithLayout('reports.index', [
            'pageTitle'   => 'Reports',
            'currentPage' => 'reports',
            'reportData'  => null,
            'reportType'  => '',
            'reportTitle' => '',
        ]);
    }

    /**
     * Generate a student enrollment report.
     * GET /reports/students
     */
    public function studentReports(): void
    {
        $this->requireAuth();
        $this->requirePermission('reports.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Accountant']);

        $filters = [];
        $classId = $this->input('class_id', '');
        $status = $this->input('status', '');
        $search = $this->input('search', '');

        if (!empty($classId)) {
            $filters['class_id'] = ['eq' => $classId];
        }
        if (!empty($status)) {
            $filters['status'] = ['eq' => $status];
        }
        if (!empty($search)) {
            $filters['name'] = ['ilike' => "%{$search}%"];
        }

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 50;

        // Use raw SQL to query users table with student_profiles
        $sql = "SELECT u.id, u.first_name, u.last_name, u.email, u.status, sp.admission_no, u.created_at\n                FROM users u LEFT JOIN student_profiles sp ON u.id = sp.user_id\n                WHERE u.userType = 'student'";
        $params = [];
        if (!empty($status)) { $sql .= ' AND u.status = ?'; $params[] = $status; }
        if (!empty($search)) { $sql .= ' AND (u.first_name LIKE ? OR u.last_name LIKE ? OR sp.admission_no LIKE ?)'; $p = '%' . $search . '%'; $params[] = $p; $params[] = $p; $params[] = $p; }
        $sql .= ' ORDER BY u.first_name ASC LIMIT ' . $perPage . ' OFFSET ' . (($page - 1) * $perPage);
        $reportRows = $this->db->raw($sql, $params);

        $this->renderWithLayout('reports.index', [
            'pageTitle'   => 'Student Enrollment Report',
            'currentPage' => 'reports',
            'reportData'  => $reportRows,
            'reportType'  => 'enrollment',
            'reportTitle' => 'Student Enrollment Report',
            'filters'     => ['class_id' => $classId, 'status' => $status, 'search' => $search],
        ]);
    }

    /**
     * Generate an academic results report.
     * GET /reports/academic
     */
    public function academicReports(): void
    {
        $this->requireAuth();
        $this->requirePermission('reports.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Accountant']);

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 50;

        $result = $this->paginate('exam_results', $page, $perPage, [], 'created_at.desc');

        $this->renderWithLayout('reports.index', [
            'pageTitle'   => 'Academic Results Report',
            'currentPage' => 'reports',
            'reportData'  => $result['data'],
            'reportType'  => 'results',
            'reportTitle' => 'Exam Results Report',
            'filters'     => ['exam_id' => $examId, 'class_id' => $classId, 'subject_id' => $subjectId],
        ]);
    }

    /**
     * Generate a financial report.
     * GET /reports/financial
     */
    public function financialReports(): void
    {
        $this->requireAuth();
        $this->requirePermission('reports.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Accountant']);

        $filters = [];
        $status = $this->input('status', '');
        $dateFrom = $this->input('date_from', '');
        $dateTo = $this->input('date_to', '');

        if (!empty($status)) {
            $filters['status'] = ['eq' => $status];
        }

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 50;

        $result = $this->paginate('payments', $page, $perPage, $filters, 'created_at.desc');

        // Calculate summary totals
        $summary = [
            'total_collected' => 0,
            'total_pending'   => 0,
            'total_records'   => $result['total'],
        ];

        $this->renderWithLayout('reports.index', [
            'pageTitle'   => 'Fee Collection Report',
            'currentPage' => 'reports',
            'reportData'  => $result['data'],
            'reportType'  => 'fees',
            'reportTitle' => 'Fee Collection Report',
            'filters'     => ['status' => $status, 'date_from' => $dateFrom, 'date_to' => $dateTo],
            'summary'     => $summary,
        ]);
    }

    /**
     * Generate an attendance report.
     * GET /reports/attendance
     */
    public function attendanceReports(): void
    {
        $this->requireAuth();
        $this->requirePermission('reports.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Accountant']);

        $filters = [];
        $classId = $this->input('class_id', '');
        $dateFrom = $this->input('date_from', '');
        $dateTo = $this->input('date_to', '');

        if (!empty($classId)) {
            $filters['class_id'] = ['eq' => $classId];
        }

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 50;

        $result = $this->paginate('attendance', $page, $perPage, $filters, 'date.desc');

        $this->renderWithLayout('reports.index', [
            'pageTitle'   => 'Attendance Report',
            'currentPage' => 'reports',
            'reportData'  => $result['data'],
            'reportType'  => 'attendance',
            'reportTitle' => 'Attendance Report',
            'filters'     => ['class_id' => $classId, 'date_from' => $dateFrom, 'date_to' => $dateTo],
        ]);
    }

    /**
     * Export a report as CSV.
     * GET /reports/export/{type}
     */
    public function export(string $type): void
    {
        $this->requireAuth();
        $this->requirePermission('reports.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Accountant']);

        $allowedTypes = ['enrollment', 'fees', 'attendance', 'results'];
        if (!in_array($type, $allowedTypes)) {
            error_msg('Invalid report type.');
            $this->redirect('/reports');
            return;
        }

        $data = [];
        $headers = [];
        $filename = '';

        switch ($type) {
            case 'enrollment':
                $results = $this->db->select('users', ['userType' => ['eq' => 'student']], 'first_name.asc', 10000);
                $headers = ['Name', 'Email', 'Class', 'Status', 'Enrolled Date'];
                foreach ($results as $row) {
                    $data[] = [
                        $row['firstName'] ?? '',
                        $row['email'] ?? '',
                        $row['classId'] ?? '',
                        $row['status'] ?? '',
                        $row['createdAt'] ?? '',
                    ];
                }
                $filename = 'student_enrollment_' . date('Y-m-d') . '.csv';
                break;

            case 'fees':
                $results = $this->db->select('payments', [], 'created_at.desc', 10000);
                $headers = ['Student', 'Amount', 'Status', 'Payment Date', 'Method'];
                foreach ($results as $row) {
                    $data[] = [
                        $row['student_id'] ?? '',
                        $row['amount'] ?? 0,
                        $row['status'] ?? '',
                        $row['created_at'] ?? '',
                        $row['payment_method'] ?? '',
                    ];
                }
                $filename = 'fee_collection_' . date('Y-m-d') . '.csv';
                break;

            case 'attendance':
                $results = $this->db->select('attendance', [], 'date.desc', 10000);
                $headers = ['Student', 'Date', 'Status', 'Class'];
                foreach ($results as $row) {
                    $data[] = [
                        $row['student_id'] ?? '',
                        $row['date'] ?? '',
                        $row['status'] ?? '',
                        $row['class_id'] ?? '',
                    ];
                }
                $filename = 'attendance_report_' . date('Y-m-d') . '.csv';
                break;

            case 'results':
                $results = $this->db->select('exam_results', [], 'created_at.desc', 10000);
                $headers = ['Student', 'Exam', 'Marks', 'Grade', 'Remarks'];
                foreach ($results as $row) {
                    $data[] = [
                        $row['studentId'] ?? '',
                        $row['examId'] ?? '',
                        $row['marksObtained'] ?? 0,
                        $row['grade'] ?? '',
                        $row['remarks'] ?? '',
                    ];
                }
                $filename = 'exam_results_' . date('Y-m-d') . '.csv';
                break;
        }

        // Generate CSV output
        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, $headers);
        foreach ($data as $row) {
            fputcsv($csv, $row);
        }
        rewind($csv);
        $csvContent = stream_get_contents($csv);
        fclose($csv);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($csvContent));
        echo $csvContent;
        exit;
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: Generate student report.
     * GET /api/reports/students
     */
    public function apiStudentReports(): void
    {
        $this->requireAuth();
        $this->requirePermission('reports.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Accountant']);

        $filters = [];
        $classId = $this->input('class_id', '');
        $status = $this->input('status', '');

        if (!empty($classId)) $filters['class_id'] = ['eq' => $classId];
        if (!empty($status)) $filters['status'] = ['eq' => $status];

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = (int) ($this->input('per_page', 50) ?: 50);

        $result = $this->paginate('users', $page, $perPage, ['userType' => ['eq' => 'student']], 'first_name.asc');
        $this->success($result);
    }

    /**
     * API: Generate academic report.
     * GET /api/reports/academic
     */
    public function apiAcademicReports(): void
    {
        $this->requireAuth();
        $this->requirePermission('reports.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Accountant']);

        $filters = [];
        $examId = $this->input('exam_id', '');
        $classId = $this->input('class_id', '');
        $subjectId = $this->input('subject_id', '');

        if (!empty($examId)) $filters['exam_id'] = ['eq' => $examId];
        if (!empty($classId)) $filters['class_id'] = ['eq' => $classId];
        if (!empty($subjectId)) $filters['subject_id'] = ['eq' => $subjectId];

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = (int) ($this->input('per_page', 50) ?: 50);

        $result = $this->paginate('exam_results', $page, $perPage, [], 'created_at.desc');
        $this->success($result);
    }

    /**
     * API: Generate financial report.
     * GET /api/reports/financial
     */
    public function apiFinancialReports(): void
    {
        $this->requireAuth();
        $this->requirePermission('reports.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Accountant']);

        $filters = [];
        $status = $this->input('status', '');
        if (!empty($status)) $filters['status'] = ['eq' => $status];

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = (int) ($this->input('per_page', 50) ?: 50);

        $result = $this->paginate('payments', $page, $perPage, $filters, 'created_at.desc');
        $this->success($result);
    }

    /**
     * API: Generate attendance report.
     * GET /api/reports/attendance
     */
    public function apiAttendanceReports(): void
    {
        $this->requireAuth();
        $this->requirePermission('reports.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Accountant']);

        $filters = [];
        $classId = $this->input('class_id', '');
        if (!empty($classId)) $filters['class_id'] = ['eq' => $classId];

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = (int) ($this->input('per_page', 50) ?: 50);

        $result = $this->paginate('attendance', $page, $perPage, $filters, 'date.desc');
        $this->success($result);
    }

    /**
     * API: Export report as CSV.
     * POST /api/reports/export
     */
    public function apiExport(): void
    {
        $this->requireAuth();
        $this->requirePermission('reports.view');
        $this->requireRole(['SuperAdmin', 'SchoolAdmin', 'BranchAdmin', 'Dean', 'Accountant']);

        $type = $this->input('type', '');
        $allowedTypes = ['enrollment', 'fees', 'attendance', 'results'];

        if (!in_array($type, $allowedTypes)) {
            $this->error('Invalid report type.', 422);
            return;
        }

        $data = [];
        $headers = [];

        switch ($type) {
            case 'enrollment':
                $results = $this->db->select('users', ['userType' => ['eq' => 'student']], 'first_name.asc', 10000);
                $headers = ['Name', 'Email', 'Class', 'Status', 'Enrolled Date'];
                foreach ($results as $row) {
                    $data[] = [$row['firstName'] ?? '', $row['email'] ?? '', $row['classId'] ?? '', $row['status'] ?? '', $row['createdAt'] ?? ''];
                }
                break;
            case 'fees':
                $results = $this->db->select('payments', [], 'created_at.desc', 10000);
                $headers = ['Student', 'Amount', 'Status', 'Payment Date', 'Method'];
                foreach ($results as $row) {
                    $data[] = [$row['student_id'] ?? '', $row['amount'] ?? 0, $row['status'] ?? '', $row['created_at'] ?? '', $row['payment_method'] ?? ''];
                }
                break;
            case 'attendance':
                $results = $this->db->select('attendance', [], 'date.desc', 10000);
                $headers = ['Student', 'Date', 'Status', 'Class'];
                foreach ($results as $row) {
                    $data[] = [$row['student_id'] ?? '', $row['date'] ?? '', $row['status'] ?? '', $row['class_id'] ?? ''];
                }
                break;
            case 'results':
                $results = $this->db->select('exam_results', [], 'created_at.desc', 10000);
                $headers = ['Student', 'Exam', 'Marks', 'Grade', 'Remarks'];
                foreach ($results as $row) {
                    $data[] = [$row['studentId'] ?? '', $row['examId'] ?? '', $row['marksObtained'] ?? 0, $row['grade'] ?? '', $row['remarks'] ?? ''];
                }
                break;
        }

        $this->success([
            'type'    => $type,
            'headers' => $headers,
            'data'    => $data,
            'total'   => count($data),
        ]);
    }
}
