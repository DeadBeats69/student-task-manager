<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$username = 'root';
$password = '';

// Support both student_task_manager (from spec) and task_manager_db (from SQL dump)
$dbNames = ['student_task_manager', 'task_manager_db'];
$pdo = null;
$connectedDb = '';

foreach ($dbNames as $db) {
    try {
        $pdo = new PDO(
            "mysql:host={$host};dbname={$db};charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        $connectedDb = $db;
        break;
    } catch (PDOException $e) {
        // Continue to next candidate
    }
}

if (!$pdo) {
    // If neither exists, attempt to connect to MySQL host directly to give clean error
    http_response_code(500);
    exit('Terjadi kesalahan koneksi database. Pastikan MySQL di XAMPP aktif dan database "student_task_manager" atau "task_manager_db" sudah diimpor.');
}

// CSRF Token Functions
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
}

function csrfField() {
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

// Flash Message Functions
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Sanitize output
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Validate integer ID
function validateId($id) {
    return filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
}

// Allowed statuses
function getAllowedStatuses() {
    return ['Belum Dikerjakan', 'Sedang Dikerjakan', 'Selesai'];
}

// Status badge class
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Belum Dikerjakan':
            return 'bg-danger';
        case 'Sedang Dikerjakan':
            return 'bg-warning text-dark';
        case 'Selesai':
            return 'bg-success';
        default:
            return 'bg-secondary';
    }
}

// Base URL helper
function baseUrl($path = '') {
    $projectRoot = '/student-task-manager';
    return $projectRoot . '/' . ltrim($path, '/');
}
