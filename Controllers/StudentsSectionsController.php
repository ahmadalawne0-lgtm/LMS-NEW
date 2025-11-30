<?php
require_once 'auth_check.php';

// Check authentication - require admin or teacher
requireAdminOrTeacher();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {

        if ($_POST['action'] === 'add_student_to_section') {
            $check = $pdo->prepare("SELECT COUNT(*) FROM section_students WHERE student_id = ? AND section_id = ?");
            $check->execute([$_POST['student_id'], $_POST['section_id']]);
            if ($check->fetchColumn() == 0) {
                $stmt = $pdo->prepare("INSERT INTO section_students (student_id, section_id) VALUES ( ?, ?)");
                $stmt->execute([$_POST['student_id'], $_POST['section_id']]);
            }

        } elseif ($_POST['action'] === 'remove_student_from_section') {
            $stmt = $pdo->prepare("DELETE FROM section_students WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// جلب كل الطلاب
$students = $pdo->query("SELECT id, name FROM users WHERE role = 'student' ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// جلب كل الطلاب في الشعب (مع التفاصيل)
$section_students_raw = $pdo->query("
    SELECT u.id AS student_id, u.name AS student_name,
           ss.id AS section_student_id,
           s.id AS section_id, s.name AS section_name,
           c.id AS class_id, c.name AS class_name
    FROM users u
    LEFT JOIN section_students ss ON u.id = ss.student_id
    LEFT JOIN sections s ON ss.section_id = s.id
    LEFT JOIN classes c ON s.class_id = c.id
    WHERE u.role = 'student'
    ORDER BY u.name
")->fetchAll(PDO::FETCH_ASSOC);

// تجهيز خريطة للطلاب في الشعب لتسهيل البحث في الـUI
$section_students_map = [];
foreach($section_students_raw as $ss) {
    if ($ss['section_student_id']) {
        $section_students_map[$ss['student_id']] = [
            'id' => $ss['section_student_id'],
            'section_id' => $ss['section_id'],
            'section_name' => $ss['section_name'],
            'class_id' => $ss['class_id'],
            'class_name' => $ss['class_name']
        ];
    }
}

// جلب كل الشعب
$sections = $pdo->query("
    SELECT s.id, s.name AS section_name, c.name AS class_name
    FROM sections s
    JOIN classes c ON s.class_id = c.id
")->fetchAll(PDO::FETCH_ASSOC);
?>
