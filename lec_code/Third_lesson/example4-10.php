<?php 
/* $Dir = ".";
$DirEntries = scandir($Dir);
foreach ($DirEntries as $Entry) {
	echo $Entry . "<br />\n";
} */

$Dir = ".";//$Dir = "name_of_dir";
$DirEntries = scandir($Dir, 1);
foreach ($DirEntries as $Entry) {
	if ((strcmp($Entry, '.') != 0) && (strcmp($Entry, '..') != 0))
		
		echo "<a href=\"./" . $Entry . "\">" . $Entry . "</a><br />\n";
    
        //echo "<a href=\"name_of_dir/" . $Entry . "\">" . $Entry . "</a><br />\n";
} 
?>

