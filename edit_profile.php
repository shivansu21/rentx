<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location:login.php");
    exit();
}

include "includes/config.php";
include "includes/header.php";

$user_id = $_SESSION['user_id'];

if(isset($_POST['update']))
{
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $mobile   = mysqli_real_escape_string($conn, $_POST['mobile']);
    $dob      = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender   = mysqli_real_escape_string($conn, $_POST['gender']);
    $address  = mysqli_real_escape_string($conn, $_POST['address']);

    mysqli_query($conn, "
        UPDATE users
        SET fullname='$fullname',
            mobile='$mobile',
            dob='$dob',
            gender='$gender',
            address='$address'
        WHERE id='$user_id'
    ");

    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Profile Updated!',
            text: 'Your profile has been updated successfully.',
            showConfirmButton: false,
            timer: 1800
        }).then(() => { window.location='profile.php'; });
    </script>";
}

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'"));
?>

<div class="container py-5 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-2xl rounded-4 overflow-hidden">
                <!-- Header -->
                <div class="card-header border-0 text-white p-4 p-md-5 text-center" style="background: linear-gradient(135deg, #059669 0%, #10b981 50%, #06b6d4 100%);">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle mb-3 shadow-lg" style="width:60px; height:60px; font-size:26px; color:#10b981;">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <h2 class="fw-extrabold mb-1 text-white">Edit Profile</h2>
                    <p class="text-white opacity-85 mb-0">Update your personal information below</p>
                </div>

                <!-- Form Body -->
                <div class="card-body p-4 p-md-5">
                    <form action="" method="POST">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-user me-1 text-primary"></i> Full Name</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" required placeholder="Your full name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-envelope me-1 text-primary"></i> Email Address</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-phone me-1 text-primary"></i> Mobile Number</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-phone"></i></span>
                                    <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($user['mobile']); ?>" required placeholder="+91 9876543210">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-calendar me-1 text-primary"></i> Date of Birth</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-calendar"></i></span>
                                    <input type="date" name="dob" class="form-control" value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-venus-mars me-1 text-primary"></i> Gender</label>
                            <div class="btn-group w-100 gender-toggle-group shadow-sm" role="group">
                                <input type="radio" class="btn-check" name="gender" id="gMale" value="Male" <?php echo ($user['gender'] === 'Male') ? 'checked' : ''; ?>>
                                <label class="btn fw-bold" for="gMale"><i class="fa-solid fa-mars me-1"></i> Male</label>

                                <input type="radio" class="btn-check" name="gender" id="gFemale" value="Female" <?php echo ($user['gender'] === 'Female') ? 'checked' : ''; ?>>
                                <label class="btn fw-bold" for="gFemale"><i class="fa-solid fa-venus me-1"></i> Female</label>

                                <input type="radio" class="btn-check" name="gender" id="gOther" value="Other" <?php echo ($user['gender'] === 'Other') ? 'checked' : ''; ?>>
                                <label class="btn fw-bold" for="gOther"><i class="fa-solid fa-genderless me-1"></i> Other</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-location-dot me-1 text-primary"></i> Full Address</label>
                            <div class="custom-input-group p-1">
                                <span class="input-icon-left align-self-start pt-2"><i class="fa-solid fa-location-dot"></i></span>
                                <textarea name="address" class="form-control" rows="3" placeholder="Enter your full residential address..." style="resize:none;"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-3 flex-wrap">
                            <button type="submit" name="update" class="btn-emerald-submit flex-fill" style="max-width:280px;">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Save Changes
                            </button>
                            <a href="profile.php" class="btn flex-fill fw-bold rounded-pill py-3 border-2 text-center" style="border-color:#64748b; color:#64748b; text-decoration:none; max-width:200px;">
                                <i class="fa-solid fa-arrow-left me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>