<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit();
}

include "../includes/config.php";

$result = mysqli_query($conn, "SELECT * FROM vehicles ORDER BY id DESC");
$activePage = 'manage_vehicles';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles - RentX Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include "partials_sidebar.php"; ?>

        <div class="panel-table-card">
            <div class="panel-table-header">
                <h2><i class="fa-solid fa-car"></i> Manage Vehicles</h2>
                <a href="add_vehicle.php" class="panel-add-btn"><i class="fa-solid fa-plus"></i> Add Vehicle</a>
            </div>

            <div class="table-scroll">
                <table class="panel-table">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Vehicle Name</th>
                        <th>Type</th>
                        <th>Brand</th>
                        <th>Fuel</th>
                        <th>Transmission</th>
                        <th>Price/KM</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                    <?php if (mysqli_num_rows($result) === 0): ?>
                        <tr>
                            <td colspan="10" class="empty-row">No vehicles added yet.</td>
                        </tr>
                    <?php endif; ?>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <img src="../uploads/vehicles/<?php echo htmlspecialchars($row['vehicle_image']); ?>" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27300%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23e5e7eb%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2720%27 fill=%27%239ca3af%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3ENo Image%3C/text%3E%3C/svg%3E';" class="table-thumb">
                            </td>
                            <td><?php echo htmlspecialchars($row['vehicle_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['vehicle_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['brand']); ?></td>
                            <td><?php echo htmlspecialchars($row['fuel_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['transmission']); ?></td>
                            <td>₹<?php echo htmlspecialchars($row['price_per_km']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $row['status'] == 'Available' ? 'status-available' : 'status-unavailable'; ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td class="action-cell">
                                <a class="edit-btn" href="edit_vehicle.php?id=<?php echo $row['id']; ?>">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                                <a class="delete-btn" href="delete_vehicle.php?id=<?php echo $row['id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this vehicle?')">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

    <?php include "partials_end.php"; ?>

</body>

</html>
