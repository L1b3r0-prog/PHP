<?php
require_once __DIR__ . '/User.php';

/**
 * Administrator.php
 * Represents an Administrator user. Location/booking management methods
 * are on Location.php / Booking.php; this class exists mainly for
 * type-identity (instanceof checks) and any admin-only helpers.
 */
class Administrator extends User {
    public function __construct(?int $id, string $name, string $phone, string $email) {
        parent::__construct($id, $name, $phone, $email, 'admin');
    }
}
