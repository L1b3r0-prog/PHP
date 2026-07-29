<?php
    require_once __DIR__ . "/User.php";

    class Client extends User {
        public function __construct(?int $id, string $name, string $phone, string $email) {
            parent::__construct($id, $name, $phone, $email, "client");
        }
        
        # This shows the client's completed session
        public function completedSessions(): array {
            return Booking::completedForClient($this->id);
        }

        # This shows the client's current and future sessions
        public function upcomingSessions(): array {
            return Booking::upcomingForClient($this->id);
        }
    }