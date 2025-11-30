<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأنظمة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #8B0000 0%, #A52A2A 100%);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 15px 20px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        .content-area {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .header-section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .table thead {
            background-color: #8B0000;
            color: white;
        }
        .btn-maroon {
            background-color: #8B0000;
            color: white;
        }
        .btn-maroon:hover {
            background-color: #A52A2A;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
          
           <!-- Sidebar -->
        <?php
// Include session manager to safely start session
include_once("session_manager.php");

// Get current file name
$currentPage = basename($_SERVER['PHP_SELF']);

// Check if user is logged in and get their role
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
?>

<!-- Sidebar -->
<div class="col-md-2 p-0 sidebar">
    <div class="p-3">
        <h4 class="text-center mb-4">القائمة</h4>
        
        <?php if ($userName): ?>
        <div class="text-center mb-3">
            <small class="text-light">مرحباً، <?= htmlspecialchars($userName) ?></small>
            <br>
            <small class="text-light">(<?= $userRole == 'admin' ? 'مدير' : 'معلم' ?>)</small>
        </div>
        <?php endif; ?>
        
        <ul class="nav flex-column">
    <!-- Admin-only links -->
    <?php if ($userRole == 'admin'): ?>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php">
            <i class="bi bi-layers me-2"></i> النظام
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'levels.php' ? 'active' : '' ?>" href="levels.php">
            <i class="bi bi-layers me-2"></i> المستوى
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'programs.php' ? 'active' : '' ?>" href="programs.php">
            <i class="bi bi-book me-2"></i> البرنامج
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'class.php' ? 'active' : '' ?>" href="class.php">
            <i class="bi bi-grid me-2"></i> الصف
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'sections.php' ? 'active' : '' ?>" href="sections.php">
            <i class="bi bi-diagram-3 me-2"></i> الشعبة
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'subjects.php' ? 'active' : '' ?>" href="subjects.php">
            <i class="bi bi-journal-text me-2"></i> المواد
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'students_sections.php' ? 'active' : '' ?>" href="students_sections.php">
            <i class="bi bi-people me-2"></i> الطلاب  
        </a>
    </li>
    <?php elseif ($userRole == 'teacher'): ?>
    <!-- Teacher-only links (limited access) -->
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'teacher_resources.php' ? 'active' : '' ?>" href="teacher_resources.php">
            <i class="bi bi-folder me-2"></i> إدارة الموارد
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'teacher_presentations.php' ? 'active' : '' ?>" href="teacher_presentations.php">
            <i class="bi bi-easel me-2"></i> العروض التقديمية
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'subjects.php' ? 'active' : '' ?>" href="subjects.php">
            <i class="bi bi-journal-text me-2"></i> المواد
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'students_sections.php' ? 'active' : '' ?>" href="students_sections.php">
            <i class="bi bi-people me-2"></i> الطلاب  
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'class.php' ? 'active' : '' ?>" href="class.php">
            <i class="bi bi-grid me-2"></i> الصف
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentPage == 'sections.php' ? 'active' : '' ?>" href="sections.php">
            <i class="bi bi-diagram-3 me-2"></i> الشعبة
        </a>
    </li>
    <?php endif; ?>
    
    <!-- Logout link for all logged-in users -->
    <?php if ($userRole): ?>
    <li class="nav-item mt-3">
        <a class="nav-link text-warning" href="logout.php">
            <i class="bi bi-box-arrow-right me-2"></i> خروج
        </a>
    </li>
    <?php endif; ?>
</ul>

    </div>
</div>
