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

<?php include "partials_sidebar.php"; ?>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-extrabold text-dark mb-1"><i class="fa-solid fa-comments text-primary me-2"></i>Customer Inquiry Messages</h4>
            <p class="text-secondary small mb-0">Messages submitted by site visitors and registered renters via the contact form.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-secondary fs-7 text-uppercase">
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Email Address</th>
                    <th>Subject</th>
                    <th>Message Details</th>
                    <th>Date Received</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-inbox fs-1 mb-2 d-block text-muted"></i>
                            No contact messages received yet.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="fw-bold text-primary">#<?php echo $row['id']; ?></td>
                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['fullname']); ?></td>
                        <td>
                            <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-decoration-none fw-semibold">
                                <i class="fa-solid fa-envelope me-1 text-primary"></i><?php echo htmlspecialchars($row['email']); ?>
                            </a>
                        </td>
                        <td><span class="badge bg-light text-dark border fs-7"><?php echo htmlspecialchars($row['subject']); ?></span></td>
                        <td style="max-width:300px;">
                            <p class="text-secondary small mb-0 text-truncate" title="<?php echo htmlspecialchars($row['message']); ?>">
                                <?php echo htmlspecialchars($row['message']); ?>
                            </p>
                        </td>
                        <td><small class="text-secondary"><?php echo htmlspecialchars($row['created_at']); ?></small></td>
                        <td class="text-center">
                            <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>?subject=Re: <?php echo urlencode($row['subject']); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">
                                <i class="fa-solid fa-reply me-1"></i>Reply
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "partials_end.php"; ?>

