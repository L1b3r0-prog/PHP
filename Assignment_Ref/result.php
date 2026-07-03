<?php
session_start();

if (!isset($_SESSION['nickname']) || !isset($_SESSION['last_result'])) {
    header('Location: menu.php');
    exit;
}

$result = $_SESSION['last_result'];
$pageTitle = 'Quiz Result';
include 'includes/header.php';
?>

<div class="card">
    <h2>Quiz Result 📋</h2>
    <p>Topic: <strong><?php echo ucfirst(htmlspecialchars($result['topic'])); ?></strong></p>
    <p>Correct answers: <strong><?php echo $result['correct']; ?></strong></p>
    <p>Incorrect answers: <strong><?php echo $result['incorrect']; ?></strong></p>
    <p>Points earned this quiz: <strong><?php echo $result['points']; ?></strong></p>
    <hr>
    <p>Your overall points this game: <strong><?php echo $_SESSION['game_points']; ?></strong></p>

    <div class="menu-options">
        <a class="btn" href="menu.php">🔁 New Quiz</a>
        <a class="btn" href="leaderboard.php">🏆 Leaderboard</a>
        <a class="btn btn-exit" href="exit.php">🚪 Exit</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
