<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

/**
 * AttendanceController
 *
 * Records and views daily student attendance.
 * Supports filtering by class, date range, and daily entry view.
 */
class AttendanceController extends Controller
{
    /**
     * Display attendance page with class selector and date picker.
     */
    public function index(): void
    {
        $this->requireAuth();

        $date = $this->input('date', date('Y-m-d'));
        $classId = $this->input('class_id', '');

        // Fetch all active classes for selector
        $classes = $this->db->select('classes', ['status' => ['eq' => 'active']], 'name.asc');

        $students = [];
        $attendance = [];
        $selectedClass = null;

        if ($classId !== '') {
            $selectedClass = $this->db->find('classes', $classId);

            // Fetch students in this class
            $students = $this->db->select('users', [
                'role'   => ['eq' => 'Student'],
                'classId' => ['eq' => $classId],
            ], 'firstName.asc');

            // Fetch existing attendance for this class and date
            $attendanceRecords = $this->db->select('attendance', [
                'classId' => ['eq' => $classId],
                'date'    => ['eq' => $date],
            ]);

            // Map attendance by student ID
            foreach ($attendanceRecords as $record) {
                $attendance[$record['studentId'] ?? ''] = $record;
            }
        }

        // Fetch attendance summary for the selected date
        $summary = $this->getAttendanceSummary($classId, $date);

        // Fetch attendance history if class selected
        $history = [];
        if ($classId !== '') {
            $history = $this->db->select('attendance', [
                'classId' => ['eq' => $classId],
                'date'    => ['gte' => date('Y-m-d', strtotime('-7 days'))],
            ], 'date.desc', 200);
        }

        $this->view('attendance.index', [
            'pageTitle'     => 'Attendance',
            'classes'       => $classes,
            'students'      => $students,
            'attendance'    => $attendance,
            'selectedClass' => $selectedClass,
            'date'          => $date,
            'classId'       => $classId,
            'summary'       => $summary,
            'history'       => $history,
        ]);
    }

    /**
     * Save attendance for a class on a specific date.
     * Accepts an array of student attendance records.
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal', 'Teacher']);

        $classId = $this->input('class_id', '');
        $date = $this->input('date', date('Y-m-d'));
        $records = $this->input('attendance', []);

        if (empty($classId) || empty($date)) {
            $this->error('Class and date are required.', 422);
            return;
        }

        $saved = 0;
        foreach ($records as $record) {
            $studentId = $record['student_id'] ?? '';
            $status = $record['status'] ?? '';

            if (empty($studentId) || empty($status)) {
                continue;
            }

            $data = [
                'studentId'  => $studentId,
                'classId'    => $classId,
                'date'       => $date,
                'status'     => $status,
                'remarks'    => $record['remarks'] ?? '',
                'recordedBy' => $this->currentUserId(),
            ];

            try {
                // Check if attendance already exists
                $existing = $this->db->single('attendance', [
                    'studentId' => ['eq' => $studentId],
                    'classId'   => ['eq' => $classId],
                    'date'      => ['eq' => $date],
                ]);

                if ($existing) {
                    $this->db->updateById('attendance', $existing['id'], $data);
                } else {
                    $this->db->insert('attendance', $data);
                }
                $saved++;
            } catch (\RuntimeException $e) {
                // Continue with next record
            }
        }

        $this->success(['saved' => $saved], "Attendance saved for {$saved} students.");
    }

    /**
     * Get attendance summary statistics.
     */
    private function getAttendanceSummary(string $classId, string $date): array
    {
        $default = ['present' => 0, 'absent' => 0, 'late' => 0, 'excused' => 0, 'total' => 0, 'rate' => 0];

        if ($classId === '') {
            return $default;
        }

        $records = $this->db->select('attendance', [
            'classId' => ['eq' => $classId],
            'date'    => ['eq' => $date],
        ]);

        $total = count($records);
        $present = 0;
        $absent = 0;
        $late = 0;
        $excused = 0;

        foreach ($records as $record) {
            $status = $record['status'] ?? '';
            switch ($status) {
                case 'present': $present++; break;
                case 'absent':  $absent++; break;
                case 'late':    $late++; break;
                case 'excused': $excused++; break;
            }
        }

        $rate = $total > 0 ? round((($present + $late) / $total) * 100, 1) : 0;

        return [
            'present' => $present,
            'absent'  => $absent,
            'late'    => $late,
            'excused' => $excused,
            'total'   => $total,
            'rate'    => $rate,
        ];
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: Get attendance for a class and date.
     */
    public function apiShow(): void
    {
        $this->requireAuth();

        $classId = $this->input('class_id', '');
        $date = $this->input('date', date('Y-m-d'));

        if (empty($classId)) {
            $this->error('Class ID is required.', 422);
            return;
        }

        $records = $this->db->select('attendance', [
            'classId' => ['eq' => $classId],
            'date'    => ['eq' => $date],
        ]);

        $summary = $this->getAttendanceSummary($classId, $date);

        $this->success([
            'records' => $records,
            'summary' => $summary,
        ]);
    }

    /**
     * API: Save attendance.
     */
    public function apiStore(): void
    {
        $this->store();
    }

    /**
     * API: Get attendance report with date range.
     */
    public function apiReport(): void
    {
        $this->requireAuth();

        $classId = $this->input('class_id', '');
        $studentId = $this->input('student_id', '');
        $startDate = $this->input('start_date', '');
        $endDate = $this->input('end_date', '');

        $filters = [];
        if ($classId !== '') $filters['classId'] = ['eq' => $classId];
        if ($studentId !== '') $filters['studentId'] = ['eq' => $studentId];
        if ($startDate !== '') $filters['date'] = ['gte' => $startDate];
        if ($endDate !== '' && $startDate !== '') $filters['date'] = ['and' => [
            ['gte' => $startDate],
            ['lte' => $endDate],
        ]];

        $records = $this->db->select('attendance', $filters, 'date.desc', 500);

        // Enrich with student names
        foreach ($records as &$record) {
            $stuId = $record['studentId'] ?? null;
            if ($stuId) {
                $student = $this->db->find('users', $stuId);
                $record['studentName'] = $student
                    ? trim(($student['firstName'] ?? '') . ' ' . ($student['lastName'] ?? ''))
                    : 'Unknown';
            }
        }
        unset($record);

        $this->success($records);
    }
}
