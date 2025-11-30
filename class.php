


<?php
include("Database/database.php");
include("Controllers/classController.php");
include("layout.php");

?>

<!-- Main Content -->
<div class="col-md-10 content-area p-4">
    <div class="header-section d-flex justify-content-between align-items-center">
        <h3 class="text-danger mb-0">إدارة الصفوف</h3>
        <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addClassModal">
            <i class="bi bi-plus-lg"></i> إضافة صف
        </button>
    </div>

    <div class="table-container">
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الصف</th>
                    <th>البرنامج</th>
                    <th>المستوى</th>
                    <th>النظام</th>
                    <th>المدرس</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($classes)): ?>
                    <?php foreach($classes as $index => $class): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($class['class_name']) ?></td>
                            <td><?= htmlspecialchars($class['program_name']) ?></td>
                            <td><?= htmlspecialchars($class['level_name']) ?></td>
                            <td><?= htmlspecialchars($class['system_name']) ?></td>
                            <td><?= htmlspecialchars($class['teacher_name'] ?? '-') ?></td>
                            <td>
                                <button 
                                    class="btn btn-sm btn-warning edit-btn" 
                                    data-id="<?= $class['id'] ?>"
                                    data-class_name="<?= htmlspecialchars($class['class_name']) ?>"
                                    data-program_id="<?= $class['program_id'] ?>"
                                    data-teacher_id="<?= $class['teacher_id'] ?? '' ?>"
                                    data-bs-toggle="modal" data-bs-target="#editClassModal"
                                >تعديل</button>

                                <form action="" method="POST" class="d-inline">
                                    <input type="hidden" name="action" value="delete_class">
                                    <input type="hidden" name="id" value="<?= $class['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">لا توجد بيانات</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal (unchanged) -->
<div class="modal fade" id="addClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة صف جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="add_class">
                <div class="mb-3">
                    <label>اسم الصف</label>
                    <input type="text" name="class_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>البرنامج</label>
                    <select name="program_id" class="form-select" required>
                        <?php foreach($programs as $program): ?>
                            <option value="<?= $program['id'] ?>"><?= htmlspecialchars($program['program_name']) ?> - <?= htmlspecialchars($program['level_name']) ?> - <?= htmlspecialchars($program['system_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>المدرس</label>
                    <select name="teacher_id" class="form-select">
                        <option value="">بدون مدرس</option>
                        <?php foreach($teachers as $teacher): ?>
                            <option value="<?= $teacher['id'] ?>"><?= htmlspecialchars($teacher['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-maroon">إضافة</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal (single modal for all rows) -->
<div class="modal fade" id="editClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل الصف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="edit_class">
                <input type="hidden" name="id" id="edit-id">
                <div class="mb-3">
                    <label>اسم الصف</label>
                    <input type="text" name="class_name" id="edit-class_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>البرنامج</label>
                    <select name="program_id" id="edit-program_id" class="form-select" required>
                        <?php foreach($programs as $program): ?>
                            <option value="<?= $program['id'] ?>"><?= htmlspecialchars($program['program_name']) ?> - <?= htmlspecialchars($program['level_name']) ?> - <?= htmlspecialchars($program['system_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>المدرس</label>
                    <select name="teacher_id" id="edit-teacher_id" class="form-select">
                        <option value="">بدون مدرس</option>
                        <?php foreach($teachers as $teacher): ?>
                            <option value="<?= $teacher['id'] ?>"><?= htmlspecialchars($teacher['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-maroon">حفظ التعديلات</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fill edit modal dynamically
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-class_name').value = this.dataset.class_name;
            document.getElementById('edit-program_id').value = this.dataset.program_id;
            document.getElementById('edit-teacher_id').value = this.dataset.teacher_id;
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
