<?php
// Simple test script to verify student login functionality
require 'Database/database.php';

echo "<h2>Student Login Test</h2>";

// Test student credentials
$test_credentials = [
    ['email' => 'student1@example.com', 'password' => 'hello123'],
    ['email' => 'student2@example.com', 'password' => 'password123']
];

foreach ($test_credentials as $cred) {
    echo "<h3>Testing: {$cred['email']}</h3>";
    
    // Get user from database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$cred['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "✓ User found in database<br>";
        echo "- Name: " . htmlspecialchars($user['name']) . "<br>";
        echo "- Role: " . htmlspecialchars($user['role']) . "<br>";
        echo "- Status: " . htmlspecialchars($user['status']) . "<br>";
        echo "- Database hash: " . $user['password'] . "<br>";
        echo "- Test password hash: " . md5($cred['password']) . "<br>";
        
        if (md5($cred['password']) === $user['password']) {
            echo "✅ <strong>Password matches! Login should work.</strong><br>";
        } else {
            echo "❌ <strong>Password doesn't match.</strong><br>";
        }
    } else {
        echo "❌ User not found in database<br>";
    }
    echo "<hr>";
}

// Show all student users
echo "<h3>All Student Users in Database:</h3>";
$stmt = $pdo->prepare("SELECT id, name, email, status FROM users WHERE role = 'student'");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($students) {
    echo "<ul>";
    foreach ($students as $student) {
        echo "<li>{$student['name']} ({$student['email']}) - Status: {$student['status']}</li>";
    }
    echo "</ul>";
} else {
    echo "No students found in database.";
}
?>