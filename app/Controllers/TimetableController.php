<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * TimetableController
 *
 * Manages the weekly timetable with subject-teacher assignments per period.
 * Supports filtering by class and teacher, and a weekly grid view.
 */
class TimetableController extends Controller
{
    private const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    private const PERIODS = ['08:00', '09:00', '10:00', '11:00', '12:00', '14:00', '15:00'];

    /**
     * Display the weekly timetable grid view.
     */
    public function index(): void
    {
        $this->requireAuth();

        $classId = $this->input('class_id', '');
        $teacherId = $this->input('teacher_id', '');

        // Fetch classes and teachers for filters
        $classes = $this->db->select('classes', ['status' => ['eq' => 'active']], 'name.asc');
        $teachers = $this->db->select('users', ['role' => ['eq' => 'Teacher']], 'firstName.asc');
        $subjects = $this->db->select('subjects', ['status' => ['eq' => 'active']], 'name.asc');

        // Build timetable grid
        $grid = [];

        if ($classId !== '') {
            // Fetch all timetable slots for this class
            $slots = $this->db->select('timetable', [
                'classId' => ['eq' => $classId],
            ]);

            // Organize into grid: day -> start_time -> slot
            foreach ($slots as $slot) {
                $day = $slot['dayOfWeek'] ?? '';
                $startTime = $slot['startTime'] ?? '';
                $grid[$day][$startTime] = $slot;
            }
        } elseif ($teacherId !== '') {
            $slots = $this->db->select('timetable', [
                'teacherId' => ['eq' => $teacherId],
            ]);

            foreach ($slots as $slot) {
                $day = $slot['dayOfWeek'] ?? '';
                $startTime = $slot['startTime'] ?? '';
                $grid[$day][$startTime] = $slot;
            }
        }

        $selectedClassName = '';
        if ($classId !== '') {
            $cls = $this->db->find('classes', $classId);
            $selectedClassName = $cls['name'] ?? '';
        }

        $selectedTeacherName = '';
        if ($teacherId !== '') {
            $tch = $this->db->find('users', $teacherId);
            $selectedTeacherName = trim(($tch['firstName'] ?? '') . ' ' . ($tch['lastName'] ?? ''));
        }

        $this->renderWithLayout('timetable.index', [
            'pageTitle'           => 'Timetable',
            'currentPage'         => 'timetable',
            'classes'             => $classes,
            'teachers'            => $teachers,
            'subjects'            => $subjects,
            'grid'                => $grid,
            'days'                => self::DAYS,
            'periods'             => self::PERIODS,
            'classId'             => $classId,
            'teacherId'           => $teacherId,
            'selectedClassName'   => $selectedClassName,
            'selectedTeacherName' => $selectedTeacherName,
        ]);
    }

    /**
     * Store a new timetable slot.
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        $validation = $this->validate([
            'class_id'   => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'day_of_week'=> 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed.', 422, $validation['errors']);
        }

        $data = [
            'classId'    => $this->input('class_id'),
            'subjectId'  => $this->input('subject_id'),
            'teacherId'  => $this->input('teacher_id'),
            'dayOfWeek'  => $this->input('day_of_week'),
            'startTime'  => $this->input('start_time'),
            'endTime'    => $this->input('end_time'),
            'room'       => $this->input('room', ''),
        ];

        try {
            $this->db->insert('timetable', $data);
            $this->success(null, 'Timetable slot added successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to add timetable slot: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing timetable slot.
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        $slot = $this->db->find('timetable', $id);
        if (!$slot) {
            $this->error('Timetable slot not found.', 404);
        }

        $data = [
            'classId'    => $this->input('class_id'),
            'subjectId'  => $this->input('subject_id'),
            'teacherId'  => $this->input('teacher_id'),
            'dayOfWeek'  => $this->input('day_of_week'),
            'startTime'  => $this->input('start_time'),
            'endTime'    => $this->input('end_time'),
            'room'       => $this->input('room', ''),
        ];

        try {
            $this->db->updateById('timetable', $id, $data);
            $this->success(null, 'Timetable slot updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update timetable slot: ' . $e->getMessage());
        }
    }

    /**
     * Delete a timetable slot.
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        try {
            $this->db->deleteById('timetable', $id);
            $this->success(null, 'Timetable slot deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete timetable slot: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: Get timetable for a class.
     */
    public function apiShow(): void
    {
        $this->requireAuth();

        $classId = $this->input('class_id', '');
        $teacherId = $this->input('teacher_id', '');

        $filters = [];
        if ($classId !== '') $filters['classId'] = ['eq' => $classId];
        if ($teacherId !== '') $filters['teacherId'] = ['eq' => $teacherId];

        $slots = $this->db->select('timetable', $filters, 'dayOfWeek.asc,startTime.asc');

        // Enrich with subject and teacher names
        foreach ($slots as &$slot) {
            $subjId = $slot['subjectId'] ?? null;
            if ($subjId) {
                $subject = $this->db->find('subjects', $subjId);
                $slot['subjectName'] = $subject['name'] ?? 'N/A';
                $slot['subjectCode'] = $subject['code'] ?? '';
            }
            $tchId = $slot['teacherId'] ?? null;
            if ($tchId) {
                $teacher = $this->db->find('users', $tchId);
                $slot['teacherName'] = $teacher
                    ? trim(($teacher['firstName'] ?? '') . ' ' . ($teacher['lastName'] ?? ''))
                    : 'N/A';
            }
        }
        unset($slot);

        $this->success($slots);
    }

    /**
     * API: Store a timetable slot.
     */
    public function apiStore(): void
    {
        $this->store();
    }

    /**
     * API: Update a timetable slot.
     */
    public function apiUpdate(string $id): void
    {
        $this->update($id);
    }

    /**
     * API: Delete a timetable slot.
     */
    public function apiDelete(string $id): void
    {
        $this->delete($id);
    }
}
