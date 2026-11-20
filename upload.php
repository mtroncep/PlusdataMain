<?php
    include ("conexion.php");
    session_start();
// Subir imagen al servidor
    $_FILES['file']['name'] = str_replace(" ", "_", $_FILES['file']['name']);
    move_uploaded_file($_FILES["file"]["tmp_name"], "fotosPublicaciones/".$_SESSION['codPais'].$_SESSION['anuncio']."_".$_FILES['file']['name']);

	$imagen = "fotosPublicaciones/".$_SESSION['codPais'].$_SESSION['anuncio']."_".$_FILES['file']['name'];
    $imagenSC = $_SESSION['codPais'].$_SESSION['anuncio']."_".$_FILES['file']['name'];
	$imagenNueva = "fotosPublicaciones/".$_SESSION['codPais'].$_SESSION['anuncio']."_".$_FILES['file']['name']; //Nueva imagen
	$nAncho = 850; //Nuevo ancho
	$nAlto = 900;  //Nuevo alto
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
    $nroAnuncio = $_SESSION['anuncio'];
    $maxSecuImg = 0;

    $maxSecuencia = "SELECT max(secanuncio) as maximo from slider where pais=$pais and nroanuncio=$nroAnuncio";
    $maxSecuenciaImg=mysqli_query($con,$maxSecuencia);

    if ($maxSecuenciaImg->num_rows == 1) {
        $maxSecuenciaStr = $maxSecuenciaImg->fetch_assoc();
        if (is_null($maxSecuenciaStr['maximo'])){
            $maxSecuImg = 0;
        } else {
            $maxSecuImg = $maxSecuenciaStr['maximo'];
        }
        ++$maxSecuImg;
        $insert="insert into slider (pais, nroanuncio, secanuncio, urlimagen, estado) VALUES 
            ($pais, $nroAnuncio, $maxSecuImg, "."'".$imagenSC."'".", 1)";
        $update=mysqli_query($con,$insert);
    }
        
        
//        $mysqli->query("insert into paises(COD_NUM, DESCRIPCION) value (\"$maxCodNumStr[maximo]\", \"$_POST[name]\")");

?>