<?php
    if (isset($_GET["submit"])) {
        $firstName = $_GET["firstName"];
        $lastName = $_GET["lastName"];
        echo "Hello, $firstName $lastName";
    }

    $email = $_POST["email"] ?? "";

    if (empty($email)) {
        echo "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email format is invalid";
    } else {
        echo "Email OK: $email";
    }
?>