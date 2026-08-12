<?php include 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - RentX</title>

<link rel="stylesheet" href="css/style.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<?php include 'includes/header.php'; ?>

<section class="register">

<div class="register-container">

<h2>Create Your Account</h2>

<p>Register to rent your favorite car or bike.</p>

<form action="" method="POST" enctype="multipart/form-data">

<label>Full Name</label>
<input type="text" name="fullname" placeholder="Enter your full name" required>

<label>Email Address</label>
<input type="email" name="email" placeholder="Enter your email" required>

<label>Mobile Number</label>
<input type="text" name="mobile" placeholder="Enter mobile number" required>

<label>Date of Birth</label>
<input type="date" name="dob" required>

<label>Gender</label>

<div class="gender">

    <label class="gender-option">
        <input type="radio" name="gender" value="Male" required>
        <span>Male</span>
    </label>

    <label class="gender-option">
        <input type="radio" name="gender" value="Female">
        <span>Female</span>
    </label>

    <label class="gender-option">
        <input type="radio" name="gender" value="Other">
        <span>Other</span>
    </label>

</div>

<label>Address</label>
<textarea name="address" rows="4" placeholder="Enter your address" required></textarea>

<label>Driving Licence Number</label>
<input type="text" name="licence_number" placeholder="Enter licence number" required>

<label>Upload Driving Licence</label>
<input type="file" name="licence_image" required>

<label>Password</label>
<input type="password" name="password" placeholder="Create password" required>

<label>Confirm Password</label>
<input type="password" name="confirm_password" placeholder="Confirm password" required>

<button type="submit" name="register">Register</button>

</form>

<p class="login-link">

Already have an account?

<a href="login.php">Login</a>

</p>

</div>

</section>
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

    // Password Hash
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check Confirm Password
    if($_POST['password'] != $_POST['confirm_password'])
    {
        echo "<script>alert('Password and Confirm Password do not match');</script>";
    }
    else
    {
        // Check Duplicate Email
        $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($check) > 0)
        {
            echo "<script>alert('Email already exists');</script>";
        }
        else
        {
            // Upload Licence Image
            $uploadDir = __DIR__ . "/uploads/";
            $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
            $ext = strtolower(pathinfo($_FILES['licence_image']['name'], PATHINFO_EXTENSION));

            if ($_FILES['licence_image']['error'] !== UPLOAD_ERR_OK) {
                echo "<script>alert('Licence upload failed. Please try again.');</script>";
            } elseif (!in_array($ext, $allowedExt)) {
                echo "<script>alert('Licence must be a JPG, PNG, WEBP or PDF file.');</script>";
            } elseif ($_FILES['licence_image']['size'] > 5 * 1024 * 1024) {
                echo "<script>alert('Licence file must be smaller than 5MB.');</script>";
            } else {
                $image = uniqid('licence_', true) . '.' . $ext;
                $tmp = $_FILES['licence_image']['tmp_name'];

                if (!move_uploaded_file($tmp, $uploadDir . $image)) {
                    echo "<script>alert('Could not save the licence file on the server.');</script>";
                } else {
                    // Insert Data
                    $sql = "INSERT INTO users
                    (fullname,email,mobile,dob,gender,address,licence_number,licence_image,password)
                    VALUES
                    ('$fullname','$email','$mobile','$dob','$gender','$address','$licence_number','$image','$password')";

                    if(mysqli_query($conn,$sql))
                    {
                        echo "<script>alert('Registration Successful');</script>";
                        echo "<script>window.location='login.php';</script>";
                    }
                    else
                    {
                        echo "<script>alert('Registration Failed');</script>";
                    }
                }
            }
        }
    }
}
?>

<?php include 'includes/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>