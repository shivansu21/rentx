<?php

session_start();

if(!isset($_SESSION['admin']))
{
    header("Location:login.php");
    exit();
}

include "../includes/config.php";

if(isset($_GET['id']))
{
    $id = (int)$_GET['id'];

    mysqli_query($conn,"
    UPDATE bookings
    SET booking_status='Rejected'
    WHERE id='$id'
    ");

    header("Location:manage_bookings.php");
    exit();
}

?>