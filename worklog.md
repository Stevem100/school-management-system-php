---
Task ID: 1
Agent: Main Agent
Task: Complete School Management System - Missing Views & MySQL Fixes

Work Log:
- Analyzed existing project structure: 25 controllers, 29 view files, full MySQL conversion already done
- Identified 4 missing view files referenced by controllers: classes/form.php, subjects/form.php, exams/form.php, skills/form.php
- Read all 4 controllers (ClassController, SubjectController, ExamController, SkillController) to understand form data requirements
- Read all 4 existing index views to match styling patterns (Tailwind CSS with dark mode support, emerald accent color)
- Created classes/form.php with fields: name, grade_level, section, capacity, academic_year, class_teacher_id, status
- Created subjects/form.php with fields: name, code, type, credit_hours, description, status
- Created exams/form.php with fields: name, type, status, subject_id, class_id, total_marks, passing_marks, start_date, end_date, academic_year, term
- Created skills/form.php with fields: name, code, category, level, strand, sub_strand, description, status

Critical Bug Fixes:
- Fixed sessions table: Added missing `is_active TINYINT(1) DEFAULT 1` column that Auth.php requires
- Added user_id and is_active indexes to sessions table for performance
- Fixed users table: Renamed `password` column to `password_hash` to match Auth.php's camelCase `passwordHash` expectation
- Updated seed-data.sql INSERT to use `password_hash` column name
- Fixed UserController.php: Changed all `password` + `password_hash()` (bcrypt) to `passwordHash` + `hash('sha256', ...)` for consistency
- Fixed StudentController.php: Same bcrypt→SHA-256 conversion for both store() and apiStore()
- Fixed ProfileController.php: Changed password verification from `password_verify()` (bcrypt) to `hash_equals()` with SHA-256, changed `data['password']` to `data['passwordHash']`

Stage Summary:
- 4 new view files created (form.php for classes, subjects, exams, skills)
- 3 SQL/DB schema fixes applied (sessions.is_active, users.password→password_hash, seed data)
- 3 controller files fixed for password hashing consistency (UserController, StudentController, ProfileController)
- All views use consistent styling: Tailwind CSS dark mode, emerald accent, card-based layout, back navigation
