<?php
    session_start();
	require_once ("../conexion.php");
    require_once("../cargarDatosFormulario.php");
/*
    if (empty($_POST['anuncio_id'])){
		$errors[] = "Id anuncio vacío.";
        return;
	} else {
        if (!empty($_POST['anuncio_id']))
            $id_anuncio=intval($_POST['anuncio_id']);
    }
    */
    $anuncio_id=intval($_REQUEST['anuncio_id']);
    $update_inactivar = "update datosbusquedasformulario set estado = 0 WHERE nroanuncio ='$anuncio_id'";
    $update = mysqli_query($con,$update_inactivar);
    if ($update) {
        $messages[] = "¡El anuncio ha sido inactivado!";
    } else {
        $errors[] = "¡Lo sentimos, la inactivación falló!";
    }

    $marcarAvisoComoInactivo = "update ctacteavisos set fechainactivo = CURRENT_DATE, estado = 2 where 
    (cod_pais=".$_SESSION['codPais']." and nroanunciante=".$_SESSION['anunciante']." and nroanuncio=".$anuncio_id." and fechainactivo is NULL)";
    $marcarInactivo = mysqli_query($con,$marcarAvisoComoInactivo);

    $sqlLecturaAnuncio="Select DATEDIFF(date(fechainactivo), date(fechaactivacion))+1 'Dias' from ctacteavisos where 
    cod_pais= ".$_SESSION['codPais']." and nroanunciante= ".$_SESSION['anunciante']." and nroanuncio= ".$anuncio_id;
//    echo $sqlLecturaAnuncio;
//    return;
    $anuncioLeido = mysqli_query($con,$sqlLecturaAnuncio);
    $row=mysqli_fetch_array($anuncioLeido);

//    $restanAvisos=Funciones::obtenerRestoAvisos($con,$_SESSION['anunciante']) - $row['Dias'];
//    $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$_SESSION['anunciante'],$restanAvisos);

	if (isset($errors)){
	?>
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> 
            <?php
                foreach ($errors as $error) {
                        echo $error;
                    }
                ?>
    </div>
    <?php
        }
    if (isset($messages)){
        ?>
        <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>¡Bien hecho!</strong>
                <?php
                    foreach ($messages as $message) {
                            echo $message;
                        }
                    ?>
        </div>
        <?php
    }
    echo '<script type="text/javascript">history.go(-1);</script>';
?>
