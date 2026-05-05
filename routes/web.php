<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| These routes return HTML views. The router will match incoming GET/POST
| requests and dispatch them to the appropriate controller method.
|
*/

use Core\Router;
use Core\Session;

// ─── Root Route ──────────────────────────────────────────────────────────────

Router::get('/', function () {
    if (isLoggedIn()) {
        redirect('/dashboard');
    }
    // Check if public website is active
    $db = new \Core\Database(
        (string) config('db_host', 'localhost'),
        (string) config('db_port', '3306'),
        (string) config('db_name', 'school_erp'),
        (string) config('db_user', 'root'),
        (string) config('db_password', '')
    );
    try {
        $ws = $db->single('website_settings', ['isActive' => ['eq' => 1]]);
        if ($ws) {
            redirect('/p');
        }
    } catch (\RuntimeException $e) {
        // Table may not exist yet, fall through
    }
    redirect('/login');
});

// ─── Authentication Routes ───────────────────────────────────────────────────

Router::get('/login', 'AuthController@login');
Router::post('/login', 'AuthController@doLogin');
Router::get('/logout', 'AuthController@logout');
Router::get('/register', 'AuthController@register');
Router::post('/register', 'AuthController@doRegister');
Router::get('/forgot-password', 'AuthController@forgotPassword');
Router::post('/forgot-password', 'AuthController@doForgotPassword');
Router::get('/reset-password', 'AuthController@resetPassword');
Router::post('/reset-password', 'AuthController@doResetPassword');

// ─── Dashboard ───────────────────────────────────────────────────────────────

Router::get('/dashboard', 'DashboardController@index');

// ─── School Management ───────────────────────────────────────────────────────

Router::get('/schools', 'SchoolController@index');
Router::get('/schools/create', 'SchoolController@create');
Router::post('/schools', 'SchoolController@store');
Router::get('/schools/{id}', 'SchoolController@show');
Router::get('/schools/{id}/edit', 'SchoolController@edit');
Router::post('/schools/{id}', 'SchoolController@update');
Router::post('/schools/{id}/delete', 'SchoolController@delete');

// ─── Branch Management ───────────────────────────────────────────────────────

Router::get('/branches', 'BranchController@index');
Router::get('/branches/create', 'BranchController@create');
Router::post('/branches', 'BranchController@store');
Router::get('/branches/{id}', 'BranchController@show');
Router::get('/branches/{id}/edit', 'BranchController@edit');
Router::post('/branches/{id}', 'BranchController@update');
Router::post('/branches/{id}/delete', 'BranchController@delete');

// ─── User Management ─────────────────────────────────────────────────────────

Router::get('/users', 'UserController@index');
Router::get('/users/create', 'UserController@create');
Router::post('/users', 'UserController@store');
Router::get('/users/{id}', 'UserController@show');
Router::get('/users/{id}/edit', 'UserController@edit');
Router::post('/users/{id}', 'UserController@update');
Router::post('/users/{id}/delete', 'UserController@delete');
Router::get('/users/{id}/profile', 'UserController@profile');

// ─── Role Management ─────────────────────────────────────────────────────────

Router::get('/roles', 'RoleController@index');
Router::get('/roles/create', 'RoleController@create');
Router::post('/roles', 'RoleController@store');
Router::get('/roles/{id}', 'RoleController@show');
Router::get('/roles/{id}/edit', 'RoleController@edit');
Router::post('/roles/{id}', 'RoleController@update');
Router::post('/roles/{id}/delete', 'RoleController@delete');

// ─── Module Management ───────────────────────────────────────────────────────

Router::get('/modules', 'ModuleController@index');
Router::get('/modules/create', 'ModuleController@create');
Router::post('/modules', 'ModuleController@store');
Router::get('/modules/{id}', 'ModuleController@show');
Router::get('/modules/{id}/edit', 'ModuleController@edit');
Router::post('/modules/{id}', 'ModuleController@update');
Router::post('/modules/{id}/delete', 'ModuleController@delete');

// ─── Student Management ──────────────────────────────────────────────────────

Router::get('/students', 'StudentController@index');
Router::get('/students/create', 'StudentController@create');
Router::post('/students', 'StudentController@store');
Router::get('/students/{id}', 'StudentController@show');
Router::get('/students/{id}/edit', 'StudentController@edit');
Router::post('/students/{id}', 'StudentController@update');
Router::post('/students/{id}/delete', 'StudentController@delete');

// ─── Class Management ────────────────────────────────────────────────────────

Router::get('/classes', 'ClassController@index');
Router::get('/classes/create', 'ClassController@create');
Router::post('/classes', 'ClassController@store');
Router::get('/classes/{id}', 'ClassController@show');
Router::get('/classes/{id}/edit', 'ClassController@edit');
Router::post('/classes/{id}', 'ClassController@update');
Router::post('/classes/{id}/delete', 'ClassController@delete');

// ─── Subject Management ──────────────────────────────────────────────────────

Router::get('/subjects', 'SubjectController@index');
Router::get('/subjects/create', 'SubjectController@create');
Router::post('/subjects', 'SubjectController@store');
Router::get('/subjects/{id}', 'SubjectController@show');
Router::get('/subjects/{id}/edit', 'SubjectController@edit');
Router::post('/subjects/{id}', 'SubjectController@update');
Router::post('/subjects/{id}/delete', 'SubjectController@delete');

// ─── Exam Management ─────────────────────────────────────────────────────────

Router::get('/exams', 'ExamController@index');
Router::get('/exams/create', 'ExamController@create');
Router::post('/exams', 'ExamController@store');
Router::get('/exams/{id}', 'ExamController@show');
Router::get('/exams/{id}/edit', 'ExamController@edit');
Router::post('/exams/{id}', 'ExamController@update');
Router::post('/exams/{id}/delete', 'ExamController@delete');

// ─── Results Management ──────────────────────────────────────────────────────

Router::get('/results', 'ResultController@index');
Router::get('/results/create', 'ResultController@create');
Router::post('/results', 'ResultController@store');
Router::get('/results/{id}', 'ResultController@show');
Router::get('/results/{id}/edit', 'ResultController@edit');
Router::post('/results/{id}', 'ResultController@update');
Router::post('/results/{id}/delete', 'ResultController@delete');

// ─── Attendance Management ───────────────────────────────────────────────────

Router::get('/attendance', 'AttendanceController@index');
Router::get('/attendance/take', 'AttendanceController@take');
Router::post('/attendance', 'AttendanceController@store');
Router::get('/attendance/{id}', 'AttendanceController@show');
Router::get('/attendance/{id}/edit', 'AttendanceController@edit');
Router::post('/attendance/{id}', 'AttendanceController@update');
Router::post('/attendance/{id}/delete', 'AttendanceController@delete');

// ─── Fee Management ──────────────────────────────────────────────────────────

Router::get('/fees', 'FeeController@index');
Router::get('/fees/create', 'FeeController@create');
Router::post('/fees', 'FeeController@store');
Router::get('/fees/{id}', 'FeeController@show');
Router::get('/fees/{id}/edit', 'FeeController@edit');
Router::post('/fees/{id}', 'FeeController@update');
Router::post('/fees/{id}/delete', 'FeeController@delete');

// ─── Payment Management ──────────────────────────────────────────────────────

Router::get('/payments', 'PaymentController@index');
Router::get('/payments/create', 'PaymentController@create');
Router::post('/payments', 'PaymentController@store');
Router::get('/payments/{id}', 'PaymentController@show');
Router::get('/payments/{id}/edit', 'PaymentController@edit');
Router::post('/payments/{id}', 'PaymentController@update');
Router::post('/payments/{id}/delete', 'PaymentController@delete');
Router::get('/payments/{id}/receipt', 'PaymentController@receipt');

// ─── Timetable Management ────────────────────────────────────────────────────

Router::get('/timetable', 'TimetableController@index');
Router::get('/timetable/create', 'TimetableController@create');
Router::post('/timetable', 'TimetableController@store');
Router::get('/timetable/{id}', 'TimetableController@show');
Router::get('/timetable/{id}/edit', 'TimetableController@edit');
Router::post('/timetable/{id}', 'TimetableController@update');
Router::post('/timetable/{id}/delete', 'TimetableController@delete');

// ─── Library Management ──────────────────────────────────────────────────────

Router::get('/library', 'LibraryController@index');
Router::get('/library/books', 'LibraryController@books');
Router::get('/library/books/create', 'LibraryController@createBook');
Router::post('/library/books', 'LibraryController@storeBook');
Router::get('/library/books/{id}', 'LibraryController@showBook');
Router::get('/library/books/{id}/edit', 'LibraryController@editBook');
Router::post('/library/books/{id}', 'LibraryController@updateBook');
Router::post('/library/books/{id}/delete', 'LibraryController@deleteBook');
Router::get('/library/issue', 'LibraryController@issue');
Router::post('/library/issue', 'LibraryController@storeIssue');
Router::get('/library/returns', 'LibraryController@returns');

// ─── Transport Management ────────────────────────────────────────────────────

Router::get('/transport', 'TransportController@index');
Router::get('/transport/routes', 'TransportController@routes');
Router::get('/transport/routes/create', 'TransportController@createRoute');
Router::post('/transport/routes', 'TransportController@storeRoute');
Router::get('/transport/vehicles', 'TransportController@vehicles');
Router::get('/transport/vehicles/create', 'TransportController@createVehicle');
Router::post('/transport/vehicles', 'TransportController@storeVehicle');
Router::get('/transport/assignments', 'TransportController@assignments');

// ─── Hostel Management ───────────────────────────────────────────────────────

Router::get('/hostel', 'HostelController@index');
Router::get('/hostel/rooms', 'HostelController@rooms');
Router::get('/hostel/rooms/create', 'HostelController@createRoom');
Router::post('/hostel/rooms', 'HostelController@storeRoom');
Router::get('/hostel/rooms/{id}', 'HostelController@showRoom');
Router::get('/hostel/rooms/{id}/edit', 'HostelController@editRoom');
Router::post('/hostel/rooms/{id}', 'HostelController@updateRoom');
Router::post('/hostel/rooms/{id}/delete', 'HostelController@deleteRoom');
Router::get('/hostel/allocations', 'HostelController@allocations');
Router::get('/hostel/allocations/create', 'HostelController@createAllocation');
Router::post('/hostel/allocations', 'HostelController@storeAllocation');

// ─── LMS (Learning Management System) ────────────────────────────────────────

Router::get('/lms', 'LmsController@index');
Router::get('/lms/courses', 'LmsController@courses');
Router::get('/lms/courses/create', 'LmsController@createCourse');
Router::post('/lms/courses', 'LmsController@storeCourse');
Router::get('/lms/courses/{id}', 'LmsController@showCourse');
Router::get('/lms/courses/{id}/edit', 'LmsController@editCourse');
Router::post('/lms/courses/{id}', 'LmsController@updateCourse');
Router::get('/lms/assignments', 'LmsController@assignments');
Router::get('/lms/assignments/create', 'LmsController@createAssignment');
Router::post('/lms/assignments', 'LmsController@storeAssignment');
Router::get('/lms/submissions', 'LmsController@submissions');
Router::get('/lms/courses/{id}/delete', 'LmsController@deleteCourse');
Router::post('/lms/courses/{id}/delete', 'LmsController@deleteCourse');

// ─── Communication ───────────────────────────────────────────────────────────

Router::get('/communication', 'CommunicationController@index');
Router::get('/communication/messages', 'CommunicationController@messages');
Router::get('/communication/messages/create', 'CommunicationController@createMessage');
Router::post('/communication/messages', 'CommunicationController@storeMessage');
Router::get('/communication/notices', 'CommunicationController@notices');
Router::get('/communication/notices/create', 'CommunicationController@createNotice');
Router::post('/communication/notices', 'CommunicationController@storeNotice');
Router::post('/communication/{id}/read', 'CommunicationController@markAsRead');
Router::post('/communication/{id}/delete', 'CommunicationController@delete');

// ─── Reports ─────────────────────────────────────────────────────────────────

Router::get('/reports', 'ReportController@index');
Router::get('/reports/students', 'ReportController@studentReports');
Router::get('/reports/academic', 'ReportController@academicReports');
Router::get('/reports/financial', 'ReportController@financialReports');
Router::get('/reports/attendance', 'ReportController@attendanceReports');
Router::get('/reports/export/{type}', 'ReportController@export');

// ─── Skills Management ───────────────────────────────────────────────────────

Router::get('/skills', 'SkillController@index');
Router::get('/skills/create', 'SkillController@create');
Router::post('/skills', 'SkillController@store');
Router::get('/skills/{id}', 'SkillController@show');
Router::get('/skills/{id}/edit', 'SkillController@edit');
Router::post('/skills/{id}', 'SkillController@update');
Router::post('/skills/{id}/delete', 'SkillController@delete');

// ─── AI Assistant ────────────────────────────────────────────────────────────

Router::get('/ai-chat', 'AIController@chat');
Router::get('/ai-settings', 'AIController@settings');
Router::post('/ai-settings', 'AIController@settings');
Router::get('/ai-analytics', 'AIController@analytics');

// ─── Profile / Settings ──────────────────────────────────────────────────────

Router::get('/profile', 'ProfileController@index');
Router::post('/profile', 'ProfileController@update');
Router::get('/settings', 'SettingController@index');
Router::post('/settings', 'SettingController@update');

// ─── Website Management (Admin) ─────────────────────────────────────────────

Router::get('/website', 'WebsiteController@index');
Router::get('/website/settings', 'WebsiteController@settings');
Router::post('/website/settings', 'WebsiteController@saveSettings');
Router::get('/website/pages', 'WebsiteController@pages');
Router::get('/website/pages/create', 'WebsiteController@createPage');
Router::post('/website/pages', 'WebsiteController@storePage');
Router::get('/website/pages/{id}/edit', 'WebsiteController@editPage');
Router::post('/website/pages/{id}', 'WebsiteController@updatePage');
Router::post('/website/pages/{id}/delete', 'WebsiteController@deletePage');
Router::get('/website/menu', 'WebsiteController@menu');
Router::post('/website/menu', 'WebsiteController@addMenuItem');
Router::post('/website/menu/{id}', 'WebsiteController@updateMenuItem');
Router::post('/website/menu/{id}/delete', 'WebsiteController@deleteMenuItem');
Router::get('/website/media', 'WebsiteController@media');
Router::post('/website/media/upload', 'WebsiteController@uploadMedia');
Router::post('/website/media/{id}/delete', 'WebsiteController@deleteMedia');
Router::post('/website/toggle', 'WebsiteController@toggleWebsite');
Router::post('/website/pages/{id}/publish', 'WebsiteController@publishPage');
Router::get('/website/preview/{slug}', 'WebsiteController@previewPage');

// ─── Admission Management (Admin) ───────────────────────────────────────────

Router::get('/admission', 'AdmissionController@index');
Router::get('/admission/settings', 'AdmissionController@settings');
Router::post('/admission/settings', 'AdmissionController@saveSettings');
Router::get('/admission/fields', 'AdmissionController@fields');
Router::get('/admission/fields/create', 'AdmissionController@addField');
Router::post('/admission/fields', 'AdmissionController@storeField');
Router::get('/admission/fields/{id}/edit', 'AdmissionController@editField');
Router::post('/admission/fields/{id}', 'AdmissionController@updateField');
Router::post('/admission/fields/{id}/delete', 'AdmissionController@deleteField');
Router::get('/admission/applications', 'AdmissionController@applications');
Router::get('/admission/applications/{id}', 'AdmissionController@showApplication');
Router::post('/admission/applications/{id}/review', 'AdmissionController@reviewApplication');
Router::post('/admission/applications/{id}/delete', 'AdmissionController@deleteApplication');
Router::post('/admission/toggle', 'AdmissionController@toggleAdmission');
Router::get('/admission/export', 'AdmissionController@exportApplications');

// ─── Public Website Routes ──────────────────────────────────────────────────

Router::get('/p', 'WebsiteController@publicHome');
Router::get('/p/about', 'WebsiteController@publicAbout');
Router::get('/p/contact', 'WebsiteController@publicContact');
Router::get('/p/classes', 'WebsiteController@publicClasses');
Router::get('/p/admission', 'AdmissionController@form');
Router::post('/p/admission', 'AdmissionController@submitApplication');
Router::get('/p/admission/thank-you', 'AdmissionController@thankYou');
Router::get('/p/page/{slug}', 'WebsiteController@publicPage');
