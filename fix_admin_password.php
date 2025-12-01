<?php
/**
 * Quick Fix: Reset Admin Password
 * Direct database update to fix admin password
 */

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'lacrm';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Generate fresh password hash for admin123
$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);

// Update admin user
$sql = "UPDATE users SET 
        password = ?,
        role_id = 1,
        status = 1,
        password_updated_at = NOW()
        WHERE email = 'admin@crm.com'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hash);

if ($stmt->execute()) {
    echo "✅ Admin password reset successfully!\n";
    echo "Email: admin@crm.com\n";
    echo "Password: admin123\n";
    echo "Hash: " . substr($hash, 0, 30) . "...\n";
    
    // Verify
    $result = $conn->query("SELECT password FROM users WHERE email = 'admin@crm.com'");
    $user = $result->fetch_assoc();
    if (password_verify('admin123', $user['password'])) {
        echo "✅ Password verification: SUCCESS\n";
    } else {
        echo "❌ Password verification: FAILED\n";
    }
} else {
    echo "❌ Error: " . $conn->error . "\n";
}

$stmt->close();
$conn->close();

?>

