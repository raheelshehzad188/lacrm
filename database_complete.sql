-- =====================================================
-- LÀ CRM - Complete Database Schema
-- Clinic + Course Lead Management CRM System
-- =====================================================

-- Drop existing tables if needed (uncomment if needed)
-- SET FOREIGN_KEY_CHECKS = 0;
-- DROP TABLE IF EXISTS `course_enrollments`;
-- DROP TABLE IF EXISTS `leads`;
-- DROP TABLE IF EXISTS `user_activity_log`;
-- DROP TABLE IF EXISTS `role_permissions`;
-- DROP TABLE IF EXISTS `users`;
-- DROP TABLE IF EXISTS `roles`;
-- DROP TABLE IF EXISTS `companies`;
-- DROP TABLE IF EXISTS `contacts`;
-- SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- 1. ROLES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default roles
INSERT INTO `roles` (`id`, `role_name`, `description`) VALUES
(1, 'Admin', 'Full system access, can manage all users and settings'),
(2, 'Sales Manager', 'Can view all sales team leads, assign leads, view reports'),
(3, 'Sales Person', 'Can only view and update assigned leads'),
(4, 'Doctor', 'Can view assigned patients and course enrollments');

-- =====================================================
-- 2. USERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '1=active, 0=inactive',
  `last_login` timestamp NULL DEFAULT NULL,
  `password_updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `status` (`status`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
-- Password hash generated using: password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO `users` (`name`, `email`, `password`, `role_id`, `status`, `password_updated_at`) VALUES
('Admin User', 'admin@crm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NOW());

-- Insert sample users for testing
INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role_id`, `status`, `password_updated_at`) VALUES
('Sales Manager', 'manager@crm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 2, 1, NOW()),
('Sales Person 1', 'sales1@crm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543211', 3, 1, NOW()),
('Sales Person 2', 'sales2@crm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543212', 3, 1, NOW()),
('Dr. John Doe', 'doctor@crm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543213', 4, 1, NOW());

-- =====================================================
-- 3. ROLE PERMISSIONS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `module_name` varchar(50) NOT NULL,
  `can_view` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  `can_assign` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_module` (`role_id`, `module_name`),
  KEY `role_id` (`role_id`),
  KEY `module_name` (`module_name`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default permissions for Admin (Full Access)
INSERT INTO `role_permissions` (`role_id`, `module_name`, `can_view`, `can_edit`, `can_delete`, `can_assign`) VALUES
(1, 'dashboard', 1, 1, 1, 1),
(1, 'users', 1, 1, 1, 1),
(1, 'leads', 1, 1, 1, 1),
(1, 'companies', 1, 1, 1, 1),
(1, 'contacts', 1, 1, 1, 1),
(1, 'reports', 1, 1, 1, 1),
(1, 'settings', 1, 1, 1, 1),
(1, 'patients', 1, 1, 1, 1),
(1, 'courses', 1, 1, 1, 1);

-- Insert default permissions for Sales Manager
INSERT INTO `role_permissions` (`role_id`, `module_name`, `can_view`, `can_edit`, `can_delete`, `can_assign`) VALUES
(2, 'dashboard', 1, 0, 0, 0),
(2, 'leads', 1, 1, 0, 1),
(2, 'companies', 1, 1, 0, 0),
(2, 'contacts', 1, 1, 0, 0),
(2, 'reports', 1, 0, 0, 0);

-- Insert default permissions for Sales Person
INSERT INTO `role_permissions` (`role_id`, `module_name`, `can_view`, `can_edit`, `can_delete`, `can_assign`) VALUES
(3, 'dashboard', 1, 0, 0, 0),
(3, 'leads', 1, 1, 0, 0),
(3, 'companies', 1, 1, 0, 0),
(3, 'contacts', 1, 1, 0, 0);

-- Insert default permissions for Doctor
INSERT INTO `role_permissions` (`role_id`, `module_name`, `can_view`, `can_edit`, `can_delete`, `can_assign`) VALUES
(4, 'dashboard', 1, 0, 0, 0),
(4, 'patients', 1, 1, 0, 0),
(4, 'courses', 1, 1, 0, 0);

-- =====================================================
-- 4. USER ACTIVITY LOG TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `user_activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `activity` varchar(50) NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL,
  `details` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `activity` (`activity`),
  KEY `timestamp` (`timestamp`),
  CONSTRAINT `user_activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 5. COMPANIES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(200) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '1=active, 0=inactive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `status` (`status`),
  CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 6. CONTACTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '1=active, 0=inactive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `created_by` (`created_by`),
  KEY `status` (`status`),
  CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  CONSTRAINT `contacts_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 7. LEADS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL COMMENT 'Facebook Ads, Instagram Ads, Meta Ads, WhatsApp Bot, Manual Entry',
  `stage` varchar(50) DEFAULT 'New' COMMENT 'New, Contacted, Interested, Follow-Up, Converted, Lost',
  `assigned_to` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `follow_up_date` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '1=active, 0=inactive',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `assigned_to` (`assigned_to`),
  KEY `company_id` (`company_id`),
  KEY `contact_id` (`contact_id`),
  KEY `stage` (`stage`),
  KEY `source` (`source`),
  KEY `follow_up_date` (`follow_up_date`),
  KEY `status` (`status`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `leads_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_ibfk_3` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample leads for testing
INSERT INTO `leads` (`name`, `email`, `phone`, `source`, `stage`, `assigned_to`, `follow_up_date`, `notes`, `status`, `created_by`) VALUES
('John Smith', 'john.smith@example.com', '9876543210', 'Facebook Ads', 'New', 3, DATE_ADD(NOW(), INTERVAL 2 DAY), 'Interested in course', 1, 1),
('Sarah Johnson', 'sarah.j@example.com', '9876543211', 'Instagram Ads', 'Contacted', 3, DATE_ADD(NOW(), INTERVAL 1 DAY), 'Follow up required', 1, 1),
('Mike Wilson', 'mike.w@example.com', '9876543212', 'WhatsApp Bot', 'Interested', 4, DATE_ADD(NOW(), INTERVAL 3 DAY), 'Course inquiry', 1, 1),
('Emma Davis', 'emma.d@example.com', '9876543213', 'Meta Ads', 'Follow-Up', 3, DATE_ADD(NOW(), INTERVAL 1 HOUR), 'Urgent follow up', 1, 1),
('David Brown', 'david.b@example.com', '9876543214', 'Manual Entry', 'Converted', 3, NULL, 'Successfully converted', 1, 1),
('Lisa Anderson', 'lisa.a@example.com', '9876543215', 'Facebook Ads', 'Lost', 4, NULL, 'Not interested', 1, 1);

-- =====================================================
-- 8. COURSE ENROLLMENTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `course_enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_name` varchar(100) NOT NULL,
  `student_email` varchar(100) DEFAULT NULL,
  `student_phone` varchar(20) DEFAULT NULL,
  `course_name` varchar(200) DEFAULT NULL,
  `type` varchar(20) DEFAULT 'course' COMMENT 'course or clinic',
  `instructor_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `status` varchar(20) DEFAULT 'pending' COMMENT 'pending, completed, cancelled',
  `enrollment_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `instructor_id` (`instructor_id`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `enrollment_date` (`enrollment_date`),
  CONSTRAINT `course_enrollments_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample course enrollments for testing
INSERT INTO `course_enrollments` (`student_name`, `student_email`, `student_phone`, `course_name`, `type`, `instructor_id`, `amount`, `status`, `enrollment_date`) VALUES
('Alice Cooper', 'alice.c@example.com', '9876543220', 'Advanced Course', 'course', 5, 5000.00, 'completed', DATE_SUB(NOW(), INTERVAL 10 DAY)),
('Bob Miller', 'bob.m@example.com', '9876543221', 'Basic Course', 'course', 5, 3000.00, 'completed', DATE_SUB(NOW(), INTERVAL 5 DAY)),
('Carol White', 'carol.w@example.com', '9876543222', 'Clinic Visit', 'clinic', 5, 2000.00, 'completed', DATE_SUB(NOW(), INTERVAL 3 DAY)),
('Daniel Green', 'daniel.g@example.com', '9876543223', 'Premium Course', 'course', 5, 8000.00, 'pending', CURDATE());

-- =====================================================
-- END OF SCHEMA
-- =====================================================

-- Show all tables created
SHOW TABLES;

-- Show table counts
SELECT 'roles' as table_name, COUNT(*) as count FROM roles
UNION ALL
SELECT 'users', COUNT(*) FROM users
UNION ALL
SELECT 'role_permissions', COUNT(*) FROM role_permissions
UNION ALL
SELECT 'user_activity_log', COUNT(*) FROM user_activity_log
UNION ALL
SELECT 'companies', COUNT(*) FROM companies
UNION ALL
SELECT 'contacts', COUNT(*) FROM contacts
UNION ALL
SELECT 'leads', COUNT(*) FROM leads
UNION ALL
SELECT 'course_enrollments', COUNT(*) FROM course_enrollments;

