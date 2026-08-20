<?php
    class Car {
        private $model = '';
        public function __construct($model){
            $this->model = $model;
        }

        public function hello(){
            return "I am a " . $this->model;
        }
    }

    class Sports extends Car {
        public function hello() {
            return "I am a overriden method";
        }
    }
    $car = new Car("Ferrari");
    $sports = new Sports("Jaguar");
    echo $sports->hello();
    echo $car->hello();
?>