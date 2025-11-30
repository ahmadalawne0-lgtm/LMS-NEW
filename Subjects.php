<?php
include("Database/database.php");
include("Controllers/SubjectsController.php");
include("layout.php");
?>

<div class="col-md-10 content-area p-4">
    <div class="header-section d-flex justify-content-between align-items-center">
        <h3 class="text-danger mb-0">المواد</h3>
        <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            <i class="bi bi-plus-circle me-2"></i> إضافة مادة
        </button>
    </div>

    <!-- Table -->
    <div class="table-container mt-4">
        <table class="table table-hover">
            <thead>
    <tr>
      <th>اسم المادة</th>
        <th>الصف</th>
        <th>البرنامج</th>
       
           <th>تعديل</th>
    </tr>
</thead>
<tbody>
    <?php foreach($subjects as $subject): ?>
    <tr>
        <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
        
        <td><?php echo htmlspecialchars($subject['class_name']); ?></td>
        <td><?php echo htmlspecialchars($subject['program_name']); ?></td>
        <td>
            <button class="btn btn-sm btn-warning me-2" onclick="editSubject(<?php echo htmlspecialchars(json_encode($subject)); ?>)">
                <i class="bi bi-pencil"></i>
            </button>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="delete_subject">
                <input type="hidden" name="id" value="<?php echo $subject['id']; ?>">
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
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">إضافة مادة جديدة</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_subject">
                    
                    <div class="mb-3">
                        <label class="form-label">الصف</label>
                        <select name="class_id" class="form-select" required>
                            <option value="">اختر الصف</option>
                            <?php foreach($classes as $class): ?>
                                <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">اسم المادة</label>
                        <input type="text" name="subject_name" class="form-control" required>
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

<!-- Edit Subject Modal -->
<div class="modal fade" id="editSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">تعديل المادة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_subject">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="mb-3">
                        <label class="form-label">الصف</label>
                        <select name="class_id" id="edit_class_id" class="form-select" required>
                            <?php foreach($classes as $class): ?>
                                <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">اسم المادة</label>
                        <input type="text" name="subject_name" id="edit_subject_name" class="form-control" required>
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

<script>
function editSubject(subject) {
    document.getElementById('edit_id').value = subject.id;
    document.getElementById('edit_class_id').value = subject.class_id;
    document.getElementById('edit_subject_name').value = subject.subject_name;
    new bootstrap.Modal(document.getElementById('editSubjectModal')).show();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
