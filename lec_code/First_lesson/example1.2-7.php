<?php
	function multiCalc($n1, $n2, $n3)
	{
	/*
       $sum = $n1+$n2+$n3;
       $prod = $n1*$n2*$n3;
	   return array($sum, $prod);
	*/
	   $res[] = $n1+$n2+$n3;
	   $res[] = $n1*$n2*$n3;
	   return $res;
     
	}
	$result = multiCalc(5, 7, 8);
	echo "Results are: ", $result[0], " and ", $result[1];
?>

