<?php
include "includes/config.php";
include "includes/header.php";
?>

<div class="container py-5 my-3">
    <div class="row justify-content-center g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-primary text-white p-4 text-center border-0">
                    <div class="brand-icon d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle mb-2" style="width:50px; height:50px; font-size:24px;">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <h3 class="fw-bold mb-1">Get in Touch</h3>
                    <p class="text-white opacity-75 small mb-0">Have questions about rentals? Send us a message below.</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-user me-1 text-primary"></i> Full Name</label>
                                <input type="text" name="fullname" class="form-control form-control-lg fs-6 rounded-3" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-envelope me-1 text-primary"></i> Email Address</label>
                                <input type="email" name="email" class="form-control form-control-lg fs-6 rounded-3" placeholder="john@example.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-heading me-1 text-primary"></i> Subject</label>
                            <input type="text" name="subject" class="form-control form-control-lg fs-6 rounded-3" placeholder="How can we help you?" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary fs-7 mb-1"><i class="fa-solid fa-comment me-1 text-primary"></i> Message</label>
                            <textarea name="message" class="form-control rounded-3 fs-6" rows="5" placeholder="Write your message here..." required></textarea>
                        </div>

                        <button type="submit" name="send" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold py-3 shadow-sm">
                            <i class="fa-solid fa-paper-plane me-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Information Sidebar -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-dark text-white h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge bg-primary rounded-pill px-3 py-1 fw-bold fs-7 mb-3">CUSTOMER CARE</span>
                    <h3 class="fw-bold mb-3">RentX Support Hub</h3>
                    <p class="text-secondary leading-relaxed mb-4">
                        We are here to assist you with vehicle availability, booking status updates, or custom enterprise rentals.
                    </p>

                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3 bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10">
                            <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-white">Central Hub Office</h6>
                                <p class="text-secondary small mb-0">123 Mobility Hub, Tech Street, Metro City</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10">
                            <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-white">Email Us</h6>
                                <p class="text-secondary small mb-0">support@rentx.com</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10">
                            <div class="bg-warning text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-white">Phone Support</h6>
                                <p class="text-secondary small mb-0">+91 9876543210 (24/7 Hotline)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-top border-secondary border-opacity-25 mt-4 text-center text-secondary small">
                    RentX guarantees response within 2 business hours.
                </div>
            </div>
        </div>
    </div>
</div>

<?php
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
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Message Sent!',
                text: 'Thank you for reaching out. We will get back to you shortly.',
                showConfirmButton: true
            });
        </script>";
    }
    else
    {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: 'Something went wrong while sending your message. Please try again.'
            });
        </script>";
    }
}
?>

<?php include "includes/footer.php"; ?>