<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/task_modal.php';

$pageTitle = 'Daftar Tugas';

// Get filter parameters
$search = trim($_GET['search'] ?? '');
$filterCourse = validateId($_GET['course_id'] ?? '') ?: '';
$filterStatus = $_GET['status'] ?? '';

// Validate status filter
$allowedStatuses = getAllowedStatuses();
if ($filterStatus !== '' && !in_array($filterStatus, $allowedStatuses)) {
    $filterStatus = '';
}

// Build query dynamically
$sql = "SELECT t.*, c.course_name, c.lecturer, c.semester 
        FROM tasks t 
        INNER JOIN courses c ON t.course_id = c.course_id 
        WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND t.task_title LIKE ?";
    $params[] = '%' . $search . '%';
}

if ($filterCourse !== '') {
    $sql .= " AND t.course_id = ?";
    $params[] = $filterCourse;
}

if ($filterStatus !== '') {
    $sql .= " AND t.status = ?";
    $params[] = $filterStatus;
}

$sql .= " ORDER BY t.deadline ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();

// Get courses for filter dropdown
$stmtCourses = $pdo->query("SELECT course_id, course_name FROM courses ORDER BY course_name ASC");
$courses = $stmtCourses->fetchAll();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/navbar.php';
?>

<div class="main-content">
    <?php include __DIR__ . '/../includes/flash.php'; ?>

    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2><i class="bi bi-list-task me-2"></i>Daftar Tugas</h2>
            <p class="text-muted mb-0">Kelola semua tugas kuliah Anda</p>
        </div>
        <a href="<?= baseUrl('tasks/create.php') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Tugas
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <form method="GET" action="">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label small text-muted">Cari Judul Tugas</label>
                    <input type="text" class="form-control" id="search" name="search" value="<?= e($search) ?>" placeholder="Ketik judul tugas...">
                </div>
                <div class="col-md-3">
                    <label for="course_id" class="form-label small text-muted">Mata Kuliah</label>
                    <select class="form-select" id="course_id" name="course_id">
                        <option value="">Semua Mata Kuliah</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?= $c['course_id'] ?>" <?= $filterCourse == $c['course_id'] ? 'selected' : '' ?>>
                                <?= e($c['course_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label small text-muted">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <?php foreach ($allowedStatuses as $s): ?>
                            <option value="<?= e($s) ?>" <?= $filterStatus === $s ? 'selected' : '' ?>>
                                <?= e($s) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                    <a href="<?= baseUrl('tasks/index.php') ?>" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tasks Table -->
    <?php if (count($tasks) > 0): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Judul Tugas</th>
                                <th>Mata Kuliah</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th style="width:160px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $i => $task): 
                                $deadlineClass = '';
                                $today = date('Y-m-d');
                                if ($task['status'] !== 'Selesai') {
                                    if ($task['deadline'] < $today) {
                                        $deadlineClass = 'deadline-passed';
                                    } elseif ($task['deadline'] <= date('Y-m-d', strtotime('+3 days'))) {
                                        $deadlineClass = 'deadline-soon';
                                    } else {
                                        $deadlineClass = 'deadline-ok';
                                    }
                                }
                            ?>
                                <tr class="clickable-task-row" data-task-modal="#taskDetailModal<?= $task['task_id'] ?>" title="Klik untuk melihat detail tugas">
                                    <td><?= $i + 1 ?></td>
                                    <td>
                                        <span class="task-clickable-title fw-bold text-primary" data-bs-toggle="modal" data-bs-target="#taskDetailModal<?= $task['task_id'] ?>">
                                            <?= e($task['task_title']) ?>
                                            <i class="bi bi-eye ms-1 small text-muted"></i>
                                        </span>
                                    </td>
                                    <td><?= e($task['course_name']) ?></td>
                                    <td class="<?= $deadlineClass ?>">
                                        <?= date('d M Y', strtotime($task['deadline'])) ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= getStatusBadgeClass($task['status']) ?>">
                                            <?= e($task['status']) ?>
                                        </span>
                                    </td>
                                    <td class="action-buttons-cell">
                                        <button type="button" class="btn btn-info btn-action text-white" data-bs-toggle="modal" data-bs-target="#taskDetailModal<?= $task['task_id'] ?>" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="<?= baseUrl('tasks/edit.php?id=' . $task['task_id']) ?>" class="btn btn-warning btn-action" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $task['task_id'] ?>" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal<?= $task['task_id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus tugas <strong><?= e($task['task_title']) ?></strong>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="<?= baseUrl('tasks/delete.php') ?>" method="POST" class="d-inline">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
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
                    <i class="bi bi-clipboard-x d-block"></i>
                    <?php if ($search !== '' || $filterCourse !== '' || $filterStatus !== ''): ?>
                        <h5>Tidak ada tugas yang cocok</h5>
                        <p>Coba ubah filter atau kata kunci pencarian Anda.</p>
                        <a href="<?= baseUrl('tasks/index.php') ?>" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
                        </a>
                    <?php else: ?>
                        <h5>Belum ada tugas</h5>
                        <p>Tambahkan tugas pertama Anda.</p>
                        <a href="<?= baseUrl('tasks/create.php') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Tugas
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Detail Tugas -->
<?php if (count($tasks) > 0): ?>
    <?php foreach ($tasks as $task): ?>
        <?php renderTaskDetailModal($task); ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
