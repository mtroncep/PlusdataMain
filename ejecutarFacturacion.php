<!doctype html>
<?php
	session_start();
	require_once ("conexion.php");
    require_once("cargarDatosFormulario.php");

    $Desde = $_REQUEST['desde'];
    $Hasta = $_REQUEST['hasta'];

    $PeriodoFact = substr($Desde,6,4).substr($Desde,3,2);

    $sql = "call ctacteAvisosActivos('$Desde','$Hasta')";

    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_execute($stmt);
        //$ren=mysqli_fetch_array($ejec);
        mysqli_stmt_close($stmt);
    }

    echo ('<script type="text/javascript">'.
             "window.location.href = 'listarFacturacion.php?periodofact=$PeriodoFact&test=1'</script>");

    echo ('<script type="text/javascript">'.
             "window.location.href = 'facturacionExcel.php'</script>");

?>