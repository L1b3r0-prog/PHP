<?php
$xmlDoc = new DOMDocument();
$xmlDoc->load("list.xml");

echo $xmlDoc->saveXML();
    
echo "<br /> <br />";
    
$x = $xmlDoc->documentElement;
foreach ($x->childNodes AS $item) {
    echo $item->nodeName . " = " . $item->nodeValue . "<br />";
}
?>
