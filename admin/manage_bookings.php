<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit();
}

include "../includes/config.php";

$query = mysqli_query($conn, "
    SELECT bookings.*, users.fullname, users.email, users.mobile, users.licence_number, users.licence_image, vehicles.vehicle_name, vehicles.vehicle_type
    FROM bookings
    INNER JOIN users ON bookings.user_id = users.id
    INNER JOIN vehicles ON bookings.vehicle_id = vehicles.id
    ORDER BY bookings.id DESC
");

$activePage = 'manage_bookings';
?>

<?php include "partials_sidebar.php"; ?>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-extrabold text-dark mb-1"><i class="fa-solid fa-calendar-check text-primary me-2"></i>Manage Bookings & Licence Verification</h4>
            <p class="text-secondary small mb-0">Review customer driving licences, inspect booking add-ons, and approve or reject applications.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-secondary fs-7 text-uppercase">
                <tr>
                    <th>Booking ID</th>
                    <th>Customer Info</th>
                    <th>Vehicle Details</th>
                    <th>Protection & Add-ons</th>
                    <th>Pickup / Return</th>
                    <th>Total Fare</th>
                    <th>Status</th>
                    <th class="text-center">Action & Licence</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query) === 0): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-calendar-xmark fs-1 mb-2 d-block text-muted"></i>
                            No bookings found in system.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                    <?php
                    $status = $row['booking_status'];
                    $badgeClass = ($status == 'Approved') ? 'bg-success text-white' : (($status == 'Rejected') ? 'bg-danger text-white' : 'bg-warning text-dark');
                    ?>
                    <tr>
                        <td class="fw-bold text-primary">#<?php echo $row['id']; ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['fullname']); ?></div>
                            <small class="text-secondary d-block"><i class="fa-solid fa-phone me-1"></i><?php echo htmlspecialchars($row['mobile']); ?></small>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark"><?php echo htmlspecialchars($row['vehicle_name']); ?></div>
                            <span class="badge bg-light text-secondary border fs-7"><?php echo htmlspecialchars($row['vehicle_type']); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill mb-1">
                                <i class="fa-solid fa-shield me-1"></i><?php echo htmlspecialchars($row['insurance_plan'] ?? 'Basic'); ?>
                            </span>
                            <div class="small text-muted"><?php echo htmlspecialchars($row['add_ons'] ?? 'None'); ?></div>
                        </td>
                        <td>
                            <small class="d-block text-dark"><strong>Out:</strong> <?php echo htmlspecialchars($row['pickup_date']); ?></small>
                            <small class="d-block text-dark"><strong>In:</strong> <?php echo htmlspecialchars($row['return_date']); ?></small>
                        </td>
                        <td class="fw-extrabold text-success fs-6">₹<?php echo number_format($row['total_amount'], 2); ?></td>
                        <td>
                            <span class="badge <?php echo $badgeClass; ?> rounded-pill px-3 py-1 fs-7 shadow-sm">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 me-1 mb-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#licenceModal<?php echo $row['id']; ?>">
                                <i class="fa-solid fa-id-card me-1"></i> Inspect Licence
                            </button>

                            <?php if ($status === 'Pending'): ?>
                                <a href="approve_booking.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success rounded-pill px-3 py-1 me-1 mb-1 shadow-sm">
                                    <i class="fa-solid fa-check me-1"></i>Approve
                                </a>
                                <a href="reject_booking.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 mb-1">
                                    <i class="fa-solid fa-xmark me-1"></i>Reject
                                </a>
                            <?php elseif ($status === 'Approved'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 mb-1"><i class="fa-solid fa-circle-check me-1"></i>Approved</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 mb-1"><i class="fa-solid fa-circle-xmark me-1"></i>Rejected</span>
                            <?php endif; ?>

                            <!-- Licence Verification Modal -->
                            <div class="modal fade text-start" id="licenceModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content rounded-4 border-0 shadow-lg">
                                        <div class="modal-header bg-dark text-white rounded-top-4 p-4">
                                            <h5 class="modal-title fw-bold">
                                                <i class="fa-solid fa-id-card text-primary me-2"></i> Licence Verification — Booking #<?php echo $row['id']; ?>
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-4">
                                                <div class="col-md-6 text-center">
                                                    <div class="bg-light p-3 rounded-4 border">
                                                        <h6 class="fw-bold text-secondary mb-3">Uploaded Driving Licence Document</h6>
                                                        <img src="../uploads/licences/<?php echo htmlspecialchars($row['licence_image']); ?>" 
                                                             onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27400%27 height=%27250%27%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23e2e8f0%27/%3E%3Ctext x=%2750%25%27 y=%2750%25%27 font-family=%27sans-serif%27 font-size=%2716%27 fill=%27%2364748b%27 text-anchor=%27middle%27 dominant-baseline=%27middle%27%3EDriving Licence Image%3C/text%3E%3C/svg%3E';" 
                                                             alt="Driving Licence" class="img-fluid rounded-3 shadow-sm" style="max-height:220px; object-fit:contain;">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold text-dark mb-3">Renter & Licence Details</h6>
                                                    <ul class="list-group list-group-flush mb-3 small">
                                                        <li class="list-group-item d-flex justify-content-between px-0">
                                                            <span class="text-secondary">Full Name:</span>
                                                            <strong class="text-dark"><?php echo htmlspecialchars($row['fullname']); ?></strong>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between px-0">
                                                            <span class="text-secondary">Mobile Contact:</span>
                                                            <strong class="text-dark"><?php echo htmlspecialchars($row['mobile']); ?></strong>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between px-0">
                                                            <span class="text-secondary">Email:</span>
                                                            <strong class="text-dark"><?php echo htmlspecialchars($row['email']); ?></strong>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between px-0 bg-light p-2 rounded">
                                                            <span class="text-secondary">Licence No:</span>
                                                            <strong class="text-primary fs-6"><?php echo htmlspecialchars($row['licence_number']); ?></strong>
                                                        </li>
                                                    </ul>

                                                    <div class="d-flex gap-2 mt-4">
                                                        <?php if ($status === 'Pending'): ?>
                                                            <a href="approve_booking.php?id=<?php echo $row['id']; ?>" class="btn btn-success rounded-pill w-100 fw-bold py-2">
                                                                <i class="fa-solid fa-check me-1"></i> Approve Application
                                                            </a>
                                                            <a href="reject_booking.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger rounded-pill w-100 fw-bold py-2">
                                                                <i class="fa-solid fa-xmark me-1"></i> Reject
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "partials_end.php"; ?>


