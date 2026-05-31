<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
$is_admin = isset($_SESSION['superuser']) && $_SESSION['superuser'] === true;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase d-flex align-items-center" href="index.php" style="letter-spacing: 1px;">
            <span class="bg-warning text-dark px-2 py-1 rounded me-2 fs-6">RT 35</span>
            <span>Jadwal <span class="text-warning">Digital</span></span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-2 align-items-center">
                <li class="nav-item">
                    <a class="nav-link px-3 rounded <?= ($current_page == 'index.php') ? 'active bg-warning text-dark fw-bold' : 'text-white-50' ?>" href="index.php">
                        <i class="bi bi-calendar-event me-1"></i> Jadwal Malam Ini
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded <?= ($current_page == 'jadwal.php') ? 'active bg-warning text-dark fw-bold' : 'text-white-50' ?>" href="jadwal.php">
                        <i class="bi bi-grid-3x3-gap me-1"></i> Jadwal Lengkap
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded <?= ($current_page == 'admin_warga.php') ? 'active bg-warning text-dark fw-bold' : 'text-white-50' ?>" href="admin_warga.php">
                        <i class="bi bi-people me-1"></i> Data Warga <?= !$is_admin ? '<i class="bi bi-lock-fill text-warning ms-1" style="font-size: 0.8rem;"></i>' : '' ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded <?= ($current_page == 'admin_jadwal.php') ? 'active bg-warning text-dark fw-bold' : 'text-white-50' ?>" href="admin_jadwal.php">
                        <i class="bi bi-calendar3 me-1"></i> Atur Jadwal <?= !$is_admin ? '<i class="bi bi-lock-fill text-warning ms-1" style="font-size: 0.8rem;"></i>' : '' ?>
                    </a>
                </li>
                <li class="nav-item ms-lg-2">
                    <?php if ($is_admin): ?>
                        <a class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-bold" href="logout.php">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                        </a>
                    <?php else: ?>
                        <a class="btn btn-outline-warning btn-sm px-3 rounded-pill fw-bold" href="login.php">
                            <i class="bi bi-person-fill-lock me-1"></i> Masuk Admin
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap Icon Support -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Bootstrap Bundle JS (for responsive toggler on mobile) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
