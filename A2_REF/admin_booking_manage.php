<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('admin');
$pageTitle = 'Manage Bookings';
$errors = [];
$confirmation = null;

$locations = Location::all();
$clients = User::allClients();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = (int)($_POST['client_id'] ?? 0);
    $locationId = (int)($_POST['location_id'] ?? 0);
    $date = trim($_POST['booking_date'] ?? '');
    $startTime = trim($_POST['start_time'] ?? '');
    $duration = (int)($_POST['duration'] ?? 0);

    if ($clientId <= 0) $errors[] = 'Please select a client.';
    if ($locationId <= 0) $errors[] = 'Please select a location.';

    if (empty($errors)) {
        try {
            $bookingId = Booking::createByAdmin($locationId, $clientId, $date, $startTime, $duration);
            $confirmation = Booking::detailedById($bookingId);
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

$allBookings = Booking::allBookings();

require __DIR__ . '/includes/header.php';
?>
<div class="card" style="max-width:560px;">
    <h1>Create Booking on Behalf of a Client</h1>
    <?php if ($confirmation): ?>
        <div class="alert alert-success">
            Booking #<?= (int)$confirmation['booking_id'] ?> created for <?= h($confirmation['client_name']) ?>
            at <?= h($confirmation['location_description']) ?> (Studio <?= (int)$confirmation['studio_number'] ?>),
            <?= h($confirmation['booking_date']) ?> <?= h(substr($confirmation['start_time'],0,5)) ?>-<?= h(substr($confirmation['end_time'],0,5)) ?>.
            Total: $<?= h(number_format((float)$confirmation['total_cost'],2)) ?>
        </div>
    <?php endif; ?>
    <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= h($error) ?></div><?php endforeach; ?>
    <form method="post">
        <label>Client</label>
        <select name="client_id" required>
            <option value="">-- Select client --</option>
            <?php foreach ($clients as $c): ?>
                <option value="<?= (int)$c['user_id'] ?>"><?= h($c['name']) ?> (<?= h($c['email']) ?>)</option>
            <?php endforeach; ?>
        </select>

        <label>Location</label>
        <select name="location_id" required>
            <option value="">-- Select location --</option>
            <?php foreach ($locations as $loc): ?>
                <option value="<?= (int)$loc['location_id'] ?>"><?= h($loc['description']) ?> ($<?= h(number_format((float)$loc['cost_per_hour'],2)) ?>/hr)</option>
            <?php endforeach; ?>
        </select>

        <label>Booking Date</label>
        <input type="date" name="booking_date" min="<?= date('Y-m-d') ?>" required>

        <label>Start Time (10:00 - 22:00)</label>
        <input type="time" name="start_time" min="10:00" max="22:00" required>

        <label>Duration (hours, 1-12)</label>
        <input type="number" name="duration" min="1" max="12" value="1" required>

        <button class="btn" type="submit">Create Booking</button>
    </form>
</div>

<div class="card">
    <h2>All Bookings</h2>
    <table>
        <tr><th>#</th><th>Client</th><th>Location</th><th>Studio</th><th>Date</th><th>Time</th><th>Status</th><th>Action</th></tr>
        <?php foreach ($allBookings as $b): ?>
        <tr>
            <td><?= (int)$b['booking_id'] ?></td>
            <td><?= h($b['client_name']) ?></td>
            <td><?= h($b['location_description']) ?></td>
            <td><?= (int)$b['studio_number'] ?></td>
            <td><?= h($b['booking_date']) ?></td>
            <td><?= h(substr($b['start_time'],0,5)) ?>-<?= h(substr($b['end_time'],0,5)) ?></td>
            <td><span class="badge badge-<?= $b['status'] === 'active' ? 'active' : 'cancelled' ?>"><?= h($b['status']) ?></span></td>
            <td>
                <?php if ($b['status'] === 'active' && !Booking::hasStarted($b)): ?>
                    <a href="booking_edit.php?id=<?= (int)$b['booking_id'] ?>">Modify</a> |
                    <a href="booking_cancel.php?id=<?= (int)$b['booking_id'] ?>" onclick="return confirm('Cancel this booking?')">Cancel</a>
                <?php else: ?>
                    &mdash;
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
