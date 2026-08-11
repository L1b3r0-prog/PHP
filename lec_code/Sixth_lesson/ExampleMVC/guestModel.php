<?php
class GuestModel {
    private $conn;

    public function __construct() {
        include("inc_DBconnect.php");
		$this->conn = $conn;
    }

    public function getGuest(int $id) {
        $stmt = $this->conn->prepare("SELECT * FROM myguests WHERE id = ?");
        $stmt->bind_param("i", $id);
		$stmt->execute();
		return $stmt->get_result()->fetch_assoc();
    }

    // More  operations...
}
?>