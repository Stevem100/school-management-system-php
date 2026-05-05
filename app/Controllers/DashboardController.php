<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Session;

/**
 * DashboardController
 *
 * Renders the main dashboard page with school statistics.
 * Uses MySQL database for live data queries.
 */
class DashboardController extends Controller
{
    /**
     * Dashboard index page.
     */
    public function index(): void
    {
        $this->requireAuth();

        $user      = $this->currentUser();
        $userRoles = Session::get('roles', []);

        // Fetch dashboard stats from DB
        $stats = $this->fetchDashboardStats();

        // Fetch recent activity
        $recentActivity = $this->fetchRecentActivity();

        // Fetch upcoming exams
        $upcomingExams = $this->fetchUpcomingExams();

        $branchName = $user['branch_name'] ?? 'Main Campus';
        $schoolName = $user['school_name'] ?? config('app_name', 'School Management System');

        $this->renderWithLayout('dashboard/index', [
            'pageTitle'      => 'Dashboard',
            'currentPage'    => 'dashboard',
            'user'           => $user,
            'userRoles'      => $userRoles,
            'stats'          => $stats,
            'recentActivity' => $recentActivity,
            'upcomingExams'  => $upcomingExams,
            'schoolName'     => $schoolName,
            'branchName'     => $branchName,
            'currentBranch'  => $branchName,
            'unreadCount'    => $this->getUnreadNotificationCount(),
            'notifications'  => $this->getRecentNotifications(),
        ]);
    }

    /**
     * Return dashboard stats as JSON.
     * GET /api/dashboard/stats
     */
    public function apiStats(): void
    {
        $this->requireAuth();

        $stats = $this->fetchDashboardStats();
        $this->success($stats);
    }

    public function apiActivities(): void
    {
        $this->requireAuth();
        $this->success($this->fetchRecentActivity());
    }

    public function apiChartData(): void
    {
        $this->requireAuth();
        $stats = $this->fetchDashboardStats();
        $this->success([
            'enrollment' => $stats['enrollment_data'] ?? [],
            'fees'       => $stats['fee_data'] ?? [],
        ]);
    }

    // ─────────────────────────────────────────────────────────
    //  Data Fetching Methods
    // ─────────────────────────────────────────────────────────

    private function fetchDashboardStats(): array
    {
        $stats = [
            'student_count'  => 0,
            'teacher_count'  => 0,
            'class_count'    => 0,
            'revenue'        => 0,
            'student_trend'  => 0,
            'teacher_trend'  => 0,
            'class_trend'    => 0,
            'revenue_trend'  => 0,
            'enrollment_data' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'values' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            ],
            'fee_data' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'values' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            ],
        ];

        try {
            // Student count
            $result = $this->db->raw("SELECT COUNT(*) as cnt FROM users WHERE user_type = 'student'");
            $stats['student_count'] = (int) ($result[0]['cnt'] ?? 0);

            // Teacher count
            $result = $this->db->raw("SELECT COUNT(*) as cnt FROM users WHERE user_type = 'teacher'");
            $stats['teacher_count'] = (int) ($result[0]['cnt'] ?? 0);

            // Class count
            $result = $this->db->raw("SELECT COUNT(*) as cnt FROM classes");
            $stats['class_count'] = (int) ($result[0]['cnt'] ?? 0);

            // Revenue
            $result = $this->db->raw("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'completed'");
            $stats['revenue'] = (float) ($result[0]['total'] ?? 0);

            // Monthly enrollment (by created_at month)
            $result = $this->db->raw(
                "SELECT DATE_FORMAT(created_at, '%b') as month, COUNT(*) as cnt
                 FROM users WHERE user_type = 'student' AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                 GROUP BY DATE_FORMAT(created_at, '%m'), DATE_FORMAT(created_at, '%b')
                 ORDER BY DATE_FORMAT(created_at, '%m')"
            );
            if (!empty($result)) {
                $stats['enrollment_data']['values'] = array_column($result, 'cnt');
            }

            // Monthly fee collection
            $result = $this->db->raw(
                "SELECT DATE_FORMAT(payment_date, '%b') as month, COALESCE(SUM(amount), 0) as total
                 FROM payments WHERE payment_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH) AND status = 'completed'
                 GROUP BY DATE_FORMAT(payment_date, '%m'), DATE_FORMAT(payment_date, '%b')
                 ORDER BY DATE_FORMAT(payment_date, '%m')"
            );
            if (!empty($result)) {
                $stats['fee_data']['values'] = array_column($result, 'total');
            }
        } catch (\RuntimeException $e) {
            // Return defaults on DB error
        }

        return $stats;
    }

    private function fetchRecentActivity(): array
    {
        try {
            $result = $this->db->raw(
                "SELECT a.action, a.description, a.created_at,
                        CASE
                            WHEN a.action LIKE '%student%' THEN 'student'
                            WHEN a.action LIKE '%payment%' THEN 'payment'
                            WHEN a.action LIKE '%exam%' THEN 'exam'
                            ELSE 'general'
                        END as type
                 FROM activity_log a
                 ORDER BY a.created_at DESC
                 LIMIT 6"
            );

            if (!empty($result)) {
                return array_map(function ($row) {
                    return [
                        'message'    => $row['description'] ?? $row['action'] ?? 'Activity recorded',
                        'time'       => timeAgo($row['created_at'] ?? null),
                        'icon_path'  => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z',
                        'bg_class'   => 'bg-emerald-100 dark:bg-emerald-900',
                        'icon_class' => 'text-emerald-600',
                    ];
                }, $result);
            }
        } catch (\RuntimeException $e) {
            // Fall through to defaults
        }

        return [
            ['message' => 'Welcome to the School Management System!', 'time' => 'Just now', 'icon_path' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z', 'bg_class' => 'bg-emerald-100 dark:bg-emerald-900', 'icon_class' => 'text-emerald-600'],
            ['message' => 'System is ready for use.', 'time' => 'Just now', 'icon_path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'bg_class' => 'bg-blue-100 dark:bg-blue-900', 'icon_class' => 'text-blue-600'],
        ];
    }

    private function fetchUpcomingExams(): array
    {
        try {
            $result = $this->db->raw(
                "SELECT name, type, start_date, end_date
                 FROM exams
                 WHERE start_date >= CURDATE()
                 ORDER BY start_date ASC
                 LIMIT 5"
            );

            if (!empty($result)) {
                return array_map(function ($row) {
                    $date = new \DateTime($row['start_date']);
                    return [
                        'name'    => $row['name'],
                        'subject' => $row['type'] ?? '',
                        'month'   => $date->format('M'),
                        'day'     => $date->format('d'),
                        'type'    => $row['type'] ?? 'Exam',
                    ];
                }, $result);
            }
        } catch (\RuntimeException $e) {
            // Fall through to defaults
        }

        return [];
    }

    private function getUnreadNotificationCount(): int
    {
        try {
            $result = $this->db->raw(
                "SELECT COUNT(*) as cnt FROM notifications WHERE user_id = ? AND is_read = 0",
                [$this->currentUserId()]
            );
            return (int) ($result[0]['cnt'] ?? 0);
        } catch (\RuntimeException $e) {
            return 0;
        }
    }

    private function getRecentNotifications(): array
    {
        try {
            return $this->db->raw(
                "SELECT message, created_at FROM notifications
                 WHERE user_id = ?
                 ORDER BY created_at DESC
                 LIMIT 5",
                [$this->currentUserId()]
            );
        } catch (\RuntimeException $e) {
            return [];
        }
    }
}
