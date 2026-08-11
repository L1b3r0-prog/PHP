<?php 

$states = array("NSW" => "New South Wales", 
				"WA" => "Western Australia", 
				"TAS" => "Tasmania",
				"SA" => "South Australia",
				"VIC" => "Victoria",
				"QLD" => "Queensland ");
$capitals = array("Sydney", "Perth", "Hobart", "Adelaide", "Melbourne", "Brisbane");
$codes = array("NSW", "WA", "TAS", "SA", "VIC", "QLD");


$AuStates = array_combine( $codes, $capitals);
echo "<pre>";
print_r($AuStates);
echo "</pre>";

//Fatal error: Uncaught ValueError: array_combine(): Argument #1 ($keys) and argument #2 ($values) must have the same number of elements 


echo "--------------<br />";

$teritories = array("ACT" => "Australian Capital Teritory",
					"NT" => "Northern Teritory");

$Australia = [...$states, ...$teritories];
echo "<pre>";
print_r($Australia);
echo "</pre>";

echo "--------------<br />";

$capitalsExt = ["Canberra", "Darwin", ...$capitals, "ACT", "NT",...$codes];
echo "<pre>";
print_r($capitalsExt);
echo "</pre>";


$capitalsExt = [ ...$states, ...$codes];
echo "<pre>";
print_r($capitalsExt);
echo "</pre>";

?>

