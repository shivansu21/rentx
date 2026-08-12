<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location:login.php");
    exit();
}

include "../includes/config.php";

$id = $_GET['id'] ?? null;

if ($id !== null) {
    $stmt = mysqli_prepare($conn, "SELECT vehicle_image FROM vehicles WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    $del = mysqli_prepare($conn, "DELETE FROM vehicles WHERE id = ?");
    mysqli_stmt_bind_param($del, "i", $id);
    mysqli_stmt_execute($del);

    if ($row && !empty($row['vehicle_image'])) {
        $path = __DIR__ . "/../uploads/vehicles/" . $row['vehicle_image'];
        if (is_file($path)) {
            @unlink($path);
        }
    }
}

header("Location:manage_vehicles.php");
exit();
