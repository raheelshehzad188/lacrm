# LÀ CRM - Database Tables Reference

## Complete List of Database Tables

### 1. **roles**
User roles in the system.

| Column | Type | Description |
|--------|------|-------------|
| id | int(11) | Primary key |
| role_name | varchar(50) | Role name (Admin, Sales Manager, Sales Person, Doctor) |
| description | text | Role description |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Default Data:**
- Admin (id: 1)
- Sales Manager (id: 2)
- Sales Person (id: 3)
- Doctor (id: 4)

---

### 2. **users**
System users with authentication.

| Column | Type | Description |
|--------|------|-------------|
| id | int(11) | Primary key |
| name | varchar(100) | User full name |
| email | varchar(100) | Email (unique) |
| password | varchar(255) | Hashed password |
| phone | varchar(20) | Phone number |
| bio | text | User biography |
| profile_photo | varchar(255) | Profile photo filename |
| role_id | int(11) | Foreign key to roles |
| status | tinyint(1) | 1=active, 0=inactive |
| last_login | timestamp | Last login time |
| password_updated_at | timestamp | Password change time |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:** email (unique), role_id, status

---

### 3. **role_permissions**
Module permissions for each role.

| Column | Type | Description |
|--------|------|-------------|
| id | int(11) | Primary key |
| role_id | int(11) | Foreign key to roles |
| module_name | varchar(50) | Module name (dashboard, leads, etc.) |
| can_view | tinyint(1) | Can view permission |
| can_edit | tinyint(1) | Can edit permission |
| can_delete | tinyint(1) | Can delete permission |
| can_assign | tinyint(1) | Can assign permission |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:** role_id, module_name, unique(role_id, module_name)

**Modules:**
- dashboard
- users
- leads
- companies
- contacts
- reports
- settings
- patients
- courses

---

### 4. **user_activity_log**
Activity tracking and audit log.

| Column | Type | Description |
|--------|------|-------------|
| id | int(11) | Primary key |
| user_id | int(11) | Foreign key to users |
| activity | varchar(50) | Activity type (login, logout, profile_update, etc.) |
| timestamp | timestamp | Activity timestamp |
| ip_address | varchar(45) | User IP address |
| details | text | Additional details |

**Indexes:** user_id, activity, timestamp

**Activity Types:**
- login
- logout
- profile_update
- password_change
- lead_created
- lead_assigned
- lead_stage_updated

---

### 5. **companies**
Company/Organization records.

| Column | Type | Description |
|--------|------|-------------|
| id | int(11) | Primary key |
| company_name | varchar(200) | Company name |
| email | varchar(100) | Company email |
| phone | varchar(20) | Company phone |
| website | varchar(255) | Company website |
| address | text | Street address |
| city | varchar(100) | City |
| state | varchar(100) | State |
| country | varchar(100) | Country |
| zip_code | varchar(20) | ZIP/Postal code |
| industry | varchar(100) | Industry type |
| description | text | Company description |
| status | tinyint(1) | 1=active, 0=inactive |
| created_by | int(11) | Foreign key to users |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:** created_by, status

---

### 6. **contacts**
Contact persons linked to companies.

| Column | Type | Description |
|--------|------|-------------|
| id | int(11) | Primary key |
| first_name | varchar(100) | First name |
| last_name | varchar(100) | Last name |
| email | varchar(100) | Email |
| phone | varchar(20) | Phone |
| mobile | varchar(20) | Mobile |
| job_title | varchar(100) | Job title |
| company_id | int(11) | Foreign key to companies |
| address | text | Street address |
| city | varchar(100) | City |
| state | varchar(100) | State |
| country | varchar(100) | Country |
| zip_code | varchar(20) | ZIP/Postal code |
| notes | text | Notes |
| status | tinyint(1) | 1=active, 0=inactive |
| created_by | int(11) | Foreign key to users |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:** company_id, created_by, status

---

### 7. **leads**
Lead management with stages and sources.

| Column | Type | Description |
|--------|------|-------------|
| id | int(11) | Primary key |
| name | varchar(100) | Lead name |
| email | varchar(100) | Lead email |
| phone | varchar(20) | Lead phone |
| source | varchar(50) | Lead source |
| stage | varchar(50) | Pipeline stage |
| assigned_to | int(11) | Foreign key to users (sales person) |
| company_id | int(11) | Foreign key to companies |
| contact_id | int(11) | Foreign key to contacts |
| follow_up_date | datetime | Follow-up date/time |
| notes | text | Lead notes |
| status | tinyint(1) | 1=active, 0=inactive |
| created_by | int(11) | Foreign key to users |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:** assigned_to, company_id, contact_id, stage, source, follow_up_date, status, created_by

**Lead Sources:**
- Facebook Ads
- Instagram Ads
- Meta Ads
- WhatsApp Bot
- Manual Entry

**Pipeline Stages:**
- New
- Contacted
- Interested
- Follow-Up
- Converted
- Lost

---

### 8. **course_enrollments**
Course enrollments and clinic visits.

| Column | Type | Description |
|--------|------|-------------|
| id | int(11) | Primary key |
| student_name | varchar(100) | Student/Patient name |
| student_email | varchar(100) | Student email |
| student_phone | varchar(20) | Student phone |
| course_name | varchar(200) | Course name |
| type | varchar(20) | 'course' or 'clinic' |
| instructor_id | int(11) | Foreign key to users (doctor/instructor) |
| amount | decimal(10,2) | Enrollment amount |
| status | varchar(20) | Enrollment status |
| enrollment_date | date | Enrollment date |
| completion_date | date | Completion date |
| notes | text | Notes |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

**Indexes:** instructor_id, type, status, enrollment_date

**Types:**
- course (for course enrollments)
- clinic (for clinic visits)

**Status:**
- pending
- completed
- cancelled

---

## Relationships

```
roles (1) ──< (many) users
users (1) ──< (many) role_permissions
users (1) ──< (many) user_activity_log
users (1) ──< (many) companies (created_by)
users (1) ──< (many) contacts (created_by)
users (1) ──< (many) leads (assigned_to, created_by)
users (1) ──< (many) course_enrollments (instructor_id)
companies (1) ──< (many) contacts
companies (1) ──< (many) leads
contacts (1) ──< (many) leads
```

---

## Quick Reference Queries

### Count all records
```sql
SELECT 
    'roles' as table_name, COUNT(*) as count FROM roles
UNION ALL SELECT 'users', COUNT(*) FROM users
UNION ALL SELECT 'role_permissions', COUNT(*) FROM role_permissions
UNION ALL SELECT 'user_activity_log', COUNT(*) FROM user_activity_log
UNION ALL SELECT 'companies', COUNT(*) FROM companies
UNION ALL SELECT 'contacts', COUNT(*) FROM contacts
UNION ALL SELECT 'leads', COUNT(*) FROM leads
UNION ALL SELECT 'course_enrollments', COUNT(*) FROM course_enrollments;
```

### Check user permissions
```sql
SELECT r.role_name, rp.module_name, 
    rp.can_view, rp.can_edit, rp.can_delete, rp.can_assign
FROM role_permissions rp
JOIN roles r ON r.id = rp.role_id
ORDER BY r.role_name, rp.module_name;
```

### View leads with assignments
```sql
SELECT l.*, u.name as assigned_to_name, u.email as assigned_to_email
FROM leads l
LEFT JOIN users u ON u.id = l.assigned_to
ORDER BY l.created_at DESC;
```

### Course revenue summary
```sql
SELECT 
    type,
    status,
    COUNT(*) as count,
    SUM(amount) as total_revenue
FROM course_enrollments
WHERE type = 'course' AND status = 'completed'
GROUP BY type, status;
```

