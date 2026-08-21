<?php
    $Dir = ".";
    $DirEntries = scandir($Dir);

    foreach($DirEntries as $Entry) {
        if ((strcmp($Entry, '.') !=0) && (strcmp($Entry, '..')!=0))
            echo "<a href=\"./" . $Entry . "\">" . $Entry . "</a><br>\n";
    }
?>