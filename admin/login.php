<?php
session_start();
include '../includes/config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        if (password_verify($password, $row['password'])) {
            unset($_SESSION['user_id']);
            unset($_SESSION['fullname']);

            $_SESSION['admin'] = $row['username'];

            header("Location:dashboard.php");
            exit();
        } else {
            echo "<script>alert('Wrong Password');</script>";
        }
    } else {
        echo "<script>alert('Admin Not Found');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Login</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <section class="login">

        <div class="login-container">
            <h2>Admin Login</h2>

            <form method="POST">

                <label>Username</label>

                <input type="text" name="username" required>

                <label>Password</label>

                <input type="password" name="password" required>

                <button type="submit" name="login">Login</button>

            </form>

        </div>

    </section>

</body>

</html>