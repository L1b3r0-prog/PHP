# ISIT307 Study Guide — Lectures 1.1, 1.2, 2
### Based on required topics listed in Overall.md

Overall.md maps these topics to this study set:
- **INTRODUCTION** → Lecture 1.1
- **FUNCTIONS AND CONTROL STRUCTURES** → Lecture 1.2
- **MANIPULATING STRINGS** → Lecture 2
- **HANDLING USER INPUT** → Lecture 2

---

# PART A — INTRODUCTION (from 1_1.md)

## PHP Syntax and Basic Structure

- PHP code is written between delimiters: `<?php` (opening) and `?>` (closing)
- Each statement ends with a semicolon (`;`)
- PHP code is executed on the server; only output is sent to the browser

### Echo and Print
- `echo` and `print` are language constructs that produce output
- `echo` can take multiple comma-separated arguments, does not return a value
- `print` takes only 1 argument, returns `1` on success or `0` on failure

**Example A**
```php
<?php
    echo "My first PHP script!";
    print "I can use print as well.";
    echo "or use echo", " with many ", "arguments";
?>
```

**Example B — with HTML**
```php
<html>
<head><title>PHP Code</title></head>
<body>
<p>
<?php
    echo "My first PHP script!";
    print "I can use print as well.";
    echo "or use echo", " with many ", "arguments";
?>
</p>
<?php echo "<p>I can use tags as well</p>"; ?>
<?= "or I can use this" ?></body>
</html>
```

### Case Sensitivity
- PHP language constructs are mostly case **insensitive** (`echo`, `Echo`, `ECHO` all work)
- Variable and constant names are case **sensitive**

```php
<?php
echo "<p>Explore <strong>Africa</strong>, <br />";
Echo "<strong>South America</strong>, <br />";
ECHO " and <strong>Australia</strong>!</p>";
?>
```

### Comments
- Single-line: `//` or `#`
- Multi-line/block: `/* ... */`

---

## Variables and Constants

### Variables
- Must start with `$`
- Name must start with a letter or underscore; can contain letters, numbers, underscores
- No spaces allowed in names
- Case sensitive

```php
$variable_name = value;
```

```php
$Num = 18;
echo $Num;
echo "<p>My number is ", $Num, ".</p>";
echo "<p>My number is $Num.</p>";
echo '<p>My number is $Num.</p>';   // single quotes = literal, no interpolation

$Num = "value";
echo "<p>My number is $Num.</p>";
```

### Constants
- `define()` function creates a constant:
```php
define("CONSTANT_NAME", value);
```
- `const` keyword — declares constant at top-level scope only (not inside functions/loops/if-else/try-catch):
```php
const CONSTANT_NAME = value;
```
- Constant names **cannot** be included inside quotation marks of an `echo` statement (unlike variables)

---

## Data Types

- **Simple types:** string, int, float, bool, null
- **Reference/composite types:** arrays and objects
- **resource** type: holds a reference to an external resource (e.g. XML file)

### Typing Concepts
- **Strongly typed languages** require declaring data types
- **Static/strong typing** — data types don't change after declaration
- **Loosely typed languages** don't require declaring data types
- **Dynamic/loose typing** — data types can change after declaration (PHP is loosely typed)

### Arrays (brief — full detail in Lecture 4)
- An array holds a set of data under one variable name
- Elements can be of different types within the same array
- Default indexing starts at 0

```php
$myArray = array("black", "white", "green", "red", "yellow");
```
```php
$myArray[] = "black";
$myArray[] = "white";
```
- `print_r()` — displays index and value of each element
- `var_dump()` — displays index, value, data type, and character count
- `var_export()` — similar to `var_dump()`

**Assignment notation pitfalls**
```php
$list = "Hello";     // assigns "Hello" to variable $list
$list[] = "Hello";   // appends "Hello" as new array element
$list[0] = "Hello";  // replaces element at index 0
```

### Type Conversion
- Casting syntax:
```php
$NewVariable = (new_type) $OldVariable;
```
- PHP auto-converts a string to numeric if it starts with a numeric value (ignores trailing non-numeric chars)
- Conversion functions: `intval()`, `floatval()`, `strval()`
- `gettype()` — returns the data type of a variable
- `is_*()` functions: `is_numeric($a)`, `is_int($a)`, `is_string($a)`

---

## Operators and Expressions

- **Operator** — symbol representing an operation (e.g. `+`, `*`)
- **Expression** — literal, variable, or combination that produces a result
- **Literal** — a static value (string or number)
- **Binary operator** — needs operand before and after
- **Unary operator** — needs a single operand, before or after

### Arithmetic Operators

| Symbol | Operation | Description |
|---|---|---|
| + | Addition | Add two operands |
| - | Subtraction | Subtracts right from left operand |
| * | Multiplication | Multiplies two operands |
| / | Division | Divides left by right operand |
| % | Modulus | Divides left by right, returns remainder |
| ** | Exponentiation | Left operand to the power of right operand |

- `++` / `--` — increment/decrement by 1; can be **prefix** (before variable) or **postfix** (after variable)

### Assignment Operators

| Symbol | Operation |
|---|---|
| = | Assignment |
| += | Compound addition |
| -= | Compound subtraction |
| *= | Compound multiplication |
| /= | Compound division |
| %= | Compound modulus |

### Comparison Operators

| Symbol | Operation |
|---|---|
| == | Equals |
| === | Identical (equal value AND type) |
| != | Not equal |
| !== | Not identical |
| < | Less than |
| > | Greater than |
| <= | Less than or equal |
| >= | Greater than or equal |
| <=> | Spaceship (-1 / 0 / 1) |

### Logical Operators

| Symbol | Operation |
|---|---|
| && / AND | And |
| \|\| / OR | Or |
| xor | Exactly one true |
| ! | NOT |

### Concatenation Operator
```php
$txt1 = "I'm learning";
$txt2 = "PHP";
$txt = $txt1.$txt2;
echo " <p> $txt </p>";

$txt = "I'm learning";
$txt .= "PHP";
echo " <p> $txt </p>";
```

### Operator Precedence & Associativity
- **Precedence** — order in which operations are evaluated
- **Associativity** — order among operators of equal precedence (left-to-right or right-to-left)
- Know the general order: `**` (right) → unary/type-casting → `instanceof` → `!` → `* / %` (left) → `+ - .` (left) → comparisons → `&&` → `||` → `??` (right) → ternary → assignment (right) → `and`/`xor`/`or`

---

# PART B — FUNCTIONS AND CONTROL STRUCTURES (from 1_2.md)

## Defining Functions

```php
<?php
function nameOfFunction(parameters) {
    statements;
}
?>
```
- **Parameter** — variable declared in the function definition
- Functions don't require parameters
- **Return statement** — returns a value to the caller
- **Procedure** — a function without a return statement

### Calling Functions
```php
function averageNumbers($a, $b, $c) {
    $SumOfNumbers = $a + $b + $c;
    $Result = $SumOfNumbers / 3;
    return $Result;
}
echo averageNumbers(5,6,7);
```

### Returning Multiple Values (via array)
```php
function multiCalc($n1, $n2, $n3)
{
    $sum = $n1+$n2+$n3;
    $prod = $n1*$n2*$n3;
    return array($sum, $prod);
}
$result = multiCalc(5, 6, 7);
echo "Results are: ", $result[0], " and ", $result[1];
```

### Passing Arguments: By Value vs By Reference
- **By value** — creates a local copy; original is unaffected
- **By reference** — prefix parameter with `&`; original variable IS modified

```php
<?php
function IncrementByValue($CountByValue) {
    ++$CountByValue;
    echo "<p>IncrementByValue() value is $CountByValue.</p>"; };

function IncrementByReference(&$CountByReference) {
    ++$CountByReference;
    echo "<p>IncrementByReference() value is $CountByReference. </p>";};

$Count = 1;
echo "<p>Main program starting value is $Count.</p>";
IncrementByValue($Count);
echo "<p>Main program after call for IncrementByValue, count value is$Count. </p>";
IncrementByReference($Count);
echo "<p>Main program after call for IncrementByReference, count value is $Count. </p>";
?>
```
**Output:**
```
Main program starting value is 1.
IncrementByValue() value is 2.
Main program after call for IncrementByValue, count value is 1.
IncrementByReference() value is 2.
Main program after call for IncrementByReference, count value is 2.
```

### Variadic Functions
- Accept an arbitrary (0, 1, or n) number of arguments
- Use `...` before the last parameter; arguments become an array

```php
<?php
function sum(...$numbers) {
    $acc = 0;
    foreach ($numbers as $n) {
        echo $n, "<br \>";
        $acc += $n;
    }
    return $acc;
}
$sum = sum(1, 10, 23);
echo "sum = $sum";
?>
```

### Optional Parameters / Default Values
- Default value used only when the parameter is not specified (or is `null`, default is used)
- Optional params must come after required ones

```php
<?php
function makeCoffee($type = "cappuccino") {
    return "<p>Making a cup of $type. </p>";
};
echo makeCoffee();
echo makeCoffee(null);
echo makeCoffee("espresso");
?>
```

### Data Type Declarations (PHP 7+)
- Type hints can restrict parameter/return types
- `declare(strict_types=1)` forces exact types (must be the very first statement in the script)

```php
<?php
function doubleNum(int $number) : int {
    return $number *= 2;
}
$num = 5;
echo '$num =', $num, '<br>';
echo 'double_num returns ', doubleNum($num), '<br>';
$num = 4.8;
echo '$num =', $num, '<br>'; // implicit float→int conversion warning, returns 8
echo 'doubleNum returns ', doubleNum($num), '<br>';
?>
```
- With `declare(strict_types=1)`: passing a float to an `int` param throws `TypeError`

### Union Types (PHP 8)
```php
<?php
declare(strict_types=1);
function doubleNum(int|float $number) : int|float {
    return $number *= 2;
}
$num = 4.8;
echo '$num =', $num, '<br>';
echo 'doubleNum returns ', doubleNum($num), '<br>';
?>
```

### Named Arguments (PHP 8)
- Set parameters by name; order doesn't matter as long as required params are passed
```php
<?php
function makeSentence($name, $activity="no activity", $hours="") {
    return "Hi $name, you have $activity for $hours hrs";
}
echo makeSentence("John"), '<br>';
echo makeSentence("John", "swimming"), '<br>';
echo makeSentence("John", activity: "hiking"), '<br>';
echo makeSentence(activity: "hiking", name: "John", hours: "8"), '<br>';
?>
```

### Anonymous Functions & Arrow Functions
- Anonymous functions: no specified name, used for single-use tasks
- Arrow functions: shorter syntax using `fn`, auto-capture parent scope variables
```php
fn(parameters) => function body;
```
```php
<?php
$increase10 = function($p1) {
    return $p1 + 10; };
echo $increase10(10);

$count = 5;
$multi = fn($num) => $num * $count;
echo $multi(10);
?>
```

---

## Variable Scope

- **Global variable** — declared outside a function, usable anywhere
- **Local variable** — declared inside a function, usable only within it
- Use the `global` keyword inside a function to access a global variable

```php
<?php
$glVar = "this is my value";

function scopeExample() {
    global $glVar;
    echo "<p>$glVar</p>";
    $glVar = "if I change it";
}
scopeExample();
echo "<p>$glVar </p>";
?>
```

---

## Control Structures

### If / Else
```php
if (conditional expression)
    statement;
else
    statement;
```

### Ternary Operator & Null Coalescing Operator
- Ternary: concise if/else
- `??` — Null Coalescing: returns right operand if left is null, otherwise returns left

```php
$today = " Tuesday ";
echo $today == " Monday " ? " <p>Today is Monday</p> " : " <p>Today is not Monday</p> ";

$tomorrow = $tomorrow ?? " not defined ";
echo $tomorrow;
```

### Switch Statement
```php
switch (expression) {
    case label:
        statement(s);
        break;
    default:
        statement(s);
        break;
}
```

### Match Expression
- Concise version of switch — more readable/functional
```php
match (expression) {
    label => statement,
    default => statement,
};
```

---

## Loop Structures

Four loop types: `while`, `do...while`, `for`, `foreach`

### While
```php
while (conditional expression) {
    statement(s);
}
```
```php
$count = 1;
while ($count <= 5) {
    echo " $count <br /> ";
    ++$count;
}
```

### Do...While
- Always executes **at least once** before the condition is checked
```php
do {
    statement(s);
} while (conditional expression);
```

### For
```php
for (initialization; condition; update statement) {
    statement(s);
}
```
```php
for ($count = 0; $count < 5; ++$count) {
    echo $count, " <br /> ";
}
```

### Foreach
```php
foreach ($array_name as $variable_name) {
    statements;
}
foreach ($array_name as $index_name => $variable_name) {
    statements;
}
```
```php
$daysOfWeek = array("Monday", "Tuesday", "Wednesday");
foreach ($daysOfWeek as $dayNum => $day) {
    echo "<p>$dayNum is $day</p>";
}
```

---

## Include and Require Statements

- `include` — generates a **warning** if file not found, script **continues**
- `require` — throws an **error**, script **stops**
- `include_once` / `require_once` — only include the file if not already included

```php
require 'C:/wamp64/www/my_folder/script.php';
include('my_folder/script.php');
include('./my_folder/script.php'); // recommended relative-path style
```

---

# PART C — MANIPULATING STRINGS (from 2.md)

## Text Strings
- Enclosed in single or double quotation marks; must start/end with matching quote type

```php
echo "<p>PHP literal text string</p>";
$stringVariable = "<p>PHP literal text string</p>";
```

## Escape Characters and Sequences
- Escape character: backslash (`\`)
- Double-quoted strings interpret escape sequences; single-quoted mostly don't

| Sequence | Meaning |
|---|---|
| `\n` | linefeed |
| `\r` | carriage return |
| `\t` | horizontal tab |
| `\v` | vertical tab |
| `\e` | escape |
| `\f` | form feed |
| `\\` | backslash |
| `\$` | dollar sign |
| `\"` | double-quote |

```php
echo '<p>This code\'s going to work</p>';
echo "<p>\"Be ready\" they said.</p>";
echo "<p>This code's going to work</p>";  // no escape needed in double quotes for apostrophe
```

## Complex String Syntax
- Variables in curly braces inside a string
```php
$many = "page";
echo "<p>How many {$many}s you have?</p>";
```

## Counting Characters and Words
- `strlen()` — string length (bytes)
- `str_word_count()` — number of words
```php
$title = "I love PHP";
echo "<p>The title contains " . strlen($title) . " chars, ";
echo " and " . str_word_count($title) . " words.</p>";
```

## Case Conversion Functions
- `strtoupper()`, `strtolower()`, `ucfirst()`, `lcfirst()`, `ucwords()`

## htmlspecialchars()
- Converts special characters to HTML entities (prevents HTML injection)
- `html_specialcharacters_decode()` — reverses it

| Character | Entity |
|---|---|
| `&` | `&amp;` |
| `"` | `&quot;` |
| `'` | `&#039;` |
| `<` | `&lt;` |
| `>` | `&gt;` |

## Trimming Whitespace
- `trim()` — leading & trailing
- `ltrim()` — leading only
- `rtrim()` — trailing only

## substr()
```php
substr(string, start, optional length);
```
- `start`: positive = skip from beginning; negative = count from end
- `length`: positive = chars to return; negative = skip that many from end; omitted = rest of string

**Example A**
```php
$ExampleString = "woodworking project";
echo substr($ExampleString, 4) . "<br>";
echo substr($ExampleString, 4, 7) . "<br>";
echo substr($ExampleString, 0, 8) . "<br>";
echo substr($ExampleString, -7) . "<br>";
echo substr($ExampleString, -12, 4) . "<br>";
echo substr($ExampleString, 5, -2) . "<br>";
```
**Example B**
```php
echo strrev($ExampleString) . "<br>";
echo str_shuffle($ExampleString) . "<br>";
```

## Search / Replace
- `strstr()` — search and return a substring from a specified point
- `str_replace()` / `str_ireplace()` — replace substring (args: search, replacement, subject)
- `strpos()` — case-sensitive search, returns position of first occurrence, or `FALSE` if not found

```php
<?php
    $Email = "my.email@uow.edu.au";
    echo "<p>if I use strstr - " . strstr($Email, ".") . "</p>";
    echo "<p>if I use strstr - " . strstr($Email, ".ed") . "</p>";
    echo "<p> the @ is at position - " . strpos($Email, "@") . "</p>";
    echo "<p>if I replace the email - " .
                    str_replace("email", "e-mail", $Email) . "</p>";
    if (strpos($Email, "m") === FALSE)
            echo "the char is not found";
    else
            echo "the char is at position ", strpos($Email, "m");
?>
```

## String Parsing
- `str_split(string[, length])` — splits string into an array of characters/chunks
- `explode(separators, string)` — splits a string into an array at a separator

```php
$subjects = "CSIT128; CSIT884; CSIT323; MTS9307";
$subjectsArray = explode(";", $subjects);
foreach ($subjectsArray as $subject) {
        echo "$subject <br />";
}
```

- `implode(separators, array)` — combines array elements into a string

```php
$subjectsArray = array("CSIT128", "CSIT884", "CSIT323", "MTS9307");
$subjects = implode(", ", $subjectsArray);
echo $subjects;
```

## String Comparison
- `strcasecmp()` — case-insensitive comparison
- `strcmp()` — case-sensitive comparison
  - negative: string1 < string2, zero: equal, positive: string1 > string2
- `str_contains()`, `str_starts_with()`, `str_ends_with()` — return boolean

---

## Regular Expressions

- Patterns used to match/manipulate strings
- Two types supported: POSIX Extended, PCRE (Perl Compatible)

```php
preg_match(pattern, string);          // returns 1 if matched, 0 if not
preg_match("/pattern/i", string);     // case-insensitive
```

### Metacharacters

| Metacharacter | Description |
|---|---|
| `.` | Any single character |
| `\` | Escapes next char as literal |
| `^` | Anchors to beginning |
| `$` | Anchors to end |
| `()` | Groups required chars |
| `[]` | Alternate allowed chars |
| `[^]` | Excluded chars |
| `-` | Range of chars |
| `\|` | Alternate sets |

### Matching Any Character
```php
$ZIP = "015";
preg_match("/...../", $ZIP); // returns 0

$ZIP = "01562";
preg_match("/...../", $ZIP); // returns 1
```

### Anchors
```php
$URL = "http://www.education.com";
preg_match("/^http/", $URL); // returns 1
preg_match("/com$/", $URL);  // returns 1
```

### Escaping Special Characters
```php
$Identifier = "http://www.education.com";
preg_match("/\.com$/", $Identifier); // returns 1

$Identifier = "$1234.56";
preg_match('/^\$/', $Identifier);   // returns 1
```

### Quantifiers

| Quantifier | Description |
|---|---|
| `?` | Preceding char optional |
| `+` | One or more matches |
| `*` | Zero or more matches |
| `{n}` | Repeat exactly n times |
| `{n,}` | At least n times |
| `{,n}` | Up to n times |
| `{n1,n2}` | Between n1 and n2 times |

**Examples**
```php
$URL = "http://www.education.com";
preg_match("/^https?/", $URL); // returns 1  ("?" makes 's' optional)

$Name = "Don";
preg_match("/.+/", $Name); // returns 1

$NumberString = "00125";
preg_match("/^0*/", $NumberString); // returns 1

preg_match("/ZIP:.{5}$/", " ZIP:01562"); // returns 1
preg_match("/(ZIP:.{5,10})$/", "ZIP:01562-2607"); // returns 1
```

### Subexpressions (grouping)
```php
preg_match("/^(1 )?(\(.{3}\))?(.{3})(\-.{4})$/", "555-1234");        // return 1
preg_match("/^(1 )?(\(.{3}\))?(.{3})(\-.{4})$/", "(707)555-1234");   // return 1
preg_match("/^(1 )?(\(.{3}\))?(.{3})(\-.{4})$/", "1 (707)555-1234"); // return 1
```

### Character Classes
```php
preg_match("/analy[sz]e/", "analyse"); // returns 1
preg_match("/analy[sz]e/", "analyze"); // returns 1
preg_match("/analy[sz]e/", "analyce"); // returns 0

$LetterGrade = "B";
preg_match("[A-DF]", $LetterGrade); // returns 1 (range)

$LetterGrade = "A";
preg_match("[^EG-Z]", $LetterGrade); // returns 1 (exclude)
```

### PCRE Character Types (common ones to know)

| Escape | Description |
|---|---|
| `\d` | any decimal digit |
| `\D` | any character not a digit |
| `\s` | any whitespace character |
| `\S` | any non-whitespace character |
| `\w` | any letter, number, or underscore |
| `\W` | any character not `\w` |

Example (email pattern):
```php
preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+
        (\.[\w-]+)*(\.[a-zA-Z]{2,})$/", $Email);
```

### Alternation
```php
preg_match("/\.(com|org|net)$/i", "http://www.education.gov"); // returns 0
preg_match("/\.(com|org|net)$/i", "http://www.education.com"); // returns 1
```

### Pattern Modifiers
- `i` — case-insensitive
- `m` — search across newlines
- `s` — changes how `.` works (matches newline too)

---

# PART D — HANDLING USER INPUT (from 2.md)

## Autoglobals (Super Global Variables)

Predefined global associative arrays for runtime/environment/user info:

| Autoglobal | Purpose |
|---|---|
| `$_SERVER` | headers, paths, script locations |
| `$_POST` | form values submitted via "post" |
| `$_GET` | form values submitted via "get" |
| `$_COOKIE` | HTTP cookie values |
| `$_SESSION` | session variables |
| `$_FILES` | uploaded file info |
| `$GLOBALS` | references to all global-scope variables |

## Web Forms

- Two required `<form>` attributes:
  - **action** — the script that processes form data
  - **method** — how data is sent

### Method Attribute
- `"post"` — embeds data in request message → populates `$_POST`
- `"get"` — appends data to URL as query string → populates `$_GET`
- Name/value pairs appended to URL are called **URL tokens**

## Processing Form Data

- A **form handler** is the script that processes submitted data:
  - validates data
  - processes it
  - returns output

### Receiving Form Data
```php
$name = $_POST['name'];
$email = $_POST['email'];
----
$name = $_GET['name'];
$email = $_GET['email'];
```

## Form Data Validation

- `empty()` — checks if a variable has no value (TRUE if empty/zero, FALSE otherwise)
- `filter_var()` — filters/validates a single value (value, filter type)
- `filter_var_array()` — applies filters to multiple values at once
- Common filters: `FILTER_VALIDATE_EMAIL`, `FILTER_VALIDATE_INT`, `FILTER_VALIDATE_FLOAT`

## All-in-One Form

- One script both **displays** the form and **processes** submitted data (vs. a two-part form using separate scripts)
- `isset()` checks whether the Submit button's name is set (i.e. whether form was submitted)

```php
if (isset ($_POST['Submit'])) {
  // Process the data
}
else {
  // Display the Web form
}
```

## Displaying Dynamic Content

- URL tokens can pass different content-selection info to a script
- A **Web template** — single page divided into static + dynamic sections, sections can be stored in separate files
- Navigation via hyperlinks/buttons with query strings:

```html
<a href = "index.php?content=Home">Home</a>
<input type="submit" name="content" value="Home" />
```

---

## Quick Self-Check Questions

- What's the difference between `include` and `require`?
- What's the difference between passing by value vs by reference?
- What does `declare(strict_types=1)` do, and where must it appear?
- Difference between `$_GET` and `$_POST`?
- How does `preg_match()` return values, and what does `i`/`m`/`s` modifier do?
- Difference between a two-part form and an All-in-One form?
- What's the purpose of `htmlspecialchars()`?