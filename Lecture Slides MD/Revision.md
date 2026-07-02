# Lecture 1.1

## Basic Structure

```php
<?php
    // PHP code goes here
?>
```
- Every statement ends with `;`
- File extension must be `.php`

---

## Echo & Print

```php
<?php
    echo "My first PHP script!";
    print "I can use print as well.";
    echo "or use echo", " with many ", "arguments";
?>
```

- `echo` — multiple comma-separated args, no return value
- `print` — only 1 arg, returns `1` (success) or `0` (fail)

**Mixed with HTML:**
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
- `<?= ... ?>` is shorthand for `<?php echo ... ?>`

---

## Case Sensitivity

```php
<?php
echo "<p>Explore <strong>Africa</strong>, <br />";
Echo "<strong>South America</strong>, <br />";
ECHO " and <strong>Australia</strong>!</p>";
?>
```
- Language constructs (like `echo`) → case **insensitive**
- Variable/constant names → case **sensitive**

---

## Comments

```php
// single-line comment
# also single-line

/*
multi-line
block comment
*/
```

---

## Variables

```php
$variable_name = value;

$Num = 18;
echo $Num;
echo "<p>My number is ", $Num, ".</p>";
echo "<p>My number is $Num.</p>";   // double quotes: variable interpolated
echo '<p>My number is $Num.</p>';   // single quotes: printed literally, NOT interpolated

$Num = "value";
echo "<p>My number is $Num.</p>";
```
- Must start with `$`, then letter/underscore, then letters/numbers/underscores
- No spaces allowed
- Case sensitive

---

## Constants

```php
define("CONSTANT_NAME", value);

const CONSTANT_NAME = value;   // top-level scope only — can't use inside functions/loops/if/try
```
- Constant names can't be embedded inside `echo "..."` quotes like variables can

---

## Arrays

```php
// array() construct
$myArray = array(
    "black",
    "white",
    "green",
    "red",
    "yellow");

// bracket append syntax
$myArray[] = "black";
$myArray[] = "white";
$myArray[] = "green";
$myArray[] = "red";
$myArray[] = "yellow";
```

**Debug/display functions:**
- `print_r()` — shows index + value
- `var_dump()` — shows index, value, data type, and length
- `var_export()` — similar to `var_dump()`

**Modifying an element:**
```php
$myArray = array(
    "black",   // index 0
    "white",   // index 1
    "green");  // index 2

$myArray[0] = "yellow";
```

**Assignment notation pitfalls:**
```php
$list = "Hello";     // assigns string to $list variable
$list[] = "Hello";   // appends "Hello" as a new array element
$list[0] = "Hello";  // replaces element at index 0
```

---

## Type Conversion

```php
$NewVariable = (new_type) $OldVariable;   // casting syntax
```

```php
intval($var);      // cast to int
floatval($var);    // cast to float
strval($var);      // cast to string

gettype($var);      // returns the data type

is_numeric($a);
is_int($a);
is_string($a);
```
- PHP converts a string to a number if it *starts* with a numeric value; trailing non-numeric characters are ignored

---

## Arithmetic Operators

| Symbol | Operation |
|---|---|
| `+` | Addition |
| `-` | Subtraction |
| `*` | Multiplication |
| `/` | Division |
| `%` | Modulus (remainder) |
| `**` | Exponentiation |
| `++` / `--` | Increment/decrement (prefix or postfix) |

---

## Assignment Operators

```php
$myNumber = 123;
$myText = "PHP";

$a += $b;   // compound assignment
```

| Symbol | Meaning |
|---|---|
| `=` | assign |
| `+=` | add & assign |
| `-=` | subtract & assign |
| `*=` | multiply & assign |
| `/=` | divide & assign |
| `%=` | modulus & assign |

---

## Comparison Operators

| Symbol | Meaning |
|---|---|
| `==` | equal (value) |
| `===` | identical (value + type) |
| `!=` | not equal |
| `!==` | not identical |
| `<` | less than |
| `>` | greater than |
| `<=` | less than or equal |
| `>=` | greater than or equal |
| `<=>` | spaceship — returns -1, 0, or 1 |

---

## Logical Operators

| Symbol | Meaning |
|---|---|
| `&&` / `AND` | and |
| `\|\|` / `OR` | or |
| `xor` | exclusive or |
| `!` | not |

---

## Concatenation Operator

```php
$txt1 = "I'm learning";
$txt2 = "PHP";
$txt = $txt1.$txt2;
echo " <p> $txt </p>";

$txt = "I'm learning";
$txt .= "PHP";   // compound concatenation
echo " <p> $txt </p>";
```

---

## Operator Precedence (high → low, partial reference)

| Operator(s) | Type | Associativity |
|---|---|---|
| `clone` `new` | clone/new | n/a |
| `**` | arithmetic | right |
| `!` | logical | n/a |
| `* / %` | arithmetic | left |
| `+ - .` | arithmetic/string | left |
| `< <= > >=` | comparison | non-assoc |
| `== != === !== <> <=>` | comparison | non-assoc |
| `&&` | logical | left |
| `\|\|` | logical | left |
| `??` | null coalescing | right |
| `?:` | ternary | non-assoc |
| `= += -= *= /= %= .=` etc. | assignment | right |
| `and` / `xor` / `or` | logical | left |

*(Full table in original slides if needed — this covers the operators most used day-to-day.)*

--------------------------------------------------------------------

# PLecture 4

## Declaring Indexed Arrays

```php
$my_array = array(item1, item2, item3);
$my_array = [item1, item2, item3];

// append one at a time
$my_array[] = item1;
$my_array[] = item2;
$my_array[] = item3;
```

---

## Push / Pop / Shift / Unshift

```php
$indA1 = array("item1", "item2", "item3");

// add to END, returns new count
$num = array_push($indA3, 101, 102);
print_r($indA3);
echo "indA3 now has $num elements";

// remove from END, returns removed value
$elem = array_pop($indA3);
print_r($indA3);
echo "deleted element had value $elem";

// add to BEGINNING
array_unshift($indA3, "B1", "B2");
print_r($indA3);

// remove from BEGINNING, returns removed value
$elem = array_shift($indA3);
print_r($indA3);
echo "deleted element had value $elem";
```

| Function | Position | Action |
|---|---|---|
| `array_push()` | end | add (variadic args) |
| `array_pop()` | end | remove, returns value |
| `array_shift()` | start | remove, returns value |
| `array_unshift()` | start | add (variadic args) |

---

## Splice / Unset / Unique / Values

```php
// add/remove elements anywhere, renumbers indexes
array_splice(array_name, start, number_to_delete, values_to_insert);

// removes elements/variables, does NOT renumber
unset($array_name[index]);

// removes duplicates, keeps original indexes
$new_array = array_unique($array_name);

// renumbers indexed array elements
$new_array = array_values($array_name);
```

---

## Associative Arrays

```php
$my_array = array(key => value, ...);
$my_array = [key => value, ...];

$my_array[key1] = item1;
$my_array[key2] = item2;
```

```php
// unkeyed elements get next available int index
$states["NSW"] = "New South Wales";
$states["WA"]  = "Western Australia";
$states[]      = "Tasmania";   // index 0

// custom starting index, no gaps created
$states[100] = "New South Wales";
$states[]    = "Western Australia"; // index 101
$states[]    = "Tasmania";          // index 102
```

---

## foreach (Iterating)

```php
foreach ($my_array as $key => $value) {
    echo "The $key has $value";
}
```

---

## Search / Check Existence

```php
in_array($value, $array);              // true/false — value exists?
array_search($value, $array);          // returns key/index or false
array_key_exists($key, $array);        // true/false — key exists?
array_keys($array);                    // returns array of all keys
array_keys($array, $value);            // returns keys matching $value

// check at home: array_key_first(), array_key_last()
```

---

## Slice

```php
array_slice(array_name, start, numbers_to_return);

$ThreeStates = array_slice($states, 2, 3);
foreach ($ThreeStates as $code => $state) {
    echo "The code for $state is $code <br />";
}
```

---

## Sorting

```php
sort($array);      // indexed, ascending
rsort($array);      // indexed, descending
asort($array);       // associative, ascending (by value)
arsort($array);      // associative, descending (by value)
ksort($array);        // by key, ascending
krsort($array);        // by key, descending
natsort($array);        // "natural order" sort
```

---

## Combining Arrays

```php
// pair up keys from one array with values from another
$combined = array_combine($keys_array, $values_array);

// array unpacking / merging
$array1 = [1, 2, 3];
$array2 = [4, 5, 6];
$combined = [...$array1, ...$array2];
```

---

## Two-Dimensional Arrays

**Indexed version:**
```php
$Ounces  = array(1, 0.125, 0.0625, 0.03125, 0.0078125);
$Cups    = array(8, 1, 0.5, 0.25, 0.0625);
$Pints   = array(16, 2, 1, 0.5, 0.125);
$Quarts  = array(32, 4, 2, 1, 0.25);
$Gallons = array(128, 16, 8, 4, 1);
$VolumeConversions = array($Ounces, $Cups, $Pints, $Quarts, $Gallons);
```

**Associative version (each row keyed):**
```php
$Ounces = array("ounces" => 1, "cups" => 0.125,
    "pints" => 0.0625, "quarts" => 0.03125, "gallons" => 0.0078125);
// ... (Cups, Pints, Quarts, Gallons similarly)

$VolumeConversions = array(
    "Ounces" => $Ounces, "Cups" => $Cups, "Pints" => $Pints,
    "Quarts" => $Quarts, "Gallons" => $Gallons
);
```

**Inline nested (indexed):**
```php
$VolumeConversions = array(
    array(1, 0.125, 0.0625, 0.03125, 0.0078125), // Ounces
    array(8, 1, 0.5, 0.25, 0.0625),               // Cups
    array(16, 2, 1, 0.5, 0.125),                  // Pints
    array(32, 4, 2, 1, 0.25),                     // Quarts
    array(128, 16, 8, 4, 1)                       // Gallons
);
```

**Inline nested (associative, both dimensions keyed):**
```php
$VolumeConversions = array(
    "ounces"  => array("ounces" => 1, "cups" => 0.125, "pints" => 0.0625,
                        "quarts" => 0.03125, "gallons" => 0.0078125),
    "cups"    => array("ounces" => 8, "cups" => 1, "pints" => 0.5,
                        "quarts" => 0.25, "gallons" => 0.0625),
    // ... pints, quarts, gallons similarly
);
```

**Array of associative arrays (common real-world pattern):**
```php
$users = [
    ['name' => 'Tom', 'email' => 'tom@email.com'],
    ['name' => 'Sam', 'email' => 'sam@email.com'],
    ['name' => 'Kim', 'email' => 'kim@email.com']
];

// basic loop
foreach ($users as $user) {
    echo "<p>" . $user['name'] . "-" . $user['email'] . "</p>";
}

// nested loop over each user's key/value pairs
foreach ($users as $user) {
    foreach ($user as $key => $info) {
        echo "$key: $info, ";
    }
}

// destructuring in foreach
foreach ($users as ['name' => $name, 'email' => $email]) {
    echo "name: $name, email: $email <br>";
}
```

---

## Arrays in Web Forms

**Name attribute syntax** — use `[]` to collect multiple inputs into one array:
```
name='req[]'   or   name='req[0]'   or   name='req[Q1]'
```

**Example form → array of answers:**
```php
<form action='ProcessForm.php' method='post'>
<p>Enter the first answer: <input type='text' name='answers[]' /></p>
<p>Enter the second answer: <input type='text' name='answers[]' /></p>
<p>Enter the third answer: <input type='text' name='answers[]' /></p>
<input type='submit' name='submit' value='submit' />
</form>
```

```php
if (is_array($_POST['answers'])) {
    $Index = 0;
    foreach ($_POST['answers'] as $Answer) {
        ++$Index;
        echo "The answer for question $Index is '$Answer' <br />\n";
    }
}
```

--------------------------------------------------------------------

