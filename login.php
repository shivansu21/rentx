<?php
session_start();
include 'includes/config.php';
?>
<?php include 'includes/header.php'; ?>

<style>
    .login-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
    }

    .login-container-card {
        width: 100%;
        max-width: 960px;
        background: var(--rentx-card-bg, #ffffff);
        border-radius: 28px;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(226, 232, 240, 0.8);
        overflow: hidden;
        display: flex;
        flex-direction: row;
        transition: background 0.3s ease;
    }

    body.dark-theme .login-container-card {
        background: #121212 !important;
        border-color: #27272a !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
    }

    .login-left-banner {
        width: 45%;
        background: linear-gradient(135deg, #041410 0%, #062e26 50%, #0f172a 100%);
        color: #ffffff;
        padding: 48px 40px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        border-right: 1px solid rgba(16, 185, 129, 0.2);
    }

    .login-left-banner::before {
        content: '';
        position: absolute;
        top: -20%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .banner-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .banner-brand-icon {
        width: 44px;
        height: 44px;
        background: #ffffff;
        color: #10b981;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 800;
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
    }

    .banner-brand-text {
        font-family: 'Outfit', sans-serif;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .banner-features {
        margin: 40px 0;
    }

    .login-left-banner,
    .login-left-banner h6,
    .login-left-banner p,
    .login-left-banner span,
    body.light-theme .login-left-banner,
    body.light-theme .login-left-banner h6,
    body.light-theme .login-left-banner p,
    body.light-theme .login-left-banner span {
        color: #ffffff !important;
    }

    .banner-feature-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 24px;
    }

    .banner-feature-item i {
        font-size: 20px;
        color: #34d399 !important;
        margin-top: 2px;
    }

    .banner-feature-item h6,
    body.light-theme .banner-feature-item h6 {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 2px;
        color: #ffffff !important;
    }

    .banner-feature-item p,
    body.light-theme .banner-feature-item p,
    .login-left-banner p,
    body.light-theme .login-left-banner p {
        font-size: 13.5px;
        line-height: 1.4;
        margin: 0;
        color: #ffffff !important;
        opacity: 0.95 !important;
    }

    .banner-footer-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }

    .login-right-form {
        width: 55%;
        padding: 48px 44px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .form-header-title {
        font-family: 'Outfit', sans-serif;
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .form-header-sub {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 32px;
    }

    body.dark-theme .form-header-sub {
        color: #a1a1aa !important;
    }

    .form-group-field {
        margin-bottom: 22px;
    }

    .field-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    body.dark-theme .field-label {
        color: #e2e8f0 !important;
    }

    .custom-input-box {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.25s ease;
    }

    body.dark-theme .custom-input-box {
        background: #0d1527 !important;
        border-color: rgba(16, 185, 129, 0.25) !important;
    }

    .custom-input-box:focus-within {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.18);
    }

    .input-box-icon {
        padding: 14px 16px;
        color: #10b981;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    body.dark-theme .input-box-icon {
        color: #10b981;
    }

    .input-box-element {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        padding: 14px 16px 14px 0;
        font-size: 15px;
        color: #0f172a;
        width: 100%;
    }

    body.dark-theme .input-box-element {
        color: #ffffff !important;
    }

    .input-box-element::placeholder {
        color: #94a3b8;
    }

    .eye-toggle-btn {
        background: transparent;
        border: none;
        color: #64748b;
        padding: 14px 16px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }

    .eye-toggle-btn:hover {
        color: #10b981;
    }

    .form-options-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        font-size: 13.5px;
    }

    .forgot-link {
        color: #10b981;
        text-decoration: none;
        font-weight: 700;
        transition: color 0.2s;
    }

    .forgot-link:hover {
        color: #059669;
        text-decoration: underline;
    }

    .btn-login-submit {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-login-submit:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 15px 25px -5px rgba(16, 185, 129, 0.5);
    }

    .register-redirect {
        text-align: center;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        font-size: 14px;
        color: #64748b;
    }

    body.dark-theme .register-redirect {
        border-top-color: #27272a !important;
        color: #a1a1aa !important;
    }

    .register-redirect a {
        color: #10b981;
        font-weight: 700;
        text-decoration: none;
    }

    .register-redirect a:hover {
        text-decoration: underline;
    }

    .portal-pill-btn {
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 700;
        border-radius: 30px;
        color: #64748b !important;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        text-decoration: none !important;
    }

    .portal-pill-btn:hover {
        color: #10b981 !important;
    }

    .portal-pill-btn.active {
        background: #10b981 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    @media (max-width: 850px) {
        .login-container-card {
            flex-direction: column;
        }
        .login-left-banner, .login-right-form {
            width: 100%;
        }
        .login-left-banner {
            padding: 36px 30px;
        }
        .login-right-form {
            padding: 36px 28px;
        }
    }
</style>

<div class="login-wrapper">
    <div class="login-container-card">
        
        <!-- Left Visual Banner -->
        <div class="login-left-banner">
            <div>
                <div class="banner-brand">
                    <div class="banner-brand-icon"><i class="fa-solid fa-car"></i></div>
                    <span class="banner-brand-text">RentX</span>
                </div>

                <div class="banner-features">
                    <div class="banner-feature-item">
                        <i class="fa-solid fa-bolt"></i>
                        <div>
                            <h6 class="text-white fw-bold mb-1">Instant Reservations</h6>
                            <p class="text-white opacity-100 mb-0">Book verified cars & bikes in under 60 seconds.</p>
                        </div>
                    </div>

                    <div class="banner-feature-item">
                        <i class="fa-solid fa-shield-halved"></i>
                        <div>
                            <h6 class="text-white fw-bold mb-1">100% Insured Fleet</h6>
                            <p class="text-white opacity-100 mb-0">Drive with peace of mind everywhere you go.</p>
                        </div>
                    </div>

                    <div class="banner-feature-item">
                        <i class="fa-solid fa-tag"></i>
                        <div>
                            <h6 class="text-white fw-bold mb-1">Best Rate Per KM</h6>
                            <p class="text-white opacity-100 mb-0">Transparent pricing with zero hidden charges.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <span class="banner-footer-pill">
                    <i class="fa-solid fa-headset"></i> 24/7 Customer Support Active
                </span>
            </div>
        </div>

        <!-- Right Form Section -->
        <div class="login-right-form">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <span class="fw-bold fs-7 text-uppercase tracking-wider text-secondary">Select Portal</span>
                <div class="portal-nav-pills d-inline-flex bg-light p-1 rounded-pill border shadow-sm">
                    <a href="login.php" class="portal-pill-btn active">
                        <i class="fa-solid fa-user me-1"></i> Customer
                    </a>
                    <a href="admin/login.php" class="portal-pill-btn">
                        <i class="fa-solid fa-user-shield me-1"></i> Admin Portal
                    </a>
                </div>
            </div>

            <h2 class="form-header-title">Welcome Back 👋</h2>
            <p class="form-header-sub">Enter your email and password to log in to your account.</p>

            <form method="POST">
                <!-- Email Address -->
                <div class="form-group-field">
                    <label class="field-label">Email Address</label>
                    <div class="custom-input-box">
                        <div class="input-box-icon"><i class="fa-solid fa-envelope"></i></div>
                        <input type="email" name="email" class="input-box-element" placeholder="name@example.com" required autocomplete="email">
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group-field">
                    <label class="field-label">Password</label>
                    <div class="custom-input-box">
                        <div class="input-box-icon"><i class="fa-solid fa-lock"></i></div>
                        <input type="password" id="userLoginPass" name="password" class="input-box-element" placeholder="Enter password" required autocomplete="current-password">
                        <button type="button" class="eye-toggle-btn" onclick="toggleUserPass()" title="Toggle Password">
                            <i class="fa-regular fa-eye" id="userEyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options-row">
                    <label class="d-flex align-items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="form-check-input mt-0">
                        <span>Remember Me</span>
                    </label>
                    <a href="contact.php" class="forgot-link">Need Help?</a>
                </div>

                <button type="submit" name="login" class="btn-login-submit">
                    Sign In to Account <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div class="register-redirect d-flex flex-column align-items-center gap-2">
                <div>
                    Don't have a RentX account yet? 
                    <a href="register.php">Create Account</a>
                </div>
                <div class="mt-3 pt-3 border-top w-100 text-center">
                    <a href="admin/login.php" class="btn btn-outline-secondary btn-sm rounded-pill px-4 fw-bold shadow-sm border d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-user-shield text-warning fs-6"></i> Administrator Access Portal <i class="fa-solid fa-arrow-right fs-7 ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function toggleUserPass() {
    const input = document.getElementById('userLoginPass');
    const icon = document.getElementById('userEyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        if (password_verify($password, $row['password'])) {
            unset($_SESSION['admin']);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Welcome Back!',
                    text: 'Logged in successfully.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location = 'user/dashboard.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Authentication Failed',
                    text: 'Incorrect password entered.'
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Account Not Found',
                text: 'No account registered with this email address.'
            });
        </script>";
    }
}
?>

<?php include 'includes/footer.php'; ?>