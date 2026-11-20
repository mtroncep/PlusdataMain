<?php
include '../conexion.php';

$resultado = $con->query("SELECT * FROM inmobiliarias");
$datos = [];

while ($fila = $resultado->fetch_assoc()) {
    $datos[] = [$fila['id'], $fila['pais'], $fila['tipoenvio'], $fila['cpostal'], $fila['ciudad'], $fila['email'], $fila['whatsapp'],
                $fila['telefono'], $fila['sorteo'], $fila['nombre'], $fila['actividad'], $fila['web'], $fila['direccion'],
                $fila['otrotelefono'], $fila['dato1'], $fila['dato2']
               ];
}

echo json_encode($datos);
?>
