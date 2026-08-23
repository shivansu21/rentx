<?php
session_start();
include '../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - RentX</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #080c14;
            background-image: 
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.18) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(6, 182, 212, 0.15) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #f8fafc;
        }

        .portal-pill-btn {
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 30px;
            color: #94a3b8 !important;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            text-decoration: none !important;
        }

        .portal-pill-btn:hover {
            color: #ffffff !important;
        }

        .portal-pill-btn.active {
            background: #10b981 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(16, 185, 129, 0.25);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), 0 0 30px rgba(16, 185, 129, 0.15);
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header-banner {
            background: linear-gradient(135deg, #059669 0%, #10b981 50%, #06b6d4 100%);
            padding: 36px 30px 30px;
            text-align: center;
            position: relative;
        }

        .brand-avatar {
            width: 64px;
            height: 64px;
            background: #ffffff;
            color: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 16px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
        }

        .card-header-banner h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .card-header-banner p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .card-body-form {
            padding: 32px 30px 36px;
        }

        .input-group-custom {
            margin-bottom: 22px;
        }

        .input-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #a1a1aa;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .field-box {
            display: flex;
            align-items: center;
            background: #0d1527;
            border: 1.5px solid rgba(16, 185, 129, 0.25);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.25s ease;
        }

        .field-box:focus-within {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
        }

        .field-icon {
            padding: 14px 16px;
            color: #10b981;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .field-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            padding: 14px 16px 14px 0;
            color: #ffffff;
            font-size: 15px;
            width: 100%;
        }

        .field-input::placeholder {
            color: #475569;
        }

        .password-toggle-btn {
            background: transparent;
            border: none;
            color: #64748b;
            padding: 14px 16px;
            cursor: pointer;
            font-size: 16px;
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle-btn:hover {
            color: #10b981;
        }

        .submit-btn {
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
            margin-top: 28px;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(16, 185, 129, 0.5);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .back-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .back-link a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-link a:hover {
            color: #10b981;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="card-header-banner">
        <div class="brand-avatar">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h2>RentX Control Center</h2>
        <p>Administrator Authentication Required</p>
    </div>

    <div class="card-body-form">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-25">
            <span class="fw-bold fs-7 text-uppercase tracking-wider text-secondary">Select Portal</span>
            <div class="portal-nav-pills d-inline-flex bg-dark p-1 rounded-pill border border-secondary border-opacity-25 shadow-sm">
                <a href="../login.php" class="portal-pill-btn">
                    <i class="fa-solid fa-user me-1"></i> Customer
                </a>
                <a href="login.php" class="portal-pill-btn active">
                    <i class="fa-solid fa-user-shield me-1"></i> Admin Portal
                </a>
            </div>
        </div>

        <form method="POST">
            <!-- Username Input -->
            <div class="input-group-custom">
                <label class="input-label"><i class="fa-solid fa-user me-1"></i> Admin Username</label>
                <div class="field-box">
                    <div class="field-icon"><i class="fa-solid fa-user"></i></div>
                    <input type="text" name="username" class="field-input" placeholder="Enter username" required autocomplete="username">
                </div>
            </div>

            <!-- Password Input -->
            <div class="input-group-custom">
                <label class="input-label"><i class="fa-solid fa-lock me-1"></i> Password</label>
                <div class="field-box">
                    <div class="field-icon"><i class="fa-solid fa-key"></i></div>
                    <input type="password" id="adminPasswordInput" name="password" class="field-input" placeholder="Enter password" required autocomplete="current-password">
                    <button type="button" class="password-toggle-btn" onclick="togglePassVisibility()" title="Toggle Password Visibility">
                        <i class="fa-regular fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="login" class="submit-btn">
                Sign In to Console <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="back-link">
            <a href="../index.php"><i class="fa-solid fa-arrow-left"></i> Return to Main Website</a>
        </div>
    </div>
</div>

<script>
function togglePassVisibility() {
    const input = document.getElementById('adminPasswordInput');
    const icon = document.getElementById('eyeIcon');
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
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        if (password_verify($password, $row['password'])) {
            unset($_SESSION['user_id']);
            unset($_SESSION['fullname']);

            $_SESSION['admin'] = $row['username'];

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Access Granted',
                    text: 'Welcome to RentX Admin Control Center',
                    showConfirmButton: false,
                    timer: 1200
                }).then(() => {
                    window.location = 'dashboard.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Authentication Failed',
                    text: 'Invalid administrator password.'
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Account Not Found',
                text: 'No administrator account found with this username.'
            });
        </script>";
    }
}
?>
</body>
</html>