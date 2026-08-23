<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentX - Car & Bike Rental Platform</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $currentDir = dirname($_SERVER['PHP_SELF']);
    $isAdmin = strpos($currentDir, '/admin') !== false;
    $isUser = strpos($currentDir, '/user') !== false;
    $base = ($isAdmin || $isUser) ? "../" : "";
    $cssPath = ($isAdmin) ? "../css/style.css" : $base . "css/style.css";
    $scriptPath = ($isAdmin) ? "../js/script.js" : $base . "js/script.js";
    ?>

    <!-- Custom RentX Styles -->
    <link rel="stylesheet" href="<?php echo $cssPath; ?>">
</head>

<body>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg sticky-top main-nav shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="<?php echo $base; ?>index.php">
                <div class="brand-icon d-flex align-items-center justify-content-center text-white rounded-3 bg-primary" style="width:40px; height:40px; font-size: 20px;">
                    <i class="fa-solid fa-car"></i>
                </div>
                <span class="brand-title">Rent<span class="text-primary">X</span></span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#rentxNav" aria-controls="rentxNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="rentxNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-medium" href="<?php echo $base; ?>index.php">
                            <i class="fa-solid fa-house me-1 opacity-75"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-medium" href="<?php echo $base; ?>index.php#cars">
                            <i class="fa-solid fa-car-side me-1 opacity-75"></i> Cars
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-medium" href="<?php echo $base; ?>index.php#bikes">
                            <i class="fa-solid fa-motorcycle me-1 opacity-75"></i> Bikes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-medium" href="<?php echo $base; ?>about.php">
                            <i class="fa-solid fa-circle-info me-1 opacity-75"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-medium" href="<?php echo $base; ?>contact.php">
                            <i class="fa-solid fa-headset me-1 opacity-75"></i> Contact
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    <!-- Dark / Light Mode Toggle Button -->
                    <button type="button" id="themeToggleBtn" class="btn btn-outline-secondary rounded-pill px-3 py-2 d-flex align-items-center gap-2 fw-semibold shadow-sm" title="Switch Theme">
                        <i class="fa-solid fa-moon text-warning" id="themeToggleIcon"></i>
                        <span id="themeToggleText" class="d-none d-sm-inline fs-7">Dark Mode</span>
                    </button>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle rounded-pill px-4 py-2 d-flex align-items-center gap-2 fw-semibold" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-circle-user fs-5"></i>
                                <span><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'My Account'); ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2" aria-labelledby="userMenu">
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 px-3 fw-medium" href="<?php echo $base; ?>user/dashboard.php">
                                        <i class="fa-solid fa-gauge me-2 text-primary"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 px-3 fw-medium" href="<?php echo $base; ?>booking_history.php">
                                        <i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i> My Bookings
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 px-3 fw-medium" href="<?php echo $base; ?>profile.php">
                                        <i class="fa-solid fa-id-card me-2 text-primary"></i> Profile Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 px-3 fw-medium text-danger" href="<?php echo $base; ?>logout.php">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo $base; ?>login.php" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Login
                        </a>
                        <a href="<?php echo $base; ?>register.php" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm">
                            <i class="fa-solid fa-user-plus me-1"></i> Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>