<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . baseUrl('courses/index.php'));
    exit;
}

// Validate CSRF
if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
    setFlash('danger', 'Token keamanan tidak valid. Silakan coba lagi.');
    header('Location: ' . baseUrl('courses/index.php'));
    exit;
}

// Validate ID
$courseId = validateId($_POST['course_id'] ?? '');
if (!$courseId) {
    setFlash('danger', 'ID mata kuliah tidak valid.');
    header('Location: ' . baseUrl('courses/index.php'));
    exit;
}

// Check if course has tasks
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM tasks WHERE course_id = ?");
$stmt->execute([$courseId]);
$taskCount = $stmt->fetch()['total'];

if ($taskCount > 0) {
    setFlash('danger', 'Mata kuliah tidak dapat dihapus karena masih memiliki ' . $taskCount . ' tugas terkait. Hapus tugas terlebih dahulu.');
    header('Location: ' . baseUrl('courses/index.php'));
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM courses WHERE course_id = ?");
    $stmt->execute([$courseId]);

    if ($stmt->rowCount() > 0) {
        setFlash('success', 'Mata kuliah berhasil dihapus.');
    } else {
        setFlash('danger', 'Mata kuliah tidak ditemukan.');
    }

    header('Location: ' . baseUrl('courses/index.php'));
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    setFlash('danger', 'Gagal menghapus mata kuliah. Silakan coba lagi.');
    header('Location: ' . baseUrl('courses/index.php'));
    exit;
}
