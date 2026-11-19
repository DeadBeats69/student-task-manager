<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . baseUrl('courses/index.php'));
    exit;
}

// Validate CSRF
if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
    setFlash('danger', 'Token keamanan tidak valid. Silakan coba lagi.');
    header('Location: ' . baseUrl('courses/create.php'));
    exit;
}

// Get and validate input
$courseName = trim($_POST['course_name'] ?? '');
$lecturer = trim($_POST['lecturer'] ?? '');
$semester = trim($_POST['semester'] ?? '');

// Validation
if (empty($courseName)) {
    setFlash('danger', 'Nama mata kuliah wajib diisi.');
    header('Location: ' . baseUrl('courses/create.php'));
    exit;
}

if (mb_strlen($courseName) > 100) {
    setFlash('danger', 'Nama mata kuliah maksimal 100 karakter.');
    header('Location: ' . baseUrl('courses/create.php'));
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO courses (course_name, lecturer, semester) VALUES (?, ?, ?)");
    $stmt->execute([
        $courseName,
        $lecturer ?: null,
        $semester ?: null
    ]);

    setFlash('success', 'Mata kuliah berhasil ditambahkan.');
    header('Location: ' . baseUrl('courses/index.php'));
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    setFlash('danger', 'Gagal menambahkan mata kuliah. Silakan coba lagi.');
    header('Location: ' . baseUrl('courses/create.php'));
    exit;
}
