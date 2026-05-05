-- School Management System - Complete Setup Script (MySQL)
-- Run this file in MySQL client: mysql -u root school_erp < setup.sql
-- Or via phpMyAdmin SQL tab
-- This script: creates tables, inserts demo data
-- One-click setup for a fresh database!

-- ==================== AUTO-INSTALL SCRIPT ====================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ==================== PART 1: SCHEMA ====================

-- ==================== CORE TENANT ====================

CREATE TABLE IF NOT EXISTS schools (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  code VARCHAR(255) NOT NULL UNIQUE,
  type VARCHAR(255) DEFAULT 'general',
  logo TEXT,
  address TEXT,
  phone TEXT,
  email TEXT,
  website TEXT,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS branches (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  code VARCHAR(255) NOT NULL,
  address TEXT,
  phone TEXT,
  email TEXT,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(school_id, code),
  INDEX idx_branches_school (school_id)
);

-- ==================== RBAC ====================

CREATE TABLE IF NOT EXISTS roles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  scope VARCHAR(255) NOT NULL,
  description TEXT,
  level INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS permissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL UNIQUE,
  module VARCHAR(255) NOT NULL,
  action VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS role_permissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_id INT UNSIGNED NOT NULL,
  permission_id INT UNSIGNED NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(role_id, permission_id),
  INDEX idx_role_permissions_role (role_id),
  INDEX idx_role_permissions_permission (permission_id)
);

-- ==================== USERS ====================

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED,
  branch_id INT UNSIGNED,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  first_name VARCHAR(255) NOT NULL,
  last_name VARCHAR(255) NOT NULL,
  phone TEXT,
  avatar TEXT,
  is_active TINYINT(1) DEFAULT 1,
  last_login DATETIME,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_users_school (school_id),
  INDEX idx_users_branch (branch_id)
);

CREATE TABLE IF NOT EXISTS user_roles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  role_id INT UNSIGNED NOT NULL,
  school_id INT UNSIGNED,
  branch_id INT UNSIGNED,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(user_id, role_id, branch_id),
  INDEX idx_user_roles_user (user_id),
  INDEX idx_user_roles_role (role_id)
);

-- ==================== ACADEMIC ====================

CREATE TABLE IF NOT EXISTS student_profiles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  admission_no VARCHAR(255) NOT NULL,
  roll_number TEXT,
  date_of_birth DATE,
  gender VARCHAR(255) DEFAULT 'other',
  blood_group TEXT,
  nationality TEXT,
  address TEXT,
  guardian_name TEXT,
  guardian_phone TEXT,
  guardian_email TEXT,
  status VARCHAR(255) DEFAULT 'active',
  enroll_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(school_id, branch_id, admission_no),
  INDEX idx_student_profiles_school (school_id),
  INDEX idx_student_profiles_branch (branch_id)
);

CREATE TABLE IF NOT EXISTS teacher_profiles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  employee_id VARCHAR(255) NOT NULL,
  qualification TEXT,
  specialization TEXT,
  department TEXT,
  join_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(255) DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(school_id, branch_id, employee_id),
  INDEX idx_teacher_profiles_school (school_id),
  INDEX idx_teacher_profiles_branch (branch_id)
);

CREATE TABLE IF NOT EXISTS parent_profiles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  occupation TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS student_parents (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  parent_id INT UNSIGNED NOT NULL,
  relation TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(student_id, parent_id),
  INDEX idx_student_parents_student (student_id),
  INDEX idx_student_parents_parent (parent_id)
);

CREATE TABLE IF NOT EXISTS classes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  section VARCHAR(255),
  numeric_level INT DEFAULT 0,
  teacher_id INT UNSIGNED,
  capacity INT DEFAULT 40,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(school_id, branch_id, name, section),
  INDEX idx_classes_school (school_id),
  INDEX idx_classes_branch (branch_id)
);

CREATE TABLE IF NOT EXISTS classrooms (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  building TEXT,
  floor INT DEFAULT 1,
  capacity INT DEFAULT 40,
  has_projector TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS enrollments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  class_id INT UNSIGNED NOT NULL,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  academic_year VARCHAR(255),
  status VARCHAR(255) DEFAULT 'active',
  enroll_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(student_id, class_id, academic_year),
  INDEX idx_enrollments_student (student_id),
  INDEX idx_enrollments_class (class_id)
);

CREATE TABLE IF NOT EXISTS subjects (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  code VARCHAR(255) NOT NULL,
  type VARCHAR(255) DEFAULT 'core',
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(school_id, branch_id, code),
  INDEX idx_subjects_school (school_id),
  INDEX idx_subjects_branch (branch_id)
);

CREATE TABLE IF NOT EXISTS subject_teachers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  subject_id INT UNSIGNED NOT NULL,
  teacher_id INT UNSIGNED NOT NULL,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(subject_id, teacher_id),
  INDEX idx_subject_teachers_subject (subject_id),
  INDEX idx_subject_teachers_teacher (teacher_id)
);

CREATE TABLE IF NOT EXISTS exams (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  type VARCHAR(255) DEFAULT 'term',
  academic_year VARCHAR(255),
  term VARCHAR(255),
  start_date DATETIME NOT NULL,
  end_date DATETIME NOT NULL,
  status VARCHAR(255) DEFAULT 'upcoming',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS exam_results (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  exam_id INT UNSIGNED NOT NULL,
  student_id INT UNSIGNED NOT NULL,
  subject_id INT UNSIGNED NOT NULL,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  marks_obtained INT NOT NULL,
  total_marks INT NOT NULL,
  grade TEXT,
  remarks TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(exam_id, student_id, subject_id),
  INDEX idx_exam_results_exam (exam_id),
  INDEX idx_exam_results_student (student_id)
);

CREATE TABLE IF NOT EXISTS timetable_entries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  class_id INT UNSIGNED NOT NULL,
  subject_id INT UNSIGNED NOT NULL,
  classroom_id INT UNSIGNED,
  teacher_id TEXT,
  day_of_week INT NOT NULL,
  period INT NOT NULL,
  start_time TEXT,
  end_time TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(class_id, day_of_week, period)
);

-- ==================== CBC SKILLS ====================

CREATE TABLE IF NOT EXISTS skills (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  subject_id INT UNSIGNED,
  name VARCHAR(255) NOT NULL,
  code VARCHAR(255) NOT NULL,
  strand TEXT,
  sub_strand TEXT,
  level VARCHAR(255) DEFAULT 'basic',
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS student_skills (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  skill_id INT UNSIGNED NOT NULL,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  status VARCHAR(255) DEFAULT 'not_started',
  rating INT,
  evidence TEXT,
  assessed_by TEXT,
  assessed_at DATETIME,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(student_id, skill_id),
  INDEX idx_student_skills_student (student_id),
  INDEX idx_student_skills_skill (skill_id)
);

CREATE TABLE IF NOT EXISTS teacher_skills (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  teacher_id INT UNSIGNED NOT NULL,
  skill_id INT UNSIGNED NOT NULL,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  proficiency INT DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(teacher_id, skill_id),
  INDEX idx_teacher_skills_teacher (teacher_id),
  INDEX idx_teacher_skills_skill (skill_id)
);

-- ==================== FINANCE ====================

CREATE TABLE IF NOT EXISTS fees (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  student_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  amount DECIMAL(10,2) NOT NULL,
  due_date DATETIME,
  status VARCHAR(255) DEFAULT 'pending',
  academic_year VARCHAR(255),
  term VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_fees_school (school_id),
  INDEX idx_fees_branch (branch_id),
  INDEX idx_fees_student (student_id)
);

CREATE TABLE IF NOT EXISTS payments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  student_id INT UNSIGNED NOT NULL,
  fee_id INT UNSIGNED NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  method VARCHAR(255) DEFAULT 'cash',
  reference TEXT,
  receipt_no TEXT,
  status VARCHAR(255) DEFAULT 'completed',
  paid_by TEXT,
  paid_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_payments_school (school_id),
  INDEX idx_payments_branch (branch_id),
  INDEX idx_payments_student (student_id)
);

-- ==================== TRANSPORT ====================

CREATE TABLE IF NOT EXISTS transport_routes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  distance DECIMAL(6,2),
  estimated_time INT,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_transport_school (school_id),
  INDEX idx_transport_branch (branch_id)
);

CREATE TABLE IF NOT EXISTS transport_vehicles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  plate_number VARCHAR(255) NOT NULL,
  vehicle_type VARCHAR(255) DEFAULT 'bus',
  capacity INT DEFAULT 50,
  driver_name TEXT,
  driver_phone TEXT,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS transport_assignments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  student_id INT UNSIGNED NOT NULL,
  route_id INT UNSIGNED NOT NULL,
  vehicle_id INT UNSIGNED,
  pickup_point TEXT,
  dropoff_point TEXT,
  pickup_time TEXT,
  dropoff_time TEXT,
  status VARCHAR(255) DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ==================== HOSTEL ====================

CREATE TABLE IF NOT EXISTS hostel_rooms (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  building TEXT,
  floor INT DEFAULT 1,
  capacity INT DEFAULT 4,
  type VARCHAR(255) DEFAULT 'shared',
  amenities TEXT,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_hostel_school (school_id),
  INDEX idx_hostel_branch (branch_id)
);

CREATE TABLE IF NOT EXISTS hostel_assignments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  student_id INT UNSIGNED NOT NULL,
  room_id INT UNSIGNED NOT NULL,
  bed_number INT NOT NULL,
  check_in_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(255) DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(room_id, bed_number)
);

-- ==================== LIBRARY ====================

CREATE TABLE IF NOT EXISTS books (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  isbn TEXT,
  publisher TEXT,
  category TEXT,
  total_copies INT DEFAULT 1,
  available_copies INT DEFAULT 1,
  location TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_library_school (school_id),
  INDEX idx_library_branch (branch_id)
);

CREATE TABLE IF NOT EXISTS book_borrowals (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  book_id INT UNSIGNED NOT NULL,
  student_id INT UNSIGNED NOT NULL,
  borrow_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  due_date DATETIME NOT NULL,
  return_date DATETIME,
  status VARCHAR(255) DEFAULT 'borrowed',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ==================== LMS ====================

CREATE TABLE IF NOT EXISTS lms_courses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  subject_id INT UNSIGNED,
  teacher_id TEXT,
  thumbnail TEXT,
  status VARCHAR(255) DEFAULT 'draft',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_lms_school (school_id),
  INDEX idx_lms_branch (branch_id)
);

CREATE TABLE IF NOT EXISTS lms_lessons (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  course_id INT UNSIGNED NOT NULL,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT,
  video_url TEXT,
  sort_order INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS lms_assignments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  course_id INT UNSIGNED NOT NULL,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  due_date DATETIME,
  total_marks INT DEFAULT 100,
  status VARCHAR(255) DEFAULT 'open',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS lms_submissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  assignment_id INT UNSIGNED NOT NULL,
  student_id TEXT NOT NULL,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  content TEXT,
  file_url TEXT,
  marks INT,
  feedback TEXT,
  status VARCHAR(255) DEFAULT 'submitted',
  submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ==================== COMMUNICATION ====================

CREATE TABLE IF NOT EXISTS notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED,
  branch_id INT UNSIGNED,
  user_id INT UNSIGNED,
  sender_id INT UNSIGNED,
  recipient_id INT UNSIGNED,
  title VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  type VARCHAR(255) DEFAULT 'info',
  channel VARCHAR(255) DEFAULT 'in_app',
  priority VARCHAR(255) DEFAULT 'medium',
  is_read TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_notifications_user (user_id),
  INDEX idx_notifications_recipient (recipient_id),
  INDEX idx_notifications_sender (sender_id)
);

-- ==================== AI MODULE ====================

CREATE TABLE IF NOT EXISTS ai_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  is_openai_enabled TINYINT(1) DEFAULT 0,
  openai_api_url TEXT DEFAULT 'http://localhost:3000/api/chat/completions',
  openai_model TEXT DEFAULT 'open-webui/default',
  openai_api_key TEXT,
  max_tokens INT DEFAULT 2048,
  temperature DECIMAL(3,2) DEFAULT 0.7,
  is_student_access TINYINT(1) DEFAULT 1,
  is_teacher_monitor TINYINT(1) DEFAULT 1,
  allow_free_chat TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(school_id, branch_id)
);

CREATE TABLE IF NOT EXISTS ai_conversations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  skill_id INT UNSIGNED,
  type VARCHAR(255) DEFAULT 'academic_help',
  status VARCHAR(255) DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_ai_conversations_user (user_id),
  INDEX idx_ai_conversations_branch (branch_id)
);

CREATE TABLE IF NOT EXISTS ai_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  conversation_id INT UNSIGNED NOT NULL,
  role VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  skill_id INT UNSIGNED,
  tokens_used INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_ai_messages_conversation (conversation_id)
);

CREATE TABLE IF NOT EXISTS ai_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  action VARCHAR(255) NOT NULL,
  input TEXT,
  output TEXT,
  model TEXT,
  tokens_used INT,
  duration INT,
  ip_address TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ==================== MODULE SYSTEM ====================

CREATE TABLE IF NOT EXISTS modules (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL UNIQUE,
  display_name VARCHAR(255),
  description TEXT,
  icon VARCHAR(255),
  route VARCHAR(255),
  version VARCHAR(255) DEFAULT '1.0.0',
  sort_order INT DEFAULT 0,
  is_core TINYINT(1) DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS module_licenses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  module_id INT UNSIGNED NOT NULL,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  is_enabled TINYINT(1) DEFAULT 1,
  expires_at DATETIME,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(module_id, school_id, branch_id),
  INDEX idx_module_licenses_school (school_id),
  INDEX idx_module_licenses_branch (branch_id)
);

-- ==================== SESSIONS (for our custom auth) ====================

CREATE TABLE IF NOT EXISTS sessions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  token TEXT NOT NULL UNIQUE,
  school_id INT UNSIGNED,
  branch_id INT UNSIGNED,
  ip_address TEXT,
  user_agent TEXT,
  expires_at DATETIME NOT NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_sessions_token (token (255)),
  INDEX idx_sessions_user (user_id),
  INDEX idx_sessions_active (is_active)
);

-- ==================== PART 2: SEED DATA ====================

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
-- SuperAdmin (id=1) gets all permissions
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

INSERT INTO users (email, password_hash, first_name, last_name, phone, school_id, branch_id) VALUES
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
  -- id=13: Student (Main)
  ('brian.njorgemc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Brian', 'Njoroge', '+254-700-000101', 1, 2),
  -- id=14: Student (Main)
  ('sarah.muthonimc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Sarah', 'Muthoni', '+254-700-000102', 1, 2),
  -- id=15: Student (Main)
  ('kevin.otienomc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Kevin', 'Otieno', '+254-700-000103', 1, 2),
  -- id=16: Student (Main)
  ('lucy.wanjirumc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Lucy', 'Wanjiru', '+254-700-000104', 1, 2),
  -- id=17: Student (Main)
  ('daniel.kipchogemc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Daniel', 'Kipchoge', '+254-700-000105', 1, 2),
  -- id=18: Student (West)
  ('amina.hassanwc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Amina', 'Hassan', '+254-700-000106', 1, 3),
  -- id=19: Student (West)
  ('ian.machariawc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Ian', 'Macharia', '+254-700-000107', 1, 3),
  -- id=20: Student (West)
  ('esther.nekesawc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Esther', 'Nekesa', '+254-700-000108', 1, 3),
  -- id=21: Student (West)
  ('alex.wekesawc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Alex', 'Wekesa', '+254-700-000109', 1, 3),
  -- id=22: Student (West)
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
INSERT INTO modules (name, display_name, description, icon, route, version, sort_order, is_core, is_active) VALUES
  ('academic', 'Academic', 'Academic management', '📚', '/classes', '1.0.0', 1, 1, 1),
  ('finance', 'Finance', 'Fee management and payments', '💰', '/fees', '1.0.0', 2, 1, 1),
  ('attendance', 'Attendance', 'Student attendance tracking', '📋', '/attendance', '1.0.0', 3, 1, 1),
  ('ai', 'AI Assistant', 'AI-powered learning assistant', '🤖', '/ai-chat', '1.0.0', 4, 0, 1),
  ('transport', 'Transport', 'School transport management', '🚌', '/transport', '1.0.0', 5, 0, 1),
  ('library', 'Library', 'Library and book management', '📖', '/library', '1.0.0', 6, 0, 1),
  ('hostel', 'Hostel', 'Hostel and room management', '🏠', '/hostel', '1.0.0', 7, 0, 1),
  ('lms', 'LMS', 'Learning management system', '💻', '/lms', '1.0.0', 8, 0, 1),
  ('communication', 'Communication', 'Notifications and messaging', '✉️', '/communication', '1.0.0', 9, 1, 1),
  ('reports', 'Reports', 'School-wide reports and analytics', '📊', '/reports', '1.0.0', 10, 1, 1),
  ('students', 'Students', 'Student management and profiles', '🎓', '/students', '1.0.0', 11, 1, 1),
  ('schools', 'Schools', 'Multi-school management', '🏫', '/schools', '1.0.0', 12, 1, 1),
  ('branches', 'Branches', 'Branch and campus management', '🏢', '/branches', '1.0.0', 13, 1, 1),
  ('users', 'Users', 'User account management', '👥', '/users', '1.0.0', 14, 1, 1),
  ('roles', 'Roles', 'Role and permission management', '🛡️', '/roles', '1.0.0', 15, 1, 1);

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
