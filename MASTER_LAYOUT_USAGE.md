# Master Layout Usage Guide

## Overview

The LÀ CRM system uses a theme-friendly modular layout system with a master layout file that ensures all UI layout code stays in views, making future theme changes easy without affecting system functionality.

## File Structure

```
application/
├── views/
│   └── layouts/
│       └── master.php          # Master layout (topbar, sidebar, footer)
├── modules/
│   └── [module_name]/
│       └── views/
│           └── content.php     # Module inner content only (no header/footer/sidebar)
└── controllers/
    └── [Controller].php        # Uses load_master_view() method
```

## Master Layout Components

The `master.php` layout includes:

### A. TOPBAR
- Logged-in user name
- Role badge (using role_id)
- Profile photo (or default avatar placeholder)
- Dropdown menu:
  - My Profile
  - Change Password
  - Logout
- Horizontal divider below topbar

### B. SIDEBAR MENU
Role-based menu items:

**Admin (role_id = 1):**
- Dashboard
- Leads
- Users
- Roles
- Reports
- Settings

**Sales Manager (role_id = 2):**
- Dashboard
- Leads
- Reports

**Sales Person (role_id = 3):**
- Dashboard
- My Leads

**Doctor (role_id = 4):**
- Dashboard
- My Patients
- My Courses

### C. MAIN CONTENT AREA
- Renders module output using `$module_view` variable
- Contains only module-specific content (no layout components)
- Theme replace-safe

### D. FOOTER
- "Developed for clinic + courses lead system"
- Dynamic year
- Minimal and responsive

## Usage in Controllers

### Using Base_Controller

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Base_Controller.php';

class YourController extends Base_Controller {

    public function index()
    {
        // Check module access
        $this->check_module_access('module_name');
        
        // Prepare your module data
        $module_data = array(
            'some_data' => 'value',
            'another_data' => 'value'
        );
        
        // Load master layout with module view
        $this->load_master_view(
            'module_view_path',  // Path to module view (only inner content)
            $module_data,         // Data for the module view
            array(                // Layout configuration (optional)
                'title' => 'Page Title - LÀ CRM',
                'body_class' => 'layout-mini',
                'show_preloader' => true,
                'extra_css' => array('path/to/custom.css'),
                'extra_js' => array('path/to/custom.js')
            )
        );
    }
}
```

### Using Base_Module_Controller (HMVC)

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Base_Module_Controller.php';

class YourModuleController extends Base_Module_Controller {

    public function index()
    {
        // Check permission
        $this->require_permission('module_name', 'can_view');
        
        // Prepare your module data
        $module_data = array(
            'some_data' => 'value'
        );
        
        // Load master layout with module view
        $this->load_master_view(
            'modules/yourmodule/views/content',  // Module view path
            $module_data,
            array('title' => 'Module Title - LÀ CRM')
        );
    }
}
```

## Module View Structure

Module views should contain **ONLY inner content** - no header, footer, sidebar, or layout wrappers.

### Example Module View

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Module view: application/views/dashboard_content.php
// OR: application/modules/dashboard/views/content.php

// Only inner content here - no <html>, <head>, <body>, header, footer, sidebar
?>

<div class="row">
    <div class="col-12">
        <h1>Dashboard Content</h1>
        <p>This is the module content only.</p>
    </div>
</div>
```

## Important Rules

1. **Module views must NOT contain:**
   - HTML structure tags (`<html>`, `<head>`, `<body>`)
   - Header/topbar code
   - Sidebar/navigation code
   - Footer code
   - Layout wrappers

2. **Module views should contain:**
   - Only the specific content for that module
   - Content-specific HTML/CSS/JS
   - Data presentation logic

3. **Master layout handles:**
   - All layout structure
   - Topbar with user info
   - Sidebar with role-based menu
   - Footer
   - Common CSS/JS includes

## Benefits

- **Theme-Friendly:** Change themes by modifying only `master.php`
- **Modular:** Each module returns only its content
- **Maintainable:** Layout code centralized in one place
- **Role-Based:** Sidebar menu automatically adjusts by role
- **Consistent:** All pages use the same layout structure

## Database Requirements

Ensure these tables exist:
- `roles` (id, role_name)
- `users` (id, name, email, password, phone, bio, profile_photo, role_id, status, last_login, password_updated_at, created_at, updated_at)
- `user_activity_log` (id, user_id, activity, timestamp, ip_address)

See `database_schema.sql` for complete schema.

