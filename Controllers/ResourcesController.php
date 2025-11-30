<?php
require_once 'auth_check.php';
require 'Database/database.php';

// Check authentication - require teacher only
requireTeacher();

$teacher_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_folder':
            $folder_name = trim($_POST['folder_name']);
            $description = trim($_POST['description']);
            
            if (!empty($folder_name)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO teacher_folders (teacher_id, folder_name, description) VALUES (?, ?, ?)");
                    $stmt->execute([$teacher_id, $folder_name, $description]);
                    $success = "تم إنشاء المجلد بنجاح";
                } catch (PDOException $e) {
                    $error = "خطأ في إنشاء المجلد: " . $e->getMessage();
                }
            } else {
                $error = "اسم المجلد مطلوب";
            }
            break;
            
        case 'edit_folder':
            $folder_id = $_POST['folder_id'];
            $folder_name = trim($_POST['folder_name']);
            $description = trim($_POST['description']);
            
            if (!empty($folder_name)) {
                try {
                    $stmt = $pdo->prepare("UPDATE teacher_folders SET folder_name = ?, description = ? WHERE id = ? AND teacher_id = ?");
                    $stmt->execute([$folder_name, $description, $folder_id, $teacher_id]);
                    $success = "تم تحديث المجلد بنجاح";
                } catch (PDOException $e) {
                    $error = "خطأ في تحديث المجلد: " . $e->getMessage();
                }
            } else {
                $error = "اسم المجلد مطلوب";
            }
            break;
            
        case 'delete_folder':
            $folder_id = $_POST['folder_id'];
            
            try {
                $stmt = $pdo->prepare("DELETE FROM teacher_folders WHERE id = ? AND teacher_id = ?");
                $stmt->execute([$folder_id, $teacher_id]);
                $success = "تم حذف المجلد بنجاح";
            } catch (PDOException $e) {
                $error = "خطأ في حذف المجلد: " . $e->getMessage();
            }
            break;
            
        case 'add_link':
            $folder_id = $_POST['folder_id'];
            $link_title = trim($_POST['link_title']);
            $link_url = trim($_POST['link_url']);
            $link_type = $_POST['link_type'];
            $description = trim($_POST['description']);
            
            if (!empty($link_title) && !empty($link_url)) {
                try {
                    // Verify that the folder belongs to this teacher
                    $stmt = $pdo->prepare("SELECT id FROM teacher_folders WHERE id = ? AND teacher_id = ?");
                    $stmt->execute([$folder_id, $teacher_id]);
                    
                    if ($stmt->fetch()) {
                        $stmt = $pdo->prepare("INSERT INTO teacher_links (folder_id, teacher_id, link_title, link_url, link_type, description) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$folder_id, $teacher_id, $link_title, $link_url, $link_type, $description]);
                        $success = "تم إضافة الرابط بنجاح";
                    } else {
                        $error = "المجلد غير موجود";
                    }
                } catch (PDOException $e) {
                    $error = "خطأ في إضافة الرابط: " . $e->getMessage();
                }
            } else {
                $error = "عنوان الرابط والرابط مطلوبان";
            }
            break;
            
        case 'edit_link':
            $link_id = $_POST['link_id'];
            $link_title = trim($_POST['link_title']);
            $link_url = trim($_POST['link_url']);
            $link_type = $_POST['link_type'];
            $description = trim($_POST['description']);
            
            if (!empty($link_title) && !empty($link_url)) {
                try {
                    $stmt = $pdo->prepare("UPDATE teacher_links SET link_title = ?, link_url = ?, link_type = ?, description = ? WHERE id = ? AND teacher_id = ?");
                    $stmt->execute([$link_title, $link_url, $link_type, $description, $link_id, $teacher_id]);
                    $success = "تم تحديث الرابط بنجاح";
                } catch (PDOException $e) {
                    $error = "خطأ في تحديث الرابط: " . $e->getMessage();
                }
            } else {
                $error = "عنوان الرابط والرابط مطلوبان";
            }
            break;
            
        case 'delete_link':
            $link_id = $_POST['link_id'];
            
            try {
                $stmt = $pdo->prepare("DELETE FROM teacher_links WHERE id = ? AND teacher_id = ?");
                $stmt->execute([$link_id, $teacher_id]);
                $success = "تم حذف الرابط بنجاح";
            } catch (PDOException $e) {
                $error = "خطأ في حذف الرابط: " . $e->getMessage();
            }
            break;
    }
}

// Get all folders for this teacher
try {
    $stmt = $pdo->prepare("SELECT * FROM teacher_folders WHERE teacher_id = ? ORDER BY created_at DESC");
    $stmt->execute([$teacher_id]);
    $folders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $folders = [];
    $error = "خطأ في جلب المجلدات: " . $e->getMessage();
}

// Get all links for this teacher with folder names
try {
    $stmt = $pdo->prepare("
        SELECT tl.*, tf.folder_name 
        FROM teacher_links tl 
        JOIN teacher_folders tf ON tl.folder_id = tf.id 
        WHERE tl.teacher_id = ? 
        ORDER BY tf.folder_name, tl.created_at DESC
    ");
    $stmt->execute([$teacher_id]);
    $links = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $links = [];
    $error = "خطأ في جلب الروابط: " . $e->getMessage();
}

// Group links by folder
$links_by_folder = [];
foreach ($links as $link) {
    $links_by_folder[$link['folder_id']][] = $link;
}
?>