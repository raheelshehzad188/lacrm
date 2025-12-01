<?php
/**
 * LÀ CRM - Reset Admin Access Script
 * 
 * This script resets admin user password and ensures full access
 * Run once: http://localhost/lacrm/reset_admin_access.php
 * 
 * WARNING: Delete this file after use for security!
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
    <title>LÀ CRM - Reset Admin Access</title>
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
        button { padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #c82333; }
    </style>
</head>
<body>
    <h1>LÀ CRM - Reset Admin Access</h1>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
        try {
            // Connect to database
            $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
            
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            
            echo "<div class='info'>✓ Connected to database</div>";
            
            // Generate new password hash (fresh hash every time)
            $new_password = 'admin123';
            $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
            
            // Verify the hash works
            if (!password_verify($new_password, $password_hash)) {
                throw new Exception("Password hash generation failed!");
            }
            
            // Check if admin user exists
            $result = $conn->query("SELECT * FROM users WHERE email = 'admin@crm.com'");
            
            if ($result->num_rows == 0) {
                // Create admin user
                $conn->query("INSERT INTO users (name, email, password, role_id, status, password_updated_at) 
                             VALUES ('Admin User', 'admin@crm.com', '$password_hash', 1, 1, NOW())");
                echo "<div class='info'>✓ Created new admin user</div>";
            } else {
                // Update existing admin user using prepared statement
                $stmt = $conn->prepare("UPDATE users SET password = ?, role_id = 1, status = 1, password_updated_at = NOW() WHERE email = 'admin@crm.com'");
                $stmt->bind_param("s", $password_hash);
                $stmt->execute();
                $stmt->close();
                echo "<div class='success'>✓ Admin password reset successfully</div>";
            }
            
            // Ensure admin role exists
            $result = $conn->query("SELECT * FROM roles WHERE id = 1");
            if ($result->num_rows == 0) {
                $conn->query("INSERT INTO roles (id, role_name, description) VALUES (1, 'Admin', 'Full system access')");
                echo "<div class='info'>✓ Created Admin role</div>";
            }
            
            // Ensure all admin permissions exist
            $modules = ['dashboard', 'users', 'leads', 'companies', 'contacts', 'reports', 'settings', 'patients', 'courses'];
            foreach ($modules as $module) {
                $result = $conn->query("SELECT * FROM role_permissions WHERE role_id = 1 AND module_name = '$module'");
                if ($result->num_rows == 0) {
                    $conn->query("INSERT INTO role_permissions (role_id, module_name, can_view, can_edit, can_delete, can_assign) 
                                 VALUES (1, '$module', 1, 1, 1, 1)");
                } else {
                    $conn->query("UPDATE role_permissions SET can_view = 1, can_edit = 1, can_delete = 1, can_assign = 1 
                                 WHERE role_id = 1 AND module_name = '$module'");
                }
            }
            
            // Get admin user details
            $result = $conn->query("SELECT u.*, r.role_name FROM users u 
                                   JOIN roles r ON r.id = u.role_id 
                                   WHERE u.email = 'admin@crm.com'");
            $admin = $result->fetch_assoc();
            
            // Verify password
            $verify_result = $conn->query("SELECT password FROM users WHERE email = 'admin@crm.com'");
            $user_data = $verify_result->fetch_assoc();
            $password_verified = password_verify($new_password, $user_data['password']);
            
            echo "<div class='success'><strong>✅ Admin Access Reset Complete!</strong></div>";
            echo "<div class='info'><strong>Admin User Details:</strong><br>";
            echo "Name: <strong>{$admin['name']}</strong><br>";
            echo "Email: <strong>{$admin['email']}</strong><br>";
            echo "Role: <strong>{$admin['role_name']}</strong><br>";
            echo "Status: <strong>" . ($admin['status'] == 1 ? 'Active' : 'Inactive') . "</strong><br>";
            echo "Password Verified: <strong>" . ($password_verified ? '✓ Yes' : '✗ No') . "</strong></div>";
            
            // Show permissions
            $perm_count = $conn->query("SELECT COUNT(*) as count FROM role_permissions WHERE role_id = 1")->fetch_assoc()['count'];
            echo "<div class='info'>✓ Admin has <strong>$perm_count modules</strong> with full access</div>";
            
            echo "<div class='success'><strong>🎉 Reset Complete!</strong><br><br>";
            echo "<strong>Login Credentials:</strong><br>";
            echo "Email: <code>admin@crm.com</code><br>";
            echo "Password: <code>admin123</code><br><br>";
            echo "<a href='auth/login' style='display:inline-block;padding:10px 20px;background:#28a745;color:white;text-decoration:none;border-radius:4px;'>Go to Login →</a></div>";
            
            $conn->close();
            
        } catch (Exception $e) {
            echo "<div class='error'><strong>❌ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
    ?>
    
    <div class="warning">
        <h3>⚠️ Reset Admin Access</h3>
        <p>This will reset the admin user password to <strong>admin123</strong> and ensure full access.</p>
        <p><strong>What this does:</strong></p>
        <ul>
            <li>Resets admin password to: <code>admin123</code></li>
            <li>Ensures admin role (ID: 1)</li>
            <li>Activates admin account</li>
            <li>Grants full permissions to all modules</li>
        </ul>
    </div>
    
    <div class="info">
        <strong>Database Configuration:</strong><br>
        Host: <code><?php echo htmlspecialchars($db_host); ?></code><br>
        User: <code><?php echo htmlspecialchars($db_user); ?></code><br>
        Database: <code><?php echo htmlspecialchars($db_name); ?></code>
    </div>
    
    <form method="POST" style="margin: 20px 0;">
        <button type="submit" name="reset" onclick="return confirm('Are you sure you want to reset admin access? This will change the password to admin123.');">
            🔄 Reset Admin Access
        </button>
    </form>
    
    <?php } ?>
    
    <hr>
    <div class="warning">
        <strong>⚠️ Security Note:</strong> Delete this file after resetting admin access!
    </div>
</body>
</html>

