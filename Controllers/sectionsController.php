<?php
// sections-controller.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_section') {
            $stmt = $pdo->prepare("INSERT INTO sections (name, class_id, created_by) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['section_name'], $_POST['class_id'], $_POST['user_id'] ?? NULL]);
        } elseif ($_POST['action'] === 'edit_section') {
            $stmt = $pdo->prepare("UPDATE sections SET name = ?, class_id = ?, updated_by = ? WHERE id = ?");
            $stmt->execute([$_POST['section_name'], $_POST['class_id'], $_POST['user_id'] ?? NULL, $_POST['id']]);
        } elseif ($_POST['action'] === 'delete_section') {
            $stmt = $pdo->prepare("DELETE FROM sections WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch classes for dropdown
$classes = $pdo->query("SELECT c.id, c.name, l.name AS level_name, s.name AS system_name
                        FROM classes c
                        JOIN programs p ON c.program_id = p.id
                        JOIN levels l ON p.level_id = l.id
                        JOIN systems s ON l.system_id = s.id
                        ORDER BY c.id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch sections with class name
$sections = $pdo->query("
    SELECT sec.id, sec.name AS section_name, sec.class_id, c.name AS class_name, l.name AS level_name, s.name AS system_name
    FROM sections sec
    JOIN classes c ON sec.class_id = c.id
    JOIN programs p ON c.program_id = p.id
    JOIN levels l ON p.level_id = l.id
    JOIN systems s ON l.system_id = s.id
    ORDER BY sec.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
