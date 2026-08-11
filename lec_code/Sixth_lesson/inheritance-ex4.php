<?php

class Car {

    protected $model="";

    public function __construct($model)
    {
        $this->model = $model;
    }
    
    public function setModel($model)
    {
    $this->model = $model;
    }

    public function hello()
    {
    return "I am a <i>" . $this->model . "</i><br />";
    }
}
 
 
//The child class inherits the code from the parent class
class SportsCar extends Car{
    private $style = 'fast and furious';
    
    public function __construct($model, $style)
    {
        parent::__construct($model);
        $this->style = $style;
    }

    public function setStyle($style)
    {
      $this->style = $style;
    }
    
    public function driveItWithStyle()
    {
    return 'I am ' . $this->model . '! Drive me ' . '<i>' .  $this->style . '</i> <br />';
    }
    
    public function hello()
    {
      return "I am <i>overriden</i> method, this is the parent one -> ". parent::hello();
    }

}
     
$car1 = new Car('Honda');
echo $car1->hello();
    
echo "------<br>";
$car2 = new SportsCar('Mazda', 'nice and slow');
echo $car2->driveItWithStyle();
echo $car2->hello();

?>

