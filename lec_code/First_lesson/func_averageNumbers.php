<?php
	function averageNumbers($a, $b, $c) {
		$sum = $a+$b+$c;
		$avg = $sum/3;
		//$avg = (int)($sum/3);
	
		echo "<p> $a+$b+$c </p>";
		echo "<p>", $a+$b+$c, "</p>";
		
		return $avg;
	}
?>
