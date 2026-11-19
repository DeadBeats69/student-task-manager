<?php
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Edit Tugas';

// Validate ID
$taskId = validateId($_GET['id'] ?? '');
if (!$taskId) {
    setFlash('danger', 'ID tugas tidak valid.');
    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
}

// Fetch task
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE task_id = ?");
$stmt->execute([$taskId]);
$task = $stmt->fetch();

if (!$task) {
    setFlash('danger', 'Tugas tidak ditemukan.');
    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
}

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
        <h2><i class="bi bi-pencil-square me-2"></i>Edit Tugas</h2>
        <p class="text-muted mb-0">Ubah data tugas kuliah</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="<?= baseUrl('tasks/update.php') ?>" method="POST">
                <?= csrfField() ?>
                <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">

                <div class="mb-3">
                    <label for="task_title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="task_title" name="task_title" required maxlength="150" value="<?= e($task['task_title']) ?>">
                </div>

                <div class="mb-3">
                    <label for="course_id" class="form-label">Mata Kuliah <span class="text-danger">*</span></label>
                    <select class="form-select" id="course_id" name="course_id" required>
                        <option value="">-- Pilih Mata Kuliah --</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= $c['course_id'] ?>" <?= $task['course_id'] == $c['course_id'] ? 'selected' : '' ?>>
                                <?= e($c['course_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= e($task['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="deadline" class="form-label">Deadline <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="deadline" name="deadline" required value="<?= e($task['deadline']) ?>">
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <?php foreach ($allowedStatuses as $s): ?>
                            <option value="<?= e($s) ?>" <?= $task['status'] === $s ? 'selected' : '' ?>>
                                <?= e($s) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                    <a href="<?= baseUrl('tasks/index.php') ?>" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
