<?php
$archivo_php = "pruebaDetalles.php";

// 1. Leer el contenido completo del archivo .php en una sola cadena
$contenido_php = file_get_contents($archivo_php);

if ($contenido_php === false) {
    die("Error al leer el archivo: $archivo_php");
}

// 2. Usar una expresión regular para encontrar todas las cadenas entre comillas dobles
// El patrón busca: " (comillas) seguido de cualquier cosa que no sea comilla (.*?) hasta la siguiente "
preg_match_all('/"(.*?)"/', $contenido_php, $coincidencias);

// $coincidencias[0] contiene las cadenas completas (con las comillas)
// $coincidencias[1] contiene solo el contenido dentro de las comillas
$strings_extraidos = $coincidencias[1];

// 3. Mostrar los resultados
echo "Strings encontrados:\n";
foreach ($strings_extraidos as $str) {
    echo "- " . $str . "\n";
}

echo "\n--- Ejemplo con explode (si necesitas dividir por un separador) ---\n";
// Ejemplo: Si quieres dividir por un marcador específico, como un punto y coma (;)
$lineas = explode(";", $contenido_php);
echo "Contenido dividido por ';':\n";
foreach ($lineas as $linea) {
    // Puedes limpiar espacios en blanco al principio/final con trim()
    // echo "- " . trim($linea) . "\n";
}
?>
