<?php 
$departments = array("Marketing", "HR", "Finance", "IT", "HR", "Marketing", "Sales");

echo "<h2>Array</h2>";
echo "<pre>";
print_r($departments);
echo "</pre>";

$departments = array_unique($departments);
echo "<h2>Array after removing duplicates</h2>";
echo "<pre>";
print_r($departments);
echo "</pre>";

    
$departments = array_values($departments);
echo "<h2>Array after renumbering</h2>";
echo "<pre>";
print_r($departments);
echo "</pre>";

unset($departments[3]);
echo "<h2>Array after unset</h2>";
echo "<pre>";
print_r($departments);
echo "</pre>";
    
?>


