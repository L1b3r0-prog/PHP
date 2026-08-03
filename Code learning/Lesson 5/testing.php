<?php
    class Mathematics{
        public function __construct(
            private string $name,
            private int $score
        ){}

        public function getName() : string {
            return $this->name;
        }

        public function getScore() : int {
            return $this->score;
        }

        public function setScore(int $newScore) : void {
            if ($newScore >= 0 && $newScore <= 20)
                $this->score = $newScore;
        }

        public function printInfo() : void {
            echo "<p>{$this->name} has a score of {$this->score}</p>";
        }
    }
    if (class_exists("Mathematics"))
        $s1 = new Mathematics("John", 15);
    else
        exit("<p>Failed</p>");
    $s1->printInfo();
    $s1->setScore(10);
    $s1->printInfo();
?>