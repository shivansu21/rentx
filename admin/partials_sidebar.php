<?php
// Reusable admin panel shell (sidebar + topbar).
if (!isset($activePage)) {
    $activePage = '';
}
$adminName = $_SESSION['admin'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentX Command Center</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Chart.js for analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom RentX Styles -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light">

<div class="d-flex min-vh-100 admin-wrapper">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar p-3 d-flex flex-column justify-content-between shadow-lg position-sticky top-0" style="width: 280px; height: 100vh; z-index: 1020;">
        <div>
            <!-- Brand Logo -->
            <div class="d-flex align-items-center justify-content-between mb-4 px-2 pt-2 border-bottom border-secondary border-opacity-25 pb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="brand-icon d-flex align-items-center justify-content-center text-white rounded-3 bg-gradient bg-primary shadow-sm" style="width:42px; height:42px; font-size: 20px;">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div>
                        <span class="fs-4 fw-extrabold text-white tracking-tight">Rent<span class="text-primary">X</span></span>
                        <span class="badge bg-primary bg-opacity-25 text-primary-subtle rounded-pill d-block fs-7 text-uppercase fw-bold">Executive Suite</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="nav nav-pills flex-column gap-2 admin-nav">
                <a href="dashboard.php" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 fw-semibold <?php echo $activePage === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-pie fs-5"></i>
                    <span>Dashboard Analytics</span>
                </a>
                
                <a href="add_vehicle.php" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 fw-semibold <?php echo $activePage === 'add_vehicle' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-square-plus fs-5"></i>
                    <span>Add New Vehicle</span>
                </a>

                <a href="manage_vehicles.php" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 fw-semibold <?php echo $activePage === 'manage_vehicles' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-car-side fs-5"></i>
                    <span>Fleet Inventory</span>
                </a>

                <a href="manage_bookings.php" class="nav-link d-flex align-items-center justify-content-between px-3 py-3 rounded-3 fw-semibold <?php echo $activePage === 'manage_bookings' ? 'active' : ''; ?>">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-calendar-check fs-5"></i>
                        <span>Bookings</span>
                    </div>
                    <?php
                    if (isset($conn)) {
                        $pCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM bookings WHERE booking_status='Pending'"))['cnt'] ?? 0;
                        if ($pCount > 0) {
                            echo "<span class='badge bg-warning text-dark rounded-pill fw-extrabold px-2 py-1'>$pCount</span>";
                        }
                    }
                    ?>
                </a>

                <a href="contact_messages.php" class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-3 fw-semibold <?php echo $activePage === 'contact_messages' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-comments fs-5"></i>
                    <span>Customer Messages</span>
                </a>
            </nav>
        </div>

        <!-- System Status & Logout -->
        <div class="pt-3 border-top border-secondary border-opacity-25">
            <div class="bg-secondary bg-opacity-10 p-3 rounded-3 mb-3 border border-secondary border-opacity-25">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="pulse-online"></span>
                    <span class="fw-bold fs-7 text-white text-uppercase tracking-wider">Server Status</span>
                </div>
                <small class="text-white-50 fs-7 d-block">MySQL Port 3306 • Online</small>
            </div>

            <a href="../index.php" target="_blank" class="btn btn-outline-light btn-sm w-100 rounded-pill mb-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Main Site
            </a>
            <a href="logout.php" class="btn btn-danger btn-sm w-100 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                <i class="fa-solid fa-right-from-bracket"></i> Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow-1 d-flex flex-column min-vh-100 overflow-x-hidden">
        <!-- Executive Top Bar -->
        <header class="navbar navbar-expand admin-topbar shadow-sm px-4 py-3 sticky-top">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold fs-7">
                        <i class="fa-solid fa-shield-halved me-1"></i> ADMIN CONSOLE
                    </span>
                    <h4 class="fw-extrabold text-white mb-0 tracking-tight d-none d-md-block">
                        <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $activePage))); ?>
                    </h4>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Dark / Light Mode Toggle Button for Admin -->
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-2 d-flex align-items-center gap-2 fw-semibold shadow-sm admin-theme-toggle" id="adminThemeToggleBtn" title="Switch Theme">
                        <i class="fa-solid fa-moon text-warning" id="adminThemeIcon"></i>
                        <span class="d-none d-sm-inline fs-7 theme-toggle-text">Dark Mode</span>
                    </button>

                    <a href="manage_bookings.php" class="btn btn-light position-relative rounded-circle p-2 d-flex align-items-center justify-content-center border" style="width:40px; height:40px;" title="Pending Requests">
                        <i class="fa-solid fa-bell text-secondary"></i>
                        <?php
                        if (isset($pCount) && $pCount > 0) {
                            echo '<span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"><span class="visually-hidden">Pending</span></span>';
                        }
                        ?>
                    </a>

                    <div class="dropdown">
                        <button class="btn btn-light rounded-pill px-3 py-2 d-flex align-items-center gap-2 border shadow-sm" type="button" id="adminUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-extrabold" style="width:34px; height:34px; font-size: 15px;">
                                <?php echo strtoupper(substr($adminName, 0, 1)); ?>
                            </div>
                            <span class="fw-bold text-dark fs-6"><?php echo htmlspecialchars($adminName); ?></span>
                            <i class="fa-solid fa-chevron-down fs-7 text-secondary ms-1"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 mt-2" aria-labelledby="adminUserDropdown">
                            <li><span class="dropdown-header text-uppercase fw-bold fs-7">Administrator Account</span></li>
                            <li><a class="dropdown-item rounded-3 py-2 text-danger fw-semibold" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body Page Container -->
        <main class="p-4 flex-grow-1">


