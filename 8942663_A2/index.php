<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Home';
require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <h1>Welcome to MyRecordingStudio</h1>
    <p>Book audio-visual equipped recording studios across multiple locations.</p>
    <?php if (!is_logged_in()): ?>
        <p><a class="btn" href="login.php">Login</a> <a class="btn btn-secondary" href="register.php">Register</a></p>
    <?php elseif (current_user_type() === 'client'): ?>
        <p><a class="btn" href="booking_create.php">Book a Studio</a> <a class="btn btn-secondary" href="location_list.php">View Locations</a></p>
    <?php else: ?>
        <p><a class="btn" href="location_list.php">View Locations</a> <a class="btn btn-secondary" href="admin_client_list.php">View Clients</a></p>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
