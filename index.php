<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/task_modal.php';

$pageTitle = 'Dashboard';

// Count total courses
$stmt = $pdo->query("SELECT COUNT(*) as total FROM courses");
$totalCourses = $stmt->fetch()['total'];

// Count total tasks
$stmt = $pdo->query("SELECT COUNT(*) as total FROM tasks");
$totalTasks = $stmt->fetch()['total'];

// Count by status
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM tasks WHERE status = ?");

$stmt->execute(['Belum Dikerjakan']);
$totalBelum = $stmt->fetch()['total'];

$stmt->execute(['Sedang Dikerjakan']);
$totalSedang = $stmt->fetch()['total'];

$stmt->execute(['Selesai']);
$totalSelesai = $stmt->fetch()['total'];

// Upcoming tasks (deadline terdekat)
$stmtUpcoming = $pdo->query("
    SELECT t.*, c.course_name, c.lecturer, c.semester
    FROM tasks t
    INNER JOIN courses c ON t.course_id = c.course_id
    ORDER BY t.deadline ASC
    LIMIT 10
");
$upcomingTasks = $stmtUpcoming->fetchAll();

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
include __DIR__ . '/includes/navbar.php';
?>

<div class="main-content">
    <?php include __DIR__ . '/includes/flash.php'; ?>

    <div class="page-header">
        <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>
        <p class="text-muted mb-0">Ringkasan tugas dan mata kuliah Anda</p>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4 col-xl">
            <div class="card dashboard-card bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small opacity-75">Mata Kuliah</div>
                        <div class="card-value"><?= $totalCourses ?></div>
                    </div>
                    <div class="card-icon"><i class="bi bi-book"></i></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl">
            <div class="card dashboard-card bg-info text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small opacity-75">Total Tugas</div>
                        <div class="card-value"><?= $totalTasks ?></div>
                    </div>
                    <div class="card-icon"><i class="bi bi-list-task"></i></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl">
            <div class="card dashboard-card bg-danger text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small opacity-75">Belum Dikerjakan</div>
                        <div class="card-value"><?= $totalBelum ?></div>
                    </div>
                    <div class="card-icon"><i class="bi bi-exclamation-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl">
            <div class="card dashboard-card bg-warning text-dark">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small opacity-75">Sedang Dikerjakan</div>
                        <div class="card-value"><?= $totalSedang ?></div>
                    </div>
                    <div class="card-icon"><i class="bi bi-clock-history"></i></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl">
            <div class="card dashboard-card bg-success text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small opacity-75">Selesai</div>
                        <div class="card-value"><?= $totalSelesai ?></div>
                    </div>
                    <div class="card-icon"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Tasks -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Tugas Deadline Terdekat</h5>
            <a href="<?= baseUrl('tasks/create.php') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Tambah Tugas
            </a>
        </div>
        <div class="card-body p-0">
            <?php if (count($upcomingTasks) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul Tugas</th>
                                <th>Mata Kuliah</th>
                                <th>Deadline</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($upcomingTasks as $i => $task): 
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
                                        <span class="task-clickable-title fw-semibold text-primary" data-bs-toggle="modal" data-bs-target="#taskDetailModal<?= $task['task_id'] ?>">
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-inbox d-block"></i>
                    <h5>Belum ada tugas</h5>
                    <p>Mulai dengan menambahkan mata kuliah dan tugas pertama Anda.</p>
                    <a href="<?= baseUrl('tasks/create.php') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Tugas
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Detail Tugas Dashboard -->
<?php if (count($upcomingTasks) > 0): ?>
    <?php foreach ($upcomingTasks as $task): ?>
        <?php renderTaskDetailModal($task); ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
