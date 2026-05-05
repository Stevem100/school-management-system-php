-- =============================================
-- School Management System - Database Migration
-- =============================================
-- Run this SQL against your MySQL database.
-- The prefix `uydirsqz_Shule` is applied by the app config.
-- =============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ─── Core Tables ──────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(200) NOT NULL UNIQUE,
    `phone` VARCHAR(30) DEFAULT '',
    `password` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(500) DEFAULT '',
    `role_id` INT UNSIGNED DEFAULT NULL,
    `school_id` INT UNSIGNED DEFAULT NULL,
    `branch_id` INT UNSIGNED DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT 'active',
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `remember_token` VARCHAR(100) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_role` (`role_id`),
    INDEX `idx_users_school` (`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `display_name` VARCHAR(100) NOT NULL,
    `description` VARCHAR(255) DEFAULT '',
    `level` INT DEFAULT 0,
    `is_system` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `permissions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `display_name` VARCHAR(150) NOT NULL,
    `module` VARCHAR(100) DEFAULT 'general',
    `description` VARCHAR(255) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `role_permissions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `role_id` INT UNSIGNED NOT NULL,
    `permission_id` INT UNSIGNED NOT NULL,
    UNIQUE KEY `unq_role_perm` (`role_id`, `permission_id`),
    INDEX `idx_rp_role` (`role_id`),
    INDEX `idx_rp_perm` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── School Management ────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `schools` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NOT NULL,
    `code` VARCHAR(50) DEFAULT '',
    `logo` VARCHAR(500) DEFAULT '',
    `address` TEXT DEFAULT NULL,
    `city` VARCHAR(100) DEFAULT '',
    `state` VARCHAR(100) DEFAULT '',
    `country` VARCHAR(100) DEFAULT '',
    `postal_code` VARCHAR(20) DEFAULT '',
    `phone` VARCHAR(30) DEFAULT '',
    `email` VARCHAR(200) DEFAULT '',
    `website` VARCHAR(255) DEFAULT '',
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `branches` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `school_id` INT UNSIGNED DEFAULT NULL,
    `name` VARCHAR(200) NOT NULL,
    `code` VARCHAR(50) DEFAULT '',
    `address` TEXT DEFAULT NULL,
    `city` VARCHAR(100) DEFAULT '',
    `state` VARCHAR(100) DEFAULT '',
    `phone` VARCHAR(30) DEFAULT '',
    `email` VARCHAR(200) DEFAULT '',
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_branch_school` (`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Module Management ────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `modules` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `display_name` VARCHAR(150) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `icon` VARCHAR(50) DEFAULT '',
    `route` VARCHAR(255) DEFAULT '',
    `version` VARCHAR(20) DEFAULT '1.0.0',
    `sort_order` INT DEFAULT 0,
    `is_core` TINYINT(1) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Academic ─────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `classes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `section` VARCHAR(20) DEFAULT '',
    `teacher_id` INT UNSIGNED DEFAULT NULL,
    `branch_id` INT UNSIGNED DEFAULT NULL,
    `capacity` INT DEFAULT 40,
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `subjects` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `code` VARCHAR(50) DEFAULT '',
    `type` VARCHAR(50) DEFAULT 'core',
    `description` TEXT DEFAULT NULL,
    `class_id` INT UNSIGNED DEFAULT NULL,
    `teacher_id` INT UNSIGNED DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_subject_class` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `students` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `admission_no` VARCHAR(50) DEFAULT '',
    `roll_no` VARCHAR(50) DEFAULT '',
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(200) DEFAULT '',
    `phone` VARCHAR(30) DEFAULT '',
    `date_of_birth` DATE DEFAULT NULL,
    `gender` VARCHAR(20) DEFAULT '',
    `address` TEXT DEFAULT NULL,
    `blood_group` VARCHAR(10) DEFAULT '',
    `class_id` INT UNSIGNED DEFAULT NULL,
    `section` VARCHAR(20) DEFAULT '',
    `parent_name` VARCHAR(200) DEFAULT '',
    `parent_phone` VARCHAR(30) DEFAULT '',
    `parent_email` VARCHAR(200) DEFAULT '',
    `guardian_name` VARCHAR(200) DEFAULT '',
    `guardian_phone` VARCHAR(30) DEFAULT '',
    `photo` VARCHAR(500) DEFAULT '',
    `status` VARCHAR(50) DEFAULT 'active',
    `academic_year` VARCHAR(20) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_student_class` (`class_id`),
    INDEX `idx_student_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `exams` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `type` VARCHAR(50) DEFAULT 'term',
    `start_date` DATE DEFAULT NULL,
    `end_date` DATE DEFAULT NULL,
    `class_id` INT UNSIGNED DEFAULT NULL,
    `subject_id` INT UNSIGNED DEFAULT NULL,
    `total_marks` INT DEFAULT 100,
    `passing_marks` INT DEFAULT 40,
    `academic_year` VARCHAR(20) DEFAULT '',
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `results` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT UNSIGNED NOT NULL,
    `exam_id` INT UNSIGNED NOT NULL,
    `class_id` INT UNSIGNED DEFAULT NULL,
    `subject_id` INT UNSIGNED DEFAULT NULL,
    `marks_obtained` DECIMAL(6,2) DEFAULT 0,
    `total_marks` DECIMAL(6,2) DEFAULT 100,
    `grade` VARCHAR(10) DEFAULT '',
    `remarks` TEXT DEFAULT NULL,
    `academic_year` VARCHAR(20) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_result_student` (`student_id`),
    INDEX `idx_result_exam` (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `attendance` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT UNSIGNED NOT NULL,
    `class_id` INT UNSIGNED DEFAULT NULL,
    `date` DATE NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'present',
    `remarks` VARCHAR(255) DEFAULT '',
    `marked_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unq_attendance` (`student_id`, `date`),
    INDEX `idx_attendance_date` (`date`),
    INDEX `idx_attendance_class` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `timetable` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `class_id` INT UNSIGNED NOT NULL,
    `subject_id` INT UNSIGNED NOT NULL,
    `teacher_id` INT UNSIGNED DEFAULT NULL,
    `day` VARCHAR(20) NOT NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `room` VARCHAR(50) DEFAULT '',
    `academic_year` VARCHAR(20) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_timetable_class` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `skills` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT '',
    `teacher_id` INT UNSIGNED DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Finance ───────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `fees` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
    `class_id` INT UNSIGNED DEFAULT NULL,
    `type` VARCHAR(50) DEFAULT 'tuition',
    `frequency` VARCHAR(50) DEFAULT 'monthly',
    `due_date` INT DEFAULT 5,
    `late_fee` DECIMAL(10,2) DEFAULT 0,
    `academic_year` VARCHAR(20) DEFAULT '',
    `description` TEXT DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `payments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT UNSIGNED NOT NULL,
    `fee_id` INT UNSIGNED DEFAULT NULL,
    `amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
    `payment_method` VARCHAR(50) DEFAULT 'cash',
    `transaction_id` VARCHAR(100) DEFAULT '',
    `receipt_no` VARCHAR(50) DEFAULT '',
    `payment_date` DATE DEFAULT NULL,
    `academic_year` VARCHAR(20) DEFAULT '',
    `status` VARCHAR(50) DEFAULT 'completed',
    `remarks` TEXT DEFAULT NULL,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_payment_student` (`student_id`),
    INDEX `idx_payment_fee` (`fee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Library ───────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `books` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `author` VARCHAR(150) DEFAULT '',
    `isbn` VARCHAR(20) DEFAULT '',
    `publisher` VARCHAR(150) DEFAULT '',
    `category` VARCHAR(100) DEFAULT '',
    `total_copies` INT DEFAULT 1,
    `available_copies` INT DEFAULT 1,
    `shelf_no` VARCHAR(50) DEFAULT '',
    `description` TEXT DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT 'available',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `book_issues` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `book_id` INT UNSIGNED NOT NULL,
    `student_id` INT UNSIGNED NOT NULL,
    `issued_by` INT UNSIGNED DEFAULT NULL,
    `issue_date` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `return_date` DATE DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT 'issued',
    `remarks` VARCHAR(255) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_issue_book` (`book_id`),
    INDEX `idx_issue_student` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Transport ─────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `transport_routes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `start_point` VARCHAR(200) DEFAULT '',
    `end_point` VARCHAR(200) DEFAULT '',
    `distance` DECIMAL(8,2) DEFAULT 0,
    `fare` DECIMAL(8,2) DEFAULT 0,
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `transport_vehicles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `route_id` INT UNSIGNED DEFAULT NULL,
    `vehicle_no` VARCHAR(50) NOT NULL,
    `vehicle_type` VARCHAR(50) DEFAULT 'bus',
    `capacity` INT DEFAULT 50,
    `driver_name` VARCHAR(100) DEFAULT '',
    `driver_phone` VARCHAR(30) DEFAULT '',
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_vehicle_route` (`route_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `transport_assignments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT UNSIGNED NOT NULL,
    `vehicle_id` INT UNSIGNED NOT NULL,
    `route_id` INT UNSIGNED DEFAULT NULL,
    `pickup_point` VARCHAR(200) DEFAULT '',
    `academic_year` VARCHAR(20) DEFAULT '',
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_transport_student` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Hostel ────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `hostel_rooms` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `floor` VARCHAR(50) DEFAULT '',
    `room_type` VARCHAR(50) DEFAULT 'shared',
    `capacity` INT DEFAULT 4,
    `occupied` INT DEFAULT 0,
    `fee_per_month` DECIMAL(10,2) DEFAULT 0,
    `amenities` TEXT DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT 'available',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hostel_allocations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT UNSIGNED NOT NULL,
    `room_id` INT UNSIGNED NOT NULL,
    `bed_no` VARCHAR(20) DEFAULT '',
    `check_in_date` DATE DEFAULT NULL,
    `check_out_date` DATE DEFAULT NULL,
    `academic_year` VARCHAR(20) DEFAULT '',
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_hostel_student` (`student_id`),
    INDEX `idx_hostel_room` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── LMS ───────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `courses` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `class_id` INT UNSIGNED DEFAULT NULL,
    `teacher_id` INT UNSIGNED DEFAULT NULL,
    `thumbnail` VARCHAR(500) DEFAULT '',
    `status` VARCHAR(50) DEFAULT 'active',
    `start_date` DATE DEFAULT NULL,
    `end_date` DATE DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_assignments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `due_date` DATE DEFAULT NULL,
    `total_marks` INT DEFAULT 100,
    `status` VARCHAR(50) DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_lms_assignment_course` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lms_submissions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `assignment_id` INT UNSIGNED NOT NULL,
    `student_id` INT UNSIGNED NOT NULL,
    `content` TEXT DEFAULT NULL,
    `file_path` VARCHAR(500) DEFAULT '',
    `marks_obtained` DECIMAL(6,2) DEFAULT NULL,
    `feedback` TEXT DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT 'submitted',
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `graded_at` TIMESTAMP NULL DEFAULT NULL,
    INDEX `idx_submission_assignment` (`assignment_id`),
    INDEX `idx_submission_student` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Communication ─────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `sender_id` INT UNSIGNED NOT NULL,
    `recipient_id` INT UNSIGNED NOT NULL,
    `subject` VARCHAR(255) DEFAULT '',
    `content` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `parent_id` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_message_sender` (`sender_id`),
    INDEX `idx_message_recipient` (`recipient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notices` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `notice_type` VARCHAR(50) DEFAULT 'general',
    `target_audience` VARCHAR(100) DEFAULT 'all',
    `priority` VARCHAR(20) DEFAULT 'normal',
    `published_at` TIMESTAMP NULL DEFAULT NULL,
    `expires_at` TIMESTAMP NULL DEFAULT NULL,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT 'published',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Website Module ────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `website_settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `site_name` VARCHAR(200) DEFAULT 'School',
    `site_description` TEXT DEFAULT NULL,
    `site_url` VARCHAR(255) DEFAULT '',
    `site_logo` VARCHAR(500) DEFAULT '',
    `favicon` VARCHAR(500) DEFAULT '',
    `primary_color` VARCHAR(20) DEFAULT '#059669',
    `secondary_color` VARCHAR(20) DEFAULT '#6b7280',
    `footer_text` TEXT DEFAULT NULL,
    `meta_keywords` VARCHAR(500) DEFAULT '',
    `meta_description` VARCHAR(500) DEFAULT '',
    `google_analytics_id` VARCHAR(50) DEFAULT '',
    `social_facebook` VARCHAR(255) DEFAULT '',
    `social_twitter` VARCHAR(255) DEFAULT '',
    `social_instagram` VARCHAR(255) DEFAULT '',
    `social_linkedin` VARCHAR(255) DEFAULT '',
    `social_youtube` VARCHAR(255) DEFAULT '',
    `contact_email` VARCHAR(200) DEFAULT '',
    `contact_phone` VARCHAR(30) DEFAULT '',
    `contact_address` TEXT DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_pages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `content` LONGTEXT DEFAULT NULL,
    `meta_title` VARCHAR(255) DEFAULT '',
    `meta_description` VARCHAR(500) DEFAULT '',
    `meta_keywords` VARCHAR(500) DEFAULT '',
    `status` VARCHAR(50) DEFAULT 'draft',
    `sort_order` INT DEFAULT 0,
    `author_id` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_page_slug` (`slug`),
    INDEX `idx_page_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_menu_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `label` VARCHAR(100) NOT NULL,
    `url` VARCHAR(500) DEFAULT '',
    `page_id` INT UNSIGNED DEFAULT NULL,
    `parent_id` INT UNSIGNED DEFAULT NULL,
    `target` VARCHAR(20) DEFAULT '_self',
    `icon_class` VARCHAR(100) DEFAULT '',
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_menu_parent` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_media` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` BIGINT DEFAULT 0,
    `file_type` VARCHAR(50) DEFAULT 'image',
    `mime_type` VARCHAR(100) DEFAULT '',
    `dimensions` VARCHAR(50) DEFAULT '',
    `alt_text` VARCHAR(255) DEFAULT '',
    `uploaded_by` INT UNSIGNED DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_media_type` (`file_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Admission Module ──────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `admission_settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `academic_year` VARCHAR(20) DEFAULT '',
    `start_date` DATE DEFAULT NULL,
    `end_date` DATE DEFAULT NULL,
    `application_fee` DECIMAL(10,2) DEFAULT 0,
    `instructions` TEXT DEFAULT NULL,
    `max_applications` INT DEFAULT 0,
    `classes_offered` TEXT DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_by` INT UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admission_fields` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `label` VARCHAR(200) NOT NULL,
    `field_type` VARCHAR(50) NOT NULL DEFAULT 'text',
    `section` VARCHAR(100) DEFAULT 'General',
    `placeholder` VARCHAR(255) DEFAULT '',
    `help_text` VARCHAR(500) DEFAULT '',
    `options` TEXT DEFAULT NULL,
    `default_value` VARCHAR(255) DEFAULT '',
    `is_required` TINYINT(1) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `max_size` INT DEFAULT 255,
    `validation_rules` VARCHAR(255) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admission_applications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `application_no` VARCHAR(50) NOT NULL UNIQUE,
    `applicant_name` VARCHAR(200) NOT NULL,
    `applicant_email` VARCHAR(200) DEFAULT '',
    `applicant_phone` VARCHAR(30) DEFAULT '',
    `class_id` INT UNSIGNED DEFAULT NULL,
    `academic_year` VARCHAR(20) DEFAULT '',
    `status` VARCHAR(50) DEFAULT 'pending',
    `review_notes` TEXT DEFAULT NULL,
    `reviewed_by` INT UNSIGNED DEFAULT NULL,
    `reviewed_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_admission_email` (`applicant_email`),
    INDEX `idx_admission_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admission_form_data` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `application_id` INT UNSIGNED NOT NULL,
    `field_id` INT UNSIGNED NOT NULL,
    `field_value` TEXT DEFAULT NULL,
    INDEX `idx_fd_application` (`application_id`),
    INDEX `idx_fd_field` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admission_attachments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `application_id` INT UNSIGNED NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` BIGINT DEFAULT 0,
    `mime_type` VARCHAR(100) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_attach_application` (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Seed Default Roles ────────────────────────────────────────

INSERT IGNORE INTO `roles` (`name`, `display_name`, `description`, `level`, `is_system`) VALUES
('SuperAdmin', 'Super Administrator', 'Full system access', 1, 1),
('SchoolAdmin', 'School Administrator', 'Manage school settings', 2, 1),
('BranchAdmin', 'Branch Administrator', 'Manage branch operations', 3, 1),
('Dean', 'Dean', 'Academic management', 4, 1),
('Teacher', 'Teacher', 'Teaching staff', 5, 1),
('Student', 'Student', 'Students', 6, 1),
('Parent', 'Parent', 'Parents/Guardians', 7, 1),
('Accountant', 'Accountant', 'Financial management', 8, 1),
('Librarian', 'Librarian', 'Library management', 9, 1),
('HostelManager', 'Hostel Manager', 'Hostel management', 10, 1),
('TransportManager', 'Transport Manager', 'Transport management', 11, 1);

-- ─── Seed Default SuperAdmin User (password: admin123) ─────────

INSERT IGNORE INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role_id`, `status`)
VALUES (1, 'Super', 'Admin', 'admin@school.com', '$2y$12$LJ3m4ys3dMJBqVh0YKBlRuUIBEBIT9oe5kQJw7hYjF8wXDKlYEqGK', 1, 'active');

SET FOREIGN_KEY_CHECKS = 1;
