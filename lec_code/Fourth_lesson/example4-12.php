<?php
$states = array("NSW" => "New South Wales", 
				"WA" => "Western Australia", 
				"TAS" => "Tasmania");

echo "<h2>Array states</h2>";
echo "<pre>";
print_r($states);
echo "</pre>";

echo "--------------<br />";

if (in_array("Tasmania", $states))
     echo "<p>The state 'Tasmania' is in the list.</p>";

echo "--------------<br />";

$keyCap = array_search ("Tasmania", $states);
if ($keyCap !== FALSE)
     echo "<p>The state 'Tasmania' has code $keyCap.</p>";

echo "--------------<br />";

if (array_key_exists("WA", $states))
     echo "<p>The key 'WA' is in the list.</p>";
 
 echo "--------------<br />";


echo "<h3>using array_keys()</h3>";
$codes = array_keys($states);
echo "<pre>";
print_r($codes);
echo "</pre>";

echo "--------------<br />";


$states["something"] = "New South Wales";
echo "--------------<br />";
echo "<pre>";
print_r($states);
echo "</pre>";
echo "--------------<br />";

$codes = array_keys($states,"New South Wales");
echo "<pre>";
print_r($codes);
echo "</pre>";
    
echo "--------------<br />";

$states = array("NSW" => "New South Wales", 
				"WA" => "Western Australia", 
				"TAS" => "Tasmania",
				"SA" => "South Australia",
				"VIC" => "Victoria");
echo "<pre>";
print_r($states);
echo "</pre>";

echo "--------------<br />";

echo "<h3> using array_slices()</h3>";
$ThreeStates = array_slice ($states, 2, 3);
foreach ($ThreeStates as $code => $state)
{
    echo "The code for $state is $code <br />";
}
echo "--------------<br />";

$codes = array("NSW", "WA", "TAS", "SA", "VIC", "QLD");
$ThreeStates = array_slice ($codes, 2, 3);
foreach ($ThreeStates as $code => $state)
{
    echo "The code for $state is $code <br />";
}
echo "--------------<br />";
    
?>


