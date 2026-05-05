<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

/**
 * ResultController
 *
 * Manages exam results: recording individual and bulk entries,
 * viewing results with grade calculations and filtering.
 */
class ResultController extends Controller
{
    /**
     * List all results with optional filters.
     */
    public function index(): void
    {
        $this->requireAuth();

        $filters = [];
        $examId = $this->input('exam_id', '');
        $classId = $this->input('class_id', '');
        $subjectId = $this->input('subject_id', '');
        $studentId = $this->input('student_id', '');

        if ($examId !== '') {
            $filters['examId'] = ['eq' => $examId];
        }
        if ($studentId !== '') {
            $filters['studentId'] = ['eq' => $studentId];
        }

        $page = (int) ($this->input('page', 1) ?: 1);
        $perPage = 25;

        $result = $this->paginate('exam_results', $page, $perPage, $filters, 'created_at.desc');

        // Enrich results with exam, student, and subject info
        $results = $result['data'];
        foreach ($results as &$res) {
            $examIdVal = $res['examId'] ?? null;
            if ($examIdVal) {
                $exam = $this->db->find('exams', $examIdVal);
                $res['examName'] = $exam['name'] ?? 'N/A';
                $res['totalMarks'] = $exam['totalMarks'] ?? 100;

                // Get subject name from exam
                $subjId = $exam['subjectId'] ?? null;
                if ($subjId) {
                    $subject = $this->db->find('subjects', $subjId);
                    $res['subjectName'] = $subject['name'] ?? 'N/A';
                } else {
                    $res['subjectName'] = 'N/A';
                }

                // Get class name from exam
                $clsId = $exam['classId'] ?? null;
                if ($clsId) {
                    $class = $this->db->find('classes', $clsId);
                    $res['className'] = $class['name'] ?? 'N/A';
                } else {
                    $res['className'] = 'N/A';
                }
            }

            $stuId = $res['studentId'] ?? null;
            if ($stuId) {
                $student = $this->db->find('users', $stuId);
                $res['studentName'] = $student
                    ? trim(($student['firstName'] ?? '') . ' ' . ($student['lastName'] ?? ''))
                    : 'Unknown';
            } else {
                $res['studentName'] = 'Unknown';
            }

            // Calculate percentage and grade
            $marks = (float) ($res['marksObtained'] ?? 0);
            $total = (float) ($res['totalMarks'] ?? 100);
            $res['percentage'] = $total > 0 ? round(($marks / $total) * 100, 1) : 0;
            $res['calculatedGrade'] = $this->calculateGrade($res['percentage']);
        }
        unset($res);

        // Fetch dropdown data for filters
        $exams = $this->db->select('exams', [], 'name.asc', 100);
        $classes = $this->db->select('classes', [], 'name.asc', 100);
        $subjects = $this->db->select('subjects', [], 'name.asc', 100);

        // Get students if class is selected
        $students = [];
        if ($classId !== '') {
            $students = $this->db->select('users', ['role' => ['eq' => 'Student'], 'classId' => ['eq' => $classId]], 'firstName.asc', 100);
        }

        $this->view('results.index', [
            'pageTitle' => 'Exam Results',
            'results'   => $results,
            'exams'     => $exams,
            'classes'   => $classes,
            'subjects'  => $subjects,
            'students'  => $students,
            'total'     => $result['total'],
            'page'      => $result['page'],
            'perPage'   => $result['perPage'],
            'lastPage'  => $result['lastPage'],
            'examId'    => $examId,
            'classId'   => $classId,
            'subjectId' => $subjectId,
            'studentId' => $studentId,
        ]);
    }

    /**
     * Store a single result.
     */
    public function store(): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal', 'Teacher']);

        $validation = $this->validate([
            'exam_id'        => 'required',
            'student_id'     => 'required',
            'marks_obtained' => 'required|numeric',
        ]);

        if (!$validation['valid']) {
            $this->error('Validation failed.', 422, $validation['errors']);
        }

        $marks = (float) $this->input('marks_obtained', 0);
        $grade = $this->calculateGradeFromMarks($marks, $this->input('total_marks', 100));

        $data = [
            'examId'        => $this->input('exam_id'),
            'studentId'     => $this->input('student_id'),
            'marksObtained' => $marks,
            'grade'         => $grade,
            'remarks'       => $this->input('remarks', ''),
        ];

        try {
            $this->db->insert('exam_results', $data);
            $this->success(null, 'Result saved successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to save result: ' . $e->getMessage());
        }
    }

    /**
     * Store bulk results (multiple students at once).
     */
    public function bulkStore(): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal', 'Teacher']);

        $examId = $this->input('exam_id', '');
        $results = $this->input('results', []);

        if (empty($examId) || empty($results)) {
            $this->error('Exam ID and results are required.', 422);
            return;
        }

        // Fetch exam for total marks
        $exam = $this->db->find('exams', $examId);
        $totalMarks = (float) ($exam['totalMarks'] ?? 100);

        $saved = 0;
        foreach ($results as $item) {
            if (empty($item['student_id'])) {
                continue;
            }

            $marks = (float) ($item['marks_obtained'] ?? 0);
            $grade = $this->calculateGradeFromMarks($marks, $totalMarks);

            $data = [
                'examId'        => $examId,
                'studentId'     => $item['student_id'],
                'marksObtained' => $marks,
                'grade'         => $grade,
                'remarks'       => $item['remarks'] ?? '',
            ];

            try {
                // Check if result already exists
                $existing = $this->db->single('exam_results', [
                    'examId'    => ['eq' => $examId],
                    'studentId' => ['eq' => $item['student_id']],
                ]);

                if ($existing) {
                    $this->db->updateById('exam_results', $existing['id'], $data);
                } else {
                    $this->db->insert('exam_results', $data);
                }
                $saved++;
            } catch (\RuntimeException $e) {
                // Continue with next record
            }
        }

        $this->success(['saved' => $saved], "Results saved successfully for {$saved} students.");
    }

    /**
     * Update a single result.
     */
    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal', 'Teacher']);

        $result = $this->db->find('exam_results', $id);
        if (!$result) {
            $this->error('Result not found.', 404);
        }

        $marks = (float) $this->input('marks_obtained', 0);
        $exam = $this->db->find('exams', $result['examId'] ?? '');
        $totalMarks = (float) ($exam['totalMarks'] ?? 100);

        $data = [
            'marksObtained' => $marks,
            'grade'         => $this->calculateGradeFromMarks($marks, $totalMarks),
            'remarks'       => $this->input('remarks', ''),
        ];

        try {
            $this->db->updateById('exam_results', $id, $data);
            $this->success(null, 'Result updated successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to update result: ' . $e->getMessage());
        }
    }

    /**
     * Delete a result.
     */
    public function delete(string $id): void
    {
        $this->requireAuth();
        $this->requireRole(['Admin', 'Principal']);

        try {
            $this->db->deleteById('exam_results', $id);
            $this->success(null, 'Result deleted successfully.');
        } catch (\RuntimeException $e) {
            $this->error('Failed to delete result: ' . $e->getMessage());
        }
    }

    /**
     * Get results for a specific exam (for bulk entry view).
     */
    public function examResults(string $examId): void
    {
        $this->requireAuth();

        $exam = $this->db->find('exams', $examId);
        if (!$exam) {
            $this->error('Exam not found.', 404);
            return;
        }

        $classId = $exam['classId'] ?? null;
        $students = [];
        if ($classId) {
            $students = $this->db->select('users', ['role' => ['eq' => 'Student'], 'classId' => ['eq' => $classId]], 'firstName.asc');
        }

        // Fetch existing results for this exam
        $existingResults = $this->db->select('exam_results', ['examId' => ['eq' => $examId]]);
        $resultsMap = [];
        foreach ($existingResults as $r) {
            $resultsMap[$r['studentId'] ?? ''] = $r;
        }

        // Merge students with results
        $studentResults = [];
        foreach ($students as $student) {
            $sid = $student['id'] ?? '';
            $existing = $resultsMap[$sid] ?? null;
            $studentResults[] = [
                'studentId'     => $sid,
                'studentName'   => trim(($student['firstName'] ?? '') . ' ' . ($student['lastName'] ?? '')),
                'marksObtained' => $existing['marksObtained'] ?? null,
                'grade'         => $existing['grade'] ?? null,
                'remarks'       => $existing['remarks'] ?? '',
                'resultId'      => $existing['id'] ?? null,
            ];
        }

        $this->success([
            'exam'           => $exam,
            'studentResults' => $studentResults,
            'totalMarks'     => $exam['totalMarks'] ?? 100,
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  API Methods
    // ─────────────────────────────────────────────────────────

    /**
     * API: List results as JSON.
     */
    public function apiIndex(): void
    {
        $this->requireAuth();

        $filters = [];
        $examId = $this->input('exam_id', '');
        $studentId = $this->input('student_id', '');

        if ($examId !== '') $filters['examId'] = ['eq' => $examId];
        if ($studentId !== '') $filters['studentId'] = ['eq' => $studentId];

        $results = $this->db->select('exam_results', $filters, 'created_at.desc');
        $this->success($results);
    }

    /**
     * API: Store a single result.
     */
    public function apiStore(): void
    {
        $this->store();
    }

    /**
     * API: Bulk store results.
     */
    public function apiBulkStore(): void
    {
        $this->bulkStore();
    }

    /**
     * API: Update a result.
     */
    public function apiUpdate(string $id): void
    {
        $this->update($id);
    }

    /**
     * API: Delete a result.
     */
    public function apiDelete(string $id): void
    {
        $this->delete($id);
    }

    /**
     * API: Get exam results for bulk entry.
     */
    public function apiExamResults(string $examId): void
    {
        $this->examResults($examId);
    }

    // ─────────────────────────────────────────────────────────
    //  Private Helpers
    // ─────────────────────────────────────────────────────────

    /**
     * Calculate grade from percentage.
     */
    private function calculateGrade(float $percentage): string
    {
        if ($percentage >= 80) return 'A';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';
        return 'F';
    }

    /**
     * Calculate grade from marks and total marks.
     */
    private function calculateGradeFromMarks(float $marks, float $total): string
    {
        $percentage = $total > 0 ? ($marks / $total) * 100 : 0;
        return $this->calculateGrade($percentage);
    }
}
