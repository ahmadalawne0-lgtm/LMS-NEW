<?php
include("Controllers/StudentDashbordController.php");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الطالب</title>
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
            padding: 30px;
        }
        .header-section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card-subject {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .card-subject:hover {
            transform: scale(1.02);
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
 <?php
// Get current file name
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<body>
    <div class="container-fluid">
        <div class="row">
            
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <div class="p-3">
                    <h4 class="text-center mb-4">لوحة الطالب</h4>
                    <ul class="nav flex-column">
                       
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-md-10 content-area">
                <div class="header-section d-flex justify-content-between align-items-center">
                    <h3>مرحبًا، <?= htmlspecialchars($_SESSION['student_name']) ?> 👋</h3>
                    <div>
                        <span class="me-3 fw-bold text-muted">الصف: <?= $info['class_name'] ?> / الشعبة: <?= $info['section_name'] ?></span>
                        <!-- <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right"></i> خروج</a> -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="table-container">
                            <h5 class="mb-3"><i class="bi bi-book me-2"></i> المواد الدراسية</h5>
                            <div class="row">
                                <?php if (!empty($subjects)): ?>
                                    <?php foreach ($subjects as $subject): ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="card card-subject text-center p-3">
                                                <h6 class="fw-bold"><?= htmlspecialchars($subject['name']) ?></h6>
                                                <button class="btn btn-maroon mt-3 w-100">عرض التفاصيل</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted">لا توجد مواد مرتبطة بصفك حاليًا.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- End content -->
        </div>
    </div>
</body>
</html>
