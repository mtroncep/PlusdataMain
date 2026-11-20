<?php
session_start();

$data = array();
$idioma=$_REQUEST['idiomapermanente'];
$_SESSION['idiomapermanente']=$idioma;
//error_log("Idioma permanente session: ".$idioma,0);
setcookie("idioma", $idioma, time() + (30 * 24 * 60 * 60), "/");
$data['status'] = '1';
echo json_encode($data);
return;
?>
