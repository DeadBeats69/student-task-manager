<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
}

// Validate CSRF
if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
    setFlash('danger', 'Token keamanan tidak valid. Silakan coba lagi.');
    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
}

// Validate task ID
$taskId = validateId($_POST['task_id'] ?? '');
if (!$taskId) {
    setFlash('danger', 'ID tugas tidak valid.');
    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
}

// Check if task exists
$stmt = $pdo->prepare("SELECT task_id FROM tasks WHERE task_id = ?");
$stmt->execute([$taskId]);
if (!$stmt->fetch()) {
    setFlash('danger', 'Tugas tidak ditemukan.');
    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
}

// Get input
$taskTitle = trim($_POST['task_title'] ?? '');
$courseId = validateId($_POST['course_id'] ?? '');
$description = trim($_POST['description'] ?? '');
$deadline = trim($_POST['deadline'] ?? '');
$status = trim($_POST['status'] ?? '');

// Validate task title
if (empty($taskTitle)) {
    setFlash('danger', 'Judul tugas wajib diisi.');
    header('Location: ' . baseUrl('tasks/edit.php?id=' . $taskId));
    exit;
}

if (mb_strlen($taskTitle) > 150) {
    setFlash('danger', 'Judul tugas maksimal 150 karakter.');
    header('Location: ' . baseUrl('tasks/edit.php?id=' . $taskId));
    exit;
}

// Validate course
if (!$courseId) {
    setFlash('danger', 'Mata kuliah wajib dipilih.');
    header('Location: ' . baseUrl('tasks/edit.php?id=' . $taskId));
    exit;
}

$stmt = $pdo->prepare("SELECT course_id FROM courses WHERE course_id = ?");
$stmt->execute([$courseId]);
if (!$stmt->fetch()) {
    setFlash('danger', 'Mata kuliah yang dipilih tidak valid.');
    header('Location: ' . baseUrl('tasks/edit.php?id=' . $taskId));
    exit;
}

// Validate deadline
if (empty($deadline)) {
    setFlash('danger', 'Deadline wajib diisi.');
    header('Location: ' . baseUrl('tasks/edit.php?id=' . $taskId));
    exit;
}

$deadlineDate = DateTime::createFromFormat('Y-m-d', $deadline);
if (!$deadlineDate || $deadlineDate->format('Y-m-d') !== $deadline) {
    setFlash('danger', 'Format deadline tidak valid (harus YYYY-MM-DD).');
    header('Location: ' . baseUrl('tasks/edit.php?id=' . $taskId));
    exit;
}

// Validate status
$allowedStatuses = getAllowedStatuses();
if (!in_array($status, $allowedStatuses, true)) {
    setFlash('danger', 'Status tidak valid.');
    header('Location: ' . baseUrl('tasks/edit.php?id=' . $taskId));
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE tasks SET course_id = ?, task_title = ?, description = ?, deadline = ?, status = ? WHERE task_id = ?");
    $stmt->execute([
        $courseId,
        $taskTitle,
        $description !== '' ? $description : null,
        $deadline,
        $status,
        $taskId
    ]);

    setFlash('success', 'Tugas berhasil diperbarui.');
    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    setFlash('danger', 'Gagal memperbarui tugas. Silakan coba lagi.');
    header('Location: ' . baseUrl('tasks/edit.php?id=' . $taskId));
    exit;
}
