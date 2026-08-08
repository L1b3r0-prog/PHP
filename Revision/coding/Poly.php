<?php
    class Car {
        private $model="";
        public function setModel($model) {
            $this->model = $model;
        }
        public function hello() {
            return "I am a <i>". $this->model . "</i><br>";
        }
    }

    class SportsCar extends Car {

    }

    $sportCar1 = new SportsCar();
    $sportCar1->setModel("Jagaur");
    echo $sportCar1->hello();
?>