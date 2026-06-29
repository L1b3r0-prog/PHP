<?php
	$num=6;

	if($num>5){
		if($num<10)
			echo "<p> the number is between 5 and 10 </p>";
		}
		
		if($num>5 && $num<10)
			echo "<p> the number is between 5 and 10 </p>";
		else
			echo "<p> the number is not between 5 and 10 </p>";
		
		
		$num=9;
		if($num==5)
			echo "<p> the number is  5 </p>";
		elseif ($num<10) 
			echo "<p> the number is less than 10 </p>";
		else
			echo "<p> the number is out of range </p>";

		$a = 5;
		$b = 6;
		if (($a<=>$b) == -1)
			echo "<p> a < b </p>";
		elseif (($a<=>$b) == 1)
			echo "<p> a > b </p>";
		else
			echo "<p> a = b </p>";


		$today = " Tuesday ";
		echo $today == " Monday " ? " <p>Today is Monday</p> " : " <p>Today 								is not Monday</p> ";

		//$tomorrow = 5;
		$tomorrow = $tomorrow ?? " not defined ";
		echo $tomorrow . "<br>";



		$page = "Login";
		
		if ($page == "Home") echo "You selected Home";
		elseif ($page == "About") echo "You selected About";
		elseif ($page == "News") echo "You selected News";
		elseif ($page == "Login") echo "You selected Login";
		elseif ($page == "Links") echo "You selected Links";
		else echo "Unrecognized selection";

	echo"<br />";
    $page = "something";
	// another option
		switch ($page) {
			case "Home":
				echo "You selected Home";
				break;
			case "About":
				echo "You selected About";
				break;
			case "News":
				echo "You selected News";
				break;
			case "Login":
				echo "You selected Login";
				break;
			case "Links":
				echo "You selected Links";
				break;
			default:
				echo "Unrecognized selection";
				break;
		}
		
	echo"<br />";
	$page = "News";
	// another option match
	
	echo match($page) {
			"Home" => "You selected Home",
			"About" => "You selected About",
			"News" => "You selected News",
			"Login" => "You selected Login",
			"Links" => "You selected Links",
			default => "Unrecognized selection",
		};
?>
