<?php

	function sum(...$numbers) {
		$acc = 0;
		echo "<br /> numbers: <br />";
		foreach ($numbers as $n) {
			echo $n, "<br />";
			$acc += $n; //$acc = $acc + $n;
		}
		return $acc;
	}
	
	function sum1($one,...$numbers) {
		$acc = 0;
		echo "<br /> $one and then numbers: <br />";
		foreach ($numbers as $n) {
			echo $n, "<br />";
			$acc += $n; //$acc = $acc + $n;
		}
		return $acc;
	}
	
	echo "<br> function sum:";
	$sum = sum();
	echo "sum = $sum <br />";

	$sum = sum(100);
	echo "sum = $sum <br />";

	$sum = sum(100, 200);
	echo "sum = $sum <br />";
	
	
	echo "<br> function sum1:";
	$sum = sum1("first");
	echo "sum = $sum <br />";

	$sum = sum1("second",1, 2, 3);
	echo "sum = $sum <br />";

	$sum = sum1("third",10, 20);
	echo "sum = $sum <br />";
?>
