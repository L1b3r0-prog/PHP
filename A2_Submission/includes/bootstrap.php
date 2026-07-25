<?php
// bootstrap.php - included at the top of every page.
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
