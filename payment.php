<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location:login.php");
    exit();
}

include "includes/config.php";
include "includes/header.php";

if (!isset($_GET['booking_id'])) {
    header("Location:booking_history.php");
    exit();
}

$booking_id = (int) $_GET['booking_id'];

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
SELECT
bookings.*,
vehicles.vehicle_name
FROM bookings
INNER JOIN vehicles
ON bookings.vehicle_id=vehicles.id
WHERE bookings.id='$booking_id'
AND bookings.user_id='$user_id'
");

if (mysqli_num_rows($query) == 0) {
    die("Booking Not Found");
}

$row = mysqli_fetch_assoc($query);

if (isset($_POST['pay_now'])) {
    $method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    $insert = mysqli_query($conn, "
    INSERT INTO payments
    (
        booking_id,
        user_id,
        amount,
        payment_method,
        payment_status
    )
    VALUES
    (
        '$booking_id',
        '$user_id',
        '" . $row['total_amount'] . "',
        '$method',
        'Paid'
    )
    ");

    if ($insert) {
        echo "<script>
        alert('Payment Successful');
        window.location='invoice.php?booking_id=" . $booking_id . "';
        </script>";
    } else {
        die(mysqli_error($conn));
    }
}

?>

<section class="register">

    <div class="register-container">

        <h2>Payment</h2>

        <p>Complete your payment</p>

        <form method="POST">

            <label>Vehicle</label>

            <input type="text" value="<?php echo htmlspecialchars($row['vehicle_name']); ?>" readonly>

            <label>Amount</label>

            <input type="text" value="₹<?php echo htmlspecialchars($row['total_amount']); ?>" readonly>

            <label>Payment Method</label>

            <select name="payment_method" required>

                <option value="">Select</option>

                <option>UPI</option>

                <option>Credit Card</option>

                <option>Debit Card</option>

                <option>Net Banking</option>

                <option>Cash</option>

            </select>

            <button type="submit" name="pay_now">

                Pay Now

            </button>

        </form>

    </div>

</section>

<?php include "includes/footer.php"; ?>