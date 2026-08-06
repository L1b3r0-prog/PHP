<?php
    class Herp {
        private string $name;
        private string $number;

        public function __construct(string $name, string $number) {
            $this->name = $name;
            $this->number = $number;
        }

        public function getName() {
            return $this->name;
        }

        public function getNumber() {
            return $this->number;
        }

        public function setNumber($setNumber) {
            if ($setNumber >=0 && $setNumber <= 20 ){
                return $this->number = $setNumber;
            }
        }

        public function getInfo() {
            echo "$this->name has a score of $this->number";
        }
    }

    $s1 = new Herp("Ben", 30);
    $s1->getInfo();
    $s1->setNumber(10);
    $s1->getInfo();
?>