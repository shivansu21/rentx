<?php

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location:login.php");
    exit();
}

include "includes/config.php";
include "includes/header.php";

$user_id=$_SESSION['user_id'];

$result=mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'");

$user=mysqli_fetch_assoc($result);

// Get booking counts for stats
$totalBookings = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE user_id='$user_id'"));
$approvedBookings = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE user_id='$user_id' AND booking_status='Approved'"));
$pendingBookings = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE user_id='$user_id' AND booking_status='Pending'"));
?>

<div class="container py-5 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <!-- Profile Header Card -->
            <div class="card border-0 shadow-2xl rounded-4 overflow-hidden mb-4">
                <div class="p-4 p-md-5 position-relative text-white" style="background: linear-gradient(135deg, #059669 0%, #10b981 50%, #06b6d4 100%);">
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <!-- Avatar Circle -->
                        <div class="profile-avatar-ring flex-shrink-0">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-white shadow-lg" style="width:90px; height:90px; font-size:36px; color:#10b981; font-weight:800;">
                                <?php echo strtoupper(substr($user['fullname'], 0, 1)); ?>
                            </div>
                        </div>
                        <div>
                            <h2 class="fw-extrabold mb-1 text-white fs-3"><?php echo htmlspecialchars($user['fullname']); ?></h2>
                            <p class="mb-1 opacity-85 fs-6"><i class="fa-solid fa-envelope me-2"></i><?php echo htmlspecialchars($user['email']); ?></p>
                            <span class="badge rounded-pill px-3 py-1 fw-semibold fs-7" style="background:rgba(255,255,255,0.25);">
                                <i class="fa-solid fa-circle-check me-1"></i> Verified Customer
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="card-body p-0">
                    <div class="row g-0 border-top">
                        <div class="col-4 text-center py-4 border-end">
                            <div class="fs-2 fw-extrabold text-emerald"><?php echo $totalBookings; ?></div>
                            <div class="text-secondary small fw-semibold">Total Bookings</div>
                        </div>
                        <div class="col-4 text-center py-4 border-end">
                            <div class="fs-2 fw-extrabold text-success"><?php echo $approvedBookings; ?></div>
                            <div class="text-secondary small fw-semibold">Approved</div>
                        </div>
                        <div class="col-4 text-center py-4">
                            <div class="fs-2 fw-extrabold text-warning"><?php echo $pendingBookings; ?></div>
                            <div class="text-secondary small fw-semibold">Pending</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Info Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header border-0 bg-transparent pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0"><i class="fa-solid fa-id-card me-2 text-primary"></i>Account Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider text-secondary mb-1"><i class="fa-solid fa-user me-1 text-primary"></i> Full Name</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider text-secondary mb-1"><i class="fa-solid fa-envelope me-1 text-primary"></i> Email Address</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider text-secondary mb-1"><i class="fa-solid fa-phone me-1 text-primary"></i> Mobile Number</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['mobile']); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider text-secondary mb-1"><i class="fa-solid fa-venus-mars me-1 text-primary"></i> Gender</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-venus-mars"></i></span>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['gender']); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider text-secondary mb-1"><i class="fa-solid fa-calendar me-1 text-primary"></i> Date of Birth</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-calendar"></i></span>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['dob'] ?? 'Not provided'); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider text-secondary mb-1"><i class="fa-solid fa-id-card me-1 text-primary"></i> Driving Licence No.</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-id-card"></i></span>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['licence_number'] ?? 'Not provided'); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider text-secondary mb-1"><i class="fa-solid fa-location-dot me-1 text-primary"></i> Address</label>
                            <div class="custom-input-group p-1">
                                <span class="input-icon-left align-self-start pt-2"><i class="fa-solid fa-location-dot"></i></span>
                                <textarea class="form-control" rows="2" readonly style="resize:none;"><?php echo htmlspecialchars($user['address'] ?? 'Not provided'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-wrap gap-3">
                <a href="edit_profile.php" class="btn-emerald-submit flex-fill" style="padding:14px; text-decoration:none; max-width:280px; font-size:15px;">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Profile
                </a>
                <a href="change_password.php" class="btn flex-fill fw-bold rounded-pill py-3 border-2" style="border-color:#10b981; color:#10b981; text-decoration:none; max-width:280px; font-size:15px;" onmouseover="this.style.background='#10b981';this.style.color='#fff'" onmouseout="this.style.background='';this.style.color='#10b981'">
                    <i class="fa-solid fa-lock me-2"></i> Change Password
                </a>
                <a href="booking_history.php" class="btn flex-fill fw-bold rounded-pill py-3 border-2" style="border-color:#64748b; color:#64748b; text-decoration:none; max-width:280px; font-size:15px;">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i> Booking History
                </a>
            </div>

        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>