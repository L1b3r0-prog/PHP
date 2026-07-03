------------------------------------------------------
Name(s):
Student number(s):
Email address(es):
Assignment number: ISIT307 Assignment 1
-------------------------------------------------------

# Learning Hub — Report

## Requirements / Remarks / Readme

- The website is built in plain PHP (no framework) using PHP sessions to track
  the current game state and text files (in /data) to persist quiz questions
  and the leaderboard.
- To run: place the `learning-hub` folder in your PHP server's document root
  (e.g. htdocs for XAMPP) and open index.php in a browser.
- Requires PHP 7+ with sessions enabled (default).
- [Add any other notes, known limitations, or special instructions here.]

## File List and Description

| File | Description |
|---|---|
| index.php | Landing page — nickname entry form |
| start.php | Validates nickname, initializes session, redirects to menu |
| menu.php | Main menu — choose quiz topic, leaderboard, or exit |
| quiz.php | Generates 3 random questions for chosen topic, displays quiz, scores submission |
| result.php | Displays quiz result and running game total |
| leaderboard.php | Displays all players sorted by nickname or score |
| exit.php | Saves cumulative score to leaderboard, shows final total |
| newgame.php | Clears session, returns to nickname entry |
| includes/functions.php | Helper functions: reading/writing text files, random question selection, leaderboard sorting |
| includes/header.php | Shared HTML head + page header |
| includes/footer.php | Shared HTML footer |
| css/style.css | Site styling |
| data/math_questions.txt | Math question bank (operand1\|operator\|operand2\|answer) |
| data/sea_questions.txt | Sea World question bank (image\|label\|isLabelCorrect) |
| data/leaderboard.txt | Cumulative player scores (nickname\|totalPoints) |
| images/*.svg | Sea animal images used in Sea World quiz |

## User Manual (Screenshots)

[Insert a screenshot of each interface below with a short caption, e.g.:]

1. **Nickname entry (index.php)** — [screenshot]
2. **Main menu (menu.php)** — [screenshot]
3. **Math quiz (quiz.php?topic=math)** — [screenshot]
4. **Sea World quiz (quiz.php?topic=sea)** — [screenshot]
5. **Quiz result (result.php)** — [screenshot]
6. **Leaderboard sorted by nickname (leaderboard.php?sort=name)** — [screenshot]
7. **Leaderboard sorted by score (leaderboard.php?sort=score)** — [screenshot]
8. **Exit / final score (exit.php)** — [screenshot]

