<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit();
}

include "../includes/config.php";

$errors = [];
$success = false;

if (isset($_POST['add_vehicle'])) {

    $vehicle_name    = trim($_POST['vehicle_name']);
    $vehicle_type    = trim($_POST['vehicle_type']);
    $brand           = trim($_POST['brand']);
    $fuel_type       = trim($_POST['fuel_type']);
    $transmission    = trim($_POST['transmission']);
    $price_per_km    = $_POST['price_per_km'];
    $city            = trim($_POST['city']);
    $pickup_address  = trim($_POST['pickup_address']);
    $service_radius  = trim($_POST['service_radius']) !== '' ? (int) $_POST['service_radius'] : 30;
    $status          = trim($_POST['status']);
    $description     = trim($_POST['description']);

    $uploadDir = __DIR__ . "/../uploads/vehicles/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $image = null;

    if (!isset($_FILES['vehicle_image']) || $_FILES['vehicle_image']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = "Please choose a vehicle image.";
    } elseif ($_FILES['vehicle_image']['error'] !== UPLOAD_ERR_OK) {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE   => "Image is larger than this server allows (upload_max_filesize).",
            UPLOAD_ERR_FORM_SIZE  => "Image is larger than the form allows.",
            UPLOAD_ERR_PARTIAL    => "Image was only partially uploaded. Please try again.",
            UPLOAD_ERR_NO_TMP_DIR => "Server is missing a temporary upload folder.",
            UPLOAD_ERR_CANT_WRITE => "Server failed to write the uploaded file to disk.",
            UPLOAD_ERR_EXTENSION  => "A server extension stopped the file upload.",
        ];
        $errors[] = $uploadErrors[$_FILES['vehicle_image']['error']] ?? "Image upload failed. Please try again.";
    } else {
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $originalName = $_FILES['vehicle_image']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            $errors[] = "Only JPG, PNG or WEBP images are allowed.";
        } elseif ($_FILES['vehicle_image']['size'] > 5 * 1024 * 1024) {
            $errors[] = "Image must be smaller than 5MB.";
        } else {
            $image = uniqid('veh_', true) . '.' . $ext;
            $tmp = $_FILES['vehicle_image']['tmp_name'];

            if (!move_uploaded_file($tmp, $uploadDir . $image)) {
                $errors[] = "Could not save the uploaded image on the server. Check that the uploads/vehicles folder is writable.";
                $image = null;
            }
        }
    }

    if ($vehicle_name === '' || $brand === '' || $fuel_type === '' || $city === '' || $pickup_address === '') {
        $errors[] = "Please fill in all required fields.";
    }

    if (!is_numeric($price_per_km) || $price_per_km <= 0) {
        $errors[] = "Price per KM must be a valid positive number.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO vehicles
                (vehicle_name, vehicle_type, brand, fuel_type, transmission,
                 city, pickup_address, service_radius,
                 price_per_km, status, vehicle_image, description)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            $errors[] = "Database error: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param(
                $stmt,
                "sssssssidsss",
                $vehicle_name,
                $vehicle_type,
                $brand,
                $fuel_type,
                $transmission,
                $city,
                $pickup_address,
                $service_radius,
                $price_per_km,
                $status,
                $image,
                $description
            );

            if (mysqli_stmt_execute($stmt)) {
                $success = true;
            } else {
                $errors[] = "Failed to save the vehicle: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        }
    }
}

$activePage = 'add_vehicle';
?>

<?php include "partials_sidebar.php"; ?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div class="bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:54px; height:54px;">
                    <i class="fa-solid fa-square-plus fs-3"></i>
                </div>
                <div>
                    <h4 class="fw-extrabold text-dark mb-1">Add New Vehicle to Inventory</h4>
                    <p class="text-secondary small mb-0">Fill in vehicle specifications, pricing, location, and upload clear photo.</p>
                </div>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 py-3 mb-4">
                    <i class="fa-solid fa-circle-check fs-4"></i>
                    <div><strong>Success!</strong> Vehicle has been added to inventory successfully. <a href="manage_vehicles.php" class="alert-link">Manage Fleet →</a></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-3 py-3 mb-4">
                    <div class="d-flex align-items-center gap-2 fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation"></i> Please fix the following errors:</div>
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-car me-1 text-primary"></i> Vehicle Name</label>
                        <input type="text" name="vehicle_name" class="form-control form-control-lg fs-6 rounded-3" placeholder="e.g. Honda City i-VTEC" required value="<?php echo isset($_POST['vehicle_name']) ? htmlspecialchars($_POST['vehicle_name']) : ''; ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-layer-group me-1 text-primary"></i> Vehicle Category</label>
                        <select name="vehicle_type" class="form-select form-select-lg fs-6 rounded-3">
                            <option value="Car" <?php if (isset($_POST['vehicle_type']) && $_POST['vehicle_type'] == 'Car') echo 'selected'; ?>>🚗 Car (4-Wheeler)</option>
                            <option value="Bike" <?php if (isset($_POST['vehicle_type']) && $_POST['vehicle_type'] == 'Bike') echo 'selected'; ?>>🏍️ Bike (2-Wheeler)</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-building me-1 text-primary"></i> Brand / Manufacturer</label>
                        <input type="text" name="brand" class="form-control form-control-lg fs-6 rounded-3" placeholder="e.g. Honda, Hyundai, Royal Enfield" required value="<?php echo isset($_POST['brand']) ? htmlspecialchars($_POST['brand']) : ''; ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-gas-pump me-1 text-primary"></i> Fuel Type</label>
                        <input type="text" name="fuel_type" class="form-control form-control-lg fs-6 rounded-3" placeholder="e.g. Petrol / EV / Diesel" required value="<?php echo isset($_POST['fuel_type']) ? htmlspecialchars($_POST['fuel_type']) : ''; ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-gears me-1 text-primary"></i> Transmission</label>
                        <select name="transmission" class="form-select form-select-lg fs-6 rounded-3">
                            <option value="Manual">Manual Transmission</option>
                            <option value="Automatic">Automatic Transmission</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-tag me-1 text-primary"></i> Price Rate (₹ / KM)</label>
                        <input type="number" step="0.01" min="0" name="price_per_km" class="form-control form-control-lg fs-6 rounded-3" placeholder="18.50" required value="<?php echo isset($_POST['price_per_km']) ? htmlspecialchars($_POST['price_per_km']) : ''; ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-city me-1 text-primary"></i> City</label>
                        <input type="text" name="city" class="form-control form-control-lg fs-6 rounded-3" placeholder="e.g. Metro City" required value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-location-dot me-1 text-primary"></i> Pickup Address</label>
                        <input type="text" name="pickup_address" class="form-control form-control-lg fs-6 rounded-3" placeholder="RentX Hub, Sector 21" required value="<?php echo isset($_POST['pickup_address']) ? htmlspecialchars($_POST['pickup_address']) : ''; ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-route me-1 text-primary"></i> Service Radius (KM)</label>
                        <input type="number" min="0" name="service_radius" class="form-control form-control-lg fs-6 rounded-3" placeholder="30" value="<?php echo isset($_POST['service_radius']) ? htmlspecialchars($_POST['service_radius']) : '30'; ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-toggle-on me-1 text-primary"></i> Status</label>
                        <select name="status" class="form-select form-select-lg fs-6 rounded-3">
                            <option value="Available">🟢 Available for Booking</option>
                            <option value="Booked">🔴 Booked / Unavailable</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-image me-1 text-primary"></i> Upload Vehicle Photo (JPG/PNG/WEBP)</label>
                        <input type="file" name="vehicle_image" class="form-control form-control-lg fs-6 rounded-3" accept=".jpg,.jpeg,.png,.webp" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-align-left me-1 text-primary"></i> Vehicle Description</label>
                        <textarea name="description" class="form-control rounded-3 fs-6" rows="4" placeholder="Mention key vehicle features, seating capacity, AC status..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                </div>

                <div class="pt-4 mt-3 border-top d-flex gap-3">
                    <button type="submit" name="add_vehicle" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa-solid fa-plus me-1"></i> Save Vehicle
                    </button>
                    <a href="manage_vehicles.php" class="btn btn-outline-secondary btn-lg rounded-pill px-4 fw-semibold">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "partials_end.php"; ?>

