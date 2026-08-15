<?php
require_once __DIR__ . '/User.php';

class Client extends User {
    public function __construct(?int $id, string $name, string $phone, string $email) {
        parent::__construct($id, $name, $phone, $email, 'client');
    }

    /** All of this client's completed sessions */
    public function completedSessions(): array {
        return Booking::completedForClient($this->id);
    }

    /** All of this client's current + future sessions */
    public function upcomingSessions(): array {
        return Booking::upcomingForClient($this->id);
    }
}
