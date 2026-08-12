<?php include "includes/config.php"; ?>
<?php

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";

$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : "";

?>
<?php include 'includes/header.php'; ?>
<!-- Hero Section Starts -->

<section class="hero">
    <div class="hero-content">
        <div class="hero-left">
            <span class="hero-tag">
                🚗 Drive Easy. Rent Smart.
            </span>

            <h1>
                Rent Your Perfect <br>
                Car & Bike
            </h1>

            <p>
                RentX helps you rent cars and bikes quickly, safely, and affordably.
                Book your favorite ride within 30 KM at the best price.
            </p>

            <div class="hero-buttons">
                <a href="#cars" class="browse-btn">
                    Browse Vehicles
                </a>

                <a href="about.php" class="learn-btn">
                    Learn More
                </a>
            </div>

        </div>
        <div class="hero-right">
            <div class="car-box">
                <img src="uploads/hero-car.png" alt="Car">
            </div>
            <div class="bike-box">
                <img src="uploads/hero-bike.png" alt="Bike">
            </div>
        </div>
    </div>
</section>

<!-- ================= SEARCH SECTION ================= -->

<section class="search-section">
    <div class="search-container">
        <h2>Find Your Perfect Vehicle</h2>

        <form method="GET">

            <div class="search-box">

                <div class="input-group">

                    <label>Search Vehicle</label>

                    <input type="text" name="search" placeholder="Vehicle Name or Brand" value="<?php echo $search; ?>">

                </div>

                <div class="input-group">

                    <label>Vehicle Type</label>

                    <select name="type">

                        <option value="">All</option>

                        <option value="Car" <?php if ($type == "Car")
                            echo "selected"; ?>>
                            Car
                        </option>

                        <option value="Bike" <?php if ($type == "Bike")
                            echo "selected"; ?>>
                            Bike
                        </option>

                    </select>

                </div>

                <div class="input-group">

                    <button type="submit">

                        Search Vehicle

                    </button>

                </div>

            </div>

        </form>

    </div>

</section>

<!-- ================= POPULAR CARS ================= -->

<section class="popular-cars" id="cars">

    <div class="section-title">
        <h2>Popular Cars</h2>
        <p>Choose your favorite car for your next trip.</p>
    </div>

    <div class="car-container">

        <?php

        $sql = "SELECT * FROM vehicles WHERE vehicle_type='Car'";

        if ($search != "") {
            $sql .= " AND (vehicle_name LIKE '%$search%' OR brand LIKE '%$search%')";
        }

        $car = mysqli_query($conn, $sql);

        if (mysqli_num_rows($car) > 0) {

            while ($row = mysqli_fetch_assoc($car)) {

                ?>

                <div class="car-card">

                    <div class="car-image">
                        <img src="uploads/vehicles/<?php echo htmlspecialchars($row['vehicle_image']); ?>" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27300%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23e5e7eb%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2720%27 fill=%27%239ca3af%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3ENo Image%3C/text%3E%3C/svg%3E';"
                            alt="<?php echo htmlspecialchars($row['vehicle_name']); ?>">
                    </div>

                    <h3><?php echo htmlspecialchars($row['vehicle_name']); ?></h3>

                    <p>
                        <?php echo htmlspecialchars($row['fuel_type']); ?>
                        |
                        <?php echo htmlspecialchars($row['transmission']); ?>
                    </p>

                    <h4>₹<?php echo htmlspecialchars($row['price_per_km']); ?> / KM</h4>

                    <?php
                    if ($row['status'] == "Available") {
                        ?>
                        <p class="available-badge">🟢 Available</p>
                        <?php
                    } else {
                        ?>
                        <p class="unavailable-badge">🔴 Unavailable</p>
                        <?php
                    }
                    ?>

                    <a href="vehicle_details.php?id=<?php echo htmlspecialchars($row['id']); ?>">
                        View Details
                    </a>
                </div>

                <?php

            }

        } else {

            ?>

            <div class="no-result">

                <h3>No Cars Found</h3>

                <p>Try another search.</p>

            </div>

            <?php

        }

        ?>

    </div>

</section>
<!-- ================= POPULAR BIKES ================= -->

<section class="popular-bikes" id="bikes">

    <div class="section-title">
        <h2>Popular Bikes</h2>
        <p>Choose your favorite bike for your next ride.</p>
    </div>

    <div class="bike-container">

        <?php

        $sql = "SELECT * FROM vehicles WHERE vehicle_type='Bike'";

        if ($search != "") {
            $sql .= " AND (vehicle_name LIKE '%$search%' OR brand LIKE '%$search%')";
        }

        $bike = mysqli_query($conn, $sql);

        if (mysqli_num_rows($bike) > 0) {

            while ($row = mysqli_fetch_assoc($bike)) {

                ?>

                <div class="bike-card">

                    <div class="bike-image">
                        <img src="uploads/vehicles/<?php echo htmlspecialchars($row['vehicle_image']); ?>" onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27300%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23e5e7eb%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2720%27 fill=%27%239ca3af%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3ENo Image%3C/text%3E%3C/svg%3E';"
                            alt="<?php echo htmlspecialchars($row['vehicle_name']); ?>">
                    </div>

                    <h3><?php echo htmlspecialchars($row['vehicle_name']); ?></h3>

                    <p>
                        <?php echo htmlspecialchars($row['fuel_type']); ?>
                        |
                        <?php echo htmlspecialchars($row['transmission']); ?>
                    </p>

                    <h4>₹<?php echo htmlspecialchars($row['price_per_km']); ?> / KM</h4>

                    <?php
                    if ($row['status'] == "Available") {
                        ?>
                        <p class="available-badge">🟢 Available</p>
                        <?php
                    } else {
                        ?>
                        <p class="unavailable-badge">🔴 Unavailable</p>
                        <?php
                    }
                    ?>

                    <a href="vehicle_details.php?id=<?php echo htmlspecialchars($row['id']); ?>">
                        View Details
                    </a>
                </div>

                <?php

            }

        } else {

            ?>

            <div class="no-result">

                <h3>No Bikes Found</h3>

                <p>Try another search.</p>

            </div>

            <?php

        }

        ?>

    </div>

</section>

<!-- ================= WHY CHOOSE RENTX ================= -->

<section class="why-choose">
    <div class="section-title">
        <h2>Why Choose RentX?</h2>
        <p>We provide a safe, reliable and affordable vehicle rental service.</p>
    </div>

    <div class="feature-container">
        <div class="feature-card">
            <div class="feature-icon">🚗</div>
            <h3>Verified Vehicles</h3>
            <p>All cars and bikes are verified before listing.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">💰</div>
            <h3>Affordable Pricing</h3>
            <p>Pay only according to the distance travelled.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">⚡</div>
            <h3>Easy Booking</h3>
            <p>Book your vehicle in just a few simple steps.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">📞</div>
            <h3>24/7 Support</h3>
            <p>Our support team is always available to help you.</p>
        </div>
    </div>
</section>

<!-- ================= HOW IT WORKS ================= -->

<section class="how-it-works">
    <div class="section-title">
        <h2>How It Works</h2>
        <p>Rent a vehicle in four simple steps.</p>
    </div>

    <div class="steps-container">
        <div class="step-card">
            <div class="step-number">1</div>
            <h3>Register</h3>
            <p>Create your account on RentX.</p>
        </div>

        <div class="step-card">
            <div class="step-number">2</div>
            <h3>Upload Licence</h3>
            <p>Upload your valid driving licence.</p>
        </div>

        <div class="step-card">
            <div class="step-number">3</div>
            <h3>Book Vehicle</h3>
            <p>Select your vehicle and confirm booking.</p>
        </div>

        <div class="step-card">
            <div class="step-number">4</div>
            <h3>Dummy Payment</h3>
            <p>Complete payment and enjoy your ride.</p>
        </div>
    </div>
</section>

<!-- ================= CUSTOMER REVIEWS ================= -->

<section class="reviews">
    <div class="section-title">
        <h2>Customer Reviews</h2>
        <p>What our customers say about RentX.</p>
    </div>

    <div class="review-container">
        <div class="review-card">
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <h3>Rahul Sharma</h3>
            <p>"Easy booking process and the vehicle was in excellent condition."</p>
        </div>

        <div class="review-card">
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <h3>Priya Verma</h3>
            <p>"Affordable pricing and smooth booking experience."</p>
        </div>

        <div class="review-card">
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <h3>Amit Singh</h3>
            <p>"Very easy to use website. Highly recommended."</p>
        </div>
    </div>
</section>
<!-- Hero Section Ends -->
<?php include 'includes/footer.php'; ?>
<script src="js/script.js"></script>