<?php
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Tambah Mata Kuliah';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="main-content">
    <div class="page-header">
        <h2><i class="bi bi-plus-circle me-2"></i>Tambah Mata Kuliah</h2>
        <p class="text-muted mb-0">Tambahkan mata kuliah baru ke daftar Anda</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="<?= baseUrl('courses/store.php') ?>" method="POST">
                <?= csrfField() ?>

                <div class="mb-3">
                    <label for="course_name" class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="course_name" name="course_name" required maxlength="100" placeholder="Contoh: Pemrograman Berbasis Web">
                </div>

                <div class="mb-3">
                    <label for="lecturer" class="form-label">Nama Dosen</label>
                    <input type="text" class="form-control" id="lecturer" name="lecturer" maxlength="100" placeholder="Contoh: Budi Santoso">
                </div>

                <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="text" class="form-control" id="semester" name="semester" maxlength="20" placeholder="Contoh: 5">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan
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
