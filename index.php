<?php
require_once("auth_check.php");

// Check authentication - require admin or teacher
requireAdminOrTeacher();

include("Database/database.php");
?>

<?php include("Controllers/SystemsController.php");
include("layout.php");?>
            <!-- Main Content -->
            <div class="col-md-10 content-area p-4">
                <!-- Header -->
                <div class="header-section d-flex justify-content-between align-items-center">
                    <h3 class="text-danger mb-0">
                        <?php if ($_SESSION['user_role'] == 'admin'): ?>
                            النظام
                        <?php else: ?>
                            لوحة التحكم - المعلم
                        <?php endif; ?>
                    </h3>
                    <?php if ($_SESSION['user_role'] == 'admin'): ?>
                    <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addSystemModal">
                        <i class="bi bi-plus-circle me-2"></i> إضافة نظام
                    </button>
                    <?php else: ?>
                    <div class="text-muted">
                        <small>مرحباً <?= htmlspecialchars($_SESSION['user_name']) ?></small>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Content based on user role -->
                <?php if ($_SESSION['user_role'] == 'admin'): ?>
                <!-- Admin: Show Systems Management -->
                <div class="table-container">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>اسم النظام</th>
                                <th>السنة الدراسية</th>
                                <th>العمليات</th>
                                
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($systems as $system): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($system['name']); ?></td>
                                <td><?php echo htmlspecialchars($system['academic_year']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-2" onclick="editSystem(<?php echo htmlspecialchars(json_encode($system)); ?>)">
                                        تعديل <i class="bi bi-pencil ms-1"></i>
                                    </button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="delete_system">
                                        <input type="hidden" name="id" value="<?php echo $system['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                
                                
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <!-- Teacher: Show Dashboard Overview -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-folder me-2"></i>إدارة الموارد</h5>
                                <p class="card-text">إنشاء مجلدات وإضافة روابط للملفات والامتحانات</p>
                                <a href="teacher_resources.php" class="btn btn-maroon">إدارة الموارد</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-easel me-2"></i>العروض التقديمية</h5>
                                <p class="card-text">إنشاء وتحرير العروض التقديمية التفاعلية</p>
                                <a href="teacher_presentations.php" class="btn btn-maroon">إدارة العروض</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-journal-text me-2"></i>المواد</h5>
                                <p class="card-text">إدارة المواد الدراسية</p>
                                <a href="subjects.php" class="btn btn-maroon">عرض المواد</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-people me-2"></i>الطلاب</h5>
                                <p class="card-text">عرض قوائم الطلاب</p>
                                <a href="students_sections.php" class="btn btn-maroon">عرض الطلاب</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-grid me-2"></i>الصفوف</h5>
                                <p class="card-text">عرض الصفوف الدراسية</p>
                                <a href="class.php" class="btn btn-maroon">عرض الصفوف</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-diagram-3 me-2"></i>الشعب</h5>
                                <p class="card-text">عرض الشعب الدراسية</p>
                                <a href="sections.php" class="btn btn-maroon">عرض الشعب</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Admin-only Modals -->
    <?php if ($_SESSION['user_role'] == 'admin'): ?>
    <!-- Add System Modal -->
    <div class="modal fade" id="addSystemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">إضافة نظام جديد</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_system">
                        <div class="mb-3">
                            <label class="form-label">اسم النظام</label>
                            <input type="text" name="system_name" class="form-control" placeholder="النظام الوطني" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">السنة الدراسية</label>
                            <input type="text" name="academic_year" class="form-control" placeholder="2025/2026" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-maroon">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit System Modal -->
    <div class="modal fade" id="editSystemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">تعديل النظام</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit_system">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">اسم النظام</label>
                            <input type="text" name="system_name" id="edit_system_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">السنة الدراسية</label>
                            <input type="text" name="academic_year" id="edit_academic_year" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-warning">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($_SESSION['user_role'] == 'admin'): ?>
    <script>
        function editSystem(system) {
            document.getElementById('edit_id').value = system.id;
            document.getElementById('edit_system_name').value = system.name;
            document.getElementById('edit_academic_year').value = system.academic_year;
            new bootstrap.Modal(document.getElementById('editSystemModal')).show();
        }
    </script>
    <?php endif; ?>
</body>
</html>