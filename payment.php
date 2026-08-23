<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:login.php");
    exit();
}

include "includes/config.php";
include "includes/header.php";

if (!isset($_GET['booking_id'])) {
    header("Location:booking_history.php");
    exit();
}

$booking_id = (int) $_GET['booking_id'];
$user_id    = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT bookings.*, vehicles.vehicle_name, vehicles.vehicle_image, vehicles.vehicle_type, vehicles.price_per_km
    FROM bookings
    INNER JOIN vehicles ON bookings.vehicle_id = vehicles.id
    WHERE bookings.id='$booking_id'
    AND bookings.user_id='$user_id'
");

if (mysqli_num_rows($query) == 0) {
    echo "<script>Swal.fire({icon:'error',title:'Not Found',text:'Booking not found.',confirmButtonColor:'#10b981'}).then(()=>{window.location='booking_history.php';});</script>";
    include "includes/footer.php";
    exit();
}

$row = mysqli_fetch_assoc($query);

if (isset($_POST['pay_now'])) {
    $method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    $insert = mysqli_query($conn, "
        INSERT INTO payments (booking_id, user_id, amount, payment_method, payment_status)
        VALUES ('$booking_id', '$user_id', '{$row['total_amount']}', '$method', 'Paid')
    ");

    if ($insert) {
        echo "<script>
            Swal.fire({ icon:'success', title:'🎉 Payment Successful!', text:'Your booking is confirmed. Redirecting to invoice...', showConfirmButton:false, timer:2200 })
            .then(() => { window.location='invoice.php?booking_id=$booking_id'; });
        </script>";
    } else {
        echo "<script>Swal.fire({ icon:'error', title:'Payment Failed', text:'Something went wrong. Please try again.', confirmButtonColor:'#10b981' });</script>";
    }
}

$days = max(1, ceil((strtotime($row['return_date']) - strtotime($row['pickup_date'])) / 86400));
?>

<div class="container py-5 my-3">
    <div class="row justify-content-center g-4">

        <!-- Left: Order Summary -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header border-0 p-4" style="background:linear-gradient(135deg,#059669,#10b981,#06b6d4);">
                    <h5 class="fw-bold text-white mb-0"><i class="fa-solid fa-receipt me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Vehicle Thumbnail -->
                    <div class="text-center mb-4 p-3 rounded-3" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);">
                        <img src="uploads/vehicles/<?php echo htmlspecialchars($row['vehicle_image'] ?? ''); ?>"
                             alt="<?php echo htmlspecialchars($row['vehicle_name']); ?>"
                             class="img-fluid rounded-3 mb-2"
                             style="max-height:120px; object-fit:contain;"
                             onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27140%27 height=%2790%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23d1fae5%27 rx=%2710%27/%3E%3Ctext x=%2750%25%27 y=%2255%25%27 font-family=%27sans-serif%27 font-size=%2730%27 fill=%27%2310b981%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3E🚗%3C/text%3E%3C/svg%3E';">
                        <h6 class="fw-extrabold mb-0"><?php echo htmlspecialchars($row['vehicle_name']); ?></h6>
                        <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 fs-7"><?php echo htmlspecialchars($row['vehicle_type']); ?></span>
                    </div>

                    <!-- Booking Details -->
                    <div class="d-flex flex-column gap-2 mb-4 fs-7">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-secondary"><i class="fa-solid fa-calendar-check me-2 text-primary"></i>Pickup Date</span>
                            <strong><?php echo date('d M Y', strtotime($row['pickup_date'])); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-secondary"><i class="fa-solid fa-calendar-xmark me-2 text-danger"></i>Return Date</span>
                            <strong><?php echo date('d M Y', strtotime($row['return_date'])); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-secondary"><i class="fa-solid fa-route me-2 text-primary"></i>Estimated KM</span>
                            <strong><?php echo htmlspecialchars($row['estimated_km']); ?> KM</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-secondary"><i class="fa-solid fa-shield-halved me-2 text-success"></i>Insurance Plan</span>
                            <strong><?php echo htmlspecialchars($row['insurance_plan'] ?? 'Basic'); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 rounded-3 px-3" style="background:linear-gradient(135deg,#d1fae5,#f0fdf4);">
                            <span class="fw-bold text-success"><i class="fa-solid fa-indian-rupee-sign me-1"></i>Total Amount</span>
                            <span class="fw-extrabold fs-5 text-success">₹<?php echo number_format($row['total_amount'], 2); ?></span>
                        </div>
                    </div>

                    <!-- Trust Badges -->
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge rounded-pill px-3 py-2 fs-7 fw-semibold" style="background:#f0fdf4;color:#059669;"><i class="fa-solid fa-shield-halved me-1"></i>100% Secure</span>
                        <span class="badge rounded-pill px-3 py-2 fs-7 fw-semibold" style="background:#f0fdf4;color:#059669;"><i class="fa-solid fa-lock me-1"></i>SSL Encrypted</span>
                        <span class="badge rounded-pill px-3 py-2 fs-7 fw-semibold" style="background:#f0fdf4;color:#059669;"><i class="fa-solid fa-rotate-left me-1"></i>Refund Policy</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Payment Form -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-credit-card me-2 text-primary"></i>Select Payment Method</h5>
                    <p class="text-secondary fs-7 mt-1 mb-0">Choose how you'd like to pay</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" id="paymentForm">

                        <!-- Payment Options as radio pills -->
                        <div class="d-flex flex-column gap-3 mb-4" id="paymentOptions">
                            <?php
                            $methods = [
                                ['value'=>'UPI',          'icon'=>'fa-mobile-screen-button', 'label'=>'UPI Payment',        'sub'=>'Google Pay, PhonePe, Paytm'],
                                ['value'=>'Credit Card',  'icon'=>'fa-credit-card',           'label'=>'Credit Card',        'sub'=>'Visa, Mastercard, Rupay'],
                                ['value'=>'Debit Card',   'icon'=>'fa-credit-card',           'label'=>'Debit Card',         'sub'=>'All major debit cards'],
                                ['value'=>'Net Banking',  'icon'=>'fa-building-columns',       'label'=>'Net Banking',        'sub'=>'All major Indian banks'],
                                ['value'=>'Cash',         'icon'=>'fa-money-bill-wave',        'label'=>'Pay at Pickup',      'sub'=>'Cash on vehicle pickup'],
                            ];
                            foreach ($methods as $i => $m):
                            ?>
                            <label class="payment-method-option" for="pm<?php echo $i; ?>">
                                <input type="radio" name="payment_method" id="pm<?php echo $i; ?>" value="<?php echo $m['value']; ?>" class="d-none payment-radio" <?php echo $i === 0 ? 'checked' : ''; ?> required>
                                <div class="d-flex align-items-center gap-3 p-3 rounded-3 border payment-option-card" style="cursor:pointer; transition:all 0.2s;">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width:44px;height:44px;background:linear-gradient(135deg,#d1fae5,#f0fdf4);">
                                        <i class="fa-solid <?php echo $m['icon']; ?> text-primary fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold fs-6"><?php echo $m['label']; ?></div>
                                        <div class="text-secondary fs-7"><?php echo $m['sub']; ?></div>
                                    </div>
                                    <i class="fa-solid fa-circle-check text-primary fs-5 check-icon" style="opacity:0; transition:opacity 0.2s;"></i>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>

                        <button type="submit" name="pay_now" class="btn-emerald-submit w-100" id="payBtn">
                            <i class="fa-solid fa-lock me-2"></i> Pay ₹<?php echo number_format($row['total_amount'], 2); ?> Securely
                        </button>

                        <a href="booking_history.php" class="btn btn-light w-100 fw-semibold rounded-pill py-3 mt-3 text-secondary">
                            <i class="fa-solid fa-arrow-left me-2"></i> Back to Bookings
                        </a>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.payment-option-card { border-color: #e2e8f0 !important; background: #fff; }
.payment-radio:checked + .payment-option-card {
    border-color: #10b981 !important;
    background: linear-gradient(135deg, #f0fdf4, #fff) !important;
    box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
}
.payment-radio:checked + .payment-option-card .check-icon { opacity: 1 !important; }
body.dark-theme .payment-option-card { background: #0d1f2d; border-color: rgba(16,185,129,0.2) !important; }
body.dark-theme .payment-radio:checked + .payment-option-card { background: linear-gradient(135deg,#0d2a1e,#0d1f2d) !important; }
</style>

<script>
// Make the whole label card clickable and update radio
document.querySelectorAll('.payment-method-option').forEach(label => {
    label.addEventListener('click', function() {
        const radio = this.querySelector('.payment-radio');
        radio.checked = true;
    });
});
</script>

<?php include "includes/footer.php"; ?>