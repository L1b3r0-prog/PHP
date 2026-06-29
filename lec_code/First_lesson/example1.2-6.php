<?php
	function averageNumbers($a, $b, $c) {
		$sum = $a+$b+$c;
		$avg = $sum/3;
		//$avg = (int)($sum/3);
	
		echo "<p> $a+$b+$c </p>";
		echo "<p>", $a+$b+$c, "</p>";
		
		return $avg;
	}

	echo averageNumbers(5,6,7);
	echo "<br />------ <br />";
	echo averageNumbers(5,5,7);
?>
