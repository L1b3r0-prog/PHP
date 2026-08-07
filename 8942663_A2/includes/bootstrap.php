<?php
// bootstrap.php - included at the top of every page.

// PHP defaults to UTC unless told otherwise, while MySQL's NOW() uses the
// server's local system clock. Left unset, those two disagree by your UTC
// offset -- e.g. a booking correctly rejected by PHP's clock could still be
// misclassified as upcoming/completed by MySQL, or vice versa. Setting this
// explicitly keeps every date()/time()/strtotime() call in the app on the
// same clock as the database. Change to your actual deployment timezone.
date_default_timezone_set('Asia/Singapore');

// Prevent the browser from caching protected pages. Without this, clicking
// "back" after logout can show a stale, cached copy of a page instead of
// re-requesting it from the server -- which would skip require_login()
// entirely and let a logged-out user see content that should be blocked.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Client.php';
require_once __DIR__ . '/../classes/Administrator.php';
require_once __DIR__ . '/../classes/Location.php';
require_once __DIR__ . '/../classes/Studio.php';
require_once __DIR__ . '/../classes/Booking.php';

function current_user_id(): ?int {
    return $_SESSION['user_id'] ?? null;
}
function current_user_type(): ?string {
    return $_SESSION['user_type'] ?? null;
}
function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}
function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
function require_role(string $role): void {
    require_login();
    if (current_user_type() !== $role) {
        header('Location: index.php');
        exit;
    }
}
function h(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
