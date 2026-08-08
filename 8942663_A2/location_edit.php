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
<div class="card" style="max-width:480px;margin:0 auto;">
    <h1>Edit Location #<?= (int)$locationId ?></h1>
    <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= h($error) ?></div><?php endforeach; ?>
    <form method="post" data-validate>
        <input type="hidden" name="location_id" value="<?= (int)$locationId ?>">

        <label>Description</label>
        <input type="text" name="description" data-label="Description" value="<?= h($location['description']) ?>" required>

        <label>Number of Studios</label>
        <input type="number" name="num_studios" data-label="Number of studios" min="1" value="<?= h((string)$location['num_studios']) ?>" required>

        <label>Cost per Hour ($)</label>
        <input type="number" step="0.01" min="0" name="cost_per_hour" data-label="Cost per hour" value="<?= h((string)$location['cost_per_hour']) ?>" required>

        <button class="btn" type="submit">Save Changes</button>
    </form>
    <p><small>Note: reducing studio count only removes studios that have no booking history.</small></p>
</div>

<div class="card" style="max-width:480px;margin:0 auto;">
    <h2>Studios at this location</h2>
    <p><small>Give a studio a custom name (e.g. "Vocal Booth"), or leave blank to show it as "Studio N".</small></p>
    <?php foreach (Studio::forLocation($locationId) as $studio): ?>
        <form method="post" action="studio_rename.php" style="display:flex;gap:8px;align-items:flex-end;margin-top:10px;">
            <input type="hidden" name="studio_id" value="<?= (int)$studio['studio_id'] ?>">
            <input type="hidden" name="location_id" value="<?= (int)$locationId ?>">
            <div style="flex:1;">
                <label style="margin-top:0;">Studio <?= (int)$studio['studio_number'] ?></label>
                <input type="text" name="label" maxlength="50" placeholder="Studio <?= (int)$studio['studio_number'] ?>" value="<?= h($studio['label'] ?? '') ?>">
            </div>
            <button class="btn btn-secondary" type="submit" style="margin-top:0;">Save</button>
        </form>
    <?php endforeach; ?>
</div>
<script src="assets/form-validation.js"></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
