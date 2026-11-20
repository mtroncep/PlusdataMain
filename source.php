<?php

$file = file('http://localhost');
foreach($file as $linenum => $line){
	echo "Line #{$linenum} ".htmlspecialchars($line).' ';
}
?>
