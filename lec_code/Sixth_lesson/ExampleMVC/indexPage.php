<?php
require 'guestModel.php';
require 'guestController.php';

$model = new GuestModel();
$controller = new GuestController($model);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $controller->showGuest($_GET['id']);
}  else {
    ?>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET">
	<p><strong>ID: </strong>
	<input type="text" name="id" /></p>
	<p><input type="Submit" name="Submit" value="Submit"/></p>
	</form>
	<?php
}

?>