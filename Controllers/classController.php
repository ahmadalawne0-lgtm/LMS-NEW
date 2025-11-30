<?php
// classes-controller.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_class') {
            $stmt = $pdo->prepare("INSERT INTO classes (name, program_id, teacher_id, created_by) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $_POST['class_name'],
                $_POST['program_id'],
                $_POST['teacher_id'] ?: NULL,
                1 // replace with logged-in user id if you have login system
            ]);
        } elseif ($_POST['action'] === 'edit_class') {
            $stmt = $pdo->prepare("UPDATE classes SET name = ?, program_id = ?, teacher_id = ?, updated_by = ? WHERE id = ?");
            $stmt->execute([
                $_POST['class_name'],
                $_POST['program_id'],
                $_POST['teacher_id'] ?: NULL,
                1, // replace with logged-in user id
                $_POST['id']
            ]);
        } elseif ($_POST['action'] === 'delete_class') {
            $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ====== Fetch Programs ======
$programs = $pdo->query("
    SELECT p.id, p.name AS program_name, l.name AS level_name, s.name AS system_name
    FROM programs p
    JOIN levels l ON p.level_id = l.id
    JOIN systems s ON l.system_id = s.id
    ORDER BY p.id DESC
")->fetchAll();

// ====== Fetch Teachers ======
$teachers = $pdo->query("SELECT id, name FROM users WHERE role='teacher' ORDER BY name ASC")->fetchAll();

// ====== Fetch Classes ======
$classes = $pdo->query("
    SELECT c.id, c.name AS class_name, c.program_id, c.teacher_id,
           p.name AS program_name, l.name AS level_name, s.name AS system_name,
           u.name AS teacher_name
    FROM classes c
    JOIN programs p ON c.program_id = p.id
    JOIN levels l ON p.level_id = l.id
    JOIN systems s ON l.system_id = s.id
    LEFT JOIN users u ON c.teacher_id = u.id
    ORDER BY c.id DESC
")->fetchAll();
?>