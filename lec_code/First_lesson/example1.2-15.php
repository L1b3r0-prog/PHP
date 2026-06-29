<?php	
	function makeCoffee($type = "cappuccino")
	{
		return "<p> Making a cup of $type </p>";
	};

	echo makeCoffee();
    echo makeCoffee(null);
	echo makeCoffee("espresso");
?>

