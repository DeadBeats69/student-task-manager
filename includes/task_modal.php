<?php
/**
 * Helper component untuk merender Modal Detail Tugas (View Only)
 * Menampilkan detail tugas menyerupai form edit tugas namun bersifat read-only.
 */

if (!function_exists('renderTaskDetailModal')) {
    function renderTaskDetailModal($task) {
        $today = new DateTime('today');
        $deadlineDate = new DateTime($task['deadline']);
        $diff = $today->diff($deadlineDate);
        $days = (int)$diff->format("%r%a");

        $formattedDeadline = $deadlineDate->format('d M Y');
        $deadlineClass = '';
        $deadlineNote = '';

        if ($task['status'] === 'Selesai') {
            $deadlineClass = 'text-success';
            $deadlineNote = ' (Selesai)';
        } elseif ($days < 0) {
            $abs = abs($days);
            $deadlineClass = 'text-danger fw-bold';
            $deadlineNote = " (Terlambat $abs hari)";
        } elseif ($days === 0) {
            $deadlineClass = 'text-danger fw-bold';
            $deadlineNote = ' (Hari ini)';
        } elseif ($days === 1) {
            $deadlineClass = 'text-warning fw-bold';
            $deadlineNote = ' (Besok)';
        } elseif ($days <= 3) {
            $deadlineClass = 'text-warning fw-bold';
            $deadlineNote = " ($days hari lagi)";
        } else {
            $deadlineClass = 'text-dark';
            $deadlineNote = " ($days hari lagi)";
        }

        $courseDetails = e($task['course_name'] ?? '');
        $extra = [];
        if (!empty($task['lecturer'])) {
            $extra[] = 'Dosen: ' . e($task['lecturer']);
        }
        if (!empty($task['semester'])) {
            $extra[] = 'Semester ' . e($task['semester']);
        }
        if (!empty($extra)) {
            $courseDetails .= ' — ' . implode(' • ', $extra);
        }
        ?>
        <!-- Task Detail Modal (ID: <?= $task['task_id'] ?>) -->
        <div class="modal fade" id="taskDetailModal<?= $task['task_id'] ?>" tabindex="-1" aria-labelledby="taskDetailModalLabel<?= $task['task_id'] ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title" id="taskDetailModalLabel<?= $task['task_id'] ?>">
                            <i class="bi bi-card-checklist text-primary me-2"></i>Detail Tugas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Judul Tugas -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">
                                Judul Tugas
                            </label>
                            <input type="text" class="form-control bg-light fw-semibold" value="<?= e($task['task_title']) ?>" readonly>
                        </div>

                        <!-- Mata Kuliah -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">
                                Mata Kuliah
                            </label>
                            <input type="text" class="form-control bg-light" value="<?= $courseDetails ?>" readonly>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">
                                Deskripsi
                            </label>
                            <textarea class="form-control bg-light" rows="3" readonly placeholder="(Tidak ada deskripsi)"><?= e($task['description'] ?? '') ?></textarea>
                        </div>

                        <!-- Deadline & Status -->
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-semibold text-secondary small">
                                    Deadline
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-calendar-event"></i></span>
                                    <input type="text" class="form-control bg-light <?= $deadlineClass ?>" value="<?= $formattedDeadline ?><?= $deadlineNote ?>" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-semibold text-secondary small">
                                    Status
                                </label>
                                <div class="d-flex align-items-center" style="height: 38px;">
                                    <span class="badge <?= getStatusBadgeClass($task['status']) ?> fs-6 py-2 px-3">
                                        <?= e($task['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <?php if (!empty($task['created_at']) || !empty($task['updated_at'])): ?>
                            <div class="border-top pt-2 mt-2 text-muted small d-flex justify-content-between flex-wrap gap-2">
                                <?php if (!empty($task['created_at'])): ?>
                                    <span><i class="bi bi-calendar-plus me-1"></i>Dibuat: <?= date('d M Y H:i', strtotime($task['created_at'])) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($task['updated_at']) && $task['updated_at'] !== $task['created_at']): ?>
                                    <span><i class="bi bi-clock-history me-1"></i>Diperbarui: <?= date('d M Y H:i', strtotime($task['updated_at'])) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer bg-light">
                        <a href="<?= baseUrl('tasks/edit.php?id=' . $task['task_id']) ?>" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>Edit Tugas
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
