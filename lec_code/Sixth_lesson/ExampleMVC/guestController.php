<?php
class GuestController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showGuest($id) {
        $guest = $this->model->getGuest($id);
        include 'guestView.php';
    }

    // More actions...
}
?>