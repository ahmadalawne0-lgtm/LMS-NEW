<?php
include("Controllers/PresentationsController.php");
include("layout.php");
?>

            <!-- Main Content -->
            <div class="col-md-10 content-area p-4">
                <!-- Header -->
                <div class="header-section d-flex justify-content-between align-items-center">
                    <h3 class="text-danger mb-0">إدارة العروض التقديمية</h3>
                    <?php if (!$current_presentation): ?>
                    <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addPresentationModal">
                        <i class="bi bi-plus-circle me-2"></i> إنشاء عرض تقديمي جديد
                    </button>
                    <?php else: ?>
                    <a href="teacher_presentations.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-right me-2"></i> العودة للقائمة
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Success/Error Messages -->
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!$current_presentation): ?>
                <!-- Presentations List -->
                <div class="row">
                    <?php foreach($presentations as $presentation): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-maroon text-white" style="background-color: #8B0000;">
                                <h6 class="mb-0"><i class="bi bi-easel me-2"></i><?= htmlspecialchars($presentation['title']) ?></h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($presentation['description'])): ?>
                                    <p class="card-text text-muted small"><?= htmlspecialchars($presentation['description']) ?></p>
                                <?php endif; ?>
                                
                                <?php
                                // Get slide count for this presentation
                                $stmt = $pdo->prepare("SELECT COUNT(*) as slide_count FROM presentation_slides WHERE presentation_id = ?");
                                $stmt->execute([$presentation['id']]);
                                $slide_count = $stmt->fetch()['slide_count'];
                                ?>
                                
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-collection me-1"></i>
                                    <?= $slide_count ?> شريحة
                                </p>
                                
                                <div class="d-grid gap-2">
                                    <a href="teacher_presentations.php?edit=<?= $presentation['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil me-2"></i>تحرير الشرائح
                                    </a>
                                    <a href="presentation_viewer.php?id=<?= $presentation['id'] ?>" class="btn btn-success btn-sm" target="_blank">
                                        <i class="bi bi-play-circle me-2"></i>عرض التقديم
                                    </a>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-warning" onclick="editPresentation(<?= htmlspecialchars(json_encode($presentation)) ?>)">
                                        <i class="bi bi-gear"></i> إعدادات
                                    </button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="delete_presentation">
                                        <input type="hidden" name="presentation_id" value="<?= $presentation['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا العرض التقديمي وجميع شرائحه؟')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                <small class="text-muted">تم الإنشاء: <?= date('Y-m-d H:i', strtotime($presentation['created_at'])) ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($presentations)): ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-easel" style="font-size: 4rem; color: #ccc;"></i>
                            <h4 class="text-muted mt-3">لا توجد عروض تقديمية حتى الآن</h4>
                            <p class="text-muted">ابدأ بإنشاء عرض تقديمي جديد لطلابك</p>
                            <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addPresentationModal">
                                <i class="bi bi-plus-circle me-2"></i> إنشاء عرض تقديمي جديد
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <?php else: ?>
                <!-- Slide Editor -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-easel me-2"></i>
                            تحرير العرض التقديمي: <?= htmlspecialchars($current_presentation['title']) ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6>الشرائح (<?= count($current_slides) ?>)</h6>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSlideModal">
                                <i class="bi bi-plus-circle me-2"></i>إضافة شريحة
                            </button>
                        </div>
                        
                        <div class="row">
                            <?php foreach($current_slides as $slide): ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border">
                                    <div class="card-header d-flex justify-content-between align-items-center p-2">
                                        <small class="text-muted">شريحة <?= $slide['slide_number'] ?></small>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="editSlide(<?= htmlspecialchars(json_encode($slide)) ?>)">تعديل</a></li>
                                                <li>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="action" value="delete_slide">
                                                        <input type="hidden" name="slide_id" value="<?= $slide['id'] ?>">
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الشريحة؟')">حذف</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <h6 class="card-title small"><?= htmlspecialchars($slide['title']) ?></h6>
                                        <p class="card-text small text-muted">
                                            <span class="badge bg-secondary"><?= ucfirst(str_replace('_', ' ', $slide['slide_type'])) ?></span>
                                        </p>
                                        <?php if (!empty($slide['content'])): ?>
                                            <div class="small text-truncate">
                                                <?= nl2br(htmlspecialchars(substr($slide['content'], 0, 100))) ?>
                                                <?= strlen($slide['content']) > 100 ? '...' : '' ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($slide['image_url'])): ?>
                                            <small class="text-info"><i class="bi bi-image"></i> يحتوي على صورة</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <?php if (empty($current_slides)): ?>
                            <div class="col-12 text-center py-4">
                                <i class="bi bi-collection" style="font-size: 3rem; color: #ccc;"></i>
                                <h5 class="text-muted mt-2">لا توجد شرائح حتى الآن</h5>
                                <p class="text-muted">ابدأ بإضافة شرائح لعرضك التقديمي</p>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSlideModal">
                                    <i class="bi bi-plus-circle me-2"></i>إضافة أول شريحة
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add Presentation Modal -->
    <div class="modal fade" id="addPresentationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">إنشاء عرض تقديمي جديد</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_presentation">
                        <div class="mb-3">
                            <label class="form-label">عنوان العرض التقديمي</label>
                            <input type="text" name="title" class="form-control" placeholder="مثال: مقدمة في علم الأحياء" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوصف (اختياري)</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="وصف مختصر عن العرض التقديمي"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-maroon">إنشاء العرض</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Presentation Modal -->
    <div class="modal fade" id="editPresentationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">تعديل العرض التقديمي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit_presentation">
                        <input type="hidden" name="presentation_id" id="edit_presentation_id">
                        <div class="mb-3">
                            <label class="form-label">عنوان العرض التقديمي</label>
                            <input type="text" name="title" id="edit_presentation_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوصف (اختياري)</label>
                            <textarea name="description" id="edit_presentation_description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-warning">تحديث العرض</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Slide Modal -->
    <div class="modal fade" id="addSlideModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">إضافة شريحة جديدة</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_slide">
                        <input type="hidden" name="presentation_id" value="<?= $current_presentation['id'] ?? '' ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">عنوان الشريحة</label>
                                    <input type="text" name="slide_title" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">نوع الشريحة</label>
                                    <select name="slide_type" class="form-control">
                                        <option value="title_slide">شريحة عنوان</option>
                                        <option value="text">نص فقط</option>
                                        <option value="bullet_points">نقاط</option>
                                        <option value="text_image">نص مع صورة</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">المحتوى</label>
                            <textarea name="slide_content" class="form-control" rows="4" placeholder="اكتب محتوى الشريحة هنا..."></textarea>
                            <small class="form-text text-muted">للنقاط، اكتب كل نقطة في سطر منفصل</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">رابط الصورة (اختياري)</label>
                            <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success">إضافة الشريحة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Slide Modal -->
    <div class="modal fade" id="editSlideModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">تعديل الشريحة</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit_slide">
                        <input type="hidden" name="slide_id" id="edit_slide_id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">عنوان الشريحة</label>
                                    <input type="text" name="slide_title" id="edit_slide_title" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">نوع الشريحة</label>
                                    <select name="slide_type" id="edit_slide_type" class="form-control">
                                        <option value="title_slide">شريحة عنوان</option>
                                        <option value="text">نص فقط</option>
                                        <option value="bullet_points">نقاط</option>
                                        <option value="text_image">نص مع صورة</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">المحتوى</label>
                            <textarea name="slide_content" id="edit_slide_content" class="form-control" rows="4"></textarea>
                            <small class="form-text text-muted">للنقاط، اكتب كل نقطة في سطر منفصل</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">رابط الصورة (اختياري)</label>
                            <input type="url" name="image_url" id="edit_slide_image_url" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-info">تحديث الشريحة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editPresentation(presentation) {
            document.getElementById('edit_presentation_id').value = presentation.id;
            document.getElementById('edit_presentation_title').value = presentation.title;
            document.getElementById('edit_presentation_description').value = presentation.description || '';
            new bootstrap.Modal(document.getElementById('editPresentationModal')).show();
        }

        function editSlide(slide) {
            document.getElementById('edit_slide_id').value = slide.id;
            document.getElementById('edit_slide_title').value = slide.title;
            document.getElementById('edit_slide_type').value = slide.slide_type;
            document.getElementById('edit_slide_content').value = slide.content || '';
            document.getElementById('edit_slide_image_url').value = slide.image_url || '';
            new bootstrap.Modal(document.getElementById('editSlideModal')).show();
        }
    </script>
</body>
</html>