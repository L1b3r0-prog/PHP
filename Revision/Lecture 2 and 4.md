# ISIT307 Study Guide — Lecture 2 (Strings & User Input) + Lecture 4 (Arrays)

How to use this guide: read the concept line, then run the code, then predict output before scrolling to it.

---

# PART 1 — LECTURE 2: STRINGS & USER INPUT

## 1. Text Strings

```php
<?php
echo "<p>PHP literal text string</p>";
$stringVariable = "<p>PHP literal text string</p>";
echo $stringVariable;
```
**Output:**
```
PHP literal text string
PHP literal text string
```
Rule: a string must open and close with the *same* quote type.

---

## 2. Escape Characters

**Example A — apostrophe inside single quotes needs escaping**
```php
<?php
echo '<p>This code\'s going to work</p>';
```

**Example B — double quote inside double quotes needs escaping**
```php
<?php
echo "<p>\"Be ready\" they said.</p>";
```

**Example C — switch quote type instead of escaping**
```php
<?php
echo "<p>This code's going to work</p>";
echo '<p>"Be ready" they said.</p>';
```
Rule of thumb: if the text has apostrophes, wrap it in double quotes (or vice versa) — less escaping.

Key sequences to memorize: `\n` newline, `\t` tab, `\\` backslash, `\$` literal dollar sign, `\"` double quote.

---

## 3. Complex String Syntax

```php
<?php
$many = "page";
echo "<p>How many {$many}s you have?</p>";
```
**Output:** `How many pages you have?`

Why the braces: `$manys` would look for a variable named `$manys` (doesn't exist). `{$many}s` tells PHP exactly where the variable name ends.

---

## 4. Counting Characters and Words

```php
<?php
$title = "I love PHP";
echo "<p>The title contains " . strlen($title) . " chars, ";
echo " and " . str_word_count($title) . " words.</p>";
```
**Output:** `The title contains 10 chars,  and 3 words.`

`strlen()` = byte length (includes spaces). `str_word_count()` = word count.

---

## 5. Case Conversion Functions

| Function | Effect |
|---|---|
| `strtoupper()` | ALL UPPER |
| `strtolower()` | all lower |
| `ucfirst()` | First letter up |
| `lcfirst()` | first letter down |
| `ucwords()` | Every Word Up |

```php
<?php
$s = "web server programming";
echo strtoupper($s) . "<br>";
echo ucwords($s) . "<br>";
```
**Output:**
```
WEB SERVER PROGRAMMING
Web Server Programming
```

---

## 6. `htmlspecialchars()`

```php
<?php
$comment = "<script>alert('x')</script> & \"quotes\"";
echo htmlspecialchars($comment);
```
**Output (as raw text, safe to display):**
```
&lt;script&gt;alert(&#039;x&#039;)&lt;/script&gt; &amp; &quot;quotes&quot;
```
Why it matters: prevents user input from being interpreted as HTML/JS — this is your basic XSS defense whenever you echo user input back to the page.

---

## 7. Trimming Spaces

```php
<?php
$name = "   Elena   ";
echo "[" . trim($name) . "]<br>";
echo "[" . ltrim($name) . "]<br>";
echo "[" . rtrim($name) . "]<br>";
```
**Output:**
```
[Elena]
[Elena   ]
[   Elena]
```

---

## 8. `substr()` — extracting parts of a string

**Example A — the six substr() variations from the slide**
```php
<?php
$ExampleString = "woodworking project";
echo substr($ExampleString, 4)      . "<br>"; // "orking project" (skip 4 from start)
echo substr($ExampleString, 4, 7)   . "<br>"; // "orking " (skip 4, take next 7)
echo substr($ExampleString, 0, 8)   . "<br>"; // "woodwork" (first 8 chars)
echo substr($ExampleString, -7)     . "<br>"; // "project" (last 7 chars)
echo substr($ExampleString, -12, 4) . "<br>"; // "orki" (start 12 from end, take 4)
echo substr($ExampleString, 5, -2)  . "<br>"; // "orking proje" (skip 5 from start, stop 2 before end)
```

**Example B — reverse and shuffle**
```php
<?php
$ExampleString = "woodworking project";
echo strrev($ExampleString) . "<br>";    // "tcejorp gnikrowdoow"
echo str_shuffle($ExampleString) . "<br>"; // random each run
```
Learn `substr()` by drawing the string with index numbers under each character (positive left-to-right, negative right-to-left), then count.

---

## 9 & 10. Searching and Replacing — `strstr()`, `strpos()`, `str_replace()`

```php
<?php
$Email = "my.email@uow.edu.au";

echo "<p>if I use strstr - " . strstr($Email, ".") . "</p>";
// returns everything FROM the first "." onward: ".email@uow.edu.au"

echo "<p>if I use strstr - " . strstr($Email, ".ed") . "</p>";
// returns from first ".ed" onward: ".edu.au"

echo "<p> the @ is at position - " . strpos($Email, "@") . "</p>";
// position of "@" → 9

echo "<p>if I replace the email - " .
                str_replace("email", "e-mail", $Email) . "</p>";
// "my.e-mail@uow.edu.au"

if (strpos($Email, "m") === FALSE)
        echo "the char is not found";
else
        echo "the char is at position ", strpos($Email, "m");
// position of first "m" → 0 (careful: 0 is falsy, that's WHY we use === FALSE, not just !strpos(...))
```
**Critical exam trap:** `strpos()` can legitimately return `0` (found at the start). Always compare with `=== FALSE` (strict), never `== 0` or plain `if (!strpos(...))`.

---

## 11. String Parsing — `str_split()` and `explode()`

```php
<?php
// str_split(): breaks into fixed-length chunks
$chars = str_split("HELLO");        // ["H","E","L","L","O"]
$pairs = str_split("HELLOWORLD", 2); // ["HE","LL","OW","OR","LD"]
print_r($pairs);
```

```php
<?php
// explode(): breaks by a separator
$subjects = "CSIT128; CSIT884; CSIT323; MTS9307";
$subjectsArray = explode(";", $subjects);

foreach ($subjectsArray as $subject) {
        echo "$subject <br />";
}
```
**Output:**
```
CSIT128
  CSIT884
  CSIT323
  MTS9307
```
Note the leading spaces — `explode(";", ...)` splits on `;` only, the space after it stays. If you don't want that, `trim()` each element or split on `"; "`.

---

## 12. `implode()` — the reverse of explode

```php
<?php
$subjectsArray = array("CSIT128", "CSIT884", "CSIT323", "MTS9307");
$subjects = implode(", ", $subjectsArray);
echo $subjects;
```
**Output:** `CSIT128, CSIT884, CSIT323, MTS9307`

Memory trick: **ex**plode = **ex**pand string → array. **im**plode = **im**plode array → string.

---

## 13. String Comparison

```php
<?php
echo strcmp("Apple", "apple") . "<br>";     // negative (A < a in ASCII)
echo strcasecmp("Apple", "apple") . "<br>"; // 0 (case ignored, equal)

var_dump(str_contains("web server programming", "server"));    // true
var_dump(str_starts_with("web server programming", "web"));    // true
var_dump(str_ends_with("web server programming", "PHP"));      // false
```

---

## 14. Mutable Strings — What Is The Output?

```php
<?php
$my_str = "Bob is working";
echo "<p>$my_str</p>";

$my_str[0] = "R";
echo "<p>$my_str</p>";

for ($i = 0; $i < strlen($my_str); $i++)
        echo "<p>$my_str[$i]</p>";
```
**Output:**
```
Bob is working
Rob is working
R
o
b
 
i
s
...
```
Key concept: strings in PHP behave like a character array — `$my_str[0]` accesses/overwrites a single character.

---

## 15. Regular Expressions — the basics

```php
preg_match(pattern, string);
```
Returns `1` if matched, `0` if not. Pattern is enclosed in delimiters (usually `/ /`).

```php
<?php
$ZIP = "015";
var_dump(preg_match("/...../", $ZIP));  // 0 — only 3 chars, pattern needs 5

$ZIP = "01562";
var_dump(preg_match("/...../", $ZIP));  // 1 — exactly 5 chars, each "." = any char
```

---

## 16. Anchors `^` and `$`

**Example A — beginning anchor**
```php
<?php
$URL = "http://www.education.com";
var_dump(preg_match("/^http/", $URL)); // 1 — string starts with "http"
```

**Example B — end anchor**
```php
<?php
$URL = "http://www.education.com";
var_dump(preg_match("/com$/", $URL));  // 1 — string ends with "com"
```

---

## 17. Escaping Metacharacters

**Example A — literal dot**
```php
<?php
$Identifier = "http://www.education.com";
var_dump(preg_match("/\.com$/", $Identifier)); // 1 — literal ".com" at end
```

**Example B — literal dollar sign (needs single quotes or double backslash)**
```php
<?php
$Identifier = '$1234.56';
var_dump(preg_match('/^\$/', $Identifier));   // 1
var_dump(preg_match("/^\\\$/", $Identifier)); // 1 (double-quoted string needs \\ to produce one \)
```
Why: in a double-quoted PHP string, `\$` already means "literal $" to PHP itself, before regex even sees it. Single quotes avoid that extra layer — this is why the slide recommends single quotes for patterns with `$` or `\`.

---

## 18. Quantifiers

| Symbol | Meaning |
|---|---|
| `?` | 0 or 1 |
| `+` | 1 or more |
| `*` | 0 or more |
| `{n}` | exactly n |
| `{n,}` | n or more |
| `{n1,n2}` | between n1 and n2 |

**Example A — `?` optional character**
```php
<?php
$URL = "http://www.education.com";
var_dump(preg_match("/^https?/", $URL)); // 1 — matches "http" or "https"
```

**Example B — `+` one or more**
```php
<?php
$Name = "Don";
var_dump(preg_match("/.+/", $Name)); // 1 — at least 1 character exists
```

**Example C — `*` zero or more**
```php
<?php
$NumberString = "00125";
var_dump(preg_match("/^0*/", $NumberString)); // 1

$NumberString2 = "1234056";
var_dump(preg_match("/^0*/", $NumberString2)); // 1 (still matches — 0 leading zeros is valid too)
```

**Example D — `{}` exact/range repeat count**
```php
<?php
var_dump(preg_match("/ZIP:.{5}$/", " ZIP:01562"));
// 1 — exactly 5 chars after "ZIP:"

var_dump(preg_match("/(ZIP:.{5,10})$/", "ZIP:01562-2607"));
// 1 — between 5 and 10 chars after "ZIP:"
```

---

## 19. Subexpressions (grouping with `()`)

```php
<?php
$pattern = "/^(1 )?(\(.{3}\))?(.{3})(\-.{4})$/";

var_dump(preg_match($pattern, "555-1234"));         // 1
var_dump(preg_match($pattern, "(707)555-1234"));    // 1
var_dump(preg_match($pattern, "1 (707)555-1234"));  // 1
```
Reading the pattern piece by piece:
- `(1 )?` → optional "1 " prefix
- `(\(.{3}\))?` → optional area code in parentheses, e.g. `(707)`
- `(.{3})` → exactly 3 digits
- `(\-.{4})` → dash + 4 digits

---

## 20. Character Classes `[]`

**Example A — allowed alternatives**
```php
<?php
var_dump(preg_match("/analy[sz]e/", "analyse")); // 1
var_dump(preg_match("/analy[sz]e/", "analyze")); // 1
var_dump(preg_match("/analy[sz]e/", "analyce")); // 0
```

**Example B — range**
```php
<?php
$LetterGrade = "B";
var_dump(preg_match("/[A-DF]/", $LetterGrade)); // 1
```

**Example C — exclusion with `^`**
```php
<?php
$LetterGrade = "A";
var_dump(preg_match("/[^EG-Z]/", $LetterGrade)); // 1 (A is not E and not in G-Z)

$LetterGrade2 = "E";
var_dump(preg_match("/[^EG-Z]/", $LetterGrade2)); // 0 (E is excluded)
```
Careful: `^` inside `[]` means "NOT these characters." `^` outside `[]` means "start of string." Same symbol, different meaning by position.

---

## 21. PCRE Shorthand Classes — Email Pattern

```php
<?php
$Email = "elenavg@uow.edu.au";
$pattern = "/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*(\.[a-zA-Z]{2,})$/";
var_dump(preg_match($pattern, $Email)); // 1
```
`\w` = letter, digit, or underscore. `\d` = digit. `\s` = whitespace. Uppercase versions (`\W`, `\D`, `\S`) mean "NOT."

---

## 22. Alternation `|`

**Example A — no match**
```php
<?php
var_dump(preg_match("/\.(com|org|net)$/i", "http://www.education.gov")); // 0
```

**Example B — match**
```php
<?php
var_dump(preg_match("/\.(com|org|net)$/i", "http://www.education.com")); // 1
```
The `/i` modifier makes it case-insensitive.

---

## 23. Autoglobals

| Autoglobal | Contains |
|---|---|
| `$_SERVER` | headers, paths, script info |
| `$_POST` | form data (method="post") |
| `$_GET` | form data / URL tokens (method="get") |
| `$_COOKIE` | cookies sent by browser |
| `$_SESSION` | session variables |
| `$_FILES` | uploaded file info |
| `$GLOBALS` | all global-scope variables |

---

## 24. Web Forms — GET example

**Example A — the HTML form**
```html
<form action="ProcessName.php" method="get">
    First name: <input type="text" name="firstName">
    Last name: <input type="text" name="lastName">
    <input type="submit" name="submit" value="Go">
</form>
```

**Example B — the processing script (ProcessName.php)**
```php
<?php
if (isset($_GET['submit'])) {
    $firstName = $_GET['firstName'];
    $lastName  = $_GET['lastName'];
    echo "<p>Hello, {$firstName} {$lastName}!</p>";
}
```
`method="get"` → data visible in URL, use `$_GET`. `method="post"` → data hidden in request body, use `$_POST`. Prefer POST for sensitive data or large amounts of data.

---

## 25. Form Validation

```php
<?php
$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email format is invalid.";
} else {
    echo "Email OK: $email";
}
```
`empty()` catches missing/blank input. `filter_var()` checks format/type. Always validate server-side — client-side JS validation can be bypassed.

---

## 26. All-in-One Form

```php
<?php
if (isset($_POST['submit'])) {
    // form was submitted — process the data
    $name = htmlspecialchars($_POST['name']);
    echo "<p>Thanks, $name!</p>";
} else {
    // form not yet submitted — show it
    echo "<form method='post' action=''>
            Name: <input type='text' name='name'>
            <input type='submit' name='submit' value='Send'>
          </form>";
}
```
One script does both jobs. `action=''` means "submit to this same page." The `isset()` check on the submit button's name is the switch between "show form" and "process form."

---

## 27. Dynamic Content via URL Tokens

```php
<?php
$content = $_GET['content'] ?? 'Home';

switch ($content) {
    case 'Home':    include 'sections/home.php'; break;
    case 'About':   include 'sections/about.php'; break;
    default:        echo "Section not found.";
}
```
```html
<a href="index.php?content=Home">Home</a>
<a href="index.php?content=About">About</a>
```
One PHP file, many pages — driven entirely by the query string.

---

# PART 2 — LECTURE 4: ARRAYS

## 1. Declaring Indexed Arrays

**Example A — array() function**
```php
<?php
$my_array = array("red", "green", "blue");
```

**Example B — short bracket syntax**
```php
<?php
$my_array = ["red", "green", "blue"];
```

**Example C — append one at a time**
```php
<?php
$my_array[] = "red";
$my_array[] = "green";
$my_array[] = "blue";
```
All three produce the identical array: `[0=>"red", 1=>"green", 2=>"blue"]`.

---

## 2. `array_push`, `array_pop`, `array_shift`, `array_unshift`

| Function | Acts on | Effect | Returns |
|---|---|---|---|
| `array_push()` | end | adds | new count |
| `array_pop()` | end | removes | removed value |
| `array_shift()` | start | removes | removed value |
| `array_unshift()` | start | adds | new count |

**Example A — push then pop**
```php
<?php
$indA3 = array("item1", "item2", "item3");

$num = array_push($indA3, 101, 102);
echo "<h2>Array 3 after push</h2>";
print_r($indA3);
echo "<p>indA3 now has $num elements</p>";

$elem = array_pop($indA3);
echo "<h2>Array 3 after pop</h2>";
print_r($indA3);
echo "<p>deleted element had value $elem</p>";
```
**Output:**
```
Array 3 after push
Array ( [0]=>item1 [1]=>item2 [2]=>item3 [3]=>101 [4]=>102 )
indA3 now has 5 elements

Array 3 after pop
Array ( [0]=>item1 [1]=>item2 [2]=>item3 [3]=>101 )
deleted element had value 102
```

**Example B — unshift then shift**
```php
<?php
array_unshift($indA3, "B1", "B2");
echo "<h2>Array 3 after unshift</h2>";
print_r($indA3);

$elem = array_shift($indA3);
echo "<h2>Array 3 after shift</h2>";
print_r($indA3);
echo "<p>deleted element had value $elem</p>";
```
**Output:**
```
Array 3 after unshift
Array ( [0]=>B1 [1]=>B2 [2]=>item1 [3]=>item2 [4]=>item3 [5]=>101 )

Array 3 after shift
Array ( [0]=>B2 [1]=>item1 [2]=>item2 [3]=>item3 [4]=>101 )
deleted element had value B1
```
Note: push/unshift **re-index** the whole array afterward — indexes stay contiguous 0,1,2...

---

## 3. `array_splice()` — insert/remove anywhere

```php
<?php
$fruits = ["apple", "banana", "cherry", "date", "elderberry"];

// remove 1 element starting at index 1, insert "kiwi","mango" there
array_splice($fruits, 1, 1, ["kiwi", "mango"]);
print_r($fruits);
```
**Output:** `["apple", "kiwi", "mango", "cherry", "date", "elderberry"]`
Syntax: `array_splice(array, start, howManyToDelete, whatToInsert)`.

---

## 4. `unset()`, `array_unique()`, `array_values()`

```php
<?php
$a = [0=>"x", 1=>"y", 2=>"x", 3=>"z"];

unset($a[1]);          // removes index 1, does NOT renumber
print_r($a);            // [0=>"x", 2=>"x", 3=>"z"]

$u = array_unique($a);  // removes duplicate VALUES, keeps original keys
print_r($u);             // [0=>"x", 3=>"z"]

$v = array_values($u);  // renumbers indexes from 0
print_r($v);             // [0=>"x", 1=>"z"]
```
Order to remember: `unset()` leaves gaps → `array_unique()` may leave gaps too → `array_values()` closes the gaps.

---

## 5. Declaring Associative Arrays

**Example A — array() with keys**
```php
<?php
$states = array("NSW" => "New South Wales", "WA" => "Western Australia");
```

**Example B — bracket syntax with keys**
```php
<?php
$states = ["NSW" => "New South Wales", "WA" => "Western Australia"];
```

**Example C — one at a time**
```php
<?php
$states["NSW"] = "New South Wales";
$states["WA"]  = "Western Australia";
$states[]      = "Tasmania"; // no key given → gets numeric index 0
```

---

## 6. Custom Starting Index

```php
<?php
$states[100] = "New South Wales";
$states[]    = "Western Australia"; // becomes index 101
$states[]    = "Tasmania";          // becomes index 102
```
Rule: `[]` always picks "highest existing integer key + 1" — it does NOT reset to 0.

---

## 7. Iterating with `foreach`

```php
<?php
$states = ["NSW" => "New South Wales", "WA" => "Western Australia"];

foreach ($states as $key => $value) {
    echo "$key stands for $value <br>";
}
```
**Output:**
```
NSW stands for New South Wales
WA stands for Western Australia
```

---

## 8. Search Functions

```php
<?php
$fruits = ["apple", "banana", "cherry"];

var_dump(in_array("banana", $fruits));       // true
var_dump(array_search("banana", $fruits));   // 1 (the index)
var_dump(array_search("grape", $fruits));    // false

$states = ["NSW" => "New South Wales"];
var_dump(array_key_exists("NSW", $states));  // true
print_r(array_keys($states));                 // ["NSW"]
```
Difference: `in_array()` checks a **value** exists (true/false). `array_search()` checks a value exists AND tells you **where** (index/false). `array_key_exists()` checks a **key** exists.

---

## 9. `array_slice()` — read a portion without modifying original

```php
<?php
$states = ["NSW", "VIC", "QLD", "WA", "SA"];
$ThreeStates = array_slice($states, 1, 3); // start at index 1, take 3

foreach ($ThreeStates as $code => $state) {
    echo "Index $code is $state <br>";
}
```
**Output:** `["VIC","QLD","WA"]` (with indexes re-based to 0,1,2 by default)

`array_slice()` = read-only copy. `array_splice()` = modifies the original. Easy to confuse — remember "sPlice = modifies."

---

## 10. Sorting Functions

| Function | Sorts by | Order | Keeps keys? |
|---|---|---|---|
| `sort()` | values | ascending | No — reindexed |
| `rsort()` | values | descending | No — reindexed |
| `asort()` | values | ascending | Yes |
| `arsort()` | values | descending | Yes |
| `ksort()` | keys | ascending | Yes |
| `krsort()` | keys | descending | Yes |

```php
<?php
$nums = [3, 1, 2];
sort($nums);
print_r($nums); // [1, 2, 3]

$ages = ["Tom" => 40, "Sam" => 25];
asort($ages);
print_r($ages); // ["Sam"=>25, "Tom"=>40] — keys stay attached to their values
```

---

## 11. Combining Arrays

**Example A — `array_combine()`**
```php
<?php
$keys   = ["NSW", "VIC", "QLD"];
$values = ["New South Wales", "Victoria", "Queensland"];
$states = array_combine($keys, $values);
print_r($states);
```

**Example B — spread operator (unpacking)**
```php
<?php
$array1 = [1, 2, 3];
$array2 = [4, 5, 6];
$combined = [...$array1, ...$array2];
print_r($combined); // [1,2,3,4,5,6]
```

---

## 12. Two-Dimensional Arrays

**Example A — indexed array of indexed arrays**
```php
<?php
$Ounces = array(1, 0.125, 0.0625, 0.03125, 0.0078125);
$Cups   = array(8, 1, 0.5, 0.25, 0.0625);
$VolumeConversions = array($Ounces, $Cups);

echo $VolumeConversions[1][2]; // row "Cups", column index 2 → 0.5
```

**Example B — associative array of associative arrays**
```php
<?php
$Ounces = array("ounces"=>1, "cups"=>0.125, "pints"=>0.0625);
$Cups   = array("ounces"=>8, "cups"=>1, "pints"=>0.5);
$VolumeConversions = array("Ounces" => $Ounces, "Cups" => $Cups);

echo $VolumeConversions["Cups"]["pints"]; // 0.5
```

**Example C — inline literal, array of associative arrays (common in real code)**
```php
<?php
$users = [
    ['name' => 'Tom', 'email' => 'tom@email.com'],
    ['name' => 'Sam', 'email' => 'sam@email.com'],
    ['name' => 'Kim', 'email' => 'kim@email.com']
];

foreach ($users as $user) {
    echo "<p>" . $user['name'] . " - " . $user['email'] . "</p>";
}
```

**Example D — destructuring inside foreach**
```php
<?php
foreach ($users as ['name' => $name, 'email' => $email]) {
    echo "name: $name, email: $email <br>";
}
```
Build your mental model bottom-up: a 2D array is just "an array whose values are themselves arrays." Access it by chaining `[key1][key2]`.

---

## 13. Arrays in Web Forms

**Example A — the HTML form**
```html
<form action='ProcessForm.php' method='post'>
    <p>Enter the first answer:  <input type='text' name='answers[]'></p>
    <p>Enter the second answer: <input type='text' name='answers[]'></p>
    <p>Enter the third answer:  <input type='text' name='answers[]'></p>
    <input type='submit' name='submit' value='submit'>
</form>
```

**Example B — the processing script**
```php
<?php
if (is_array($_POST['answers'])) {
    $Index = 0;
    foreach ($_POST['answers'] as $Answer) {
        ++$Index;
        echo "The answer for question $Index is '$Answer' <br />\n";
    }
}
```
Key idea: giving multiple inputs the *same* `name='answers[]'` collects them all into one PHP array `$_POST['answers']` automatically.

---

## Practice Plan

1. Rebuild every "Example" above from a blank file — don't peek until you've tried.
2. For regex slides, test each pattern against 3 strings: one that should match, one that shouldn't, one edge case.
3. For arrays, print the array with `print_r()` after every single line to watch it change.
4. Redo the exam-style question in `Overall.md` (the `array_pop`/`array_push` hospital departments one) from memory.
5. Time yourself writing the "All-in-One form" pattern and a 2D array loop — these show up often in exam Part B.