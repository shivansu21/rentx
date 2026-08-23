<?php include 'includes/config.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="container py-5 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-2xl rounded-4 overflow-hidden register-card">
                <div class="card-header bg-emerald-gradient text-white p-4 p-md-5 text-center border-0 position-relative" style="background: linear-gradient(135deg, #059669 0%, #10b981 50%, #06b6d4 100%);">
                    <div class="brand-icon d-inline-flex align-items-center justify-content-center bg-white text-emerald rounded-circle mb-3 shadow-lg" style="width:58px; height:58px; font-size:26px;">
                        <i class="fa-solid fa-user-plus text-primary"></i>
                    </div>
                    <h2 class="fw-extrabold mb-1 text-white">Create Your Account</h2>
                    <p class="text-white opacity-85 fs-6 mb-0">Join RentX to rent verified cars & bikes instantly</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-user me-1 text-primary"></i> Full Name</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" name="fullname" class="form-control" placeholder="John Doe" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-envelope me-1 text-primary"></i> Email Address</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-phone me-1 text-primary"></i> Mobile Number</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-phone"></i></span>
                                    <input type="text" name="mobile" class="form-control" placeholder="+91 9876543210" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-calendar me-1 text-primary"></i> Date of Birth</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-calendar"></i></span>
                                    <input type="date" name="dob" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-venus-mars me-1 text-primary"></i> Gender</label>
                            <div class="btn-group w-100 gender-toggle-group shadow-sm" role="group">
                                <input type="radio" class="btn-check" name="gender" id="gMale" value="Male" required checked>
                                <label class="btn fw-bold" for="gMale"><i class="fa-solid fa-mars me-1"></i> Male</label>

                                <input type="radio" class="btn-check" name="gender" id="gFemale" value="Female">
                                <label class="btn fw-bold" for="gFemale"><i class="fa-solid fa-venus me-1"></i> Female</label>

                                <input type="radio" class="btn-check" name="gender" id="gOther" value="Other">
                                <label class="btn fw-bold" for="gOther"><i class="fa-solid fa-genderless me-1"></i> Other</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-location-dot me-1 text-primary"></i> Full Address</label>
                            <div class="custom-input-group p-1">
                                <span class="input-icon-left align-self-start pt-2"><i class="fa-solid fa-location-dot"></i></span>
                                <textarea name="address" class="form-control" rows="3" placeholder="Enter your full residential address..." required style="resize:none;"></textarea>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-id-card me-1 text-primary"></i> Driving Licence Number</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-id-card"></i></span>
                                    <input type="text" name="licence_number" class="form-control" placeholder="DL-1420110012345" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-file-arrow-up me-1 text-primary"></i> Upload Driving Licence (Image/PDF)</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-file-arrow-up"></i></span>
                                    <input type="file" name="licence_image" class="form-control" accept="image/*,.pdf" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-key me-1 text-primary"></i> Create Password</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-key"></i></span>
                                    <input type="password" id="regPass" name="password" class="form-control" placeholder="••••••••" required>
                                    <button type="button" class="toggle-password-btn" data-target="regPass" title="Toggle Password Visibility">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-7 text-uppercase tracking-wider opacity-85 mb-1"><i class="fa-solid fa-lock me-1 text-primary"></i> Confirm Password</label>
                                <div class="custom-input-group">
                                    <span class="input-icon-left"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" id="regConfirmPass" name="confirm_password" class="form-control" placeholder="••••••••" required>
                                    <button type="button" class="toggle-password-btn" data-target="regConfirmPass" title="Toggle Password Visibility">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="mb-3 p-3 rounded-3" style="background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.2);">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="termsCheck" required style="accent-color:#10b981; width:16px; height:16px; margin-top:3px;">
                                <label class="form-check-label fs-7 ms-2" for="termsCheck">
                                    I agree to the <a href="#" class="fw-bold text-primary text-decoration-none">Terms of Service</a> and <a href="#" class="fw-bold text-primary text-decoration-none">Privacy Policy</a>. I confirm that my driving licence is valid and accurate.
                                </label>
                            </div>
                        </div>

                        <button type="submit" name="register" class="btn-emerald-submit mb-3">
                            <i class="fa-solid fa-user-check me-2"></i> Register Account
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top">
                        <p class="text-secondary small mb-0">
                            Already have an account? 
                            <a href="login.php" class="fw-bold text-primary text-decoration-none ms-1">Login Here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if(isset($_POST['register']))
{
    $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $mobile = mysqli_real_escape_string($conn,$_POST['mobile']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = mysqli_real_escape_string($conn,$_POST['address']);
    $licence_number = mysqli_real_escape_string($conn,$_POST['licence_number']);

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if($_POST['password'] != $_POST['confirm_password'])
    {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Password and Confirm Password do not match.'
            });
        </script>";
    }
    else
    {
        $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($check) > 0)
        {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Email Exists',
                    text: 'An account with this email address already exists.'
                });
            </script>";
        }
        else
        {
            $uploadDir = __DIR__ . "/uploads/";
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
            $ext = strtolower(pathinfo($_FILES['licence_image']['name'], PATHINFO_EXTENSION));

            if ($_FILES['licence_image']['error'] !== UPLOAD_ERR_OK) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: 'Licence upload failed. Please try again.'
                    });
                </script>";
            } elseif (!in_array($ext, $allowedExt)) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Licence must be a JPG, PNG, WEBP or PDF file.'
                    });
                </script>";
            } elseif ($_FILES['licence_image']['size'] > 5 * 1024 * 1024) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Licence file must be smaller than 5MB.'
                    });
                </script>";
            } else {
                $image = uniqid('licence_', true) . '.' . $ext;
                $tmp = $_FILES['licence_image']['tmp_name'];

                if (!move_uploaded_file($tmp, $uploadDir . $image)) {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Could not save the licence file on server.'
                        });
                    </script>";
                } else {
                    $sql = "INSERT INTO users
                    (fullname,email,mobile,dob,gender,address,licence_number,licence_image,password)
                    VALUES
                    ('$fullname','$email','$mobile','$dob','$gender','$address','$licence_number','$image','$password')";

                    if(mysqli_query($conn,$sql))
                    {
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Registration Successful!',
                                text: 'Your account has been created. Please log in.',
                                showConfirmButton: true
                            }).then(() => {
                                window.location = 'login.php';
                            });
                        </script>";
                    }
                    else
                    {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Database Error',
                                text: 'Registration failed. Please try again.'
                            });
                        </script>";
                    }
                }
            }
        }
    }
}
?>

<?php include 'includes/footer.php'; ?>