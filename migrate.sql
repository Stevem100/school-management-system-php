-- Migration: Add missing columns and tables
-- Run this AFTER setup.sql to patch the schema

SET NAMES utf8mb4;

-- ==================== ADD MISSING COLUMNS ====================

-- Users table: add missing columns
ALTER TABLE users ADD COLUMN IF NOT EXISTS user_type VARCHAR(50) DEFAULT 'staff';
ALTER TABLE users ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'active';

-- Classes table: add missing columns  
ALTER TABLE classes ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'active';
ALTER TABLE classes ADD COLUMN IF NOT EXISTS grade_level INT DEFAULT 0;
ALTER TABLE classes ADD COLUMN IF NOT EXISTS academic_year VARCHAR(50) DEFAULT '';
ALTER TABLE classes ADD COLUMN IF NOT EXISTS class_teacher_id INT UNSIGNED;
-- Rename numeric_level reference: grade_level = numeric_level (keep both, set grade_level)
UPDATE classes SET grade_level = numeric_level WHERE grade_level = 0 AND numeric_level > 0;
-- Rename teacher_id reference: class_teacher_id = teacher_id  
UPDATE classes SET class_teacher_id = teacher_id WHERE class_teacher_id IS NULL AND teacher_id IS NOT NULL;

-- Subjects table: add missing columns
ALTER TABLE subjects ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'active';
ALTER TABLE subjects ADD COLUMN IF NOT EXISTS credit_hours INT DEFAULT 0;

-- Exams table: add missing columns
ALTER TABLE exams ADD COLUMN IF NOT EXISTS subject_id INT UNSIGNED;
ALTER TABLE exams ADD COLUMN IF NOT EXISTS class_id INT UNSIGNED;
ALTER TABLE exams ADD COLUMN IF NOT EXISTS total_marks INT DEFAULT 100;
ALTER TABLE exams ADD COLUMN IF NOT EXISTS passing_marks INT DEFAULT 40;

-- Skills table: add missing column
ALTER TABLE skills ADD COLUMN IF NOT EXISTS category VARCHAR(100) DEFAULT 'general';

-- ==================== CREATE MISSING TABLES ====================

-- Attendance table (for AttendanceController)
CREATE TABLE IF NOT EXISTS attendance (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  student_id INT UNSIGNED NOT NULL,
  class_id INT UNSIGNED NOT NULL,
  date DATE NOT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'present',
  remarks TEXT,
  recorded_by INT UNSIGNED,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(student_id, class_id, date),
  INDEX idx_attendance_student (student_id),
  INDEX idx_attendance_class (class_id),
  INDEX idx_attendance_date (date)
);

-- Fee Structures table (for FeeController)
CREATE TABLE IF NOT EXISTS fee_structures (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  class_id INT UNSIGNED,
  term VARCHAR(50),
  academic_year VARCHAR(50),
  total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
  description TEXT,
  status VARCHAR(50) DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_fee_structures_school (school_id)
);

-- Fee Items table (for FeeController)
CREATE TABLE IF NOT EXISTS fee_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  fee_structure_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  amount DECIMAL(12,2) NOT NULL DEFAULT 0,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_fee_items_structure (fee_structure_id)
);

-- ==================== WEBSITE MODULE ====================

-- Website settings (one per school/branch)
CREATE TABLE IF NOT EXISTS website_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  is_active TINYINT(1) DEFAULT 0,
  site_title VARCHAR(255) NOT NULL DEFAULT '',
  site_tagline TEXT,
  logo_url TEXT,
  favicon_url TEXT,
  primary_color VARCHAR(20) DEFAULT '#059669',
  secondary_color VARCHAR(20) DEFAULT '#064e3b',
  accent_color VARCHAR(20) DEFAULT '#34d399',
  header_layout VARCHAR(50) DEFAULT 'standard',
  footer_text TEXT,
  show_admission_link TINYINT(1) DEFAULT 1,
  show_contact_form TINYINT(1) DEFAULT 1,
  show_gallery TINYINT(1) DEFAULT 1,
  show_testimonials TINYINT(1) DEFAULT 1,
  show_news_events TINYINT(1) DEFAULT 1,
  social_facebook TEXT,
  social_twitter TEXT,
  social_instagram TEXT,
  social_youtube TEXT,
  meta_description TEXT,
  meta_keywords TEXT,
  google_analytics_id TEXT,
  custom_css TEXT,
  custom_js TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(school_id, branch_id)
);

-- Website pages (dynamic CMS pages)
CREATE TABLE IF NOT EXISTS website_pages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  content LONGTEXT,
  meta_title VARCHAR(255),
  meta_description TEXT,
  featured_image TEXT,
  template VARCHAR(100) DEFAULT 'default',
  status VARCHAR(50) DEFAULT 'draft',
  sort_order INT DEFAULT 0,
  show_in_menu TINYINT(1) DEFAULT 1,
  menu_label VARCHAR(255),
  is_homepage TINYINT(1) DEFAULT 0,
  created_by INT UNSIGNED,
  updated_by INT UNSIGNED,
  published_at DATETIME,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(school_id, branch_id, slug),
  INDEX idx_website_pages_status (status),
  INDEX idx_website_pages_sort (sort_order)
);

-- Website menu items (custom navigation)
CREATE TABLE IF NOT EXISTS website_menu_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  label VARCHAR(255) NOT NULL,
  url VARCHAR(500),
  page_id INT UNSIGNED,
  parent_id INT UNSIGNED DEFAULT 0,
  target VARCHAR(20) DEFAULT '_self',
  sort_order INT DEFAULT 0,
  is_active TINYINT(1) DEFAULT 1,
  menu_position VARCHAR(50) DEFAULT 'main',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_menu_items_parent (parent_id),
  INDEX idx_menu_items_sort (sort_order)
);

-- Website media (images, videos, documents)
CREATE TABLE IF NOT EXISTS website_media (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  file_type VARCHAR(50) NOT NULL,
  file_size BIGINT DEFAULT 0,
  file_path TEXT NOT NULL,
  alt_text VARCHAR(500),
  category VARCHAR(100) DEFAULT 'general',
  uploaded_by INT UNSIGNED,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_media_school (school_id),
  INDEX idx_media_category (category)
);

-- ==================== ADMISSION MODULE ====================

-- Admission settings (per school/branch)
CREATE TABLE IF NOT EXISTS admission_settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  is_active TINYINT(1) DEFAULT 0,
  academic_year VARCHAR(50) NOT NULL,
  term VARCHAR(50) DEFAULT '',
  start_date DATE,
  end_date DATE,
  max_applications INT DEFAULT 0,
  application_fee DECIMAL(10,2) DEFAULT 0,
  instructions TEXT,
  thank_you_message TEXT,
  require_documents TINYINT(1) DEFAULT 1,
  require_photo TINYINT(1) DEFAULT 1,
  status VARCHAR(50) DEFAULT 'open',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(school_id, branch_id, academic_year)
);

-- Admission form fields (dynamic fields)
CREATE TABLE IF NOT EXISTS admission_fields (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  field_name VARCHAR(255) NOT NULL,
  field_label VARCHAR(255) NOT NULL,
  field_type VARCHAR(50) NOT NULL DEFAULT 'text',
  field_options TEXT,
  placeholder VARCHAR(500),
  validation_rules TEXT,
  section VARCHAR(100) DEFAULT 'personal',
  sort_order INT DEFAULT 0,
  is_required TINYINT(1) DEFAULT 1,
  is_active TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_admission_fields_section (section),
  INDEX idx_admission_fields_sort (sort_order)
);

-- Admission applications
CREATE TABLE IF NOT EXISTS admission_applications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED NOT NULL,
  branch_id INT UNSIGNED NOT NULL,
  application_no VARCHAR(255) NOT NULL UNIQUE,
  applicant_name VARCHAR(255) NOT NULL,
  applicant_email VARCHAR(255),
  applicant_phone VARCHAR(100),
  form_data LONGTEXT,
  status VARCHAR(50) DEFAULT 'pending',
  applied_class_id INT UNSIGNED,
  reviewed_by INT UNSIGNED,
  review_notes TEXT,
  reviewed_at DATETIME,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_applications_school (school_id),
  INDEX idx_applications_status (status)
);

-- Admission attachments
CREATE TABLE IF NOT EXISTS admission_attachments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  application_id INT UNSIGNED NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  file_path TEXT NOT NULL,
  file_type VARCHAR(50),
  file_size BIGINT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_attachments_application (application_id)
);
