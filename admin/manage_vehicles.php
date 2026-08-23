<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit();
}

include "../includes/config.php";

// Handle Maintenance Status Toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_maintenance'])) {
    $vId = (int) $_POST['vehicle_id'];
    $mStatus = mysqli_real_escape_string($conn, $_POST['maintenance_status']);
    mysqli_query($conn, "UPDATE vehicles SET maintenance_status = '$mStatus' WHERE id = $vId");
}

$result = mysqli_query($conn, "SELECT * FROM vehicles ORDER BY id DESC");
$activePage = 'manage_vehicles';
?>

<?php include "partials_sidebar.php"; ?>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-extrabold text-dark mb-1"><i class="fa-solid fa-car-side text-primary me-2"></i>Manage Fleet Vehicles</h4>
            <p class="text-secondary small mb-0">Add, update, manage maintenance schedules, or remove cars and bikes from the active inventory.</p>
        </div>
        <a href="add_vehicle.php" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
            <i class="fa-solid fa-plus"></i> Add New Vehicle
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-secondary fs-7 text-uppercase">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Vehicle Details</th>
                    <th>Category</th>
                    <th>Specs</th>
                    <th>Location</th>
                    <th>Rate / KM</th>
                    <th>Rental Status</th>
                    <th>Maintenance Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr>
                        <td colspan="10" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-car-rear fs-1 mb-2 d-block text-muted"></i>
                            No vehicles listed in inventory. Click "Add New Vehicle" to get started.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php
                    $isAvail = ($row['status'] == 'Available');
                    $maintStatus = $row['maintenance_status'] ?? 'Available';
                    $imgUrl = "../uploads/vehicles/" . htmlspecialchars($row['vehicle_image']);
                    ?>
                    <tr>
                        <td class="fw-bold text-primary">#<?php echo $row['id']; ?></td>
                        <td style="width:70px;">
                            <img src="<?php echo $imgUrl; ?>" 
                                 onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27300%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23f1f5f9%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2720%27 fill=%27%239ca3af%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3EImg%3C/text%3E%3C/svg%3E';" 
                                 class="rounded-3 border p-1 bg-light" style="width:60px; height:45px; object-fit:contain;">
                        </td>
                        <td>
                            <div class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($row['vehicle_name']); ?></div>
                            <small class="text-secondary"><i class="fa-solid fa-building me-1"></i><?php echo htmlspecialchars($row['brand']); ?></small>
                        </td>
                        <td>
                            <span class="badge <?php echo $row['vehicle_type'] == 'Car' ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-success-subtle text-success border border-success-subtle'; ?> rounded-pill px-3 py-1 fs-7">
                                <?php echo $row['vehicle_type'] == 'Car' ? '🚗 Car' : '🏍️ Bike'; ?>
                            </span>
                        </td>
                        <td>
                            <small class="text-secondary d-block"><i class="fa-solid fa-gas-pump me-1"></i><?php echo htmlspecialchars($row['fuel_type']); ?></small>
                            <small class="text-secondary d-block"><i class="fa-solid fa-gears me-1"></i><?php echo htmlspecialchars($row['transmission']); ?></small>
                        </td>
                        <td>
                            <small class="text-dark fw-semibold d-block"><i class="fa-solid fa-location-dot text-danger me-1"></i><?php echo htmlspecialchars($row['city'] ?? 'Local'); ?></small>
                        </td>
                        <td class="fw-extrabold text-primary fs-6">
                            ₹<?php echo htmlspecialchars($row['price_per_km']); ?> <small class="text-secondary fw-normal fs-7">/KM</small>
                        </td>
                        <td>
                            <span class="badge <?php echo $isAvail ? 'bg-success text-white' : 'bg-danger text-white'; ?> rounded-pill px-3 py-1 fs-7 shadow-sm">
                                <?php echo $isAvail ? '🟢 Available' : '🔴 Booked'; ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="vehicle_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="toggle_maintenance" value="1">
                                <select name="maintenance_status" onchange="this.form.submit()" class="form-select form-select-sm rounded-pill border-secondary fs-7 fw-semibold">
                                    <option value="Available" <?php echo $maintStatus === 'Available' ? 'selected' : ''; ?>>🟢 Operational</option>
                                    <option value="In Maintenance" <?php echo $maintStatus === 'In Maintenance' ? 'selected' : ''; ?>>🟡 In Maintenance</option>
                                    <option value="Out of Service" <?php echo $maintStatus === 'Out of Service' ? 'selected' : ''; ?>>🔴 Out of Service</option>
                                </select>
                            </form>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-outline-primary rounded-circle me-1" style="width:34px; height:34px;" href="edit_vehicle.php?id=<?php echo $row['id']; ?>" title="Edit Vehicle">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a class="btn btn-sm btn-outline-danger rounded-circle" style="width:34px; height:34px;" href="delete_vehicle.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to remove this vehicle from inventory?')" title="Delete Vehicle">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "partials_end.php"; ?>


