<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('admin');
$pageTitle = 'Add Location';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');
    $numStudios = $_POST['num_studios'] ?? '';
    $costPerHour = $_POST['cost_per_hour'] ?? '';

    try {
        Location::create($description, (int)$numStudios, (float)$costPerHour);
        $_SESSION['flash_success'] = 'Location created successfully.';
        header('Location: location_list.php');
        exit;
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}

require __DIR__ . '/includes/header.php';
?>
<div class="card" style="max-width:480px;margin:0 auto;">
    <h1>Add Location</h1>
    <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= h($error) ?></div><?php endforeach; ?>
    <form method="post" data-validate>
        <label>Description</label>
        <input type="text" name="description" data-label="Description" value="<?= h($_POST['description'] ?? '') ?>" required>

        <label>Number of Studios</label>
        <input type="number" name="num_studios" data-label="Number of studios" min="1" value="<?= h($_POST['num_studios'] ?? '1') ?>" required>

        <label>Cost per Hour ($)</label>
        <input type="number" step="0.01" min="0" name="cost_per_hour" data-label="Cost per hour" value="<?= h($_POST['cost_per_hour'] ?? '') ?>" required>

        <button class="btn" type="submit">Create Location</button>
    </form>
</div>
<script src="assets/form-validation.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
