<?php
    session_start();
    $_SESSION["Firstname"] = "Benjamin";
    $_SESSION["Occupation"] = "Coder";
?>
<a href="read_session.php">Go read session</a>