<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('client');
$pageTitle = 'My Past Sessions';

$client = new Client(current_user_id(), $_SESSION['user_name'], '', '');
$bookings = $client->completedSessions();

require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <h1>My Previously Completed Sessions</h1>
    <?php if (empty($bookings)): ?>
        <p>You have no completed sessions yet.</p>
    <?php else: ?>
    <table>
        <tr><th>Booking #</th><th>Location</th><th>Studio</th><th>Date</th><th>Time</th><th>Cost</th></tr>
        <?php foreach ($bookings as $b): ?>
        <tr>
            <td><?= (int)$b['booking_id'] ?></td>
            <td><?= h($b['location_description']) ?></td>
            <td><?= h(Studio::displayName((int)$b['studio_number'])) ?></td>
            <td><?= h(format_date($b['booking_date'])) ?></td>
            <td><?= h(substr($b['start_time'],0,5)) ?> - <?= h(substr($b['end_time'],0,5)) ?></td>
            <td>$<?= h(number_format((float)$b['total_cost'],2)) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
