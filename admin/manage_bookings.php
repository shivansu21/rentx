<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit();
}

include "../includes/config.php";

$query = mysqli_query($conn, "
SELECT
bookings.*,
users.fullname,
vehicles.vehicle_name
FROM bookings
INNER JOIN users
ON bookings.user_id = users.id
INNER JOIN vehicles
ON bookings.vehicle_id = vehicles.id
ORDER BY bookings.id DESC
");

$activePage = 'manage_bookings';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - RentX Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include "partials_sidebar.php"; ?>

        <div class="panel-table-card">
            <div class="panel-table-header">
                <h2><i class="fa-solid fa-calendar-check"></i> Manage Bookings</h2>
            </div>

            <div class="table-scroll">
                <table class="panel-table">
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Pickup</th>
                        <th>Return</th>
                        <th>KM</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                    <?php if (mysqli_num_rows($query) === 0): ?>
                        <tr>
                            <td colspan="9" class="empty-row">No bookings yet.</td>
                        </tr>
                    <?php endif; ?>

                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($row['vehicle_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['pickup_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['return_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['estimated_km']); ?></td>
                            <td>₹<?php echo htmlspecialchars($row['total_amount']); ?></td>
                            <td>
                                <?php
                                $statusClass = 'status-pending';
                                if ($row['booking_status'] == 'Approved') $statusClass = 'status-available';
                                if ($row['booking_status'] == 'Rejected') $statusClass = 'status-unavailable';
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($row['booking_status']); ?>
                                </span>
                            </td>
                            <td class="action-cell">
                                <a href="approve_booking.php?id=<?php echo $row['id']; ?>" class="approve-btn">
                                    <i class="fa-solid fa-check"></i>
                                    <span>Approve</span>
                                </a>

                                <a href="reject_booking.php?id=<?php echo $row['id']; ?>" class="reject-btn">
                                    <i class="fa-solid fa-xmark"></i>
                                    <span>Reject</span>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

    <?php include "partials_end.php"; ?>

</body>

</html>
