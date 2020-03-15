<?php 


echo ucfirst("EscadaCavalcante");
echo "<br>";

$name = 'MinhaMaoTaDoendo';
$name	= preg_replace('/([a-z])([A-Z])/',"$1_$2",$name);
$name	= strtolower($name);
echo $name;
?>