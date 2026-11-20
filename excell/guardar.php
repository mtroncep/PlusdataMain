<?php
// guardar.php
include '../conexion.php';
// Recibir el JSON enviado desde el frontend
$input = file_get_contents('php://input');
$data = json_decode($input, true);
// Validar que haya cambios
if (!isset($data['cambios']) || !is_array($data['cambios'])) {
  http_response_code(400);
  echo json_encode(['error' => 'No se recibieron cambios válidos']);
  exit;
}
// Iniciar transacción
//$con->begin_transaction();

try {
  // Preparar la consulta
  $stmt = $con->prepare("UPDATE inmobiliarias SET tipoenvio = ? WHERE id = ?");

  foreach ($data['cambios'] as $cambio) {
    $id = $cambio['id'];
    $valor = $cambio['valor'];
    $fila = intval($cambio['fila']);

    $stmt->bind_param('si', $valor, $id);
    $stmt->execute();
  }

  // Confirmar cambios
//  $con->commit();
  echo json_encode(['status' => 'ok', 'modificados' => count($data['cambios'])]);

} catch (Exception $e) {
  // Revertir si hay error
  $mysqli->rollback();
  http_response_code(500);
  echo json_encode(['error' => 'Error al guardar: ' . $e->getMessage()]);
}
?>