<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location:login.php");
    exit();
}

include "includes/config.php";
include "includes/header.php";

if(!isset($_GET['id']))
{
    header("Location:index.php");
    exit();
}

$vehicle_id = (int)$_GET['id'];

$result = mysqli_query($conn,"SELECT * FROM vehicles WHERE id='$vehicle_id'");

if(mysqli_num_rows($result)==0)
{
    die("Vehicle Not Found");
}

$vehicle = mysqli_fetch_assoc($result);

if(isset($_POST['book_vehicle']))
{
    $user_id = $_SESSION['user_id'];

    $pickup_date = $_POST['pickup_date'];

    $return_date = $_POST['return_date'];

    $estimated_km = (int) $_POST['estimated_km'];

    $price_per_km = $vehicle['price_per_km'];

    $total_amount = $estimated_km * $price_per_km;

    if ($vehicle['status'] !== 'Available') {
        echo "<script>
                alert('This vehicle is currently not available for booking.');
              </script>";
    } elseif (strtotime($return_date) < strtotime($pickup_date)) {
        echo "<script>
                alert('Return date cannot be before the pickup date.');
              </script>";
    } elseif ($estimated_km <= 0) {
        echo "<script>
                alert('Please enter a valid estimated KM.');
              </script>";
    } else {

        // Check if vehicle is already booked on selected dates
        $checkStmt = mysqli_prepare($conn, "
        SELECT id
        FROM bookings
        WHERE vehicle_id = ?
        AND booking_status = 'Approved'
        AND (
            (? BETWEEN pickup_date AND return_date)
            OR (? BETWEEN pickup_date AND return_date)
            OR (pickup_date BETWEEN ? AND ?)
        )
        ");
        mysqli_stmt_bind_param($checkStmt, "sssss", $vehicle_id, $pickup_date, $return_date, $pickup_date, $return_date);
        mysqli_stmt_execute($checkStmt);
        $check = mysqli_stmt_get_result($checkStmt);

        if(mysqli_num_rows($check)>0)
        {
            echo "<script>
                    alert('Vehicle is already booked for the selected dates.');
                  </script>";
        }
        else
        {
            $insertStmt = mysqli_prepare($conn, "
            INSERT INTO bookings
            (
                user_id,
                vehicle_id,
                pickup_date,
                return_date,
                estimated_km,
                total_amount,
                booking_status
            )
            VALUES (?, ?, ?, ?, ?, ?, 'Pending')
            ");
            mysqli_stmt_bind_param(
                $insertStmt,
                "iissid",
                $user_id,
                $vehicle_id,
                $pickup_date,
                $return_date,
                $estimated_km,
                $total_amount
            );

            if(mysqli_stmt_execute($insertStmt))
            {
                echo "<script>

                Swal.fire({
                icon:'success',
                title:'Success',
                text:'Booking Successful!'
                }).then(()=>{
                window.location='index.php';
                });

                </script>";
            }
            else
            {
                echo "<script>
                        alert('Booking Failed');
                      </script>";
            }
        }
    }
}

?>

<section class="register">

    <div class="register-container">

        <h2>Book Vehicle</h2>

        <p>Please fill the booking details.</p>

        <form method="POST">

            <label>Vehicle Name</label>
            <input type="text"
                   value="<?php echo htmlspecialchars($vehicle['vehicle_name']); ?>"
                   readonly>

            <label>Price Per KM</label>
            <input type="text"
                   value="₹<?php echo htmlspecialchars($vehicle['price_per_km']); ?> / KM"
                   readonly>

            <label>Pickup Date</label>
            <input type="date"
                   name="pickup_date"
                   required>

            <label>Return Date</label>
            <input type="date"
                   name="return_date"
                   required>

            <label>Estimated KM</label>
            <input type="number"
                   id="km"
                   name="estimated_km"
                   placeholder="Enter Estimated KM"
                   min="1"
                   required>

            <label>Total Amount</label>
            <input type="text"
                   id="total_amount"
                   readonly
                   placeholder="Total Amount">

            <input type="hidden"
                   id="price"
                   value="<?php echo htmlspecialchars($vehicle['price_per_km']); ?>">

            <button type="submit" name="book_vehicle">
                Confirm Booking
            </button>

        </form>

    </div>

</section>

<script>

document.addEventListener("DOMContentLoaded", function(){

    const km = document.getElementById("km");
    const price = document.getElementById("price");
    const total = document.getElementById("total_amount");

    function calculateTotal(){

        let kmValue = parseFloat(km.value);

        let priceValue = parseFloat(price.value);

        if(isNaN(kmValue) || kmValue <= 0){

            total.value = "";

            return;

        }

        let amount = kmValue * priceValue;

        total.value = "₹ " + amount.toFixed(2);

    }

    km.addEventListener("input", calculateTotal);

});

</script>

<?php include "includes/footer.php"; ?>