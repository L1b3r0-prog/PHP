<?php
    class Fruit {
        public $name;
        public $colour;

        public function __construct($name, $colour) {
            $this->name = $name;
            $this->colour = $colour;
        }

        protected function intro() {
            echo "This fruit is $this->name and the colour is $this->colour";
        }
    }

    class Strawberry extends Fruit {
        public function message() {
            echo "Am i a fruit or berry";
            $this->intro();
        }
    }

    $strawberry = new Strawberry("Strawberry", "red");
    $strawberry->message();
?>