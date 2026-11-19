<!-- Top Navbar for mobile -->
<nav class="navbar navbar-dark bg-dark d-lg-none w-100 fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= baseUrl('index.php') ?>">
            <i class="bi bi-journal-check me-2"></i>Task Manager
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<!-- Offcanvas menu for mobile -->
<div class="offcanvas offcanvas-start bg-dark text-white" id="mobileMenu">
    <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title">
            <i class="bi bi-journal-check me-2"></i>Task Manager
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <nav class="nav flex-column">
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
</div>
