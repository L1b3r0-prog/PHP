<?php 
$indA1 = array("item1", "item2", "item3");

$indA2 = ["item1", "item2", "item3"];

$indA3[] = "item1"; 
$indA3[] = "item2"; 
$indA3[] = "item3"; 

echo "<h2>Array 1</h2>";
echo "<pre>";
print_r($indA1);
echo "</pre>";

echo "<h2>Array 2</h2>";
echo "<pre>";
print_r($indA2);
echo "</pre>";

echo "<h2>Array 3</h2>";
echo "<pre>";
print_r($indA3);
echo "</pre>";

$num = array_push($indA3, 101, 102); 

echo "<h2>Array 3 after push</h2>";
echo "<pre>";
print_r($indA3);
echo "</pre>";
echo"<p> indA3 now has $num elements</p>";

$elem = array_pop($indA3); 

echo "<h2>Array 3 after pop</h2>";
echo "<pre>";
print_r($indA3);
echo "</pre>";
echo"<p> deleted element had value $elem</p>";


array_unshift($indA3, "B1", "B2"); 

echo "<h2>Array 3 after unshift</h2>";
echo "<pre>";
print_r($indA3);
echo "</pre>";

$elem = array_shift($indA3); 

echo "<h2>Array 3 after shift</h2>";
echo "<pre>";
print_r($indA3);
echo "</pre>";
echo"<p> deleted element had value $elem</p>";


?>


