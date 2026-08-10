<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Modify Booking';
$errors = [];

$bookingId = (int)($_GET['id'] ?? $_POST['booking_id'] ?? 0);
$booking = Booking::detailedById($bookingId);

if (!$booking) {
    $_SESSION['flash_error'] = 'Booking not found.';
    header('Location: index.php');
    exit;
}

// Clients may only modify their own bookings; admins may modify any.
if (current_user_type() === 'client' && (int)$booking['client_id'] !== current_user_id()) {
    $_SESSION['flash_error'] = 'You may only modify your own bookings.';
    header('Location: booking_list_upcoming.php');
    exit;
}

if (Booking::hasStarted($booking)) {
    $_SESSION['flash_error'] = 'This session has already started and can no longer be modified.';
    header('Location: ' . (current_user_type() === 'admin' ? 'admin_booking_manage.php' : 'booking_list_upcoming.php'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = trim($_POST['booking_date'] ?? '');
    $startTime = trim($_POST['start_time'] ?? '');
    $duration = (int)($_POST['duration'] ?? 0);

    try {
        Booking::modify($bookingId, $date, $startTime, $duration);
        $_SESSION['flash_success'] = 'Booking updated successfully.';
        header('Location: ' . (current_user_type() === 'admin' ? 'admin_booking_manage.php' : 'booking_list_upcoming.php'));
        exit;
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        $booking = array_merge($booking, ['booking_date' => $date, 'start_time' => $startTime, 'duration_hours' => $duration]);
    }
}

require __DIR__ . '/includes/header.php';
?>
<div class="card" style="max-width:480px;margin:0 auto;">
    <h1>Modify Booking #<?= (int)$bookingId ?></h1>
    <p><?= h($booking['location_description']) ?> &mdash; <?= h(Studio::displayName((int)$booking['studio_number'])) ?>
       <?php if (current_user_type() === 'admin'): ?> &mdash; Client: <?= h($booking['client_name']) ?><?php endif; ?></p>
    <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= h($error) ?></div><?php endforeach; ?>
    <form method="post">
        <input type="hidden" name="booking_id" value="<?= (int)$bookingId ?>">

        <label>Booking Date</label>
        <input type="date" name="booking_date" min="<?= date('Y-m-d') ?>" max="<?= Booking::maxBookingDate() ?>" value="<?= h($booking['booking_date']) ?>" required>

        <label>Time Slot (10:00 - 22:00)</label>
        <select name="start_time" required>
            <?php foreach (Booking::hourlyStartSlots() as $slot): ?>
                <option value="<?= h($slot) ?>" <?= substr($booking['start_time'],0,5) === $slot ? 'selected' : '' ?>><?= h($slot) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Duration</label>
        <input type="number" name="duration" min="1" max="12" value="<?= h((string)$booking['duration_hours']) ?>" required>

        <button class="btn" type="submit">Save Changes</button>
    </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
