<?php
session_start();

require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

/*
var_dump($_POST);
echo "<br>";
echo "<br>";
return;
*/

$deleteFicha = "DELETE FROM fichas WHERE idficha=".$_SESSION["idFicha"];
$resDelete=Funciones::actualizarFichaBD($deleteFicha,"N",$con);

$coma="";
$insertInto = "INSERT INTO fichas (";
$values = " VALUES (";

$anunciante="";
if (isset($_SESSION['anunciante'])){
	$anunciante=$_SESSION['anunciante'];
}

$infonoticias = isset($_POST['infonoticias']) ? 1 : 0;
$_POST['infonoticias'] = $infonoticias;

foreach($_POST as $campo => $valor){
	$insertInto .= $coma.$campo;
	$values .= $coma."'".$valor."'";
	$coma=",";
}
$insertInto .= ")";
$values .= ")";
$insert=$insertInto . $values;
//echo ("INSERT: ".$insert."<br>");
//return;
//error_log("INSERT FICHA: ".$insert);
error_log(print_r($_POST, true), 0);
$resultado=Funciones::actualizarFichaBD($insert,"N",$con);
//return;
//echo ("Resultado insert: ".$resultado."<br>");
if (isset($_SESSION['clientetipoQ']) && $_SESSION['clientetipoQ'] === 'Q'){
    $_SESSION['clientetipo'] = 'Q';
}


if (!empty($anunciante)){
	$actualizarFicha = mysqli_query($con, "UPDATE fichas SET anunciante=".$_SESSION['anunciante'].", tipoficha = 2, 
    clientetipo='".$_SESSION['clientetipo']."' where idficha=".$_SESSION['idFicha']);
	$actualizarAnunciante = mysqli_query($con, "UPDATE anunciantes SET ficha=".$_SESSION['idFicha']." where idanunciante=".$_SESSION['anunciante']);
}
if (!isset($_SESSION['registro'])) {
    echo ("<script>alert('Perfil procesado correctamente')</script>");
} else {
    unset($_SESSION['registro']);
    echo ("<script>alert('Su perfil se ha procesado correctamente.')</script>");
} 

if ($anunciante == ""){
	echo ('<script type="text/javascript">'."window.location.href = 'indexFichas.php'</script>");
} else {
	echo ('<script type="text/javascript">'."window.parent.location.href = '".$_SESSION['menuinicio']."'</script>");
}
?>