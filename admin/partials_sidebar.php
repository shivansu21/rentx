<?php
// Reusable admin panel shell (sidebar + topbar).
// Include AFTER session_start()/auth-check/config include.
// Set $activePage before including this file to highlight the current nav item,
// e.g. $activePage = 'dashboard';
if (!isset($activePage)) {
    $activePage = '';
}
$adminName = $_SESSION['admin'] ?? 'Admin';
?>
<div class="admin-shell">

    <aside class="admin-sidebar">
        <div class="admin-brand">
            Rent<span>X</span>
            <small>Admin Panel</small>
        </div>

        <nav class="admin-nav">
            <a href="dashboard.php" class="<?php echo $activePage === 'dashboard' ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge"></i> <span>Dashboard</span>
            </a>
            <a href="add_vehicle.php" class="<?php echo $activePage === 'add_vehicle' ? 'active' : ''; ?>">
                <i class="fa-solid fa-square-plus"></i> <span>Add Vehicle</span>
            </a>
            <a href="manage_vehicles.php" class="<?php echo $activePage === 'manage_vehicles' ? 'active' : ''; ?>">
                <i class="fa-solid fa-car"></i> <span>Manage Vehicles</span>
            </a>
            <a href="manage_bookings.php" class="<?php echo $activePage === 'manage_bookings' ? 'active' : ''; ?>">
                <i class="fa-solid fa-calendar-check"></i> <span>Bookings</span>
            </a>
            <a href="contact_messages.php" class="<?php echo $activePage === 'contact_messages' ? 'active' : ''; ?>">
                <i class="fa-solid fa-envelope"></i> <span>Messages</span>
            </a>
        </nav>

        <a href="logout.php" class="admin-logout">
            <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
        </a>
    </aside>

    <div class="admin-main">

        <header class="admin-topbar">
            <button class="admin-menu-toggle" onclick="document.querySelector('.admin-shell').classList.toggle('sidebar-open')">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="admin-topbar-title"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $activePage))); ?></div>
            <div class="admin-topbar-user">
                <i class="fa-solid fa-circle-user"></i>
                <span><?php echo htmlspecialchars($adminName); ?></span>
            </div>
        </header>

        <main class="admin-content">
