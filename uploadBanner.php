<?php
    include ("conexion.php");
    session_start();
// Subir imagen al servidor
/*
    if (is_null($_FILES["file"]["tmp_name"]) ){
        echo ("<script>alert('El archivo que quiere subir es muy pesado.')</script>");
        return;
    }
*/    
    $imagenEntrada = $_FILES["file"]["name"] = str_replace(" ", "_", $_FILES['file']['name']);
    $pesoBytes = filesize($_FILES["file"]["tmp_name"]);
    $pesoKBytes = $pesoBytes / 1024;
    $pesoMBytes = $pesoKBytes / 1024;
    $pesoMBytes = round($pesoMBytes,1);

    const VALOR_LIMITE = 1848576.;

    
    error_log("2.- Peso en Bytes: ".$pesoBytes);
    error_log("3.- Peso en Mb: ".$pesoMBytes);
    error_log("4.- Tipo variable: ".gettype($pesoMBytes));
    error_log("5.- Tipo constante: ".gettype(VALOR_LIMITE));
    error_log("6.- Valor constante: ".VALOR_LIMITE);

    if ($pesoBytes > (double) VALOR_LIMITE){
        error_log("#### Imagen a subir, supera os dos Mb. ####");
        return;
    } else {
        error_log("#### Imagen a subir, tiene el tamaño correcto ####");
    }

    move_uploaded_file($_FILES["file"]["tmp_name"], "banners/".$_SESSION['codPais'].$_SESSION['anunciante']."_1_".$_FILES['file']['name']);

	$imagen = "banners/".$_SESSION['codPais'].$_SESSION['anunciante']."_1_".$_FILES['file']['name'];
    $imagenSC = $_SESSION['codPais'].$_SESSION['anunciante']."_1_".$_FILES['file']['name'];
	$imagenNueva = "banners/".$_SESSION['codPais'].$_SESSION['anunciante']."_1_".$_FILES['file']['name']; //Nueva imagen
	$nAncho = 340; //Nuevo ancho
	$nAlto = 75;  //Nuevo alto
	//Creamos una nueva imagen a partir del fichero inicial
	
	$imagen = imagecreatefromjpeg($imagen);
	//Obtenemos el tamaño 
	$x = imagesx($imagen);
	$y = imagesy($imagen);
	
	//Validamos los tamaños y calculamos la relación de aspecto

    if ($x >= $y) {
		$nAncho = $nAncho;
		$nAlto = $nAncho * $y / $x;
		} else {
		$nAlto = $nAlto;
		$nAncho = $x / $y * $nAlto;
	}

	// Crear una nueva imagen, copia y cambia el tamaño de la imagen
	$img = imagecreatetruecolor($nAncho, $nAlto);
	imagecopyresampled($img, $imagen, 0, 0, 0, 0, floor($nAncho), floor($nAlto), $x, $y);
	
	//Creamos el archivo jpg
	imagejpeg($img, $imagenNueva);

// Cargar imagen a base de datos
    $pais = $_SESSION['codPais'];
    $nroAnunciante = $_SESSION['anunciante'];
    $maxSecuImg = 0;

    $imagenOriginal = "SELECT urlbanner from banners where pais=$pais and nroanunciante=$nroAnunciante and secanunciante=1";
    $leerImagenOrig=mysqli_query($con,$imagenOriginal);
    $imagenVieja = $leerImagenOrig->fetch_assoc();
    $imagenBorrar = "banners/".$imagenVieja['urlbanner'];
    $maxSecuencia = "SELECT max(secanunciante) as maximo from banners where pais=$pais and nroanunciante=$nroAnunciante";
    $maxSecuenciaImg=mysqli_query($con,$maxSecuencia);

    if ($maxSecuenciaImg->num_rows == 1) {
        $maxSecuenciaStr = $maxSecuenciaImg->fetch_assoc();
        if (is_null($maxSecuenciaStr['maximo'])){
            $maxSecuImg = 0;
            $insert="insert into banners (pais, nroanunciante, secanunciante, urlbanner, estado) VALUES 
                ($pais, $nroAnunciante, 1, "."'".$imagenSC."'".", 1)";
            $update=mysqli_query($con,$insert);
        } else {
            $maxSecuImg = $maxSecuenciaStr['maximo'];
            $update = "update banners set urlbanner='".$imagenSC."' where pais=".$pais." and nroanunciante=".$nroAnunciante." and secanunciante=1";
            $updateE=mysqli_query($con,$update);
//            unlink("'".$imagenBorrar."'");
            unlink($imagenBorrar);
        }
        echo 'window.parent.postMessage("Proceso terminado", "*");';
        ++$maxSecuImg;
    }
?>