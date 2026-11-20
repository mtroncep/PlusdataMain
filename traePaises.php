<?php

/**
 * Obtiene todos los paises de la base de datos
 */

require 'funciones.php';
$paises = funciones::listaPaises();

if ($paises) {
	$datos["estado"] = 1;
    $datos["paises"] = $paises;
    //print_r($paises);
    } else {
      print json_encode(array(
            "estado" => 2,
            "mensaje" => "Ha ocurrido un error"
      ));
    }
?>
