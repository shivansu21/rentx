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

if(isset($_POST['change']))
{
    $old     = $_POST['old_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $check = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
    $user  = mysqli_fetch_assoc($check);

    if ($user && password_verify($old, $user['password']))
    {
        if (strlen($new) < 8) {
            echo "<script>Swal.fire({ icon:'error', title:'Too Short', text:'Password must be at least 8 characters.', confirmButtonColor:'#10b981' });</script>";
        } elseif ($new !== $confirm) {
            echo "<script>Swal.fire({ icon:'error', title:'Mismatch', text:'New password and confirm password do not match.', confirmButtonColor:'#10b981' });</script>";
        } else {
            $newHash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "UPDATE users SET password=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "si", $newHash, $user_id);
            mysqli_stmt_execute($stmt);

            echo "<script>
                Swal.fire({ icon:'success', title:'Password Changed!', text:'Your password has been updated successfully.', showConfirmButton:false, timer:1800 })
                .then(() => { window.location='profile.php'; });
            </script>";
        }
    }
    else
    {
        echo "<script>Swal.fire({ icon:'error', title:'Incorrect Password', text:'Your current password is wrong. Please try again.', confirmButtonColor:'#10b981' });</script>";
    }
}
?>

<div class="container py-5 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">

            <div class="card border-0 shadow-2xl rounded-4 overflow-hidden">
                <!-- Header -->
                <div class="card-header border-0 text-white p-4 text-center" style="background: linear-gradient(135deg, #059669 0%, #10b981 60%, #06b6d4 100%);">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle mb-3 shadow" style="width:56px; height:56px; font-size:24px; color:#10b981;">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h4 class="fw-extrabold text-white mb-1">Change Password</h4>
                    <p class="text-white opacity-85 mb-0 fs-7">Keep your account secure with a strong password</p>
                </div>

                <!-- Form Body -->
                <div class="card-body p-4 p-md-5">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-lock-open me-1 text-primary"></i> Current Password</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-lock-open"></i></span>
                                <input type="password" id="oldPass" name="old_password" class="form-control" placeholder="Your current password" required>
                                <button type="button" class="toggle-password-btn" data-target="oldPass"><i class="fa-regular fa-eye"></i></button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-key me-1 text-primary"></i> New Password</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-key"></i></span>
                                <input type="password" id="newPass" name="new_password" class="form-control" placeholder="Min. 8 characters" required>
                                <button type="button" class="toggle-password-btn" data-target="newPass"><i class="fa-regular fa-eye"></i></button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-lock me-1 text-primary"></i> Confirm New Password</label>
                            <div class="custom-input-group">
                                <span class="input-icon-left"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" id="confPass" name="confirm_password" class="form-control" placeholder="Repeat new password" required>
                                <button type="button" class="toggle-password-btn" data-target="confPass"><i class="fa-regular fa-eye"></i></button>
                            </div>
                        </div>

                        <!-- Password Tips -->
                        <div class="alert border-0 rounded-3 mb-4 p-3" style="background:rgba(16,185,129,0.08); border-left:3px solid #10b981 !important;">
                            <p class="mb-1 fs-7 fw-bold text-primary"><i class="fa-solid fa-circle-info me-1"></i> Password Tips</p>
                            <ul class="mb-0 fs-7 text-secondary ps-3">
                                <li>At least 8 characters</li>
                                <li>Mix letters, numbers & symbols</li>
                                <li>Avoid using your name or email</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" name="change" class="btn-emerald-submit flex-fill" style="max-width:260px;">
                                <i class="fa-solid fa-shield-check me-2"></i> Update Password
                            </button>
                            <a href="profile.php" class="btn flex-fill fw-bold rounded-pill py-3 border-2 text-center" style="border-color:#64748b; color:#64748b; text-decoration:none; max-width:160px;">
                                <i class="fa-solid fa-arrow-left me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>