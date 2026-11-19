<?php
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Tambah Tugas';

// Get courses for dropdown
$stmtCourses = $pdo->query("SELECT course_id, course_name FROM courses ORDER BY course_name ASC");
$courses = $stmtCourses->fetchAll();

$allowedStatuses = getAllowedStatuses();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="main-content">
    <div class="page-header">
        <h2><i class="bi bi-plus-circle me-2"></i>Tambah Tugas</h2>
        <p class="text-muted mb-0">Tambahkan tugas baru ke daftar Anda</p>
    </div>

    <?php if (count($courses) === 0): ?>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Belum ada mata kuliah. Silakan <a href="<?= baseUrl('courses/create.php') ?>" class="alert-link">tambahkan mata kuliah</a> terlebih dahulu sebelum menambahkan tugas.
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="<?= baseUrl('tasks/store.php') ?>" method="POST">
                    <?= csrfField() ?>

                    <div class="mb-3">
                        <label for="task_title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="task_title" name="task_title" required maxlength="150" placeholder="Contoh: Membuat Aplikasi CRUD PHP">
                    </div>

                    <div class="mb-3">
                        <label for="course_id" class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?= $c['course_id'] ?>"><?= e($c['course_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Deskripsi tugas (opsional)"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="deadline" name="deadline" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <?php foreach ($allowedStatuses as $s): ?>
                                <option value="<?= e($s) ?>" <?= $s === 'Belum Dikerjakan' ? 'selected' : '' ?>>
                                    <?= e($s) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                        <a href="<?= baseUrl('tasks/index.php') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
