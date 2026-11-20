<?php
// Función para agregar marca de agua proporcionalmente y centrada
function addImageWatermark($SourceFile, $WaterMark, $DestinationFile=NULL, $opacity) {
    $main_img = $SourceFile; 
    $watermark_img = $WaterMark; 
    $padding = 5; 
    $proporcion=0.5;
    $opacity = $opacity;
    
    $watermark = imagecreatefrompng($watermark_img); 
    $image = imagecreatefromjpeg($main_img);
    
    if(!$image || !$watermark) die("Error: La imagen o la marca de agua no puede ser cargada");
    
    $watermark_size = getimagesize($watermark_img);
    $watermark_width = $watermark_size[0]; 
    $watermark_height = $watermark_size[1]; 
    $image_size = getimagesize($main_img); 
    /*
    echo "<br>Ancho Marca : ".$watermark_size[0];
    echo "<br>Alto Marca  : ".$watermark_size[1];
    echo "<br>";
    echo "<br>Ancho Imagen: ".$image_size[0];
    echo "<br>Alto Imagen : ".$image_size[1];
    return;
    */
    // Calcular las nuevas dimensiones de la marca de agua
    $anchoMarcaNuevo = $watermark_width * $proporcion;
    $altoMarcaNuevo = ($anchoMarcaNuevo / $watermark_width) * $watermark_height;

    // Crear una nueva imagen para la marca de agua escalada
    $marcaDeAguaEscalada = imagecreatetruecolor($anchoMarcaNuevo, $altoMarcaNuevo);
    imagesavealpha($marcaDeAguaEscalada, true);
    //$transparente = imagecolorallocatealpha($marcaDeAguaEscalada, 0, 0, 0, 127);
    //imagefill($marcaDeAguaEscalada, 0, 0, $transparente);
    imagecopyresampled($marcaDeAguaEscalada, $watermark, 0, 0, 0, 0, $anchoMarcaNuevo, $altoMarcaNuevo, $watermark_width, $watermark_height);
    
    $dest_x = ($image_size[0] - $anchoMarcaNuevo - $padding) / 2; 
    $dest_y = ($image_size[1] - $altoMarcaNuevo - $padding) / 2;
    
    imagecopymerge($image, $marcaDeAguaEscalada, $dest_x, $dest_y, 0, 0, $anchoMarcaNuevo, $altoMarcaNuevo, $opacity);
        
    if ($DestinationFile<>'') {
        imagejpeg($image, $DestinationFile, 100); 
    } else {
        header('Content-Type: image/jpeg');
        imagejpeg($image);
    }
    
    imagedestroy($image); 
    imagedestroy($watermark); 
}

// Ejemplo de uso
//$rutaImagenOriginal = 'ruta/a/tu/imagen.jpg';
//$rutaMarcaDeAgua = 'ruta/a/tu/marca-de-agua.png';
//$rutaImagenSalida = 'ruta/a/tu/imagen-con-marca-de-agua.jpg';

$SourceFile = $_REQUEST['imagen'];
//$SourceFile = 'C:\Users\marce\Documents\HMILocal\imagenes\fondoAlicante.jpg';

$WaterMark = 'imagenes/MarcaPlusdataTexto.png';
//$WaterMark = 'C:\Users\marce\Documents\HMILocal\imagenes\house.png';

//$DestinationFile = 'C:\Users\marce\Documents\HMILocal\imagenes\fondoMarbellaFondoAgua.jpg';
$DestinationFile = '';
$opacidad=10;

addImageWatermark($SourceFile, $WaterMark, $DestinationFile, $opacidad);
?>
