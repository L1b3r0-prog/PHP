<html>
<head>
<title>Number form</title>
</head>
<body>
<?php
	$DisplayForm = TRUE;
	$Number = "";
	
	if (isset($_POST['Submit'])) {
		//if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$Number = $_POST['Number'];
		if (is_numeric($Number)) {
			$DisplayForm = FALSE;
		} else {
			echo "<p>You need to enter a numeric value.</p>\n";
			$DisplayForm = TRUE;
		}
	}
	if ($DisplayForm) {
		?>
		<form name="NumberForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<p>Enter a number: <input type="text" name="Number" value="<?php echo $Number; ?>" /></p>
		&nbsp;<input type="submit" name="Submit" value="Send Form" /></p>
		</form>
		<?php
	}
	else {
		echo "<p>Thank you for entering a number.</p>\n";
		echo "<p>Your number, $Number, squared is " . ($Number*$Number) . ".</p>\n ";
		echo "<p><a href=\"example2-53.php\">Try again?</a></p>\n";
	}
?>
</body>
</html>

