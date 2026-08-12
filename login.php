<?php

session_start();
include 'includes/config.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - RentX</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

</head>

<body>

    <?php include 'includes/header.php'; ?>

    <section class="login">

        <div class="login-container">

            <h2>Welcome Back</h2>

            <p>Login to continue your journey with RentX.</p>

            <form action="" method="POST">

                <label>Email Address</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <button type="submit" name="login">Login</button>

            </form>

            <p style="text-align:right;margin-top:10px;">
                <a href="forgot_password.php" style="text-decoration:none;color:#2563EB;font-weight:600;">
                    Forgot Password?
                </a>
            </p>

            <p class="register-link">
                Don't have an account?
                <a href="register.php">Register</a>
            </p>

        </div>

    </section>

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

    
            header("Location:user/dashboard.php");

    
            exit();


            } else {

                echo "<script>alert('Incorrect Password');</script>";

            }

        } else {

            echo "<script>alert('Email Not Found');</script>";

        }

    }

    ?>

    <?php include 'includes/footer.php'; ?>

</body>

</html>