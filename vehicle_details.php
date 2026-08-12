<?php
include "includes/config.php";
include "includes/header.php";
if (!isset($_GET['id'])) {
    header("Location:index.php");
    exit();
}

$id = (int) $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM vehicles WHERE id=" . $id);
if (mysqli_num_rows($result) == 0) {
    die("Vehicle Not Found");
}
$row = mysqli_fetch_assoc($result);
?>

<section class="vehicle-details">

    <div class="vehicle-container">

        <div class="vehicle-left">

            <img src="uploads/vehicles/<?php echo htmlspecialchars($row['vehicle_image']); ?>" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27300%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23e5e7eb%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2720%27 fill=%27%239ca3af%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3ENo Image%3C/text%3E%3C/svg%3E';" alt="Vehicle Image">

        </div>

        <div class="vehicle-right">

            <h1><?php echo htmlspecialchars($row['vehicle_name']); ?></h1>

            <p><strong>Brand :</strong> <?php echo htmlspecialchars($row['brand']); ?></p>

            <p><strong>Vehicle Type :</strong> <?php echo htmlspecialchars($row['vehicle_type']); ?></p>

            <p><strong>Fuel :</strong> <?php echo htmlspecialchars($row['fuel_type']); ?></p>

            <p><strong>Transmission :</strong> <?php echo htmlspecialchars($row['transmission']); ?></p>

            <p><strong>Price :</strong> ₹<?php echo htmlspecialchars($row['price_per_km']); ?> / KM</p>

            <p><strong>Pickup & Return Location :</strong><br>
                📍 <?php echo htmlspecialchars($row['pickup_address']); ?>, <?php echo htmlspecialchars($row['city']); ?>
            </p>

            <p>

                <strong>Availability :</strong>

                <?php

                if ($row['status'] == "Available") {
                    echo "<span class='available-badge'>🟢 Available</span>";
                } else {
                    echo "<span class='unavailable-badge'>🔴 Currently Not Available</span>";
                }

                ?>
            </p>

            <h3>Description</h3>

            <p>

                <?php echo nl2br($row['description']); ?>

            </p>

            <?php

            if ($row['status'] == "Available") {

                ?>

                <a href="booking.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="book-btn">

                    Book Now

                </a>

                <?php

            } else {

                ?>

                <button class="book-btn unavailable-btn" disabled>

                    🔴 Currently Not Available

                </button>

                <?php

            }

            ?>

        </div>

    </div>

</section>

<?php include "includes/footer.php"; ?>