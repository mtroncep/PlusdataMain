<?php
// Rutas de archivos
$ruta_imagen_original = 'imagenes/banner_1254.jpg';
echo ("Imagen: ".$ruta_imagen_original).'<br>';
$ruta_guardado = 'imagenes/banner_1254_340x100.jpg';

// Dimensiones deseadas
$nuevo_ancho = 340;
$nuevo_alto = 100;

// 1. Obtener dimensiones originales
list($ancho_orig, $alto_orig) = getimagesize($ruta_imagen_original);
echo ("AnchoOrig: ".$ancho_orig." - AltoOrig: ".$alto_orig).'<br>';

// 2. Crear una imagen en blanco con las nuevas dimensiones
$canvas = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);

// 3. Cargar la imagen original según su tipo (soporta JPG, PNG, GIF)
$tipo = exif_imagetype($ruta_imagen_original);
if ($tipo == IMAGETYPE_JPEG) {
    echo ('Es JPG!!!!').'<br>';
    $imagen_orig = imagecreatefromjpeg($ruta_imagen_original);
} elseif ($tipo == IMAGETYPE_PNG) {
    $imagen_orig = imagecreatefrompng($ruta_imagen_original);
    // Preservar transparencia PNG
    imagealphablending($canvas, false);
    imagesavealpha($canvas, true);
} elseif ($tipo == IMAGETYPE_GIF) {
    $imagen_orig = imagecreatefromgif($ruta_imagen_original);
}

//echo ('ImagenOrig: '.$imagen_orig).'<br>';
// 4. Redimensionar/Copiar la imagen original a la nueva imagen
echo ('Antes de imagecopyresampled!!!!').'<br>';
imagecopyresampled(
    $canvas, $imagen_orig,
    0, 0, 0, 0,
    $nuevo_ancho, $nuevo_alto,
    $ancho_orig, $alto_orig
);
echo ('Antes de imagejpge!!!!').'<br>';

// 5. Guardar la imagen (calidad 90)
imagejpeg($canvas, $ruta_guardado, 100);

// 6. Liberar memoria
//imagedestroy($canvas);
//imagedestroy($imagen_orig);

echo "Imagen redimensionada correctamente.";
?>
