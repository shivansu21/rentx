<?php
session_start();
include 'includes/config.php';

// Step 1: Verify email exists and start OTP session
if (isset($_POST['request_otp'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        // Generate 6-digit OTP and store in session
        $otp = rand(100000, 999999);
        $_SESSION['reset_otp']   = $otp;
        $_SESSION['reset_email'] = $email;
        $_SESSION['otp_time']    = time();
        // In production you would email this OTP. For now show it via alert (dev mode).
        echo "<script>
            sessionStorage.setItem('devOtp', '$otp');
            Swal.fire({
                icon: 'info',
                title: '📧 OTP Sent!',
                html: '<b>Demo Mode:</b> Your OTP is <span style=\"font-size:28px;font-weight:900;color:#10b981;letter-spacing:6px\">$otp</span><br><small class=\"text-muted\">In production this would be emailed to you.</small>',
                confirmButtonColor: '#10b981'
            });
        </script>";
        $_SESSION['otp_step'] = 2;
    } else {
        echo "<script>
            Swal.fire({ icon:'error', title:'Email Not Found', text:'No account exists with this email address.', confirmButtonColor:'#10b981' });
        </script>";
    }
}

// Step 2: Verify OTP
if (isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];
    $expired = (time() - ($_SESSION['otp_time'] ?? 0)) > 600; // 10 min expiry

    if ($expired) {
        unset($_SESSION['reset_otp'], $_SESSION['reset_email'], $_SESSION['otp_time'], $_SESSION['otp_step']);
        echo "<script>Swal.fire({ icon:'error', title:'OTP Expired', text:'Your OTP expired. Please request a new one.', confirmButtonColor:'#10b981' });</script>";
    } elseif ($entered_otp == ($_SESSION['reset_otp'] ?? '')) {
        $_SESSION['otp_step'] = 3;
        echo "<script>Swal.fire({ icon:'success', title:'OTP Verified!', text:'Now set your new password.', showConfirmButton:false, timer:1500 });</script>";
    } else {
        echo "<script>Swal.fire({ icon:'error', title:'Wrong OTP', text:'The OTP you entered is incorrect. Please try again.', confirmButtonColor:'#10b981' });</script>";
    }
}

// Step 3: Reset password
if (isset($_POST['reset_password'])) {
    $newpassword     = $_POST['newpassword'];
    $confirmpassword = $_POST['confirmpassword'];

    if ($newpassword !== $confirmpassword) {
        echo "<script>Swal.fire({ icon:'error', title:'Mismatch', text:'Passwords do not match.', confirmButtonColor:'#10b981' });</script>";
    } elseif (strlen($newpassword) < 8) {
        echo "<script>Swal.fire({ icon:'error', title:'Too Short', text:'Password must be at least 8 characters.', confirmButtonColor:'#10b981' });</script>";
    } else {
        $email    = $_SESSION['reset_email'];
        $hashed   = password_hash($newpassword, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE email='$email'");
        unset($_SESSION['reset_otp'], $_SESSION['reset_email'], $_SESSION['otp_time'], $_SESSION['otp_step']);
        echo "<script>
            Swal.fire({ icon:'success', title:'Password Reset!', text:'Your password has been updated. Please log in.', confirmButtonColor:'#10b981' })
            .then(() => { window.location='login.php'; });
        </script>";
    }
}

$step = $_SESSION['otp_step'] ?? 1;
include 'includes/header.php';
?>

<div class="container py-5 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">

            <div class="card border-0 shadow-2xl rounded-4 overflow-hidden">
                <!-- Header -->
                <div class="card-header border-0 text-white p-4 text-center" style="background: linear-gradient(135deg, #059669 0%, #10b981 60%, #06b6d4 100%);">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle mb-3 shadow" style="width:56px; height:56px; font-size:24px; color:#10b981;">
                        <?php if ($step === 1): ?>
                            <i class="fa-solid fa-envelope-open-text"></i>
                        <?php elseif ($step === 2): ?>
                            <i class="fa-solid fa-shield-halved"></i>
                        <?php else: ?>
                            <i class="fa-solid fa-key"></i>
                        <?php endif; ?>
                    </div>
                    <h4 class="fw-extrabold text-white mb-1">
                        <?php
                        if ($step === 1) echo 'Forgot Password';
                        elseif ($step === 2) echo 'Enter OTP';
                        else echo 'New Password';
                        ?>
                    </h4>
                    <!-- Step Progress -->
                    <div class="d-flex justify-content-center gap-2 mt-2">
                        <span class="badge rounded-pill px-3 py-1" style="background:<?php echo $step >= 1 ? 'rgba(255,255,255,0.9)' : 'rgba(255,255,255,0.3)'; ?>; color:#059669;">1 Email</span>
                        <span class="badge rounded-pill px-3 py-1" style="background:<?php echo $step >= 2 ? 'rgba(255,255,255,0.9)' : 'rgba(255,255,255,0.3)'; ?>; color:#059669;">2 Verify OTP</span>
                        <span class="badge rounded-pill px-3 py-1" style="background:<?php echo $step >= 3 ? 'rgba(255,255,255,0.9)' : 'rgba(255,255,255,0.3)'; ?>; color:#059669;">3 Reset</span>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">

                    <?php if ($step === 1): ?>
                    <!-- STEP 1: Enter Email -->
                    <p class="text-secondary mb-4 text-center">Enter your registered email to receive a verification OTP.</p>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-envelope me-1 text-primary"></i> Email Address</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                            </div>
                        </div>
                        <button type="submit" name="request_otp" class="btn-emerald-submit">
                            <i class="fa-solid fa-paper-plane me-2"></i> Send OTP
                        </button>
                    </form>

                    <?php elseif ($step === 2): ?>
                    <!-- STEP 2: Verify OTP -->
                    <p class="text-secondary mb-4 text-center">Enter the 6-digit OTP sent to <strong><?php echo htmlspecialchars($_SESSION['reset_email']); ?></strong></p>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-shield-halved me-1 text-primary"></i> One-Time Password</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-hashtag"></i></span>
                                <input type="text" name="otp" class="form-control" placeholder="Enter 6-digit OTP" maxlength="6" required inputmode="numeric" style="letter-spacing:6px; font-size:20px; font-weight:800;">
                            </div>
                            <small class="text-secondary mt-1 d-block">OTP expires in 10 minutes.</small>
                        </div>
                        <button type="submit" name="verify_otp" class="btn-emerald-submit">
                            <i class="fa-solid fa-shield-check me-2"></i> Verify OTP
                        </button>
                    </form>
                    <form method="POST" class="mt-3 text-center">
                        <button type="submit" name="request_otp" class="btn btn-link text-secondary fw-semibold p-0 fs-7" style="text-decoration:none;">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['reset_email']); ?>">
                            <i class="fa-solid fa-rotate me-1"></i> Resend OTP
                        </button>
                    </form>

                    <?php else: ?>
                    <!-- STEP 3: Set New Password -->
                    <p class="text-secondary mb-4 text-center">Identity verified! Set a strong new password below.</p>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-key me-1 text-primary"></i> New Password</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-key"></i></span>
                                <input type="password" name="newpassword" id="newPass" class="form-control" placeholder="Min. 8 characters" required>
                                <button type="button" class="toggle-password-btn" data-target="newPass"><i class="fa-regular fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-lock me-1 text-primary"></i> Confirm Password</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="confirmpassword" id="confPass" class="form-control" placeholder="Repeat your password" required>
                                <button type="button" class="toggle-password-btn" data-target="confPass"><i class="fa-regular fa-eye"></i></button>
                            </div>
                        </div>
                        <button type="submit" name="reset_password" class="btn-emerald-submit">
                            <i class="fa-solid fa-shield-check me-2"></i> Reset Password
                        </button>
                    </form>
                    <?php endif; ?>

                    <div class="text-center mt-4 pt-3 border-top">
                        <a href="login.php" class="fw-bold text-primary text-decoration-none fs-7">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>