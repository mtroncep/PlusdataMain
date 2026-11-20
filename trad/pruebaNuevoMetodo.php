<?php

function traducir($texto, $de = 'es', $a = 'ca') {
  $url = "https://api.mymemory.translated.net/get?q=" . urlencode($texto) . "&langpair=$de|$a";
  $respuesta = file_get_contents($url);
  $datos = json_decode($respuesta, true);
  return $datos['responseData']['translatedText'];
}

$archivoEntrada = 'original.txt';
$archivoSalida = 'traducido.txt';

$lineas = file($archivoEntrada, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$salida = fopen($archivoSalida, 'w');

foreach ($lineas as $linea) {
  $traducido = traducir($linea, "es", "ca");
  echo ($linea." - ".$traducido) . "<br>";
  fwrite($salida, $traducido . "\n");
//  sleep(1);
}

fclose($salida);
echo "Traducción completada.";

?>