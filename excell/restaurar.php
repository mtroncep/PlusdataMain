<?php
include '../conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

foreach ($data['restauradas'] as $fila) {
      $stmt = $con->prepare("INSERT INTO inmobiliarias (id, pais, tipoenvio, cpostal, ciudad, email, whatsapp, telefono, sorteo, nombre, actividad, web, direccion, otrotelefono, dato1, dato2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("isssssssssssssss",
        $fila['id'], $fila['pais'], $fila['tipoenvio'], $fila['cpostal'], $fila['ciudad'],
        $fila['email'], $fila['whatsapp'], $fila['telefono'], $fila['sorteo'], $fila['nombre'],
        $fila['actividad'], $fila['web'], $fila['direccion'], $fila['otrotelefono'],
        $fila['dato1'], $fila['dato2']
      );
  $stmt->execute();
}
?>
