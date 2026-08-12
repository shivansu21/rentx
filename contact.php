<?php
include "includes/config.php";
include "includes/header.php";

if(isset($_POST['send']))
{
    $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $subject = mysqli_real_escape_string($conn,$_POST['subject']);
    $message = mysqli_real_escape_string($conn,$_POST['message']);

    $insert = mysqli_query($conn,"
    INSERT INTO contact_messages(fullname,email,subject,message)
    VALUES('$fullname','$email','$subject','$message')
    ");

    if($insert)
    {
        echo "<script>alert('Message Sent Successfully');</script>";
    }
    else
    {
        echo "<script>alert('Something went wrong');</script>";
    }
}
?>

<section class="register">

<div class="register-container">

<h2>Contact Us</h2>

<p>Feel free to contact us anytime.</p>

<form method="POST">

<label>Full Name</label>
<input type="text" name="fullname" required>

<label>Email</label>
<input type="email" name="email" required>

<label>Subject</label>
<input type="text" name="subject" required>

<label>Message</label>
<textarea name="message" rows="5" required></textarea>

<button type="submit" name="send">
Send Message
</button>

</form>

</div>

</section>

<?php include "includes/footer.php"; ?>