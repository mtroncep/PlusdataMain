<?php
require_once ("conexion.php");

$data = array();
$telmovil=$_REQUEST['telMovil'];
$count=mysqli_query($con,"SELECT * FROM fichas WHERE telmovil='$telmovil'");
$row=mysqli_fetch_array($count);
if(mysqli_num_rows($count) > 0){
    $data['status'] = '1';
} else {
    $data['status'] = '0';
}
echo json_encode($data);
return;
?>
