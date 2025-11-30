<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_system') {
            $stmt = $pdo->prepare("INSERT INTO systems (name, academic_year) VALUES (?, ?)");
            $stmt->execute([$_POST['system_name'], $_POST['academic_year']]);
        } elseif ($_POST['action'] === 'delete_system') {
            $stmt = $pdo->prepare("DELETE FROM systems WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        } elseif ($_POST['action'] === 'edit_system') {
            $stmt = $pdo->prepare("UPDATE systems SET name = ?, academic_year = ? WHERE id = ?");
            $stmt->execute([$_POST['system_name'], $_POST['academic_year'], $_POST['id']]);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch data
$systems = $pdo->query("SELECT * FROM systems ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>