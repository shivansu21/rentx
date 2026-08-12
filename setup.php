<?php
// One-time setup script.
// Visit this file once in your browser (e.g. http://localhost/RentX_Fixed/setup.php)
// to create the database and all tables automatically.
//
// Delete this file after setup is complete — it should not stay on a live server.

$servername = "localhost";
$username   = "root";
$password   = "";

$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die("<h2>Connection Failed</h2><p>" . htmlspecialchars(mysqli_connect_error()) . "</p>
    <p>Check the \$servername / \$username / \$password values at the top of setup.php (they should match includes/config.php).</p>");
}

$sqlFile = __DIR__ . "/setup.sql";

if (!file_exists($sqlFile)) {
    die("<h2>setup.sql not found</h2><p>Make sure setup.sql is in the same folder as setup.php.</p>");
}

$sql = file_get_contents($sqlFile);

$success = true;
$errorMsg = "";

if (mysqli_multi_query($conn, $sql)) {
    do {
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_more_results($conn) && mysqli_next_result($conn));

    if (mysqli_errno($conn)) {
        $success = false;
        $errorMsg = mysqli_error($conn);
    }
} else {
    $success = false;
    $errorMsg = mysqli_error($conn);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>RentX Setup</title>
<style>
    body{font-family:Arial,sans-serif;background:#f4f6fb;padding:60px;color:#111827;}
    .box{max-width:600px;margin:auto;background:#fff;padding:36px;border-radius:14px;box-shadow:0 8px 24px rgba(17,24,39,.08);}
    h1{font-size:22px;margin-bottom:16px;}
    .ok{color:#166534;background:#dcfce7;padding:14px 18px;border-radius:10px;margin-bottom:16px;}
    .err{color:#991b1b;background:#fee2e2;padding:14px 18px;border-radius:10px;margin-bottom:16px;white-space:pre-wrap;}
    code{background:#f1f5f9;padding:2px 6px;border-radius:4px;}
    a{color:#2563EB;font-weight:600;}
</style>
</head>
<body>
<div class="box">
    <h1>RentX Database Setup</h1>

    <?php if ($success): ?>
        <p class="ok">Database <code>rentx_fixed</code> and all tables were created successfully.</p>
        <p>Default admin login:</p>
        <p><code>username: admin</code><br><code>password: admin123</code></p>
        <p><b>Please change this password after logging in</b>, and delete <code>setup.php</code> from the server now.</p>
        <p><a href="admin/login.php">Go to Admin Login →</a></p>
    <?php else: ?>
        <p class="err"><?php echo htmlspecialchars($errorMsg); ?></p>
        <p>You can also import <code>setup.sql</code> manually via phpMyAdmin instead.</p>
    <?php endif; ?>
</div>
</body>
</html>
