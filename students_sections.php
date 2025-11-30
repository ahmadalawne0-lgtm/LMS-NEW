<?php
include("Database/database.php");
include("Controllers/StudentsSectionsController.php");
include("layout.php");
?>

<div class="col-md-10 content-area p-4">
    <div class="header-section d-flex justify-content-between align-items-center">
        <h3 class="text-danger mb-0">إدارة الطلاب في الشعب</h3>
    </div>

    <!-- جدول الطلاب -->
    <div class="table-container mt-4">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الطالب</th>
                    <th>الشعبة</th>
                    <th>الصف</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($students as $index => $student): ?>
                <?php
                    // تحقق إذا كان الطالب موجود في شعبة
                    $ss = $section_students_map[$student['id']] ?? null;
                ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($ss['section_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($ss['class_name'] ?? '-') ?></td>
                    <td>
                        <?php if($ss): ?>
                            <!-- زر إزالة الطالب من الشعبة -->
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="remove_student_from_section">
                                <input type="hidden" name="id" value="<?= $ss['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟')">إزالة</button>
                            </form>
                        <?php else: ?>
                            <!-- زر إضافة الطالب لشعبة -->
                            <button class="btn btn-sm btn-maroon" data-bs-toggle="modal" data-bs-target="#addStudentModal" 
                                data-student_id="<?= $student['id'] ?>" 
                                data-student_name="<?= htmlspecialchars($student['name']) ?>">
                                إضافة إلى شعبة
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- مودال إضافة طالب -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">إضافة طالب إلى شعبة</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="add_student_to_section">
                <input type="hidden" name="student_id" id="modal-student_id">

                <div class="mb-3">
                    <label>اختر الشعبة</label>
                    <select name="section_id" class="form-select" required>
                        <option value="">اختر الشعبة</option>
                        <?php foreach($sections as $section): ?>
                            <option value="<?= $section['id'] ?>"><?= htmlspecialchars($section['class_name']) ?> - <?= htmlspecialchars($section['section_name']) ?></option>
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

<script>
    // تمرير بيانات الطالب للمودال عند الضغط على زر "إضافة إلى شعبة"
    const addModal = document.getElementById('addStudentModal');
    addModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const studentId = button.getAttribute('data-student_id');
        document.getElementById('modal-student_id').value = studentId;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
