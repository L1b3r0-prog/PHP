# ISIT307 Study Guide — Lectures 3, 4, 5.1, 5.2
### Based on required topics listed in Overall.md

Overall.md maps these topics to this study set:
- **FILES AND DIRECTORIES** → Lecture 3
- **ARRAYS** → Lecture 4
- **PHP: OBJECT-ORIENTED PROGRAMMING** (Part 1) → Lecture 5.1
- **WORKING WITH DATABASES USING PHP** → Lecture 5.2

---

# PART A — FILES AND DIRECTORIES (from 3.md)

## File Permissions

- Determine what actions a user can/cannot perform on a file
- Three access levels: **User**, **Group**, **Other**
- Three permission types: **Read (r)**, **Write (w)**, **Execute (x)**
- PHP calculates permissions using a four-digit octal value (first digit always 0)

| Permissions | 1st Digit (always 0) | 2nd Digit User (u) | 3rd Digit Group (g) | 4th Digit Other (o) |
|---|---|---|---|---|
| Read (r) | 0 | 4 | 4 | 4 |
| Write (w) | 0 | 2 | 2 | 2 |
| Execute (x) | 0 | 1 | 1 | 1 |

- Add values together to assign multiple permissions to an access level

### File Permission Functions
```php
chmod($filename, $mode); // e.g. 0754

$perms = fileperms($testfile);
$perms = decoct($perms % 01000);
echo "file permissions for $testfile: 0" . $perms ."<br>";
```
- `chmod()` — changes permissions/mode of a file or directory
- `fileperms()` — reads the permissions of a file

---

## Working with Directories

| Function | Description |
|---|---|
| `chdir(directory)` | Changes to the specified directory |
| `chroot(directory)` | Changes root directory of current process |
| `closedir(handle)` | Closes a directory handle |
| `getcwd()` | Gets current working directory |
| `opendir(directory)` | Opens a handle to a directory |
| `readdir(handle)` | Reads a file/directory name, moves pointer |
| `rewinddir(handle)` | Resets directory pointer to the start |
| `scandir(directory[, sort])` | Returns array of names in a directory |

- A **handle** — variable representing a resource (file/directory)
- The **directory pointer** — refers to the currently selected record in a listing
- `"."` = current directory, `".."` = parent directory (use `strcmp()` to exclude these when iterating)

### Reading Directories — opendir/readdir Example
```php
<?php
$Dir = "."; //$Dir = "name_of_dir";
$DirOpen = opendir($Dir);
while ($CurFile = readdir($DirOpen)) {
    if ((strcmp($CurFile, '.') != 0) && (strcmp($CurFile, '..') != 0))
        echo "<a href=\"./" . $CurFile ."\">" . $CurFile . "</a><br/>\n";
}
closedir($DirOpen);
?>
```

### Reading Directories — scandir() Example
- `scandir()` returns entries sorted ascending by default (pass `1` as 2nd arg for descending)
```php
$Dir = ".";
$DirEntries = scandir($Dir); // scandir($Dir, 1);
foreach ($DirEntries as $Entry) {
    if ((strcmp($Entry, '.') != 0) && (strcmp($Entry, '..') != 0))
        echo "<a href=\"./" . $Entry . "\">" . $Entry . "</a><br />\n";
}
```

### Creating Directories
```php
mkdir("volunteers");        // relative
mkdir("../event");          // relative, up one level
mkdir("/bin/PHP/utilities"); // absolute
```

---

## Uploading Files

- Use a web form with `method="post"` and `enctype="multipart/form-data"`
- `<input type="file" name="filefield" />` creates a Browse button
- `MAX_FILE_SIZE` hidden field limits file size — must appear **before** the file input field

### $_FILES Autoglobal
When the form posts, info is stored in `$_FILES`:

| Element | Description |
|---|---|
| `$_FILES['filefield']['error']` | error code |
| `$_FILES['filefield']['tmp_name']` | temp file location |
| `$_FILES['filefield']['name']` | original filename |
| `$_FILES['filefield']['size']` | size in bytes |
| `$_FILES['filefield']['type']` | file MIME type |

### Considerations
- File size, file type, security, public vs. private access

```php
move_uploaded_file($filename, $destination); // moves temp file to permanent location
```

---

## Downloading Files

- Files in public HTML directories: use a plain hyperlink
- Files outside public directory require 3 steps:
  1. Identify which file to download (e.g. via URL tokens)
  2. Send appropriate headers (must be sent **before** any other content)
  3. Send the file

### Content Headers

| Header | Description | Example |
|---|---|---|
| Content-Description | Message description | `header("Content-Description: File Transfer");` |
| Content-Type | MIME type/subtype | `header("Content-Type: application/force-download");` |
| Content-Disposition | Attachment attributes (filename) | `header("Content-Disposition: attachment; filename=\"list.txt\"");` |
| Content-Transfer-Encoding | Encoding method | `header("Content-Transfer-Encoding: base64");` |
| Content-Length | Length of message | `header("Content-Length: 5000");` |

---

## Writing and Reading File Content

### Writing
```php
file_put_contents(filename, string[, options]);
```
- Returns number of bytes written
- Pass `FILE_APPEND` to append instead of overwrite

### Reading
```php
$myfile = file_get_contents("my_file.txt"); // reads entire file into a string
echo $myfile;

readfile("my_file.txt"); // displays file contents directly to browser
```
- `file()` — reads entire file into an indexed array (auto-detects `\n`, `\r`, `\r\n` line endings)

---

## Opening and Closing File Streams

- A **stream** — channel for reading from/writing to a resource
- **Input stream** — reads data; **Output stream** — writes data
- Steps: (1) `fopen()` → (2) read/write → (3) `fclose()`

- A **handle** — represents the file resource
- A **file pointer** — refers to the currently selected line/character

```php
$open_file = fopen("text file", "method");
fclose($handle);
```

### fopen() Mode Arguments

| Argument | Description |
|---|---|
| a | Write only, pointer at end, creates file if missing |
| a+ | Read/write, pointer at end, creates file if missing |
| r | Read only, pointer at start |
| r+ | Read/write, pointer at start |
| w | Write only, deletes existing content, creates if missing |
| w+ | Read/write, deletes existing content, creates if missing |
| x | Create + write only, fails (FALSE) if file exists |
| x+ | Create + read/write, fails (FALSE) if file exists |

### Writing/Reading via Stream

```php
fwrite($handle, data[, length]); // returns bytes written
```

| Function | Description |
|---|---|
| `fgetc($handle)` | Returns single character, advances pointer |
| `fgetcsv($handle, length[, delimiter, enclosure])` | Returns/parses a CSV line |
| `fgets($handle[, length])` | Returns a line, advances pointer |
| `fgetss($handle, length[, allowed_tags])` | Returns a line, strips XHTML tags |
| `fread($handle, length)` | Returns up to `length` characters |
| `stream_get_line($handle, length, delimiter)` | Returns a line ending in a delimiter |

- `feof($handle)` — returns TRUE when pointer reaches end of file

---

## Managing Files and Directories

- Tasks: Copying, Moving, Renaming, Deleting

```php
copy(source, destination);   // creates a copy of a file
rename(old_name, new_name);  // renames a file or directory
unlink($file);                // deletes a file
rmdir($dir);                  // deletes a directory (must be empty)
file_exists($name);           // returns true/false
```

---

# PART B — ARRAYS (from 4.md)

## Indexed Arrays

```php
$my_array = array(item1, item2, item3);
$my_array = [item1, item2, item3];
$my_array[] = item1;
$my_array[] = item2;
$my_array[] = item3;
```

## Manipulating Arrays — Add/Remove from Ends

- `array_push()` — adds one or more elements to the **end**; variadic; returns new total element count
- `array_pop()` — removes **last** element; returns its value; works on associative arrays too
- `array_shift()` — removes **first** element; returns its value
- `array_unshift()` — adds one or more elements to the **beginning**; variadic

**Example A — push/pop**
```php
$indA1 = array("item1", "item2", "item3");

$num = array_push($indA3, 101, 102);
echo "<h2>Array 3 after push</h2>";
echo "<pre>"; print_r($indA3); echo "</pre>";
echo"<p> indA3 now has $num elements</p>";

$elem = array_pop($indA3);
echo "<h2>Array 3 after pop</h2>";
echo "<pre>"; print_r($indA3); echo "</pre>";
echo"<p>deleted element had value $elem</p>";
```

**Example B — unshift/shift**
```php
array_unshift($indA3, "B1", "B2");
echo "<h2>Array 3 after unshift</h2>";
echo "<pre>"; print_r($indA3); echo "</pre>";

$elem = array_shift($indA3);
echo "<h2>Array 3 after shift</h2>";
echo "<pre>"; print_r($indA3); echo "</pre>";
echo"<p>deleted element had value $elem</p>";
```

## Manipulating Arrays — Insert/Remove Anywhere

- `array_splice(array_name, start, number_to_delete, values_to_insert)` — adds/removes elements anywhere; renumbers indexes
- `unset()` — removes array elements/variables; can remove multiple; does **not** renumber remaining elements
- `array_unique()` — removes duplicate values; returns new array; does **not** renumber indexes
- `array_values()` — renumbers indexed array elements; returns a new array

## Associative Arrays

```php
$my_array = array(key=>value, ...);
$my_array = [key=>value, ...];
$my_array[key1] = item1;
$my_array[key2] = item2;
```

- Adding an element without a key assigns index `0` or the next available integer:
```php
$states["NSW"] = "New South Wales";
$states["WA"] = "Western Australia";
$states[] = "Tasmania";
```
- Can start indexing anywhere without creating empty elements:
```php
$states[100] = "New South Wales";
$states[] = "Western Australia";
$states[] = "Tasmania";
```

## Iterating Arrays

- **Internal array pointer** — refers to currently selected element
- `foreach` doesn't change the pointer position (need advanced foreach for that)

```php
foreach ($my_array as $key => $value)
{
    echo "The $key has $value";
}
```

## More Array Functions

- `in_array()` — checks if a value exists; returns boolean
- `array_search()` — returns key/index of first match, or `false`
- `array_key_exists()` — checks if a key/index exists; returns boolean
- `array_keys()` — returns all keys of an associative array (2nd arg filters by matching value)
- *(For self-study: `array_key_first()`, `array_key_last()`)*

### array_slice()
```php
array_slice(array_name, start, numbers_to_return);
```
```php
$ThreeStates = array_slice($states, 2, 3);
foreach ($ThreeStates as $code => $state)
{
    echo "The code for $state is $code <br />";
}
```

## Sorting Arrays

| Function | Behaviour |
|---|---|
| `sort()` | Indexed array, ascending |
| `rsort()` | Indexed array, descending |
| `asort()` | Associative array, ascending (preserves keys) |
| `arsort()` | Associative array, descending (preserves keys) |
| `ksort()` | By key, ascending |
| `krsort()` | By key, descending |
| `natsort()` | "Natural order" sort |

## Combining Arrays

- `array_combine()` — creates new associative array (one array = keys, another = values)
- Array unpacking (`...`) — merges arrays

```php
$array1 = [1, 2, 3];
$array2 = [4, 5, 6];
$combined = [...$array1, ...$array2];
```

---

## Multidimensional Arrays

- A **two-dimensional array** has two sets of indexes/keys

### Indexed 2D array
```php
$Ounces = array(1, 0.125, 0.0625, 0.03125,0.0078125);
$Cups = array(8, 1, 0.5, 0.25, 0.0625);
$Pints = array(16, 2, 1, 0.5, 0.125);
$Quarts = array(32, 4, 2, 1, 0.25);
$Gallons = array(128, 16, 8, 4, 1);
$VolumeConversions = array($Ounces, $Cups, $Pints, $Quarts, $Gallons);
```

### Associative 2D array
```php
$Ounces = array("ounces" => 1, "cups" => 0.125,
"pints" => 0.0625, "quarts" => 0.03125,
"gallons" => 0.0078125);
// ... (similarly for Cups, Pints, Quarts, Gallons)

$VolumeConversions = array("Ounces" => $Ounces,
"Cups" => $Cups, "Pints" => $Pints,
"Quarts" => $Quarts, "Gallons" => $Gallons);
```

### Direct nested array literal
```php
$VolumeConversions = array(
    array(1, 0.125, 0.0625, 0.03125, 0.0078125), // Ounces
    array(8, 1, 0.5, 0.25, 0.0625), // Cups
    array(16, 2, 1, 0.5, 0.125), // Pints
    array(32, 4, 2, 1, 0.25), // Quarts
    array(128, 16, 8, 4, 1) // Gallons
);
```

### Array of Associative Arrays (common pattern)
```php
$users = [ ['name' => 'Tom', 'email' => 'tom@email.com'],
['name' => 'Sam', 'email' => 'sam@email.com'],
['name' => 'Kim', 'email' => 'kim@email.com']];

foreach($users as $user){
echo"<p>" . $user['name'] . "-" . $user['email'] . "</p>"; }
```
```php
foreach($users as $user){
foreach ($user as $key => $info)
echo"$key: $info, ";}
```
```php
foreach($users as ['name' => $name, 'email' => $email]){
echo"name: $name, email: $email <br>";}
```

---

## Using Arrays in Web Forms

- Give multiple form elements the **same name** with `[]` appended to collect them into an array
```
name='req[]'  or  name='req[0]'  or  name='req[Q1]'
```

```php
<form action='ProcessForm.php' method='post' >
<p>Enter the first answer: <input type='text' name='answers[]' /></p>
<p>Enter the second answer:<input type='text' name='answers[]' /></p>
<p>Enter the third answer:<input type='text' name='answers[]' /></p>
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

---

# PART C — PHP OOP PART 1 (from 5-1.md)

## OOP Key Concepts
object, encapsulation, association, aggregation, delegation, composition, dynamic binding, polymorphism, inheritance, hierarchical objects, abstract classes

## Introduction
- **OOP** — merges related variables and functions into a single interface
- **Object** — code and data treated as one unit/component
- Object orientedness = cooperative problem-solving via objects communicating with one another

## Classes

- A **class** is a blueprint/template defining structure and behaviour of objects
  - Makes complex programs easier to manage
  - Hides info not needed by users of the object
  - Makes code reusable
- **Properties** — variables representing data/state
- **Methods** — functions representing behaviour, operate on properties

```php
class ClassName {
    properties
    methods
}
```
- Class names conventionally start with uppercase
- Good practice: store classes in separate files, `require()` / `require_once()` them in

### Access Specifiers
- **public** — accessible from anywhere
- **private** — accessible only within the class
- **protected** — accessible within the class and child classes
- General rule: properties private/protected; methods used outside the class → public

### $this
- Refers to the object itself, used inside methods to access its own properties/methods
```php
$this->property
$this->method()
```

## Objects

- An object is an **instance** of a class, created with `new`
```php
$objectName = new ClassName();
```
- `->` (member selection) accesses properties/methods

### Useful Functions
- `get_class()` — retrieves class name of an object
- `class_exists()` — checks if a class exists
- `instanceof` — checks if an object is an instance of a given class

```php
$myObj = new MyClass();
echo 'The $myObj object is from' . get_class($myObj) . " class.</p>";
if ($myObj instanceof MyClass) {…}
if (class_exists("MyClass")) {…}
```

### Classes & Objects Example
```php
class BankAccount {
            public float $balance = 120.00;
            public function withdrawal(float $Amount):void {
                $this->balance -= $Amount;
            }
}
if (class_exists("BankAccount"))
   $checking = new BankAccount();
else
   exit("<p>The BankAccount class is not available!</p>");

echo "<p>Your checking account balance is \${$checking->balance}</p>";
$cash = 200;
$checking->withdrawal($cash);
echo "<p>After withdrawing \${$cash}, your checking account balance is 
                                            \${$checking->balance}</p>";
```

## Constructors

- Special function called automatically when an object is instantiated
- Cannot share the class's own name (not recognized as constructor in PHP 8)

```php
class BankAccount{
    private float $balance;
    public function __construct(float $balance) {
        $this->balance = $balance;
    }
}
```

### Constructor Property Promotion (PHP 8.0)
- Declares and initializes properties directly in constructor parameters

```php
class BankAccount {
    public function __construct(private float $balance) {}
}
```
```php
class BankAccount {
    private string $name;
    public function __construct(private float $balance, string $name) {
        $this->name = $name; }
}
```

## Destructors

- Called when the object is destroyed — frees allocated resources
- Commonly triggered when: script ends, or `unset()` is called on the object

```php
function __destruct(){...}
```

## Accessor / Mutator Functions (Get / Set)

- Public methods to retrieve (**accessor/get**) or modify (**mutator/set**) property values

```php
class BankAccount {
    private float $balance=0;
     function __construct(float $bal=0)
    {   $this->balance = $bal;  }
    public function setBalance(float $newValue) 
    {   if ($newValue >0)
             $this->balance = $newValue;  }
    public function getBalance():float 
    {   return $this->balance;     }
}
 $checking = new BankAccount();
 $checking->setBalance(100);
 echo "<p>Your checking account balance is " . $checking->getBalance() 
                                                        . "</p>\n";
 $newCh = new BankAccount();
 $newCh->setBalance(-50);
 echo "<p>Your checking account balance is " . $newCh->getBalance() 
                                                        . "</p>\n";
```

## Magic Functions __get() / __set()

- `__set()` — called when writing to a protected/private property
- `__get()` — called when reading a protected/private property

```php
class MyClass{
    private int $myP;
    function __get($name)
    {   return $this->$name;}
    function __set($name, $value)
    {   $this->$name = $value; }
}
```
```php
$myV = new MyClass();
$myV->myP = 5;
echo $myV->myP;
```

---

# PART D — WORKING WITH DATABASES USING PHP (from 5-2.md)

## Databases vs File Systems

- Indexing → fast, efficient calculation/retrieval/search (vs manual in file systems)
- Controlled redundancy
- Minimum maintenance
- Strong logging mechanism, multiple user interfaces
- Backup and recovery support

## Connecting to Databases with PHP

- PHP can access any ODBC-compliant database
- Direct access options without ODBC/PEAR DB: **PDO**, **mySQLi**

### mysqli Package
- Available since PHP 5; works with MySQL 4.1.3+
- `mysql` package deprecated since PHP 5.5.x — use `mysqli`
- OO equivalent of the old `mysql` package
- Supports both procedural and object-oriented paradigms

## Opening and Closing a MySQL Connection

```php
$conn = new mysqli(host, username, password[,dbname, port,socket]);
$conn->close();
```
- `host` — server location; `user`/`password` — MySQL account credentials; `dbname` — default database

## Reporting MySQL Errors

- Common connection failure reasons: server not running, insufficient privileges, invalid credentials
- Connection error info: `$conn->connect_errno`, `$conn->connect_error`
- Most recent method call error info: `$conn->error`, `$conn->errno`
- `die(error properties)` — shortcut to display error and stop script

## Suppressing Errors — Error Control Operator (@)

- `@` prepended to an expression suppresses error messages
- **PHP 8.0+**: `@` no longer suppresses fatal-type errors: `E_ERROR`, `E_CORE_ERROR`, `E_COMPILE_ERROR`, `E_USER_ERROR`, `E_RECOVERABLE_ERROR`, `E_PARSE`
  - These still halt the application
  - `@` still silences warnings and notices

## Exception Handling

- Since PHP 7, most errors are reported via exceptions
- PHP 8.1: default mysqli error behaviour changed to **throw exceptions**
- **try** — wraps code that might throw an exception
- **catch** — defines the response to a thrown exception
- **throw** — throws an exception, hands control to catch
- **finally** — always executes after try/catch, regardless of exception

### Connection Example
```php
<?php
$servername = "localhost";
$username = "root";
$password = "";

try{
    $conn = new mysqli($servername, $username, $password);
    echo "<p>Connection successful</p>\n";
}
catch (mysqli_sql_exception $e)
{
    die ($e->getCode(). ": " . $e->getMessage());
}
$conn->close();
?>
```

## Executing SQL Statements

```php
$conn->query(query);
```
- Non-result statements (`CREATE DATABASE`, `CREATE TABLE`) → returns `TRUE` on success
- Result-returning statements (`SELECT`, `SHOW`) → returns a resultset object
- Throws an exception on any failed statement

### Create / Drop Database Example
```php
//file inc_dbconnect.php
<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new mysqli($servername, $username, $password);
}
catch (mysqli_sql_exception $e){
    die("Connection failed: " . $e->getCode(). ": " . $e->getMessage());
}
?>
```
```php
<?php
include "inc_dbconnect.php";

$sql = "CREATE DATABASE myDB2";
try {
    $conn->query($sql);
    echo "Database created successfully"; }
catch(mysqli_sql_exception $e) {
    die("Error creating database: " . $e->getCode(). ": " . $e->getMessage()); }

$sql = "DROP DATABASE myDB2";
try {
    $conn->query($sql);
    echo"Database deleted successfully";
}
catch(mysqli_sql_exception $e){
    die( "Error deleting database: " . $e->getCode(). ": " . $e->getMessage());}

$conn->close();
?>
```

## Selecting a Database

```php
$conn->select_db(database); // returns TRUE on success
```
- Needed if the database wasn't specified as a connection argument

## Creating and Deleting Tables

```php
<?php
include 'inc_dbconnect.php';
$conn->select_db("mydb");
$sql = "CREATE TABLE MyGuests1 (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50),
    reg_date TIMESTAMP
)";
// sql to delete table:  $sql = "DROP TABLE MyGuests1";
try {
    $conn->query($sql);
    echo "Table MyGuests1 created successfully"; }
catch (mysqli_sql_exception $e) {
    die("Error creating table: " . $e->getCode(). ": " . $e->getMessage());}

$conn->close();
?>
```
- `PRIMARY KEY` — identifies a primary key field
- `AUTO_INCREMENT` — auto-generates unique IDs (used with primary key)
- `NOT NULL` — requires the field to have a value
- `SHOW TABLES LIKE 'name'` — checks table existence to avoid duplicate-creation errors

## Adding, Deleting, Updating Records

### Load bulk data
```php
$sql = "LOAD DATA INFILE 'myFile.txt'
        INTO TABLE MyGuests
        FIELDS TERMINATED BY '~'";
```

### Insert + insert_id
```php
$sql = "INSERT INTO
    myguests(firstname,lastname, email)
    VALUES('Elena', 'Vlahu', 'evg@gmail.com')";
try {
    $conn->query($sql);
    $GuestID = $conn->insert_id;
    echo "Your ID is $GuestID <br />";
}
catch (mysqli_sql_exception $e) {
    echo "Unable to insert the the record";
}
```
- `insert_id` — returns last AUTO_INCREMENT id (string if beyond max int); `0` if no auto-increment field/updates

### Update
```php
$sql = "UPDATE MyGuests SET email='" . $email
                                . "' WHERE id=" . $id ;
try {
    $conn->query($sql);
    echo "Record updated successfully <br />"; }
catch (mysqli_sql_exception $e) {
    die("Error in updating: " . $e->getMessage() );  }
```

### Delete
- Omit `WHERE` to delete all records

```php
$sql = "DELETE FROM MyGuests where id=1";
try {
    $conn->query($sql);
    echo $conn->affected_rows .
                " row(s) were deleted.<br />";
}
catch (mysqli_sql_exception $e) {
    echo "error" . $e->getMessage();
}
```
- `affected_rows` — number of rows affected by INSERT/UPDATE/DELETE

## The info Property

- Returns info about the last query for: `INSERT INTO...SELECT...`, multi-row `INSERT INTO...VALUES`, `LOAD DATA INFILE`, `ALTER TABLE`, `UPDATE`
- Returns empty string for queries not matching these formats

```php
$sql = "INSERT INTO MyGuests " .
    " (firstname, lastname, email) " .
    " VALUES " .
    " ('Tom', 'Hon', 'tt@gmail.com'), " .
    " ('Tara', 'Davis', 'tara@gmail.com'), " .
    " ('Kate', 'Smith', 'kate@gmail.com')";

try {
    $conn->query($sql);
    echo "Successfully added the records.<br />";
    echo $conn->info;
}
catch (mysqli_sql_exception $e) {
    die("Unable to execute the query" .
        $e->getCode(). ": " . $e->getMessage());
}
```

## Working with Query Results

| Method | Description |
|---|---|
| `fetch_row()` | Returns one row as enumerated array; call again for next row |
| `fetch_assoc()` | Returns one row as associative array (keyed by field name) |
| `data_seek(position)` | Moves result pointer to a specified row |
| `fetch_all(MYSQL_ASSOC\|MYSQL_NUM)` | Returns all rows as an array |

```php
while ($row = $result->fetch_assoc())
{…};
```
- Both `fetch_assoc()` and `fetch_row()` return `NULL` when there are no more rows

### Closing Query Results
```php
free_result(); free(); close();
```

### Result Info Properties
- `num_rows` — number of rows in the result set
- `field_count` — number of fields in the result set

---

## Prepared Statements and Bound Parameters

- A **prepared statement** executes the same/similar SQL repeatedly and efficiently
- **Prepare** step: SQL template sent to DB with `?` placeholders for unspecified values
```php
prepare(sqlstat)
bind_param(argType,[arguments])
bind_result(mixed &$var1 [, mixed &$... ])
```
- Argument types: `i` (integer), `d` (double), `s` (string), `b` (BLOB)

- **Execute** step: bind actual values, then execute; can repeat with different values
```php
execute()
fetch()
get_result()
```

### Advantages
- Reduced parsing time (prepared once)
- Minimizes bandwidth (only parameters sent per execution)
- Protects against SQL injection

### Example
```php
// prepare and bind
$stmt = $conn->prepare("INSERT INTO MyGuests
                                (firstname, lastname, email)
                                VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fname, $lname, $email);
// set parameters and execute
$fname = "John";
$lname = "Doe";
$email = "john@example.com";
$stmt->execute();
$stmt->close();
$conn->close();
```

## phpMyAdmin

- Graphical tool simplifying database/table creation and maintenance tasks

---

## Quick Self-Check Questions

- What's the difference between `array_splice()` and `unset()` when removing array elements?
- How do `fopen()` modes `w`, `a`, `x` differ?
- Why must `setcookie()`/headers be sent before other output? *(applies to `header()` calls too)*
- What's the difference between `public`, `private`, and `protected`?
- What's Constructor Property Promotion, and what PHP version introduced it?
- What are the three main advantages of prepared statements?
- Difference between `fetch_row()` and `fetch_assoc()`?
- What does the `@` operator no longer suppress as of PHP 8.0?