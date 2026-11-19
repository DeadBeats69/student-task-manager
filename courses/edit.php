<?php
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Edit Mata Kuliah';

// Validate ID
$courseId = validateId($_GET['id'] ?? '');
if (!$courseId) {
    setFlash('danger', 'ID mata kuliah tidak valid.');
    header('Location: ' . baseUrl('courses/index.php'));
    exit;
}

// Fetch course
$stmt = $pdo->prepare("SELECT * FROM courses WHERE course_id = ?");
$stmt->execute([$courseId]);
$course = $stmt->fetch();

if (!$course) {
    setFlash('danger', 'Mata kuliah tidak ditemukan.');
    header('Location: ' . baseUrl('courses/index.php'));
    exit;
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="main-content">
    <div class="page-header">
        <h2><i class="bi bi-pencil-square me-2"></i>Edit Mata Kuliah</h2>
        <p class="text-muted mb-0">Ubah data mata kuliah</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="<?= baseUrl('courses/update.php') ?>" method="POST">
                <?= csrfField() ?>
                <input type="hidden" name="course_id" value="<?= $course['course_id'] ?>">

                <div class="mb-3">
                    <label for="course_name" class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="course_name" name="course_name" required maxlength="100" value="<?= e($course['course_name']) ?>">
                </div>

                <div class="mb-3">
                    <label for="lecturer" class="form-label">Nama Dosen</label>
                    <input type="text" class="form-control" id="lecturer" name="lecturer" maxlength="100" value="<?= e($course['lecturer']) ?>">
                </div>

                <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="text" class="form-control" id="semester" name="semester" maxlength="20" value="<?= e($course['semester']) ?>">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                    <a href="<?= baseUrl('courses/index.php') ?>" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
