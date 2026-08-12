<?php

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location:login.php");
    exit();
}

include "includes/config.php";
include "includes/header.php";

$user_id=$_SESSION['user_id'];

$result=mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'");

$user=mysqli_fetch_assoc($result);

?>

<section class="register">

<div class="register-container">

<h2>My Profile</h2>

<p>Your Account Information</p>

<label>Full Name</label>
<input type="text" value="<?php echo htmlspecialchars($user['fullname']); ?>" readonly>

<label>Email</label>
<input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

<label>Mobile</label>
<input type="text" value="<?php echo htmlspecialchars($user['mobile']); ?>" readonly>

<label>Gender</label>
<input type="text" value="<?php echo htmlspecialchars($user['gender']); ?>" readonly>

<br><br>

<a href="edit_profile.php" class="book-btn">
Edit Profile
</a>

<a href="change_password.php" class="book-btn">
Change Password
</a>

</div>

</section>

<?php include "includes/footer.php"; ?>