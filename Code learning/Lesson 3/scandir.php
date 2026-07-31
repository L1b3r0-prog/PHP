<?php
    $Dir = ".";
    $Scan = scandir($Dir);
    foreach ($Scan as $Entry) {
        if ((strcmp($Entry, ".") != 0) && (strcmp($Entry, "..l") != 0))
            echo "<a href=\"./" . $Entry . "\">" . $Entry . "</a></br>\n";
    }
?>