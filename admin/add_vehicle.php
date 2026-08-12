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

    // ---------- Validate the uploaded image (this is the part that used
    // to crash the page with no useful message) ----------
    $uploadDir = __DIR__ . "/../uploads/vehicles/";

    if (!is_dir($uploadDir)) {
        // Auto-create the folder instead of failing if it happens to be missing
        mkdir($uploadDir, 0777, true);
    }

    $image = null;

    if (!isset($_FILES['vehicle_image']) || $_FILES['vehicle_image']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = "Please choose a vehicle image.";
    } elseif ($_FILES['vehicle_image']['error'] !== UPLOAD_ERR_OK) {
        // Give a real reason instead of a blank/failed page
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
            // Unique filename so two vehicles never overwrite each other's photo
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

    // ---------- Insert using a prepared statement ----------
    // The old version built the SQL by gluing strings together, so a single
    // apostrophe typed into the name/description/pickup location (e.g.
    // "Driver's Colony") broke the SQL syntax and crashed the whole request.
    // A prepared statement removes that failure mode entirely.
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle - RentX Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include "partials_sidebar.php"; ?>

        <div class="panel-form-card">
            <h2><i class="fa-solid fa-square-plus"></i> Add New Vehicle</h2>
            <p class="panel-form-subtitle">Fill in the details below to list a new car or bike.</p>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> Vehicle added successfully.
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="panel-form">

                <div class="form-grid">
                    <div class="form-field">
                        <label>Vehicle Name</label>
                        <input type="text" name="vehicle_name" placeholder="e.g. Hyundai Creta" required
                            value="<?php echo isset($_POST['vehicle_name']) ? htmlspecialchars($_POST['vehicle_name']) : ''; ?>">
                    </div>

                    <div class="form-field">
                        <label>Vehicle Type</label>
                        <select name="vehicle_type">
                            <option>Car</option>
                            <option>Bike</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label>Brand</label>
                        <input type="text" name="brand" placeholder="e.g. Hyundai" required
                            value="<?php echo isset($_POST['brand']) ? htmlspecialchars($_POST['brand']) : ''; ?>">
                    </div>

                    <div class="form-field">
                        <label>Fuel Type</label>
                        <input type="text" name="fuel_type" placeholder="e.g. Petrol" required
                            value="<?php echo isset($_POST['fuel_type']) ? htmlspecialchars($_POST['fuel_type']) : ''; ?>">
                    </div>

                    <div class="form-field">
                        <label>Transmission</label>
                        <select name="transmission">
                            <option>Manual</option>
                            <option>Automatic</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label>Price Per KM (₹)</label>
                        <input type="number" step="0.01" min="0" name="price_per_km" required
                            value="<?php echo isset($_POST['price_per_km']) ? htmlspecialchars($_POST['price_per_km']) : ''; ?>">
                    </div>

                    <div class="form-field">
                        <label>City</label>
                        <input type="text" name="city" placeholder="e.g. Gandhinagar" required
                            value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
                    </div>

                    <div class="form-field">
                        <label>Pickup Address</label>
                        <input type="text" name="pickup_address" placeholder="RentX, Sector 21, Gandhinagar" required
                            value="<?php echo isset($_POST['pickup_address']) ? htmlspecialchars($_POST['pickup_address']) : ''; ?>">
                    </div>

                    <div class="form-field">
                        <label>Service Radius (km) <small>optional, default 30</small></label>
                        <input type="number" min="0" name="service_radius" placeholder="30"
                            value="<?php echo isset($_POST['service_radius']) ? htmlspecialchars($_POST['service_radius']) : ''; ?>">
                    </div>

                    <div class="form-field">
                        <label>Availability</label>
                        <select name="status">
                            <option value="Available">Available</option>
                            <option value="Booked">Booked</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label>Vehicle Image <small>(JPG/PNG/WEBP, max 5MB)</small></label>
                        <input type="file" name="vehicle_image" accept=".jpg,.jpeg,.png,.webp" required>
                    </div>

                    <div class="form-field form-field-full">
                        <label>Description</label>
                        <textarea name="description" rows="4"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                </div>

                <button type="submit" name="add_vehicle" class="panel-submit-btn">
                    <i class="fa-solid fa-plus"></i> Add Vehicle
                </button>

            </form>
        </div>

    <?php include "partials_end.php"; ?>

</body>

</html>
