<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location:login.php");
    exit();
}

include "includes/config.php";

if(!isset($_GET['booking_id']))
{
    header("Location:booking_history.php");
    exit();
}

$booking_id = (int)$_GET['booking_id'];

$query = mysqli_query($conn,"
SELECT
    bookings.*,
    vehicles.vehicle_name,
    users.fullname,
    payments.payment_method,
    payments.payment_status,
    payments.payment_date
FROM bookings
LEFT JOIN vehicles
ON bookings.vehicle_id = vehicles.id
LEFT JOIN users
ON bookings.user_id = users.id
LEFT JOIN payments
ON bookings.id = payments.booking_id
WHERE bookings.id='$booking_id'
LIMIT 1
");

if(mysqli_num_rows($query)==0)
{
    die("Invoice Not Found");
}

$row = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>RentX Invoice</title>

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#f4f6fb;
padding:40px;
}

.invoice-box{

max-width:900px;

margin:auto;

background:#fff;

padding:40px;

border-radius:15px;

box-shadow:0 10px 30px rgba(0,0,0,.15);

}

.invoice-header{

display:flex;

justify-content:space-between;

align-items:center;

border-bottom:3px solid #2d63e2;

padding-bottom:20px;

margin-bottom:30px;

}

.company h1{

font-size:38px;

color:#2d63e2;

}

.company p{

color:#666;

margin-top:5px;

}

.invoice-info{

text-align:right;

}

.invoice-info h2{

color:#2d63e2;

margin-bottom:10px;

}

.invoice-info p{

margin:5px 0;

font-size:15px;

}

table{

width:100%;

border-collapse:collapse;

margin-top:20px;

}

table th{

width:35%;

background:#2d63e2;

color:#fff;

padding:15px;

text-align:left;

border:1px solid #ddd;

}

table td{

padding:15px;

border:1px solid #ddd;

background:#fafafa;

}

.status{

display:inline-block;

padding:6px 15px;

background:#28a745;

color:#fff;

border-radius:20px;

font-size:14px;

font-weight:600;

}

.print-btn{

margin-top:30px;

padding:14px 35px;

background:#2d63e2;

color:#fff;

border:none;

border-radius:8px;

cursor:pointer;

font-size:16px;

font-weight:600;

}

.print-btn:hover{

background:#1749c6;

}

.footer{

margin-top:40px;

text-align:center;

color:#777;

font-size:14px;

}

@media print{

.print-btn{

display:none;

}

body{

background:#fff;

padding:0;

}

.invoice-box{

box-shadow:none;

border:none;

max-width:100%;

padding:0;

}

}

</style>

</head>

<body>

<div class="invoice-box">

<div class="invoice-header">

<div class="company">

<h1>RentX</h1>

<p>Car & Bike Rental System</p>

</div>

<div class="invoice-info">

<h2>INVOICE</h2>

<p><b>Invoice No:</b> INV-<?php echo htmlspecialchars($row['id']); ?></p>

<p><b>Date:</b> <?php echo date("d-m-Y"); ?></p>

</div>

</div>

<table>

<tr>

<th>Customer Name</th>

<td><?php echo htmlspecialchars($row['fullname']); ?></td>

</tr>

<tr>

<th>Vehicle</th>

<td><?php echo htmlspecialchars($row['vehicle_name']); ?></td>

</tr>

<tr>

<th>Pickup Date</th>

<td><?php echo htmlspecialchars($row['pickup_date']); ?></td>

</tr>

<tr>

<th>Return Date</th>

<td><?php echo htmlspecialchars($row['return_date']); ?></td>

</tr>

<tr>

<th>Estimated KM</th>

<td><?php echo htmlspecialchars($row['estimated_km']); ?> KM</td>

</tr>

<tr>

<th>Total Amount</th>

<td>₹<?php echo number_format($row['total_amount'],2); ?></td>

</tr>

<tr>

<th>Payment Method</th>

<td><?php echo htmlspecialchars($row['payment_method']); ?></td>

</tr>

<tr>

<th>Payment Status</th>

<td><span class="status"><?php echo htmlspecialchars($row['payment_status']); ?></span></td>

</tr>

<tr>

<th>Payment Date</th>

<td><?php echo htmlspecialchars($row['payment_date']); ?></td>

</tr>

</table>

<button class="print-btn" onclick="window.print()">

🖨 Print Invoice

</button>

<div class="footer">

<p>Thank you for choosing <b>RentX</b>.</p>

<p>This is a computer generated invoice.</p>

</div>

</div>

</body>

</html>