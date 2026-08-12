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
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $check = mysqli_query($conn,"
    SELECT * FROM users
    WHERE id='$user_id'
    ");

    $user = mysqli_fetch_assoc($check);

    if($user && password_verify($old, $user['password']))
    {
        if($new==$confirm)
        {
            $newHash = password_hash($new, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($conn, "UPDATE users SET password=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "si", $newHash, $user_id);
            mysqli_stmt_execute($stmt);

            echo "<script>
            alert('Password Changed Successfully');
            window.location='profile.php';
            </script>";
        }
        else
        {
            echo "<script>alert('Passwords do not match');</script>";
        }
    }
    else
    {
        echo "<script>alert('Old Password Incorrect');</script>";
    }
}
?>

<section class="register">

<div class="register-container">

<h2>Change Password</h2>

<form method="POST">

<label>Old Password</label>
<input type="password" name="old_password" required>

<label>New Password</label>
<input type="password" name="new_password" required>

<label>Confirm Password</label>
<input type="password" name="confirm_password" required>

<button type="submit" name="change">
Change Password
</button>

</form>

</div>

</section>

<?php include "includes/footer.php"; ?>