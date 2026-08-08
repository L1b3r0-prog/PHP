# ISIT307 — Study Guide: Lecture 1.1 + 1.2
## Introduction to PHP, Functions & Control Structures

---

# PART A — INTRODUCTION (Lecture 1.1)

## PHP Basics

- PHP (PHP: Hypertext Preprocessor) is a **server scripting language** for building dynamic, interactive Web pages
- Open source, free, cross-platform (Windows, Linux, MacOS), compatible with almost all servers (Apache, IIS, etc.)
- Supports a wide range of databases; runs efficiently server-side

**Embedded language**
- PHP files can contain HTML, CSS, JavaScript, and PHP code together
- Files must be saved with a `.php` extension
- PHP code runs on the server — only the **output** (rendered HTML) is sent to the browser; PHP source code is never sent to the client

## Basic Structure of a PHP Script

- PHP code is written between delimiters:
  - `<?php` — opening tag
  - `?>` — closing tag
- Each statement must end with a semicolon (`;`)

## Echo and Print

- `echo` and `print` are **language constructs** used to produce output
- `echo`: can take multiple comma-separated arguments, does **not** return a value
- `print`: takes only **one** argument, returns `1` on success or `0` on failure

```php
<?php
    echo "My first PHP script!";
    print "I can use print as well.";
    echo "or use echo", " with many ", "arguments";
?>
```

**Mixed with HTML:**
```php
<html>
<head><title>PHP Code</title></head>
<body>
<p>
<?php
    echo "My first PHP script!";
    print "I can use print as well.";
?>
</p>
<?php echo "<p>I can use tags as well</p>"; ?>
<?= "or I can use this" ?>
</body>
</html>
```
- `<?= ... ?>` is shorthand for `<?php echo ... ?>`

## Case Sensitivity

- PHP language constructs (like `echo`) are mostly case **insensitive**
- Variable and constant names are case **sensitive**

```php
echo "<p>Explore <strong>Africa</strong>, <br />";
Echo "<strong>South America</strong>, <br />";
ECHO " and <strong>Australia</strong>!</p>";
```

## Comments

- Single-line: `//` or `#`
- Multi-line/block: `/* ... */`

## Variables and Constants

### Variables
- Must start with `$`
- Name must start with a letter or underscore; may contain letters, numbers, underscores
- No spaces allowed
- Case sensitive

```php
$Num = 18;
echo $Num;
echo "<p>My number is ", $Num, ".</p>";
echo "<p>My number is $Num.</p>";   // double quotes: interpolated
echo '<p>My number is $Num.</p>';   // single quotes: literal, NOT interpolated
```

### Constants
```php
define("CONSTANT_NAME", value);

const CONSTANT_NAME = value; // top-level scope only — NOT inside functions/loops/if/try-catch
```
- Constant names **cannot** be embedded inside `echo "..."` quotes (unlike variables)

## Data Types

- **Simple types:** string, int, float, bool, null
- **Reference/composite types:** arrays, objects
- **resource** type: holds a reference to an external resource (e.g. an XML file)

- **Strongly typed** languages require declared data types; **static/strong typing** = type doesn't change after declaration
- **Loosely typed** languages don't require declared types; **dynamic/loose typing** = type can change (PHP is loosely typed)

## Arrays (brief — full detail in Lecture 4)

```php
$myArray = array("black", "white", "green", "red", "yellow");
$myArray[] = "black"; // append syntax
```
- `print_r()` — index + value
- `var_dump()` — index, value, data type, character count
- `var_export()` — similar to `var_dump()`

**Assignment notation pitfalls**
```php
$list = "Hello";     // assigns "Hello" to variable $list
$list[] = "Hello";   // appends "Hello" as new array element
$list[0] = "Hello";  // replaces element at index 0
```

## Type Conversion

```php
$NewVariable = (new_type) $OldVariable; // casting syntax
```
- PHP auto-converts a string to a number if it starts with a numeric value (trailing non-numeric chars ignored)
- Conversion functions: `intval()`, `floatval()`, `strval()`
- `gettype()` — returns data type of a variable
- `is_*()` functions: `is_numeric($a)`, `is_int($a)`, `is_string($a)`

## Operators and Expressions

- **Operator** — symbol representing an operation
- **Expression** — literal, variable, or combination producing a result
- **Literal** — a static value (string or number)
- **Binary operator** — needs operand before and after
- **Unary operator** — needs a single operand, before or after

### Arithmetic Operators
| Symbol | Operation |
|---|---|
| `+` | Addition |
| `-` | Subtraction |
| `*` | Multiplication |
| `/` | Division |
| `%` | Modulus (remainder) |
| `**` | Exponentiation |
| `++` / `--` | Increment/decrement (prefix or postfix) |

### Assignment Operators
| Symbol | Meaning |
|---|---|
| `=` | assign |
| `+=` `-=` `*=` `/=` `%=` | compound assignment |

### Comparison Operators
| Symbol | Meaning |
|---|---|
| `==` | equal (value) |
| `===` | identical (value + type) |
| `!=` | not equal |
| `!==` | not identical |
| `<` `>` `<=` `>=` | relational |
| `<=>` | spaceship (-1 / 0 / 1) |

### Logical Operators
| Symbol | Meaning |
|---|---|
| `&&` / `AND` | and |
| `\|\|` / `OR` | or |
| `xor` | exactly one true |
| `!` | not |

### Concatenation
```php
$txt1 = "I'm learning";
$txt2 = "PHP";
$txt = $txt1.$txt2;
$txt .= "PHP"; // compound concatenation
```

### Operator Precedence (high → low, partial)
`clone`/`new` → `**` (right) → unary/type-casting → `instanceof` → `!` → `* / %` (left) → `+ - .` (left) → comparisons → `&&` → `||` → `??` (right) → ternary → assignment (right) → `and`/`xor`/`or`

---

# PART B — FUNCTIONS AND CONTROL STRUCTURES (Lecture 1.2)

## Defining Functions

```php
<?php
function nameOfFunction(parameters) {
    statements;
}
?>
```
- **Parameter** — variable declared in the function definition
- Functions don't have to contain parameters
- **Return statement** — returns a value to the caller
- **Procedure** — a function without a return statement

### Calling Functions
```php
function averageNumbers($a, $b, $c) {
    $SumOfNumbers = $a + $b + $c;
    return $SumOfNumbers / 3;
}
echo averageNumbers(5,6,7);
```

### Returning Multiple Values (via array)
```php
function multiCalc($n1, $n2, $n3) {
    $sum = $n1+$n2+$n3;
    $prod = $n1*$n2*$n3;
    return array($sum, $prod);
}
$result = multiCalc(5, 6, 7);
echo "Results are: ", $result[0], " and ", $result[1];
```

## Passing Arguments: By Value vs By Reference

- **By value** — creates a local copy; original variable unaffected
- **By reference** — prefix parameter with `&`; original variable **is** modified

```php
<?php
function IncrementByValue($CountByValue) {
    ++$CountByValue;
    echo "<p>IncrementByValue() value is $CountByValue.</p>"; };

function IncrementByReference(&$CountByReference) {
    ++$CountByReference;
    echo "<p>IncrementByReference() value is $CountByReference.</p>";};

$Count = 1;
IncrementByValue($Count);      // $Count still 1 after call
IncrementByReference($Count);  // $Count becomes 2 after call
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

## Variadic Functions

- Accept an arbitrary (0, 1, or n) number of arguments
- Use `...` before the last parameter — arguments become an array

```php
<?php
function sum(...$numbers) {
    $acc = 0;
    foreach ($numbers as $n) { $acc += $n; }
    return $acc;
}
$sum = sum(1, 10, 23); // sum = 34
?>
```

## Optional Parameters / Default Values

- Default value used only when the parameter isn't specified (passing `null` uses the default too)
- Optional parameters must come after required ones

```php
<?php
function makeCoffee($type = "cappuccino") {
    return "<p>Making a cup of $type. </p>";
};
echo makeCoffee();       // cappuccino
echo makeCoffee(null);   // cappuccino
echo makeCoffee("espresso");
?>
```

## Data Type Declarations (PHP 7+)

- Type hints can restrict parameter/return types
- `declare(strict_types=1)` forces exact types — **must be the very first statement in the script**

```php
<?php
function doubleNum(int $number) : int {
    return $number *= 2;
}
$num = 4.8; // implicit float→int conversion warning, still returns 8 without strict_types
?>
```
- With `declare(strict_types=1)`: passing a float to an `int` param throws a `TypeError`

## Union Types (PHP 8)

```php
<?php
declare(strict_types=1);
function doubleNum(int|float $number) : int|float {
    return $number *= 2;
}
?>
```

## Named Arguments (PHP 8)

- Set parameters by name; order doesn't matter as long as required params are passed
- Useful for skipping optional parameters

```php
<?php
function makeSentence($name, $activity="no activity", $hours="") {
    return "Hi $name, you have $activity for $hours hrs";
}
echo makeSentence("John");
echo makeSentence("John", activity: "hiking");
echo makeSentence(activity: "hiking", name: "John", hours: "8");
?>
```

## Anonymous Functions & Arrow Functions

- **Anonymous functions** — no specified name, used for single-use tasks
- **Arrow functions** — shorter syntax with `fn`, auto-capture parent scope variables

```php
fn(parameters) => function body;
```
```php
<?php
$increase10 = function($p1) { return $p1 + 10; };
echo $increase10(10);

$count = 5;
$multi = fn($num) => $num * $count;
echo $multi(10);
?>
```

## Variable Scope

- **Global variable** — declared outside a function, accessible anywhere
- **Local variable** — declared inside a function, accessible only within it
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
echo "<p>$glVar </p>"; // reflects the change
?>
```

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
- `??` (Null Coalescing): returns right operand if left is null, otherwise returns left

```php
echo $today == " Monday " ? " <p>Today is Monday</p> " : " <p>Today is not Monday</p> ";
$tomorrow = $tomorrow ?? " not defined ";
```

### Switch
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
- Concise version of switch, more readable/functional
```php
match (expression) {
    label => statement,
    default => statement,
};
```

## Loop Structures

Four types: `while`, `do...while`, `for`, `foreach`

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
- Always executes **at least once** before condition is checked
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

## Include and Require Statements

- `include` — generates a **warning** if file not found; script **continues**
- `require` — throws an **error**, script **stops**
- `include_once` / `require_once` — include the file only if it hasn't been included already

```php
require 'C:/wamp64/www/my_folder/script.php';
include('my_folder/script.php');
include('./my_folder/script.php'); // recommended relative-path style
```

---

## Quick Self-Check Questions

- What's the difference between `echo` and `print`?
- Why is `'$Num'` in single quotes displayed literally instead of interpolated?
- What's the difference between passing a function argument by value vs by reference?
- What does `declare(strict_types=1)` do, and where must it appear in the script?
- What's the difference between `include` and `require`?
- When would you use a `do...while` loop instead of a `while` loop?
- What is the purpose of the `global` keyword?
- How do arrow functions differ from anonymous functions in variable scope capture?