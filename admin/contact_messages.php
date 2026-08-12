<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit();
}

include "../includes/config.php";

$result = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY id DESC");
$activePage = 'contact_messages';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - RentX Admin</title>

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
                <h2><i class="fa-solid fa-envelope"></i> Contact Messages</h2>
            </div>

            <div class="table-scroll">
                <table class="panel-table">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>

                    <?php if (mysqli_num_rows($result) === 0): ?>
                        <tr>
                            <td colspan="6" class="empty-row">No messages yet.</td>
                        </tr>
                    <?php endif; ?>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td class="message-cell"><?php echo htmlspecialchars($row['message']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

    <?php include "partials_end.php"; ?>

</body>

</html>
