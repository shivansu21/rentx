<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:../login.php");
    exit();
}

include "../includes/config.php";

$user_id = $_SESSION['user_id'];

$totalBookings = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM bookings WHERE user_id='$user_id'"))['total'] ?? 0;

$approvedBookings = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM bookings WHERE user_id='$user_id' AND booking_status='Approved'"))['total'] ?? 0;

$pendingBookings = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM bookings WHERE user_id='$user_id' AND booking_status='Pending'"))['total'] ?? 0;

$rejectedBookings = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM bookings WHERE user_id='$user_id' AND booking_status='Rejected'"))['total'] ?? 0;

?>

<?php include "../includes/header.php"; ?>

<div class="container py-5">

    <!-- User Dashboard Header Banner -->
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-gradient bg-primary text-white position-relative overflow-hidden">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary rounded-pill px-3 py-1-5 fw-bold fs-7 mb-2">
                    <i class="fa-solid fa-user-check me-1"></i> Account Dashboard
                </span>
                <h1 class="fw-extrabold mb-1">Welcome back, <?php echo htmlspecialchars($_SESSION['fullname']); ?>! 👋</h1>
                <p class="text-white opacity-75 fs-6 mb-0">Track your active rental bookings, approval status, and account settings.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                    <button type="button" class="btn btn-light text-dark rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm theme-dash-toggle">
                        <i class="fa-solid fa-moon text-warning"></i>
                        <span>Toggle Dark/Light Mode</span>
                    </button>
                    <a href="../index.php#cars" class="btn btn-light text-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                        <i class="fa-solid fa-car me-1"></i> Book a Ride
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Metric Cards Grid -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center h-100 border-top border-4 border-primary">
                <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:56px; height:56px;">
                    <i class="fa-solid fa-calendar-days fs-3"></i>
                </div>
                <h2 class="fw-extrabold text-dark mb-1"><?php echo $totalBookings; ?></h2>
                <span class="text-secondary small fw-semibold">Total Rides Booked</span>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center h-100 border-top border-4 border-success">
                <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:56px; height:56px;">
                    <i class="fa-solid fa-circle-check fs-3"></i>
                </div>
                <h2 class="fw-extrabold text-success mb-1"><?php echo $approvedBookings; ?></h2>
                <span class="text-secondary small fw-semibold">Approved Rides</span>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center h-100 border-top border-4 border-warning">
                <div class="bg-warning-subtle text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:56px; height:56px;">
                    <i class="fa-solid fa-hourglass-half fs-3"></i>
                </div>
                <h2 class="fw-extrabold text-warning mb-1"><?php echo $pendingBookings; ?></h2>
                <span class="text-secondary small fw-semibold">Pending Approvals</span>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center h-100 border-top border-4 border-danger">
                <div class="bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:56px; height:56px;">
                    <i class="fa-solid fa-circle-xmark fs-3"></i>
                </div>
                <h2 class="fw-extrabold text-danger mb-1"><?php echo $rejectedBookings; ?></h2>
                <span class="text-secondary small fw-semibold">Rejected Applications</span>
            </div>
        </div>
    </div>

    <!-- Quick Account Actions -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h5 class="fw-extrabold text-dark mb-3"><i class="fa-solid fa-sliders text-primary me-2"></i>Quick Actions</h5>
        <div class="d-flex flex-wrap gap-3">
            <a href="../booking_history.php" class="btn btn-outline-primary rounded-pill px-4 py-2.5 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left"></i> View Booking History
            </a>
            <a href="../profile.php" class="btn btn-outline-secondary rounded-pill px-4 py-2.5 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-id-card"></i> Manage Profile
            </a>
            <a href="../logout.php" class="btn btn-outline-danger rounded-pill px-4 py-2.5 fw-semibold d-inline-flex align-items-center gap-2 ms-auto">
                <i class="fa-solid fa-right-from-bracket"></i> Sign Out
            </a>
        </div>
    </div>

</div>

<?php include "../includes/footer.php"; ?>

