<?php
    declare(strict_types=1);

	function doubleNum(int|float $number) : int|float {
		return $number*=2;
	}
	$num = 5;
	echo '$num =', $num, '<br>';
	echo 'doubleNum returns ', doubleNum($num), '<br>';

	$num = 4.6;
	echo '$num =', $num, '<br>';
	echo 'doubleNum returns ', doubleNum($num), '<br>';
?>

