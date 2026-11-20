 <?php
	session_start();
	require_once ("../conexion.php");
    require_once("../cargarDatosFormulario.php");

    if (empty($_GET['anuncio_id'])){
		$errors[] = "Id anuncio vacío.";
        return;
	} else {
        if (!empty($_GET['anuncio_id']))
            $anuncio_id=intval($_GET['anuncio_id']);
    }

    $restanAvisos=Funciones::obtenerRestoAvisos($con,$_SESSION['anunciante']);
    if ($restanAvisos == 0){
        $alertStr="<script>alert('AVISO: No tiene avisos disponibles para publicar...');</script>";
        echo $alertStr;
        echo '<script type="text/javascript">history.go(-1);</script>';
    } else {
        $sqlLecturaAnuncio="Select estado, fechainactivo from ctacteavisos where 
        (cod_pais=".$_SESSION['codPais']." and nroanunciante=".$_SESSION['anunciante']." and nroanuncio=".$anuncio_id.")";
        $anuncioLeido = mysqli_query($con,$sqlLecturaAnuncio);
        $row=mysqli_fetch_array($anuncioLeido);
        
        $fecha_actual = strtotime(date("d-m-Y"));
        $fecha_entrada = strtotime($row['fechainactivo']);
/*        
        echo "Act: ".$fecha_actual.'<br>';
        echo "Ent: ".$fecha_entrada.'<br>';
        if ($fecha_actual <> $fecha_entrada){
            echo "Fechas distintas".'<br>';
        }
        
        if (!empty($fecha_entrada)){
            echo "Entrada con fecha".'<br>';
        } else {
            echo "Entrada vacia".'<br>';
        }
        
        return;
 */     
        if ($fecha_actual <> $fecha_entrada){
            $anuncio_id=intval($_REQUEST['anuncio_id']);
            $update_activar = "update datosbusquedasformulario set estado = 1 WHERE nroanuncio ='$anuncio_id'";
            $update = mysqli_query($con,$update_activar);
        } else {
            $alertStr="<script>alert('ATENCION: Operación en el mismno día. Puede hacerla desde mañana.');</script>";
            echo $alertStr;
            echo '<script type="text/javascript">history.go(-1);</script>';
            return;
        }
        
        if ($row['estado'] == 0) {
            $marcarAvisoComoActivo = "update ctacteavisos set fechaactivacion = CURRENT_DATE, estado=1 where 
            (cod_pais=".$_SESSION['codPais']." and nroanunciante=".$_SESSION['anunciante']." and nroanuncio=".$anuncio_id.")";
            $marcaractivo = mysqli_query($con,$marcarAvisoComoActivo);
        } else {
            if ($row['estado'] == 1){
                if ($row['fechainactivo'] <> ""){
                    $cargarAvisoComoActivo = "insert into ctacteavisos (cod_pais,nroanunciante,nroanuncio,fechaactivacion,fechainactivo,estado) values 
                    (".$_SESSION['codPais'].",".$_SESSION['anunciante'].",".$anuncio_id.",CURRENT_DATE, NULL, 1)";
                    $ingresaractivo = mysqli_query($con,$cargarAvisoComoActivo);
                } else {
                    $marcarAvisoComoActivo = "update ctacteavisos set fechaactivacion = CURRENT_DATE where 
                    (cod_pais=".$_SESSION['codPais']." and nroanunciante=".$_SESSION['anunciante']." and nroanuncio=".$anuncio_id.")";
                    $marcaractivo = mysqli_query($con,$marcarAvisoComoActivo);
                }
            } else {
                $cargarAvisoComoActivo = "insert into ctacteavisos (cod_pais,nroanunciante,nroanuncio,fechaactivacion,fechainactivo,estado) values 
                (".$_SESSION['codPais'].",".$_SESSION['anunciante'].",".$anuncio_id.",CURRENT_DATE, NULL, 1)";
                $ingresaractivo = mysqli_query($con,$cargarAvisoComoActivo);
                    
            }
        }
    }
/*
    $cargarAvisoComoActivo = "insert into ctacteavisos (cod_pais,nroanunciante,nroanuncio,fechaactivacion,fechainactivo,estado) values 
    (".$_SESSION['codPais'].",".$_SESSION['anunciante'].",".$anuncio_id.",CURRENT_DATE, NULL, 1)";
    $ingresaractivo = mysqli_query($con,$cargarAvisoComoActivo);
    */
//    echo $cargarAvisoComoActivo;
//    return;
/*
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
    */
    echo '<script type="text/javascript">history.go(-1);</script>';
?>
