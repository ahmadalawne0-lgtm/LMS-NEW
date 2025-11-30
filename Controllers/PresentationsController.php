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
        case 'add_presentation':
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            
            if (!empty($title)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO teacher_presentations (teacher_id, title, description) VALUES (?, ?, ?)");
                    $stmt->execute([$teacher_id, $title, $description]);
                    $success = "تم إنشاء العرض التقديمي بنجاح";
                } catch (PDOException $e) {
                    $error = "خطأ في إنشاء العرض التقديمي: " . $e->getMessage();
                }
            } else {
                $error = "عنوان العرض التقديمي مطلوب";
            }
            break;
            
        case 'edit_presentation':
            $presentation_id = $_POST['presentation_id'];
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            
            if (!empty($title)) {
                try {
                    $stmt = $pdo->prepare("UPDATE teacher_presentations SET title = ?, description = ? WHERE id = ? AND teacher_id = ?");
                    $stmt->execute([$title, $description, $presentation_id, $teacher_id]);
                    $success = "تم تحديث العرض التقديمي بنجاح";
                } catch (PDOException $e) {
                    $error = "خطأ في تحديث العرض التقديمي: " . $e->getMessage();
                }
            } else {
                $error = "عنوان العرض التقديمي مطلوب";
            }
            break;
            
        case 'delete_presentation':
            $presentation_id = $_POST['presentation_id'];
            
            try {
                $stmt = $pdo->prepare("DELETE FROM teacher_presentations WHERE id = ? AND teacher_id = ?");
                $stmt->execute([$presentation_id, $teacher_id]);
                $success = "تم حذف العرض التقديمي بنجاح";
            } catch (PDOException $e) {
                $error = "خطأ في حذف العرض التقديمي: " . $e->getMessage();
            }
            break;
            
        case 'add_slide':
            $presentation_id = $_POST['presentation_id'];
            $title = trim($_POST['slide_title']);
            $content = trim($_POST['slide_content']);
            $slide_type = $_POST['slide_type'];
            $image_url = trim($_POST['image_url'] ?? '');
            
            if (!empty($title)) {
                try {
                    // Verify that the presentation belongs to this teacher
                    $stmt = $pdo->prepare("SELECT id FROM teacher_presentations WHERE id = ? AND teacher_id = ?");
                    $stmt->execute([$presentation_id, $teacher_id]);
                    
                    if ($stmt->fetch()) {
                        // Get next slide number
                        $stmt = $pdo->prepare("SELECT COALESCE(MAX(slide_number), 0) + 1 as next_slide FROM presentation_slides WHERE presentation_id = ?");
                        $stmt->execute([$presentation_id]);
                        $next_slide = $stmt->fetch()['next_slide'];
                        
                        $stmt = $pdo->prepare("INSERT INTO presentation_slides (presentation_id, slide_number, title, content, slide_type, image_url) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$presentation_id, $next_slide, $title, $content, $slide_type, $image_url]);
                        $success = "تم إضافة الشريحة بنجاح";
                    } else {
                        $error = "العرض التقديمي غير موجود";
                    }
                } catch (PDOException $e) {
                    $error = "خطأ في إضافة الشريحة: " . $e->getMessage();
                }
            } else {
                $error = "عنوان الشريحة مطلوب";
            }
            break;
            
        case 'edit_slide':
            $slide_id = $_POST['slide_id'];
            $title = trim($_POST['slide_title']);
            $content = trim($_POST['slide_content']);
            $slide_type = $_POST['slide_type'];
            $image_url = trim($_POST['image_url'] ?? '');
            
            if (!empty($title)) {
                try {
                    // Verify that the slide belongs to this teacher's presentation
                    $stmt = $pdo->prepare("
                        SELECT ps.id 
                        FROM presentation_slides ps 
                        JOIN teacher_presentations tp ON ps.presentation_id = tp.id 
                        WHERE ps.id = ? AND tp.teacher_id = ?
                    ");
                    $stmt->execute([$slide_id, $teacher_id]);
                    
                    if ($stmt->fetch()) {
                        $stmt = $pdo->prepare("UPDATE presentation_slides SET title = ?, content = ?, slide_type = ?, image_url = ? WHERE id = ?");
                        $stmt->execute([$title, $content, $slide_type, $image_url, $slide_id]);
                        $success = "تم تحديث الشريحة بنجاح";
                    } else {
                        $error = "الشريحة غير موجودة";
                    }
                } catch (PDOException $e) {
                    $error = "خطأ في تحديث الشريحة: " . $e->getMessage();
                }
            } else {
                $error = "عنوان الشريحة مطلوب";
            }
            break;
            
        case 'delete_slide':
            $slide_id = $_POST['slide_id'];
            
            try {
                // Verify ownership and delete
                $stmt = $pdo->prepare("
                    DELETE ps FROM presentation_slides ps 
                    JOIN teacher_presentations tp ON ps.presentation_id = tp.id 
                    WHERE ps.id = ? AND tp.teacher_id = ?
                ");
                $stmt->execute([$slide_id, $teacher_id]);
                $success = "تم حذف الشريحة بنجاح";
            } catch (PDOException $e) {
                $error = "خطأ في حذف الشريحة: " . $e->getMessage();
            }
            break;
    }
}

// Get all presentations for this teacher
try {
    $stmt = $pdo->prepare("SELECT * FROM teacher_presentations WHERE teacher_id = ? ORDER BY created_at DESC");
    $stmt->execute([$teacher_id]);
    $presentations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $presentations = [];
    $error = "خطأ في جلب العروض التقديمية: " . $e->getMessage();
}

// Get presentation details for editing if requested
$current_presentation = null;
$current_slides = [];
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $presentation_id = $_GET['edit'];
    try {
        // Get presentation details
        $stmt = $pdo->prepare("SELECT * FROM teacher_presentations WHERE id = ? AND teacher_id = ?");
        $stmt->execute([$presentation_id, $teacher_id]);
        $current_presentation = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($current_presentation) {
            // Get slides for this presentation
            $stmt = $pdo->prepare("SELECT * FROM presentation_slides WHERE presentation_id = ? ORDER BY slide_number");
            $stmt->execute([$presentation_id]);
            $current_slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $error = "خطأ في جلب تفاصيل العرض التقديمي: " . $e->getMessage();
    }
}
?>
