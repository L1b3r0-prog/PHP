<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('client');
$pageTitle = 'Book a Studio';
$errors = [];
$confirmation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locationId = (int)($_POST['location_id'] ?? 0);
    $date = trim($_POST['booking_date'] ?? '');
    $startTime = trim($_POST['start_time'] ?? '');
    $duration = (int)($_POST['duration'] ?? 0);

    if ($locationId <= 0) $errors[] = 'Please select a location.';
    if ($date === '') $errors[] = 'Please choose a booking date.';
    if ($startTime === '') $errors[] = 'Please choose a time slot.';

    // A specific studio is never chosen by the client -- the first free
    // studio at the location is auto-assigned. If none is free for the
    // requested slot, Booking::create() throws an exception whose message
    // is shown below as the error.
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
<div class="card" style="max-width:560px;margin:0 auto;">
    <h1>Book a Studio</h1>

    <?php if ($confirmation): ?>
        <div class="alert alert-success">
            <strong>Booking Confirmed!</strong><br>
            Booking #<?= (int)$confirmation['booking_id'] ?> at <?= h($confirmation['location_description']) ?> (<?= h(Studio::displayName((int)$confirmation['studio_number'])) ?>)<br>
            Date: <?= h(format_date($confirmation['booking_date'])) ?><br>
            Time: <?= h(substr($confirmation['start_time'],0,5)) ?> - <?= h(substr($confirmation['end_time'],0,5)) ?> (<?= (int)$confirmation['duration_hours'] ?> hour<?= $confirmation['duration_hours'] > 1 ? 's' : '' ?>)<br>
            Total Cost: $<?= h(number_format((float)$confirmation['total_cost'], 2)) ?>
        </div>
        <p><a class="btn" href="booking_list_upcoming.php">View My Bookings</a> <a class="btn btn-secondary" href="booking_create.php">Book Another</a></p>
    <?php else: ?>
        <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= h($error) ?></div><?php endforeach; ?>
        <form method="post" id="booking-form">
            <label>Location</label>
            <div class="autocomplete" data-role="location-search">
                <input type="text" class="location-search-input" placeholder="Search by name or ID..." autocomplete="off" required>
                <input type="hidden" name="location_id" class="location-hidden-id">
                <div class="suggestions"></div>
            </div>

            <label>Booking Date</label>
            <input type="date" name="booking_date" min="<?= date('Y-m-d') ?>" max="<?= Booking::maxBookingDate() ?>" value="<?= h($_POST['booking_date'] ?? '') ?>" required>

            <label>Time Slot (10:00 - 22:00)</label>
            <select name="start_time" required>
                <option value="">-- Select a time slot --</option>
                <?php foreach (Booking::hourlyStartSlots() as $slot): ?>
                    <option value="<?= h($slot) ?>" <?= ($_POST['start_time'] ?? '') === $slot ? 'selected' : '' ?>><?= h($slot) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Duration</label>
            <input type="number" name="duration" min="1" max="12" value="<?= h($_POST['duration'] ?? '1') ?>" required>

            <button class="btn" type="submit">Confirm Booking</button>
        </form>
    <?php endif; ?>
</div>
<script src="assets/location-autocomplete.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
