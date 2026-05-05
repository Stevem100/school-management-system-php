---
Task ID: 1
Agent: Main Agent
Task: Generate all missing view files and update sidebar menu

Work Log:
- Read project state: routes/web.php, all controllers, existing views, sidebar menu
- Created database migration SQL at /home/z/my-project/database/migrate.sql with all tables (users, roles, permissions, schools, branches, modules, classes, subjects, students, exams, results, attendance, timetable, skills, fees, payments, books, book_issues, transport_routes, transport_vehicles, transport_assignments, hostel_rooms, hostel_allocations, courses, lms_assignments, lms_submissions, messages, notices, website_settings, website_pages, website_menu_items, website_media, admission_settings, admission_fields, admission_applications, admission_form_data, admission_attachments)
- Fixed admission view naming: copied show.php → show-application.php to match controller reference
- Created upload directories: public/uploads/media, public/uploads/admissions
- Launched 3 parallel subagents to create all missing views
  - Agent 1: admission/form.php, admission/closed.php, admission/not-yet-open.php, admission/thank-you.php, auth/register.php, auth/forgot-password.php, auth/reset-password.php, website/preview.php
  - Agent 2: library/books.php, library/book-form.php, library/issue.php, library/returns.php, transport/routes.php, transport/vehicles.php, transport/assignments.php, hostel/rooms.php, hostel/room-form.php, hostel/allocations.php, hostel/allocation-form.php
  - Agent 3: lms/courses.php, lms/course-form.php, lms/assignments.php, lms/assignment-form.php, lms/submissions.php, communication/messages.php, communication/message-form.php, communication/notices.php, communication/notice-form.php, reports/student-reports.php, reports/academic-reports.php, reports/financial-reports.php, reports/attendance-reports.php
- Updated sidebar menu with Website (Dashboard, Pages, Settings, Menu, Media) and Admission (Dashboard, Settings, Form Fields, Applications) submenu entries
- Added globe, admission, list, image icons to sidebar icon definitions

Stage Summary:
- Total: 32 new/updated view files created
- 1 database migration SQL file created
- Sidebar menu updated with 2 new submenu sections
- All views follow consistent Tailwind CSS emerald theme with dark mode support
