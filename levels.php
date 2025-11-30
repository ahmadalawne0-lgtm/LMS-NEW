<?php
include("Database/database.php");

include("Controllers/LevelsController.php");

include("layout.php");
?>


        <!-- Main Content -->
        <div class="col-md-10 content-area p-4">
            <div class="header-section d-flex justify-content-between align-items-center">
                <h3 class="text-danger mb-0">المستوى</h3>
                <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addLevelModal">
                    <i class="bi bi-plus-circle me-2"></i> إضافة مستوى
                </button>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>تعديل</th>
                            <th>السنة الدراسية</th>
                            <th>اسم المستوى</th>
                            <th>اسم النظام</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($levels as $level): ?>
                        <tr>
                            <td>
                                <button class="btn btn-sm btn-warning me-2" onclick="editLevel(<?php echo htmlspecialchars(json_encode($level)); ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_level">
                                    <input type="hidden" name="id" value="<?php echo $level['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td><?php echo htmlspecialchars($level['academic_year']); ?></td>
                            <td><?php echo htmlspecialchars($level['level_name']); ?></td>
                            <td><?php echo htmlspecialchars($level['system_name']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Level Modal -->
<div class="modal fade" id="addLevelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">إضافة مستوى جديد</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_level">
                    <div class="mb-3">
                        <label class="form-label">اسم النظام</label>
                        <select name="system_id" class="form-select" required>
                            <option value="">اختر النظام</option>
                            <?php foreach($systems as $system): ?>
                                <option value="<?php echo $system['id']; ?>"><?php echo htmlspecialchars($system['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">اسم المستوى</label>
                        <input type="text" name="level_name" class="form-control" required>
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

<!-- Edit Level Modal -->
<div class="modal fade" id="editLevelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">تعديل المستوى</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_level">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">اسم النظام</label>
                        <select name="system_id" id="edit_system_id" class="form-select" required>
                            <?php foreach($systems as $system): ?>
                                <option value="<?php echo $system['id']; ?>"><?php echo htmlspecialchars($system['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">اسم المستوى</label>
                        <input type="text" name="level_name" id="edit_level_name" class="form-control" required>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editLevel(level) {
    document.getElementById('edit_id').value = level.id;
    document.getElementById('edit_system_id').value = level.system_id;
    document.getElementById('edit_level_name').value = level.level_name;
    new bootstrap.Modal(document.getElementById('editLevelModal')).show();
}
</script>
</body>
</html>
