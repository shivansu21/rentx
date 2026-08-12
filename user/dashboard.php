<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:../login.php");
    exit();
}

include "../includes/config.php";

$user_id = $_SESSION['user_id'];

$totalBookings = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM bookings WHERE user_id='$user_id'"));

$approvedBookings = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM bookings WHERE user_id='$user_id' AND booking_status='Approved'"));

$pendingBookings = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM bookings WHERE user_id='$user_id' AND booking_status='Pending'"));

$rejectedBookings = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM bookings WHERE user_id='$user_id' AND booking_status='Rejected'"));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - RentX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include "../includes/header.php"; ?>

    <section class="user-dash">
        <div class="user-dash-banner">
            <div>
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['fullname']); ?> 👋</h1>
                <p>Here's a quick look at your rental activity.</p>
            </div>
            <a href="../index.php#cars" class="user-dash-cta"><i class="fa-solid fa-car"></i> Book a Vehicle</a>
        </div>

        <div class="user-stat-grid">

            <div class="user-stat-card stat-blue">
                <i class="fa-solid fa-calendar-days"></i>
                <h2><?php echo $totalBookings['total']; ?></h2>
                <p>Total Bookings</p>
            </div>

            <div class="user-stat-card stat-green">
                <i class="fa-solid fa-circle-check"></i>
                <h2><?php echo $approvedBookings['total']; ?></h2>
                <p>Approved</p>
            </div>

            <div class="user-stat-card stat-amber">
                <i class="fa-solid fa-hourglass-half"></i>
                <h2><?php echo $pendingBookings['total']; ?></h2>
                <p>Pending</p>
            </div>

            <div class="user-stat-card stat-red">
                <i class="fa-solid fa-circle-xmark"></i>
                <h2><?php echo $rejectedBookings['total']; ?></h2>
                <p>Rejected</p>
            </div>

        </div>

        <div class="user-dash-actions">
            <a href="../booking_history.php" class="dashboard-btn"><i class="fa-solid fa-clock-rotate-left"></i> My Bookings</a>
            <a href="../profile.php" class="dashboard-btn"><i class="fa-solid fa-user"></i> My Profile</a>
            <a href="../logout.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </section>

    <?php include "../includes/footer.php"; ?>

</body>

</html>
