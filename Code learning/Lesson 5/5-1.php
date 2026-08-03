<?php
    class Student{
        private string $name;
        private float $gpa = 0.0;

        public function __construct(string $name, float $gpa) {
            $this->name = $name;
            $this->gpa = $gpa;
        }

        public function getName() : string {
            return $this->name;
        }

        public function setGpa(float $newGpa) : void {
            if ($newGpa >= 0 && $newGpa <= 4.0)
                $this->gpa = $newGpa;
        }

        public function getGpa() : float {
            return $this->gpa;
        }

        public function printInfo() : void {
            echo "<p>{$this->name} has GPA {$this->gpa}</p>";
        }

        public function __destruct() {
            echo "<p>{$this->name}'s object destroyed</p>";
        }
    }

    if (class_exists("Student"))
        $s1 = new Student("Jasper", 3.5);
    else
        exit("<p>Class not available</p>");

    $s1->setGpa(3.9);
    $s1->printInfo();
    echo get_class($s1);
    var_dump($s1 instanceof Student);
?>