<?php
// this is for a question pool array
    $qtspool = array( 
                    1=>array("qts" => "today is?", "ans" => "Mon"),
                    2=>array("qts" => "this year is?", "ans" => "2026"),
                    3=>array("qts" => "this module is?", "ans" => "307"),
                    4=>array("qts" => "what is missing 1245?", "ans" => "3"),
                    5=>array("qts" => "what is missing abde?", "ans" => "C"));


    $qtspick_key = array_rand($qtspool, 2);
    var_dump($qtspick_key);
    echo"<br>";

    $pickqts = array();
    $i = 0;
    foreach ($qtspick_key as $k) 
    { $pickqts[$i] = $qtspool[$k]; $i++; }

    foreach ($pickqts as $k=>$v)
    { echo "key=".$k. " qts=".$v['qts']. " ans=".$v['ans']. "<br>"; }
?>

<br>
<form>
    <?php foreach ($pickqts as $k => $v){ ?>
        <?php echo $v['qts'];?>
        <input type="text" name="ans">
        <?php echo $v['ans']; ?>
        <br>
    <?php } ?>
</form>