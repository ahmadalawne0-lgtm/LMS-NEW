<?php
include("Controllers/ResourcesController.php");
include("layout.php");
?>

<style>
    .folder-accordion {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 16px;
        overflow: hidden;
    }
    
    .folder-header {
        padding: 20px;
        background: white;
        border-bottom: 1px solid #e0e0e0;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s;
    }
    
    .folder-header:hover {
        background: #f8f9fa;
    }
    
    .folder-title {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }
    
    .folder-title h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .folder-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .collapse-icon {
        transition: transform 0.3s;
        color: #666;
    }
    
    .folder-header[aria-expanded="true"] .collapse-icon {
        transform: rotate(180deg);
    }
    
    .folder-content {
        padding: 0;
    }
    
    .link-item {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s;
    }
    
    .link-item:last-child {
        border-bottom: none;
    }
    
    .link-item:hover {
        background: #f8f9fa;
    }
    
    .link-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }
    
    .link-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 20px;
    }
    
    .link-icon.drive { background: #e3f2fd; color: #1976d2; }
    .link-icon.exam { background: #fff3e0; color: #f57c00; }
    .link-icon.document { background: #f3e5f5; color: #7b1fa2; }
    .link-icon.video { background: #fce4ec; color: #c2185b; }
    .link-icon.other { background: #e8f5e9; color: #388e3c; }
    
    .link-details h6 {
        margin: 0 0 4px 0;
        font-size: 15px;
        font-weight: 500;
        color: #333;
    }
    
    .link-details small {
        color: #666;
        font-size: 13px;
    }
    
    .empty-folder {
        padding: 40px 20px;
        text-align: center;
        color: #999;
    }
    
    .btn-icon-only {
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }
</style>

<!-- Main Content -->
<div class="col-md-10 content-area p-4">
    <!-- Header -->
    <div class="header-section d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-danger mb-0">إدارة الموارد والملفات</h3>
        <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addFolderModal">
            <i class="bi bi-folder-plus me-2"></i> إنشاء مجلد جديد
        </button>
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

    <!-- Folders List -->
    <div class="folders-container">
        <?php foreach($folders as $folder): ?>
        <div class="folder-accordion">
            <div class="folder-header" data-bs-toggle="collapse" data-bs-target="#folder-<?= $folder['id'] ?>" aria-expanded="false">
                <div class="folder-title">
                    <i class="bi bi-chevron-down collapse-icon"></i>
                    <h5><?= htmlspecialchars($folder['folder_name']) ?></h5>
                </div>
                <div class="folder-actions" onclick="event.stopPropagation();">
                    <button class="btn btn-sm btn-outline-secondary btn-icon-only" onclick="addLinkToFolder(<?= $folder['id'] ?>, '<?= htmlspecialchars($folder['folder_name']) ?>')">
                        <i class="bi bi-plus"></i>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary btn-icon-only" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" onclick="editFolder(<?= htmlspecialchars(json_encode($folder)) ?>)">
                                <i class="bi bi-pencil me-2"></i>تعديل المجلد
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_folder">
                                    <input type="hidden" name="folder_id" value="<?= $folder['id'] ?>">
                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('هل أنت متأكد من حذف المجلد وجميع الروابط الموجودة به؟')">
                                        <i class="bi bi-trash me-2"></i>حذف المجلد
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div id="folder-<?= $folder['id'] ?>" class="collapse">
                <div class="folder-content">
                    <?php if (isset($links_by_folder[$folder['id']]) && !empty($links_by_folder[$folder['id']])): ?>
                        <?php foreach($links_by_folder[$folder['id']] as $link): ?>
                        <div class="link-item">
                            <div class="link-info">
                                <div class="link-icon <?= $link['link_type'] ?>">
                                    <i class="bi bi-<?= getLinkIcon($link['link_type']) ?>"></i>
                                </div>
                                <div class="link-details">
                                    <h6>
                                        <a href="<?= htmlspecialchars($link['link_url']) ?>" target="_blank" class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($link['link_title']) ?>
                                        </a>
                                    </h6>
                                    <?php if (!empty($link['description'])): ?>
                                        <small><?= htmlspecialchars($link['description']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary btn-icon-only" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= htmlspecialchars($link['link_url']) ?>" target="_blank">
                                        <i class="bi bi-box-arrow-up-right me-2"></i>فتح الرابط
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="editLink(<?= htmlspecialchars(json_encode($link)) ?>)">
                                        <i class="bi bi-pencil me-2"></i>تعديل
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="delete_link">
                                            <input type="hidden" name="link_id" value="<?= $link['id'] ?>">
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الرابط؟')">
                                                <i class="bi bi-trash me-2"></i>حذف
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-folder">
                            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                            <p class="mt-3 mb-2">لا توجد روابط في هذا المجلد</p>
                            <button class="btn btn-sm btn-success" onclick="addLinkToFolder(<?= $folder['id'] ?>, '<?= htmlspecialchars($folder['folder_name']) ?>')">
                                <i class="bi bi-plus me-2"></i>إضافة أول رابط
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($folders)): ?>
        <div class="text-center py-5">
            <i class="bi bi-folder-x" style="font-size: 4rem; color: #ccc;"></i>
            <h4 class="text-muted mt-3">لا توجد مجلدات حتى الآن</h4>
            <p class="text-muted">ابدأ بإنشاء مجلد جديد لتنظيم ملفاتك وروابطك</p>
            <button class="btn btn-maroon" data-bs-toggle="modal" data-bs-target="#addFolderModal">
                <i class="bi bi-folder-plus me-2"></i> إنشاء مجلد جديد
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Folder Modal -->
<div class="modal fade" id="addFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">إنشاء مجلد جديد</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_folder">
                    <div class="mb-3">
                        <label class="form-label">اسم المجلد</label>
                        <input type="text" name="folder_name" class="form-control" placeholder="مثال: الرياضيات - الصف الأول" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف (اختياري)</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="وصف مختصر عن محتويات المجلد"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-maroon">إنشاء المجلد</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Folder Modal -->
<div class="modal fade" id="editFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">تعديل المجلد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_folder">
                    <input type="hidden" name="folder_id" id="edit_folder_id">
                    <div class="mb-3">
                        <label class="form-label">اسم المجلد</label>
                        <input type="text" name="folder_name" id="edit_folder_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف (اختياري)</label>
                        <textarea name="description" id="edit_folder_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">تحديث المجلد</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Link Modal -->
<div class="modal fade" id="addLinkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">إضافة رابط جديد</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_link">
                    <input type="hidden" name="folder_id" id="add_link_folder_id">
                    <div class="mb-3">
                        <label class="form-label">المجلد</label>
                        <input type="text" id="add_link_folder_name" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">عنوان الرابط</label>
                        <input type="text" name="link_title" class="form-control" placeholder="مثال: كتاب الرياضيات" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الرابط</label>
                        <input type="url" name="link_url" class="form-control" placeholder="https://drive.google.com/..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نوع الرابط</label>
                        <select name="link_type" class="form-control">
                            <option value="drive">Google Drive</option>
                            <option value="exam">امتحان</option>
                            <option value="document">مستند</option>
                            <option value="video">فيديو</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف (اختياري)</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="وصف مختصر عن الملف"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">إضافة الرابط</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Link Modal -->
<div class="modal fade" id="editLinkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">تعديل الرابط</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_link">
                    <input type="hidden" name="link_id" id="edit_link_id">
                    <div class="mb-3">
                        <label class="form-label">عنوان الرابط</label>
                        <input type="text" name="link_title" id="edit_link_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الرابط</label>
                        <input type="url" name="link_url" id="edit_link_url" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نوع الرابط</label>
                        <select name="link_type" id="edit_link_type" class="form-control">
                            <option value="drive">Google Drive</option>
                            <option value="exam">امتحان</option>
                            <option value="document">مستند</option>
                            <option value="video">فيديو</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف (اختياري)</label>
                        <textarea name="description" id="edit_link_description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-info">تحديث الرابط</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function editFolder(folder) {
        document.getElementById('edit_folder_id').value = folder.id;
        document.getElementById('edit_folder_name').value = folder.folder_name;
        document.getElementById('edit_folder_description').value = folder.description || '';
        new bootstrap.Modal(document.getElementById('editFolderModal')).show();
    }

    function addLinkToFolder(folderId, folderName) {
        document.getElementById('add_link_folder_id').value = folderId;
        document.getElementById('add_link_folder_name').value = folderName;
        new bootstrap.Modal(document.getElementById('addLinkModal')).show();
    }

    function editLink(link) {
        document.getElementById('edit_link_id').value = link.id;
        document.getElementById('edit_link_title').value = link.link_title;
        document.getElementById('edit_link_url').value = link.link_url;
        document.getElementById('edit_link_type').value = link.link_type;
        document.getElementById('edit_link_description').value = link.description || '';
        new bootstrap.Modal(document.getElementById('editLinkModal')).show();
    }
</script>
</body>
</html>

<?php
function getLinkIcon($linkType) {
    switch ($linkType) {
        case 'drive':
            return 'cloud-arrow-down';
        case 'exam':
            return 'clipboard-check';
        case 'document':
            return 'file-text';
        case 'video':
            return 'play-circle';
        default:
            return 'link-45deg';
    }
}
?>