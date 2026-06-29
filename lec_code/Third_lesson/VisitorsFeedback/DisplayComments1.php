<!DOCTYPE html>
<html>
<head>
<title>Visitors comments</title>
</head>
<body>
<h2>Visitor Feedback</h2>
<hr />
<?php
	$Dir = "comments";
	if (is_dir($Dir)) {
		$commentFiles = scandir($Dir, 1);
		foreach ($commentFiles as $fileName) {
			if (($fileName != ".") && ($fileName !="..")) {
				echo "From <strong>$fileName</strong><br />";
				echo "<pre>\n";
				$fp = fopen($Dir . "/" . $fileName, "rb");
				if ($fp === FALSE)
					echo "There was an error reading file \"" . $fileName . "\".<br />\n";
				else {
					echo "From <strong>$fileName</strong><br />";
					$from = fgets($fp);
					echo "From: " . htmlspecialchars($from) . "<br />\n";
					$email = fgets($fp);
					echo "Email Address: " . htmlspecialchars($email) . "<br />\n";
					$date = fgets($fp);
					echo "Date: " . htmlspecialchars($date) . "<br />\n";
					echo "Comment:<br />\n";
					$comment = "";
					while (!feof($fp)) {
						$comment .= fgets($fp);
					}
					echo htmlspecialchars($comment) . "<br />\n";
					echo "<hr />\n";
					fclose($fp);
				}
				echo "</pre>\n";
			}
		}
	}
?>

</body>
</html>

