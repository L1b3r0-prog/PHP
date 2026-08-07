<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_role('admin');
$pageTitle = 'Clients';

$view = $_GET['view'] ?? 'all';
if ($view === 'active') {
    $clients = User::activeClients();
    $heading = 'Clients Currently Using a Studio';
} else {
    $clients = User::allClients();
    $heading = 'All Registered Clients';
}

require __DIR__ . '/includes/header.php';
?>
<div class="card">
    <h1><?= h($heading) ?></h1>
    <p>
        <a class="btn btn-secondary" href="admin_client_list.php?view=all">All Clients</a>
        <a class="btn btn-secondary" href="admin_client_list.php?view=active">Currently Active</a>
    </p>
    <?php if (empty($clients)): ?>
        <p>No clients found.</p>
    <?php else: ?>
    <table>
        <tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th></tr>
        <?php foreach ($clients as $c): ?>
        <tr>
            <td><?= (int)$c['user_id'] ?></td>
            <td><?= h($c['name']) ?></td>
            <td><?= h($c['phone']) ?></td>
            <td><?= h($c['email']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
