<?php
	
	$Num = 18;
	echo $Num;
	echo "<p>My number is ", $Num, ".</p>";
	echo "<p>My number is $Num.</p>";
	echo '<p>My number is $Num.</p>';

	
	$Num = "value";
	echo "<p>My number is $Num.</p>";
	
	define("MYCONST", 55663);
	echo MYCONST;
	
	const MESSAGE = "Hello";
	echo MESSAGE;
	
	
	
	
	
	$myarray = array("black", "white", "green", "red", "yellow");

	echo "<pre>";
	print_r($myarray);
	echo "</pre>";

	$myarray[] = "new one";

	echo "<pre>";
	print_r($myarray);
	echo "</pre>";
	
	$myarray[2] = "changing";
	print_r($myarray);

	$list = "one";

	$list = 10;
	
	
	$var1 = "120-130 people";
	$var2 = (int)$var1;
	//$var2 = (int)$var1-5;
	
	echo " <p> $var1 </p>";
	echo " <p> $var2 </p>";
	
	$var = "123";
	$var = intval($var); 
	echo " <p> $var </p>";
	
	$txt1 = "I'm learning";
	$txt2 = "PHP";
	$txt = $txt1.$txt2;
	echo " <p> $txt </p>";
	
	$txt = "I'm learning";
	$txt .= "PHP";
	echo " <p> $txt </p>";

?>
