<?php
session_start();
require 'includes/functions.php';

$sortBy = (isset($_GET['sort']) && $_GET['sort'] === 'score') ? 'score' : 'name';
$board  = sortLeaderboard(loadLeaderboard(), $sortBy);

$pageTitle = 'Leaderboard';
include 'includes/header.php';
?>

<div class="card">
    <h2>🏆 Leaderboard</h2>

    <div class="sort-links">
        Sort by:
        <a href="leaderboard.php?sort=name" class="<?php echo $sortBy === 'name' ? 'active' : ''; ?>">Nickname</a> |
        <a href="leaderboard.php?sort=score" class="<?php echo $sortBy === 'score' ? 'active' : ''; ?>">Score</a>
    </div>

    <?php if (empty($board)): ?>
        <p>No players yet. Be the first!</p>
    <?php else: ?>
        <table>
            <tr><th>Nickname</th><th>Total Points</th></tr>
            <?php foreach ($board as $name => $points): ?>
                <tr>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo $points; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <a class="btn" href="<?php echo isset($_SESSION['nickname']) ? 'menu.php' : 'index.php'; ?>">⬅ Back</a>
</div>

<?php include 'includes/footer.php'; ?>
