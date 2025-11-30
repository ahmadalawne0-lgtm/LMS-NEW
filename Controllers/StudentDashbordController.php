<?php
require_once 'auth_check.php';
require 'Database/database.php';

// Check authentication - require student
requireStudent();

// نجلب الطالب ونعرف صفه عبر جدول section_students (إن وجد)
$stmt = $pdo->prepare("
    SELECT c.id AS class_id, c.name AS class_name, s.name AS section_name
    FROM section_students ss
    JOIN sections s ON ss.section_id = s.id
    JOIN classes c ON s.class_id = c.id
    WHERE ss.student_id = ?
    LIMIT 1
");
$stmt->execute([$_SESSION['student_id']]);
$info = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$info) {
    die("لم يتم ربطك بأي صف أو شعبة بعد.");
}

// نجلب المواد الخاصة بهذا الصف
$stmt = $pdo->prepare("SELECT * FROM subjects WHERE class_id = ?");
$stmt->execute([$info['class_id']]);
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
