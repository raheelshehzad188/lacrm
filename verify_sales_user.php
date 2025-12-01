<?php
/**
 * Verify Sales Person 1 Login Credentials
 */

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'lacrm';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = 'sales1@crm.com';
$password = 'admin123';

// Get user from database
$stmt = $conn->prepare("SELECT u.*, r.role_name FROM users u LEFT JOIN roles r ON r.id = u.role_id WHERE u.email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "❌ User not found: $email\n";
    echo "Please create this user first using the Users management page.\n";
} else {
    $user = $result->fetch_assoc();
    
    echo "✅ User found!\n";
    echo "Name: {$user['name']}\n";
    echo "Email: {$user['email']}\n";
    echo "Role: {$user['role_name']} (ID: {$user['role_id']})\n";
    echo "Status: " . ($user['status'] == 1 ? 'Active' : 'Inactive') . "\n";
    echo "\n";
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        echo "✅ Password verification: SUCCESS\n";
        echo "Email: $email\n";
        echo "Password: $password\n";
        echo "\n";
        echo "Login URL: http://localhost/lacrm/auth/login\n";
    } else {
        echo "❌ Password verification: FAILED\n";
        echo "The password '$password' does not match the stored hash.\n";
        echo "\n";
        echo "To reset the password, run:\n";
        echo "UPDATE users SET password = '" . password_hash($password, PASSWORD_BCRYPT) . "' WHERE email = '$email';\n";
    }
}

$stmt->close();
$conn->close();
?>

