<?php
$asA1 = array("I1" => "item1", "I2" => "item2", "I3" => "item3");

$asA2 = ["I1" => "item1", "I2" => "item2", "I3" => "item3"];

$asA3["I1"] = "item1"; 
$asA3["I2"] = "item2"; 
$asA3["I3"] = "item3"; 

echo "<h2>Array 1</h2>";
echo "<pre>";
print_r($asA1);
echo "</pre>";

echo "<h2>Array 2</h2>";
echo "<pre>";
print_r($asA2);
echo "</pre>";

echo "<h2>Array 3</h2>";
echo "<pre>";
print_r($asA3);
echo "</pre>";

$states["NSW"] = "New South Wales";
$states["WA"] = "Western Australia";
$states[] = "Tasmania";

echo "<h2>Array states</h2>";
echo "<pre>";
print_r($states);
echo "</pre>";

$states1[100] = "New South Wales";
$states1[] = "Western Australia";
$states1[] = "Tasmania";

echo "<h2>Array states1</h2>";
echo "<pre>";
print_r($states1);
echo "</pre>";

$states = array("NSW" => "New South Wales", 
				"WA" => "Western Australia", 
				"TAS" => "Tasmania");


foreach ($states as $state) {
   echo "The " . key($states) ." is $state<br />";  }

echo "--------------<br />";

foreach ($states as $key => $state)
{
    echo "The $key is $state<br />";
}
?>

