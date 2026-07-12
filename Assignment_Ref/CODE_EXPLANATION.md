# Learning Hub — Code Explanation

This document walks through every file: what it does, the key logic inside it,
and the reasoning behind design decisions — so you can explain any part of it
confidently if your tutor asks.

---

## 1. `index.php` — Nickname Entry

**Purpose:** The landing page. Shows a form asking for a nickname.

**Key code:**
```php
<?php session_start(); ?>
```
`session_start()` must run before *any* HTML output on every page that uses
`$_SESSION`. It either creates a new session (first visit) or resumes an
existing one, based on a session ID cookie PHP sets in the browser.

```html
<input type="text" id="nickname" name="nickname" maxlength="20"
       pattern="[A-Za-z ]+" title="Letters only, no numbers or symbols" required>
```
- `pattern="[A-Za-z ]+"` is a regex the *browser* checks before allowing
  submission — letters and spaces only. This is a **usability** feature, not
  a security one: it gives instant feedback without a server round-trip.
- `required` stops empty submissions client-side.

**Why this isn't "real" validation on its own:** anyone can disable
JavaScript, edit the HTML in dev tools, or send a POST request directly with
a tool like curl or Postman, bypassing `pattern` and `required` entirely.
That's why the actual enforcement lives in `start.php` (below).

**If tutor asks "what happens if I turn off JavaScript and submit garbage?"**
→ It still gets blocked, because `start.php` checks it again server-side.

---

## 2. `start.php` — Validate Nickname & Create Session

**Purpose:** Receives the POSTed nickname, validates it properly, and sets
up the session that represents "this player is now in a game."

```php
$nickname = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';

if ($nickname === '' || !preg_match('/^[A-Za-z ]+$/', $nickname)) {
    header('Location: index.php?error=1');
    exit;
}
```
- `trim()` removes leading/trailing whitespace (so `"  Alex  "` becomes `"Alex"`).
- `preg_match('/^[A-Za-z ]+$/', $nickname)` — this is the **real** check.
  - `^` and `$` anchor the pattern to the *whole* string (not just part of it).
  - `[A-Za-z ]+` means "one or more characters, each of which is an
    uppercase letter, lowercase letter, or space." Nothing else is allowed —
    no digits, no punctuation, no emoji.
  - If it doesn't match, `header('Location: ...')` redirects the browser
    back to `index.php` with `?error=1` in the URL, which `index.php` reads
    to show an error message.
- `exit;` after every `header('Location: ...')` is important — without it,
  PHP would keep executing the rest of the script even though it already
  told the browser to redirect, potentially running code (like setting
  session variables) that shouldn't happen for an invalid nickname.

```php
$_SESSION['nickname']    = htmlspecialchars($nickname);
$_SESSION['game_points'] = 0;
updateLeaderboard($_SESSION['nickname'], 0);
```
- `htmlspecialchars()` converts characters like `<`, `>`, `&` into safe HTML
  entities before storing. Even though the regex above already blocks
  symbols, this is a second layer of protection in case the validation
  logic ever changes — good practice for anything that gets echoed into
  HTML later (defends against XSS/script injection).
- `game_points` starts at 0 — this is the running total *for this game
  session only*, separate from the player's all-time leaderboard total.
- `updateLeaderboard($nickname, 0)` registers the player on the leaderboard
  immediately, even before they've played a single quiz. Adding 0 is a
  deliberate trick: if the nickname already exists, `+= 0` leaves their
  score untouched; if it's new, it creates the entry at 0.

**If tutor asks "why validate twice, once in HTML and once in PHP?"**
→ HTML/JS validation is for user experience (fast, friendly feedback).
PHP validation is for correctness and security, because it can't be
bypassed — it's the only check that's actually trustworthy.

---

## 3. `menu.php` — Main Menu

**Purpose:** Shows the player's nickname, current game points, and links to
the two quiz topics, leaderboard, and exit.

```php
if (!isset($_SESSION['nickname'])) {
    header('Location: index.php');
    exit;
}
```
This "guard clause" appears at the top of almost every page. It checks
whether a valid session exists; if someone tries to jump straight to
`menu.php` (or any other page) without going through `start.php` first —
e.g. by typing the URL directly — they get sent back to the nickname
screen instead of seeing a broken page.

The links to quizzes are plain `<a href="quiz.php?topic=math">` — this
sends a **GET** request with `topic` as a URL query parameter, which
`quiz.php` reads via `$_GET['topic']`.

---

## 4. `quiz.php` — Generate & Grade the Quiz (the most complex file)

This file does two completely different jobs depending on how it's reached:
**showing** a new quiz (GET request) or **grading** a submitted one (POST
request). It tells them apart using `$_SERVER['REQUEST_METHOD']`.

### Job A — Showing a new quiz (GET)
```php
$allQuestions  = ($topic === 'math') ? loadMathQuestions() : loadSeaQuestions();
$quizQuestions = getRandomQuestions($allQuestions, 3);
$_SESSION['current_quiz'] = $quizQuestions;
```
- Loads the *entire* question bank for the chosen topic (6–8 questions).
- `getRandomQuestions()` shuffles that list and takes the first 3
  (`array_slice($questions, 0, 3)`).
- **Crucially**, those 3 chosen questions are saved into
  `$_SESSION['current_quiz']`. This is how the app "remembers" which
  questions were shown, so that when the form is submitted, it can check
  the answers against the *same* questions — not regenerate new random ones
  (which would make grading meaningless).

### Job B — Grading a submission (POST)
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quiz'])) {
    $questions = $_SESSION['current_quiz'];
    // ... validate all answered, then ...
    foreach ($questions as $i => $q) {
        if ($topic === 'math') {
            $userAnswer = trim($_POST['answer' . $i]);
            if (is_numeric($userAnswer) && (int)$userAnswer === $q['answer']) {
                $correct++;
            } else {
                $incorrect++;
            }
        } else {
            $userAnswer = $_POST['answer' . $i];
            $userSaysCorrect = ($userAnswer === 'correct') ? 1 : 0;
            if ($userSaysCorrect === $q['correct']) {
                $correct++;
            } else {
                $incorrect++;
            }
        }
    }
    $points = ($correct * 3) - ($incorrect * 2);
```
- `answer0`, `answer1`, `answer2` are the form field names for the 3
  questions — the loop uses `$i` (the array index) to build the field name
  dynamically and match it back to the right question.
- Math: `is_numeric()` first checks the input is actually a number before
  casting it with `(int)` — this stops something like `"abc"` from silently
  becoming `0` and being wrongly compared.
- Sea World: the radio button's `value="correct"` or `value="incorrect"`
  gets converted to `1`/`0` and compared against the question's stored
  `correct` flag (also `1`/`0`).
- The scoring formula `(correct * 3) - (incorrect * 2)` is taken directly
  from the assignment spec.

### Validation before grading
```php
$allAnswered = true;
foreach ($questions as $i => $q) {
    $userAnswer = isset($_POST['answer' . $i]) ? trim($_POST['answer' . $i]) : '';
    if ($userAnswer === '') {
        $allAnswered = false;
        break;
    }
}

if (!$allAnswered) {
    $error = 'Please answer all 3 questions before submitting.';
    $quizQuestions = $questions; // re-show the SAME questions, not new random ones
}
```
This runs *before* scoring. If anything's missing, it doesn't grade at all —
it just re-displays the identical 3 questions with an error message. This
matters because the HTML5 `required` attributes and the JavaScript check
(further down the file) can both be bypassed, so this is the check that
actually can't be skipped.

### Saving to the leaderboard immediately
```php
$points = ($correct * 3) - ($incorrect * 2);
$_SESSION['game_points'] += $points;
updateLeaderboard($_SESSION['nickname'], $points);
```
This line is why the leaderboard updates right after each quiz instead of
only at Exit — `updateLeaderboard()` is called here, per-quiz, adding just
this quiz's points to the player's cumulative total on disk.

**If tutor asks "why is showing and grading the quiz in the same file?"**
→ It keeps everything about "the quiz" logically together (question
selection, display, and scoring all reference the same session data), and
avoids passing the 3 questions between separate files. It's a common
pattern: one script handling both GET (show form) and POST (process form).

---

## 5. `result.php` — Show Quiz Outcome

**Purpose:** Reads `$_SESSION['last_result']` (set at the end of the POST
branch in `quiz.php`) and displays correct/incorrect counts, points earned,
and the running game total.

```php
if (!isset($_SESSION['nickname']) || !isset($_SESSION['last_result'])) {
    header('Location: menu.php');
    exit;
}
```
Guards against someone navigating here directly without having just
finished a quiz (no `last_result` would exist yet).

```php
<p>Topic: <strong><?php echo $result['topic'] === 'math' ? 'Math' : 'Sea World'; ?></strong></p>
```
Explicit ternary rather than `ucfirst()` — `ucfirst('sea')` would only
produce "Sea", not "Sea World", since it just capitalizes the first letter
of whatever string it's given rather than expanding it. This was a small
bug fixed during development — a good example if your tutor asks about
debugging/testing.

---

## 6. `leaderboard.php` — Sortable Leaderboard

```php
$sortBy = (isset($_GET['sort']) && $_GET['sort'] === 'score') ? 'score' : 'name';
$board  = sortLeaderboard(loadLeaderboard(), $sortBy);
```
- Reads the `?sort=` URL parameter to decide sort order, defaulting to name
  if it's missing or invalid.
- `loadLeaderboard()` reads the whole file fresh every time this page
  loads — no caching, so it's always current.

In `functions.php`:
```php
function sortLeaderboard($board, $by = 'name') {
    $sorted = $board;
    if ($by === 'score') {
        arsort($sorted);
    } else {
        ksort($sorted, SORT_STRING | SORT_FLAG_CASE);
    }
    return $sorted;
}
```
- `arsort()` sorts an associative array by **value** (points), descending,
  while keeping the key→value pairing intact — highest score first.
- `ksort()` sorts by **key** (nickname) alphabetically. `SORT_STRING |
  SORT_FLAG_CASE` makes it case-insensitive (`"bob"` and `"Bob"` sort
  together rather than capital letters all coming before lowercase ones).

**If tutor asks "why two different sort functions instead of one?"**
→ `arsort`/`ksort` are built-in PHP functions that already do exactly what's
needed (sort by value vs. sort by key) — no need to write a custom sorting
algorithm when PHP provides the right tool for each case.

---

## 7. `exit.php` — Final Score & Reset Option

```php
$board       = loadLeaderboard();
$totalPoints = isset($board[$nickname]) ? $board[$nickname] : 0;
```
Notice this **reads** the leaderboard rather than adding to it — because by
the time a player reaches Exit, every quiz they played already wrote its
points to the file (in `quiz.php`, step 4 above). If this file *also* added
`$_SESSION['game_points']` on top, the player's total would be counted
twice. This was a bug in an earlier version, fixed once leaderboard saving
was moved to be per-quiz instead of per-game.

---

## 8. `newgame.php` — Reset

```php
session_start();
session_unset();
session_destroy();
header('Location: index.php');
exit;
```
- `session_unset()` clears all `$_SESSION` variables.
- `session_destroy()` destroys the session data on the server side entirely.
- Together, this fully resets the player's state, so the next person (or
  the same person starting again) begins with a clean nickname entry —
  nothing carries over except what's already been saved to
  `leaderboard.txt`.

---

## 9. `includes/functions.php` — Shared Helper Functions

| Function | What it does |
|---|---|
| `loadMathQuestions()` | Reads `data/math_questions.txt` line by line, splits each on `\|`, returns an array of associative arrays like `['op1'=>7, 'operator'=>'+', 'op2'=>5, 'answer'=>12]` |
| `loadSeaQuestions()` | Same idea, for `data/sea_questions.txt` — returns `['image'=>..., 'label'=>..., 'correct'=>1/0]` |
| `getRandomQuestions($questions, $count)` | `shuffle()` randomizes array order in place, `array_slice()` takes the first N — this is how "3 random questions" is implemented |
| `loadLeaderboard()` | Reads `data/leaderboard.txt`, returns an associative array `nickname => points` |
| `saveLeaderboard($board)` | Converts the array back to `nickname\|points` lines and **overwrites** the whole file with `file_put_contents()` |
| `updateLeaderboard($nickname, $pointsToAdd)` | Loads the board, adds points (or creates a new entry), saves it back, returns the new total |
| `sortLeaderboard($board, $by)` | Returns a sorted copy — by name or by score |

**Why `file()` with those flags?**
```php
file(MATH_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
```
- `FILE_IGNORE_NEW_LINES` — without this, each line would include the
  trailing `\n` character, which would mess up `explode('|', $line)` on the
  last field.
- `FILE_SKIP_EMPTY_LINES` — ignores blank lines (e.g. a stray empty line at
  the end of the file), so it doesn't try to process an empty string as a
  question.

**Why `explode('|', $line)` and not something like CSV parsing?**
The pipe character `|` is used as a simple delimiter because it's very
unlikely to appear naturally in a nickname, question, or filename (unlike
commas, which might appear in text). `explode()` is PHP's basic
"split a string into an array" function — simplest tool that fits the job.

**Type hints** (e.g. `function loadMathQuestions(): array`) declare what
type of value a function expects/returns. PHP will throw a `TypeError` if
the wrong type is ever passed in or returned, catching mistakes early
rather than causing confusing behaviour somewhere else in the code later.

---

## 10. `includes/header.php` / `footer.php`

Just the shared HTML skeleton (opening `<html>`/`<head>` and closing
`</body>/</html>`) so every page doesn't repeat the same boilerplate. Every
page does:
```php
$pageTitle = 'Some Title';
include 'includes/header.php';
```
before its own content, then `include 'includes/footer.php';` at the end.

---

## Data File Formats (cheat sheet)

```
data/math_questions.txt   → operand1|operator|operand2|correctAnswer
data/sea_questions.txt    → imageFilename|labelShownToUser|isLabelCorrect(1 or 0)
data/leaderboard.txt      → nickname|totalPoints
```
All pipe-delimited, one record per line, parsed with `explode('|', $line)`.

---

## Anticipated Tutor Questions & Short Answers

**Q: Why text files instead of a database?**
A: The assignment specifically requires text file storage, not a database.
Text files are enough here because the data is small (a handful of
questions and leaderboard entries) and only one player interacts with the
files at a time in this context.

**Q: What happens if two people use the same nickname?**
A: They'd share one leaderboard entry — the app has no login/authentication
system to tell them apart, so identical nicknames are treated as the same
player. This is an accepted simplification given the assignment doesn't
require unique accounts.

**Q: What stops someone submitting the quiz form twice by refreshing the
result page?**
A: `quiz.php` redirects to `result.php` after grading (`header('Location:
result.php'); exit;`) rather than displaying the result directly. This is
the Post/Redirect/Get pattern — refreshing `result.php` just re-reads
`$_SESSION['last_result']`, it doesn't resubmit the quiz form.

**Q: Why is the leaderboard update inside `quiz.php` instead of `exit.php`?**
A: So the leaderboard reflects reality immediately — a player can check the
leaderboard mid-game and see their latest score without needing to exit
first. Originally it was only updated on Exit, but that meant the
leaderboard could look outdated while someone was still playing.

**Q: What happens to an empty math answer field?**
A: Blocked before scoring even runs — `quiz.php`'s server-side check
detects any missing `answer{i}` field and re-shows the same 3 questions
with an error, rather than allowing an incomplete submission.
