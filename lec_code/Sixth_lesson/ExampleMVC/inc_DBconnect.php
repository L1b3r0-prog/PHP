<?php
try {
    $conn = new mysqli("localhost", "root", "", "mydb");
}
catch (mysqli_sql_exception $e) {
	die("The database server is not available. Error: " . $e->getCode() . "." . $e->getMessage());
}
?>
