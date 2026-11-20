<?php
require_once ("conexion.php");

$data = array();
$inmob=$_REQUEST['nroInmob'];
$count=mysqli_query($con,"SELECT * FROM fichas WHERE anunciante='$inmob'"." and clientetipo='R'");
$row=mysqli_fetch_array($count);
if(mysqli_num_rows($count) > 0){
    $data['status'] = '1';
    $data['result'] = $row['nombrerz'];
} else {
    $data['status'] = '0';
    $data['result'] = 'Inmobiliaria no existe';
    $data['nroInmob'] = '';
}
echo json_encode($data);
return;
?>
