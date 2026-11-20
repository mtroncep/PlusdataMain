<?php
session_start();
require_once ("conexion.php");

$data = array();
$email=$_REQUEST['emailReg'];
$count=mysqli_query($con,"SELECT idanunciante FROM anunciantes WHERE email='$email'");
$row=mysqli_fetch_array($count);
if(mysqli_num_rows($count) > 0){
    $data['status'] = '1';
} else {
    $data['status'] = '0';
}
echo json_encode($data);
return;
?>
