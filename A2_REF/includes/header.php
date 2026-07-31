<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= isset($pageTitle) ? h($pageTitle) . ' - ' : '' ?>MyRecordingStudio</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
    <div class="header-bar">
        <div class="brand"><a href="index.php">MyRecording<span>Studio</span></a></div>
        <nav>
            <?php if (is_logged_in()): ?>
                <?php if (current_user_type() === 'admin'): ?>
                    <a href="location_list.php">Locations</a>
                    <a href="location_create.php">Add Location</a>
                    <a href="admin_booking_manage.php">Manage Bookings</a>
                    <a href="admin_client_list.php">Clients</a>
                    <a href="admin_create.php">Add Admin</a>
                <?php else: ?>
                    <a href="location_list.php">Locations</a>
                    <a href="booking_create.php">Book a Studio</a>
                    <a href="booking_list_upcoming.php">My Upcoming Sessions</a>
                    <a href="booking_list_completed.php">My Past Sessions</a>
                <?php endif; ?>
                <a href="location_search.php">Search</a>
                <span class="user-info">Hi, <?= h($_SESSION['user_name']) ?> <span class="role-chip"><?= h(current_user_type()) ?></span></span>
                <a href="logout.php" class="nav-logout">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="nav-cta">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-error"><?= h($_SESSION['flash_error']) ?></div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= h($_SESSION['flash_success']) ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>
