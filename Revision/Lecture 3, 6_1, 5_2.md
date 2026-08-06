# ISIT307 Study Guide — Lecture 3 (Files & Directories), Lecture 6.1 (State Information), Lecture 5.2 (Databases)

Same method as before: read the concept, run the code, predict output before checking.

---

# PART 1 — LECTURE 3: FILES AND DIRECTORIES

## 1. File Permissions Concept

Three access levels: **User (u)**, **Group (g)**, **Other (o)**.
Three actions: **Read (r)**, **Write (w)**, **Execute (x)**.

Octal digit table:
| Permission | Value |
|---|---|
| Read | 4 |
| Write | 2 |
| Execute | 1 |

Add values to combine. e.g. read+write = 6, read+write+execute = 7.
A permission like `0754` means: owner=7 (rwx), group=5 (r-x), other=4 (r--). First digit is always 0.

---

## 2. `chmod()` and `fileperms()`

**Example A — set permissions**
```php
<?php
chmod("data.txt", 0754); // owner rwx, group r-x, other r--
```

**Example B — read current permissions**
```php
<?php
$testfile = "data.txt";
$perms = fileperms($testfile);
$perms = decoct($perms % 01000);
echo "file permissions for $testfile: 0" . $perms . "<br>";
```
`fileperms()` returns a raw number that includes file-type bits, so `% 01000` strips those down to just the permission bits, and `decoct()` converts to octal for display.

---

## 3. Directory Functions Reference

| Function | Description |
|---|---|
| `chdir(dir)` | change to directory |
| `getcwd()` | get current directory |
| `opendir(dir)` | open a handle to a directory |
| `readdir(handle)` | read next entry, advance pointer |
| `closedir(handle)` | close the handle |
| `scandir(dir[, sort])` | return all entries as an array |

---

## 4. Reading Directories — `opendir()` / `readdir()`

```php
<?php
$Dir = ".";
$DirOpen = opendir($Dir);
while ($CurFile = readdir($DirOpen)) {
    if ((strcmp($CurFile, '.') != 0) && (strcmp($CurFile, '..') != 0))
        echo "<a href=\"./" . $CurFile . "\">" . $CurFile . "</a><br/>\n";
}
closedir($DirOpen);
```
Why the `strcmp()` check: every directory listing includes `.` (current dir) and `..` (parent dir) — you almost always want to skip these.

---

## 5. Reading Directories — `scandir()`

```php
<?php
$Dir = ".";
$DirEntries = scandir($Dir); // scandir($Dir, 1) → descending order
foreach ($DirEntries as $Entry) {
    if ((strcmp($Entry, '.') != 0) && (strcmp($Entry, '..') != 0))
        echo "<a href=\"./" . $Entry . "\">" . $Entry . "</a><br />\n";
}
```
`scandir()` gives you a ready-made array in one call — simpler than the manual `opendir()`/`readdir()` loop, but you still filter out `.` and `..` yourself.

---

## 6. Creating Directories

```php
<?php
mkdir("volunteers");        // relative to current script location
mkdir("../event");          // one level up
mkdir("/bin/PHP/utilities"); // absolute path
```

---

## 7. Uploading Files

**Example A — the HTML form**
```html
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="500000">
    <input type="file" name="filefield">
    <input type="submit" value="Upload">
</form>
```
`enctype="multipart/form-data"` is required for file uploads. `MAX_FILE_SIZE` (hidden field, must appear BEFORE the file input) is a client-side hint — always re-check size on the server too.

**Example B — reading `$_FILES` and moving the file**
```php
<?php
if (isset($_FILES['filefield'])) {
    $error    = $_FILES['filefield']['error'];
    $tmpName  = $_FILES['filefield']['tmp_name'];
    $name     = $_FILES['filefield']['name'];
    $size     = $_FILES['filefield']['size'];
    $type     = $_FILES['filefield']['type'];

    if ($error === 0) {
        $destination = "uploads/" . basename($name);
        move_uploaded_file($tmpName, $destination);
        echo "<p>Uploaded $name ($size bytes, $type) successfully.</p>";
    } else {
        echo "<p>Upload failed with error code $error.</p>";
    }
}
```
The file arrives in a temporary location (`tmp_name`) — `move_uploaded_file()` is what actually saves it somewhere permanent.

---

## 8. Downloading Files (files outside public HTML)

```php
<?php
$file = "private/report.pdf";

header("Content-Description: File Transfer");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=\"report.pdf\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . filesize($file));

readfile($file);
```
Three-step process: (1) identify which file, (2) send headers, (3) send the file. Headers must be sent BEFORE any other output — even one stray space or blank line before `<?php` will break this.

---

## 9. Writing Content to a File

```php
<?php
file_put_contents("log.txt", "New entry\n");             // overwrites file
file_put_contents("log.txt", "Another entry\n", FILE_APPEND); // appends instead
```

---

## 10. Reading Content from a File

```php
<?php
$myfile = file_get_contents("my_file.txt"); // whole file as one string
echo $myfile;

readfile("my_file.txt"); // reads AND outputs directly to browser

$lines = file("my_file.txt"); // whole file as an array, one element per line
foreach ($lines as $lineNum => $line) {
    echo "Line $lineNum: $line <br>";
}
```

---

## 11. File Streams — `fopen()` / `fclose()`

```php
<?php
$handle = fopen("data.txt", "r"); // open for reading
// ... read operations here ...
fclose($handle);
```

`fopen()` mode reference:
| Mode | Meaning |
|---|---|
| `r` | read only, pointer at start |
| `r+` | read + write, pointer at start |
| `w` | write only, erases existing content |
| `w+` | read + write, erases existing content |
| `a` | write only, pointer at end (append) |
| `a+` | read + write, pointer at end |
| `x` | create new for writing, fails if file exists |
| `x+` | create new for read+write, fails if file exists |

---

## 12. Reading/Writing Using a Stream

```php
<?php
$handle = fopen("data.txt", "w");
fwrite($handle, "Hello world\n"); // write
fclose($handle);

$handle = fopen("data.txt", "r");
while (!feof($handle)) {          // loop until end of file
    $line = fgets($handle);       // read one line at a time
    echo $line . "<br>";
}
fclose($handle);
```

Stream function reference:
| Function | Reads |
|---|---|
| `fgetc()` | one character |
| `fgets()` | one line |
| `fread($handle, length)` | up to `length` characters |
| `feof($handle)` | true when pointer reaches end of file |

---

## 13. Copying, Moving, Renaming, Deleting

```php
<?php
copy("data.txt", "data_backup.txt");   // duplicate
rename("data_backup.txt", "backup2024.txt"); // rename/move
unlink("backup2024.txt");              // delete a file
rmdir("empty_folder");                 // delete a directory (must be empty)

if (file_exists("data.txt")) {
    echo "File exists.";
}
```

---

# PART 2 — LECTURE 6.1: MANAGING STATE INFORMATION

## 1. The Concept

HTTP is stateless — the server forgets you between requests. Four tools to fake memory:
1. Hidden form fields
2. Query strings
3. Cookies
4. Sessions

---

## 2. Hidden Form Fields

```php
<?php
$InternID = 42;
echo "<form method='post' action='AvailableOpportunities.php'>\n";
echo "<input type='hidden' name='internID' value='$InternID'>\n";
echo "<input type='submit' name='submit' value='View Available Opportunities'>\n";
echo "</form>\n";
```
Retrieved on the next page with `$_POST['internID']`. Downside: user can view/edit it in page source — never store sensitive data this way.

---

## 3. Query Strings

**Example A — the link with a query string**
```html
<a href="TargetPage.php?firstName=Elena&lastName=Vlahu&occupation=lecturer">Link Text</a>
```

**Example B — reading it**
```php
<?php
echo "{$_GET['firstName']} {$_GET['lastName']} is a {$_GET['occupation']}.";
```
**Output:** `Elena Vlahu is a lecturer.`
Only survives while that link is followed — not stored beyond the single request.

---

## 4. Creating Cookies — `setcookie()`

```php
setcookie(name [, value, expires, path, domain, secure, httponly, samesite]);
```

```php
<?php
setcookie("name", "Elena", time() + 3600, '/', 'domain.com', true, true, 'Strict');
```
**Critical rule:** `setcookie()` must run before ANY other output (even whitespace before `<?php`), or it fails silently/with a warning.

---

## 5. Setting Multiple Cookies

```php
<?php
setcookie("firstName", "Elena");
setcookie("lastName", "Vlahu");
setcookie("occupation", "lecturer");

setcookie("firstName", "Elena", time() + 3600); // update with an expiry
```
Calling `setcookie()` again with the same name overwrites the previous value.

---

## 6. Reading Cookies

**Example A — direct read**
```php
<?php
echo $_COOKIE['firstName'];
```

**Example B — safe read with `isset()`**
```php
<?php
setcookie("firstName", "Elena");
setcookie("lastName", "Vlahu");
setcookie("occupation", "lecturer");
// ---
if (isset($_COOKIE['firstName']) && isset($_COOKIE['lastName']) && isset($_COOKIE['occupation']))
    echo "{$_COOKIE['firstName']} {$_COOKIE['lastName']} is a {$_COOKIE['occupation']}.";
```
Important gotcha: a cookie you just set with `setcookie()` is NOT available in `$_COOKIE` until the page reloads — it only exists for the browser, not for this same script run.

---

## 7. Deleting Cookies

```php
<?php
setcookie("firstName", "", time() - 3600);
setcookie("lastName", "", time() - 3600);
setcookie("occupation", "", time() - 3600);
```
Trick: set an expiry time in the PAST (`time() - 3600` = 1 hour ago) — the browser deletes it immediately.

---

## 8. Starting a Session

```php
<?php
session_start(); // must be called before any output
```
No arguments, no return value. Generates a unique session ID, creates a server-side file `sess_<sessionID>`. If cookies are enabled client-side, the ID is stored in a cookie called `PHPSESSID` automatically.

---

## 9. Passing the Session ID Manually (`session_id()`, `SID`)

```php
<?php
session_start();
?>
<p><a href='<?php echo "Occupation.php?PHPSESSID=" . session_id(); ?>'>Occupation</a></p>
<!-- OR, shorter: -->
<p><a href='<?php echo "Occupation.php?" . SID; ?>'>Occupation</a></p>
```
Needed when the client has cookies disabled — the session ID rides along as a URL token instead.

---

## 10. Session Variables — Setting

```php
<?php
session_start();
$_SESSION['firstName'] = "Elena";
$_SESSION['lastName']  = "Vlahu";
$_SESSION['occupation'] = "lecturer";
?>
<p><a href='<?php echo "Occupation.php?" . SID ?>'>Occupation</a></p>
```

---

## 11. Session Variables — Reading

```php
<?php
session_start();
if (isset($_SESSION['firstName']) && isset($_SESSION['lastName']) && isset($_SESSION['occupation']))
    echo "<p>" . $_SESSION['firstName'] . " " . $_SESSION['lastName'] . " is a " . $_SESSION['occupation'] . "</p>";
```
Unlike cookies, session variables ARE immediately available in `$_SESSION` in the same request they were set — no reload needed.

---

## 12. Ending a Session

```php
<?php
session_start();
session_destroy(); // deletes all session data for this session
```

---

## 13. Relevant php.ini Settings

| Setting | Meaning |
|---|---|
| `session.use_cookies = 1` | store session ID in a cookie |
| `session.use_only_cookies = 0` | also allow session ID via URL |
| `session.name = PHPSESSID` | the cookie/param name used |
| `session.use_trans_sid` | whether PHP auto-adds session ID to URLs |

---

## Comparison Table (memorize this for exam Part A)

| Tool | Survives across pages? | Survives browser close? | Visible to user? | Needs cookies enabled? |
|---|---|---|---|---|
| Hidden form field | One submission only | No | Yes (view source) | No |
| Query string | While link is followed | No | Yes (in URL) | No |
| Cookie (persistent) | Yes | Yes | Yes (browser settings) | Yes |
| Session | Yes (per session) | No (unless persistent cookie stores ID) | No (just an ID) | Preferred, but can fall back to URL |

---

# PART 3 — LECTURE 5.2: WORKING WITH DATABASES USING PHP

## 1. Opening a Connection

```php
<?php
$conn = new mysqli("localhost", "root", "");
```
Optional extra arguments: `dbname`, `port`, `socket`. Closing:
```php
<?php
$conn->close();
```

---

## 2. Error Properties

```php
$conn->connect_errno   // error code for connection failure
$conn->connect_error    // error message for connection failure
$conn->errno            // error code for last query
$conn->error            // error message for last query
```

---

## 3. Connecting with Exception Handling

```php
<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new mysqli($servername, $username, $password);
    echo "<p>Connection successful</p>\n";
} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ": " . $e->getMessage());
}
$conn->close();
```
Since PHP 8.1, mysqli throws exceptions by default on errors — wrap connection/query code in `try/catch`.

---

## 4. Creating and Dropping a Database

```php
<?php
include "inc_dbconnect.php"; // reuses the $conn from part 3

$sql = "CREATE DATABASE myDB2";
try {
    $conn->query($sql);
    echo "Database created successfully";
} catch (mysqli_sql_exception $e) {
    die("Error creating database: " . $e->getCode() . ": " . $e->getMessage());
}

$sql = "DROP DATABASE myDB2";
try {
    $conn->query($sql);
    echo "Database deleted successfully";
} catch (mysqli_sql_exception $e) {
    die("Error deleting database: " . $e->getCode() . ": " . $e->getMessage());
}

$conn->close();
```
`query()` returns `TRUE` for statements with no results (CREATE/DROP), a resultset object for SELECT/SHOW, and throws an exception on failure.

---

## 5. Selecting a Database

```php
<?php
$conn->select_db("mydb");
```
Only needed if you didn't pass `dbname` when creating the connection.

---

## 6. Creating a Table

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

try {
    $conn->query($sql);
    echo "Table MyGuests1 created successfully";
} catch (mysqli_sql_exception $e) {
    die("Error creating table: " . $e->getCode() . ": " . $e->getMessage());
}

$conn->close();
```
`PRIMARY KEY` = unique row identifier. `AUTO_INCREMENT` = auto-generates the next ID. `NOT NULL` = field is required.

---

## 7. Checking If a Table Already Exists

```php
<?php
$sql = "SHOW TABLES LIKE 'MyGuests1'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    // safe to create it
}
```

---

## 8. Inserting Records + `insert_id`

```php
<?php
$sql = "INSERT INTO myguests(firstname, lastname, email)
        VALUES('Elena', 'Vlahu', 'evg@gmail.com')";
try {
    $conn->query($sql);
    $GuestID = $conn->insert_id; // the auto-generated ID of this new row
    echo "Your ID is $GuestID <br />";
} catch (mysqli_sql_exception $e) {
    echo "Unable to insert the record";
}
```

---

## 9. Updating Records

```php
<?php
$email = "newemail@example.com";
$id = 3;

$sql = "UPDATE MyGuests SET email='" . $email . "' WHERE id=" . $id;
try {
    $conn->query($sql);
    echo "Record updated successfully <br />";
} catch (mysqli_sql_exception $e) {
    die("Error in updating: " . $e->getMessage());
}
```
**Security warning:** this pattern (concatenating variables directly into SQL) is vulnerable to SQL injection. Section 19 below shows the safe version with prepared statements — use that in real code.

---

## 10. Deleting Records + `affected_rows`

```php
<?php
$sql = "DELETE FROM MyGuests WHERE id=1";
try {
    $conn->query($sql);
    echo $conn->affected_rows . " row(s) were deleted.<br />";
} catch (mysqli_sql_exception $e) {
    echo "error" . $e->getMessage();
}
```
Omitting `WHERE` deletes ALL rows — always double check the clause before running DELETE.

---

## 11. The `info` Property

```php
<?php
$sql = "INSERT INTO MyGuests (firstname, lastname, email) VALUES
        ('Tom', 'Hon', 'tt@gmail.com'),
        ('Tara', 'Davis', 'tara@gmail.com'),
        ('Kate', 'Smith', 'kate@gmail.com')";
try {
    $conn->query($sql);
    echo "Successfully added the records.<br />";
    echo $conn->info; // e.g. "Records: 3  Duplicates: 0  Warnings: 0"
} catch (mysqli_sql_exception $e) {
    die("Unable to execute the query" . $e->getCode() . ": " . $e->getMessage());
}
```
`info` only returns something useful for bulk INSERT, LOAD DATA, ALTER TABLE, UPDATE — empty string for everything else.

---

## 12. Working with Query Results

| Method | Returns |
|---|---|
| `fetch_row()` | next row, as a numbered array |
| `fetch_assoc()` | next row, as an associative array (field names as keys) |
| `data_seek(pos)` | jumps result pointer to a specific row |
| `fetch_all(MYSQLI_ASSOC)` | ALL rows at once, as an array of arrays |

```php
<?php
$result = $conn->query("SELECT firstname, email FROM MyGuests");

while ($row = $result->fetch_assoc()) {
    echo $row['firstname'] . " - " . $row['email'] . "<br>";
}
```
Both `fetch_row()` and `fetch_assoc()` return `NULL` once there are no more rows — that's what makes the `while` loop stop.

---

## 13. Closing Results and Reading Result Info

```php
<?php
$result = $conn->query("SELECT * FROM MyGuests");
echo "Rows: " . $result->num_rows . "<br>";
echo "Fields: " . $result->field_count . "<br>";
$result->free(); // release memory
```

---

## 14. Prepared Statements

```php
prepare(sqlWithPlaceholders);
bind_param(argType, ...arguments);
execute();
```
Placeholder = `?`. Type letters: `i` integer, `d` double, `s` string, `b` blob.

**Example — full insert with prepared statement**
```php
<?php
$stmt = $conn->prepare("INSERT INTO MyGuests (firstname, lastname, email) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fname, $lname, $email);

$fname = "John";
$lname = "Doe";
$email = "john@example.com";
$stmt->execute();

$stmt->close();
$conn->close();
```
Why prepared statements matter (this is a common exam Part A question):
1. Faster for repeated similar queries — parsed once, reused many times.
2. Less data sent per execution — only the parameter values travel, not the full SQL text.
3. Prevents SQL injection — user input is bound as data, never concatenated as executable SQL.

---

## Practice Plan

1. Set up a local WAMP/XAMPP MySQL database and actually run the CREATE TABLE / INSERT / SELECT examples — don't just read them.
2. Deliberately break a connection (wrong password) and read the real error via `connect_errno`/`connect_error`.
3. Rewrite the plain `UPDATE` example (section 9) as a prepared statement yourself.
4. Practice the cookie vs session comparison table until you can redraw it from memory — it's a favorite Part A exam question.
5. Write a small upload-and-list-files mini script: form uploads a file, then a second script uses `scandir()` to list what's in the folder.