<?php

echo "<br><br><p>with SPL </p>";
$q = new SplStack();
$q[] = 1;
$q[] = 2;
$q[] = 3;
$q->push(4);
$q->push(5);
$q->rewind(); //Rewind iterator back to the start
while($q->valid()){
    echo $q->current(),", ";
    $q->next();
}
echo "<p>" . $q->pop() . "</p>";
$q->rewind();
while($q->valid()){
    echo $q->current(),", ";
    $q->next();
}

?>
