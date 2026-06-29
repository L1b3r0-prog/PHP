<!DOCTYPE html>
<html>
<head>
<title>Visitors' comments</title>
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
				$Comment = file_get_contents($Dir . "/" .$fileName);
				echo $Comment;
				//readfile($Dir . "/" .$fileName);
				echo "</pre>\n";
				echo "<hr />\n";
			}
		}
	}
?>

</body>
</html>

