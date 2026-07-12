<?php
session_start();
require 'includes/functions.php';

$nickname = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';

// Must be non-empty AND letters/spaces only (no digits, no symbols)
if ($nickname === '' || !preg_match('/^[A-Za-z ]+$/', $nickname)) {
    header('Location: index.php?error=1');
    exit;
}

$_SESSION['nickname']    = htmlspecialchars($nickname);
$_SESSION['game_points'] = 0;

// Add them to the leaderboard immediately at 0 points, so they show up
// even if they never finish a quiz and just leave the browser tab.
// Adding 0 to an existing player leaves their current score untouched.
updateLeaderboard($_SESSION['nickname'], 0);

header('Location: menu.php');
exit;
