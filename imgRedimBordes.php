<?php

$ruta_imagen_original = 'imagenes/AsiSomos1.jpg';
$ruta_guardado = 'imagenes/AsiSomos1_Bordes_340x75.jpg';

$nuevo_ancho = 340;
$nuevo_alto = 75;

// Obtener tamaño original
list($ancho_orig, $alto_orig) = getimagesize($ruta_imagen_original);

// Crear lienzo final
$canvas = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);

// Color de borde (blanco)
$blanco = imagecolorallocate($canvas, 255, 255, 255);
imagefill($canvas, 0, 0, $blanco);

// Detectar tipo
$tipo = exif_imagetype($ruta_imagen_original);
if ($tipo == IMAGETYPE_JPEG) {
    $imagen_orig = imagecreatefromjpeg($ruta_imagen_original);
} elseif ($tipo == IMAGETYPE_PNG) {
    $imagen_orig = imagecreatefrompng($ruta_imagen_original);
} elseif ($tipo == IMAGETYPE_GIF) {
    $imagen_orig = imagecreatefromgif($ruta_imagen_original);
}

// Calcular proporción
$ratio_orig = $ancho_orig / $alto_orig;
$ratio_nuevo = $nuevo_ancho / $nuevo_alto;

if ($ratio_orig > $ratio_nuevo) {
    // Imagen más ancha → ajusta ancho
    $ancho_final = $nuevo_ancho;
    $alto_final = intval($nuevo_ancho / $ratio_orig);
} else {
    // Imagen más alta → ajusta alto
    $alto_final = $nuevo_alto;
    $ancho_final = intval($nuevo_alto * $ratio_orig);
}

// Calcular posición centrada
$x = intval(($nuevo_ancho - $ancho_final) / 2);
$y = intval(($nuevo_alto - $alto_final) / 2);

// Copiar redimensionada al centro del canvas
imagecopyresampled(
    $canvas, $imagen_orig,
    $x, $y, 0, 0,
    $ancho_final, $alto_final,
    $ancho_orig, $alto_orig
);

// Guardar
imagejpeg($canvas, $ruta_guardado, 90);

imagedestroy($canvas);
imagedestroy($imagen_orig);

echo "Imagen generada con bordes correctamente.";

?>
