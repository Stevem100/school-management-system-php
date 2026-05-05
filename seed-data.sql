-- School Management System - Full Seed Script (MySQL)
-- Run this file in MySQL client: mysql -u root school_erp < seed-data.sql
-- Or via phpMyAdmin SQL tab
-- This script: clears data, creates demo data
-- Can be run after setup.sql to re-seed the database

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ==================== STEP 1: CLEAR EXISTING DATA ====================
TRUNCATE TABLE module_licenses;
TRUNCATE TABLE modules;
TRUNCATE TABLE ai_logs;
TRUNCATE TABLE ai_messages;
TRUNCATE TABLE ai_conversations;
TRUNCATE TABLE ai_settings;
TRUNCATE TABLE notifications;
TRUNCATE TABLE lms_submissions;
TRUNCATE TABLE lms_assignments;
TRUNCATE TABLE lms_lessons;
TRUNCATE TABLE lms_courses;
TRUNCATE TABLE book_borrowals;
TRUNCATE TABLE books;
TRUNCATE TABLE hostel_assignments;
TRUNCATE TABLE hostel_rooms;
TRUNCATE TABLE transport_assignments;
TRUNCATE TABLE transport_vehicles;
TRUNCATE TABLE transport_routes;
TRUNCATE TABLE payments;
TRUNCATE TABLE fees;
TRUNCATE TABLE student_skills;
TRUNCATE TABLE teacher_skills;
TRUNCATE TABLE skills;
TRUNCATE TABLE timetable_entries;
TRUNCATE TABLE exam_results;
TRUNCATE TABLE exams;
TRUNCATE TABLE subject_teachers;
TRUNCATE TABLE subjects;
TRUNCATE TABLE enrollments;
TRUNCATE TABLE classes;
TRUNCATE TABLE classrooms;
TRUNCATE TABLE student_parents;
TRUNCATE TABLE parent_profiles;
TRUNCATE TABLE student_profiles;
TRUNCATE TABLE teacher_profiles;
TRUNCATE TABLE user_roles;
TRUNCATE TABLE sessions;
TRUNCATE TABLE users;
TRUNCATE TABLE role_permissions;
TRUNCATE TABLE permissions;
TRUNCATE TABLE roles;
TRUNCATE TABLE branches;
TRUNCATE TABLE schools;

-- ==================== STEP 2: CREATE ROLES ====================
INSERT INTO roles (name, scope, description, level) VALUES
  ('SuperAdmin', 'global', 'System administrator with full access', 100),
  ('SchoolAdmin', 'school', 'School-wide administrator', 80),
  ('BranchAdmin', 'branch', 'Branch administrator', 60),
  ('Dean', 'branch', 'Academic dean', 50),
  ('Teacher', 'branch', 'Classroom teacher', 40),
  ('Accountant', 'branch', 'Finance officer', 35),
  ('Librarian', 'branch', 'Library manager', 30),
  ('TransportManager', 'branch', 'Transport coordinator', 25),
  ('HostelManager', 'branch', 'Hostel warden', 25),
  ('Parent', 'branch', 'Student parent/guardian', 10),
  ('Student', 'branch', 'School student', 5);

-- ==================== STEP 3: CREATE PERMISSIONS ====================
INSERT INTO permissions (name, module, action, description) VALUES
  ('schools.view', 'schools', 'view', 'view schools'),
  ('schools.create', 'schools', 'create', 'create schools'),
  ('schools.edit', 'schools', 'edit', 'edit schools'),
  ('schools.delete', 'schools', 'delete', 'delete schools'),
  ('schools.manage', 'schools', 'manage', 'manage schools'),
  ('branches.view', 'branches', 'view', 'view branches'),
  ('branches.create', 'branches', 'create', 'create branches'),
  ('branches.edit', 'branches', 'edit', 'edit branches'),
  ('branches.delete', 'branches', 'delete', 'delete branches'),
  ('branches.manage', 'branches', 'manage', 'manage branches'),
  ('users.view', 'users', 'view', 'view users'),
  ('users.create', 'users', 'create', 'create users'),
  ('users.edit', 'users', 'edit', 'edit users'),
  ('users.delete', 'users', 'delete', 'delete users'),
  ('users.manage', 'users', 'manage', 'manage users'),
  ('academic.view', 'academic', 'view', 'view academic'),
  ('academic.create', 'academic', 'create', 'create academic'),
  ('academic.edit', 'academic', 'edit', 'edit academic'),
  ('academic.delete', 'academic', 'delete', 'delete academic'),
  ('academic.manage', 'academic', 'manage', 'manage academic'),
  ('students.view', 'students', 'view', 'view students'),
  ('students.create', 'students', 'create', 'create students'),
  ('students.edit', 'students', 'edit', 'edit students'),
  ('students.delete', 'students', 'delete', 'delete students'),
  ('students.manage', 'students', 'manage', 'manage students'),
  ('finance.view', 'finance', 'view', 'view finance'),
  ('finance.create', 'finance', 'create', 'create finance'),
  ('finance.edit', 'finance', 'edit', 'edit finance'),
  ('finance.delete', 'finance', 'delete', 'delete finance'),
  ('finance.manage', 'finance', 'manage', 'manage finance'),
  ('ai.view', 'ai', 'view', 'view ai'),
  ('ai.create', 'ai', 'create', 'create ai'),
  ('ai.edit', 'ai', 'edit', 'edit ai'),
  ('ai.delete', 'ai', 'delete', 'delete ai'),
  ('ai.manage', 'ai', 'manage', 'manage ai'),
  ('ai.chat', 'ai', 'chat', 'chat ai'),
  ('ai.monitor', 'ai', 'monitor', 'monitor ai'),
  ('modules.view', 'modules', 'view', 'view modules'),
  ('modules.create', 'modules', 'create', 'create modules'),
  ('modules.edit', 'modules', 'edit', 'edit modules'),
  ('modules.delete', 'modules', 'delete', 'delete modules'),
  ('modules.manage', 'modules', 'manage', 'manage modules'),
  ('reports.view', 'reports', 'view', 'view reports'),
  ('communication.view', 'communication', 'view', 'view communication'),
  ('communication.create', 'communication', 'create', 'create communication'),
  ('communication.edit', 'communication', 'edit', 'edit communication'),
  ('communication.delete', 'communication', 'delete', 'delete communication'),
  ('communication.manage', 'communication', 'manage', 'manage communication'),
  ('transport.view', 'transport', 'view', 'view transport'),
  ('transport.create', 'transport', 'create', 'create transport'),
  ('transport.edit', 'transport', 'edit', 'edit transport'),
  ('transport.delete', 'transport', 'delete', 'delete transport'),
  ('transport.manage', 'transport', 'manage', 'manage transport'),
  ('library.view', 'library', 'view', 'view library'),
  ('library.create', 'library', 'create', 'create library'),
  ('library.edit', 'library', 'edit', 'edit library'),
  ('library.delete', 'library', 'delete', 'delete library'),
  ('library.manage', 'library', 'manage', 'manage library'),
  ('hostel.view', 'hostel', 'view', 'view hostel'),
  ('hostel.create', 'hostel', 'create', 'create hostel'),
  ('hostel.edit', 'hostel', 'edit', 'edit hostel'),
  ('hostel.delete', 'hostel', 'delete', 'delete hostel'),
  ('hostel.manage', 'hostel', 'manage', 'manage hostel'),
  ('lms.view', 'lms', 'view', 'view lms'),
  ('lms.create', 'lms', 'create', 'create lms'),
  ('lms.edit', 'lms', 'edit', 'edit lms'),
  ('lms.delete', 'lms', 'delete', 'delete lms'),
  ('lms.manage', 'lms', 'manage', 'manage lms');

-- ==================== STEP 4: ROLE-PERMISSION MAPPINGS ====================
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE (r.name = 'SuperAdmin')
  AND p.module IN ('schools','branches','users','academic','students','finance','ai','modules','reports','communication','transport','library','hostel','lms');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'SchoolAdmin'
  AND p.name IN (
    'schools.view','schools.manage','branches.view','branches.create','branches.manage',
    'users.view','users.create','users.manage','academic.view','academic.manage',
    'finance.view','finance.manage','ai.view','ai.manage','modules.view','modules.manage',
    'reports.view','communication.view','communication.manage','transport.view','transport.manage',
    'library.view','library.manage','hostel.view','hostel.manage','lms.view','lms.manage',
    'students.view','students.manage'
  );

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'BranchAdmin'
  AND p.name IN (
    'branches.view','users.view','users.create','users.manage','academic.view','academic.manage',
    'finance.view','finance.manage','ai.view','ai.manage','reports.view',
    'communication.view','communication.manage','transport.view','transport.manage',
    'library.view','library.manage','hostel.view','hostel.manage','lms.view','lms.manage',
    'students.view','students.manage'
  );

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'Dean' AND p.name IN ('academic.view','academic.manage','students.view','reports.view','lms.view','lms.manage');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'Teacher' AND p.name IN ('academic.view','students.view','lms.view','lms.manage','ai.view','ai.monitor');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'Accountant' AND p.name IN ('finance.view','finance.manage','reports.view');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'Librarian' AND p.name IN ('library.view','library.manage');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'TransportManager' AND p.name IN ('transport.view','transport.manage');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'HostelManager' AND p.name IN ('hostel.view','hostel.manage');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'Parent' AND p.name IN ('students.view','academic.view','finance.view','communication.view');

INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r, permissions p
WHERE r.name = 'Student' AND p.name IN ('academic.view','lms.view','ai.view','ai.chat','library.view');

-- ==================== STEP 5: CREATE SCHOOL ====================
INSERT INTO schools (name, code, type, address, phone, email, website) VALUES
  ('Greenfield Academy', 'GA', 'general', '123 Education Lane, Nairobi', '+254-700-123456', 'info@greenfield.ac.ke', 'https://greenfield.ac.ke');

-- ==================== STEP 6: CREATE BRANCHES ====================
INSERT INTO branches (school_id, name, code, address, phone) VALUES
  (1, 'Main Campus', 'MC', '123 Education Lane, Nairobi', '+254-700-123456'),
  (1, 'West Campus', 'WC', '456 West Avenue, Nakuru', '+254-700-789012');

-- ==================== STEP 7: CREATE USERS ====================
-- Passwords are SHA-256 hashes of: password + '_school_erp_salt'
-- admin123: 03b1dbf3638037f717845e3e36e2c5ae0c28a13abef309dd61976f4c40fa2b18
-- demo123: 809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083
-- student123: 10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72

INSERT INTO users (email, password, first_name, last_name, phone, school_id, branch_id) VALUES
  -- id=1: SuperAdmin
  ('admin@school.com', '03b1dbf3638037f717845e3e36e2c5ae0c28a13abef309dd61976f4c40fa2b18', 'Admin', 'User', '+254-700-000001', 1, 2),
  -- id=2: SchoolAdmin
  ('schooladmin@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Margaret', 'Wanjiku', '+254-700-000010', 1, 2),
  -- id=3: BranchAdmin (Main)
  ('branchadmin@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'James', 'Odhiambo', '+254-700-000011', 1, 2),
  -- id=4: BranchAdmin (West)
  ('westadmin@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Grace', 'Chebet', '+254-700-000012', 1, 3),
  -- id=5: Dean
  ('dean@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Prof', 'Kamau', '+254-700-000013', 1, 2),
  -- id=6: Teacher (Main)
  ('mary@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Mary', 'Akinyi', '+254-700-000020', 1, 2),
  -- id=7: Teacher (Main)
  ('john@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'John', 'Mwangi', '+254-700-000021', 1, 2),
  -- id=8: Teacher (West)
  ('faith@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Faith', 'Rotich', '+254-700-000022', 1, 3),
  -- id=9: Accountant
  ('accounts@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Peter', 'Kariuki', '+254-700-000030', 1, 2),
  -- id=10: Librarian
  ('library@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Susan', 'Njeri', '+254-700-000031', 1, 2),
  -- id=11: Transport Manager
  ('transport@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'David', 'Mutua', '+254-700-000032', 1, 2),
  -- id=12: Hostel Manager
  ('hostel@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Rose', 'Wambui', '+254-700-000033', 1, 2),
  -- id=13-22: Students
  ('brian.njorgemc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Brian', 'Njoroge', '+254-700-000101', 1, 2),
  ('sarah.muthonimc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Sarah', 'Muthoni', '+254-700-000102', 1, 2),
  ('kevin.otienomc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Kevin', 'Otieno', '+254-700-000103', 1, 2),
  ('lucy.wanjirumc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Lucy', 'Wanjiru', '+254-700-000104', 1, 2),
  ('daniel.kipchogemc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Daniel', 'Kipchoge', '+254-700-000105', 1, 2),
  ('amina.hassanwc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Amina', 'Hassan', '+254-700-000106', 1, 3),
  ('ian.machariawc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Ian', 'Macharia', '+254-700-000107', 1, 3),
  ('esther.nekesawc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Esther', 'Nekesa', '+254-700-000108', 1, 3),
  ('alex.wekesawc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Alex', 'Wekesa', '+254-700-000109', 1, 3),
  ('mercy.moraawc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Mercy', 'Moraa', '+254-700-000110', 1, 3);

-- ==================== STEP 8: ASSIGN USER ROLES ====================
INSERT INTO user_roles (user_id, role_id, school_id, branch_id) VALUES
  (1, 1, 1, 2),   -- admin → SuperAdmin
  (2, 2, 1, 2),   -- schooladmin → SchoolAdmin
  (3, 3, 1, 2),   -- branchadmin → BranchAdmin (Main)
  (4, 3, 1, 3),   -- westadmin → BranchAdmin (West)
  (5, 4, 1, 2),   -- dean → Dean
  (6, 5, 1, 2),   -- mary → Teacher (Main)
  (7, 5, 1, 2),   -- john → Teacher (Main)
  (8, 5, 1, 3),   -- faith → Teacher (West)
  (9, 6, 1, 2),   -- accounts → Accountant
  (10, 7, 1, 2),  -- library → Librarian
  (11, 8, 1, 2),  -- transport → TransportManager
  (12, 9, 1, 2),  -- hostel → HostelManager
  (13, 11, 1, 2), -- brian → Student (Main)
  (14, 11, 1, 2), -- sarah → Student (Main)
  (15, 11, 1, 2), -- kevin → Student (Main)
  (16, 11, 1, 2), -- lucy → Student (Main)
  (17, 11, 1, 2), -- daniel → Student (Main)
  (18, 11, 1, 3), -- amina → Student (West)
  (19, 11, 1, 3), -- ian → Student (West)
  (20, 11, 1, 3), -- esther → Student (West)
  (21, 11, 1, 3), -- alex → Student (West)
  (22, 11, 1, 3); -- mercy → Student (West)

-- ==================== STEP 9: TEACHER PROFILES ====================
INSERT INTO teacher_profiles (user_id, school_id, branch_id, employee_id, qualification, specialization, department) VALUES
  (6, 1, 2, CONCAT('EMP', UNIX_TIMESTAMP(), '0006'), 'Bachelor of Education', 'Mathematics & Science', 'Academic'),
  (7, 1, 2, CONCAT('EMP', UNIX_TIMESTAMP(), '0007'), 'Bachelor of Education', 'Mathematics & Science', 'Academic'),
  (8, 1, 3, CONCAT('EMP', UNIX_TIMESTAMP(), '0008'), 'Bachelor of Education', 'Mathematics & Science', 'Academic');

-- ==================== STEP 10: STUDENT PROFILES ====================
INSERT INTO student_profiles (user_id, school_id, branch_id, admission_no, date_of_birth, gender, guardian_name, guardian_phone) VALUES
  -- Main Campus students (branch_id=2, code=MC)
  (13, 1, 2, 'ADM/MC/101', '2012-01-15', 'male',   'Njoroge Parent',  '+254-7000001'),
  (14, 1, 2, 'ADM/MC/102', '2012-04-15', 'female', 'Muthoni Parent',  '+254-7000002'),
  (15, 1, 2, 'ADM/MC/103', '2012-07-15', 'male',   'Otieno Parent',   '+254-7000003'),
  (16, 1, 2, 'ADM/MC/104', '2012-10-15', 'female', 'Wanjiru Parent',  '+254-7000004'),
  (17, 1, 2, 'ADM/MC/105', '2013-01-15', 'male',   'Kipchoge Parent', '+254-7000005'),
  -- West Campus students (branch_id=3, code=WC)
  (18, 1, 3, 'ADM/WC/101', '2013-04-15', 'female', 'Hassan Parent',   '+254-7000006'),
  (19, 1, 3, 'ADM/WC/102', '2013-07-15', 'male',   'Macharia Parent', '+254-7000007'),
  (20, 1, 3, 'ADM/WC/103', '2013-10-15', 'female', 'Nekesa Parent',   '+254-7000008'),
  (21, 1, 3, 'ADM/WC/104', '2014-01-15', 'male',   'Wekesa Parent',   '+254-7000009'),
  (22, 1, 3, 'ADM/WC/105', '2014-04-15', 'female', 'Moraa Parent',    '+254-7000010');

-- ==================== STEP 11: CLASSES ====================
INSERT INTO classes (school_id, branch_id, name, section, numeric_level) VALUES
  -- Main Campus (school_id=1, branch_id=2): 13 classes
  (1, 2, 'Grade 1', 'A', 1),
  (1, 2, 'Grade 2', 'A', 2),
  (1, 2, 'Grade 3', 'A', 3),
  (1, 2, 'Grade 4', 'A', 4),
  (1, 2, 'Grade 5', 'A', 5),
  (1, 2, 'Grade 6', 'A', 6),
  (1, 2, 'Grade 7', 'A', 7),
  (1, 2, 'Grade 8', 'A', 8),
  (1, 2, 'Form 1', 'A', 9),
  (1, 2, 'Form 1', 'B', 9),
  (1, 2, 'Form 2', 'A', 10),
  (1, 2, 'Form 3', 'A', 11),
  (1, 2, 'Form 4', 'A', 12),
  -- West Campus (school_id=1, branch_id=3): 7 classes
  (1, 3, 'Grade 1', 'A', 1),
  (1, 3, 'Grade 2', 'A', 2),
  (1, 3, 'Grade 3', 'A', 3),
  (1, 3, 'Grade 4', 'A', 4),
  (1, 3, 'Grade 5', 'A', 5),
  (1, 3, 'Form 1', 'A', 9),
  (1, 3, 'Form 2', 'A', 10);

-- ==================== STEP 12: SUBJECTS ====================
INSERT INTO subjects (school_id, branch_id, name, code, type) VALUES
  -- Main Campus (school_id=1, branch_id=2)
  (1, 2, 'Mathematics', 'MATH', 'core'),
  (1, 2, 'English', 'ENG', 'core'),
  (1, 2, 'Kiswahili', 'KIS', 'core'),
  (1, 2, 'Science', 'SCI', 'core'),
  (1, 2, 'Social Studies', 'SST', 'core'),
  (1, 2, 'CRE', 'CRE', 'core'),
  (1, 2, 'IT', 'IT', 'optional'),
  -- West Campus (school_id=1, branch_id=3)
  (1, 3, 'Mathematics', 'MATH', 'core'),
  (1, 3, 'English', 'ENG', 'core'),
  (1, 3, 'Kiswahili', 'KIS', 'core'),
  (1, 3, 'Science', 'SCI', 'core'),
  (1, 3, 'Social Studies', 'SST', 'core'),
  (1, 3, 'CRE', 'CRE', 'core'),
  (1, 3, 'IT', 'IT', 'optional');

-- ==================== STEP 13: ENROLLMENTS ====================
INSERT INTO enrollments (student_id, class_id, school_id, branch_id, academic_year) VALUES
  (1, 1, 1, 2, YEAR(CURDATE())),   -- Brian (student_profile id=1) → Grade 1A Main (class id=1)
  (2, 2, 1, 2, YEAR(CURDATE())),   -- Sarah → Grade 2A Main
  (3, 3, 1, 2, YEAR(CURDATE())),   -- Kevin → Grade 3A Main
  (4, 4, 1, 2, YEAR(CURDATE())),   -- Lucy → Grade 4A Main
  (5, 5, 1, 2, YEAR(CURDATE())),   -- Daniel → Grade 5A Main
  (6, 14, 1, 3, YEAR(CURDATE())),  -- Amina → Grade 1A West
  (7, 15, 1, 3, YEAR(CURDATE())),  -- Ian → Grade 2A West
  (8, 16, 1, 3, YEAR(CURDATE())),  -- Esther → Grade 3A West
  (9, 17, 1, 3, YEAR(CURDATE())),  -- Alex → Grade 4A West
  (10, 18, 1, 3, YEAR(CURDATE())); -- Mercy → Grade 5A West

-- ==================== STEP 14: FEES ====================
INSERT INTO fees (school_id, branch_id, student_id, title, description, amount, due_date, status, academic_year, term) VALUES
  (1, 2, 1, 'Term 1 Tuition Fee', 'Academic year tuition for term 1', 15000.00, DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-01-01'), INTERVAL 2 MONTH), 'pending', YEAR(CURDATE()), 'Term 1'),
  (1, 2, 2, 'Term 1 Tuition Fee', 'Academic year tuition for term 1', 15000.00, DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-01-01'), INTERVAL 2 MONTH), 'pending', YEAR(CURDATE()), 'Term 1'),
  (1, 2, 3, 'Term 1 Tuition Fee', 'Academic year tuition for term 1', 15000.00, DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-01-01'), INTERVAL 2 MONTH), 'pending', YEAR(CURDATE()), 'Term 1'),
  (1, 2, 4, 'Term 1 Tuition Fee', 'Academic year tuition for term 1', 15000.00, DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-01-01'), INTERVAL 2 MONTH), 'pending', YEAR(CURDATE()), 'Term 1'),
  (1, 2, 5, 'Term 1 Tuition Fee', 'Academic year tuition for term 1', 15000.00, DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-01-01'), INTERVAL 2 MONTH), 'pending', YEAR(CURDATE()), 'Term 1'),
  (1, 3, 6, 'Term 1 Tuition Fee', 'Academic year tuition for term 1', 15000.00, DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-01-01'), INTERVAL 2 MONTH), 'pending', YEAR(CURDATE()), 'Term 1'),
  (1, 3, 7, 'Term 1 Tuition Fee', 'Academic year tuition for term 1', 15000.00, DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-01-01'), INTERVAL 2 MONTH), 'pending', YEAR(CURDATE()), 'Term 1'),
  (1, 3, 8, 'Term 1 Tuition Fee', 'Academic year tuition for term 1', 15000.00, DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-01-01'), INTERVAL 2 MONTH), 'pending', YEAR(CURDATE()), 'Term 1'),
  (1, 3, 9, 'Term 1 Tuition Fee', 'Academic year tuition for term 1', 15000.00, DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-01-01'), INTERVAL 2 MONTH), 'pending', YEAR(CURDATE()), 'Term 1'),
  (1, 3, 10, 'Term 1 Tuition Fee', 'Academic year tuition for term 1', 15000.00, DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-01-01'), INTERVAL 2 MONTH), 'pending', YEAR(CURDATE()), 'Term 1');

-- ==================== STEP 15: AI SETTINGS ====================
INSERT INTO ai_settings (school_id, branch_id, is_openai_enabled, openai_api_url, openai_model, max_tokens, temperature, is_student_access, is_teacher_monitor, allow_free_chat) VALUES
  (1, 2, 0, 'http://localhost:11434/v1/chat/completions', 'llama3', 2048, 0.7, 1, 1, 0),
  (1, 3, 0, 'http://localhost:11434/v1/chat/completions', 'llama3', 2048, 0.7, 1, 1, 0);

-- ==================== STEP 16: CBC SKILLS ====================
INSERT INTO skills (school_id, branch_id, name, code, strand, sub_strand, level, description) VALUES
  -- Main Campus (school_id=1, branch_id=2)
  (1, 2, 'Critical Thinking', 'CT', 'Core Competencies', 'Problem Solving', 'basic', 'CBC aligned Critical Thinking competency'),
  (1, 2, 'Communication', 'COM', 'Core Competencies', 'Oral Communication', 'basic', 'CBC aligned Communication competency'),
  (1, 2, 'Self-Management', 'SM', 'Core Competencies', 'Self-Discipline', 'basic', 'CBC aligned Self-Management competency'),
  (1, 2, 'Digital Literacy', 'DL', 'Core Competencies', 'ICT Skills', 'intermediate', 'CBC aligned Digital Literacy competency'),
  (1, 2, 'Collaboration', 'COL', 'Core Competencies', 'Teamwork', 'basic', 'CBC aligned Collaboration competency'),
  (1, 2, 'Creativity & Imagination', 'CR', 'Core Competencies', 'Innovation', 'intermediate', 'CBC aligned Creativity & Imagination competency'),
  (1, 2, 'Citizenship', 'CIT', 'Core Competencies', 'National Values', 'basic', 'CBC aligned Citizenship competency'),
  (1, 2, 'Learning to Learn', 'LTL', 'Core Competencies', 'Study Skills', 'basic', 'CBC aligned Learning to Learn competency'),
  -- West Campus (school_id=1, branch_id=3)
  (1, 3, 'Critical Thinking', 'CT', 'Core Competencies', 'Problem Solving', 'basic', 'CBC aligned Critical Thinking competency'),
  (1, 3, 'Communication', 'COM', 'Core Competencies', 'Oral Communication', 'basic', 'CBC aligned Communication competency'),
  (1, 3, 'Self-Management', 'SM', 'Core Competencies', 'Self-Discipline', 'basic', 'CBC aligned Self-Management competency'),
  (1, 3, 'Digital Literacy', 'DL', 'Core Competencies', 'ICT Skills', 'intermediate', 'CBC aligned Digital Literacy competency'),
  (1, 3, 'Collaboration', 'COL', 'Core Competencies', 'Teamwork', 'basic', 'CBC aligned Collaboration competency'),
  (1, 3, 'Creativity & Imagination', 'CR', 'Core Competencies', 'Innovation', 'intermediate', 'CBC aligned Creativity & Imagination competency'),
  (1, 3, 'Citizenship', 'CIT', 'Core Competencies', 'National Values', 'basic', 'CBC aligned Citizenship competency'),
  (1, 3, 'Learning to Learn', 'LTL', 'Core Competencies', 'Study Skills', 'basic', 'CBC aligned Learning to Learn competency');

-- ==================== STEP 17: MODULES ====================
INSERT INTO modules (name, version, description, is_core) VALUES
  ('academic', '1.0.0', 'Academic management', 1),
  ('finance', '1.0.0', 'Fee management and payments', 1),
  ('attendance', '1.0.0', 'Student attendance tracking', 1),
  ('ai', '1.0.0', 'AI-powered learning assistant', 0),
  ('transport', '1.0.0', 'School transport management', 0),
  ('library', '1.0.0', 'Library and book management', 0),
  ('hostel', '1.0.0', 'Hostel and room management', 0),
  ('lms', '1.0.0', 'Learning management system', 0),
  ('communication', '1.0.0', 'Notifications and messaging', 1),
  ('reports', '1.0.0', 'School-wide reports and analytics', 1);

INSERT INTO module_licenses (module_id, school_id, branch_id, is_enabled) VALUES
  (1, 1, 2, 1),
  (2, 1, 2, 1),
  (3, 1, 2, 1),
  (4, 1, 2, 1),
  (5, 1, 2, 1),
  (6, 1, 2, 1),
  (7, 1, 2, 1),
  (8, 1, 2, 1),
  (9, 1, 2, 1),
  (10, 1, 2, 1),
  (1, 1, 3, 1),
  (2, 1, 3, 1),
  (3, 1, 3, 1),
  (4, 1, 3, 1),
  (5, 1, 3, 1),
  (6, 1, 3, 1),
  (7, 1, 3, 1),
  (8, 1, 3, 1),
  (9, 1, 3, 1),
  (10, 1, 3, 1);

-- ==================== DONE! ====================

SET FOREIGN_KEY_CHECKS = 1;
