<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
$pageTitle = 'Locations';

$view = $_GET['view'] ?? 'all';
switch ($view) {
    case 'available':
        $locations = Location::withAvailableStudios();
        $heading = 'Locations with Available Studios';
        break;
    case 'fully_booked':
        // fully-booked list only makes sense for Administrator
        if (current_user_type() !== 'admin') { header('Location: location_list.php'); exit; }
        $locations = Location::fullyBooked();
        $heading = 'Fully Booked Locations';
        break;
    default:
        $locations = Location::all();
        $heading = 'All Locations';
}

require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <h1><?= h($heading) ?></h1>
    <p>
        <a class="btn btn-secondary" href="location_list.php?view=all">All</a>
        <a class="btn btn-secondary" href="location_list.php?view=available">Available Studios</a>
        <?php if (current_user_type() === 'admin'): ?>
            <a class="btn btn-secondary" href="location_list.php?view=fully_booked">Fully Booked</a>
        <?php endif; ?>
    </p>

    <?php if (empty($locations)): ?>
        <p>No locations found.</p>
    <?php else: ?>
    <table>
        <tr><th>ID</th><th>Description</th><th>Studios</th><th>Cost / hour</th><?php if (current_user_type() === 'admin'): ?><th>Action</th><?php endif; ?></tr>
        <?php foreach ($locations as $loc): ?>
        <tr>
            <td><?= h((string)$loc['location_id']) ?></td>
            <td><?= h($loc['description']) ?></td>
            <td><?= h(implode(', ', Location::studioNames((int)$loc['location_id']))) ?></td>
            <td>$<?= h(number_format((float)$loc['cost_per_hour'], 2)) ?></td>
            <?php if (current_user_type() === 'admin'): ?>
            <td><a href="location_edit.php?id=<?= (int)$loc['location_id'] ?>">Edit</a></td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
