<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "includes/config.php";
include "includes/header.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$vehicle_id = (int)$_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM vehicles WHERE id = '$vehicle_id'");

if (mysqli_num_rows($result) == 0) {
    die("Vehicle Not Found");
}

$vehicle = mysqli_fetch_assoc($result);

if (isset($_POST['book_vehicle'])) {
    $user_id = $_SESSION['user_id'];
    $pickup_date = $_POST['pickup_date'];
    $return_date = $_POST['return_date'];
    $estimated_km = (int) $_POST['estimated_km'];
    $price_per_km = (float) $vehicle['price_per_km'];

    $insurance_plan = mysqli_real_escape_string($conn, $_POST['insurance_plan'] ?? 'Basic');
    $addons = isset($_POST['addons']) && is_array($_POST['addons']) ? $_POST['addons'] : [];
    $add_ons_str = mysqli_real_escape_string($conn, !empty($addons) ? implode(', ', $addons) : 'None');

    $days = max(1, ceil((strtotime($return_date) - strtotime($pickup_date)) / 86400));
    
    // Calculate extra amounts
    $extra_amount = 0;
    if ($insurance_plan === 'Standard') {
        $extra_amount += 250 * $days;
    } elseif ($insurance_plan === 'Premium') {
        $extra_amount += 500 * $days;
    }

    if (in_array('GPS Navigation', $addons)) $extra_amount += 150;
    if (in_array('Child Safety Seat', $addons)) $extra_amount += 200;
    if (in_array('Additional Driver', $addons)) $extra_amount += 300;

    $base_amount = $estimated_km * $price_per_km;
    $total_amount = $base_amount + $extra_amount;

    if ($vehicle['status'] !== 'Available' || ($vehicle['maintenance_status'] ?? 'Available') !== 'Available') {
        echo "<script>
                Swal.fire({ icon: 'error', title: 'Unavailable', text: 'This vehicle is currently not available for booking.' });
              </script>";
    } elseif (strtotime($return_date) < strtotime($pickup_date)) {
        echo "<script>
                Swal.fire({ icon: 'error', title: 'Invalid Dates', text: 'Return date cannot be before pickup date.' });
              </script>";
    } elseif ($estimated_km <= 0) {
        echo "<script>
                Swal.fire({ icon: 'error', title: 'Invalid Distance', text: 'Please enter a valid estimated KM distance.' });
              </script>";
    } else {
        // Check date conflict with existing approved bookings
        $checkStmt = mysqli_prepare($conn, "
            SELECT id FROM bookings
            WHERE vehicle_id = ?
            AND booking_status = 'Approved'
            AND (
                (? BETWEEN pickup_date AND return_date)
                OR (? BETWEEN pickup_date AND return_date)
                OR (pickup_date BETWEEN ? AND ?)
            )
        ");
        mysqli_stmt_bind_param($checkStmt, "issss", $vehicle_id, $pickup_date, $return_date, $pickup_date, $return_date);
        mysqli_stmt_execute($checkStmt);
        $check = mysqli_stmt_get_result($checkStmt);

        if (mysqli_num_rows($check) > 0) {
            echo "<script>
                    Swal.fire({ icon: 'warning', title: 'Date Conflict', text: 'Vehicle is already reserved for the selected dates.' });
                  </script>";
        } else {
            $insertStmt = mysqli_prepare($conn, "
                INSERT INTO bookings
                (user_id, vehicle_id, pickup_date, return_date, estimated_km, total_amount, add_ons, insurance_plan, extra_amount, booking_status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
            ");
            mysqli_stmt_bind_param(
                $insertStmt,
                "iissidssd",
                $user_id,
                $vehicle_id,
                $pickup_date,
                $return_date,
                $estimated_km,
                $total_amount,
                $add_ons_str,
                $insurance_plan,
                $extra_amount
            );

            if (mysqli_stmt_execute($insertStmt)) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Booking Request Submitted!',
                        text: 'Your reservation is pending admin approval.',
                        confirmColor: '#2563eb'
                    }).then(() => {
                        window.location = 'booking_history.php';
                    });
                </script>";
            } else {
                echo "<script>
                        Swal.fire({ icon: 'error', title: 'Booking Failed', text: 'An error occurred while saving your booking.' });
                      </script>";
            }
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-secondary"><i class="fa-solid fa-house me-1"></i>Home</a></li>
                    <li class="breadcrumb-item"><a href="vehicle_details.php?id=<?php echo $vehicle['id']; ?>" class="text-decoration-none text-secondary">Vehicle Details</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Book Vehicle</li>
                </ol>
            </nav>

            <div class="row g-4">
                <!-- Left: Booking Form -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                        <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-3">
                            <div class="bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:54px; height:54px;">
                                <i class="fa-solid fa-calendar-check fs-3"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1 text-dark">Reserve Your Ride</h3>
                                <p class="text-secondary small mb-0">Customize your dates, insurance protection, and optional add-ons.</p>
                            </div>
                        </div>

                        <form method="POST" id="bookingForm">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary fs-7 mb-1">Selected Vehicle</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-car text-primary"></i></span>
                                    <input type="text" class="form-control form-control-lg bg-light border-start-0 fw-bold" value="<?php echo htmlspecialchars($vehicle['vehicle_name']); ?>" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary fs-7 mb-1">Rate Per Kilometer</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-tag text-success"></i></span>
                                    <input type="text" class="form-control form-control-lg bg-light border-start-0 fw-bold text-success" value="₹<?php echo htmlspecialchars($vehicle['price_per_km']); ?> / KM" readonly>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-calendar-days me-1 text-primary"></i> Pickup Date</label>
                                    <input type="date" id="pickup_date" name="pickup_date" min="<?php echo date('Y-m-d'); ?>" class="form-control form-control-lg rounded-3" required onchange="calculateTotal()">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-calendar-check me-1 text-primary"></i> Return Date</label>
                                    <input type="date" id="return_date" name="return_date" min="<?php echo date('Y-m-d'); ?>" class="form-control form-control-lg rounded-3" required onchange="calculateTotal()">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-route me-1 text-primary"></i> Estimated Distance (KM)</label>
                                <div class="input-group">
                                    <input type="number" id="km" name="estimated_km" class="form-control form-control-lg rounded-start-3" placeholder="e.g. 150" min="1" required oninput="calculateTotal()">
                                    <span class="input-group-text bg-light fw-bold text-secondary">Kilometers</span>
                                </div>
                                <div class="form-text">Service radius limit is <?php echo htmlspecialchars($vehicle['service_radius'] ?? 30); ?> KM from pickup point.</div>
                            </div>

                            <!-- Insurance Options -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark fs-6 mb-2"><i class="fa-solid fa-shield-halved me-1 text-primary"></i> Select Protection Plan</label>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="insurance_plan" id="insBasic" value="Basic" checked onchange="calculateTotal()">
                                        <label class="btn btn-outline-secondary w-100 text-start p-3 rounded-3" for="insBasic">
                                            <div class="fw-bold text-dark">Basic</div>
                                            <div class="small text-muted">Included (₹0)</div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="insurance_plan" id="insStandard" value="Standard" onchange="calculateTotal()">
                                        <label class="btn btn-outline-primary w-100 text-start p-3 rounded-3" for="insStandard">
                                            <div class="fw-bold">Standard</div>
                                            <div class="small">+₹250/day</div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="insurance_plan" id="insPremium" value="Premium" onchange="calculateTotal()">
                                        <label class="btn btn-outline-success w-100 text-start p-3 rounded-3" for="insPremium">
                                            <div class="fw-bold">Premium</div>
                                            <div class="small">+₹500/day</div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Optional Extras -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark fs-6 mb-2"><i class="fa-solid fa-sliders me-1 text-primary"></i> Add-on Extras (Optional)</label>
                                <div class="d-flex flex-column gap-2">
                                    <div class="form-check p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                        <div>
                                            <input class="form-check-input addon-checkbox me-2" type="checkbox" name="addons[]" value="GPS Navigation" id="addonGps" data-price="150" onchange="calculateTotal()">
                                            <label class="form-check-label fw-semibold text-dark cursor-pointer" for="addonGps">
                                                <i class="fa-solid fa-location-crosshairs text-primary me-1"></i> GPS Navigation System
                                            </label>
                                        </div>
                                        <span class="badge bg-white text-dark border">+₹150</span>
                                    </div>
                                    <div class="form-check p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                        <div>
                                            <input class="form-check-input addon-checkbox me-2" type="checkbox" name="addons[]" value="Child Safety Seat" id="addonSeat" data-price="200" onchange="calculateTotal()">
                                            <label class="form-check-label fw-semibold text-dark cursor-pointer" for="addonSeat">
                                                <i class="fa-solid fa-baby text-primary me-1"></i> Child Safety Seat
                                            </label>
                                        </div>
                                        <span class="badge bg-white text-dark border">+₹200</span>
                                    </div>
                                    <div class="form-check p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                        <div>
                                            <input class="form-check-input addon-checkbox me-2" type="checkbox" name="addons[]" value="Additional Driver" id="addonDriver" data-price="300" onchange="calculateTotal()">
                                            <label class="form-check-label fw-semibold text-dark cursor-pointer" for="addonDriver">
                                                <i class="fa-solid fa-user-plus text-primary me-1"></i> Additional Authorized Driver
                                            </label>
                                        </div>
                                        <span class="badge bg-white text-dark border">+₹300</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary fs-7 mb-1">Total Estimated Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white border-primary fw-bold">Total</span>
                                    <input type="text" id="total_amount_display" class="form-control form-control-lg bg-light border-primary fw-extrabold text-primary fs-4" readonly placeholder="₹ 0.00">
                                </div>
                            </div>

                            <input type="hidden" id="price_per_km" value="<?php echo htmlspecialchars($vehicle['price_per_km']); ?>">

                            <button type="submit" name="book_vehicle" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold py-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-check-circle fs-5"></i> Submit Booking Reservation
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right: Vehicle Preview & Fare Breakdown -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
                        <div class="card-header bg-dark text-white p-4 border-0 text-center">
                            <span class="badge bg-primary rounded-pill px-3 py-1 mb-2 fs-7"><?php echo htmlspecialchars($vehicle['vehicle_type']); ?></span>
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($vehicle['vehicle_name']); ?></h4>
                            <p class="text-secondary small mb-0">Brand: <?php echo htmlspecialchars($vehicle['brand']); ?></p>
                        </div>
                        <div class="card-body p-4 text-center bg-light">
                            <img src="uploads/vehicles/<?php echo htmlspecialchars($vehicle['vehicle_image']); ?>" 
                                 onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27300%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23e5e7eb%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2720%27 fill=%27%239ca3af%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3EVehicle Image%3C/text%3E%3C/svg%3E';"
                                 alt="<?php echo htmlspecialchars($vehicle['vehicle_name']); ?>" class="img-fluid rounded-3 mb-3 border p-2 bg-white" style="max-height:180px; object-fit:contain;">
                            
                            <div class="row g-2 text-start small text-secondary">
                                <div class="col-6 bg-white p-2 rounded border"><strong>Fuel:</strong> <?php echo htmlspecialchars($vehicle['fuel_type']); ?></div>
                                <div class="col-6 bg-white p-2 rounded border"><strong>Gear:</strong> <?php echo htmlspecialchars($vehicle['transmission']); ?></div>
                                <div class="col-12 bg-white p-2 rounded border"><strong>Location:</strong> <?php echo htmlspecialchars($vehicle['pickup_address']); ?>, <?php echo htmlspecialchars($vehicle['city']); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Fare Calculation Breakdown Box -->
                    <div id="calcSummary" class="card border-primary border-2 shadow-sm rounded-4 p-4 bg-white">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-calculator me-2 text-primary"></i>Live Price Breakdown</h5>
                        <ul class="list-group list-group-flush small mb-3">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Per KM Base Rate</span>
                                <strong id="calcPrice">₹<?php echo htmlspecialchars($vehicle['price_per_km']); ?> / KM</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Distance Subtotal</span>
                                <strong id="calcKmSubtotal">₹0.00</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Protection & Extras</span>
                                <strong id="calcExtrasSubtotal">₹0.00</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0 bg-primary-subtle p-3 rounded text-primary fw-bold fs-5">
                                <span>Est. Total</span>
                                <strong id="calcTotal">₹0.00</strong>
                            </li>
                        </ul>
                        <p class="text-secondary small mb-0"><i class="fa-solid fa-circle-info text-info me-1"></i> Booking request requires admin approval before payment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calculateTotal() {
    const km = parseFloat(document.getElementById('km').value) || 0;
    const rate = parseFloat(document.getElementById('price_per_km').value) || 0;
    const pDate = document.getElementById('pickup_date').value;
    const rDate = document.getElementById('return_date').value;

    let days = 1;
    if (pDate && rDate) {
        const d1 = new Date(pDate);
        const d2 = new Date(rDate);
        const diffTime = d2 - d1;
        if (diffTime >= 0) {
            days = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));
        }
    }

    let baseSubtotal = km * rate;
    let extrasSubtotal = 0;

    // Protection plan
    const insPlan = document.querySelector('input[name="insurance_plan"]:checked')?.value || 'Basic';
    if (insPlan === 'Standard') {
        extrasSubtotal += 250 * days;
    } else if (insPlan === 'Premium') {
        extrasSubtotal += 500 * days;
    }

    // Addons
    document.querySelectorAll('.addon-checkbox:checked').forEach(cb => {
        extrasSubtotal += parseFloat(cb.getAttribute('data-price')) || 0;
    });

    const grandTotal = baseSubtotal + extrasSubtotal;

    document.getElementById('total_amount_display').value = '₹ ' + grandTotal.toFixed(2);
    document.getElementById('calcKmSubtotal').innerText = '₹' + baseSubtotal.toFixed(2);
    document.getElementById('calcExtrasSubtotal').innerText = '₹' + extrasSubtotal.toFixed(2);
    document.getElementById('calcTotal').innerText = '₹' + grandTotal.toFixed(2);
}

document.addEventListener('DOMContentLoaded', calculateTotal);
</script>

<?php include "includes/footer.php"; ?>