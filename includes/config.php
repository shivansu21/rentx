<?php

// ============================================================
// InfinityFree Live Production Database Credentials
// ============================================================
$servername = "sql307.infinityfree.com";
$username   = "if0_42729933";
$password   = "QNpUjQfV2LL";
$database   = "if0_42729933_rentx_db";

// Use classic "return false on error" mysqli behaviour instead of the
// PHP 8.1+ default of throwing exceptions on every query error.
// Without this, a single bad query (e.g. a quote/apostrophe inside a
// text field breaking the SQL) causes an uncaught fatal error and the
// browser shows a blank / failed response instead of a normal message.
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection Failed : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// echo "Database Connected Successfully";

?>