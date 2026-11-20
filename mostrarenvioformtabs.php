<?php
$coma="";
$insertInto = "INSERT INTO fichas (";
$values = " VALUES (";
foreach($_POST as $campo => $valor){
  if (!empty($valor)){
	$insertInto .= $coma.$campo;
	$values .= $coma."'".$valor."'";
	$coma=",";
  }
}
$insertInto .= ")";
$values .= ")";
$insert=$insertInto . $values;
echo $insert;
?>