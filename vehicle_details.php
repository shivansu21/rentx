<?php
session_start();
include "includes/config.php";
include "includes/header.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM vehicles WHERE id = " . $id);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "<div class='container py-5 text-center'><div class='alert alert-danger rounded-4 py-4'><h4>Vehicle Not Found</h4><a href='index.php' class='btn btn-primary mt-2 rounded-pill'>Return to Home</a></div></div>";
    include "includes/footer.php";
    exit();
}
$row = mysqli_fetch_assoc($result);

// Maintenance check
$maintStatus = $row['maintenance_status'] ?? 'Available';
$isAvailable = ($row['status'] == "Available" && $maintStatus == "Available");

// Handle Review Submission
$reviewSuccess = false;
$reviewError = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        $reviewError = "Please log in to submit a review.";
    } else {
        $userId = (int) $_SESSION['user_id'];
        $rating = (int) $_POST['rating'];
        $comment = mysqli_real_escape_string($conn, trim($_POST['comment']));

        if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
            $insertReview = mysqli_query($conn, "INSERT INTO reviews (vehicle_id, user_id, rating, comment) VALUES ($id, $userId, $rating, '$comment')");
            if ($insertReview) {
                $reviewSuccess = true;
            } else {
                $reviewError = "Failed to save your review. Please try again.";
            }
        } else {
            $reviewError = "Please select a star rating and enter a comment.";
        }
    }
}

// Fetch Reviews & Ratings
$reviewsQuery = mysqli_query($conn, "SELECT r.*, u.fullname FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.vehicle_id = $id ORDER BY r.created_at DESC");
$reviewsCount = mysqli_num_rows($reviewsQuery);
$avgRatingQuery = mysqli_query($conn, "SELECT AVG(rating) as avg_rating FROM reviews WHERE vehicle_id = $id");
$avgRatingRow = mysqli_fetch_assoc($avgRatingQuery);
$avgRating = $avgRatingRow['avg_rating'] ? round($avgRatingRow['avg_rating'], 1) : 5.0;

// Fetch Extra Images
$imagesQuery = mysqli_query($conn, "SELECT * FROM vehicle_images WHERE vehicle_id = $id");
$extraImages = [];
while ($img = mysqli_fetch_assoc($imagesQuery)) {
    $extraImages[] = $img['image_path'];
}

// Fetch booked date ranges for calendar blackout
$bookedDatesQuery = mysqli_query($conn, "SELECT pickup_date, return_date FROM bookings WHERE vehicle_id = $id AND booking_status IN ('Approved', 'Pending')");
$bookedRanges = [];
while ($bRow = mysqli_fetch_assoc($bookedDatesQuery)) {
    $bookedRanges[] = [
        'from' => $bRow['pickup_date'],
        'to' => $bRow['return_date']
    ];
}
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-secondary"><i class="fa-solid fa-house me-1"></i>Home</a></li>
            <li class="breadcrumb-item"><a href="index.php#<?php echo strtolower($row['vehicle_type']); ?>s" class="text-decoration-none text-secondary"><?php echo htmlspecialchars($row['vehicle_type']); ?>s</a></li>
            <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page"><?php echo htmlspecialchars($row['vehicle_name']); ?></li>
        </ol>
    </nav>

    <?php if ($reviewSuccess): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Thank You!',
                    text: 'Your review has been submitted successfully.',
                    confirmColor: '#2563eb'
                });
            });
        </script>
    <?php elseif (!empty($reviewError)): ?>
        <div class="alert alert-danger rounded-4 mb-4 d-flex align-items-center gap-2">
            <i class="fa-solid fa-circle-exclamation fs-5"></i>
            <div><?php echo htmlspecialchars($reviewError); ?></div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Vehicle Gallery & Info Container -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden p-3 bg-white mb-4">
                <!-- Main Image Stage -->
                <div class="position-relative bg-light rounded-4 p-4 text-center d-flex align-items-center justify-content-center" style="min-height:380px;">
                    <img id="mainVehicleImage" 
                         src="uploads/vehicles/<?php echo htmlspecialchars($row['vehicle_image']); ?>" 
                         onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27300%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23f1f5f9%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2720%27 fill=%27%239ca3af%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3EVehicle Image%3C/text%3E%3C/svg%3E';" 
                         alt="<?php echo htmlspecialchars($row['vehicle_name']); ?>" 
                         class="img-fluid rounded-3" style="max-height: 350px; object-fit: contain; transition: all 0.3s ease;">
                    
                    <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-2">
                        <?php if ($maintStatus !== 'Available'): ?>
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-7 fw-bold shadow-sm">
                                <i class="fa-solid fa-wrench me-1"></i> <?php echo htmlspecialchars($maintStatus); ?>
                            </span>
                        <?php elseif ($isAvailable): ?>
                            <span class="badge bg-success text-white px-3 py-2 rounded-pill fs-7 fw-bold shadow-sm">
                                🟢 Available for Rent
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill fs-7 fw-bold shadow-sm">
                                🔴 Currently Booked
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-dark text-white px-3 py-2 rounded-pill fs-7 fw-bold shadow-sm">
                            ⭐ <?php echo $avgRating; ?> / 5.0 (<?php echo $reviewsCount; ?> reviews)
                        </span>
                    </div>
                </div>

                <!-- Thumbnail Strip -->
                <div class="d-flex align-items-center gap-2 mt-3 overflow-x-auto pb-2">
                    <img src="uploads/vehicles/<?php echo htmlspecialchars($row['vehicle_image']); ?>" 
                         onclick="changeMainImg(this.src)" 
                         class="img-thumbnail rounded-3 cursor-pointer thumb-active" style="width:75px; height:60px; object-fit:cover; cursor:pointer;">
                    <?php foreach ($extraImages as $extImg): ?>
                        <img src="uploads/vehicles/<?php echo htmlspecialchars($extImg); ?>" 
                             onclick="changeMainImg(this.src)" 
                             class="img-thumbnail rounded-3 cursor-pointer" style="width:75px; height:60px; object-fit:cover; cursor:pointer;">
                    <?php endforeach; ?>
                </div>

                <!-- Specs Highlights Grid -->
                <div class="row g-2 mt-3 text-center">
                    <div class="col-4">
                        <div class="bg-light p-3 rounded-3 border">
                            <i class="fa-solid fa-building text-primary fs-4 mb-1"></i>
                            <div class="small text-secondary fw-semibold">Brand</div>
                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['brand']); ?></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light p-3 rounded-3 border">
                            <i class="fa-solid fa-gas-pump text-primary fs-4 mb-1"></i>
                            <div class="small text-secondary fw-semibold">Fuel</div>
                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['fuel_type']); ?></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light p-3 rounded-3 border">
                            <i class="fa-solid fa-gears text-primary fs-4 mb-1"></i>
                            <div class="small text-secondary fw-semibold">Transmission</div>
                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['transmission']); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Reviews Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="fw-extrabold text-dark mb-1">Customer Reviews</h4>
                        <div class="d-flex align-items-center gap-2">
                            <div class="text-warning fs-5">
                                <?php
                                $fullStars = floor($avgRating);
                                for ($i = 1; $i <= 5; $i++) {
                                    echo ($i <= $fullStars) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star text-muted"></i>';
                                }
                                ?>
                            </div>
                            <span class="fw-bold text-dark fs-5"><?php echo $avgRating; ?></span>
                            <span class="text-secondary small">(Based on <?php echo $reviewsCount; ?> ratings)</span>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button class="btn btn-outline-primary rounded-pill fw-semibold px-4" data-bs-toggle="collapse" data-bs-target="#writeReviewBox">
                            <i class="fa-solid fa-pen me-1"></i> Write Review
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Review Form (Collapsed) -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="collapse mb-4" id="writeReviewBox">
                        <div class="bg-light p-4 rounded-4 border">
                            <h6 class="fw-bold text-dark mb-3">Share Your Experience</h6>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label text-secondary fw-semibold small">Rating</label>
                                    <select name="rating" class="form-select rounded-3" required>
                                        <option value="5">⭐⭐⭐⭐⭐ Excellent (5/5)</option>
                                        <option value="4">⭐⭐⭐⭐ Very Good (4/5)</option>
                                        <option value="3">⭐⭐⭐ Average (3/5)</option>
                                        <option value="2">⭐⭐ Poor (2/5)</option>
                                        <option value="1">⭐ Terrible (1/5)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-secondary fw-semibold small">Your Review</label>
                                    <textarea name="comment" class="form-control rounded-3" rows="3" placeholder="Tell us about the vehicle condition, drive quality, and service..." required></textarea>
                                </div>
                                <button type="submit" name="submit_review" class="btn btn-primary rounded-pill fw-bold px-4">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Reviews List -->
                <?php if ($reviewsCount > 0): ?>
                    <div class="d-flex flex-column gap-3">
                        <?php while ($rev = mysqli_fetch_assoc($reviewsQuery)): ?>
                            <div class="border-bottom pb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:32px; height:32px; font-size:13px;">
                                            <?php echo strtoupper(substr($rev['fullname'], 0, 1)); ?>
                                        </div>
                                        <?php echo htmlspecialchars($rev['fullname']); ?>
                                        <span class="badge bg-success-subtle text-success fs-8 rounded-pill">Verified Renter</span>
                                    </div>
                                    <span class="text-muted fs-8"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></span>
                                </div>
                                <div class="text-warning small mb-2">
                                    <?php
                                    for ($s = 1; $s <= 5; $s++) {
                                        echo ($s <= $rev['rating']) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star text-muted"></i>';
                                    }
                                    ?>
                                </div>
                                <p class="text-secondary small mb-0"><?php echo htmlspecialchars($rev['comment']); ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 bg-light rounded-4">
                        <i class="fa-regular fa-comments text-muted fs-2 mb-2"></i>
                        <p class="text-secondary mb-0">No reviews yet for this vehicle. Be the first to leave feedback!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Vehicle Details & Booking Sidebar -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold fs-7">
                            <?php echo strtoupper(htmlspecialchars($row['vehicle_type'])); ?>
                        </span>
                        <span class="text-secondary small"><i class="fa-solid fa-city me-1"></i><?php echo htmlspecialchars($row['city']); ?></span>
                    </div>

                    <h1 class="fw-extrabold text-dark mb-2"><?php echo htmlspecialchars($row['vehicle_name']); ?></h1>
                    
                    <div class="d-flex align-items-baseline gap-2 mb-4">
                        <span class="display-6 fw-extrabold text-primary">₹<?php echo htmlspecialchars($row['price_per_km']); ?></span>
                        <span class="fs-6 text-secondary fw-semibold">/ KM Estimated Rate</span>
                    </div>

                    <!-- Location Box -->
                    <div class="card bg-light border-0 rounded-3 p-3 mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:40px; height:40px;">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Pickup & Return Location</h6>
                                <p class="text-secondary small mb-0">
                                    <?php echo htmlspecialchars($row['pickup_address']); ?>, <strong><?php echo htmlspecialchars($row['city']); ?></strong>
                                </p>
                                <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1 mt-2 fs-7">
                                    <i class="fa-solid fa-route me-1"></i> Max <?php echo htmlspecialchars($row['service_radius'] ?? 30); ?> KM Service Radius
                                </span>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">Vehicle Specifications & Rules</h5>
                    <div class="bg-light p-3 rounded-3 mb-4">
                        <ul class="list-unstyled mb-0 text-secondary small d-flex flex-column gap-2">
                            <li><i class="fa-solid fa-check text-success me-2"></i> Sanitized & Inspected Before Pickup</li>
                            <li><i class="fa-solid fa-check text-success me-2"></i> Valid Commercial Insurance & RC Book</li>
                            <li><i class="fa-solid fa-check text-success me-2"></i> 24/7 Roadside Assistance Included</li>
                            <li><i class="fa-solid fa-check text-success me-2"></i> Original Driving License Required at Pickup</li>
                        </ul>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">Description</h5>
                    <p class="text-secondary leading-relaxed bg-light p-3 rounded-3 mb-4" style="white-space: pre-line;">
                        <?php echo !empty($row['description']) ? htmlspecialchars($row['description']) : 'Clean, well-maintained vehicle with full insurance coverage. Ready for instant pickup.'; ?>
                    </p>
                </div>

                <div class="pt-3 border-top">
                    <?php if ($maintStatus !== 'Available'): ?>
                        <button class="btn btn-warning btn-lg rounded-pill w-100 fw-bold py-3 text-dark" disabled>
                            <i class="fa-solid fa-wrench me-2"></i> Vehicle Under Maintenance
                        </button>
                    <?php elseif ($isAvailable): ?>
                        <a href="booking.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold py-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-bolt"></i> Reserve & Book This <?php echo htmlspecialchars($row['vehicle_type']); ?>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg rounded-pill w-100 fw-bold py-3" disabled>
                            <i class="fa-solid fa-lock me-1"></i> Currently Reserved
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeMainImg(src) {
    document.getElementById('mainVehicleImage').src = src;
}
</script>

<?php include "includes/footer.php"; ?>