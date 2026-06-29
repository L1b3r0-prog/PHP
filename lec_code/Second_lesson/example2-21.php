
<?php
$subjects = "CSIT128; CSIT884; CSIT323; MTS9307";
$subjectsArray = explode(";", $subjects);
foreach ($subjectsArray as $subject) {
		echo "$subject<br />";
}

echo "<p>-----------------</p>";

$subjectsArray = array("CSIT128", "CSIT884", "CSIT323", "MTS9307");
$subjects = implode(" & ", $subjectsArray);
echo $subjects;

echo "<p>-----------------</p>";

echo strcasecmp ("Check this", "check this");

echo "<p>-----------------</p>";

echo "<p>-----------------</p>";

echo strcmp ("check this", "Check this");

echo "<p>-----------------</p>";



$my_str = "Bob is working";
echo "<p>$my_str</p>";

$my_str[0] = "R";
echo "<p>$my_str</p>";

echo "<p> try this with negative -5: $my_str[-5]</p>";

for($i=0; $i< strlen($my_str); $i++)
    echo "<p> * $my_str[$i]</p>";







?>



</body>
</html>

