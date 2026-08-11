<?php 
$departments = array("Marketing", "Finance", "IT");

echo "<h2>Array</h2>";
echo "<pre>";
print_r($departments);
echo "</pre>";

array_splice($departments, 2, 0, array("HR", "Sales"));

echo "<h2>Array after adding at 2</h2>";
echo "<pre>";
print_r($departments);
echo "</pre>";

array_splice($departments, 3, 0, "Add New");

echo "<h2>Array after adding at 3</h2>";
echo "<pre>";
print_r($departments);
echo "</pre>";

array_splice($departments, 1, 2);
    
echo "<h2>Array after delete at 1, 2 elements</h2>";
echo "<pre>";
print_r($departments);
echo "</pre>";

    
array_splice($departments, 1, 1, array("add1", "add2"));
echo "<pre>";
print_r($departments);
echo "</pre>";
    
?>


