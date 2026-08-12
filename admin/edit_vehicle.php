<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit();
}

include "../includes/config.php";

$id = $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM vehicles WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("Location:manage_vehicles.php");
    exit();
}

$errors = [];
$success = false;

if (isset($_POST['update_vehicle'])) {

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

    $image = $row['vehicle_image']; // keep existing image unless a new one is uploaded

    $uploadDir = __DIR__ . "/../uploads/vehicles/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!empty($_FILES['vehicle_image']['name'])) {
        if ($_FILES['vehicle_image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Image upload failed. Please try again.";
        } else {
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['vehicle_image']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt)) {
                $errors[] = "Only JPG, PNG or WEBP images are allowed.";
            } elseif ($_FILES['vehicle_image']['size'] > 5 * 1024 * 1024) {
                $errors[] = "Image must be smaller than 5MB.";
            } else {
                $newImage = uniqid('veh_', true) . '.' . $ext;
                if (move_uploaded_file($_FILES['vehicle_image']['tmp_name'], $uploadDir . $newImage)) {
                    $image = $newImage;
                } else {
                    $errors[] = "Could not save the uploaded image on the server.";
                }
            }
        }
    }

    if (!is_numeric($price_per_km) || $price_per_km <= 0) {
        $errors[] = "Price per KM must be a valid positive number.";
    }

    if (empty($errors)) {
        $sql = "UPDATE vehicles SET
                    vehicle_name = ?, vehicle_type = ?, brand = ?, fuel_type = ?,
                    transmission = ?, city = ?, pickup_address = ?, service_radius = ?,
                    price_per_km = ?, status = ?, vehicle_image = ?, description = ?
                WHERE id = ?";

        $update = mysqli_prepare($conn, $sql);

        if ($update === false) {
            $errors[] = "Database error: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param(
                $update,
                "sssssssidsssi",
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
                $description,
                $id
            );

            if (mysqli_stmt_execute($update)) {
                $success = true;
                // refresh $row so the form shows the freshly saved values
                $row = array_merge($row, [
                    'vehicle_name' => $vehicle_name,
                    'vehicle_type' => $vehicle_type,
                    'brand' => $brand,
                    'fuel_type' => $fuel_type,
                    'transmission' => $transmission,
                    'price_per_km' => $price_per_km,
                    'city' => $city,
                    'pickup_address' => $pickup_address,
                    'service_radius' => $service_radius,
                    'status' => $status,
                    'vehicle_image' => $image,
                    'description' => $description,
                ]);
            } else {
                $errors[] = "Failed to update the vehicle: " . mysqli_error($conn);
            }

            mysqli_stmt_close($update);
        }
    }
}

$activePage = 'manage_vehicles';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle - RentX Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include "partials_sidebar.php"; ?>

        <div class="panel-form-card">
            <h2><i class="fa-solid fa-pen-to-square"></i> Edit Vehicle</h2>
            <p class="panel-form-subtitle">Update the details for this vehicle.</p>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> Vehicle updated successfully.
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
                        <input type="text" name="vehicle_name" value="<?php echo htmlspecialchars($row['vehicle_name']); ?>" required>
                    </div>

                    <div class="form-field">
                        <label>Vehicle Type</label>
                        <select name="vehicle_type">
                            <option value="Car" <?php echo $row['vehicle_type'] == 'Car' ? 'selected' : ''; ?>>Car</option>
                            <option value="Bike" <?php echo $row['vehicle_type'] == 'Bike' ? 'selected' : ''; ?>>Bike</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label>Brand</label>
                        <input type="text" name="brand" value="<?php echo htmlspecialchars($row['brand']); ?>" required>
                    </div>

                    <div class="form-field">
                        <label>Fuel Type</label>
                        <input type="text" name="fuel_type" value="<?php echo htmlspecialchars($row['fuel_type']); ?>" required>
                    </div>

                    <div class="form-field">
                        <label>Transmission</label>
                        <select name="transmission">
                            <option value="Manual" <?php echo $row['transmission'] == 'Manual' ? 'selected' : ''; ?>>Manual</option>
                            <option value="Automatic" <?php echo $row['transmission'] == 'Automatic' ? 'selected' : ''; ?>>Automatic</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label>Price Per KM (₹)</label>
                        <input type="number" step="0.01" min="0" name="price_per_km" value="<?php echo htmlspecialchars($row['price_per_km']); ?>" required>
                    </div>

                    <div class="form-field">
                        <label>City</label>
                        <input type="text" name="city" value="<?php echo htmlspecialchars($row['city']); ?>" required>
                    </div>

                    <div class="form-field">
                        <label>Pickup Address</label>
                        <input type="text" name="pickup_address" value="<?php echo htmlspecialchars($row['pickup_address']); ?>" required>
                    </div>

                    <div class="form-field">
                        <label>Service Radius (km)</label>
                        <input type="number" min="0" name="service_radius" value="<?php echo htmlspecialchars($row['service_radius']); ?>">
                    </div>

                    <div class="form-field">
                        <label>Availability</label>
                        <select name="status">
                            <option value="Available" <?php echo $row['status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                            <option value="Booked" <?php echo $row['status'] == 'Booked' ? 'selected' : ''; ?>>Booked</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label>Current Image</label>
                        <div class="current-image-preview">
                            <img src="../uploads/vehicles/<?php echo htmlspecialchars($row['vehicle_image']); ?>" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27300%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23e5e7eb%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2720%27 fill=%27%239ca3af%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3ENo Image%3C/text%3E%3C/svg%3E';" alt="<?php echo htmlspecialchars($row['vehicle_name']); ?>">
                        </div>
                    </div>

                    <div class="form-field">
                        <label>Change Image <small>(optional, JPG/PNG/WEBP, max 5MB)</small></label>
                        <input type="file" name="vehicle_image" accept=".jpg,.jpeg,.png,.webp">
                    </div>

                    <div class="form-field form-field-full">
                        <label>Description</label>
                        <textarea name="description" rows="4"><?php echo htmlspecialchars($row['description']); ?></textarea>
                    </div>
                </div>

                <button type="submit" name="update_vehicle" class="panel-submit-btn">
                    <i class="fa-solid fa-check"></i> Update Vehicle
                </button>

            </form>
        </div>

    <?php include "partials_end.php"; ?>

</body>

</html>
