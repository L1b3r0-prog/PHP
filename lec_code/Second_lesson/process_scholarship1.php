<html>
<head>
<title>Scholarships</title>
</head>
<body>
<?php
    function displayRequired(string $fieldName): void {
        echo "The field \"$fieldName\" is required.<br>";
    }
    function validateInput(string $data, string $fieldName): string {
        global $errorCount;
		$retval = "";
        if (empty($data)) { //if it is empty
            displayRequired($fieldName);
            ++$errorCount;
        } else { // Only clean up the input if it isn't empty
			$retval = trim($data);
			if ($fieldName == "email") { 
				if (!filter_var(trim($data), FILTER_VALIDATE_EMAIL)) { 
					++$errorCount;
					echo "Invalid email format.<br>"; 
					$retval = "";
				}
			}
        }
			
        return($retval);
    }
	
    $errorCount = 0;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") { //ensure that the data is sent via POST
		$name = validateInput($_POST['name'], "name");
		$email = validateInput($_POST['email'], "email");

		if ($errorCount>0)
			echo "Please use the \"Back\" button to re-enter the data.<br />\n";
		else
			echo "Thank you for filling out the scholarship form, " . $name . ", " . $email . ".";
	}
?>
</body>
</html>

