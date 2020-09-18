<?php

function newline(){
	echo "<br>\n";
}

$numArr = [1,3,4,5,6,8,9,15];

$result = [];
foreach($numArr as $num){

	if(($num % 2) == 0){
		array_push($result, $num);
	}
	echo "$num";
	newline();
}
var_dump($result);

?>
