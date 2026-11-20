<!DOCTYPE html>
<?php
	session_start();
    require_once ("conexion.php");
    require_once("cargarDatosFormulario.php");
    $log="N";
?>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>
<body>
    <?php
        $borrar="delete from banners where pais=".$_SESSION['codPais']." and nroanunciante=".$_SESSION['anunciante']." and secanunciante=1";
        $aux=Funciones::actualizarAnuncioBD($borrar, $log, $con);
    ?>
    <?php
        echo '<script type="text/javascript">history.go(-1);</script>';
    ?>
</body>
</html>