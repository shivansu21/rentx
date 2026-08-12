<?php

session_start();

include 'includes/config.php';

if (isset($_POST['reset'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $newpassword = $_POST['newpassword'];

    $confirmpassword = $_POST['confirmpassword'];

    if ($newpassword != $confirmpassword) {

        echo "<script>alert('Passwords do not match');</script>";

    } else {

        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

        if (mysqli_num_rows($check) > 0) {

            $password = password_hash($newpassword, PASSWORD_DEFAULT);

            mysqli_query($conn, "UPDATE users SET password='$password' WHERE email='$email'");

            echo "<script>
alert('Password Updated Successfully');
window.location='login.php';
</script>";

        } else {

            echo "<script>alert('Email Not Found');</script>";

        }

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'includes/header.php'; ?>

    <section class="login">

        <div class="login-container">

            <h2>Forgot Password</h2>

            <p>Enter your email and create a new password.</p>

            <form method="POST">

                <label>Email Address</label>

                <input type="email" name="email" required>

                <label>New Password</label>

                <input type="password" name="newpassword" required>

                <label>Confirm Password</label>

                <input type="password" name="confirmpassword" required>

                <button type="submit" name="reset">
                    Update Password
                </button>

            </form>

            <p class="register-link">

                Remember your password?

                <a href="login.php">

                    Login

                </a>

            </p>

        </div>

    </section>

    <?php include 'includes/footer.php'; ?>

</body>

</html>