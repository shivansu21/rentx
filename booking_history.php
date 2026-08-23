<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location:login.php");
    exit();
}

include "includes/config.php";
include "includes/header.php";

$user_id = $_SESSION['user_id'];

// Handle cancellation
if (isset($_GET['cancel_id'])) {
    $cancel_id = (int)$_GET['cancel_id'];
    $check = mysqli_query($conn, "SELECT * FROM bookings WHERE id='$cancel_id' AND user_id='$user_id' AND booking_status='Pending'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE bookings SET booking_status='Cancelled' WHERE id='$cancel_id'");
        echo "<script>
            Swal.fire({ icon:'success', title:'Booking Cancelled', text:'Your booking has been cancelled successfully.', showConfirmButton:false, timer:2000 });
        </script>";
    }
}

$query = mysqli_query($conn, "
    SELECT bookings.*, vehicles.vehicle_name, vehicles.vehicle_image, vehicles.vehicle_type
    FROM bookings
    INNER JOIN vehicles ON bookings.vehicle_id = vehicles.id
    WHERE bookings.user_id = '$user_id'
    ORDER BY bookings.id DESC
");

$payments = [];
$p = mysqli_query($conn, "SELECT booking_id FROM payments WHERE user_id='$user_id'");
while ($pay = mysqli_fetch_assoc($p)) {
    $payments[$pay['booking_id']] = true;
}

$statusConfig = [
    'Pending'   => ['color' => '#f59e0b', 'bg' => '#fef3c7', 'icon' => 'fa-clock', 'label' => 'Pending Review'],
    'Approved'  => ['color' => '#10b981', 'bg' => '#d1fae5', 'icon' => 'fa-circle-check', 'label' => 'Approved'],
    'Rejected'  => ['color' => '#ef4444', 'bg' => '#fee2e2', 'icon' => 'fa-circle-xmark', 'label' => 'Rejected'],
    'Cancelled' => ['color' => '#64748b', 'bg' => '#f1f5f9', 'icon' => 'fa-ban', 'label' => 'Cancelled'],
];
?>

<div class="container py-5 my-3">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-extrabold mb-1"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>My Booking History</h2>
            <p class="text-secondary mb-0">Track and manage all your vehicle bookings</p>
        </div>
        <a href="index.php" class="btn-emerald-submit" style="padding:12px 24px; text-decoration:none; width:auto; font-size:14px;">
            <i class="fa-solid fa-plus me-2"></i> New Booking
        </a>
    </div>

    <?php
    $totalRows = mysqli_num_rows($query);
    if ($totalRows === 0):
    ?>
    <!-- Empty State -->
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
        <div class="mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width:90px; height:90px; background:linear-gradient(135deg,#d1fae5,#a7f3d0); font-size:38px; color:#10b981;">
                <i class="fa-solid fa-car-side"></i>
            </div>
            <h4 class="fw-bold mb-2">No Bookings Yet</h4>
            <p class="text-secondary mb-4">You haven't made any bookings yet. Start by exploring our fleet!</p>
            <a href="index.php" class="btn-emerald-submit" style="padding:12px 28px; text-decoration:none; width:auto; display:inline-flex; margin:0 auto;">
                <i class="fa-solid fa-magnifying-glass me-2"></i> Browse Vehicles
            </a>
        </div>
    </div>

    <?php else: ?>

    <!-- Booking Cards -->
    <div class="row g-4">
    <?php while ($row = mysqli_fetch_assoc($query)):
        $status = $row['booking_status'];
        $cfg    = $statusConfig[$status] ?? $statusConfig['Pending'];
        $isPaid = isset($payments[$row['id']]);
    ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden booking-card-item">
                <div class="card-body p-0">
                    <div class="row g-0 align-items-stretch">

                        <!-- Vehicle Image Sidebar -->
                        <div class="col-md-3 d-flex align-items-center justify-content-center p-3" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7); min-height:140px;">
                            <img src="uploads/vehicles/<?php echo htmlspecialchars($row['vehicle_image'] ?? ''); ?>"
                                 alt="<?php echo htmlspecialchars($row['vehicle_name']); ?>"
                                 class="img-fluid rounded-3"
                                 style="max-height:110px; object-fit:contain;"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27120%27 height=%2780%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23d1fae5%27 rx=%2710%27/%3E%3Ctext x=%2750%25%27 y=%2255%25%27 font-family=%27sans-serif%27 font-size=%2226%27 fill=%27%2310b981%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3E🚗%3C/text%3E%3C/svg%3E';">
                        </div>

                        <!-- Booking Details -->
                        <div class="col-md-6 p-4 border-start border-end">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge rounded-pill fw-bold fs-7 px-3 py-1"
                                      style="background:<?php echo $cfg['bg']; ?>; color:<?php echo $cfg['color']; ?>;">
                                    <i class="fa-solid <?php echo $cfg['icon']; ?> me-1"></i> <?php echo $cfg['label']; ?>
                                </span>
                                <span class="text-secondary fs-7">#BK-<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></span>
                            </div>

                            <h5 class="fw-extrabold mb-1"><?php echo htmlspecialchars($row['vehicle_name']); ?></h5>
                            <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 fs-7 mb-3"><?php echo htmlspecialchars($row['vehicle_type']); ?></span>

                            <div class="row g-2 fs-7 text-secondary">
                                <div class="col-6">
                                    <i class="fa-solid fa-calendar-check me-1 text-primary"></i>
                                    <strong>Pickup:</strong> <?php echo date('d M Y', strtotime($row['pickup_date'])); ?>
                                </div>
                                <div class="col-6">
                                    <i class="fa-solid fa-calendar-xmark me-1 text-danger"></i>
                                    <strong>Return:</strong> <?php echo date('d M Y', strtotime($row['return_date'])); ?>
                                </div>
                                <div class="col-6">
                                    <i class="fa-solid fa-route me-1 text-primary"></i>
                                    <strong>Distance:</strong> <?php echo htmlspecialchars($row['estimated_km']); ?> KM
                                </div>
                                <div class="col-6">
                                    <i class="fa-solid fa-indian-rupee-sign me-1 text-success"></i>
                                    <strong>Total:</strong> ₹<?php echo number_format($row['total_amount'], 2); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="col-md-3 d-flex flex-column align-items-center justify-content-center gap-2 p-3">
                            <?php if ($status === 'Approved' && $isPaid): ?>
                                <a href="invoice.php?booking_id=<?php echo $row['id']; ?>" class="btn w-100 fw-bold rounded-pill py-2 fs-7" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;">
                                    <i class="fa-solid fa-file-invoice me-1"></i> View Invoice
                                </a>
                            <?php elseif ($status === 'Approved' && !$isPaid): ?>
                                <a href="payment.php?booking_id=<?php echo $row['id']; ?>" class="btn w-100 fw-bold rounded-pill py-2 fs-7" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;">
                                    <i class="fa-solid fa-credit-card me-1"></i> Pay Now
                                </a>
                            <?php endif; ?>

                            <?php if ($status === 'Pending'): ?>
                                <a href="?cancel_id=<?php echo $row['id']; ?>"
                                   class="btn btn-outline-danger w-100 fw-bold rounded-pill py-2 fs-7"
                                   onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    <i class="fa-solid fa-ban me-1"></i> Cancel Booking
                                </a>
                            <?php endif; ?>

                            <a href="vehicle_details.php?id=<?php echo $row['vehicle_id']; ?>" class="btn btn-outline-secondary w-100 fw-semibold rounded-pill py-2 fs-7">
                                <i class="fa-solid fa-eye me-1"></i> View Vehicle
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    </div>

    <?php endif; ?>
</div>

<style>
.booking-card-item {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1.5px solid #f1f5f9 !important;
}
.booking-card-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important;
}
body.dark-theme .booking-card-item {
    border-color: rgba(16,185,129,0.2) !important;
}
body.dark-theme .booking-card-item .col-md-3:first-child {
    background: linear-gradient(135deg, #0d2a1e, #071a12) !important;
}
</style>

<?php include "includes/footer.php"; ?>