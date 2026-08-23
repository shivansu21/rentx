<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit();
}

include "../includes/config.php";

// Fetch Analytics & KPIs
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'] ?? 0;
$totalVehicles = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM vehicles"))['total'] ?? 0;
$availableVehicles = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM vehicles WHERE status='Available'"))['total'] ?? 0;
$bookedVehicles = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM vehicles WHERE status='Booked'"))['total'] ?? 0;

$totalCars = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM vehicles WHERE vehicle_type='Car'"))['total'] ?? 0;
$totalBikes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM vehicles WHERE vehicle_type='Bike'"))['total'] ?? 0;

$totalBookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings"))['total'] ?? 0;
$pendingBookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE booking_status='Pending'"))['total'] ?? 0;
$approvedBookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE booking_status='Approved'"))['total'] ?? 0;
$rejectedBookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE booking_status='Rejected'"))['total'] ?? 0;

$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) AS total FROM bookings WHERE booking_status='Approved'"))['total'] ?? 0;

$fleetUtilizationPct = ($totalVehicles > 0) ? round(($bookedVehicles / $totalVehicles) * 100) : 0;

// Fetch Recent Pending Bookings
$recentPending = mysqli_query($conn, "
    SELECT bookings.*, users.fullname, users.mobile, vehicles.vehicle_name, vehicles.vehicle_type 
    FROM bookings 
    JOIN users ON bookings.user_id = users.id 
    JOIN vehicles ON bookings.vehicle_id = vehicles.id 
    ORDER BY bookings.id DESC LIMIT 5
");

$activePage = 'dashboard';
?>

<?php include "partials_sidebar.php"; ?>

<!-- Hero Greeting Header Banner -->
<div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 mb-4 text-white position-relative overflow-hidden admin-hero-banner" style="background: linear-gradient(135deg, #041410 0%, #062e26 50%, #0f172a 100%); border: 1px solid rgba(16, 185, 129, 0.3) !important;">
    <div class="position-absolute end-0 top-50 translate-middle-y me-4 opacity-10 pointer-events-none d-none d-md-block" style="z-index: 1;">
        <i class="fa-solid fa-gauge-high text-white" style="font-size: 8.5rem; opacity: 0.12;"></i>
    </div>
    
    <div class="row align-items-center position-relative" style="z-index:2;">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 rounded-pill px-3 py-1-5 fw-bold fs-7">
                    <i class="fa-solid fa-calendar-day me-1 text-warning"></i> <?php echo date('l, F j, Y'); ?>
                </span>
                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill px-3 py-1-5 fw-bold fs-7">
                    <span class="pulse-online me-1"></span> Live Data Feed
                </span>
            </div>

            <h1 class="display-5 fw-extrabold mb-2 text-white">Command Center Overview</h1>
            <p class="text-white opacity-75 fs-6 mb-0 max-w-2xl">
                Real-time control tower for RentX vehicle fleet, booking approvals, revenue performance, and customer growth metrics.
            </p>
        </div>

        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
            <div class="d-flex flex-column flex-sm-row justify-content-lg-end gap-2">
                <a href="add_vehicle.php" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow-lg d-inline-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-plus"></i> Add Vehicle
                </a>
                <a href="manage_bookings.php" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-semibold d-inline-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-calendar-check"></i> Bookings (<?php echo $pendingBookings; ?>)
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Pending Approvals Alert Banner (Conditional) -->
<?php if ($pendingBookings > 0): ?>
<div class="alert alert-warning border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center justify-content-between bg-warning-subtle text-dark border-start border-5 border-warning">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-warning text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:42px; height:42px;">
            <i class="fa-solid fa-triangle-exclamation fs-5"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0 text-dark"><?php echo $pendingBookings; ?> Pending Rental Applications Require Your Review</h6>
            <p class="small text-secondary mb-0">Customers are waiting for booking confirmation.</p>
        </div>
    </div>
    <a href="manage_bookings.php" class="btn btn-warning btn-sm rounded-pill px-4 fw-bold shadow-sm">Review Now <i class="fa-solid fa-arrow-right ms-1"></i></a>
</div>
<?php endif; ?>

<!-- Executive KPI Stat Cards -->
<div class="row g-4 mb-4">
    <!-- Revenue Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-stat-card kpi-blue p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary small fw-bold text-uppercase tracking-wider">Total Approved Revenue</span>
                <div class="kpi-icon-container bg-primary-subtle text-primary">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <h2 class="display-6 fw-extrabold text-dark mb-1">₹<?php echo number_format($totalRevenue, 2); ?></h2>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 fs-7 fw-bold">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>+18.4%
                </span>
                <span class="text-secondary fs-7"><?php echo $approvedBookings; ?> Paid Rides</span>
            </div>
        </div>
    </div>

    <!-- Fleet Utilization Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-stat-card kpi-green p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary small fw-bold text-uppercase tracking-wider">Fleet Inventory</span>
                <div class="kpi-icon-container bg-success-subtle text-success">
                    <i class="fa-solid fa-car-side"></i>
                </div>
            </div>
            <h2 class="display-6 fw-extrabold text-dark mb-1"><?php echo $totalVehicles; ?> <small class="fs-6 text-secondary fw-normal">Vehicles</small></h2>
            <div class="mt-2">
                <div class="d-flex justify-content-between fs-7 text-secondary mb-1">
                    <span>Fleet Utilization</span>
                    <strong class="text-dark"><?php echo $fleetUtilizationPct; ?>% Booked</strong>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: <?php echo $fleetUtilizationPct; ?>%" aria-valuenow="<?php echo $fleetUtilizationPct; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-stat-card kpi-warning p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary small fw-bold text-uppercase tracking-wider">Pending Applications</span>
                <div class="kpi-icon-container bg-warning-subtle text-warning">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
            </div>
            <h2 class="display-6 fw-extrabold text-warning mb-1"><?php echo $pendingBookings; ?></h2>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1 fs-7 fw-bold">
                    Action Required
                </span>
                <span class="text-secondary fs-7">Out of <?php echo $totalBookings; ?> Total</span>
            </div>
        </div>
    </div>

    <!-- Customers Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card kpi-stat-card kpi-purple p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-secondary small fw-bold text-uppercase tracking-wider">Registered Renters</span>
                <div class="kpi-icon-container bg-purple-subtle text-purple" style="background:#f3e8ff; color:#9333ea;">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <h2 class="display-6 fw-extrabold text-dark mb-1"><?php echo $totalUsers; ?></h2>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 fs-7 fw-bold">
                    Active Accounts
                </span>
                <span class="text-secondary fs-7">Verified Renters</span>
            </div>
        </div>
    </div>
</div>

<!-- Analytics & Data Visualization Charts Grid -->
<div class="row g-4 mb-4">
    <!-- Chart 1: Revenue & Bookings Trend Area Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-2">
                <div>
                    <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-chart-area text-primary"></i> Booking Volume & Status Analysis
                    </h5>
                    <p class="text-secondary small mb-0">Total system bookings grouped by status categories.</p>
                </div>
                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fs-7 fw-semibold">Real-time Metrics</span>
            </div>
            <div style="height: 300px;">
                <canvas id="mainAnalyticsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 2: Fleet Category & Status Donut Chart -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-success"></i> Fleet Ratio
                    </h5>
                    <p class="text-secondary small mb-0">Cars vs Bikes distribution.</p>
                </div>
            </div>
            <div style="height: 240px;" class="d-flex align-items-center justify-content-center position-relative my-2">
                <canvas id="fleetDoughnutChart"></canvas>
            </div>
            <div class="row g-2 text-center pt-3 border-top mt-2 fs-7">
                <div class="col-6">
                    <span class="text-secondary d-block">Cars (4-Wheelers)</span>
                    <strong class="text-primary fs-6"><?php echo $totalCars; ?></strong>
                </div>
                <div class="col-6">
                    <span class="text-secondary d-block">Bikes (2-Wheelers)</span>
                    <strong class="text-success fs-6"><?php echo $totalBikes; ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Launcher Cards -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <h5 class="fw-extrabold text-dark mb-3"><i class="fa-solid fa-rocket text-primary me-2"></i>Quick Management Launchpad</h5>
    </div>
    <div class="col-lg-3 col-sm-6">
        <a href="add_vehicle.php" class="card border-0 shadow-sm rounded-4 p-3 bg-white text-decoration-none text-dark h-100 border-start border-4 border-primary hover-elevation">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-3 p-3 d-flex align-items-center justify-content-center" style="width:48px; height:48px; font-size:22px;">
                    <i class="fa-solid fa-square-plus"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Add Vehicle</h6>
                    <small class="text-secondary">List new car or bike</small>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-sm-6">
        <a href="manage_vehicles.php" class="card border-0 shadow-sm rounded-4 p-3 bg-white text-decoration-none text-dark h-100 border-start border-4 border-info hover-elevation">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-info text-white rounded-3 p-3 d-flex align-items-center justify-content-center" style="width:48px; height:48px; font-size:22px;">
                    <i class="fa-solid fa-car-side"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Manage Fleet</h6>
                    <small class="text-secondary"><?php echo $totalVehicles; ?> active vehicles</small>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-sm-6">
        <a href="manage_bookings.php" class="card border-0 shadow-sm rounded-4 p-3 bg-white text-decoration-none text-dark h-100 border-start border-4 border-warning hover-elevation">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning text-dark rounded-3 p-3 d-flex align-items-center justify-content-center" style="width:48px; height:48px; font-size:22px;">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Booking Approvals</h6>
                    <small class="text-secondary"><?php echo $pendingBookings; ?> pending review</small>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-sm-6">
        <a href="contact_messages.php" class="card border-0 shadow-sm rounded-4 p-3 bg-white text-decoration-none text-dark h-100 border-start border-4 border-success hover-elevation">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success text-white rounded-3 p-3 d-flex align-items-center justify-content-center" style="width:48px; height:48px; font-size:22px;">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Customer Messages</h6>
                    <small class="text-secondary">View user inquiries</small>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Recent Pending Applications Table -->
<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
        <div>
            <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-primary"></i> Recent Booking Activity Feed
            </h5>
            <p class="text-secondary small mb-0">Latest customer rental submissions requiring processing.</p>
        </div>
        <a href="manage_bookings.php" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold">View All Bookings <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>

    <div class="table-responsive">
        <table class="table admin-table align-middle mb-0">
            <thead class="table-light text-secondary fs-7 text-uppercase">
                <tr>
                    <th>ID</th>
                    <th>Renter Name</th>
                    <th>Vehicle Requested</th>
                    <th>Pickup Date</th>
                    <th>Return Date</th>
                    <th>Total Fare</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($recentPending) > 0): ?>
                    <?php while ($b = mysqli_fetch_assoc($recentPending)): ?>
                        <?php
                        $st = $b['booking_status'];
                        $badgeClass = ($st == 'Approved') ? 'bg-success text-white' : (($st == 'Rejected') ? 'bg-danger text-white' : 'bg-warning text-dark');
                        ?>
                        <tr>
                            <td class="fw-bold text-primary">#<?php echo $b['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:34px; height:34px; font-size:13px;">
                                        <?php echo strtoupper(substr($b['fullname'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($b['fullname']); ?></div>
                                        <small class="text-secondary"><i class="fa-solid fa-phone me-1"></i><?php echo htmlspecialchars($b['mobile']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($b['vehicle_name']); ?></div>
                                <span class="badge bg-light text-secondary border fs-7"><?php echo htmlspecialchars($b['vehicle_type']); ?></span>
                            </td>
                            <td><small class="fw-semibold text-dark"><?php echo htmlspecialchars($b['pickup_date']); ?></small></td>
                            <td><small class="fw-semibold text-dark"><?php echo htmlspecialchars($b['return_date']); ?></small></td>
                            <td class="fw-extrabold text-success fs-6">₹<?php echo number_format($b['total_amount'], 2); ?></td>
                            <td><span class="badge <?php echo $badgeClass; ?> rounded-pill px-3 py-1 fs-7 shadow-sm"><?php echo htmlspecialchars($st); ?></span></td>
                            <td class="text-center">
                                <?php if ($st == 'Pending'): ?>
                                    <a href="approve_booking.php?id=<?php echo $b['id']; ?>" class="btn btn-sm btn-success rounded-pill px-3 py-1 me-1 shadow-sm"><i class="fa-solid fa-check me-1"></i>Approve</a>
                                    <a href="reject_booking.php?id=<?php echo $b['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1"><i class="fa-solid fa-xmark me-1"></i>Reject</a>
                                <?php else: ?>
                                    <span class="text-secondary small"><i class="fa-solid fa-check-double text-muted me-1"></i>Processed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-secondary">No recent booking records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js Graphics Controller -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Main Booking Status Bar Chart
    const ctxMain = document.getElementById('mainAnalyticsChart').getContext('2d');
    const gradientBar = ctxMain.createLinearGradient(0, 0, 0, 300);
    gradientBar.addColorStop(0, 'rgba(37, 99, 235, 0.9)');
    gradientBar.addColorStop(1, 'rgba(79, 70, 229, 0.6)');

    new Chart(ctxMain, {
        type: 'bar',
        data: {
            labels: ['Total Applications', 'Approved Rides', 'Pending Review', 'Rejected Applications'],
            datasets: [{
                label: 'Bookings',
                data: [<?php echo $totalBookings; ?>, <?php echo $approvedBookings; ?>, <?php echo $pendingBookings; ?>, <?php echo $rejectedBookings; ?>],
                backgroundColor: [
                    '#2563eb',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444'
                ],
                borderRadius: 12,
                barThickness: 45
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { precision: 0 }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // 2. Fleet Doughnut Chart
    const ctxFleet = document.getElementById('fleetDoughnutChart').getContext('2d');
    new Chart(ctxFleet, {
        type: 'doughnut',
        data: {
            labels: ['Cars (4-Wheelers)', 'Bikes (2-Wheelers)'],
            datasets: [{
                data: [<?php echo $totalCars; ?>, <?php echo $totalBikes; ?>],
                backgroundColor: ['#2563eb', '#10b981'],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>

<?php include "partials_end.php"; ?>


