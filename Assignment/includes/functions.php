<?php
// File paths (relative to this file's location, so they work no matter which page includes them)
define('MATH_FILE', __DIR__ . '/../data/math_questions.txt');
define('SEA_FILE', __DIR__ . '/../data/sea_questions.txt');
define('LEADERBOARD_FILE', __DIR__ . '/../data/leaderboard.txt');

/**
 * Load all Math questions from the text file.
 * Each line format: operand1|operator|operand2|correctAnswer
 */
function loadMathQuestions() {
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

/**
 * Load all Sea World questions from the text file.
 * Each line format: imageFile|labelShownToUser|isLabelCorrect(1 or 0)
 */
function loadSeaQuestions() {
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

/**
 * Randomly pick $count questions from a question bank.
 */
function getRandomQuestions($questions, $count = 3) {
    if (count($questions) <= $count) {
        shuffle($questions);
        return $questions;
    }
    shuffle($questions);
    return array_slice($questions, 0, $count);
}

/**
 * Load the leaderboard as an associative array: nickname => totalPoints
 */
function loadLeaderboard() {
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

/**
 * Overwrite the leaderboard file with the given associative array.
 */
function saveLeaderboard($board) {
    $lines = [];
    foreach ($board as $nickname => $points) {
        $lines[] = $nickname . '|' . $points;
    }
    file_put_contents(LEADERBOARD_FILE, implode(PHP_EOL, $lines) . PHP_EOL);
}

/**
 * Add points to a nickname's cumulative leaderboard score (or create a new entry).
 * Returns the player's new total.
 */
function updateLeaderboard($nickname, $pointsToAdd) {
    $board = loadLeaderboard();
    if (isset($board[$nickname])) {
        $board[$nickname] += $pointsToAdd;
    } else {
        $board[$nickname] = $pointsToAdd;
    }
    saveLeaderboard($board);
    return $board[$nickname];
}

/**
 * Sort the leaderboard either alphabetically by nickname ('name')
 * or by score descending ('score').
 */
function sortLeaderboard($board, $by = 'name') {
    $sorted = $board;
    if ($by === 'score') {
        arsort($sorted); // sort by value, descending, keep keys
    } else {
        ksort($sorted, SORT_STRING | SORT_FLAG_CASE); // sort by key, alphabetical
    }
    return $sorted;
}
