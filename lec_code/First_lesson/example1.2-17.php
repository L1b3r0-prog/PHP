<?php
    //declare(strict_types=1);

	function doubleNum(int $number) : int {
		return $number *= 2;
	}
	$num = 5;
	echo '$num =', $num, '<br>';
	echo 'doubleNum returns ', doubleNum($num), '<br>';

	$num = 4.8;
	echo '$num =', $num, '<br>';
	echo 'doubleNum returns ', doubleNum($num), '<br>';

	$num = "40";
	echo '$num =', $num, '<br>';
	echo 'doubleNum returns ', doubleNum($num), '<br>';
?>

