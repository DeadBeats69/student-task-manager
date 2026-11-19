<!-- Sidebar -->
<div class="bg-dark text-white sidebar d-none d-lg-block" id="sidebar">
    <div class="sidebar-header p-3 border-bottom border-secondary">
        <h5 class="mb-0">
            <i class="bi bi-journal-check me-2"></i>
            <span>Task Manager</span>
        </h5>
        <small class="text-muted">Student Edition</small>
    </div>
    <nav class="nav flex-column p-3">
        <a href="<?= baseUrl('index.php') ?>" class="nav-link text-white <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a href="<?= baseUrl('courses/index.php') ?>" class="nav-link text-white <?= $currentPage === 'courses' ? 'active' : '' ?>">
            <i class="bi bi-book me-2"></i> Mata Kuliah
        </a>
        <a href="<?= baseUrl('tasks/index.php') ?>" class="nav-link text-white <?= $currentPage === 'tasks' ? 'active' : '' ?>">
            <i class="bi bi-list-task me-2"></i> Tugas
        </a>
    </nav>
</div>
