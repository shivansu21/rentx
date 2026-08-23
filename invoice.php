<?php
session_start();

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "includes/config.php";

if (!isset($_GET['booking_id'])) {
    header("Location: booking_history.php");
    exit();
}

$booking_id = (int)$_GET['booking_id'];

$query = mysqli_query($conn, "
SELECT
    bookings.*,
    vehicles.vehicle_name,
    vehicles.vehicle_type,
    vehicles.price_per_km,
    users.fullname,
    users.email,
    users.mobile,
    payments.payment_method,
    payments.payment_status,
    payments.payment_date
FROM bookings
LEFT JOIN vehicles ON bookings.vehicle_id = vehicles.id
LEFT JOIN users ON bookings.user_id = users.id
LEFT JOIN payments ON bookings.id = payments.booking_id
WHERE bookings.id = '$booking_id'
LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
    die("Invoice Not Found");
}

$row = mysqli_fetch_assoc($query);
$baseFare = ($row['estimated_km'] * $row['price_per_km']);
$extraFare = $row['extra_amount'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RentX Official Invoice #INV-<?php echo htmlspecialchars($row['id']); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

body {
    background-color: #f1f5f9;
    padding: 40px 20px;
    color: #1e293b;
}

.invoice-box {
    max-width: 850px;
    margin: auto;
    background: #ffffff;
    padding: 48px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
}

.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 24px;
    margin-bottom: 32px;
}

.brand-logo {
    font-size: 32px;
    font-weight: 800;
    color: #059669;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

.brand-logo i {
    font-size: 28px;
}

.company-sub {
    color: #64748b;
    font-size: 13px;
    margin-top: 4px;
}

.invoice-meta {
    text-align: right;
}

.invoice-meta h2 {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.5px;
}

.invoice-badge {
    display: inline-block;
    padding: 4px 12px;
    background: #dcfce7;
    color: #166534;
    font-size: 12px;
    font-weight: 700;
    border-radius: 20px;
    margin-top: 6px;
}

.billing-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
}

.info-card {
    background: #f8fafc;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.info-card h6 {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 8px;
}

.info-card p {
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.5;
}

.table-custom {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 32px;
}

.table-custom th {
    background: #0f172a;
    color: #ffffff;
    padding: 14px 16px;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
}

.table-custom th:last-child,
.table-custom td:last-child {
    text-align: right;
}

.table-custom td {
    padding: 16px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
    color: #334155;
}

.total-row {
    background: linear-gradient(90deg, #f0fdf4, #dcfce7);
    font-weight: 800;
    font-size: 16px;
    color: #059669;
}

.invoice-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 2px dashed #e2e8f0;
    padding-top: 24px;
    margin-top: 32px;
}

.btn-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    justify-content: center;
}

.btn-print {
    padding: 12px 28px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
    border: none;
    border-radius: 30px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
    text-decoration: none;
}

.btn-print:hover {
    background: linear-gradient(135deg, #059669, #047857);
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.5);
}

.btn-back {
    padding: 12px 28px;
    background: #ffffff;
    color: #475569;
    border: 1px solid #cbd5e1;
    border-radius: 30px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

@media print {
    body { background: #ffffff; padding: 0; }
    .invoice-box { box-shadow: none; padding: 0; }
    .btn-actions { display: none; }
}
</style>
</head>
<body>

<div class="invoice-box">
    <div class="invoice-header">
        <div>
            <div class="brand-logo">
                <i class="fa-solid fa-car-side"></i> RentX
            </div>
            <div class="company-sub">Premium Vehicle Rentals & Mobility Services</div>
        </div>
        <div class="invoice-meta">
            <h2>OFFICIAL INVOICE</h2>
            <div class="invoice-badge">#INV-<?php echo str_pad($row['id'], 6, '0', STR_PAD_LEFT); ?></div>
            <div style="font-size:12px; color:#64748b; margin-top:6px;">Date: <?php echo date('M d, Y'); ?></div>
        </div>
    </div>

    <div class="billing-grid">
        <div class="info-card">
            <h6>Billed To</h6>
            <p><?php echo htmlspecialchars($row['fullname']); ?></p>
            <p style="font-weight:400; color:#64748b; font-size:13px;"><?php echo htmlspecialchars($row['email']); ?></p>
            <p style="font-weight:400; color:#64748b; font-size:13px;"><?php echo htmlspecialchars($row['mobile']); ?></p>
        </div>
        <div class="info-card">
            <h6>Rental Period & Vehicle</h6>
            <p><?php echo htmlspecialchars($row['vehicle_name']); ?> (<?php echo htmlspecialchars($row['vehicle_type']); ?>)</p>
            <p style="font-weight:400; color:#64748b; font-size:13px;">Pickup: <?php echo htmlspecialchars($row['pickup_date']); ?></p>
            <p style="font-weight:400; color:#64748b; font-size:13px;">Return: <?php echo htmlspecialchars($row['return_date']); ?></p>
        </div>
    </div>

    <table class="table-custom">
        <thead>
            <tr>
                <th>Description</th>
                <th>Rate / Detail</th>
                <th>Qty / Days</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Distance Fare Base</strong> (<?php echo htmlspecialchars($row['vehicle_name']); ?>)</td>
                <td>₹<?php echo htmlspecialchars($row['price_per_km']); ?> / KM</td>
                <td><?php echo htmlspecialchars($row['estimated_km']); ?> KM</td>
                <td>₹<?php echo number_format($baseFare, 2); ?></td>
            </tr>
            <tr>
                <td><strong>Protection Plan</strong></td>
                <td><?php echo htmlspecialchars($row['insurance_plan'] ?? 'Basic'); ?> Coverage</td>
                <td>1 Plan</td>
                <td>₹<?php echo number_format($extraFare, 2); ?></td>
            </tr>
            <?php if (!empty($row['add_ons']) && $row['add_ons'] !== 'None'): ?>
            <tr>
                <td><strong>Add-on Extras</strong></td>
                <td colspan="2"><?php echo htmlspecialchars($row['add_ons']); ?></td>
                <td>Included</td>
            </tr>
            <?php endif; ?>
            <tr class="total-row">
                <td colspan="3">Grand Total Amount</td>
                <td>₹<?php echo number_format($row['total_amount'], 2); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="billing-grid">
        <div class="info-card">
            <h6>Payment Information</h6>
            <p>Method: <?php echo htmlspecialchars($row['payment_method'] ?? 'Online'); ?></p>
            <p>Status: <span style="color:#16a34a; font-weight:700;"><?php echo htmlspecialchars($row['payment_status'] ?? 'Paid'); ?></span></p>
        </div>
        <div class="info-card d-flex align-items-center justify-content-between">
            <div>
                <h6>Digital Verification</h6>
                <p style="font-size:12px; color:#64748b;">Scan to verify booking validity</p>
            </div>
            <div style="background:#0f172a; color:#fff; width:60px; height:60px; display:flex; align-items:center; justify-content:center; border-radius:10px; font-weight:bold; font-size:11px;">
                QR SCAN
            </div>
        </div>
    </div>

    <div class="invoice-footer">
        <div style="font-size:13px; color:#64748b;">
            Thank you for renting with <strong>RentX</strong>! Safe travels.
        </div>
        <div style="font-size:12px; color:#94a3b8;">
            Computer Generated Receipt
        </div>
    </div>

    <div class="btn-actions">
        <button class="btn-print" onclick="window.print()"><i class="fa-solid fa-print"></i> Print Invoice / Save PDF</button>
        <a href="booking_history.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to History</a>
    </div>
</div>

</body>
</html>