<?php
    require_once __DIR__ . "/includes/bootstrap.php";
    $pageTitle = "Home";
    require __DIR__ . "/includes/header.php";
?>

<div>
    <h1>
        Welcome to MyRecordingStudio
    </h1>
    <p>
        Book a recording studio across multiple locations that are equipped with the latest tech!
    </p>

    <?php if (!is_logged_in()): ?>
        <p>
            <a class="btn" href="login.php">Login</a>
            <a class="btn btn-secondary" href="location_list.php">Register</a>
        </p>
    
    <?php elseif (current_user_type()==="client"): ?>
        <p>
            <a class="btn" href="booking_create.php">Book a studio now!</a>
            <a class="btn" href="location_list.php">Scout the location now!</a>
        </p>

    <?php else: ?>
        <p>
            <a class="btn" href="location_list.php">View Locations</a>
            <a class="btn btn-secondary" href="admin_client_list.php">View Client List</a>
        </p>

    <?php endif; ?>
</div>

<?php
    require __DIR__ . "/includes/footer.php";
?>