<?php
require_once 'auth_check.php';
require 'Database/database.php';

requireTeacher();

$teacher_id = $_SESSION['user_id'];
$presentation_id = $_GET['id'] ?? 0;

// Get presentation details
try {
    $stmt = $pdo->prepare("SELECT * FROM teacher_presentations WHERE id = ? AND teacher_id = ?");
    $stmt->execute([$presentation_id, $teacher_id]);
    $presentation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$presentation) {
        header('Location: teacher_presentations.php');
        exit;
    }
    
    // Get slides for this presentation
    $stmt = $pdo->prepare("SELECT * FROM presentation_slides WHERE presentation_id = ? ORDER BY slide_number");
    $stmt->execute([$presentation_id]);
    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    header('Location: teacher_presentations.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($presentation['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .presentation-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .slide {
            min-height: 600px;
            padding: 60px;
            display: none;
            position: relative;
        }
        
        .slide.active {
            display: block;
        }
        
        .slide-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .slide-content {
            font-size: 1.3rem;
            line-height: 1.8;
            color: #555;
        }
        
        .slide-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .bullet-points {
            list-style: none;
            padding: 0;
        }
        
        .bullet-points li {
            font-size: 1.4rem;
            margin: 15px 0;
            padding-right: 30px;
            position: relative;
        }
        
        .bullet-points li:before {
            content: "•";
            color: #8B0000;
            font-weight: bold;
            position: absolute;
            right: 0;
            font-size: 1.6rem;
        }
        
        .title-slide {
            background: linear-gradient(135deg, #8B0000 0%, #A52A2A 100%);
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .title-slide .slide-title {
            color: white;
            font-size: 3.5rem;
            margin-bottom: 20px;
        }
        
        .text-image-slide {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }
        
        .controls {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.8);
            padding: 15px 25px;
            border-radius: 50px;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .controls button {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 8px 15px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .controls button:hover {
            background: white;
            color: #333;
        }
        
        .controls button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .slide-counter {
            color: white;
            font-weight: bold;
            margin: 0 15px;
        }
        
        .slide-number {
            position: absolute;
            bottom: 20px;
            left: 30px;
            color: #999;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="presentation-container mt-4 mb-4">
        <?php foreach($slides as $index => $slide): ?>
        <div class="slide <?= $index === 0 ? 'active' : '' ?> <?= $slide['slide_type'] === 'title_slide' ? 'title-slide' : '' ?>" data-slide="<?= $index ?>">
            <?php if ($slide['slide_type'] === 'text_image' && !empty($slide['image_url'])): ?>
            <div class="text-image-slide">
                <div>
                    <h2 class="slide-title"><?= htmlspecialchars($slide['title']) ?></h2>
                    <div class="slide-content"><?= nl2br(htmlspecialchars($slide['content'])) ?></div>
                </div>
                <div>
                    <img src="<?= htmlspecialchars($slide['image_url']) ?>" alt="<?= htmlspecialchars($slide['title']) ?>" class="slide-image">
                </div>
            </div>
            <?php else: ?>
            <h2 class="slide-title"><?= htmlspecialchars($slide['title']) ?></h2>
            
            <?php if ($slide['slide_type'] === 'bullet_points'): ?>
            <ul class="bullet-points">
                <?php 
                $points = explode("\n", $slide['content']);
                foreach($points as $point): 
                    $point = trim($point);
                    if (!empty($point)):
                ?>
                <li><?= htmlspecialchars($point) ?></li>
                <?php 
                    endif;
                endforeach; 
                ?>
            </ul>
            <?php elseif (!empty($slide['content'])): ?>
            <div class="slide-content"><?= nl2br(htmlspecialchars($slide['content'])) ?></div>
            <?php endif; ?>
            
            <?php if ($slide['slide_type'] !== 'text_image' && !empty($slide['image_url'])): ?>
            <div class="text-center">
                <img src="<?= htmlspecialchars($slide['image_url']) ?>" alt="<?= htmlspecialchars($slide['title']) ?>" class="slide-image">
            </div>
            <?php endif; ?>
            <?php endif; ?>
            
            <div class="slide-number"><?= $index + 1 ?> / <?= count($slides) ?></div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($slides)): ?>
        <div class="slide active">
            <div class="text-center py-5">
                <h3 class="text-muted">لا توجد شرائح في هذا العرض التقديمي</h3>
                <a href="teacher_presentations.php?edit=<?= $presentation_id ?>" class="btn btn-primary">إضافة شرائح</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($slides)): ?>
    <div class="controls">
        <button id="prevBtn" onclick="changeSlide(-1)">
            <i class="bi bi-chevron-right"></i> السابق
        </button>
        <span class="slide-counter">
            <span id="currentSlide">1</span> / <?= count($slides) ?>
        </span>
        <button id="nextBtn" onclick="changeSlide(1)">
            التالي <i class="bi bi-chevron-left"></i>
        </button>
        <button onclick="toggleFullscreen()" title="ملء الشاشة">
            <i class="bi bi-fullscreen"></i>
        </button>
        <a href="teacher_presentations.php" class="btn btn-sm btn-outline-light">
            <i class="bi bi-x-circle"></i> إغلاق
        </a>
    </div>
    <?php endif; ?>

    <script>
        let currentSlideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            if (slides[index]) {
                slides[index].classList.add('active');
                document.getElementById('currentSlide').textContent = index + 1;
                
                // Update button states
                document.getElementById('prevBtn').disabled = index === 0;
                document.getElementById('nextBtn').disabled = index === totalSlides - 1;
            }
        }

        function changeSlide(direction) {
            const newIndex = currentSlideIndex + direction;
            if (newIndex >= 0 && newIndex < totalSlides) {
                currentSlideIndex = newIndex;
                showSlide(currentSlideIndex);
            }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                changeSlide(1); // Next slide (reversed for RTL)
            } else if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                changeSlide(-1); // Previous slide (reversed for RTL)
            } else if (e.key === 'Escape') {
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                }
            }
        });

        // Initialize
        showSlide(0);
    </script>
</body>
</html>