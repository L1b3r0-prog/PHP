<?php
    if (isset($_COOKIE["Firstname"]) && isset($_COOKIE["Occupation"]))
        echo "{$_COOKIE["Firstname"]} is a {$_COOKIE["Occupation"]}";
    else
        echo "Cookie not set";
?>