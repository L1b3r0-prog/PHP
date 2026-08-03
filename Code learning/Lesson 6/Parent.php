<?php
    class Fruit{
        public string $name;
        public string $colour;

        public function __construct(string $name, string $colour) {
            $this->name = $name;
            $this->colour = $colour;
        }

        function get_details() {
            echo "Name: " . $this->name . ". Colour: " . $this->colour . ".<br>";
        }

        function getDetails() {
            echo "<p>Name: {$this->name}. Colour: {$this->colour}.</p>";
        }
    }

    $apple = new Fruit("Apple", "Red");
    $apple->get_details();
    $apple->getDetails();

    $banana = new Fruit("Banana", "Yellow");
    $banana->get_details();
    $banana->getDetails();
?>