<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename(dirname($_SERVER['SCRIPT_NAME']));
if ($currentPage === 'student-task-manager') {
    $currentPage = 'dashboard';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?>Student Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= baseUrl('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="d-flex" id="wrapper">
