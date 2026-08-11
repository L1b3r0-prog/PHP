<!DOCTYPE html>
<html>
<head>
    <title>Guest Details</title>
</head>
<body>
    <h1>Guest Details</h1>
    <p>Name: <?php echo (htmlspecialchars($guest['firstname']) . " " . htmlspecialchars($guest['lastname'])); ?></p>
    <p>Email: <?php echo(htmlspecialchars($guest['email'])); ?></p>
</body>
</html>