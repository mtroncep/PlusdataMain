<?php
session_start();
/* Connect To Database*/
require_once ("conexion.php");
require_once("cargarDatosFormulario.php");

$log="N";
$select='select * from datosformulario where ';
$and="";
$aux=false;

if($_POST)
	{

/*	if ($_SESSION['operacion'] == 'M'){
		$delete = "delete from datosformulario where nroanuncio = '".$_SESSION['anuncio']."' 
		and nroanunciante = ".$_SESSION['anunciante'];
		$aux=Funciones::actualizarAnuncioBD($delete, $log, $con);
	}
*/
	foreach ($_POST as $clave=>$valor)
   		{
//		if ($valor){
		echo("Clave: ".$clave."  Valor: ".$valor."</br>");
/*		
			if ($_SESSION['operacion'] == 'A' || $_SESSION['operacion'] == 'M'){
				$prefijo=substr($clave,0,4);
				if (substr($clave,0,4) == 'ubic'){
					$insert = "insert into datosformulario (nroanuncio,campo,tipocampo,valor,codigoubicacion,nroanunciante) values ('".$_SESSION['anuncio']."','".$clave."', '".substr($prefijo,0,1)."',".'"'.buscarIdUbicacion($clave, $valor, $con).'"'.",'".$valor."', ".$_SESSION['anunciante'].')';
				}else{
					$insert = "insert into datosformulario (nroanuncio,campo,tipocampo,valor,nroanunciante) values ('".$_SESSION['anuncio']."','".$clave."', '".substr($prefijo,0,1)."', '".$valor."', ".$_SESSION['anunciante'].")";
				}
				$aux=Funciones::actualizarAnuncioBD($insert, $log, $con);
//				}
			} else {
				$prefijo=substr($clave,0,4);
				if (substr($clave,0,4) == 'ubic'){
//					$select .= $and."(campo='".$clave."' and valor='".buscarIdUbicacion($clave, $valor, $con)."')";
					$select .= $and."(nroanuncio in (select nroanuncio from datosformulario where campo='$clave' and valor='".buscarIdUbicacion($clave, $valor, $con)."' group by nroanuncio))" ;
					$and=" and ";
				} else {
					if (substr($clave,0,5) <> 'valor'){
						$select .= $and."(nroanuncio in (select nroanuncio from datosformulario where campo='$clave' and valor='$valor' group by nroanuncio))" ;
						$and=" and ";
					} else {
						$select .= $and."(nroanuncio in (select nroanuncio from datosformulario where campo='$clave' and valor <=$valor group by nroanuncio))" ;
						$and=" and ";
					}
				}
			}
*/   	}
	}
?>
