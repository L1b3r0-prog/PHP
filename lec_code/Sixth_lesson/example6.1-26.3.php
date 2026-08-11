<?php
    session_start();
   
    if (isset($_SESSION['firstName']) && isset($_SESSION['lastName'])&& isset($_SESSION['occupation']))
        echo "<p> 26.3-" . $_SESSION['firstName'] . " ". $_SESSION['lastName'] . " is a " . $_SESSION['occupation'] . "</p>";
?>

