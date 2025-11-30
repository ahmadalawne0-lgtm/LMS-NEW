<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_level') {
            $stmt = $pdo->prepare("INSERT INTO levels (name, system_id) VALUES (?, ?)");
            $stmt->execute([$_POST['level_name'], $_POST['system_id']]);
        } elseif ($_POST['action'] === 'edit_level') {
            $stmt = $pdo->prepare("UPDATE levels SET name = ?, system_id = ? WHERE id = ?");
            $stmt->execute([$_POST['level_name'], $_POST['system_id'], $_POST['id']]);
        } elseif ($_POST['action'] === 'delete_level') {
            $stmt = $pdo->prepare("DELETE FROM levels WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch systems for dropdown
$systems = $pdo->query("SELECT * FROM systems ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch levels with system name and academic_year via JOIN
$levels = $pdo->query("
    SELECT l.id, l.name AS level_name, l.system_id, s.name AS system_name, s.academic_year
    FROM levels l
    JOIN systems s ON l.system_id = s.id
    ORDER BY l.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>