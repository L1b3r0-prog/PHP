<?php
session_start();
require 'includes/functions.php';

if (!isset($_SESSION['nickname'])) {
    header('Location: index.php');
    exit;
}

$nickname    = $_SESSION['nickname'];
$totalPoints = updateLeaderboard($nickname, $_SESSION['game_points']);

$pageTitle = 'Goodbye';
include 'includes/header.php';
?>

<div class="card">
    <h2>Thanks for playing, <?php echo $nickname; ?>! 👋</h2>
    <p>Your overall points (all games): <strong><?php echo $totalPoints; ?></strong></p>

    <div class="menu-options">
        <a class="btn" href="newgame.php">🔁 Start New Game</a>
        <a class="btn" href="leaderboard.php">🏆 View Leaderboard</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
