<?php
include "includes/config.php";

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$type   = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : "";

include "includes/header.php";

$sql = "SELECT * FROM vehicles WHERE 1=1";

if ($search !== "") {
    $sql .= " AND (vehicle_name LIKE '%$search%' OR brand LIKE '%$search%')";
}

if ($type !== "") {
    $sql .= " AND vehicle_type = '$type'";
}

$result = mysqli_query($conn, $sql);
?>

<section class="popular-cars">

    <div class="section-title">
        <h2>Search Results</h2>
        <p><?php echo mysqli_num_rows($result); ?> result<?php echo mysqli_num_rows($result) !== 1 ? 's' : ''; ?> found<?php echo $search !== '' ? ' for "' . htmlspecialchars($search) . '"' : ''; ?></p>
    </div>

    <div class="car-container">

        <?php if (mysqli_num_rows($result) === 0): ?>

            <div class="no-result">
                <h3>No Vehicles Found</h3>
                <p>Try different keywords or browse all vehicles.</p>
            </div>

        <?php else: ?>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                <div class="car-card">

                    <div class="car-image">
                        <img src="uploads/vehicles/<?php echo htmlspecialchars($row['vehicle_image']); ?>" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27300%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23e5e7eb%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2720%27 fill=%27%239ca3af%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3ENo Image%3C/text%3E%3C/svg%3E';"
                            alt="<?php echo htmlspecialchars($row['vehicle_name']); ?>">
                    </div>

                    <h3><?php echo htmlspecialchars($row['vehicle_name']); ?></h3>

                    <p>
                        <?php echo htmlspecialchars($row['brand']); ?> |
                        <?php echo htmlspecialchars($row['fuel_type']); ?> |
                        <?php echo htmlspecialchars($row['transmission']); ?>
                    </p>

                    <h4>₹<?php echo htmlspecialchars($row['price_per_km']); ?> / KM</h4>

                    <?php if ($row['status'] == "Available"): ?>
                        <p class="available-badge">🟢 Available</p>
                    <?php else: ?>
                        <p class="unavailable-badge">🔴 Unavailable</p>
                    <?php endif; ?>

                    <a href="vehicle_details.php?id=<?php echo $row['id']; ?>">
                        View Details
                    </a>
                </div>

            <?php endwhile; ?>

        <?php endif; ?>

    </div>

</section>

<?php include "includes/footer.php"; ?>
