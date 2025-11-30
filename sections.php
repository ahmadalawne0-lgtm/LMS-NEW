
<?php
include("Database/database.php");
 include "Controllers/sectionsController.php";


include("layout.php");
?>

<!-- Main Content -->
<div class="col-md-10 content-area p-4">
    <div class="header-section d-flex justify-content-between align-items-center">
        <h3 class="text-danger mb-0">إدارة الشعب</h3>
        <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addSectionModal">
            <i class="bi bi-plus-lg"></i> إضافة شعبة
        </button>
    </div>
    <div class="table-container">
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الشعبة</th>
                    <th>الصف</th>
                    <th>المستوى</th>
                    <th>النظام</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($sections as $index => $section): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($section['section_name']) ?></td>
                    <td><?= htmlspecialchars($section['class_name']) ?></td>
                    <td><?= htmlspecialchars($section['level_name']) ?></td>
                    <td><?= htmlspecialchars($section['system_name']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editSection(<?= htmlspecialchars(json_encode($section)) ?>)">تعديل</button>
                        <form action="" method="POST" class="d-inline">
                            <input type="hidden" name="action" value="delete_section">
                            <input type="hidden" name="id" value="<?= $section['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الشعبة؟')">حذف</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة شعبة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="add_section">
                <div class="mb-3">
                    <label>اسم الشعبة</label>
                    <input type="text" name="section_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>الصف</label>
                    <select name="class_id" class="form-select" required>
                        <?php foreach($classes as $class): ?>
                            <option value="<?= $class['id'] ?>">
                                <?= htmlspecialchars($class['name']) ?> - <?= htmlspecialchars($class['level_name']) ?> - <?= htmlspecialchars($class['system_name']) ?>
                            </option>
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

<!-- Edit Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل الشعبة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="edit_section">
                <input type="hidden" name="id" id="edit_section_id">
                <div class="mb-3">
                    <label>اسم الشعبة</label>
                    <input type="text" name="section_name" id="edit_section_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>الصف</label>
                    <select name="class_id" id="edit_class_id" class="form-select" required>
                        <?php foreach($classes as $class): ?>
                            <option value="<?= $class['id'] ?>">
                                <?= htmlspecialchars($class['name']) ?> - <?= htmlspecialchars($class['level_name']) ?> - <?= htmlspecialchars($class['system_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-warning">حفظ التعديلات</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editSection(section) {
    // Populate the edit modal with section data
    document.getElementById('edit_section_id').value = section.id;
    document.getElementById('edit_section_name').value = section.section_name;
    document.getElementById('edit_class_id').value = section.class_id;
    
    // Show the modal
    new bootstrap.Modal(document.getElementById('editSectionModal')).show();
}
</script>

</body>
</html>