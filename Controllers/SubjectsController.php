<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {

        // إضافة مادة جديدة
        if ($_POST['action'] === 'add_subject') {
            $stmt = $pdo->prepare("INSERT INTO subjects (name, class_id, created_by) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['subject_name'], $_POST['class_id'], 1]); // مؤقتًا created_by = 1

        // تعديل المادة
        } elseif ($_POST['action'] === 'edit_subject') {
            $stmt = $pdo->prepare("UPDATE subjects SET name = ?, class_id = ?, updated_by = ? WHERE id = ?");
            $stmt->execute([$_POST['subject_name'], $_POST['class_id'], 1, $_POST['id']]);

        // حذف المادة
        } elseif ($_POST['action'] === 'delete_subject') {
            $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// جلب الصفوف لعرضها في القائمة المنسدلة
$classes = $pdo->query("SELECT * FROM classes ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// جلب المواد مع اسم الصف
$subjects = $pdo->query("
    SELECT 
        s.id, 
        s.name AS subject_name, 
        s.class_id, 
        c.name AS class_name, 
        p.name AS program_name
    FROM subjects s
    JOIN classes c ON s.class_id = c.id
    JOIN programs p ON c.program_id = p.id
    ORDER BY s.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>
