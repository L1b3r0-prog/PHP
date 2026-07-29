<?php
    require_once __DIR__ . "/User.php";

    class Client extends User {
        public function __construct(?int $id, string $name, string $phone, string $email) {
            parent::__construct($id, $name, $phone, $email, "client");
        }
        
        public function completedSessions(): array {
            return Booking::completedForClient($this->id);
        }

        public function upcomingSessions(): array {
            return Booking::upcomingForClient($this->id);
        }
    }