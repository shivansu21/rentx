<?php include "includes/config.php"; ?>
<?php
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : "";
?>
<?php include 'includes/header.php'; ?>

<!-- Hero Section Starts -->
<section class="hero-wrapper py-5 mb-4 text-white">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1-5 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 text-white mb-3 shadow-sm backdrop-blur">
                    <span class="pulse-online me-1"></span>
                    <span class="fw-bold fs-7 tracking-wider text-uppercase text-warning">Drive Easy. Rent Smart.</span>
                </div>

                <h1 class="display-4 fw-extrabold mb-3 leading-tight text-white">
                    Rent Your Perfect <br>
                    <span class="hero-highlight-gradient">Car & Bike</span> Today
                </h1>

                <p class="fs-5 text-white opacity-85 mb-4 me-lg-4 leading-relaxed">
                    RentX connects you to premium verified vehicles nearby. Enjoy affordable per-kilometer pricing within a 30 KM service radius with instant online confirmation.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="#cars" class="btn btn-primary btn-lg rounded-pill px-4 py-3 fw-bold shadow-lg d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-car-side fs-5"></i> Browse Vehicles
                    </a>
                    <a href="about.php" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3 fw-bold d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-info fs-5"></i> Learn More
                    </a>
                </div>

                <!-- Stats counter -->
                <div class="row g-3 pt-3 border-top border-white border-opacity-15">
                    <div class="col-4">
                        <h4 class="fw-extrabold mb-0 text-white">500+</h4>
                        <small class="text-white opacity-75">Active Rides</small>
                    </div>
                    <div class="col-4">
                        <h4 class="fw-extrabold mb-0 text-white">100%</h4>
                        <small class="text-white opacity-75">Verified Fleet</small>
                    </div>
                    <div class="col-4">
                        <h4 class="fw-extrabold mb-0 text-warning">4.9 ★</h4>
                        <small class="text-white opacity-75">Customer Rating</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 text-center position-relative">
                <div class="hero-vehicle-stage p-4 p-lg-5 rounded-5 shadow-2xl position-relative overflow-hidden">
                    <div class="hero-vehicle-glow"></div>
                    <div class="speed-particles"></div>

                    <!-- Hero Vehicle Showcase Display Stage -->
                    <div class="car-stage-road-track position-relative overflow-hidden py-3">
                        <div class="car-drive-wrapper position-relative" id="heroCarDriveWrapper">
                            <img src="uploads/fortuner-suv.png" alt="Toyota Fortuner GR Sport SUV" class="img-fluid floating-hero-img position-relative" style="max-height: 250px; z-index: 3;" onerror="this.onerror=null;this.src='uploads/hero-car.png';">
                            <div class="car-dynamic-shadow"></div>
                            <div class="headlight-beam"></div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-2 position-relative mt-2" style="z-index: 3;">
                        <span class="badge bg-emerald-glass text-emerald-glow border border-emerald-glass rounded-pill px-3 py-2 fw-semibold fs-7"><i class="fa-solid fa-shield-halved me-1"></i> Fully Insured</span>
                        <span class="badge bg-cyan-glass text-cyan-glow border border-cyan-glass rounded-pill px-3 py-2 fw-semibold fs-7"><i class="fa-solid fa-location-arrow me-1"></i> 30 KM Service Radius</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Special Promo Coupons & Deals Section -->
<div class="container mb-4 position-relative" style="z-index: 10;">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 rounded-4 shadow-sm p-3 bg-gradient-emerald text-white h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge bg-white text-dark rounded-pill px-2.5 py-1 fs-7 fw-bold uppercase tracking-wider mb-2">HOT DEAL</span>
                        <h5 class="fw-extrabold mb-1">Get 20% OFF</h5>
                        <p class="small text-white opacity-90 mb-2">On your first reservation today.</p>
                    </div>
                    <div class="fs-1 text-white opacity-25 me-2"><i class="fa-solid fa-tags"></i></div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-white border-opacity-20">
                    <code class="text-warning fw-bold fs-6 tracking-widest bg-black bg-opacity-30 px-3 py-1 rounded-pill border border-warning border-opacity-50">RENTX20</code>
                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold fs-7 shadow-sm" onclick="copyCouponCode('RENTX20')">
                        <i class="fa-regular fa-copy me-1"></i> Copy
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 rounded-4 shadow-sm p-3 bg-gradient-cyan text-white h-100 position-relative overflow-hidden">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge bg-white text-dark rounded-pill px-2.5 py-1 fs-7 fw-bold uppercase tracking-wider mb-2">WEEKEND PASS</span>
                        <h5 class="fw-extrabold mb-1">₹500 Flat Off</h5>
                        <p class="small text-white opacity-90 mb-2">Valid for 3+ day trips.</p>
                    </div>
                    <div class="fs-1 text-white opacity-25 me-2"><i class="fa-solid fa-gift"></i></div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-white border-opacity-20">
                    <code class="text-warning fw-bold fs-6 tracking-widest bg-black bg-opacity-30 px-3 py-1 rounded-pill border border-warning border-opacity-50">WEEKEND500</code>
                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold fs-7 shadow-sm" onclick="copyCouponCode('WEEKEND500')">
                        <i class="fa-regular fa-copy me-1"></i> Copy
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 rounded-4 shadow-sm p-3 bg-gradient-dark text-white h-100 position-relative overflow-hidden border border-emerald-glass">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge bg-emerald-glass text-emerald-glow rounded-pill px-2.5 py-1 fs-7 fw-bold uppercase tracking-wider mb-2">ECO GREEN</span>
                        <h5 class="fw-extrabold mb-1">Free EV Charge</h5>
                        <p class="small text-white opacity-90 mb-2">Unlimited power on all EVs.</p>
                    </div>
                    <div class="fs-1 text-emerald-glow opacity-25 me-2"><i class="fa-solid fa-bolt"></i></div>
                </div>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top border-white border-opacity-20">
                    <code class="text-emerald-glow fw-bold fs-6 tracking-widest bg-black bg-opacity-30 px-3 py-1 rounded-pill border border-emerald-glass">EVGREEN</code>
                    <button type="button" class="btn btn-sm btn-emerald text-white rounded-pill px-3 fw-bold fs-7 shadow-sm" onclick="copyCouponCode('EVGREEN')">
                        <i class="fa-regular fa-copy me-1"></i> Copy
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Container -->
<div class="container mb-5 position-relative" style="z-index: 10;">
    <div class="card border-0 shadow-2xl rounded-4 p-4 p-md-5 search-card-widget">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="fw-extrabold mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-sliders text-primary"></i> Find Your Perfect Ride
            </h4>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold fs-7 d-none d-sm-inline">
                Instant Online Availability
            </span>
        </div>
        <form method="GET" action="index.php">
            <div class="row g-3 align-items-end">
                <div class="col-lg-5 col-md-6">
                    <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-magnifying-glass me-1 text-primary"></i> Search Vehicle / Brand</label>
                    <input type="text" name="search" id="liveSearchInput" class="form-control form-control-lg rounded-pill fs-6 px-4" placeholder="Search e.g. Honda City, Royal Enfield..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-filter me-1 text-primary"></i> Vehicle Category</label>
                    <select name="type" id="liveTypeFilter" class="form-select form-select-lg rounded-pill fs-6 px-4">
                        <option value="">All Categories (Cars & Bikes)</option>
                        <option value="Car" <?php if ($type == "Car") echo "selected"; ?>>🚗 Cars Only</option>
                        <option value="Bike" <?php if ($type == "Bike") echo "selected"; ?>>🏍️ Bikes Only</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-12">
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2 shadow-lg py-3">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i> Search Fleet
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Container for No Instant Search Results Message -->
<div class="container mt-4 d-none" id="noResultsMessage">
    <div class="alert alert-warning border-0 shadow-sm rounded-4 text-center py-4">
        <i class="fa-solid fa-circle-exclamation fs-1 mb-2 text-warning"></i>
        <h5 class="fw-bold">No Matching Vehicles Found</h5>
        <p class="mb-0 text-secondary">Try tweaking your search term or select "All Categories".</p>
    </div>
</div>

<!-- ================= POPULAR CARS SECTION ================= -->
<section class="py-5" id="cars">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-2">
            <div>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold fs-7 mb-2">FOUR WHEELERS</span>
                <h2 class="fw-extrabold text-dark mb-0">Popular Cars</h2>
                <p class="text-secondary mb-0">Explore premium sedans, SUVs, and compact hatchbacks.</p>
            </div>
            <a href="index.php?type=Car#cars" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">View All Cars <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php
            $sql = "SELECT * FROM vehicles WHERE vehicle_type='Car'";
            if ($search != "") {
                $sql .= " AND (vehicle_name LIKE '%$search%' OR brand LIKE '%$search%')";
            }
            $car = mysqli_query($conn, $sql);

            if (mysqli_num_rows($car) > 0) {
                while ($row = mysqli_fetch_assoc($car)) {
                    $imgUrl = "uploads/vehicles/" . htmlspecialchars($row['vehicle_image']);
                    $isAvailable = ($row['status'] == "Available");
                    ?>
                    <div class="col-lg-4 col-md-6 vehicle-card-col" 
                         data-name="<?php echo htmlspecialchars($row['vehicle_name']); ?>" 
                         data-brand="<?php echo htmlspecialchars($row['brand']); ?>" 
                         data-type="car">
                        <div class="card rentx-card h-100 shadow-sm border-0 position-relative">
                            <div class="card-img-wrapper position-relative">
                                <button type="button" class="favorite-btn position-absolute top-0 start-0 m-3 shadow-sm" title="Save to Favorites">
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                                <img src="<?php echo $imgUrl; ?>" 
                                     onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27300%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23f1f5f9%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2720%27 fill=%27%239ca3af%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3E🚗 Car Image%3C/text%3E%3C/svg%3E';"
                                     alt="<?php echo htmlspecialchars($row['vehicle_name']); ?>">
                                <span class="badge <?php echo $isAvailable ? 'bg-success text-white' : 'bg-danger text-white'; ?> status-chip rounded-pill shadow-sm px-3 py-2">
                                    <?php echo $isAvailable ? '🟢 Available' : '🔴 Booked'; ?>
                                </span>
                            </div>

                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-light text-secondary rounded-pill border px-2 py-1 fs-7 fw-semibold">
                                            <i class="fa-solid fa-building me-1"></i><?php echo htmlspecialchars($row['brand']); ?>
                                        </span>
                                        <span class="text-secondary small fw-medium">
                                            <i class="fa-solid fa-location-dot me-1 text-danger"></i><?php echo htmlspecialchars($row['city'] ?? 'Local'); ?>
                                        </span>
                                    </div>

                                    <h4 class="card-title fw-bold text-dark mb-2"><?php echo htmlspecialchars($row['vehicle_name']); ?></h4>
                                    
                                    <div class="d-flex gap-2 text-secondary small mb-3 flex-wrap">
                                        <span class="bg-light px-2 py-1 rounded border"><i class="fa-solid fa-gas-pump me-1 text-primary"></i><?php echo htmlspecialchars($row['fuel_type']); ?></span>
                                        <span class="bg-light px-2 py-1 rounded border"><i class="fa-solid fa-gears me-1 text-primary"></i><?php echo htmlspecialchars($row['transmission']); ?></span>
                                        <span class="bg-light px-2 py-1 rounded border"><i class="fa-solid fa-route me-1 text-primary"></i>Radius: <?php echo htmlspecialchars($row['service_radius'] ?? 30); ?>KM</span>
                                    </div>
                                </div>

                                <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                    <div>
                                        <small class="text-secondary d-block">Price Rate</small>
                                        <span class="price-tag">₹<?php echo htmlspecialchars($row['price_per_km']); ?> <small class="fs-7 text-secondary fw-normal">/ KM</small></span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-secondary rounded-circle btn-quick-view" style="width:40px; height:40px;" 
                                                data-id="<?php echo htmlspecialchars($row['id']); ?>"
                                                data-name="<?php echo htmlspecialchars($row['vehicle_name']); ?>"
                                                data-brand="<?php echo htmlspecialchars($row['brand']); ?>"
                                                data-type="Car"
                                                data-price="<?php echo htmlspecialchars($row['price_per_km']); ?>"
                                                data-fuel="<?php echo htmlspecialchars($row['fuel_type']); ?>"
                                                data-trans="<?php echo htmlspecialchars($row['transmission']); ?>"
                                                data-city="<?php echo htmlspecialchars($row['city']); ?>"
                                                data-status="<?php echo htmlspecialchars($row['status']); ?>"
                                                data-image="<?php echo $imgUrl; ?>"
                                                data-desc="<?php echo htmlspecialchars($row['description']); ?>"
                                                title="Quick View Specs">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <a href="vehicle_details.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary rounded-pill px-3 fw-bold">
                                            Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col-12">
                    <div class="card border-0 bg-light p-5 text-center rounded-4">
                        <i class="fa-solid fa-car-side fs-1 text-muted mb-3"></i>
                        <h4 class="fw-bold">No Cars Found</h4>
                        <p class="text-secondary mb-0">No car matching your search criteria is currently listed.</p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>

<!-- ================= POPULAR BIKES SECTION ================= -->
<section class="py-5 bg-light" id="bikes">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-2">
            <div>
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold fs-7 mb-2">TWO WHEELERS</span>
                <h2 class="fw-extrabold text-dark mb-0">Popular Bikes & Scooters</h2>
                <p class="text-secondary mb-0">Nimble, fuel-efficient motorbikes for quick city commutes.</p>
            </div>
            <a href="index.php?type=Bike#bikes" class="btn btn-outline-success rounded-pill px-4 fw-semibold">View All Bikes <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            <?php
            $sql = "SELECT * FROM vehicles WHERE vehicle_type='Bike'";
            if ($search != "") {
                $sql .= " AND (vehicle_name LIKE '%$search%' OR brand LIKE '%$search%')";
            }
            $bike = mysqli_query($conn, $sql);

            if (mysqli_num_rows($bike) > 0) {
                while ($row = mysqli_fetch_assoc($bike)) {
                    $imgUrl = "uploads/vehicles/" . htmlspecialchars($row['vehicle_image']);
                    $isAvailable = ($row['status'] == "Available");
                    ?>
                    <div class="col-lg-4 col-md-6 vehicle-card-col" 
                         data-name="<?php echo htmlspecialchars($row['vehicle_name']); ?>" 
                         data-brand="<?php echo htmlspecialchars($row['brand']); ?>" 
                         data-type="bike">
                        <div class="card rentx-card h-100 shadow-sm border-0 position-relative">
                            <div class="card-img-wrapper position-relative">
                                <button type="button" class="favorite-btn position-absolute top-0 start-0 m-3 shadow-sm" title="Save to Favorites">
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                                <img src="<?php echo $imgUrl; ?>" 
                                     onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27300%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23f1f5f9%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2720%27 fill=%27%239ca3af%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3E🏍️ Bike Image%3C/text%3E%3C/svg%3E';"
                                     alt="<?php echo htmlspecialchars($row['vehicle_name']); ?>">
                                <span class="badge <?php echo $isAvailable ? 'bg-success text-white' : 'bg-danger text-white'; ?> status-chip rounded-pill shadow-sm px-3 py-2">
                                    <?php echo $isAvailable ? '🟢 Available' : '🔴 Booked'; ?>
                                </span>
                            </div>

                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-light text-secondary rounded-pill border px-2 py-1 fs-7 fw-semibold">
                                            <i class="fa-solid fa-motorcycle me-1"></i><?php echo htmlspecialchars($row['brand']); ?>
                                        </span>
                                        <span class="text-secondary small fw-medium">
                                            <i class="fa-solid fa-location-dot me-1 text-danger"></i><?php echo htmlspecialchars($row['city'] ?? 'Local'); ?>
                                        </span>
                                    </div>

                                    <h4 class="card-title fw-bold text-dark mb-2"><?php echo htmlspecialchars($row['vehicle_name']); ?></h4>
                                    
                                    <div class="d-flex gap-2 text-secondary small mb-3 flex-wrap">
                                        <span class="bg-light px-2 py-1 rounded border"><i class="fa-solid fa-gas-pump me-1 text-success"></i><?php echo htmlspecialchars($row['fuel_type']); ?></span>
                                        <span class="bg-light px-2 py-1 rounded border"><i class="fa-solid fa-gears me-1 text-success"></i><?php echo htmlspecialchars($row['transmission']); ?></span>
                                        <span class="bg-light px-2 py-1 rounded border"><i class="fa-solid fa-route me-1 text-success"></i>Radius: <?php echo htmlspecialchars($row['service_radius'] ?? 30); ?>KM</span>
                                    </div>
                                </div>

                                <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                    <div>
                                        <small class="text-secondary d-block">Price Rate</small>
                                        <span class="price-tag text-success">₹<?php echo htmlspecialchars($row['price_per_km']); ?> <small class="fs-7 text-secondary fw-normal">/ KM</small></span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-secondary rounded-circle btn-quick-view" style="width:40px; height:40px;" 
                                                data-id="<?php echo htmlspecialchars($row['id']); ?>"
                                                data-name="<?php echo htmlspecialchars($row['vehicle_name']); ?>"
                                                data-brand="<?php echo htmlspecialchars($row['brand']); ?>"
                                                data-type="Bike"
                                                data-price="<?php echo htmlspecialchars($row['price_per_km']); ?>"
                                                data-fuel="<?php echo htmlspecialchars($row['fuel_type']); ?>"
                                                data-trans="<?php echo htmlspecialchars($row['transmission']); ?>"
                                                data-city="<?php echo htmlspecialchars($row['city']); ?>"
                                                data-status="<?php echo htmlspecialchars($row['status']); ?>"
                                                data-image="<?php echo $imgUrl; ?>"
                                                data-desc="<?php echo htmlspecialchars($row['description']); ?>"
                                                title="Quick View Specs">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <a href="vehicle_details.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-success rounded-pill px-3 fw-bold">
                                            Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col-12">
                    <div class="card border-0 bg-white p-5 text-center rounded-4 shadow-sm">
                        <i class="fa-solid fa-motorcycle fs-1 text-muted mb-3"></i>
                        <h4 class="fw-bold">No Bikes Found</h4>
                        <p class="text-secondary mb-0">No bike matching your search criteria is currently listed.</p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>

<!-- ================= WHY CHOOSE RENTX ================= -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center max-w-2xl mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold fs-7 mb-2">OUR GUARANTEE</span>
            <h2 class="fw-extrabold text-dark">Why Choose RentX?</h2>
            <p class="text-secondary">We ensure safe, transparent, and hassle-free vehicle rentals for every trip.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-light">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3 mx-auto" style="width:64px; height:64px; font-size:28px;">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Verified Vehicles</h5>
                    <p class="text-secondary small mb-0">Every vehicle undergoes rigorous safety checks before each rental.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-light">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3 mx-auto" style="width:64px; height:64px; font-size:28px;">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Per-KM Pricing</h5>
                    <p class="text-secondary small mb-0">No hidden fees or unexpected surcharges. Pay strictly based on distance.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-light">
                    <div class="d-inline-flex align-items-center justify-content-center bg-warning text-white rounded-circle mb-3 mx-auto" style="width:64px; height:64px; font-size:28px;">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Instant Booking</h5>
                    <p class="text-secondary small mb-0">Reserve your car or bike in seconds with instant digital confirmation.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-light">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle mb-3 mx-auto" style="width:64px; height:64px; font-size:28px;">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">24/7 Road Support</h5>
                    <p class="text-secondary small mb-0">Dedicated customer helpline and on-ground assistance anytime.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= HOW IT WORKS ================= -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center max-w-2xl mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold fs-7 mb-2">SIMPLE PROCESS</span>
            <h2 class="fw-extrabold text-dark">How It Works</h2>
            <p class="text-secondary">Get on the road in four quick, seamless steps.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 rounded-4 p-4 text-center h-100 shadow-sm bg-white">
                    <span class="badge bg-primary text-white rounded-circle fs-5 mb-3 mx-auto d-flex align-items-center justify-content-center" style="width:48px; height:48px;">1</span>
                    <h5 class="fw-bold">Register Account</h5>
                    <p class="text-secondary small mb-0">Sign up with your mobile number and basic contact information.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 rounded-4 p-4 text-center h-100 shadow-sm bg-white">
                    <span class="badge bg-primary text-white rounded-circle fs-5 mb-3 mx-auto d-flex align-items-center justify-content-center" style="width:48px; height:48px;">2</span>
                    <h5 class="fw-bold">Upload Licence</h5>
                    <p class="text-secondary small mb-0">Upload a clear photo of your valid government driving licence.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 rounded-4 p-4 text-center h-100 shadow-sm bg-white">
                    <span class="badge bg-primary text-white rounded-circle fs-5 mb-3 mx-auto d-flex align-items-center justify-content-center" style="width:48px; height:48px;">3</span>
                    <h5 class="fw-bold">Book Vehicle</h5>
                    <p class="text-secondary small mb-0">Choose pickup dates, enter estimated distance, and confirm booking.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 rounded-4 p-4 text-center h-100 shadow-sm bg-white">
                    <span class="badge bg-primary text-white rounded-circle fs-5 mb-3 mx-auto d-flex align-items-center justify-content-center" style="width:48px; height:48px;">4</span>
                    <h5 class="fw-bold">Enjoy Your Ride</h5>
                    <p class="text-secondary small mb-0">Pick up your vehicle at the designated hub and enjoy your journey!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= CUSTOMER REVIEWS ================= -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center max-w-2xl mx-auto mb-5">
            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fw-bold fs-7 mb-2">TESTIMONIALS</span>
            <h2 class="fw-extrabold text-dark">Loved By Thousands</h2>
            <p class="text-secondary">Read what satisfied riders say about RentX service quality.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="text-warning mb-2 fs-5">★★★★★</div>
                    <p class="text-dark fst-italic mb-3">"Super smooth booking process. The SUV was clean, well-maintained, and fuel-efficient. Will definitely book again!"</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px;">RS</div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Rahul Sharma</h6>
                            <small class="text-secondary">Verified Renter</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="text-warning mb-2 fs-5">★★★★★</div>
                    <p class="text-dark fst-italic mb-3">"Rented a sports bike for a weekend road trip. Great per-KM pricing model and instant approval from admin!"</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success text-white fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px;">PV</div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Priya Verma</h6>
                            <small class="text-secondary">Verified Renter</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-light">
                    <div class="text-warning mb-2 fs-5">★★★★★</div>
                    <p class="text-dark fst-italic mb-3">"Clean interface, fast response time, and transparent invoice details. RentX is my go-to travel app now."</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-info text-white fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px;">AS</div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Amit Singh</h6>
                            <small class="text-secondary">Verified Renter</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function copyCouponCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Coupon Copied!',
            text: 'Code ' + code + ' has been copied to your clipboard.',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    }).catch(() => {
        prompt("Copy this coupon code:", code);
    });
}

</script>

<?php include 'includes/footer.php'; ?>