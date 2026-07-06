<?php
echo "<h1>reading file</h1>";

$myfile = fopen("user.txt", "r") or die("can't open file");
while (!feof($myfile))
    {
        echo fgets($myfile), "<br>";
    }
fclose($myfile);
?>