<?php
    $myFile = fopen("NewFile.txt", "w") or die("Error");
    $txt = "kk \n";
    fwrite($myFile, $txt);
    $txt = "mmm \n";
    fwrite ($myFile, $txt);
    fclose($myFile);
?>