<?php
	if (empty($_REQUEST['delete_id'])){
		$errors[] = "Id vacío.";
	} elseif (!empty($_REQUEST['delete_id']))
{
	require_once ("conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
    $id_cp=$_REQUEST['delete_id'];

    $cod_localidad=$_REQUEST['cod_localidad'];
	// DELETE FROM  database
    $sql = "DELETE FROM codigospostales WHERE cp='$id_cp' and cod_localidad='$cod_localidad'";
	$sqlVerEnPublic="SELECT * FROM `datosformulario` WHERE campo = 'valor_checkbox_09_32' and valor = '$id_cp'";
	$queryVer = mysqli_query($con,$sqlVerEnPublic);
	$numrows=mysqli_num_rows($queryVer);
    if ( $numrows > 0){
		echo '<script type="text/javascript">alert("CP ASOCIADO A PUBLICACION, NO PUEDE DARSE DE BAJA");</script>';
		echo '<script type="text/javascript">history.go(-1);</script>';
	    return;
	}

	$query = mysqli_query($con,$sql);
    // if product has been added successfully
    if ($query) {
		echo '<script type="text/javascript">alert("EL CP FUE DADO DE BAJA CORRECTAMENTE");</script>';
		echo '<script type="text/javascript">history.go(-1);</script>';
    } else {
		echo '<script type="text/javascript">alert("SE PRODUCJO UN ERROR, CP NO FUE DADO DE BAJA");</script>';
		echo '<script type="text/javascript">history.go(-1);</script>';
    }
	return;
}
?>
