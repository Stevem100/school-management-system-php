-- School Management System - Complete Setup Script
-- Run this in the Supabase SQL Editor (https://supabase.com/dashboard/project/uomjhejhtlzmayxvzglg/sql)
-- This script: creates tables, disables RLS, inserts demo data
-- One-click setup for a fresh database!

-- ==================== PART 1: SCHEMA ====================

-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- ==================== CORE TENANT ====================

CREATE TABLE IF NOT EXISTS schools (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  name TEXT NOT NULL,
  code TEXT NOT NULL UNIQUE,
  type TEXT DEFAULT 'general',
  logo TEXT,
  address TEXT,
  phone TEXT,
  email TEXT,
  website TEXT,
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS branches (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL REFERENCES schools(id) ON DELETE CASCADE,
  name TEXT NOT NULL,
  code TEXT NOT NULL,
  address TEXT,
  phone TEXT,
  email TEXT,
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(school_id, code)
);

-- ==================== RBAC ====================

CREATE TABLE IF NOT EXISTS roles (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  name TEXT NOT NULL,
  scope TEXT NOT NULL,
  description TEXT,
  level INT DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS permissions (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  name TEXT NOT NULL UNIQUE,
  module TEXT NOT NULL,
  action TEXT NOT NULL,
  description TEXT,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS role_permissions (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  role_id UUID NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
  permission_id UUID NOT NULL REFERENCES permissions(id) ON DELETE CASCADE,
  created_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(role_id, permission_id)
);

-- ==================== USERS ====================

CREATE TABLE IF NOT EXISTS users (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID REFERENCES schools(id),
  branch_id UUID REFERENCES branches(id),
  email TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  first_name TEXT NOT NULL,
  last_name TEXT NOT NULL,
  phone TEXT,
  avatar TEXT,
  is_active BOOLEAN DEFAULT true,
  last_login TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS user_roles (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  role_id UUID NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
  school_id UUID,
  branch_id UUID,
  created_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(user_id, role_id, branch_id)
);

-- ==================== ACADEMIC ====================

CREATE TABLE IF NOT EXISTS student_profiles (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id UUID NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  admission_no TEXT NOT NULL,
  roll_number TEXT,
  date_of_birth DATE,
  gender TEXT DEFAULT 'other',
  blood_group TEXT,
  nationality TEXT,
  address TEXT,
  guardian_name TEXT,
  guardian_phone TEXT,
  guardian_email TEXT,
  status TEXT DEFAULT 'active',
  enroll_date TIMESTAMPTZ DEFAULT now(),
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(school_id, branch_id, admission_no)
);

CREATE TABLE IF NOT EXISTS teacher_profiles (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id UUID NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  employee_id TEXT NOT NULL,
  qualification TEXT,
  specialization TEXT,
  department TEXT,
  join_date TIMESTAMPTZ DEFAULT now(),
  status TEXT DEFAULT 'active',
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(school_id, branch_id, employee_id)
);

CREATE TABLE IF NOT EXISTS parent_profiles (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id UUID NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  occupation TEXT,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS student_parents (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  student_id UUID NOT NULL REFERENCES student_profiles(id) ON DELETE CASCADE,
  parent_id UUID NOT NULL REFERENCES parent_profiles(id) ON DELETE CASCADE,
  relation TEXT,
  created_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(student_id, parent_id)
);

CREATE TABLE IF NOT EXISTS classes (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  name TEXT NOT NULL,
  section TEXT,
  numeric_level INT DEFAULT 0,
  teacher_id UUID REFERENCES teacher_profiles(id),
  capacity INT DEFAULT 40,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(school_id, branch_id, name, section)
);

CREATE TABLE IF NOT EXISTS classrooms (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  name TEXT NOT NULL,
  building TEXT,
  floor INT DEFAULT 1,
  capacity INT DEFAULT 40,
  has_projector BOOLEAN DEFAULT false,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS enrollments (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  student_id UUID NOT NULL REFERENCES student_profiles(id) ON DELETE CASCADE,
  class_id UUID NOT NULL REFERENCES classes(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  academic_year TEXT,
  status TEXT DEFAULT 'active',
  enroll_date TIMESTAMPTZ DEFAULT now(),
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(student_id, class_id, academic_year)
);

CREATE TABLE IF NOT EXISTS subjects (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  name TEXT NOT NULL,
  code TEXT NOT NULL,
  type TEXT DEFAULT 'core',
  description TEXT,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(school_id, branch_id, code)
);

CREATE TABLE IF NOT EXISTS subject_teachers (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  subject_id UUID NOT NULL REFERENCES subjects(id) ON DELETE CASCADE,
  teacher_id UUID NOT NULL REFERENCES teacher_profiles(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  created_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(subject_id, teacher_id)
);

CREATE TABLE IF NOT EXISTS exams (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  name TEXT NOT NULL,
  type TEXT DEFAULT 'term',
  academic_year TEXT,
  term TEXT,
  start_date TIMESTAMPTZ NOT NULL,
  end_date TIMESTAMPTZ NOT NULL,
  status TEXT DEFAULT 'upcoming',
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS exam_results (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  exam_id UUID NOT NULL REFERENCES exams(id) ON DELETE CASCADE,
  student_id UUID NOT NULL REFERENCES student_profiles(id) ON DELETE CASCADE,
  subject_id UUID NOT NULL REFERENCES subjects(id),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  marks_obtained INT NOT NULL,
  total_marks INT NOT NULL,
  grade TEXT,
  remarks TEXT,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(exam_id, student_id, subject_id)
);

CREATE TABLE IF NOT EXISTS timetable_entries (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  class_id UUID NOT NULL REFERENCES classes(id),
  subject_id UUID NOT NULL REFERENCES subjects(id),
  classroom_id UUID REFERENCES classrooms(id),
  teacher_id TEXT,
  day_of_week INT NOT NULL,
  period INT NOT NULL,
  start_time TEXT,
  end_time TEXT,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(class_id, day_of_week, period)
);

-- ==================== CBC SKILLS ====================

CREATE TABLE IF NOT EXISTS skills (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  subject_id UUID REFERENCES subjects(id),
  name TEXT NOT NULL,
  code TEXT NOT NULL,
  strand TEXT,
  sub_strand TEXT,
  level TEXT DEFAULT 'basic',
  description TEXT,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS student_skills (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  student_id UUID NOT NULL REFERENCES student_profiles(id) ON DELETE CASCADE,
  skill_id UUID NOT NULL REFERENCES skills(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  status TEXT DEFAULT 'not_started',
  rating INT,
  evidence TEXT,
  assessed_by TEXT,
  assessed_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(student_id, skill_id)
);

CREATE TABLE IF NOT EXISTS teacher_skills (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  teacher_id UUID NOT NULL REFERENCES teacher_profiles(id) ON DELETE CASCADE,
  skill_id UUID NOT NULL REFERENCES skills(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  proficiency INT DEFAULT 1,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(teacher_id, skill_id)
);

-- ==================== FINANCE ====================

CREATE TABLE IF NOT EXISTS fees (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  student_id UUID NOT NULL REFERENCES student_profiles(id) ON DELETE CASCADE,
  title TEXT NOT NULL,
  description TEXT,
  amount DECIMAL(10,2) NOT NULL,
  due_date TIMESTAMPTZ,
  status TEXT DEFAULT 'pending',
  academic_year TEXT,
  term TEXT,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS payments (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  student_id UUID NOT NULL REFERENCES student_profiles(id) ON DELETE CASCADE,
  fee_id UUID NOT NULL REFERENCES fees(id) ON DELETE CASCADE,
  amount DECIMAL(10,2) NOT NULL,
  method TEXT DEFAULT 'cash',
  reference TEXT,
  receipt_no TEXT,
  status TEXT DEFAULT 'completed',
  paid_by TEXT,
  paid_at TIMESTAMPTZ DEFAULT now(),
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

-- ==================== TRANSPORT ====================

CREATE TABLE IF NOT EXISTS transport_routes (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  name TEXT NOT NULL,
  description TEXT,
  distance DECIMAL(6,2),
  estimated_time INT,
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS transport_vehicles (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  plate_number TEXT NOT NULL,
  vehicle_type TEXT DEFAULT 'bus',
  capacity INT DEFAULT 50,
  driver_name TEXT,
  driver_phone TEXT,
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS transport_assignments (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  student_id UUID NOT NULL REFERENCES student_profiles(id) ON DELETE CASCADE,
  route_id UUID NOT NULL REFERENCES transport_routes(id) ON DELETE CASCADE,
  vehicle_id UUID REFERENCES transport_vehicles(id),
  pickup_point TEXT,
  dropoff_point TEXT,
  pickup_time TEXT,
  dropoff_time TEXT,
  status TEXT DEFAULT 'active',
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

-- ==================== HOSTEL ====================

CREATE TABLE IF NOT EXISTS hostel_rooms (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  name TEXT NOT NULL,
  building TEXT,
  floor INT DEFAULT 1,
  capacity INT DEFAULT 4,
  type TEXT DEFAULT 'shared',
  amenities TEXT,
  is_active BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS hostel_assignments (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  student_id UUID NOT NULL REFERENCES student_profiles(id) ON DELETE CASCADE,
  room_id UUID NOT NULL REFERENCES hostel_rooms(id) ON DELETE CASCADE,
  bed_number INT NOT NULL,
  check_in_date TIMESTAMPTZ DEFAULT now(),
  status TEXT DEFAULT 'active',
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(room_id, bed_number)
);

-- ==================== LIBRARY ====================

CREATE TABLE IF NOT EXISTS books (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  title TEXT NOT NULL,
  author TEXT NOT NULL,
  isbn TEXT,
  publisher TEXT,
  category TEXT,
  total_copies INT DEFAULT 1,
  available_copies INT DEFAULT 1,
  location TEXT,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS book_borrowals (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  book_id UUID NOT NULL REFERENCES books(id) ON DELETE CASCADE,
  student_id UUID NOT NULL REFERENCES student_profiles(id) ON DELETE CASCADE,
  borrow_date TIMESTAMPTZ DEFAULT now(),
  due_date TIMESTAMPTZ NOT NULL,
  return_date TIMESTAMPTZ,
  status TEXT DEFAULT 'borrowed',
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

-- ==================== LMS ====================

CREATE TABLE IF NOT EXISTS lms_courses (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  title TEXT NOT NULL,
  description TEXT,
  subject_id UUID REFERENCES subjects(id),
  teacher_id TEXT,
  thumbnail TEXT,
  status TEXT DEFAULT 'draft',
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS lms_lessons (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  course_id UUID NOT NULL REFERENCES lms_courses(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  title TEXT NOT NULL,
  content TEXT,
  video_url TEXT,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS lms_assignments (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  course_id UUID NOT NULL REFERENCES lms_courses(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  title TEXT NOT NULL,
  description TEXT,
  due_date TIMESTAMPTZ,
  total_marks INT DEFAULT 100,
  status TEXT DEFAULT 'open',
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS lms_submissions (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  assignment_id UUID NOT NULL REFERENCES lms_assignments(id) ON DELETE CASCADE,
  student_id TEXT NOT NULL,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  content TEXT,
  file_url TEXT,
  marks INT,
  feedback TEXT,
  status TEXT DEFAULT 'submitted',
  submitted_at TIMESTAMPTZ DEFAULT now(),
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

-- ==================== COMMUNICATION ====================

CREATE TABLE IF NOT EXISTS notifications (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID,
  branch_id UUID,
  user_id UUID REFERENCES users(id) ON DELETE SET NULL,
  title TEXT NOT NULL,
  message TEXT NOT NULL,
  type TEXT DEFAULT 'info',
  channel TEXT DEFAULT 'in_app',
  is_read BOOLEAN DEFAULT false,
  created_at TIMESTAMPTZ DEFAULT now()
);

-- ==================== AI MODULE ====================

CREATE TABLE IF NOT EXISTS ai_settings (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  is_openai_enabled BOOLEAN DEFAULT false,
  openai_api_url TEXT DEFAULT 'http://localhost:3000/api/chat/completions',
  openai_model TEXT DEFAULT 'open-webui/default',
  openai_api_key TEXT,
  max_tokens INT DEFAULT 2048,
  temperature DECIMAL(3,2) DEFAULT 0.7,
  is_student_access BOOLEAN DEFAULT true,
  is_teacher_monitor BOOLEAN DEFAULT true,
  allow_free_chat BOOLEAN DEFAULT false,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(school_id, branch_id)
);

CREATE TABLE IF NOT EXISTS ai_conversations (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  title TEXT NOT NULL,
  skill_id UUID REFERENCES skills(id),
  type TEXT DEFAULT 'academic_help',
  status TEXT DEFAULT 'active',
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS ai_messages (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  conversation_id UUID NOT NULL REFERENCES ai_conversations(id) ON DELETE CASCADE,
  role TEXT NOT NULL,
  content TEXT NOT NULL,
  skill_id UUID REFERENCES skills(id),
  tokens_used INT,
  created_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS ai_logs (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  action TEXT NOT NULL,
  input TEXT,
  output TEXT,
  model TEXT,
  tokens_used INT,
  duration INT,
  ip_address TEXT,
  created_at TIMESTAMPTZ DEFAULT now()
);

-- ==================== MODULE SYSTEM ====================

CREATE TABLE IF NOT EXISTS modules (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  name TEXT NOT NULL UNIQUE,
  version TEXT DEFAULT '1.0.0',
  description TEXT,
  is_core BOOLEAN DEFAULT false,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS module_licenses (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  module_id UUID NOT NULL REFERENCES modules(id) ON DELETE CASCADE,
  school_id UUID NOT NULL,
  branch_id UUID NOT NULL,
  is_enabled BOOLEAN DEFAULT true,
  expires_at TIMESTAMPTZ,
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE(module_id, school_id, branch_id)
);

-- ==================== INDEXES ====================

CREATE INDEX IF NOT EXISTS idx_users_school ON users(school_id);
CREATE INDEX IF NOT EXISTS idx_users_branch ON users(branch_id);
CREATE INDEX IF NOT EXISTS idx_student_profiles_school ON student_profiles(school_id);
CREATE INDEX IF NOT EXISTS idx_student_profiles_branch ON student_profiles(branch_id);
CREATE INDEX IF NOT EXISTS idx_classes_school ON classes(school_id);
CREATE INDEX IF NOT EXISTS idx_classes_branch ON classes(branch_id);
CREATE INDEX IF NOT EXISTS idx_subjects_school ON subjects(school_id);
CREATE INDEX IF NOT EXISTS idx_subjects_branch ON subjects(branch_id);
CREATE INDEX IF NOT EXISTS idx_fees_school ON fees(school_id);
CREATE INDEX IF NOT EXISTS idx_fees_branch ON fees(branch_id);
CREATE INDEX IF NOT EXISTS idx_fees_student ON fees(student_id);
CREATE INDEX IF NOT EXISTS idx_payments_school ON payments(school_id);
CREATE INDEX IF NOT EXISTS idx_payments_branch ON payments(branch_id);
CREATE INDEX IF NOT EXISTS idx_payments_student ON payments(student_id);
CREATE INDEX IF NOT EXISTS idx_notifications_user ON notifications(user_id);
CREATE INDEX IF NOT EXISTS idx_ai_conversations_user ON ai_conversations(user_id);
CREATE INDEX IF NOT EXISTS idx_ai_conversations_branch ON ai_conversations(branch_id);
CREATE INDEX IF NOT EXISTS idx_ai_messages_conversation ON ai_messages(conversation_id);
CREATE INDEX IF NOT EXISTS idx_transport_school ON transport_routes(school_id);
CREATE INDEX IF NOT EXISTS idx_transport_branch ON transport_routes(branch_id);
CREATE INDEX IF NOT EXISTS idx_library_school ON books(school_id);
CREATE INDEX IF NOT EXISTS idx_library_branch ON books(branch_id);
CREATE INDEX IF NOT EXISTS idx_hostel_school ON hostel_rooms(school_id);
CREATE INDEX IF NOT EXISTS idx_hostel_branch ON hostel_rooms(branch_id);
CREATE INDEX IF NOT EXISTS idx_lms_school ON lms_courses(school_id);
CREATE INDEX IF NOT EXISTS idx_lms_branch ON lms_courses(branch_id);
CREATE INDEX IF NOT EXISTS idx_module_licenses_school ON module_licenses(school_id);
CREATE INDEX IF NOT EXISTS idx_module_licenses_branch ON module_licenses(branch_id);

-- ==================== SESSIONS (for our custom auth) ====================

CREATE TABLE IF NOT EXISTS sessions (
  id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  token TEXT NOT NULL UNIQUE,
  school_id UUID,
  branch_id UUID,
  ip_address TEXT,
  user_agent TEXT,
  expires_at TIMESTAMPTZ NOT NULL,
  created_at TIMESTAMPTZ DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_sessions_token ON sessions(token);

-- ==================== PART 2: SEED DATA ====================

-- ==================== STEP 1: DISABLE RLS ON ALL TABLES ====================
ALTER TABLE schools DISABLE ROW LEVEL SECURITY;
ALTER TABLE branches DISABLE ROW LEVEL SECURITY;
ALTER TABLE roles DISABLE ROW LEVEL SECURITY;
ALTER TABLE permissions DISABLE ROW LEVEL SECURITY;
ALTER TABLE role_permissions DISABLE ROW LEVEL SECURITY;
ALTER TABLE users DISABLE ROW LEVEL SECURITY;
ALTER TABLE user_roles DISABLE ROW LEVEL SECURITY;
ALTER TABLE sessions DISABLE ROW LEVEL SECURITY;
ALTER TABLE student_profiles DISABLE ROW LEVEL SECURITY;
ALTER TABLE teacher_profiles DISABLE ROW LEVEL SECURITY;
ALTER TABLE parent_profiles DISABLE ROW LEVEL SECURITY;
ALTER TABLE student_parents DISABLE ROW LEVEL SECURITY;
ALTER TABLE classes DISABLE ROW LEVEL SECURITY;
ALTER TABLE classrooms DISABLE ROW LEVEL SECURITY;
ALTER TABLE enrollments DISABLE ROW LEVEL SECURITY;
ALTER TABLE subjects DISABLE ROW LEVEL SECURITY;
ALTER TABLE subject_teachers DISABLE ROW LEVEL SECURITY;
ALTER TABLE exams DISABLE ROW LEVEL SECURITY;
ALTER TABLE exam_results DISABLE ROW LEVEL SECURITY;
ALTER TABLE timetable_entries DISABLE ROW LEVEL SECURITY;
ALTER TABLE skills DISABLE ROW LEVEL SECURITY;
ALTER TABLE student_skills DISABLE ROW LEVEL SECURITY;
ALTER TABLE teacher_skills DISABLE ROW LEVEL SECURITY;
ALTER TABLE fees DISABLE ROW LEVEL SECURITY;
ALTER TABLE payments DISABLE ROW LEVEL SECURITY;
ALTER TABLE transport_routes DISABLE ROW LEVEL SECURITY;
ALTER TABLE transport_vehicles DISABLE ROW LEVEL SECURITY;
ALTER TABLE transport_assignments DISABLE ROW LEVEL SECURITY;
ALTER TABLE hostel_rooms DISABLE ROW LEVEL SECURITY;
ALTER TABLE hostel_assignments DISABLE ROW LEVEL SECURITY;
ALTER TABLE books DISABLE ROW LEVEL SECURITY;
ALTER TABLE book_borrowals DISABLE ROW LEVEL SECURITY;
ALTER TABLE lms_courses DISABLE ROW LEVEL SECURITY;
ALTER TABLE lms_lessons DISABLE ROW LEVEL SECURITY;
ALTER TABLE lms_assignments DISABLE ROW LEVEL SECURITY;
ALTER TABLE lms_submissions DISABLE ROW LEVEL SECURITY;
ALTER TABLE notifications DISABLE ROW LEVEL SECURITY;
ALTER TABLE ai_settings DISABLE ROW LEVEL SECURITY;
ALTER TABLE ai_conversations DISABLE ROW LEVEL SECURITY;
ALTER TABLE ai_messages DISABLE ROW LEVEL SECURITY;
ALTER TABLE ai_logs DISABLE ROW LEVEL SECURITY;
ALTER TABLE modules DISABLE ROW LEVEL SECURITY;
ALTER TABLE module_licenses DISABLE ROW LEVEL SECURITY;

-- ==================== STEP 2: CLEAR EXISTING DATA ====================
TRUNCATE TABLE module_licenses, modules, ai_logs, ai_messages, ai_conversations, ai_settings,
  notifications, lms_submissions, lms_assignments, lms_lessons, lms_courses,
  book_borrowals, books, hostel_assignments, hostel_rooms,
  transport_assignments, transport_vehicles, transport_routes,
  payments, fees, student_skills, teacher_skills, skills,
  timetable_entries, exam_results, exams, subject_teachers, subjects,
  enrollments, classes, classrooms, student_parents, parent_profiles,
  student_profiles, teacher_profiles, user_roles, sessions, users,
  role_permissions, permissions, roles, branches, schools CASCADE;

-- ==================== STEP 3: CREATE ROLES ====================
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

-- ==================== STEP 4: CREATE PERMISSIONS ====================
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

-- ==================== STEP 5: ROLE-PERMISSION MAPPINGS ====================
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

-- ==================== STEP 6: CREATE SCHOOL ====================
INSERT INTO schools (name, code, type, address, phone, email, website) VALUES
  ('Greenfield Academy', 'GA', 'general', '123 Education Lane, Nairobi', '+254-700-123456', 'info@greenfield.ac.ke', 'https://greenfield.ac.ke');

-- ==================== STEP 7: CREATE BRANCHES ====================
INSERT INTO branches (school_id, name, code, address, phone) VALUES
  ((SELECT id FROM schools WHERE code = 'GA'), 'Main Campus', 'MC', '123 Education Lane, Nairobi', '+254-700-123456'),
  ((SELECT id FROM schools WHERE code = 'GA'), 'West Campus', 'WC', '456 West Avenue, Nakuru', '+254-700-789012');

-- ==================== STEP 8: CREATE USERS ====================
-- Passwords are SHA-256 hashes of: password + '_school_erp_salt'
-- admin123: 03b1dbf3638037f717845e3e36e2c5ae0c28a13abef309dd61976f4c40fa2b18
-- demo123: 809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083
-- student123: 10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72

INSERT INTO users (email, password, first_name, last_name, phone, school_id, branch_id) VALUES
  -- SuperAdmin
  ('admin@school.com', '03b1dbf3638037f717845e3e36e2c5ae0c28a13abef309dd61976f4c40fa2b18', 'Admin', 'User', '+254-700-000001',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  -- SchoolAdmin
  ('schooladmin@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Margaret', 'Wanjiku', '+254-700-000010',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  -- BranchAdmins
  ('branchadmin@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'James', 'Odhiambo', '+254-700-000011',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  ('westadmin@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Grace', 'Chebet', '+254-700-000012',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'WC')),
  -- Dean
  ('dean@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Prof', 'Kamau', '+254-700-000013',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  -- Teachers
  ('mary@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Mary', 'Akinyi', '+254-700-000020',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  ('john@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'John', 'Mwangi', '+254-700-000021',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  ('faith@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Faith', 'Rotich', '+254-700-000022',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'WC')),
  -- Staff
  ('accounts@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Peter', 'Kariuki', '+254-700-000030',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  ('library@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Susan', 'Njeri', '+254-700-000031',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  ('transport@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'David', 'Mutua', '+254-700-000032',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  ('hostel@greenfield.ac.ke', '809ca9818040a03b8f923a18dec82588b41a9d89f495e4205b51e7a6b09de083', 'Rose', 'Wambui', '+254-700-000033',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  -- Students
  ('brian.njorgemc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Brian', 'Njoroge', '+254-700-000101',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  ('sarah.muthonimc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Sarah', 'Muthoni', '+254-700-000102',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  ('kevin.otienomc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Kevin', 'Otieno', '+254-700-000103',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  ('lucy.wanjirumc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Lucy', 'Wanjiru', '+254-700-000104',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  ('daniel.kipchogemc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Daniel', 'Kipchoge', '+254-700-000105',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'MC')),
  ('amina.hassanwc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Amina', 'Hassan', '+254-700-000106',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'WC')),
  ('ian.machariawc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Ian', 'Macharia', '+254-700-000107',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'WC')),
  ('esther.nekesawc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Esther', 'Nekesa', '+254-700-000108',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'WC')),
  ('alex.wekesawc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Alex', 'Wekesa', '+254-700-000109',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'WC')),
  ('mercy.moraawc@greenfield.ac.ke', '10b18736f7330af3305d3c72b233ea6942149e4dd023ec01dbba61de587dbc72', 'Mercy', 'Moraa', '+254-700-000110',
    (SELECT id FROM schools WHERE code = 'GA'), (SELECT id FROM branches WHERE code = 'WC'));

-- ==================== STEP 9: ASSIGN USER ROLES ====================
INSERT INTO user_roles (user_id, role_id, school_id, branch_id)
SELECT u.id, r.id, u.school_id, u.branch_id FROM users u, roles r
WHERE u.email = 'admin@school.com' AND r.name = 'SuperAdmin';

INSERT INTO user_roles (user_id, role_id, school_id, branch_id)
SELECT u.id, r.id, u.school_id, u.branch_id FROM users u, roles r
WHERE u.email = 'schooladmin@greenfield.ac.ke' AND r.name = 'SchoolAdmin';

INSERT INTO user_roles (user_id, role_id, school_id, branch_id)
SELECT u.id, r.id, u.school_id, u.branch_id FROM users u, roles r
WHERE u.email IN ('branchadmin@greenfield.ac.ke', 'westadmin@greenfield.ac.ke') AND r.name = 'BranchAdmin';

INSERT INTO user_roles (user_id, role_id, school_id, branch_id)
SELECT u.id, r.id, u.school_id, u.branch_id FROM users u, roles r
WHERE u.email = 'dean@greenfield.ac.ke' AND r.name = 'Dean';

INSERT INTO user_roles (user_id, role_id, school_id, branch_id)
SELECT u.id, r.id, u.school_id, u.branch_id FROM users u, roles r
WHERE u.email IN ('mary@greenfield.ac.ke', 'john@greenfield.ac.ke', 'faith@greenfield.ac.ke') AND r.name = 'Teacher';

INSERT INTO user_roles (user_id, role_id, school_id, branch_id)
SELECT u.id, r.id, u.school_id, u.branch_id FROM users u, roles r
WHERE u.email = 'accounts@greenfield.ac.ke' AND r.name = 'Accountant';

INSERT INTO user_roles (user_id, role_id, school_id, branch_id)
SELECT u.id, r.id, u.school_id, u.branch_id FROM users u, roles r
WHERE u.email = 'library@greenfield.ac.ke' AND r.name = 'Librarian';

INSERT INTO user_roles (user_id, role_id, school_id, branch_id)
SELECT u.id, r.id, u.school_id, u.branch_id FROM users u, roles r
WHERE u.email = 'transport@greenfield.ac.ke' AND r.name = 'TransportManager';

INSERT INTO user_roles (user_id, role_id, school_id, branch_id)
SELECT u.id, r.id, u.school_id, u.branch_id FROM users u, roles r
WHERE u.email = 'hostel@greenfield.ac.ke' AND r.name = 'HostelManager';

INSERT INTO user_roles (user_id, role_id, school_id, branch_id)
SELECT u.id, r.id, u.school_id, u.branch_id FROM users u, roles r
WHERE u.email LIKE '%@greenfield.ac.ke' AND r.name = 'Student'
AND u.email NOT IN ('admin@school.com','schooladmin@greenfield.ac.ke','branchadmin@greenfield.ac.ke','westadmin@greenfield.ac.ke',
  'dean@greenfield.ac.ke','mary@greenfield.ac.ke','john@greenfield.ac.ke','faith@greenfield.ac.ke',
  'accounts@greenfield.ac.ke','library@greenfield.ac.ke','transport@greenfield.ac.ke','hostel@greenfield.ac.ke');

-- ==================== STEP 10: TEACHER PROFILES ====================
INSERT INTO teacher_profiles (user_id, school_id, branch_id, employee_id, qualification, specialization, department)
SELECT u.id, u.school_id, u.branch_id, 'EMP' || EXTRACT(EPOCH FROM now())::int || RIGHT(u.id::text, 4),
  'Bachelor of Education', 'Mathematics & Science', 'Academic'
FROM users u WHERE u.email IN ('mary@greenfield.ac.ke', 'john@greenfield.ac.ke', 'faith@greenfield.ac.ke');

-- ==================== STEP 11: STUDENT PROFILES ====================
INSERT INTO student_profiles (user_id, school_id, branch_id, admission_no, date_of_birth, gender, guardian_name, guardian_phone)
SELECT u.id, u.school_id, u.branch_id,
  'ADM/' || b.code || '/' || (ROW_NUMBER() OVER (PARTITION BY u.branch_id ORDER BY u.id) + 100),
  (DATE '2012-01-15' + (ROW_NUMBER() OVER (ORDER BY u.id) * INTERVAL '3 months'))::date,
  CASE WHEN ROW_NUMBER() OVER (ORDER BY u.id) % 2 = 1 THEN 'male' ELSE 'female' END,
  SPLIT_PART(u.last_name, ' ', 1) || ' Parent',
  '+254-7' || LPAD((RANDOM() * 10000000)::int::text, 7, '0')
FROM users u JOIN branches b ON u.branch_id = b.id
WHERE u.email LIKE '%@greenfield.ac.ke'
AND u.email NOT IN ('admin@school.com','schooladmin@greenfield.ac.ke','branchadmin@greenfield.ac.ke','westadmin@greenfield.ac.ke',
  'dean@greenfield.ac.ke','mary@greenfield.ac.ke','john@greenfield.ac.ke','faith@greenfield.ac.ke',
  'accounts@greenfield.ac.ke','library@greenfield.ac.ke','transport@greenfield.ac.ke','hostel@greenfield.ac.ke');

-- ==================== STEP 12: CLASSES ====================
INSERT INTO classes (school_id, branch_id, name, section, numeric_level) VALUES
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Grade 1', 'A', 1),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Grade 2', 'A', 2),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Grade 3', 'A', 3),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Grade 4', 'A', 4),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Grade 5', 'A', 5),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Grade 6', 'A', 6),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Grade 7', 'A', 7),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Grade 8', 'A', 8),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Form 1', 'A', 9),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Form 1', 'B', 9),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Form 2', 'A', 10),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Form 3', 'A', 11),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='MC'), 'Form 4', 'A', 12),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='WC'), 'Grade 1', 'A', 1),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='WC'), 'Grade 2', 'A', 2),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='WC'), 'Grade 3', 'A', 3),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='WC'), 'Grade 4', 'A', 4),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='WC'), 'Grade 5', 'A', 5),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='WC'), 'Form 1', 'A', 9),
  ((SELECT id FROM schools WHERE code='GA'), (SELECT id FROM branches WHERE code='WC'), 'Form 2', 'A', 10);

-- ==================== STEP 13: SUBJECTS ====================
INSERT INTO subjects (school_id, branch_id, name, code, type)
SELECT s.id, b.id, subj.name, subj.code, subj.type
FROM schools s, branches b,
(VALUES ('Mathematics','MATH','core'), ('English','ENG','core'), ('Kiswahili','KIS','core'),
  ('Science','SCI','core'), ('Social Studies','SST','core'), ('CRE','CRE','core'),
  ('IT','IT','optional')) AS subj(name, code, type)
WHERE s.code = 'GA';

-- ==================== STEP 14: ENROLLMENTS ====================
INSERT INTO enrollments (student_id, class_id, school_id, branch_id, academic_year)
WITH numbered_students AS (
  SELECT id, school_id, branch_id,
    (ROW_NUMBER() OVER (PARTITION BY branch_id ORDER BY id) - 1) AS student_idx
  FROM student_profiles
),
numbered_classes AS (
  SELECT id, branch_id,
    (ROW_NUMBER() OVER (PARTITION BY branch_id ORDER BY id) - 1) AS class_idx,
    COUNT(*) OVER (PARTITION BY branch_id) AS class_count
  FROM classes
)
SELECT ns.id, nc.id, ns.school_id, ns.branch_id, EXTRACT(YEAR FROM now())::text
FROM numbered_students ns
JOIN numbered_classes nc ON ns.branch_id = nc.branch_id
WHERE ns.student_idx % nc.class_count = nc.class_idx
LIMIT (SELECT COUNT(*) FROM student_profiles);

-- ==================== STEP 15: FEES ====================
INSERT INTO fees (school_id, branch_id, student_id, title, description, amount, due_date, status, academic_year, term)
SELECT sp.school_id, sp.branch_id, sp.id, 'Term 1 Tuition Fee', 'Academic year tuition for term 1',
  15000, (DATE_TRUNC('year', now()) + INTERVAL '2 months')::timestamptz, 'pending',
  EXTRACT(YEAR FROM now())::text, 'Term 1'
FROM student_profiles sp;

-- ==================== STEP 16: AI SETTINGS ====================
INSERT INTO ai_settings (school_id, branch_id, is_openai_enabled, openai_api_url, openai_model, max_tokens, temperature, is_student_access, is_teacher_monitor, allow_free_chat)
SELECT s.id, b.id, true, 'http://localhost:11434/v1/chat/completions', 'llama3', 2048, 0.7, true, true, false
FROM schools s, branches b WHERE s.code = 'GA';

-- ==================== STEP 17: CBC SKILLS ====================
INSERT INTO skills (school_id, branch_id, name, code, strand, sub_strand, level, description)
SELECT s.id, b.id, sk.name, sk.code, sk.strand, sk.sub_strand, sk.level, 'CBC aligned ' || sk.name || ' competency'
FROM schools s, branches b,
(VALUES ('Critical Thinking','CT','Core Competencies','Problem Solving','basic'),
  ('Communication','COM','Core Competencies','Oral Communication','basic'),
  ('Self-Management','SM','Core Competencies','Self-Discipline','basic'),
  ('Digital Literacy','DL','Core Competencies','ICT Skills','intermediate'),
  ('Collaboration','COL','Core Competencies','Teamwork','basic'),
  ('Creativity & Imagination','CR','Core Competencies','Innovation','intermediate'),
  ('Citizenship','CIT','Core Competencies','National Values','basic'),
  ('Learning to Learn','LTL','Core Competencies','Study Skills','basic')) AS sk(name, code, strand, sub_strand, level)
WHERE s.code = 'GA';

-- ==================== STEP 18: MODULES ====================
INSERT INTO modules (name, version, description, is_core) VALUES
  ('academic', '1.0.0', 'Academic management', true),
  ('finance', '1.0.0', 'Fee management and payments', true),
  ('attendance', '1.0.0', 'Student attendance tracking', true),
  ('ai', '1.0.0', 'AI-powered learning assistant', false),
  ('transport', '1.0.0', 'School transport management', false),
  ('library', '1.0.0', 'Library and book management', false),
  ('hostel', '1.0.0', 'Hostel and room management', false),
  ('lms', '1.0.0', 'Learning management system', false),
  ('communication', '1.0.0', 'Notifications and messaging', true),
  ('reports', '1.0.0', 'School-wide reports and analytics', true);

INSERT INTO module_licenses (module_id, school_id, branch_id, is_enabled)
SELECT m.id, s.id, b.id, true FROM modules m, schools s, branches b WHERE s.code = 'GA';

-- ==================== DONE! ====================
