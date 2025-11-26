-- LÀ CRM Database Schema
-- User Roles and Authentication System

-- Roles Table
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default roles
INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Sales Manager'),
(3, 'Sales Person'),
(4, 'Doctor');

-- Users Table
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: admin123)
-- Password hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO `users` (`name`, `email`, `password`, `role_id`, `status`, `password_updated_at`) VALUES
('Admin User', 'admin@crm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NOW());

-- Role Permissions Table
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
  KEY `role_id` (`role_id`),
  KEY `module_name` (`module_name`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- User Activity Log Table
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Leads Table
CREATE TABLE IF NOT EXISTS `leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL COMMENT 'Facebook Ads, Instagram Ads, Meta Ads, WhatsApp Bot, Manual Entry',
  `stage` varchar(50) DEFAULT 'New' COMMENT 'New, Contacted, Interested, Follow-Up, Converted, Lost',
  `assigned_to` int(11) DEFAULT NULL,
  `follow_up_date` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `assigned_to` (`assigned_to`),
  KEY `stage` (`stage`),
  KEY `source` (`source`),
  KEY `follow_up_date` (`follow_up_date`),
  CONSTRAINT `leads_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Course Enrollments Table
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `instructor_id` (`instructor_id`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  CONSTRAINT `course_enrollments_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


