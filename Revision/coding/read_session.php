<?php
    session_start();
    if (isset($_SESSION["Firstname"]) && isset($_SESSION["Occupation"]))
        echo "<p>" . $_SESSION["Firstname"] . " is a " . $_SESSION["Occupation"] . "</p>";
    else
        echo "Session not set.";
?>