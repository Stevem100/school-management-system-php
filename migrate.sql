-- Migration: Add missing columns to notifications and modules tables
-- Run this on an existing database to apply schema updates
-- Usage: mysql -u root school_erp < migrate.sql

SET NAMES utf8mb8mb4;

-- 1. Add missing columns to notifications table
ALTER TABLE notifications ADD COLUMN IF NOT EXISTS sender_id INT UNSIGNED AFTER user_id;
ALTER TABLE notifications ADD COLUMN IF NOT EXISTS recipient_id INT UNSIGNED AFTER sender_id;
ALTER TABLE notifications ADD COLUMN IF NOT EXISTS priority VARCHAR(255) DEFAULT 'medium' AFTER channel;
ALTER TABLE notifications ADD INDEX IF NOT EXISTS idx_notifications_recipient (recipient_id);
ALTER TABLE IF NOT EXISTS idx_notifications_sender (sender_id);

-- 2. Add missing columns to modules table
ALTER TABLE modules ADD COLUMN IF NOT EXISTS display_name VARCHAR(255) AFTER name;
ALTER TABLE modules ADD COLUMN IF NOT EXISTS icon VARCHAR(255) AFTER description;
ALTER TABLE modules ADD COLUMN IF NOT EXISTS route VARCHAR(255) AFTER icon;
ALTER TABLE modules ADD COLUMN IF NOT EXISTS sort_order INT DEFAULT 0 AFTER version;
ALTER TABLE modules ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 AFTER is_core;

-- 3. Update existing module records with display_name, icon, route, sort_order
UPDATE modules SET display_name = 'Academic', icon = '📚', route = '/classes', sort_order = 1, is_active = 1 WHERE name = 'academic' AND display_name IS NULL;
UPDATE modules SET display_name = 'Finance', icon = '💰', route = '/fees', sort_order = 2, is_active = 1 WHERE name = 'finance' AND display_name IS NULL;
UPDATE modules SET display_name = 'Attendance', icon = '📋', route = '/attendance', sort_order = 3, is_active = 1 WHERE name = 'attendance' AND display_name IS NULL;
UPDATE modules SET display_name = 'AI Assistant', icon = '🤖', route = '/ai-chat', sort_order = 4, is_active = 1 WHERE name = 'ai' AND display_name IS NULL;
UPDATE modules SET display_name = 'Transport', icon = '🚌', route = '/transport', sort_order = 5, is_active = 1 WHERE name = 'transport' AND display_name IS NULL;
UPDATE modules SET display_name = 'Library', icon = '📖', route = '/library', sort_order = 6, is_active = 1 WHERE name = 'library' AND display_name IS NULL;
UPDATE modules SET display_name = 'Hostel', icon = '🏠', route = '/hostel', sort_order = 7, is_active = 1 WHERE name = 'hostel' AND display_name IS NULL;
UPDATE modules SET display_name = 'LMS', icon = '💻', route = '/lms', sort_order = 8, is_active = 1 WHERE name = 'lms' AND display_name IS NULL;
UPDATE modules SET display_name = 'Communication', icon = '✉️', route = '/communication', sort_order = 9, is_active = 1 WHERE name = 'communication' AND display_name IS NULL;
UPDATE modules SET display_name = 'Reports', icon = '📊', route = '/reports', sort_order = 10, is_active = 1 WHERE name = 'reports' AND display_name IS NULL;

-- 4. Insert missing module entries
INSERT IGNORE INTO modules (name, display_name, description, icon, route, version, sort_order, is_core, is_active)
VALUES
  ('students', 'Students', 'Student management and profiles', '🎓', '/students', '1.0.0', 11, 1, 1),
  ('schools', 'Schools', 'Multi-school management', '🏫', '/schools', '1.0.0', 12, 1, 1),
  ('branches', 'Branches', 'Branch and campus management', '🏢', '/branches', '1.0.0', 13, 1, 1),
  ('users', 'Users', 'User account management', '👥', '/users', '1.0.0', 14, 1, 1),
  ('roles', 'Roles', 'Role and permission management', '🛡️', '/roles', '1.0.0', 15, 1, 1);

-- 5. Add missing columns to classes table
ALTER TABLE classes ADD COLUMN IF NOT EXISTS grade_level VARCHAR(255) DEFAULT '' AFTER capacity;
ALTER TABLE classes ADD COLUMN IF NOT EXISTS section VARCHAR(255) DEFAULT '' AFTER grade_level;
ALTER TABLE classes ADD COLUMN IF NOT EXISTS academic_year VARCHAR(255) DEFAULT '' AFTER section;
ALTER TABLE classes ADD COLUMN IF NOT EXISTS class_teacher_id INT UNSIGNED DEFAULT NULL AFTER academic_year;
ALTER TABLE classes ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'active' AFTER class_teacher_id;
ALTER TABLE classes ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT CURRENT_TIMESTAMP AFTER created_at;
ALTER TABLE classes ADD COLUMN IF NOT EXISTS branch_id INT UNSIGNED NOT NULL DEFAULT 0 AFTER school_id;

-- 6. Add missing attendance table (if not exists)
CREATE TABLE IF NOT EXISTS attendance (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id INT UNSIGNED DEFAULT 0,
  branch_id INT UNSIGNED DEFAULT 0,
  student_id INT UNSIGNED NOT NULL,
  class_id INT UNSIGNED DEFAULT NULL,
  date DATE NOT NULL,
  status VARCHAR(50) DEFAULT 'present' COMMENT 'present|absent|late|excused',
  remarks TEXT,
  recorded_by INT UNSIGNED DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_attendance_student (student_id),
  INDEX idx_attendance_class (class_id),
  INDEX idx_attendance_date (date),
  INDEX idx_attendance_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Add missing student_parents table (if not exists)
CREATE TABLE IF NOT EXISTS student_parents (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  parent_id INT UNSIGNED NOT NULL,
  relationship VARCHAR(100) DEFAULT 'guardian',
  is_primary_guardian TINYINT(1) DEFAULT 0,
  can_pickup TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_sp_student (student_id),
  INDEX idx_sp_parent (parent_id),
  UNIQUE KEY uk_student_parent (student_id, parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
