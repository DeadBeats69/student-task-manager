<?php
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Mata Kuliah';

// Fetch all courses
$stmt = $pdo->query("SELECT * FROM courses ORDER BY course_name ASC");
$courses = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="main-content">
    <?php include __DIR__ . '/../includes/flash.php'; ?>

    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2><i class="bi bi-book me-2"></i>Mata Kuliah</h2>
            <p class="text-muted mb-0">Kelola daftar mata kuliah Anda</p>
        </div>
        <a href="<?= baseUrl('courses/create.php') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Mata Kuliah
        </a>
    </div>

    <?php if (count($courses) > 0): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Dosen</th>
                                <th>Semester</th>
                                <th>Tanggal Dibuat</th>
                                <th style="width:150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $i => $course): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><strong><?= e($course['course_name']) ?></strong></td>
                                    <td><?= $course['lecturer'] ? e($course['lecturer']) : '<span class="text-muted">-</span>' ?></td>
                                    <td><?= $course['semester'] ? e($course['semester']) : '<span class="text-muted">-</span>' ?></td>
                                    <td><?= date('d M Y', strtotime($course['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= baseUrl('courses/edit.php?id=' . $course['course_id']) ?>" class="btn btn-warning btn-action" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $course['course_id'] ?>" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal<?= $course['course_id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus mata kuliah <strong><?= e($course['course_name']) ?></strong>?</p>
                                                        <p class="text-muted small">Mata kuliah yang masih memiliki tugas tidak dapat dihapus.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="<?= baseUrl('courses/delete.php') ?>" method="POST" class="d-inline">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="course_id" value="<?= $course['course_id'] ?>">
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-trash me-1"></i>Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="empty-state">
                    <i class="bi bi-book d-block"></i>
                    <h5>Belum ada mata kuliah</h5>
                    <p>Tambahkan mata kuliah pertama Anda untuk mulai mencatat tugas.</p>
                    <a href="<?= baseUrl('courses/create.php') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Mata Kuliah
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
