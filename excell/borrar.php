<?php
include '../conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

foreach ($data['eliminados'] as $fila) {
  $id = intval($fila['id']);
  error_log("id a borrar: ".$id,0);
  $stmt = $con->prepare("DELETE FROM inmobiliarias WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}
?>