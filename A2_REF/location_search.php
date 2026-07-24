<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Search Locations';

$locationId = trim($_GET['location_id'] ?? '');
$description = trim($_GET['description'] ?? '');
$searched = isset($_GET['search']);
$results = $searched ? Location::search($locationId, $description) : [];

require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <h1>Search Locations</h1>
    <p>Search by Location ID and/or Description. Partial matches allowed. Leave a field blank to ignore it.</p>
    <form method="get" class="search-form">
        <div>
            <label>Location ID</label>
            <input type="text" name="location_id" value="<?= h($locationId) ?>">
        </div>
        <div>
            <label>Description</label>
            <input type="text" name="description" value="<?= h($description) ?>">
        </div>
        <div>
            <button class="btn" type="submit" name="search" value="1">Search</button>
        </div>
    </form>
</div>

<?php if ($searched): ?>
<div class="card">
    <h2>Results (<?= count($results) ?>)</h2>
    <?php if (empty($results)): ?>
        <p>No locations matched your search.</p>
    <?php else: ?>
    <table>
        <tr><th>ID</th><th>Description</th><th>Studios</th><th>Cost / hour</th></tr>
        <?php foreach ($results as $loc): ?>
        <tr>
            <td><?= h((string)$loc['location_id']) ?></td>
            <td><?= h($loc['description']) ?></td>
            <td><?= h((string)$loc['num_studios']) ?></td>
            <td>$<?= h(number_format((float)$loc['cost_per_hour'], 2)) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
