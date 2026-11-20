<?php
session_start();

require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

$anunciante="";
if (isset($_SESSION['anunciante'])){
	$anunciante=$_SESSION['anunciante'];
}

$sqlMax = "SELECT MAX(idficha) as maximo from fichas";
$maxCodNum = mysqli_query($con,$sqlMax);
if ($maxCodNum->num_rows == 1) {
	$maxCodNumStr = $maxCodNum->fetch_assoc();
	++$maxCodNumStr['maximo'];
	$idFicha=$maxCodNumStr['maximo'];
}

$coma=",";
$insertInto = "INSERT INTO fichas (id,";
$values = " VALUES (null,";

$insertInto .= "idficha";
$values .= "'".$idFicha."'";

foreach($_POST as $campo => $valor){
	$insertInto .= $coma.$campo;
	$values .= $coma."'".$valor."'";
	$coma=",";
}

//echo var_dump($_POST).'<br>';

$insertInto .= ", anunciante".")";
$values .= ",".$anunciante.")";
$insert=$insertInto . $values;
//echo($insert);
//return;
$resultado=Funciones::actualizarFichaBD($insert,"N",$con);

if (!empty($anunciante)){
    $clientetipo=$_SESSION['tipocliReg'];
    $infonoticias=$_SESSION['infonoticias'];

    $anuncienteinmob=0;
    if (isset($_SESSION['nroInmob'])){
        $anuncienteinmob=$_SESSION['nroInmob'];
    }

    $updateficha="UPDATE fichas SET tipoficha = 2,
    anuncianteinmob=".$anuncienteinmob.", clientetipo='".$clientetipo."' where idficha=".$idFicha;
    //echo $updateficha.'<br>';
    //return;
	$actualizarFicha = mysqli_query($con, $updateficha);
//	$actualizarFicha = mysqli_query($con, "UPDATE fichas SET anunciante=".$anunciante.", tipoficha = 2,
//    anuncianteinmob=".$anuncienteinmob.", clientetipo=".$clientetipo." where idficha=".$idFicha);
    $actualizarAnunciante = mysqli_query($con, "UPDATE anunciantes SET ficha=".$idFicha." where idanunciante=".$anunciante);
//    echo $updateficha;
//    return;
}

echo ("<script>alert('Ficha procesada correctamente')</script>");

if ($anunciante == ""){
	echo ('<script type="text/javascript">'."window.location.href = 'indexFichas.php'</script>");
} else {
	echo '<script type="text/javascript">alert("REGISTRO CORRECTO POR FAVOR, INGRESE NUEVAMENTE");</script>';
    echo ('<script type="text/javascript">'."window.location.href = '"."index.php"."'</script>");
}
?>