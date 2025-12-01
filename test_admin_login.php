<?php
/**
 * Test Admin Login Script
 * Run this to verify admin user exists and can login
 * http://localhost/lacrm/test_admin_login.php
 */

// Load CodeIgniter
require_once __DIR__ . '/index.php';

// Get CI instance
$CI =& get_instance();
$CI->load->database();
$CI->load->model('User_model');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Admin Login - LÀ CRM</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px 0; }
        .info { color: #0c5460; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 4px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table th, table td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        table th { background: #f8f9fa; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>Test Admin Login - LÀ CRM</h1>
    
    <?php
    try {
        // Check admin user
        $admin = $CI->User_model->get_by_email('admin@crm.com');
        
        if ($admin) {
            echo "<div class='success'>✓ Admin user found!</div>";
            echo "<div class='info'><strong>Admin Details:</strong><br>";
            echo "ID: <strong>{$admin->id}</strong><br>";
            echo "Name: <strong>{$admin->name}</strong><br>";
            echo "Email: <strong>{$admin->email}</strong><br>";
            echo "Role ID: <strong>{$admin->role_id}</strong><br>";
            echo "Status: <strong>" . ($admin->status == 1 ? 'Active' : 'Inactive') . "</strong><br>";
            echo "Last Login: <strong>" . ($admin->last_login ? $admin->last_login : 'Never') . "</strong></div>";
            
            // Test password verification
            $test_password = 'admin123';
            $password_valid = $CI->User_model->verify_password($test_password, $admin->password);
            
            if ($password_valid) {
                echo "<div class='success'>✓ Password verification works! Password 'admin123' is correct.</div>";
            } else {
                echo "<div class='error'>✗ Password verification failed. Password 'admin123' is incorrect.</div>";
                echo "<div class='info'>Current password hash: <code>" . substr($admin->password, 0, 30) . "...</code></div>";
            }
            
            // Check role
            $CI->load->model('Role_model');
            $role = $CI->Role_model->get_by_id($admin->role_id);
            
            if ($role) {
                echo "<div class='info'>Role: <strong>{$role->role_name}</strong></div>";
            }
            
            // Check permissions
            $permissions = $CI->Role_model->get_permissions($admin->role_id);
            echo "<div class='info'><strong>Admin Permissions:</strong><br>";
            echo "<table><tr><th>Module</th><th>View</th><th>Edit</th><th>Delete</th><th>Assign</th></tr>";
            foreach ($permissions as $perm) {
                echo "<tr>";
                echo "<td><strong>{$perm->module_name}</strong></td>";
                echo "<td>" . ($perm->can_view ? '✓' : '✗') . "</td>";
                echo "<td>" . ($perm->can_edit ? '✓' : '✗') . "</td>";
                echo "<td>" . ($perm->can_delete ? '✓' : '✗') . "</td>";
                echo "<td>" . ($perm->can_assign ? '✓' : '✗') . "</td>";
                echo "</tr>";
            }
            echo "</table></div>";
            
            echo "<div class='success'><strong>✅ Admin Access Verified!</strong><br><br>";
            echo "<strong>Login Credentials:</strong><br>";
            echo "Email: <code>admin@crm.com</code><br>";
            echo "Password: <code>admin123</code><br><br>";
            echo "<a href='auth/login' style='display:inline-block;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:4px;'>Go to Login Page →</a></div>";
            
        } else {
            echo "<div class='error'>✗ Admin user not found!</div>";
            echo "<div class='info'>Please run <code>setup_database.php</code> or <code>verify_admin_access.php</code> first.</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='error'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    ?>
    
    <hr>
    <p><small>⚠️ <strong>Security Note:</strong> Delete this file after testing!</small></p>
</body>
</html>

