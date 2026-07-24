<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('admin');
$pageTitle = 'Edit Location';
$errors = [];

$locationId = (int)($_GET['id'] ?? $_POST['location_id'] ?? 0);
$location = Location::findById($locationId);
if (!$location) {
    $_SESSION['flash_error'] = 'Location not found.';
    header('Location: location_list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');
    $numStudios = $_POST['num_studios'] ?? '';
    $costPerHour = $_POST['cost_per_hour'] ?? '';

    try {
        Location::update($locationId, $description, (int)$numStudios, (float)$costPerHour);
        $_SESSION['flash_success'] = 'Location updated successfully.';
        header('Location: location_list.php');
        exit;
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        $location = ['location_id' => $locationId, 'description' => $description, 'num_studios' => $numStudios, 'cost_per_hour' => $costPerHour];
    }
}

require __DIR__ . '/includes/header.php';
?>
<div class="card" style="max-width:480px;">
    <h1>Edit Location #<?= (int)$locationId ?></h1>
    <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= h($error) ?></div><?php endforeach; ?>
    <form method="post">
        <input type="hidden" name="location_id" value="<?= (int)$locationId ?>">

        <label>Description</label>
        <input type="text" name="description" value="<?= h($location['description']) ?>" required>

        <label>Number of Studios</label>
        <input type="number" name="num_studios" min="1" value="<?= h((string)$location['num_studios']) ?>" required>

        <label>Cost per Hour ($)</label>
        <input type="number" step="0.01" min="0" name="cost_per_hour" value="<?= h((string)$location['cost_per_hour']) ?>" required>

        <button class="btn" type="submit">Save Changes</button>
    </form>
    <p><small>Note: reducing studio count only removes studios that have no booking history.</small></p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
