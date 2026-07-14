<?php
// File paths wihch are relative to this file's location
// so they work no matter which page includes them
define('MATH_FILE', __DIR__ . '/../data/math_questions.txt');
define('SEA_FILE', __DIR__ . '/../data/sea_questions.txt');
define('LEADERBOARD_FILE', __DIR__ . '/../data/leaderboard.txt');

// this loads the questions from the text file itself
function loadMathQuestions(): array {
    $questions = [];
    if (!file_exists(MATH_FILE)) {
        return $questions;
    }
    $lines = file(MATH_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if (count($parts) === 4) {
            $questions[] = [
                'op1'      => (int)$parts[0],
                'operator' => trim($parts[1]),
                'op2'      => (int)$parts[2],
                'answer'   => (int)$parts[3],
            ];
        }
    }
    return $questions;
}

// this loads the questions from the text file itself
function loadSeaQuestions(): array {
    $questions = [];
    if (!file_exists(SEA_FILE)) {
        return $questions;
    }
    $lines = file(SEA_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if (count($parts) === 3) {
            $questions[] = [
                'image'   => trim($parts[0]),
                'label'   => trim($parts[1]),
                'correct' => (int)$parts[2],
            ];
        }
    }
    return $questions;
}

// this randomly loads 3 questions
function getRandomQuestions(array $questions, int $count = 3): array {
    if (count($questions) <= $count) {
        shuffle($questions);
        return $questions;
    }
    shuffle($questions);
    return array_slice($questions, 0, $count);
}

// this loads the leaderboard that will show the nickname
// and the total points that they have earned/lost
function loadLeaderboard(): array {
    $board = [];
    if (file_exists(LEADERBOARD_FILE)) {
        $lines = file(LEADERBOARD_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $parts = explode('|', $line);
            if (count($parts) === 2) {
                $board[$parts[0]] = (int)$parts[1];
            }
        }
    }
    return $board;
}

// this overwrites the leaderboard
function saveLeaderboard(array $board): void {
    $lines = [];
    foreach ($board as $nickname => $points) {
        $lines[] = $nickname . '|' . $points;
    }
    file_put_contents(LEADERBOARD_FILE, implode(PHP_EOL, $lines) . PHP_EOL);
}

// this adds the points to the nickname and if it doesn't exist it will create a new entry
// it would then return the player's new total
function updateLeaderboard(string $nickname, int $pointsToAdd): int {
    $board = loadLeaderboard();
    if (isset($board[$nickname])) {
        $board[$nickname] += $pointsToAdd;
    } else {
        $board[$nickname] = $pointsToAdd;
    }
    saveLeaderboard($board);
    return $board[$nickname];
}

// this function helps to either sort by the nickname or
// by the score in a descending manner
function sortLeaderboard(array $board, string $by = 'name'): array {
    $sorted = $board;
    if ($by === 'score') {
        arsort($sorted); // sort by descending value
    } else {
        ksort($sorted, SORT_STRING | SORT_FLAG_CASE); // sorts by alphabetical order
    }
    return $sorted;
}
