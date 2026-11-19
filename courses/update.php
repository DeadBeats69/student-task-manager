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

// Check if course exists
$stmt = $pdo->prepare("SELECT course_id FROM courses WHERE course_id = ?");
$stmt->execute([$courseId]);
if (!$stmt->fetch()) {
    setFlash('danger', 'Mata kuliah tidak ditemukan.');
    header('Location: ' . baseUrl('courses/index.php'));
    exit;
}

// Get and validate input
$courseName = trim($_POST['course_name'] ?? '');
$lecturer = trim($_POST['lecturer'] ?? '');
$semester = trim($_POST['semester'] ?? '');

if (empty($courseName)) {
    setFlash('danger', 'Nama mata kuliah wajib diisi.');
    header('Location: ' . baseUrl('courses/edit.php?id=' . $courseId));
    exit;
}

if (mb_strlen($courseName) > 100) {
    setFlash('danger', 'Nama mata kuliah maksimal 100 karakter.');
    header('Location: ' . baseUrl('courses/edit.php?id=' . $courseId));
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE courses SET course_name = ?, lecturer = ?, semester = ? WHERE course_id = ?");
    $stmt->execute([
        $courseName,
        $lecturer ?: null,
        $semester ?: null,
        $courseId
    ]);

    setFlash('success', 'Mata kuliah berhasil diperbarui.');
    header('Location: ' . baseUrl('courses/index.php'));
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    setFlash('danger', 'Gagal memperbarui mata kuliah. Silakan coba lagi.');
    header('Location: ' . baseUrl('courses/edit.php?id=' . $courseId));
    exit;
}
