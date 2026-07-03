<?php
session_start();
require 'includes/functions.php';

if (!isset($_SESSION['nickname'])) {
    header('Location: index.php');
    exit;
}

$nickname = $_SESSION['nickname'];

// Points are now saved to the leaderboard immediately after every quiz
// (see quiz.php), so Exit just needs to look up the player's current
// all-time total rather than adding anything itself.
$board       = loadLeaderboard();
$totalPoints = isset($board[$nickname]) ? $board[$nickname] : 0;

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
