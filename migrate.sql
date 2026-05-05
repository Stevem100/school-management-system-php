-- Migration: Add missing columns to notifications and modules tables
-- Run this on an existing database to apply schema updates
-- Usage: mysql -u root school_erp < migrate.sql

SET NAMES utf8mb4;

-- 1. Add missing columns to notifications table
ALTER TABLE notifications ADD COLUMN IF NOT EXISTS sender_id INT UNSIGNED AFTER user_id;
ALTER TABLE notifications ADD COLUMN IF NOT EXISTS recipient_id INT UNSIGNED AFTER sender_id;
ALTER TABLE notifications ADD COLUMN IF NOT EXISTS priority VARCHAR(255) DEFAULT 'medium' AFTER channel;
ALTER TABLE notifications ADD INDEX IF NOT EXISTS idx_notifications_recipient (recipient_id);
ALTER TABLE notifications ADD INDEX IF NOT EXISTS idx_notifications_sender (sender_id);

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
