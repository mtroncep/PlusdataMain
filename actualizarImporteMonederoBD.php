<!doctype html>
<?php
session_start();

require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

$CantDias = 0;
if (isset($_POST['cantDias'])){
    $CantDias=$_POST['cantDias'];
}
$movim=2;
$aux = Funciones::cargarMovimientoMonedero($con,$movim,$_SESSION['codPais'],$_SESSION['anunciante'],$CantDias);
$saldoActual=Funciones::obtenerRestoAvisos($con,$_SESSION['anunciante']) + $CantDias;
$actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$_SESSION['anunciante'],$saldoActual);
echo ('<script type="text/javascript">'."window.location.href = '".$_SESSION['menuinicio']."'</script>");
?>
<html>
<head>
    <title>Actualizar importe monedero</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h6>Entre</h6>
</body>
</html>
