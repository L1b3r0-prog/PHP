# ISIT307 — Study Guide: Lecture 5.1 + 6.2
## PHP Object-Oriented Programming — Part 1 & Part 2

---

# PART A — OOP FUNDAMENTALS (Lecture 5.1)

## Key OOP Concepts

object, encapsulation, association, aggregation, delegation, composition, dynamic binding, polymorphism, inheritance, hierarchical objects, abstract classes

## Introduction

- **Object-oriented programming (OOP)** merges related variables and functions into a single interface
- An **object** is programming code and data treated as an individual unit/component
- Object orientedness = co-operative problem solving through objects communicating with one another

## Classes

- A **class** is a blueprint/template defining the structure and behaviour of objects
  - Makes complex programs easier to manage
  - Hides information users of the object don't need to know about
  - Makes code easier to reuse
- **Properties** — variables representing the data/state of an object
- **Methods** — functions representing behaviour, operate on properties

```php
class ClassName {
    properties
    methods
}
```
- Class names conventionally start with an uppercase letter
- Good practice: store classes in separate files, `require()` / `require_once()` them in

### Access Specifiers (Visibility Modifiers)

| Specifier | Access |
|---|---|
| `public` | anywhere, inside or outside the class |
| `private` | only within the class |
| `protected` | within the class **and** child classes |

- General rule: properties should be `private`/`protected`; methods needed outside the class should be `public`

### `$this`

- Refers to the object itself; used inside methods to access its own properties/methods
```php
$this->property
$this->method()
```

## Objects

- An object is an **instance** of a class, created with the `new` operator
```php
$objectName = new ClassName();
```
- `->` (member selection notation) accesses properties/methods

### Useful Functions
- `get_class()` — retrieves the class name of an object
- `class_exists()` — determines if a class exists
- `instanceof` — determines whether an object is an instance of a given class

```php
$myObj = new MyClass();
echo get_class($myObj);
if ($myObj instanceof MyClass) {…}
if (class_exists("MyClass")) {…}
```

### Example
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
```

## Constructors

- A special function called automatically when an object is instantiated
- Cannot share the class's own name (not recognized as a constructor in PHP 8)

```php
class BankAccount {
    private float $balance;
    public function __construct(float $balance) {
        $this->balance = $balance;
    }
}
```

### Constructor Property Promotion (PHP 8.0)
- Allows declaring and initializing properties directly in constructor parameters

```php
class BankAccount {
    public function __construct(private float $balance) {}
}
```
```php
class BankAccount {
    private string $name;
    public function __construct(private float $balance, string $name) {
        $this->name = $name;
    }
}
```

## Destructors

- Called when the object is destroyed — frees up allocated resources
- Commonly triggered when: a script ends, or `unset()` is called on the object

```php
function __destruct(){...}
```

## Accessor / Mutator Functions (Get / Set)

- Public methods used to retrieve (**accessor/get**) or modify (**mutator/set**) property values

```php
class BankAccount {
    private float $balance=0;
    function __construct(float $bal=0) { $this->balance = $bal; }
    public function setBalance(float $newValue) {
        if ($newValue > 0) $this->balance = $newValue;
    }
    public function getBalance():float { return $this->balance; }
}
$checking = new BankAccount();
$checking->setBalance(100);
echo $checking->getBalance();
```

## Magic Functions `__get()` / `__set()`

- `__set()` — called when writing to a protected/private property
- `__get()` — called when reading a protected/private property

```php
class MyClass {
    private int $myP;
    function __get($name) { return $this->$name; }
    function __set($name, $value) { $this->$name = $value; }
}
$myV = new MyClass();
$myV->myP = 5;
echo $myV->myP;
```

---

# PART B — OOP: INHERITANCE, POLYMORPHISM & MORE (Lecture 6.2)

## Inheritance

- Reduces code duplication: write code once in the parent class, reuse it in child classes
- Declared using the `extends` keyword

```php
class ParentClass {
    // parent's code
}
class Child extends ParentClass {
    // child can use the parent's code
}
```

- The child class can use all **non-private** members (methods/properties) inherited from the parent
- The child can also have its own properties/methods
- The parent class **cannot** use the child class's code
- A property/method that needs to be accessed from both parent and child (but not made fully public) should be declared **protected**

### Overriding
- A child class can **override** a parent method by redefining it with different code
- The `final` prefix on a parent method **prevents** it from being overridden by a child
- If the child does **not** define a constructor/destructor, it inherits the parent's
- If the child **does** define a constructor/destructor, the parent's is **not** called implicitly — must explicitly call `parent::__construct()` or `parent::__destruct()`

### Inheritance Examples

**(1) Simple inheritance, no overriding:**
```php
class Car {
    private $model="";
    public function setModel($model) { $this->model = $model; }
    public function hello() { return "I am a <i>" . $this->model . "</i><br />"; }
}
class SportsCar extends Car { /* No code in the child class */ }

$sportsCar1 = new SportsCar();
$sportsCar1->setModel('Jaguar');
echo $sportsCar1->hello();
```

**(2) Adding new properties/methods in the child:**
```php
class SportsCar extends Car {
    private $style = 'fast and furious';
    public function driveItWithStyle() {
        return $this->hello() . 'Drive me ' . '<i>' . $this->style . '</i>';
    }
}
```

**(3) Overriding a parent method:**
```php
class SportsCar extends Car {
    private $style = 'fast and furious';
    public function driveItWithStyle() {
        return 'I am ' . $this->model . '! Drive me ' . '<i>' . $this->style . '</i>';
    }
    public function hello() { return "I am a <i>overriden</i> method <br />"; }
}
```
*(Note: for example (3) to access `$this->model` directly, the parent's `$model` property needs to be `protected` rather than `private`.)*

**(4) Calling the parent constructor:**
```php
class SportsCar extends Car {
    private $style = 'fast and furious';
    public function __construct($model, $style) {
        parent::__construct($model);
        $this->style = $style;
    }
}
```

## Polymorphism

- The ability of a class instance to behave as if it were an instance of another class in its inheritance tree (usually an ancestor)
- Using the same function/method name to produce different responses in base-class vs. derived-class objects
- Example: an `area()` method defined differently in a `Figure` class vs. a `Circle` (derived) class

## Abstract Classes

- **Abstract methods** declare only the method signature — no implementation
- A class containing at least one abstract method **must** itself be declared abstract
- Abstract classes **cannot be instantiated**
- When a child class inherits from an abstract class:
  - **All** abstract methods must be defined by the child
  - Visibility must match or be **less restrictive** (e.g. `protected` in parent → `protected` or `public` in child, but not `private`)
  - Method signatures must match

```php
abstract class AbstractClass {
    abstract protected function getValue();
    abstract protected function prefixValue($prefix);

    // Common (non-abstract) method
    public function printOut() {
        print $this->getValue() . "\n";
    }
}
```

## Interfaces

- Lets you specify **only the public methods** a class must implement, without implementation detail
- The "next level of abstraction" — a class **implements** the interface
- Declared with the `interface` keyword; only function prototypes are declared

```php
interface MyInterfaceName {
    public function methodA();
    public function methodB();
}

class MyClassName implements MyInterfaceName {
    public function methodA() { /* implementation */ }
    public function methodB() { /* implementation */ }
}
```

## Traits

- Groups together functionality reusable across **multiple classes**
- Unlike interfaces, a Trait **includes the implementation** of its methods
- A Trait **cannot be instantiated** on its own
- Reduces limitations of single inheritance — enables horizontal composition of behaviour without requiring inheritance

```php
trait myTrait {
    function getTemp() { /* implementation */ }
    function setTemp() { /* implementation */ }
}

class MyClassA extends SomeClass {
    use myTrait;
}
class MyClassB extends OtherClass {
    use myTrait;
}
```

## Serializing Objects

- **Serialization** converts an object into a string for storage/reuse (stores both properties and, conceptually, its structure)

```php
$SavedAccount = serialize($checking);      // serialize
$checking = unserialize($savedAccount);    // unserialize
```

- Can be stored in a session variable to use between scripts:
```php
session_start();
$_SESSION['SavedAccount'] = serialize($checking);
```

### Serialization Magic Functions
- `__sleep()` — specifies which properties to serialize (if omitted, **all** properties are serialized)
```php
function __sleep() {
    $serialVars = array('balance');
    return $serialVars;
}
```
- `__wakeup()` — called on `unserialize()`, performs constructor-like initialization (restore DB/file connections, re-initialize properties, etc.)

## Model-View-Controller (MVC)

An architectural pattern used in creating web applications:

| Component | Responsibility |
|---|---|
| **Model** | business logic and application's data |
| **View** | user interface and interaction |
| **Controller** | intermediary between User, Model, and View |

---

## Quick Self-Check Questions

- What's the difference between `public`, `private`, and `protected`?
- What is Constructor Property Promotion, and which PHP version introduced it?
- Why does a child class need to call `parent::__construct()` explicitly?
- What does the `final` keyword do when applied to a method?
- What's the difference between an **abstract class** and an **interface**?
- What's the difference between a **Trait** and an **Interface**?
- Why can't abstract classes or traits be instantiated directly?
- What is the purpose of `__sleep()` and `__wakeup()`?
- What are the three roles in the MVC pattern?
- Give an example of polymorphism using an `area()` method.