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

$query = mysqli_query($conn,"
SELECT
    bookings.*,
    vehicles.vehicle_name
FROM bookings
INNER JOIN vehicles
ON bookings.vehicle_id = vehicles.id
WHERE bookings.user_id = '$user_id'
ORDER BY bookings.id DESC
");

$payments = [];

$p = mysqli_query($conn,"SELECT booking_id FROM payments");

while($pay = mysqli_fetch_assoc($p))
{
    $payments[$pay['booking_id']] = true;
}

?>

<section class="booking-history">

    <div class="history-container">

        <h2>My Booking History</h2>

        <table>

            <thead>

                <tr>

                    <th>Booking ID</th>

                    <th>Vehicle</th>

                    <th>Pickup Date</th>

                    <th>Return Date</th>

                    <th>Estimated KM</th>

                    <th>Total Amount</th>

                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

            <?php

            if(mysqli_num_rows($query)>0)
            {

                while($row=mysqli_fetch_assoc($query))
                {

            ?>

                <tr>

                    <td><?php echo htmlspecialchars($row['id']); ?></td>

                    <td><?php echo htmlspecialchars($row['vehicle_name']); ?></td>

                    <td><?php echo htmlspecialchars($row['pickup_date']); ?></td>

                    <td><?php echo htmlspecialchars($row['return_date']); ?></td>

                    <td><?php echo htmlspecialchars($row['estimated_km']); ?> KM</td>

                    <td>₹<?php echo number_format($row['total_amount'],2); ?></td>

                    <td><?php echo htmlspecialchars($row['booking_status']); ?></td>

                    <td>

                    <?php echo htmlspecialchars($row['booking_status']); ?>

                    <?php

if($row['booking_status']=="Approved")
{

    if(isset($payments[$row['id']]))
    {

?>

<br><br>

<a href="invoice.php?booking_id=<?php echo htmlspecialchars($row['id']); ?>" class="book-btn">
View Invoice
</a>

<?php

    }
    else
    {

?>

<br><br>

<a href="payment.php?booking_id=<?php echo htmlspecialchars($row['id']); ?>" class="book-btn">
Pay Now
</a>

<?php

    }

}

?>
                    </td>

                </tr>

            <?php

                }

            }
            else
            {

            ?>

                <tr>

                    <td colspan="7">No Bookings Found</td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>

    </div>

</section>

<?php include "includes/footer.php"; ?>