<!DOCTYPE html>
<html>
<head>
<title>Visitor comments</title>
</head>
<body>
<?php 
$Dir = "comments";
if (is_dir($Dir)) {
	if (isset($_POST['save'])) {
		if (empty($_POST['name']))
			$saveString = "Unknown Visitor\n";
		else
			$saveString = stripslashes($_POST['name']) . "\n";
        
		$saveString .= stripslashes($_POST['email']) . "\n";
		$saveString .= date('r') . "\n";
		$saveString .= stripslashes($_POST['comment']);
		$currentTime = microtime();
		$timeArray = explode(" ", $currentTime);
		$timeStamp = (float)$timeArray[1] + (float)$timeArray[0]; 
		// File name is "comment.second.microseconds.txt"  
		$saveFileName = "$Dir/Comment.$timeStamp.txt";
		$fp = fopen($saveFileName,"wb");
		if ($fp === FALSE) {
			echo "There was an error creating \"" . htmlspecialchars($saveFileName) . "\".<br />";
		}
		else {
			if (fwrite($fp, $saveString)>0)
				echo "Successfully wrote to file \"" . htmlspecialchars($saveFileName) . "\".<br />";
			else
				echo "There was an error writing to file \"" . htmlspecialchars($saveFileName) .	"\".<br />";
			fclose($fp);
		}
	}
}
?>
<h2>Visitor Comments</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	Your name: <input type="text" name="name" /><br />
	Your email: <input type="text" name="email" /><br />
<textarea name="comment" rows="6" cols="100"></textarea><br />
<input type="submit" name="save" value="Submit your comment" /><br />
</form>
</body>
</html>

