<?php
/**
 * LÀ CRM - Quick Database Setup Script
 * 
 * This script helps you quickly set up the database.
 * Run this file once via browser: http://localhost/lacrm/setup_database.php
 * 
 * WARNING: Delete this file after setup for security!
 */

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = ''; // Change if you have MySQL password
$db_name = 'lacrm';

$sql_file = __DIR__ . '/database_complete.sql';

?>
<!DOCTYPE html>
<html>
<head>
    <title>LÀ CRM - Database Setup</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px 0; }
        .info { color: #0c5460; padding: 10px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 4px; margin: 10px 0; }
        .warning { color: #856404; padding: 10px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px; margin: 10px 0; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #0056b3; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table th, table td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        table th { background: #f8f9fa; }
    </style>
</head>
<body>
    <h1>LÀ CRM - Database Setup</h1>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setup'])) {
        try {
            // Connect to MySQL (without database)
            $conn = new mysqli($db_host, $db_user, $db_pass);
            
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }
            
            echo "<div class='info'>✓ Connected to MySQL server successfully</div>";
            
            // Create database if not exists
            $sql = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            if ($conn->query($sql) === TRUE) {
                echo "<div class='success'>✓ Database '$db_name' created/verified successfully!</div>";
            } else {
                throw new Exception("Error creating database: " . $conn->error);
            }
            
            // Select database
            $conn->select_db($db_name);
            echo "<div class='info'>✓ Selected database '$db_name'</div>";
            
            // Read SQL file
            if (!file_exists($sql_file)) {
                throw new Exception("SQL file not found: $sql_file");
            }
            
            $sql_content = file_get_contents($sql_file);
            echo "<div class='info'>✓ SQL file loaded (" . number_format(strlen($sql_content)) . " bytes)</div>";
            
            // Clean SQL content
            // Remove comments
            $sql_content = preg_replace('/--.*$/m', '', $sql_content);
            $sql_content = preg_replace('/\/\*.*?\*\//s', '', $sql_content);
            
            // Disable foreign key checks
            $conn->query("SET FOREIGN_KEY_CHECKS = 0;");
            $conn->query("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';");
            
            // Split SQL into statements
            // Better approach: split by semicolon but handle multi-line statements
            $statements = array();
            $current = '';
            $in_string = false;
            $string_char = '';
            
            for ($i = 0; $i < strlen($sql_content); $i++) {
                $char = $sql_content[$i];
                $current .= $char;
                
                // Track string boundaries
                if (($char === '"' || $char === "'") && ($i === 0 || $sql_content[$i-1] !== '\\')) {
                    if (!$in_string) {
                        $in_string = true;
                        $string_char = $char;
                    } elseif ($char === $string_char) {
                        $in_string = false;
                        $string_char = '';
                    }
                }
                
                // If semicolon and not in string, it's end of statement
                if ($char === ';' && !$in_string) {
                    $stmt = trim($current);
                    if (!empty($stmt) && strlen($stmt) > 10) {
                        // Skip certain statements
                        if (stripos($stmt, 'SET FOREIGN_KEY_CHECKS') === false &&
                            stripos($stmt, 'SHOW TABLES') === false &&
                            stripos($stmt, 'SELECT') !== 0) {
                            $statements[] = $stmt;
                        }
                    }
                    $current = '';
                }
            }
            
            echo "<div class='info'>✓ Parsed " . count($statements) . " SQL statements</div>";
            
            // Execute statements
            $success_count = 0;
            $error_count = 0;
            $errors = array();
            $executed_tables = array();
            
            foreach ($statements as $index => $statement) {
                $statement = trim($statement);
                if (empty($statement)) continue;
                
                // Extract table name from CREATE TABLE statement
                if (preg_match('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?(\w+)`?/i', $statement, $matches)) {
                    $table_name = $matches[1];
                } else {
                    $table_name = null;
                }
                
                if ($conn->query($statement) === TRUE) {
                    $success_count++;
                    if ($table_name) {
                        $executed_tables[] = $table_name;
                    }
                } else {
                    $error_msg = $conn->error;
                    // Ignore duplicate/already exists errors
                    if (strpos($error_msg, 'Duplicate') === false && 
                        strpos($error_msg, 'already exists') === false &&
                        strpos($error_msg, 'Unknown database') === false &&
                        !empty($error_msg)) {
                        $error_count++;
                        $errors[] = array(
                            'statement' => substr($statement, 0, 80) . '...',
                            'error' => $error_msg,
                            'table' => $table_name
                        );
                    }
                }
            }
            
            // Re-enable foreign key checks
            $conn->query("SET FOREIGN_KEY_CHECKS = 1;");
            
            // Show results
            if ($error_count > 0) {
                echo "<div class='error'><strong>⚠️ Some errors occurred ($error_count):</strong><br>";
                foreach (array_slice($errors, 0, 5) as $err) {
                    echo "• " . htmlspecialchars($err['error']) . "<br>";
                    if ($err['table']) {
                        echo "&nbsp;&nbsp;Table: " . htmlspecialchars($err['table']) . "<br>";
                    }
                }
                if (count($errors) > 5) {
                    echo "... and " . (count($errors) - 5) . " more errors<br>";
                }
                echo "</div>";
            }
            
            echo "<div class='success'>✓ Database setup completed!</div>";
            echo "<div class='info'><strong>Executed $success_count SQL statements successfully.</strong></div>";
            
            // Show created tables
            $result = $conn->query("SHOW TABLES");
            $table_count = $result->num_rows;
            echo "<div class='success'>✓ Created $table_count tables</div>";
            
            if ($table_count > 0) {
                echo "<div class='info'><strong>Tables Created:</strong><ul>";
                while ($row = $result->fetch_array()) {
                    echo "<li>" . htmlspecialchars($row[0]) . "</li>";
                }
                echo "</ul></div>";
            }
            
            // Show sample data counts
            $tables_to_check = ['roles', 'users', 'role_permissions', 'leads', 'course_enrollments', 'companies', 'contacts', 'user_activity_log'];
            echo "<div class='info'><strong>Sample Data Counts:</strong><br>";
            echo "<table><tr><th>Table</th><th>Records</th></tr>";
            foreach ($tables_to_check as $table) {
                $count_result = $conn->query("SELECT COUNT(*) as count FROM `$table`");
                if ($count_result) {
                    $count = $count_result->fetch_assoc()['count'];
                    echo "<tr><td>$table</td><td><strong>$count</strong></td></tr>";
                } else {
                    echo "<tr><td>$table</td><td><span style='color:red;'>Error</span></td></tr>";
                }
            }
            echo "</table></div>";
            
            echo "<div class='success'><strong>🎉 Setup Complete!</strong><br><br>";
            echo "✅ Database: <strong>$db_name</strong><br>";
            echo "✅ Tables: <strong>$table_count</strong><br>";
            echo "✅ Statements: <strong>$success_count</strong><br><br>";
            echo "<strong>Default Login:</strong><br>";
            echo "Email: <code>admin@crm.com</code><br>";
            echo "Password: <code>admin123</code><br><br>";
            echo "<a href='auth/login' style='display:inline-block;padding:10px 20px;background:#28a745;color:white;text-decoration:none;border-radius:4px;'>Go to Login Page →</a></div>";
            
            $conn->close();
            
        } catch (Exception $e) {
            echo "<div class='error'><strong>❌ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
    ?>
    
    <div class="info">
        <h3>📋 Database Setup Instructions:</h3>
        <ol>
            <li>Make sure MySQL/XAMPP is running</li>
            <li>Update database credentials in this file if needed (lines 12-15)</li>
            <li>Click the button below to create database and tables</li>
            <li><strong>Delete this file after setup for security!</strong></li>
        </ol>
    </div>
    
    <div class="info">
        <strong>Database Configuration:</strong><br>
        Host: <code><?php echo htmlspecialchars($db_host); ?></code><br>
        User: <code><?php echo htmlspecialchars($db_user); ?></code><br>
        Database: <code><?php echo htmlspecialchars($db_name); ?></code><br>
        SQL File: <code><?php echo basename($sql_file); ?></code>
    </div>
    
    <?php
    // Check if SQL file exists
    if (file_exists($sql_file)) {
        $file_size = filesize($sql_file);
        echo "<div class='success'>✓ SQL file found (" . number_format($file_size) . " bytes)</div>";
    } else {
        echo "<div class='error'>✗ SQL file not found: " . htmlspecialchars($sql_file) . "</div>";
    }
    ?>
    
    <form method="POST" style="margin: 20px 0;">
        <button type="submit" name="setup" onclick="return confirm('This will create/overwrite the database. Continue?');">
            🚀 Setup Database Now
        </button>
    </form>
    
    <?php } ?>
    
    <hr>
    <div class="warning">
        <strong>⚠️ Security Note:</strong> Delete this file (<code>setup_database.php</code>) after database setup is complete!
    </div>
</body>
</html>
