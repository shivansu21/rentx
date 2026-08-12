<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit();
}

include "../includes/config.php";

$totalUsers = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM users"));

$totalVehicles = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM vehicles"));

$totalBookings = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM bookings"));

$pendingBookings = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM bookings WHERE booking_status='Pending'"));

$approvedBookings = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM bookings WHERE booking_status='Approved'"));

$activePage = 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RentX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include "partials_sidebar.php"; ?>

        <h1 class="panel-welcome">Welcome back, <?php echo htmlspecialchars($_SESSION['admin']); ?> 👋</h1>
        <p class="panel-subtext">Here's what's happening with RentX today.</p>

        <div class="kpi-grid">

            <div class="kpi-card kpi-blue">
                <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
                <div>
                    <h3>Total Users</h3>
                    <h2><?php echo $totalUsers['total']; ?></h2>
                </div>
            </div>

            <div class="kpi-card kpi-purple">
                <div class="kpi-icon"><i class="fa-solid fa-car"></i></div>
                <div>
                    <h3>Total Vehicles</h3>
                    <h2><?php echo $totalVehicles['total']; ?></h2>
                </div>
            </div>

            <div class="kpi-card kpi-teal">
                <div class="kpi-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div>
                    <h3>Total Bookings</h3>
                    <h2><?php echo $totalBookings['total']; ?></h2>
                </div>
            </div>

            <div class="kpi-card kpi-amber">
                <div class="kpi-icon"><i class="fa-solid fa-hourglass-half"></i></div>
                <div>
                    <h3>Pending</h3>
                    <h2><?php echo $pendingBookings['total']; ?></h2>
                </div>
            </div>

            <div class="kpi-card kpi-green">
                <div class="kpi-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <h3>Approved</h3>
                    <h2><?php echo $approvedBookings['total']; ?></h2>
                </div>
            </div>

        </div>

        <h3 class="panel-section-title">Quick Actions</h3>

        <div class="quick-actions">
            <a href="add_vehicle.php" class="quick-action-card">
                <i class="fa-solid fa-square-plus"></i>
                <span>Add Vehicle</span>
            </a>

            <a href="manage_vehicles.php" class="quick-action-card">
                <i class="fa-solid fa-car"></i>
                <span>Manage Vehicles</span>
            </a>

            <a href="manage_bookings.php" class="quick-action-card">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Manage Bookings</span>
            </a>

            <a href="contact_messages.php" class="quick-action-card">
                <i class="fa-solid fa-envelope"></i>
                <span>Contact Messages</span>
            </a>
        </div>

    <?php include "partials_end.php"; ?>

</body>

</html>
