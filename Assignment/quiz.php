<?php
session_start();
require 'includes/functions.php';

if (!isset($_SESSION['nickname'])) {
    header('Location: index.php');
    exit;
}

$topic = isset($_GET['topic']) ? $_GET['topic'] : (isset($_POST['topic']) ? $_POST['topic'] : '');

if (!in_array($topic, ['math', 'sea'])) {
    header('Location: menu.php');
    exit;
}

// ---- Handle quiz submission ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quiz'])) {

    $questions = isset($_SESSION['current_quiz']) ? $_SESSION['current_quiz'] : [];
    $correct   = 0;
    $incorrect = 0;

    foreach ($questions as $i => $q) {
        if ($topic === 'math') {
            $userAnswer = isset($_POST['answer' . $i]) ? trim($_POST['answer' . $i]) : '';
            // Empty or non-numeric input counts as "doesn't know" -> incorrect
            if ($userAnswer !== '' && is_numeric($userAnswer) && (int)$userAnswer === $q['answer']) {
                $correct++;
            } else {
                $incorrect++;
            }
        } else { // sea
            $userAnswer = isset($_POST['answer' . $i]) ? $_POST['answer' . $i] : '';
            if ($userAnswer === '') {
                $incorrect++; // no selection = counted as incorrect
                continue;
            }
            $userSaysCorrect = ($userAnswer === 'correct') ? 1 : 0;
            if ($userSaysCorrect === $q['correct']) {
                $correct++;
            } else {
                $incorrect++;
            }
        }
    }

    $points = ($correct * 3) - ($incorrect * 2);
    $_SESSION['game_points'] += $points;

    $_SESSION['last_result'] = [
        'topic'     => $topic,
        'correct'   => $correct,
        'incorrect' => $incorrect,
        'points'    => $points,
    ];

    unset($_SESSION['current_quiz']);

    header('Location: result.php');
    exit;
}

// ---- Generate a fresh quiz (GET request) ----
$allQuestions   = ($topic === 'math') ? loadMathQuestions() : loadSeaQuestions();
$quizQuestions  = getRandomQuestions($allQuestions, 3);
$_SESSION['current_quiz'] = $quizQuestions;

$pageTitle = ($topic === 'math') ? 'Math Quiz' : 'Sea World Quiz';
include 'includes/header.php';
?>

<div class="card">
    <h2><?php echo $topic === 'math' ? '➕ Math Quiz' : '🐬 Sea World Quiz'; ?></h2>

    <form action="quiz.php" method="post">
        <input type="hidden" name="topic" value="<?php echo htmlspecialchars($topic); ?>">

        <?php foreach ($quizQuestions as $i => $q): ?>
            <div class="question">
                <?php if ($topic === 'math'): ?>
                    <p>Q<?php echo $i + 1; ?>: What is <?php echo $q['op1']; ?> <?php echo htmlspecialchars($q['operator']); ?> <?php echo $q['op2']; ?>?</p>
                    <input type="text" name="answer<?php echo $i; ?>" placeholder="Type your answer">
                <?php else: ?>
                    <p>Q<?php echo $i + 1; ?>: Is this animal's name correct?</p>
                    <img src="images/<?php echo htmlspecialchars($q['image']); ?>" alt="Sea animal" class="sea-img">
                    <p class="animal-label"><?php echo htmlspecialchars($q['label']); ?></p>
                    <label><input type="radio" name="answer<?php echo $i; ?>" value="correct"> Correct</label>
                    <label><input type="radio" name="answer<?php echo $i; ?>" value="incorrect"> Incorrect</label>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" name="submit_quiz" value="1">Submit Quiz ✅</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
