<?php
	$increase10 = function($p1) {
		return $p1 + 10; }; 
	
	echo $increase10(10);
	
	echo "<br>-----------<br>";
	
	$count = 5; 
	$multi = fn($num) => $num * $count; 
	echo $multi(10);
?>
