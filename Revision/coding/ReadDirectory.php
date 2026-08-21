<?php
    $Dir = ".";
    $DirOpen = opendir($Dir);
    while ($CurFile = readdir($DirOpen)) {
        if ((strcmp($CurFile, '.') !=0) && (strcmp($CurFile, '..') !=0))
            echo "<a href=\"./" . $CurFile ."\">" . $CurFile . "</a><br/>\n";
        }

    closedir($DirOpen);
?>