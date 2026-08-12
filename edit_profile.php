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

if(isset($_POST['update']))
{
    $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
    $mobile = mysqli_real_escape_string($conn,$_POST['mobile']);

    mysqli_query($conn,"
    UPDATE users
    SET fullname='$fullname',
        mobile='$mobile'
    WHERE id='$user_id'
    ");

    echo "<script>
    alert('Profile Updated Successfully');
    window.location='profile.php';
    </script>";
}

$user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'"));
?>

<section class="register">

<div class="register-container">

<h2>Edit Profile</h2>

<form method="POST">

<label>Full Name</label>
<input type="text" name="fullname"
value="<?php echo htmlspecialchars($user['fullname']); ?>" required>

<label>Email</label>
<input type="email"
value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

<label>Mobile</label>
<input type="text" name="mobile"
value="<?php echo htmlspecialchars($user['mobile']); ?>" required>

<button type="submit" name="update">
Update Profile
</button>

</form>

</div>

</section>

<?php include "includes/footer.php"; ?>