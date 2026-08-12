<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RentX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $currentDir = dirname($_SERVER['PHP_SELF']);

    $isAdmin = strpos($currentDir, '/admin') !== false;

    $isUser = strpos($currentDir, '/user') !== false;

    $base = ($isAdmin || $isUser) ? "../" : "";

    $cssPath = ($isAdmin)
        ? "../css/style.css"
        : $base . "css/style.css";

    ?>

    <link rel="stylesheet" href="<?php echo $cssPath; ?>">

</head>

<body>

    <header>

        <div class="container">

            <div class="logo">

                <a href="<?php echo $base; ?>index.php">

                    Rent<span>X</span>

                </a>

            </div>

            <nav>

                <ul>

                    <li>
                        <a href="<?php echo $base; ?>index.php">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $base; ?>index.php#cars">
                            Cars
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $base; ?>index.php#bikes">
                            Bikes
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $base; ?>about.php">
                            About
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo $base; ?>contact.php">
                            Contact
                        </a>
                    </li>

                </ul>

            </nav>

            <div class="nav-btn">

                <?php

                /* ================= USER ================= */

                if (isset($_SESSION['user_id'])) {
                    ?>

                    <a href="<?php echo $base; ?>user/dashboard.php" class="login-btn">

                        Dashboard

                    </a>

                    <a href="<?php echo $base; ?>booking_history.php" class="login-btn">

                        My Bookings

                    </a>

                    <a href="<?php echo $base; ?>profile.php" class="login-btn">

                        Profile

                    </a>

                    <a href="<?php echo $base; ?>logout.php" class="register-btn">

                        Logout

                    </a>

                    <?php

                }

                /* ================= GUEST ================= */ else {

                    ?>

                    <a href="<?php echo $base; ?>login.php" class="login-btn">

                        Login

                    </a>

                    <a href="<?php echo $base; ?>register.php" class="register-btn">

                        Register

                    </a>

                    <?php

                }

                ?>

            </div>

        </div>

    </header>