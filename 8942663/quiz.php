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

$quizQuestions = null;

// this part handles the submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quiz'])) {

    $questions = isset($_SESSION['current_quiz']) ? $_SESSION['current_quiz'] : [];

    $correct = 0;
    $incorrect = 0;

    foreach ($questions as $i => $q) {
        $userAnswer = isset($_POST['answer' . $i]) ? trim($_POST['answer' . $i]) : '';

        // when blank answer is submitted, it will be considered as incorrect
        if ($userAnswer === '') {
            $incorrect++;
            continue;
        }

        if ($topic === 'math') {
            if (is_numeric($userAnswer) && (int)$userAnswer === $q['answer']) {
                $correct++;
            } else {
                $incorrect++;
            }
        } else { // sea
            $userSaysCorrect = ($userAnswer === 'correct') ? 1 : 0;
            if ($userSaysCorrect === $q['correct']) {
                $correct++;
            } else {
                $incorrect++;
            }
        }
    }

    $pointsEarned = $correct * 3;
    $pointsLost   = $incorrect * 2;
    $points       = $pointsEarned - $pointsLost;
    $_SESSION['game_points'] += $points;

    // this will save to the leaderboard immediately
    updateLeaderboard($_SESSION['nickname'], $points);

    $_SESSION['last_result'] = [
        'topic'         => $topic,
        'correct'       => $correct,
        'incorrect'     => $incorrect,
        'pointsEarned'  => $pointsEarned,
        'pointsLost'    => $pointsLost,
        'points'        => $points,
    ];

    unset($_SESSION['current_quiz']);

    header('Location: result.php');
    exit;
}

// this is to generate a new random set of questions
if ($quizQuestions === null) {
    $allQuestions  = ($topic === 'math') ? loadMathQuestions() : loadSeaQuestions();
    $quizQuestions = getRandomQuestions($allQuestions, 3);
    $_SESSION['current_quiz'] = $quizQuestions;
}

$pageTitle = ($topic === 'math') ? 'Math Quiz' : 'Sea World Quiz';
include 'includes/header.php';
?>

<div class="card">
    <h2>
        <?php echo $topic === 'math' ? '➕ Math Quiz' : '🐬 Sea World Quiz'; ?>
    </h2>

    <form action="quiz.php" method="post" id="quizForm">
        <input type="hidden" name="topic" value="<?php echo htmlspecialchars($topic); ?>">

        <?php foreach ($quizQuestions as $i => $q): ?>
            <div class="question">
                <?php if ($topic === 'math'): ?>
                    <p>
                        Q<?php echo $i + 1; ?>: What is <?php echo $q['op1']; ?> 
                        <?php echo htmlspecialchars($q['operator']); ?> 
                        <?php echo $q['op2']; ?>?
                    </p>
                    <input type="number" name="answer<?php echo $i; ?>" placeholder="Type your answer">
                <?php else: ?>
                    <p>
                        Q<?php echo $i + 1; ?>: Is this animal's name correct?
                    </p>
                    <img src="images/<?php echo htmlspecialchars($q['image']); ?>" alt="Sea animal" class="sea-img">
                    <p class="animal-label">
                        <?php echo htmlspecialchars($q['label']); ?>
                    </p>
                    <label>
                        <input type="radio" name="answer<?php echo $i; ?>" value="correct">
                            Correct
                        </label>
                    <label>
                        <input type="radio" name="answer<?php echo $i; ?>" value="incorrect">
                            Incorrect
                        </label>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" name="submit_quiz" value="1">
            Submit Quiz ✅
        </button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
