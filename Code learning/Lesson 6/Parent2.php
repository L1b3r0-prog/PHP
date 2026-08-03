<?php
    class Fruit {
    public $name;
    public $color;

    public function __construct($name, $color) {
        $this->name = $name;
        $this->color = $color;
    }

    protected function intro() {
        echo "The fruit is $this->name and the color is $this->color.";
    }
    }
    // Try to call all three methods from outside class
    $strawberry = new Strawberry("Strawberry", "red");  // OK. __construct() is public
    $strawberry->message(); // OK. message() is public
    $strawberry->intro(); // ERROR. intro() is protected
?>