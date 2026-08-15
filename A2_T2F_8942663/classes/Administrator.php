<?php
require_once __DIR__ . '/User.php';

class Administrator extends User {
    public function __construct(?int $id, string $name, string $phone, string $email) {
        parent::__construct($id, $name, $phone, $email, 'admin');
    }
}
