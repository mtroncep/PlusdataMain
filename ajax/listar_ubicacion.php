<?php	
/* Connect To Database*/
require_once ("../conexion.php");
//echo ("Pais: ".$_REQUEST['pais']);
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$ubic_desc="";
	
	if (empty($_REQUEST["pais"])){
		$errores = "Pais está vacío.";
	} elseif (!empty($_REQUEST['pais'])){
		$pais=$_REQUEST['pais'];
//		echo ($pais);
		$query = mysqli_query($con,"SELECT DESCRIPCION FROM paises where COD_NUM=$pais");
		$row = mysqli_fetch_array($query);
		$ubic_desc = "Pa:".$row['DESCRIPCION'];
	}

	if (empty($_REQUEST["region"])){
		$errores = "Region está vacío.";
	} elseif (!empty($_REQUEST['region'])){
		$region=$_REQUEST['region'];
		$query = mysqli_query($con,"SELECT DESCRIPCION FROM regiones where COD_REGION=$region");
		$row = mysqli_fetch_array($query);
		$ubic_desc .= "/Re:".$row['DESCRIPCION'];
	}

	if (empty($_REQUEST["provincia"])){
		$errores = "Provincia está vacío.";
	} elseif (!empty($_REQUEST['provincia'])){
		$provincia=$_REQUEST['provincia'];
		$query = mysqli_query($con,"SELECT DESCRIPCION FROM provincias where COD_PROVINCIA=$provincia");
		$row = mysqli_fetch_array($query);
		$ubic_desc .= "/Pr:".$row['DESCRIPCION'];
	}

	if (empty($_REQUEST["comarca"])){
		$errores = "Comarca está vacío.";
	} elseif (!empty($_REQUEST['comarca'])){
		$comarca=$_REQUEST['comarca'];
		$query = mysqli_query($con,"SELECT DESCRIPCION FROM comarcas where COD_COMARCA=$comarca");
		$row = mysqli_fetch_array($query);
		$ubic_desc .= "/Co:".$row['DESCRIPCION'];
	}

	if (empty($_REQUEST["municipio"])){
		$errores = "Municipio está vacío.";
	} elseif (!empty($_REQUEST['municipio'])){
		$municipio=$_REQUEST['municipio'];
		$query = mysqli_query($con,"SELECT DESCRIPCION FROM municipiosxcomarca where COD_MUNICIPIO=$municipio");
		$row = mysqli_fetch_array($query);
		$ubic_desc .= "/Mu:".$row['DESCRIPCION'];
	}

	if (empty($_REQUEST["localidad"])){
		$errores = "Localidad está vacío.";
	} elseif (!empty($_REQUEST['localidad'])){
		$municipio=$_REQUEST['localidad'];
		$query = mysqli_query($con,"SELECT DESCRIPCION FROM localidades where COD_LOCALIDAD=$municipio");
		$row = mysqli_fetch_array($query);
		$ubic_desc .= "/Lo:".$row['DESCRIPCION'];
	}

	if (empty($_REQUEST["barrio"])){
		$errores = "Barrio está vacío.";
	} elseif (!empty($_REQUEST['barrio'])){
		$barrio=$_REQUEST['barrio'];
		$query = mysqli_query($con,"SELECT DESCRIPCION FROM barrios where COD_BARRIO=$barrio");
		$row = mysqli_fetch_array($query);
		$ubic_desc .= "/Ba:".$row['DESCRIPCION'];
	}

	if (empty($_REQUEST["nucleo"])){
		$errores = "Nucleo está vacío.";
	} elseif (!empty($_REQUEST['nucleo'])){
		$nucleo=$_REQUEST['nucleo'];
		$query = mysqli_query($con,"SELECT DESCRIPCION FROM nucleos where COD_NUCLEO=$nucleo");
		$row = mysqli_fetch_array($query);
		$ubic_desc .= "/Nu:".$row['DESCRIPCION'];
	}

	if (empty($_REQUEST["manzana"])){
		$errores = "Manzana está vacío.";
	} elseif (!empty($_REQUEST['manzana'])){
		$nucleo=$_REQUEST['nucleo'];
		$query = mysqli_query($con,"SELECT DESCRIPCION FROM manzanas where COD_MANZANA=$manzana");
		$row = mysqli_fetch_array($query);
		$ubic_desc .= "/Nu:".$row['DESCRIPCION'];
	}

	echo ($ubic_desc);
}
?>
