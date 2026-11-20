<!doctype html>
<?php
session_start();

require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

$importe = "";
if (isset($_POST['importe'])){
    $importe=$_POST['importe'];
}
$valorDia= floatval( Funciones::obtenerValorParametro($con,'COSTO_DIARIO'));
$CantDias = intval(intval( $importe) / $valorDia );

?>
<html>
<head>
    <title>Cargar importe monedero</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<form id="loginform" method="post" action="actualizarImporteMonederoBD.php">
    <div>
        Importe:
        <input type="text" name="importe" id="importe" value="<?php echo $importe?>" />
        Dias:
        <input type="text" name="cantDias" id="cantDias" value="<?php echo $CantDias?>"/>

        <input type="submit" name="loginBtn" id="loginBtn" value="Confirmar compra" />
    </div>
</form>
</body>
</html>
