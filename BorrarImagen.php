<!DOCTYPE html>
<?php
	session_start();
    require_once ("conexion.php");
    require_once("cargarDatosFormulario.php");
    $imagenBorrar = $_GET['imagen'];
    $log="N";
?>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>
<body>
    <?php
        $borrar="delete from slider where urlimagen='".$imagenBorrar."'";
        $aux=Funciones::actualizarAnuncioBD($borrar, $log, $con);
    ?>
    <?php
        echo '<script type="text/javascript">history.go(-1);</script>';
    ?>
</body>
</html>