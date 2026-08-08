<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('admin');
$pageTitle = 'Manage Bookings';
$errors = [];
$confirmation = null;

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
<div class="card" style="max-width:560px;margin:0 auto;">
    <h1>Create Booking on Behalf of a Client</h1>
    <?php if ($confirmation): ?>
        <div class="alert alert-success">
            Booking #<?= (int)$confirmation['booking_id'] ?> created for <?= h($confirmation['client_name']) ?>
            at <?= h($confirmation['location_description']) ?> (<?= h(Studio::displayName($confirmation['studio_label'], $confirmation['studio_number'])) ?>),
            <?= h($confirmation['booking_date']) ?> <?= h(substr($confirmation['start_time'],0,5)) ?>-<?= h(substr($confirmation['end_time'],0,5)) ?>.
            Total: $<?= h(number_format((float)$confirmation['total_cost'],2)) ?>
        </div>
    <?php endif; ?>
    <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= h($error) ?></div><?php endforeach; ?>
    <form method="post" data-validate>
        <label>Client</label>
        <select name="client_id" data-label="Client" required>
            <option value="">-- Select client --</option>
            <?php foreach ($clients as $c): ?>
                <option value="<?= (int)$c['user_id'] ?>"><?= h($c['name']) ?> (<?= h($c['email']) ?>)</option>
            <?php endforeach; ?>
        </select>

        <label>Location</label>
        <div class="autocomplete" data-role="location-search">
            <input type="text" class="location-search-input" placeholder="Search by name, ID, or studio..." autocomplete="off" data-label="Location" required>
            <input type="hidden" name="location_id" class="location-hidden-id">
            <div class="suggestions"></div>
        </div>

        <label>Booking Date</label>
        <input type="date" name="booking_date" data-label="Booking date" min="<?= date('Y-m-d') ?>" max="<?= Booking::maxBookingDate() ?>" required>

        <label>Start Time (10:00 - 22:00)</label>
        <select name="start_time" data-label="Start time" required>
            <option value="">-- Select a start time --</option>
            <?php foreach (Booking::hourlyStartSlots() as $slot): ?>
                <option value="<?= h($slot) ?>"><?= h($slot) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Duration (hours, 1-12)</label>
        <input type="number" name="duration" data-label="Duration" min="1" max="12" value="1" required>

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
            <td><?= h(Studio::displayName($b['studio_label'], $b['studio_number'])) ?></td>
            <td><?= h($b['booking_date']) ?></td>
            <td><?= h(substr($b['start_time'],0,5)) ?>-<?= h(substr($b['end_time'],0,5)) ?></td>
            <?php $displayStatus = $b['status'] === 'active' ? Booking::timeStatus($b) : 'cancelled'; ?>
            <td><span class="badge badge-<?= h($displayStatus) ?>"><?= h($displayStatus) ?></span></td>
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
<script src="assets/form-validation.js"></script>
<script src="assets/location-autocomplete.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
