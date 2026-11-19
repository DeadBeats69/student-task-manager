<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
}

// Validate CSRF
if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
    setFlash('danger', 'Token keamanan tidak valid. Silakan coba lagi.');
    header('Location: ' . baseUrl('tasks/create.php'));
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
    header('Location: ' . baseUrl('tasks/create.php'));
    exit;
}

if (mb_strlen($taskTitle) > 150) {
    setFlash('danger', 'Judul tugas maksimal 150 karakter.');
    header('Location: ' . baseUrl('tasks/create.php'));
    exit;
}

// Validate course
if (!$courseId) {
    setFlash('danger', 'Mata kuliah wajib dipilih.');
    header('Location: ' . baseUrl('tasks/create.php'));
    exit;
}

// Check if course exists
$stmt = $pdo->prepare("SELECT course_id FROM courses WHERE course_id = ?");
$stmt->execute([$courseId]);
if (!$stmt->fetch()) {
    setFlash('danger', 'Mata kuliah yang dipilih tidak valid.');
    header('Location: ' . baseUrl('tasks/create.php'));
    exit;
}

// Validate deadline
if (empty($deadline)) {
    setFlash('danger', 'Deadline wajib diisi.');
    header('Location: ' . baseUrl('tasks/create.php'));
    exit;
}

$deadlineDate = DateTime::createFromFormat('Y-m-d', $deadline);
if (!$deadlineDate || $deadlineDate->format('Y-m-d') !== $deadline) {
    setFlash('danger', 'Format deadline tidak valid (harus YYYY-MM-DD).');
    header('Location: ' . baseUrl('tasks/create.php'));
    exit;
}

// Validate status
$allowedStatuses = getAllowedStatuses();
if (!in_array($status, $allowedStatuses, true)) {
    setFlash('danger', 'Status tidak valid.');
    header('Location: ' . baseUrl('tasks/create.php'));
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO tasks (course_id, task_title, description, deadline, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $courseId,
        $taskTitle,
        $description !== '' ? $description : null,
        $deadline,
        $status
    ]);

    setFlash('success', 'Tugas berhasil ditambahkan.');
    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    setFlash('danger', 'Gagal menambahkan tugas. Silakan coba lagi.');
    header('Location: ' . baseUrl('tasks/create.php'));
    exit;
}
