<?php
session_start();
/* Connect To Database*/
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

$cod_localidad=$_SESSION['cod_localidad'];
$cod_descripcion=$_SESSION['cod_descripcion'];

$pais=$_SESSION['paisCP'];
$region=$_SESSION['regionCP'];
$municipio=$_SESSION['municipioCP'];
$comarca=$_SESSION['comarcaCP'];
$provincia=$_SESSION['provinciaCP'];

$cpNuevo = "";
if (isset($_POST['cpnuevo'])){
    $cpNuevo=$_POST['cpnuevo'];
}

$cpAnterior=$_SESSION['cp'];
$log="N";
$select='select * from codigospostales where ';
$and="";
$aux=false;
//echo("Operacion: ".$_SESSION['operacion']."<br>");
if (empty($cpNuevo))
{
	if($_POST)
	{
	if ($_SESSION['operacion'] == 'M')
	{
	$auxAct=actualizarCP($_SESSION['cp'], $cod_localidad, $log, $con);
//    return;
    if ($auxAct)
		{
		echo ("<script>alert('Código Postal procesado correctamente CP: ".$_SESSION['cp']."');</script>");
		$pagina="indexCP.php?cod_localidad=".$cod_localidad."&cod_descripcion=".$cod_descripcion."&pais=".$pais."&region=".$region.
			"&provincia=".$provincia."&comarca=".$comarca."&municipio=".$municipio;

		echo ("<script type='text/javascript'>window.location.href = ".'"'.$pagina.'"'."</script>");
		}
	return;
	}
	$aux=Funciones::actualizarCP_POST($_SESSION['cp'], $cod_localidad, $_POST, $con);
	if ($aux)
		{
		echo ("<script>alert('Código Postal procesado correctamente CP: ".$_SESSION['cp']."');</script>");
		$pagina="indexCP.php?cod_localidad=".$cod_localidad."&cod_descripcion=".$cod_descripcion."&pais=".$pais."&region=".$region.
			"&provincia=".$provincia."&comarca=".$comarca."&municipio=".$municipio;
		echo ("<script type='text/javascript'>window.location.href = ".'"'.$pagina.'"'."</script>");
		}
	}
} else {
    $updateCP="update codigospostales set cp='".$cpNuevo."' where cp='".$_SESSION['cp']."' and cod_localidad = ".$cod_localidad;
	$updateValor="update codigospostales set valor='".$cpNuevo."' where cp='".$cpNuevo."' and campo = 'valor_checkbox_09_32'"." and cod_localidad = ".$cod_localidad;
    $updateCPAnuncio="update datosformulario set valor = '".$cpNuevo."' where campo = 'valor_checkbox_09_32' and valor = '".$cpAnterior."'";
	$aux=Funciones::actualizarAnuncioBD($updateCP, $log, $con);
	$aux=Funciones::actualizarAnuncioBD($updateValor, $log, $con);
	$aux=Funciones::actualizarAnuncioBD($updateCPAnuncio, $log, $con);
	if ($aux)
	{
	echo ("<script>alert('Código Postal cambiado correctamente: ".$_SESSION['cp']." a ".$cpNuevo."');</script>");
	$pagina="indexCP.php?cod_localidad=".$cod_localidad."&cod_descripcion=".$cod_descripcion."&pais=".$pais."&region=".$region.
			"&provincia=".$provincia."&comarca=".$comarca."&municipio=".$municipio;
	echo ("<script type='text/javascript'>window.location.href = ".'"'.$pagina.'"'."</script>");
	}
}

function actualizarCP($cp, $cod_localidad, $log, $con){
	$query = mysqli_query($con,"SELECT cp, cod_localidad FROM codigospostales where cp='".$cp."' and cod_localidad=".$cod_localidad." group by cp, cod_localidad order by cp, cod_localidad");
	while($row = mysqli_fetch_array($query)){	
		$cp_code=$row['cp'];
		$cp_cod_localidad=$row['cod_localidad'];
		$delete = "delete from codigospostales where cp = '".$cp_code."'"." and cod_localidad=".$cp_cod_localidad;
		$aux=Funciones::actualizarAnuncioBD($delete, $log, $con);

		foreach ($_POST as $clave=>$valor)
		{
			if ($valor)
			{
			$prefijo=substr($clave,0,4);
			$insert = "insert into codigospostales (cp,campo,tipocampo,valor,cod_localidad) values ('".$cp_code."','".$clave."', '".substr($prefijo,0,1)."', '".$valor."', ".$cp_cod_localidad.")";
			$aux=Funciones::actualizarAnuncioBD($insert, $log, $con);
			}
		}
	}

// Actualización de anuncios si hubiera alguno...
    $selanuncios = "SELECT nroanuncio, nroanunciante FROM datosformulario where campo='valor_checkbox_09_32' and valor='".$cp."'";
	$queryanuncio = mysqli_query($con, $selanuncios);
    
	while($row = mysqli_fetch_array($queryanuncio)){
//        echo("Anuncio: ".$row['nroanuncio']."<br>");
        $anunciante = $row['nroanunciante'];
        $anuncio = $row['nroanuncio'];

// Obtener codigo de localidad...
        $selectLoc="SELECT codigoubicacion FROM datosformulario WHERE nroanuncio=".$row['nroanuncio']." and campo = 'ubic_localidad_id' and codigoubicacion=".$cod_localidad;
        $queryLoc = mysqli_query($con, $selectLoc);
        $rowLoc = mysqli_fetch_array($queryLoc);
		$cp_cod_localidad=$rowLoc['codigoubicacion'];

        if ($rowLoc){
            $delete = "delete from datosformulario where nroanuncio=".$row['nroanuncio']." and campo like '%_09_%'";
            $aux=Funciones::actualizarAnuncioBD($delete, $log, $con);
            $datosPlanos09="";

            foreach ($_POST as $clave=>$valor)
            {
                if ($valor)
                {
                $prefijo=substr($clave,0,4);
                $insert = "insert into datosformulario (nroanuncio,campo,tipocampo,valor,nroanunciante) values     ('".$row['nroanuncio']."','".$clave."', '".substr($prefijo,0,1)."', '".$valor."', ".$anunciante.")";
                $datosPlanos09.=$clave.",".substr($prefijo,0,1).",".$valor."|";
//            echo $datosplanos09."<br>";
                $aux=Funciones::actualizarAnuncioBD($insert, $log, $con);
                }
            }
            $anuncioAux="'".$row['nroanuncio']."'";
            $responseActDatosPlanos=Funciones::actualizarBusquedaPlana($anuncioAux, $con);
        }
//      $delete = "delete from datosformulario where nroanuncio=".$row['nroanuncio']." and campo like '%_09_%'";
//		$aux=Funciones::actualizarAnuncioBD($delete, $log, $con);
/*
        $datosPlanos09="";
        
        foreach ($_POST as $clave=>$valor)
		{
			if ($valor)
			{
			$prefijo=substr($clave,0,4);
			$insert = "insert into datosformulario (nroanuncio,campo,tipocampo,valor,nroanunciante) values     ('".$row['nroanuncio']."','".$clave."', '".substr($prefijo,0,1)."', '".$valor."', ".$anunciante.")";
            $datosPlanos09.=$clave.",".substr($prefijo,0,1).",".$valor."|";
//            echo $datosplanos09."<br>";
            $aux=Funciones::actualizarAnuncioBD($insert, $log, $con);
			}
		}
        */
    //
    //  Actualizar datos de busqueda plana...
    //
//    $responseActDatosPlanos=Funciones::actualizarBusquedaPlana($anuncio, $con);
        /*
        echo $datosPlanos09."<br>"."<br>";
        $selectDatosAsoc = "select * from datosbusquedasformulario where nroanuncio=".$anuncio;
        $querySelDatosAsoc = mysqli_query($con, $selectDatosAsoc);
        $datosAsocOrigRow = mysqli_fetch_array($querySelDatosAsoc);
		$datosAsocOrig = $datosAsocOrigRow['datosasociados'];
        echo $datosAsocOrig."<br>"."<br>";
    */
    }
	return true;
}

function buscarIdUbicacion($clave, $valor, $con){
	if(strpos($clave, 'pais') != false){
		return obtenerUbicacion('paises', 'COD_NUM', $valor, $con);
	}
	if(strpos($clave, 'region') != false){
		return obtenerUbicacion('regiones', 'COD_REGION', $valor, $con);
	}
	if(strpos($clave, 'provincia') != false){
		return obtenerUbicacion('provincias', 'COD_PROVINCIA', $valor, $con);
	}
	if(strpos($clave, 'comarca') != false){
		return obtenerUbicacion('comarcas', 'COD_COMARCA', $valor, $con);
	}
	if(strpos($clave, 'municipio') != false){
		return obtenerUbicacion('municipiosxcomarca', 'COD_MUNICIPIO', $valor, $con);
	}
	if(strpos($clave, 'localidad') != false){
		return obtenerUbicacion('localidades', 'COD_LOCALIDAD', $valor, $con);
	}
	if(strpos($clave, 'barrio') != false){
		return obtenerUbicacion('barrios', 'COD_BARRIO', $valor, $con);
	}
	if(strpos($clave, 'nucleo') != false){
		return obtenerUbicacion('nucleos', 'COD_NUCLEO', $valor, $con);
	}
	if(strpos($clave, 'manzana') != false){
		return obtenerUbicacion('manzanas', 'COD_MANZANA', $valor, $con);
	}
}

function obtenerUbicacion($tabla, $clave, $valor, $con){
	$query = mysqli_query($con,"SELECT DESCRIPCION FROM $tabla where $clave=$valor");	
	$row = mysqli_fetch_array($query);
	return $row['DESCRIPCION'];		
}

?>
