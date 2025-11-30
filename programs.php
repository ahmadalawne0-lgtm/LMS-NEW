<?php
include("Database/database.php");
include("Controllers/ProgramsController.php");
include("layout.php");
?>

<div class="col-md-10 content-area p-4">
    <div class="header-section d-flex justify-content-between align-items-center">
        <h3 class="text-danger mb-0">البرامج</h3>
        <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addProgramModal">
            <i class="bi bi-plus-circle me-2"></i> إضافة برنامج
        </button>
    </div>

    <!-- Table -->
    <div class="table-container mt-4">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>تعديل</th>
                    <th>اسم المستوى</th>
                    <th>اسم البرنامج</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($programs as $program): ?>
                <tr>
                    <td>
                        <button class="btn btn-sm btn-warning me-2" onclick="editProgram(<?php echo htmlspecialchars(json_encode($program)); ?>)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete_program">
                            <input type="hidden" name="id" value="<?php echo $program['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                    <td><?php echo htmlspecialchars($program['level_name']); ?></td>
                    <td><?php echo htmlspecialchars($program['program_name']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Program Modal -->
<div class="modal fade" id="addProgramModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">إضافة برنامج جديد</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_program">
                    <div class="mb-3">
                        <label class="form-label">اسم المستوى</label>
                        <select name="level_id" class="form-select" required>
                            <option value="">اختر المستوى</option>
                            <?php foreach($levels as $level): ?>
                                <option value="<?php echo $level['id']; ?>"><?php echo htmlspecialchars($level['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">اسم البرنامج</label>
                        <input type="text" name="program_name" class="form-control" required>
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

<!-- Edit Program Modal -->
<div class="modal fade" id="editProgramModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">تعديل البرنامج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_program">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">اسم المستوى</label>
                        <select name="level_id" id="edit_level_id" class="form-select" required>
                            <?php foreach($levels as $level): ?>
                                <option value="<?php echo $level['id']; ?>"><?php echo htmlspecialchars($level['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">اسم البرنامج</label>
                        <input type="text" name="program_name" id="edit_program_name" class="form-control" required>
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
function editProgram(program) {
    document.getElementById('edit_id').value = program.id;
    document.getElementById('edit_level_id').value = program.level_id;
    document.getElementById('edit_program_name').value = program.program_name;
    new bootstrap.Modal(document.getElementById('editProgramModal')).show();
}

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
