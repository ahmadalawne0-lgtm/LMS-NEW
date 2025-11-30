<?php
require_once 'session_manager.php';
require 'Database/database.php'; // ملف الاتصال بقاعدة البيانات PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check for all user types (admin, teacher, student)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && md5($password) === $user['password']) {
        // Set session variables based on user role
        if ($user['role'] === 'student') {
            // Student uses different session variables for backward compatibility
            $_SESSION['student_id'] = $user['id'];
            $_SESSION['student_name'] = $user['name'];
            $_SESSION['student_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role']; // Also set for consistency
            
            // Redirect to student dashboard
            header('Location: student_dashboard.php');
            exit;
        } else {
            // Teachers and admins use the standard session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'];
            
            // Redirect to main dashboard (index.php)
            header('Location: index.php');
            exit;
        }
    } else {
        $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
    }
}
?>
