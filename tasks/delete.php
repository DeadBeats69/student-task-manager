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

// Validate ID
$taskId = validateId($_POST['task_id'] ?? '');
if (!$taskId) {
    setFlash('danger', 'ID tugas tidak valid.');
    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE task_id = ?");
    $stmt->execute([$taskId]);

    if ($stmt->rowCount() > 0) {
        setFlash('success', 'Tugas berhasil dihapus.');
    } else {
        setFlash('danger', 'Tugas tidak ditemukan.');
    }

    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    setFlash('danger', 'Gagal menghapus tugas. Silakan coba lagi.');
    header('Location: ' . baseUrl('tasks/index.php'));
    exit;
}
