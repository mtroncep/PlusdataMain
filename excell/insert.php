<?php
include '../conexion.php';
if ($con->connect_error) {
  die("Conexión fallida: " . $con->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);

// Vinculamos los parámetros
error_log("Id INSERT: ".$data['id'],0);
error_log("Id INSERT: ".$data['email'],0);
$stmt = $con->prepare("INSERT INTO inmobiliarias (id, pais, tipoenvio, cpostal, ciudad, email, whatsapp, telefono, sorteo, nombre, actividad, web, direccion, otrotelefono, dato1, dato2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssssssssssss",
$data['id'], $data['pais'], $data['tipoenvio'], $data['cpostal'], $data['ciudad'],
$data['email'], $data['whatsapp'], $data['telefono'], $data['sorteo'], $data['nombre'],
$data['actividad'], $data['web'], $data['direccion'], $data['otrotelefono'],
$data['dato1'], $data['dato2']);
// Ejecutamos
$stmt->execute();

echo "OK";
?>
