<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

$bookingId = (int)($_GET['id'] ?? 0);
$booking = Booking::detailedById($bookingId);
$redirect = current_user_type() === 'admin' ? 'admin_booking_manage.php' : 'booking_list_upcoming.php';

if (!$booking) {
    $_SESSION['flash_error'] = 'Booking not found.';
    header('Location: ' . $redirect);
    exit;
}

if (current_user_type() === 'client' && (int)$booking['client_id'] !== current_user_id()) {
    $_SESSION['flash_error'] = 'You may only cancel your own bookings.';
    header('Location: ' . $redirect);
    exit;
}

try {
    Booking::cancel($bookingId);
    $_SESSION['flash_success'] = 'Booking #' . $bookingId . ' has been cancelled.';
} catch (Exception $e) {
    $_SESSION['flash_error'] = $e->getMessage();
}

header('Location: ' . $redirect);
exit;
