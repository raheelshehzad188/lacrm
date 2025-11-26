# LÀ CRM - Database Setup Guide

## Database Tables Overview

Complete database schema with all required tables for the Clinic + Course Lead Management CRM system.

### 📊 Database Tables

1. **roles** - User roles (Admin, Sales Manager, Sales Person, Doctor)
2. **users** - System users with authentication
3. **role_permissions** - Module permissions for each role
4. **user_activity_log** - Activity tracking and audit log
5. **companies** - Company/Organization records
6. **contacts** - Contact persons linked to companies
7. **leads** - Lead management with stages and sources
8. **course_enrollments** - Course enrollments and clinic visits

## 🚀 Installation Steps

### Step 1: Create Database

```sql
CREATE DATABASE IF NOT EXISTS lacrm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lacrm;
```

### Step 2: Run SQL File

Import the complete database schema:

```bash
# Via MySQL command line
mysql -u root -p lacrm < database_complete.sql

# Or via phpMyAdmin
# 1. Open phpMyAdmin
# 2. Select 'lacrm' database
# 3. Go to 'Import' tab
# 4. Choose 'database_complete.sql' file
# 5. Click 'Go'
```

### Step 3: Verify Installation

Check if all tables are created:

```sql
SHOW TABLES;
```

You should see 8 tables:
- roles
- users
- role_permissions
- user_activity_log
- companies
- contacts
- leads
- course_enrollments

## 🔐 Default Login Credentials

After installation, you can login with:

**Admin Account:**
- Email: `admin@crm.com`
- Password: `admin123`

**Other Test Accounts:**
- Sales Manager: `manager@crm.com` / `admin123`
- Sales Person 1: `sales1@crm.com` / `admin123`
- Sales Person 2: `sales2@crm.com` / `admin123`
- Doctor: `doctor@crm.com` / `admin123`

## 📋 Table Structure Details

### 1. roles
Stores user roles with descriptions.

### 2. users
- User authentication and profile information
- Links to roles table
- Tracks last login and password updates

### 3. role_permissions
- Defines module access permissions
- Controls view, edit, delete, assign permissions per role

### 4. user_activity_log
- Tracks all user activities
- Records login, profile updates, password changes, etc.
- Includes IP address for security

### 5. companies
- Company/Organization records
- Stores business information
- Links to contacts and leads

### 6. contacts
- Individual contact persons
- Can be linked to companies
- Stores personal and professional details

### 7. leads
- Lead management system
- Tracks lead sources (Facebook, Instagram, Meta, WhatsApp, Manual)
- Pipeline stages (New, Contacted, Interested, Follow-Up, Converted, Lost)
- Follow-up date tracking
- Assignment to sales persons

### 8. course_enrollments
- Course enrollments and clinic visits
- Tracks revenue (courses only, excludes clinic)
- Links to instructor/doctor
- Status tracking (pending, completed, cancelled)

## 🔄 Sample Data

The schema includes sample data for testing:
- 4 roles
- 5 users (1 admin, 1 manager, 2 sales persons, 1 doctor)
- 6 sample leads
- 4 sample course enrollments
- Complete permission matrix

## ⚙️ Database Configuration

Update `application/config/database.php`:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',  // Your MySQL password
    'database' => 'lacrm',
    'dbdriver' => 'mysqli',
    // ... other settings
);
```

## ✅ Verification Queries

Run these queries to verify data:

```sql
-- Check roles
SELECT * FROM roles;

-- Check users
SELECT id, name, email, role_id, status FROM users;

-- Check permissions
SELECT r.role_name, rp.module_name, rp.can_view, rp.can_edit, rp.can_delete, rp.can_assign 
FROM role_permissions rp 
JOIN roles r ON r.id = rp.role_id;

-- Check leads
SELECT l.*, u.name as assigned_to_name 
FROM leads l 
LEFT JOIN users u ON u.id = l.assigned_to;

-- Check course enrollments
SELECT ce.*, u.name as instructor_name 
FROM course_enrollments ce 
LEFT JOIN users u ON u.id = ce.instructor_id;
```

## 🛠️ Troubleshooting

### Foreign Key Errors
If you get foreign key errors, make sure tables are created in order:
1. roles
2. users
3. role_permissions
4. user_activity_log
5. companies
6. contacts
7. leads
8. course_enrollments

### Character Set Issues
Ensure database uses utf8mb4:
```sql
ALTER DATABASE lacrm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Reset Database
To reset and recreate all tables:
```sql
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS course_enrollments, leads, contacts, companies, user_activity_log, role_permissions, users, roles;
SET FOREIGN_KEY_CHECKS = 1;
```
Then run `database_complete.sql` again.

## 📝 Notes

- All timestamps use CURRENT_TIMESTAMP
- Foreign keys use ON DELETE SET NULL or CASCADE appropriately
- Indexes are created on frequently queried columns
- Sample data is included for testing purposes

