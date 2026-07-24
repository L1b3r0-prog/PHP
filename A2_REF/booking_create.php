<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('client');
$pageTitle = 'Book a Studio';
$errors = [];
$confirmation = null;

$locations = Location::all();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locationId = (int)($_POST['location_id'] ?? 0);
    $date = trim($_POST['booking_date'] ?? '');
    $startTime = trim($_POST['start_time'] ?? '');
    $duration = (int)($_POST['duration'] ?? 0);

    if ($locationId <= 0) $errors[] = 'Please select a location.';
    if ($date === '') $errors[] = 'Please choose a booking date.';
    if ($startTime === '') $errors[] = 'Please choose a start time.';

    if (empty($errors)) {
        try {
            $bookingId = Booking::create($locationId, current_user_id(), $date, $startTime, $duration);
            $confirmation = Booking::detailedById($bookingId);
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

require __DIR__ . '/includes/header.php';
?>
<div class="card" style="max-width:560px;">
    <h1>Book a Studio</h1>

    <?php if ($confirmation): ?>
        <div class="alert alert-success">
            <strong>Booking Confirmed!</strong><br>
            Booking #<?= (int)$confirmation['booking_id'] ?> at <?= h($confirmation['location_description']) ?> (Studio <?= (int)$confirmation['studio_number'] ?>)<br>
            Date: <?= h($confirmation['booking_date']) ?><br>
            Time: <?= h(substr($confirmation['start_time'],0,5)) ?> - <?= h(substr($confirmation['end_time'],0,5)) ?> (<?= (int)$confirmation['duration_hours'] ?> hour<?= $confirmation['duration_hours'] > 1 ? 's' : '' ?>)<br>
            Total Cost: $<?= h(number_format((float)$confirmation['total_cost'], 2)) ?>
        </div>
        <p><a class="btn" href="booking_list_upcoming.php">View My Bookings</a> <a class="btn btn-secondary" href="booking_create.php">Book Another</a></p>
    <?php else: ?>
        <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= h($error) ?></div><?php endforeach; ?>
        <form method="post">
            <label>Location</label>
            <select name="location_id" required>
                <option value="">-- Select a location --</option>
                <?php foreach ($locations as $loc): ?>
                    <option value="<?= (int)$loc['location_id'] ?>" <?= (isset($_POST['location_id']) && $_POST['location_id'] == $loc['location_id']) ? 'selected' : '' ?>>
                        <?= h($loc['description']) ?> ($<?= h(number_format((float)$loc['cost_per_hour'],2)) ?>/hr)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Booking Date</label>
            <input type="date" name="booking_date" min="<?= date('Y-m-d') ?>" value="<?= h($_POST['booking_date'] ?? '') ?>" required>

            <label>Start Time (10:00 - 22:00)</label>
            <input type="time" name="start_time" min="10:00" max="22:00" value="<?= h($_POST['start_time'] ?? '') ?>" required>

            <label>Duration (hours, 1-12)</label>
            <input type="number" name="duration" min="1" max="12" value="<?= h($_POST['duration'] ?? '1') ?>" required>

            <button class="btn" type="submit">Confirm Booking</button>
        </form>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
