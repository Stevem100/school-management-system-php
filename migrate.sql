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
