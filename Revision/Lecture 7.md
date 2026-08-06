# ISIT307 Study Guide — Lecture 7.1 (XML/JSON, AJAX) + Lecture 7.2 (Recursion, Data Structures)

Same method: read the concept, run the code, predict output before checking.

---

# PART 1 — LECTURE 7.1: XML/JSON, AJAX

## 1. XML Basics

```xml
<?xml version='1.0' ?>
<contact idx='37'>
<name>Tom White</name>
<category>Family</category>
<phone type='home'>301-555-1212</phone>
<meta id='x634724' />
</contact>
```
Two forms of tags: with value `<tag>value</tag>`, without value (self-closing) `<tag />`. Tags can have attributes (`idx='37'`). Nested tags = children; the enclosing tag = parent. Unlike HTML, XML has no fixed tag vocabulary, but structure must be strict (every opening tag needs a matching closing tag).

---

## 2. PHP XML Parser Types

| Type | Parsers |
|---|---|
| Tree-based | SimpleXML, DOM |
| Event-based | XMLReader, XML Expat Parser |

Tree-based = loads the whole document into memory as a navigable structure. Event-based = reads through the document piece by piece, useful for very large files.

---

## 3. SimpleXML — Loading and Printing

**Example A — load from a string, well-formed XML**
```php
<?php
$myXMLData =
"<?xml version='1.0' ?>
<contact idx='37'>
<name>Tom White</name>
<category>Family</category>
<phone type='home'>02 1555 1212</phone>
<meta id='x634724' />
</contact>";

if (!($xml = simplexml_load_string($myXMLData)))
    die("Error: Cannot create object");

echo "<pre>\n";
print_r($xml);
echo "</pre>\n";
```
`simplexml_load_string()` parses an XML string in a variable. `simplexml_load_file()` does the same from a file. Each tag becomes a property of the returned object.

**Example B — malformed XML, error handling**
```php
<?php
$myXMLData =
"<?xml version='1.0' ?>
<contact idx='37'>
<name>Tom White</mname>
<category>Family</category1>
<phone type='home'>02 1555 1212</phone>
<meta id='x634724' />
</contact>";

libxml_use_internal_errors(true); // stop PHP from throwing warnings directly
$xml = simplexml_load_string($myXMLData);

if ($xml === false) {
    echo "Failed loading XML: ";
    foreach (libxml_get_errors() as $error)
        echo "<br>" . $error->message;
} else {
    echo "<pre>\n";
    print_r($xml);
    echo "</pre>\n";
}
```
Note the deliberate typo: `<name>...</mname>` and `<category>...</category1>` — mismatched tags. `libxml_use_internal_errors(true)` lets you catch parsing errors yourself instead of PHP printing raw warnings.

---

## 4. SimpleXML — Looping Through a File

```php
<?php
$xml = simplexml_load_file('contacts.xml');
echo "<ol>\n";
foreach ($xml->contact as $c) {
    // print contact's name, id, email
    echo '<li>' . $c->name . " - " . $c['idx'] . ", email:" . $c->email;

    echo '<ul>';
    foreach ($c->phone as $p) {
        // attribute accessed like an array key: $p['type']
        // the element itself (echoed directly) gives its text value
        echo '<li>', ucfirst($p['type']), ': ', $p, '</li>';
    }
    echo "</ul></li>\n";
}
```
Key pattern: `$c->name` reads a child element's text. `$c['idx']` reads an attribute. This distinction (`->` for elements, `[]` for attributes) is a common exam trap.

---

## 5. The DOM Parser — Tree Structure

```
Level 1: XML Document
Level 2: Root element (e.g. <note>)
Level 3: Text elements (e.g. <to>, <from>...)
```

```xml
<?xml version='1.0' ?>
<note>
    <to>Jack</to>
    <from>Anna</from>
    <heading>List</heading>
    <body>All important items</body>
</note>
```

```php
<?php
$xmlDoc = new DOMDocument();
$xmlDoc->load("list.xml");
echo $xmlDoc->saveXML();
echo "<br /><br />";

$x = $xmlDoc->documentElement; // the root element (<note>)
foreach ($x->childNodes as $item) {
    echo $item->nodeName . " = " . $item->nodeValue . "<br />";
}
```
**Output pattern:**
```
#text =
to = Jack
#text =
from = Anna
#text =
heading = List
#text =
body = All important items
#text =
```
Why the extra `#text =` lines: whitespace (the newlines/indentation) between tags in the source XML counts as its own text node in the DOM. This trips people up — DOM is more "raw" than SimpleXML.

---

## 6. DOM Parser — Reusable Pattern

```php
<?php
$xmlDoc = new DOMDocument();
$xmlDoc->load("contacts.xml");
$x = $xmlDoc->documentElement;
foreach ($x->childNodes as $item) {
    print $item->nodeName . " = " . $item->nodeValue . "<br>";
}
```

---

## 7. Converting DOM to SimpleXML

```php
<?php
$xmlDoc = new DOMDocument();
$xmlDoc->load("contacts.xml");
$xml = simplexml_import_dom($xmlDoc);

echo "<p>First Contact's name: {$xml->contact[0]->name}</p>\n";
echo "<p>Second Contact's name: {$xml->contact[1]->name}</p>\n";
```
`simplexml_import_dom()` lets you load with DOM (if you need DOM-specific features) but then read it with the friendlier SimpleXML syntax.

---

## 8. XPath — Query Syntax Reference

| Pattern | Matches |
|---|---|
| `x` | any tag named x |
| `x/y` | y directly inside x |
| `x/y/..` | returns x itself (not y) |
| `x//y` | y anywhere inside x, any depth |
| `x[5]` | the 5th tag named x |
| `x[last()]` | the last tag named x |
| `x[@att]` | x with attribute att |
| `x[@att="val"]` | x with attribute att equal to "val" |

---

## 9. XPath — Worked Example

```php
<?php
$xml = simplexml_load_file('contacts.xml');

$meta = $xml->xpath('//meta'); // any 'meta' tag, any depth
foreach ($meta as $m) {
    echo "Meta - {$m['id']}<br />\n";
}

$email = $xml->xpath('/contacts/contact/email'); // email tags directly under contact, from root
foreach ($email as $e) {
    echo "Email - {$e}<br />\n";
}

$cell = $xml->xpath('contact/phone[@type="cell"]/..'); // contacts that HAVE a cell phone
foreach ($cell as $c) {
    echo "Cell Contact - {$c->name}<br />\n";
}
```
**Output:**
```
Meta - x634724
Meta - y49302
Meta - z34567
Email - ritab@example.com
Email - rj@example.com
Cell Contact - Rick Jones
```
Reading the third query: `phone[@type="cell"]` finds the phone tag, `/..` steps back UP to its parent `contact` tag — this is the trick for "find the parent record based on a condition on its child."

---

## 10. JSON Basics

```json
{
"contacts": [
{"id": 37, "name": "Tom White", "phone": "04 1555 1212"},
{"id": 42, "name": "Rita Brown", "phone": "02 2555 1212"},
{"id": 56, "name": "Rick Jones", "phone": "04 1235 6765"}
]
}
```
Object = `{ }` with key-value pairs. Array = `[ ]` with a list of values. JSON is text-based, lighter than XML, and is the standard format for web APIs today.

---

## 11. `json_encode()` and `json_decode()`

```php
<?php
$data = ["contacts" => [
    ["id" => 37, "name" => "Tom White", "phone" => "02 1555 1212"],
    ["id" => 42, "name" => "Rita Brown", "phone" => "02 2555 1212"],
    ["id" => 56, "name" => "Rick Jones", "phone" => "04 1235 6765"]
]];

$json_output = json_encode($data);
echo $json_output;
echo "<br>---------<br>";

$json_output = json_encode($data, JSON_PRETTY_PRINT);
echo $json_output;
echo "<br>---------<br>";

$json_input = file_get_contents('contacts.json');

$data_object = json_decode($json_input); // as an OBJECT — access with ->
print_r($data_object);
echo "<br>---------<br>";

$data_array = json_decode($json_input, true); // as an ASSOCIATIVE ARRAY — access with []
print_r($data_array);
```
The second argument to `json_decode()` is the switch: `true` → array, `false`/omitted → object. This decides whether you'll later write `$data->contacts[0]->name` (object) or `$data['contacts'][0]['name']` (array).

---

## 12. What Is AJAX

Asynchronous JavaScript And XML — lets a web page talk to the server and update itself WITHOUT a full page reload.

Ajax combines:
- `XMLHttpRequest` object — sends/receives data
- JavaScript/DOM — updates the page
- CSS — styles the update
- XML (or JSON, or plain text/HTML) — the data format

Flow:
```
Browser event happens → create XMLHttpRequest → send request
      → Server processes request → sends response back
      → Browser's JS processes response → updates page content
```

---

## 13. AJAX + PHP — the Client Side

```html
<html>
<head>
<script>
function showHint(str) {
    if (str.length == 0) {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "gethint.php?q=" + str, true);
        xmlhttp.send();
    }
}
</script>
</head>
<body>
<p><b>Start typing a name in the input field below:</b></p>
<form>
First name: <input type="text" onkeyup="showHint(this.value)">
</form>
<p>Suggestions: <span id="txtHint"></span></p>
</body>
</html>
```
`readyState` values: 0 not initialized, 1 connection established, 2 request received, 3 processing, 4 finished + response ready.
`status` 200 = OK, 403 = forbidden, 404 = not found.
Only act on the response when `readyState == 4 && status == 200` — otherwise you're reading a half-finished response.

---

## 14. AJAX + PHP — the Server Side

```php
<?php
$a[] = "Anna";
$a[] = "Brittany";
$a[] = "Cinderella";

$q = $_GET["q"];
$hint = "";

if ($q !== "") {
    $q = strtolower($q);
    $len = strlen($q);
    foreach ($a as $name) {
        if (stristr($q, substr($name, 0, $len))) {
            if ($hint === "") {
                $hint = $name;
            } else {
                $hint .= ", $name";
            }
        }
    }
}

echo $hint === "" ? "no suggestion" : $hint;
```
This is a plain PHP script — it doesn't know or care it's being called by AJAX. It just reads `$_GET['q']` and echoes plain text back. The "asynchronous" part is entirely on the JavaScript side.

---

# PART 2 — LECTURE 7.2: RECURSION AND DATA STRUCTURES

## 1. What Is Recursion

A function that calls itself. Needs:
- A **base case** — the condition that stops the recursion.
- A **reduction step** — each call must move closer to the base case.

```php
<?php
function myRecursiveFunction() {
    if ($baseCaseReached) {
        return; // stop
    } else {
        myRecursiveFunction(); // recurse — must move toward base case
    }
}
```
Good recursive functions: sub-problems converge toward the base case, sub-problems don't overlap, and every call creates a fresh instance of the function (its own local variables).

Use recursion for: recursively-defined data (like trees/XML), and nested structures (like folders inside folders).

---

## 2. Recursion — Factorial

```php
<?php
function factorial($n) {
    if ($n == 0) {
        echo "Base case: \$n = 0. Returning 1...<br>";
        return 1;
    } else {
        echo "\$n = $n: Computing $n * factorial(" . ($n - 1) . ")...<br>";
        $result = ($n * factorial($n - 1));
        echo "Result of $n * factorial(" . ($n - 1) . ") = $result. Returning $result...<br>";
        return $result;
    }
}
echo "The factorial of 5 is: " . factorial(5);
```
**Output:**
```
$n = 5: Computing 5 * factorial(4)...
$n = 4: Computing 4 * factorial(3)...
$n = 3: Computing 3 * factorial(2)...
$n = 2: Computing 2 * factorial(1)...
$n = 1: Computing 1 * factorial(0)...
Base case: $n = 0. Returning 1...
Result of 1 * factorial(0) = 1. Returning 1...
Result of 2 * factorial(1) = 2. Returning 2...
Result of 3 * factorial(2) = 6. Returning 6...
Result of 4 * factorial(3) = 24. Returning 24...
Result of 5 * factorial(4) = 120. Returning 120...
The factorial of 5 is: 120
```
Trace it by hand with a "call stack" diagram: calls go DOWN (5→4→3→2→1→0) until the base case, then results bubble back UP, multiplying as they go. This is the single most common exam trace question for recursion.

---

## 3. Recursion — Displaying a Folder Tree

```php
<?php
$folderPath = "C://wamp64/www";

function readFolder($path) {
    if (!($dir = opendir($path)))
        die("Can't open $path");

    $filenames = array();
    while ($filename = readdir($dir)) {
        if ($filename != '.' && $filename != '..') {
            if (is_dir("$path/$filename"))
                $filename .= '/'; // mark folders with a trailing slash
            $filenames[] = $filename;
        }
    }
    closedir($dir);

    echo "<ul>";
    foreach ($filenames as $filename) {
        echo "<li>$filename";
        if (substr($filename, -1) == '/')
            readFolder("$path/" . substr($filename, 0, -1)); // recurse into subfolder
        echo "</li>";
    }
    echo "</ul>";
}

echo "<h2>Contents of '$folderPath':</h2>";
readFolder($folderPath);
```
Why recursion fits here: a folder can contain folders, which can contain folders — an unknown, arbitrary depth. `readFolder()` calling itself whenever it finds a subfolder is exactly the "nested structure" use case from section 1.

---

## 4. Recursion + Static Variables

```php
<?php
function test() {
    static $step = 0; // keeps its value BETWEEN calls, unlike a normal local variable
    if ($step < 10) {
        $step++;
        echo "<p>into function step = $step</p>";
        test();
    } else {
        echo "finish";
    }
}
test();
echo "<p>out of function";
```
**Output:**
```
into function step = 1
into function step = 2
into function step = 3
into function step = 4
into function step = 5
into function step = 6
into function step = 7
into function step = 8
into function step = 9
into function step = 10
finish
out of function
```
Without `static`, `$step` would reset to 0 on every call — the recursion would never end. `static` is one clean way to give a recursive function memory of "how deep am I" without passing an extra parameter.

---

## 5. Abstract Data Types (ADT) Overview

PHP has 8 primitive types: boolean, integer, float, string, array, object, resource, null.
An **ADT** is a conceptual model for organizing data; a **data structure** is the concrete implementation of that model.

Common ADTs: List, Map, Set, Stack, Queue, Priority Queue, Graph, Tree.

---

## 6. Linked Lists

```
Singly:  [12|•]→[99|•]→[37|•]→[X]
Doubly:  [X]←[•|12|•]⇄[•|99|•]⇄[•|37|•]→[X]
```
A linked list = nodes connected by pointers/links, instead of sitting in contiguous memory like an array. Doubly linked = each node also links back to the previous node, so you can traverse in both directions.

---

## 7. Stack (LIFO)

Last In, First Out — think of a stack of plates, you take from the top.

```php
<?php
class Stack {
    protected $stack;
    protected $top;

    public function __construct() {
        $this->stack = array();
        $this->top = -1;
    }

    public function push($item) {
        $this->top++;
        $this->stack[$this->top] = $item;
    }

    public function pop() {
        if ($this->isEmpty()) return null;
        $item = $this->stack[$this->top];
        unset($this->stack[$this->top]);
        $this->top--;
        return $item;
    }

    public function top() {
        return $this->isEmpty() ? null : $this->stack[$this->top];
    }

    public function isEmpty() {
        return $this->top == -1;
    }
}

$s = new Stack();
$s->push(10);
$s->push(20);
echo $s->pop(); // 20 — last one in, first one out
echo $s->top(); // 10
```
The lecture slide only shows the class skeleton (method bodies as `{ . . . }`) — the implementation above is the working version you can actually run.

---

## 8. Queue (FIFO)

First In, First Out — think of a checkout line.

```
Enqueue → [Back][ ][ ][ ][Front] → Dequeue
```
- `enqueue` = add to the back
- `dequeue` = remove from the front

```php
<?php
class Queue {
    protected $queue = [];

    public function enqueue($item) {
        array_push($this->queue, $item); // add to back
    }

    public function dequeue() {
        return array_shift($this->queue); // remove from front
    }

    public function isEmpty() {
        return count($this->queue) == 0;
    }
}

$q = new Queue();
$q->enqueue("A");
$q->enqueue("B");
echo $q->dequeue(); // "A" — first one in, first one out
```
Notice the array function pairing: Stack = `push()`/manual pop from the end. Queue = `push()` to add, `shift()` to remove from the front.

---

## 9. Trees

```
              Root
               A          Level 0
            /     \
           B       C       Level 1
          / \     / \
         D   E   F   G     Level 2
        /|   |
       H I   J             Level 3
```
Vocabulary: **root** = top node, **parent/child** = direct connection, **siblings** = same parent, **leaf** = no children, **subtree** = any node + everything below it.
Trees are naturally recursive: a tree is a root node plus a collection of subtrees (which are themselves trees).

---

## 10. Binary Tree Implementation

```php
<?php
class BinaryNode {
    public $value;
    public $left;
    public $right;

    public function __construct($item) {
        $this->value = $item;
        $this->left = null;  // new nodes start as leaf nodes
        $this->right = null;
    }

    public function addChildren($left, $right) {
        $this->left = $left;
        $this->right = $right;
    }
}

// building a small tree: root A with children B and C
$b = new BinaryNode("B");
$c = new BinaryNode("C");
$a = new BinaryNode("A");
$a->addChildren($b, $c);

echo $a->value . "<br>";        // A
echo $a->left->value . "<br>";  // B
echo $a->right->value . "<br>"; // C
```
A "binary" tree = each node has at most 2 children (`left`, `right`). Note the class only stores `value` — traversal (visiting all nodes) is normally a separate recursive function you'd write yourself, e.g.:

```php
<?php
function printTree($node) {
    if ($node === null) return; // base case
    echo $node->value . " ";
    printTree($node->left);     // recurse left
    printTree($node->right);    // recurse right
}
printTree($a); // A B C
```

---

## 11. Heaps

```
                100
             /       \
            19         36
          /    \      /   \
         17     3    25    1
        /  \
       2    7
```
**Max heap** — root is always the largest value. **Min heap** — root is always the smallest value. Not fully sorted overall — only the parent/child relationship (parent ≥ children for max heap) is guaranteed.

---

## 12. Graphs

```
A → B → C
    ↓   ↓
    D ← E → F
```
**Directed graph** — edges have a direction (A → B doesn't imply B → A). **Undirected graph** — edges have no direction, just a connection.

Connectivity types:
- **Weakly connected** — connected if you ignore direction.
- **Strongly connected** — a path exists between every pair of nodes IN the correct direction (often forms a cycle).
- **Disjoint** — the graph splits into separate unconnected pieces.

---

## 13. Representing Graphs — Adjacency Matrix

```
Vertex vector: A B C D E F

Adjacency matrix (undirected)
   A B C D E F
A  0 1 0 0 0 0
B  1 0 1 0 1 0
C  0 1 0 1 1 0
D  0 0 1 0 1 0
E  0 1 1 1 0 1
F  0 0 0 0 1 0
```
A `1` at row X, column Y means there's an edge between X and Y. For an undirected graph the matrix is symmetric (mirrors across the diagonal). For a directed graph it usually isn't.

```php
<?php
$vertices = ['A', 'B', 'C', 'D', 'E', 'F'];
$matrix = [
    [0,1,0,0,0,0],
    [1,0,1,0,1,0],
    [0,1,0,1,1,0],
    [0,0,1,0,1,0],
    [0,1,1,1,0,1],
    [0,0,0,0,1,0],
];

// check if A and B are connected
$i = array_search('A', $vertices);
$j = array_search('B', $vertices);
echo $matrix[$i][$j] ? "Connected" : "Not connected"; // Connected
```

---

## 14. Representing Graphs — Adjacency List

```
A → B
B → A → C → E
C → B → D → E
D → C → E
E → B → C → D → F
F → E
```
Each vertex lists only its direct neighbors — usually more memory-efficient than a matrix for sparse graphs (few connections relative to total possible).

```php
<?php
$adjacencyList = [
    'A' => ['B'],
    'B' => ['A', 'C', 'E'],
    'C' => ['B', 'D', 'E'],
    'D' => ['C', 'E'],
    'E' => ['B', 'C', 'D', 'F'],
    'F' => ['E'],
];

foreach ($adjacencyList['E'] as $neighbor) {
    echo "E connects to $neighbor <br>";
}
```

---

## 15. Weighted Graphs (Networks)

```
A-B: 523    A-C: 345
B-C: 200    B-D: 548
C-D: 360    C-E: 467
D-E: 245    D-F: 320
E-F: 555
```
Same idea as adjacency matrix/list, but each entry stores a **weight** (cost/distance/etc.) instead of just `1`/`0`.

```php
<?php
$weightedList = [
    'A' => ['B' => 523, 'C' => 345],
    'B' => ['A' => 523, 'C' => 200, 'D' => 548],
    // ...
];

echo $weightedList['A']['C']; // 345 — the weight of edge A-C
```
Used for shortest-path problems (e.g. "find the cheapest route from A to F").

---

## 16. Standard PHP Library (SPL) Data Structures

| ADT | SPL Class |
|---|---|
| Doubly linked list | `SplDoublyLinkedList` |
| Stack | `SplStack` |
| Queue | `SplQueue` |
| Heap (max/min) | `SplHeap`, `SplMaxHeap`, `SplMinHeap` |
| Priority queue | `SplPriorityQueue` |
| Fixed-size array | `SplFixedArray` |
| Map | `SplObjectStorage` |

```php
<?php
$stack = new SplStack();
$stack->push(1);
$stack->push(2);
echo $stack->pop(); // 2

$queue = new SplQueue();
$queue->enqueue("A");
$queue->enqueue("B");
echo $queue->dequeue(); // "A"
```
Takeaway: you don't have to hand-roll Stack/Queue classes like section 7/8 in real projects — SPL already provides tested versions built in to PHP.

---

## Practice Plan

1. Hand-trace the `factorial(5)` and `test()` (static variable) examples on paper, writing each call and return value, before running them.
2. Build `contacts.xml` yourself with 3 contacts (each with a phone and a meta tag), then run every SimpleXML/DOM/XPath example against your own file.
3. Convert one of your PHP arrays to JSON with `json_encode()`, save it to a `.json` file, then read it back with `json_decode()` both as object and as array — compare the two access styles.
4. Implement `printTree()` (section 10) yourself for a tree with at least 2 levels, and trace the order nodes get printed.
5. Draw the adjacency matrix AND adjacency list for a graph you make up, then write the PHP for both — this pairing is a common Part B question.
6. Redo the AJAX "showHint" example, but connect `gethint.php` to a small array of your own subject codes instead of names.