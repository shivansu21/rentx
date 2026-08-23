<?php
include "includes/config.php";
include "includes/header.php";

$search = isset($_GET['search'])       ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$type   = isset($_GET['type'])         ? mysqli_real_escape_string($conn, $_GET['type'])   : "";
$fuel   = isset($_GET['fuel'])         ? mysqli_real_escape_string($conn, $_GET['fuel'])   : "";
$trans  = isset($_GET['trans'])        ? mysqli_real_escape_string($conn, $_GET['trans'])  : "";
$sort   = isset($_GET['sort'])         ? $_GET['sort']                                      : "newest";
$minp   = isset($_GET['min_price'])    ? (float)$_GET['min_price']                         : 0;
$maxp   = isset($_GET['max_price'])    ? (float)$_GET['max_price']                         : 9999;

$sql = "SELECT * FROM vehicles WHERE 1=1";
if ($search !== "")  $sql .= " AND (vehicle_name LIKE '%$search%' OR brand LIKE '%$search%')";
if ($type   !== "")  $sql .= " AND vehicle_type = '$type'";
if ($fuel   !== "")  $sql .= " AND fuel_type = '$fuel'";
if ($trans  !== "")  $sql .= " AND transmission = '$trans'";
$sql .= " AND price_per_km BETWEEN '$minp' AND '$maxp'";

switch ($sort) {
    case 'price_asc':  $sql .= " ORDER BY price_per_km ASC";  break;
    case 'price_desc': $sql .= " ORDER BY price_per_km DESC"; break;
    case 'name_asc':   $sql .= " ORDER BY vehicle_name ASC";  break;
    default:           $sql .= " ORDER BY id DESC";            break;
}

$result     = mysqli_query($conn, $sql);
$totalFound = mysqli_num_rows($result);
?>

<div class="container py-5 my-3">
    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="fw-extrabold mb-1"><i class="fa-solid fa-magnifying-glass me-2 text-primary"></i>Search Results</h2>
        <p class="text-secondary mb-0">
            <?php echo $totalFound; ?> vehicle<?php echo $totalFound !== 1 ? 's' : ''; ?> found
            <?php echo $search !== '' ? ' for <strong>"' . htmlspecialchars($search) . '"</strong>' : ''; ?>
        </p>
    </div>

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top:90px;">
                <div class="card-header border-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold mb-0"><i class="fa-solid fa-sliders me-2 text-primary"></i>Filter Results</h6>
                </div>
                <div class="card-body px-4 pb-4 pt-3">
                    <form method="GET">
                        <?php if ($search): ?>
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <?php endif; ?>

                        <!-- Vehicle Type -->
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-7 text-uppercase text-secondary mb-2">Vehicle Type</label>
                            <div class="d-flex flex-column gap-2">
                                <?php foreach (['','Car','Bike'] as $t): ?>
                                <label class="d-flex align-items-center gap-2 fw-semibold fs-7" style="cursor:pointer;">
                                    <input type="radio" name="type" value="<?php echo $t; ?>" <?php echo $type === $t ? 'checked' : ''; ?> style="accent-color:#10b981;">
                                    <?php echo $t === '' ? 'All Types' : $t; ?>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Fuel Type -->
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-7 text-uppercase text-secondary mb-2">Fuel Type</label>
                            <select name="fuel" class="form-select form-select-sm rounded-3 border-0 shadow-sm">
                                <option value="" <?php echo $fuel==='' ? 'selected':'' ?>>All Fuel Types</option>
                                <option value="Petrol"  <?php echo $fuel==='Petrol'  ? 'selected':'' ?>>Petrol</option>
                                <option value="Diesel"  <?php echo $fuel==='Diesel'  ? 'selected':'' ?>>Diesel</option>
                                <option value="Electric"<?php echo $fuel==='Electric'? 'selected':'' ?>>Electric</option>
                                <option value="Hybrid"  <?php echo $fuel==='Hybrid'  ? 'selected':'' ?>>Hybrid</option>
                            </select>
                        </div>

                        <!-- Transmission -->
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-7 text-uppercase text-secondary mb-2">Transmission</label>
                            <select name="trans" class="form-select form-select-sm rounded-3 border-0 shadow-sm">
                                <option value="" <?php echo $trans==='' ? 'selected':'' ?>>All Types</option>
                                <option value="Automatic"<?php echo $trans==='Automatic'? 'selected':'' ?>>Automatic</option>
                                <option value="Manual"   <?php echo $trans==='Manual'   ? 'selected':'' ?>>Manual</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-7 text-uppercase text-secondary mb-2">Price per KM (₹)</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="number" name="min_price" value="<?php echo $minp > 0 ? $minp : ''; ?>" placeholder="Min" min="0" class="form-control form-control-sm rounded-3 border-0 shadow-sm text-center">
                                <span class="text-secondary fw-bold">–</span>
                                <input type="number" name="max_price" value="<?php echo $maxp < 9999 ? $maxp : ''; ?>" placeholder="Max" min="0" class="form-control form-control-sm rounded-3 border-0 shadow-sm text-center">
                            </div>
                        </div>

                        <!-- Sort -->
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-7 text-uppercase text-secondary mb-2">Sort By</label>
                            <select name="sort" class="form-select form-select-sm rounded-3 border-0 shadow-sm">
                                <option value="newest"     <?php echo $sort==='newest'     ? 'selected':'' ?>>Newest First</option>
                                <option value="price_asc"  <?php echo $sort==='price_asc'  ? 'selected':'' ?>>Price: Low to High</option>
                                <option value="price_desc" <?php echo $sort==='price_desc' ? 'selected':'' ?>>Price: High to Low</option>
                                <option value="name_asc"   <?php echo $sort==='name_asc'   ? 'selected':'' ?>>Name A–Z</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-emerald-submit w-100" style="padding:11px;">
                            <i class="fa-solid fa-filter me-2"></i> Apply Filters
                        </button>
                        <a href="search.php" class="btn btn-light w-100 fw-semibold rounded-pill py-2 mt-2 text-secondary fs-7">
                            <i class="fa-solid fa-rotate-left me-1"></i> Clear Filters
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Grid -->
        <div class="col-lg-9">
            <?php if ($totalFound === 0): ?>
            <!-- Empty State -->
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mx-auto mb-3" style="width:90px;height:90px;background:linear-gradient(135deg,#d1fae5,#a7f3d0);font-size:40px;color:#10b981;">
                    <i class="fa-solid fa-car-burst"></i>
                </div>
                <h4 class="fw-bold mb-2">No Vehicles Found</h4>
                <p class="text-secondary mb-4">Try adjusting your filters or search with different keywords.</p>
                <a href="search.php" class="btn-emerald-submit" style="padding:12px 28px;text-decoration:none;width:auto;display:inline-flex;margin:0 auto;">
                    <i class="fa-solid fa-rotate-left me-2"></i> Clear All Filters
                </a>
            </div>

            <?php else: ?>
            <div class="row g-4">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 vehicle-search-card" style="transition:transform 0.2s,box-shadow 0.2s;">
                        <div class="position-relative">
                            <img src="uploads/vehicles/<?php echo htmlspecialchars($row['vehicle_image']); ?>"
                                 alt="<?php echo htmlspecialchars($row['vehicle_name']); ?>"
                                 class="w-100" style="height:180px; object-fit:cover;"
                                 onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27200%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23d1fae5%27/%3E%3Ctext x=%2750%25%27 y=%2755%25%27 font-family=%27sans-serif%27 font-size=%2738%27 fill=%27%2310b981%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3E🚗%3C/text%3E%3C/svg%3E';">
                            <div class="position-absolute top-0 end-0 m-2">
                                <?php if ($row['status'] === 'Available'): ?>
                                    <span class="badge rounded-pill fw-bold px-3 py-1 shadow-sm" style="background:#10b981;color:#fff;"><i class="fa-solid fa-circle me-1" style="font-size:7px;"></i> Available</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill fw-bold px-3 py-1 shadow-sm bg-secondary text-white"><i class="fa-solid fa-circle me-1" style="font-size:7px;"></i> Unavailable</span>
                                <?php endif; ?>
                            </div>
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge rounded-pill fw-bold px-2 py-1" style="background:rgba(0,0,0,0.5);color:#fff;font-size:10px;"><?php echo htmlspecialchars($row['vehicle_type']); ?></span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="fw-extrabold mb-1"><?php echo htmlspecialchars($row['vehicle_name']); ?></h6>
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 fs-7"><i class="fa-solid fa-tag me-1"></i><?php echo htmlspecialchars($row['brand']); ?></span>
                                <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 fs-7"><i class="fa-solid fa-gas-pump me-1"></i><?php echo htmlspecialchars($row['fuel_type']); ?></span>
                                <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 fs-7"><i class="fa-solid fa-gears me-1"></i><?php echo htmlspecialchars($row['transmission']); ?></span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-3">
                                <div>
                                    <span class="fs-5 fw-extrabold text-primary">₹<?php echo htmlspecialchars($row['price_per_km']); ?></span>
                                    <span class="text-secondary fs-7"> / KM</span>
                                </div>
                                <a href="vehicle_details.php?id=<?php echo $row['id']; ?>" class="btn fw-bold rounded-pill px-3 py-2 fs-7" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;">
                                    <i class="fa-solid fa-arrow-right me-1"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.vehicle-search-card:hover { transform:translateY(-4px); box-shadow:0 16px 40px rgba(0,0,0,0.12) !important; }
</style>

<?php include "includes/footer.php"; ?>
