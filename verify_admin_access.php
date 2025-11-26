<?php
/**
 * LÀ CRM - Admin Access Verification Script
 * 
 * This script verifies and ensures admin user has full access
 * Run once: http://localhost/lacrm/verify_admin_access.php
 */

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'lacrm';

?>
<!DOCTYPE html>
<html>
<head>
    <title>LÀ CRM - Admin Access Verification</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px 0; }
        .info { color: #0c5460; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 4px; margin: 10px 0; }
        .warning { color: #856404; padding: 10px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table th, table td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        table th { background: #f8f9fa; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>LÀ CRM - Admin Access Verification</h1>
    
    <?php
    try {
        // Connect to database
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        echo "<div class='success'>✓ Connected to database successfully</div>";
        
        // Check if admin role exists
        $result = $conn->query("SELECT * FROM roles WHERE id = 1 OR role_name = 'Admin'");
        if ($result->num_rows == 0) {
            // Create admin role
            $conn->query("INSERT INTO roles (id, role_name, description) VALUES (1, 'Admin', 'Full system access')");
            echo "<div class='info'>✓ Created Admin role</div>";
        } else {
            $role = $result->fetch_assoc();
            echo "<div class='success'>✓ Admin role exists (ID: {$role['id']})</div>";
        }
        
        // Check if admin user exists
        $result = $conn->query("SELECT * FROM users WHERE email = 'admin@crm.com' OR role_id = 1 LIMIT 1");
        if ($result->num_rows == 0) {
            // Create admin user
            $password_hash = password_hash('admin123', PASSWORD_BCRYPT);
            $conn->query("INSERT INTO users (name, email, password, role_id, status, password_updated_at) 
                         VALUES ('Admin User', 'admin@crm.com', '$password_hash', 1, 1, NOW())");
            echo "<div class='info'>✓ Created Admin user</div>";
        } else {
            $admin = $result->fetch_assoc();
            // Update admin to ensure role_id is 1 and status is active
            $conn->query("UPDATE users SET role_id = 1, status = 1 WHERE id = {$admin['id']}");
            echo "<div class='success'>✓ Admin user exists (ID: {$admin['id']}, Email: {$admin['email']})</div>";
        }
        
        // Get admin user details
        $result = $conn->query("SELECT u.*, r.role_name FROM users u 
                               JOIN roles r ON r.id = u.role_id 
                               WHERE u.email = 'admin@crm.com'");
        $admin = $result->fetch_assoc();
        
        echo "<div class='info'><strong>Admin User Details:</strong><br>";
        echo "Name: <strong>{$admin['name']}</strong><br>";
        echo "Email: <strong>{$admin['email']}</strong><br>";
        echo "Role: <strong>{$admin['role_name']}</strong><br>";
        echo "Status: <strong>" . ($admin['status'] == 1 ? 'Active' : 'Inactive') . "</strong><br>";
        echo "User ID: <strong>{$admin['id']}</strong><br>";
        echo "Role ID: <strong>{$admin['role_id']}</strong></div>";
        
        // Verify admin permissions
        $modules = ['dashboard', 'users', 'leads', 'companies', 'contacts', 'reports', 'settings', 'patients', 'courses'];
        $missing_permissions = array();
        
        echo "<div class='info'><strong>Admin Permissions Check:</strong></div>";
        echo "<table>";
        echo "<tr><th>Module</th><th>View</th><th>Edit</th><th>Delete</th><th>Assign</th><th>Status</th></tr>";
        
        foreach ($modules as $module) {
            $result = $conn->query("SELECT * FROM role_permissions WHERE role_id = 1 AND module_name = '$module'");
            
            if ($result->num_rows == 0) {
                // Create permission
                $conn->query("INSERT INTO role_permissions (role_id, module_name, can_view, can_edit, can_delete, can_assign) 
                             VALUES (1, '$module', 1, 1, 1, 1)");
                $status = "<span style='color:green;'>✓ Created</span>";
            } else {
                $perm = $result->fetch_assoc();
                // Update to ensure full access
                $conn->query("UPDATE role_permissions SET can_view = 1, can_edit = 1, can_delete = 1, can_assign = 1 
                             WHERE role_id = 1 AND module_name = '$module'");
                $status = "<span style='color:green;'>✓ Verified</span>";
            }
            
            $result = $conn->query("SELECT * FROM role_permissions WHERE role_id = 1 AND module_name = '$module'");
            $perm = $result->fetch_assoc();
            
            echo "<tr>";
            echo "<td><strong>$module</strong></td>";
            echo "<td>" . ($perm['can_view'] ? '✓' : '✗') . "</td>";
            echo "<td>" . ($perm['can_edit'] ? '✓' : '✗') . "</td>";
            echo "<td>" . ($perm['can_delete'] ? '✓' : '✗') . "</td>";
            echo "<td>" . ($perm['can_assign'] ? '✓' : '✗') . "</td>";
            echo "<td>$status</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Summary
        $perm_count = $conn->query("SELECT COUNT(*) as count FROM role_permissions WHERE role_id = 1")->fetch_assoc()['count'];
        
        echo "<div class='success'><strong>✅ Admin Access Verified!</strong><br><br>";
        echo "✓ Admin Role: <strong>Active</strong><br>";
        echo "✓ Admin User: <strong>Active</strong><br>";
        echo "✓ Permissions: <strong>$perm_count modules</strong> with full access<br><br>";
        echo "<strong>Login Credentials:</strong><br>";
        echo "Email: <code>admin@crm.com</code><br>";
        echo "Password: <code>admin123</code><br><br>";
        echo "<a href='auth/login' style='display:inline-block;padding:10px 20px;background:#28a745;color:white;text-decoration:none;border-radius:4px;'>Go to Login →</a></div>";
        
        $conn->close();
        
    } catch (Exception $e) {
        echo "<div class='error'><strong>❌ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
        echo "<div class='warning'>Make sure database is set up first. Run <code>setup_database.php</code> first.</div>";
    }
    ?>
    
    <hr>
    <p><small>⚠️ <strong>Security Note:</strong> Delete this file after verification!</small></p>
</body>
</html>

