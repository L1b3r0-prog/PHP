<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('client');
$pageTitle = 'My Upcoming Sessions';

$client = new Client(current_user_id(), $_SESSION['user_name'], '', '');
$bookings = $client->upcomingSessions();

require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <h1>My Current & Upcoming Sessions</h1>
    <?php if (empty($bookings)): ?>
        <p>You have no current or upcoming bookings. <a href="booking_create.php">Book a studio now</a>.</p>
    <?php else: ?>
    <table>
        <tr><th>Booking #</th><th>Location</th><th>Studio</th><th>Date</th><th>Time</th><th>Cost</th><th>Action</th></tr>
        <?php foreach ($bookings as $b): ?>
        <tr>
            <td><?= (int)$b['booking_id'] ?></td>
            <td><?= h($b['location_description']) ?></td>
            <td><?= h(Studio::displayName((int)$b['studio_number'])) ?></td>
            <td><?= h(format_date($b['booking_date'])) ?></td>
            <td><?= h(substr($b['start_time'],0,5)) ?> - <?= h(substr($b['end_time'],0,5)) ?></td>
            <td>$<?= h(number_format((float)$b['total_cost'],2)) ?></td>
            <td>
                <?php if (!Booking::hasStarted($b)): ?>
                    <a href="booking_edit.php?id=<?= (int)$b['booking_id'] ?>">Modify</a> |
                    <a href="booking_cancel.php?id=<?= (int)$b['booking_id'] ?>" onclick="return confirm('Cancel this booking?')">Cancel</a>
                <?php else: ?>
                    <span class="badge badge-active">In progress</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
