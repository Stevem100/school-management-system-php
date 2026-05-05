<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes return JSON responses for API consumption.
| They follow RESTful conventions for CRUD operations.
|
*/

use Core\Router;

// ─── Authentication API ─────────────────────────────────────────────────────

Router::post('/api/auth/login', 'AuthController@apiLogin');
Router::get('/api/auth/me', 'AuthController@apiMe');
Router::post('/api/auth/logout', 'AuthController@apiLogout');
Router::post('/api/auth/register', 'AuthController@apiRegister');
Router::post('/api/auth/forgot-password', 'AuthController@apiForgotPassword');
Router::post('/api/auth/reset-password', 'AuthController@apiResetPassword');

// ─── Dashboard API ───────────────────────────────────────────────────────────

Router::get('/api/dashboard', 'DashboardController@apiStats');
Router::get('/api/dashboard/activities', 'DashboardController@apiActivities');
Router::get('/api/dashboard/chart', 'DashboardController@apiChartData');

// ─── Schools API ─────────────────────────────────────────────────────────────

Router::get('/api/schools', 'SchoolController@apiIndex');
Router::post('/api/schools', 'SchoolController@apiStore');
Router::get('/api/schools/{id}', 'SchoolController@apiShow');
Router::put('/api/schools/{id}', 'SchoolController@apiUpdate');
Router::delete('/api/schools/{id}', 'SchoolController@apiDelete');

// ─── Branches API ────────────────────────────────────────────────────────────

Router::get('/api/branches', 'BranchController@apiIndex');
Router::post('/api/branches', 'BranchController@apiStore');
Router::get('/api/branches/{id}', 'BranchController@apiShow');
Router::put('/api/branches/{id}', 'BranchController@apiUpdate');
Router::delete('/api/branches/{id}', 'BranchController@apiDelete');

// ─── Users API ───────────────────────────────────────────────────────────────

Router::get('/api/users', 'UserController@apiIndex');
Router::post('/api/users', 'UserController@apiStore');
Router::get('/api/users/{id}', 'UserController@apiShow');
Router::put('/api/users/{id}', 'UserController@apiUpdate');
Router::delete('/api/users/{id}', 'UserController@apiDelete');

// ─── Roles API ───────────────────────────────────────────────────────────────

Router::get('/api/roles', 'RoleController@apiIndex');
Router::post('/api/roles', 'RoleController@apiStore');
Router::get('/api/roles/{id}', 'RoleController@apiShow');
Router::put('/api/roles/{id}', 'RoleController@apiUpdate');
Router::delete('/api/roles/{id}', 'RoleController@apiDelete');

// ─── Modules API ─────────────────────────────────────────────────────────────

Router::get('/api/modules', 'ModuleController@apiIndex');
Router::post('/api/modules', 'ModuleController@apiStore');
Router::get('/api/modules/{id}', 'ModuleController@apiShow');
Router::put('/api/modules/{id}', 'ModuleController@apiUpdate');
Router::delete('/api/modules/{id}', 'ModuleController@apiDelete');

// ─── Students API ────────────────────────────────────────────────────────────

Router::get('/api/students', 'StudentController@apiIndex');
Router::post('/api/students', 'StudentController@apiStore');
Router::get('/api/students/{id}', 'StudentController@apiShow');
Router::put('/api/students/{id}', 'StudentController@apiUpdate');
Router::delete('/api/students/{id}', 'StudentController@apiDelete');
Router::get('/api/students/{id}/results', 'StudentController@apiResults');
Router::get('/api/students/{id}/attendance', 'StudentController@apiAttendance');
Router::get('/api/students/{id}/fees', 'StudentController@apiFees');

// ─── Classes API ─────────────────────────────────────────────────────────────

Router::get('/api/classes', 'ClassController@apiIndex');
Router::post('/api/classes', 'ClassController@apiStore');
Router::get('/api/classes/{id}', 'ClassController@apiShow');
Router::put('/api/classes/{id}', 'ClassController@apiUpdate');
Router::delete('/api/classes/{id}', 'ClassController@apiDelete');
Router::get('/api/classes/{id}/students', 'ClassController@apiStudents');

// ─── Subjects API ────────────────────────────────────────────────────────────

Router::get('/api/subjects', 'SubjectController@apiIndex');
Router::post('/api/subjects', 'SubjectController@apiStore');
Router::get('/api/subjects/{id}', 'SubjectController@apiShow');
Router::put('/api/subjects/{id}', 'SubjectController@apiUpdate');
Router::delete('/api/subjects/{id}', 'SubjectController@apiDelete');

// ─── Exams API ───────────────────────────────────────────────────────────────

Router::get('/api/exams', 'ExamController@apiIndex');
Router::post('/api/exams', 'ExamController@apiStore');
Router::get('/api/exams/{id}', 'ExamController@apiShow');
Router::put('/api/exams/{id}', 'ExamController@apiUpdate');
Router::delete('/api/exams/{id}', 'ExamController@apiDelete');

// ─── Results API ─────────────────────────────────────────────────────────────

Router::get('/api/results', 'ResultController@apiIndex');
Router::post('/api/results', 'ResultController@apiStore');
Router::get('/api/results/{id}', 'ResultController@apiShow');
Router::put('/api/results/{id}', 'ResultController@apiUpdate');
Router::delete('/api/results/{id}', 'ResultController@apiDelete');
Router::post('/api/results/bulk', 'ResultController@apiBulkStore');

// ─── Attendance API ──────────────────────────────────────────────────────────

Router::get('/api/attendance', 'AttendanceController@apiIndex');
Router::post('/api/attendance', 'AttendanceController@apiStore');
Router::get('/api/attendance/{id}', 'AttendanceController@apiShow');
Router::put('/api/attendance/{id}', 'AttendanceController@apiUpdate');
Router::delete('/api/attendance/{id}', 'AttendanceController@apiDelete');
Router::post('/api/attendance/bulk', 'AttendanceController@apiBulkStore');

// ─── Fees API ────────────────────────────────────────────────────────────────

Router::get('/api/fees', 'FeeController@apiIndex');
Router::post('/api/fees', 'FeeController@apiStore');
Router::get('/api/fees/{id}', 'FeeController@apiShow');
Router::put('/api/fees/{id}', 'FeeController@apiUpdate');
Router::delete('/api/fees/{id}', 'FeeController@apiDelete');

// ─── Payments API ────────────────────────────────────────────────────────────

Router::get('/api/payments', 'PaymentController@apiIndex');
Router::post('/api/payments', 'PaymentController@apiStore');
Router::get('/api/payments/{id}', 'PaymentController@apiShow');
Router::put('/api/payments/{id}', 'PaymentController@apiUpdate');
Router::delete('/api/payments/{id}', 'PaymentController@apiDelete');
Router::post('/api/payments/{id}/receipt', 'PaymentController@apiGenerateReceipt');

// ─── Library Books API ───────────────────────────────────────────────────────

Router::get('/api/library/books', 'LibraryController@apiBooks');
Router::post('/api/library/books', 'LibraryController@apiStoreBook');
Router::get('/api/library/books/{id}', 'LibraryController@apiShowBook');
Router::put('/api/library/books/{id}', 'LibraryController@apiUpdateBook');
Router::delete('/api/library/books/{id}', 'LibraryController@apiDeleteBook');
Router::get('/api/library/issues', 'LibraryController@apiIssues');
Router::post('/api/library/issues', 'LibraryController@apiStoreIssue');
Router::post('/api/library/returns/{id}', 'LibraryController@apiReturnBook');

// ─── Transport Routes API ───────────────────────────────────────────────────

Router::get('/api/transport/routes', 'TransportController@apiRoutes');
Router::post('/api/transport/routes', 'TransportController@apiStoreRoute');
Router::get('/api/transport/routes/{id}', 'TransportController@apiShowRoute');
Router::put('/api/transport/routes/{id}', 'TransportController@apiUpdateRoute');
Router::delete('/api/transport/routes/{id}', 'TransportController@apiDeleteRoute');

// ─── Transport Vehicles API ─────────────────────────────────────────────────

Router::get('/api/transport/vehicles', 'TransportController@apiVehicles');
Router::post('/api/transport/vehicles', 'TransportController@apiStoreVehicle');
Router::get('/api/transport/vehicles/{id}', 'TransportController@apiShowVehicle');
Router::put('/api/transport/vehicles/{id}', 'TransportController@apiUpdateVehicle');
Router::delete('/api/transport/vehicles/{id}', 'TransportController@apiDeleteVehicle');

// ─── Hostel Rooms API ────────────────────────────────────────────────────────

Router::get('/api/hostel/rooms', 'HostelController@apiRooms');
Router::post('/api/hostel/rooms', 'HostelController@apiStoreRoom');
Router::get('/api/hostel/rooms/{id}', 'HostelController@apiShowRoom');
Router::put('/api/hostel/rooms/{id}', 'HostelController@apiUpdateRoom');
Router::delete('/api/hostel/rooms/{id}', 'HostelController@apiDeleteRoom');
Router::get('/api/hostel/allocations', 'HostelController@apiAllocations');
Router::post('/api/hostel/allocations', 'HostelController@apiStoreAllocation');
Router::delete('/api/hostel/allocations/{id}', 'HostelController@apiDeleteAllocation');

// ─── LMS Courses API ─────────────────────────────────────────────────────────

Router::get('/api/lms/courses', 'LmsController@apiCourses');
Router::post('/api/lms/courses', 'LmsController@apiStoreCourse');
Router::get('/api/lms/courses/{id}', 'LmsController@apiShowCourse');
Router::put('/api/lms/courses/{id}', 'LmsController@apiUpdateCourse');
Router::delete('/api/lms/courses/{id}', 'LmsController@apiDeleteCourse');

// ─── LMS Assignments API ─────────────────────────────────────────────────────

Router::get('/api/lms/assignments', 'LmsController@apiAssignments');
Router::post('/api/lms/assignments', 'LmsController@apiStoreAssignment');
Router::get('/api/lms/assignments/{id}', 'LmsController@apiShowAssignment');
Router::put('/api/lms/assignments/{id}', 'LmsController@apiUpdateAssignment');
Router::delete('/api/lms/assignments/{id}', 'LmsController@apiDeleteAssignment');
Router::post('/api/lms/assignments/{id}/submit', 'LmsController@apiSubmitAssignment');
Router::get('/api/lms/submissions', 'LmsController@apiSubmissions');
Router::get('/api/lms/submissions/{id}', 'LmsController@apiShowSubmission');

// ─── Communication API ───────────────────────────────────────────────────────

Router::get('/api/communication/messages', 'CommunicationController@apiMessages');
Router::post('/api/communication/messages', 'CommunicationController@apiStoreMessage');
Router::get('/api/communication/messages/{id}', 'CommunicationController@apiShowMessage');
Router::delete('/api/communication/messages/{id}', 'CommunicationController@apiDeleteMessage');
Router::get('/api/communication/notices', 'CommunicationController@apiNotices');
Router::post('/api/communication/notices', 'CommunicationController@apiStoreNotice');
Router::put('/api/communication/notices/{id}', 'CommunicationController@apiUpdateNotice');
Router::delete('/api/communication/notices/{id}', 'CommunicationController@apiDeleteNotice');

// ─── Skills API ──────────────────────────────────────────────────────────────

Router::get('/api/skills', 'SkillController@apiIndex');
Router::post('/api/skills', 'SkillController@apiStore');
Router::get('/api/skills/{id}', 'SkillController@apiShow');
Router::put('/api/skills/{id}', 'SkillController@apiUpdate');
Router::delete('/api/skills/{id}', 'SkillController@apiDelete');

// ─── Reports API ─────────────────────────────────────────────────────────────

Router::get('/api/reports/students', 'ReportController@apiStudentReports');
Router::get('/api/reports/academic', 'ReportController@apiAcademicReports');
Router::get('/api/reports/financial', 'ReportController@apiFinancialReports');
Router::get('/api/reports/attendance', 'ReportController@apiAttendanceReports');
Router::post('/api/reports/export', 'ReportController@apiExport');

// ─── Timetable API ───────────────────────────────────────────────────────────

Router::get('/api/timetable', 'TimetableController@apiIndex');
Router::post('/api/timetable', 'TimetableController@apiStore');
Router::get('/api/timetable/{id}', 'TimetableController@apiShow');
Router::put('/api/timetable/{id}', 'TimetableController@apiUpdate');
Router::delete('/api/timetable/{id}', 'TimetableController@apiDelete');

// ─── Settings API ────────────────────────────────────────────────────────────

Router::get('/api/settings', 'SettingController@apiIndex');
Router::put('/api/settings', 'SettingController@apiUpdate');

// ─── Profile API ─────────────────────────────────────────────────────────────

Router::get('/api/profile', 'ProfileController@apiIndex');
Router::put('/api/profile', 'ProfileController@apiUpdate');
Router::post('/api/profile/avatar', 'ProfileController@apiUploadAvatar');
Router::post('/api/profile/change-password', 'ProfileController@apiChangePassword');
