<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {

        // إضافة برنامج جديد
        if ($_POST['action'] === 'add_program') {
            $stmt = $pdo->prepare("INSERT INTO programs (name, level_id, created_by) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['program_name'], $_POST['level_id'], 1]); // مؤقتًا user_id = 1

        // تعديل البرنامج
        } elseif ($_POST['action'] === 'edit_program') {
            $stmt = $pdo->prepare("UPDATE programs SET name = ?, level_id = ?, updated_by = ? WHERE id = ?");
            $stmt->execute([$_POST['program_name'], $_POST['level_id'], 1, $_POST['id']]);

        // حذف البرنامج
        } elseif ($_POST['action'] === 'delete_program') {
            $stmt = $pdo->prepare("DELETE FROM programs WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// جلب المستويات لعرضها في القائمة المنسدلة
$levels = $pdo->query("SELECT * FROM levels ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// جلب البرامج مع اسم المستوى
$programs = $pdo->query("
    SELECT p.id, p.name AS program_name, p.level_id, l.name AS level_name
    FROM programs p
    JOIN levels l ON p.level_id = l.id
    ORDER BY p.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
