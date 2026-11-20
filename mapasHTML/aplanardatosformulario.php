// aplanardatosformulario.php
<?php
require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");

$log="N";
$select='select * from datosformulario order by nroanuncio, id';
$and="";
$aux=false;

$query = mysqli_query($con,$select);
$nroanuncioant=0;
$cod_pais=0;
$cod_localidad='';
$datosplanosanuncio='';

while($row = mysqli_fetch_array($query)){
    if ($row['nroanuncio'] <> $nroanuncioant){
        if ($nroanuncioant <> 0){
            // Grabar en nueva tabla...
            echo 'ANUNCIO   : '.$nroanuncioant.'<br>';
            echo 'PAIS      : '.$cod_pais.'<br>';
            echo 'LOCALIDAD : '.$cod_localidad.'<br>';
            echo 'CP        : '.$cp.'<br>';
            echo 'ANUNCIANTE: '.$nroanuncianteNT.'<br>';
            echo 'DATOS     : '.$datosplanosanuncio.'<br>'.'<br>';
            $insert = "insert into datosbusquedasformulario (nroanuncio,cod_pais,cod_localidad,cp,nroanunciante,datosasociados) values (".$nroanuncioant.",".$cod_pais.",".$cod_localidad.",".$cp.",".$nroanuncianteNT.",".'"'.$datosplanosanuncio.'"'.")";
            $aux=Funciones::actualizarAnuncioBD($insert, $log, $con);

            $datosplanosanuncio='';
            $cod_pais=0;
            $cod_localidad=0;
            $cp='';
            $nroanuncioant=$row['nroanuncio'];
            $nroanuncianteNT=$row['nroanunciante'];
        } else {
            $nroanuncioant=$row['nroanuncio'];
            $nroanuncianteNT=$row['nroanunciante'];
//            echo $row['nroanuncio'].' '.$row['campo'].' '.$row['valor'].' '.$row['codigoubicacion'].'<br>';

            $prefijo=substr($row['campo'],0,4);

            if (substr($row['campo'],0,5) === 'ubic_'){
                if ($row['campo'] === 'ubic_pais_id'){
                    $cod_pais = $row['codigoubicacion'];
                }

                if ($row['campo'] === 'ubic_localidad_id'){
                    $cod_localidad = $row['codigoubicacion'];
                }

                $datosplanosanuncio.=$row['campo'].",".substr($prefijo,0,1).",".$row['valor'].",".$row['codigoubicacion']."|";
            } else {
                $datosplanosanuncio.=$row['campo'].",".substr($prefijo,0,1).",".$row['valor']."|";
            }
        }
    } else {
        $prefijo=substr($row['campo'],0,4);

        if (substr($row['campo'],0,5) === 'ubic_'){
            if ($row['campo'] === 'ubic_localidad_id'){
                $cod_localidad = $row['codigoubicacion'];
            }
            if ($row['campo'] === 'ubic_pais_id'){
                $cod_pais = $row['codigoubicacion'];
            }
            $datosplanosanuncio.=$row['campo'].",".substr($prefijo,0,1).",".$row['valor'].",".$row['codigoubicacion']."|";
        } else {
            if ($row['campo'] === 'valor_checkbox_09_32'){
                $cp = $row['valor'];
            }
            $datosplanosanuncio.=$row['campo'].",".substr($prefijo,0,1).",".$row['valor']."|";
        }
        echo $row['nroanuncio'].' '.$row['campo'].' '.$row['valor'].' '.$row['codigoubicacion'].'<br>';
    }
}
// Grabar en nueva tabla...
echo 'ANUNCIO   : '.$nroanuncioant.'<br>';
echo 'PAIS      : '.$cod_pais.'<br>';
echo 'LOCALIDAD : '.$cod_localidad.'<br>';
echo 'CP        : '.$cp.'<br>';
echo 'ANUNCIANTE: '.$nroanuncianteNT.'<br>';
echo 'DATOS     : '.$datosplanosanuncio.'<br>';
$insert = "insert into datosbusquedasformulario (nroanuncio,cod_pais,cod_localidad,cp,nroanunciante,datosasociados) values (".$nroanuncioant.",".$cod_pais.",".$cod_localidad.",".$cp.",".$nroanuncianteNT.",".'"'.$datosplanosanuncio.'"'.")";
$aux=Funciones::actualizarAnuncioBD($insert, $log, $con);

return;

if($_POST)
	{
	if ($_SESSION['operacion'] == 'M'){
// Anuncios para publicacion
		$delete = "delete from datosformulario where nroanuncio = '".$_SESSION['anuncio']."' 
		and nroanunciante = ".$_SESSION['anunciante'];
		$aux=Funciones::actualizarAnuncioBD($delete, $log, $con);

// Anuncios para búsquedas
        $deletebusq = "delete from datosbusquedasformulario where nroanuncio = '".$_SESSION['anuncio']."' 
		and nroanunciante = ".$_SESSION['anunciante'];
		$aux=Funciones::actualizarAnuncioBD($deletebusq, $log, $con);
	}
	
    $datosplanosanuncio = '';
    
	foreach ($_POST as $clave=>$valor)
   		{
		if ($valor){
			if ($_SESSION['operacion'] == 'A' || $_SESSION['operacion'] == 'M'){
				$prefijo=substr($clave,0,4);
				if (substr($clave,0,5) === 'ubic_'){
					$insert = "insert into datosformulario (nroanuncio,campo,tipocampo,valor,codigoubicacion,nroanunciante) values ('".$_SESSION['anuncio']."','".$clave."', '".substr($prefijo,0,1)."',".'"'.buscarIdUbicacion($clave, $valor, $con).'"'.",'".$valor."', ".$_SESSION['anunciante'].')';
                    $datosplanosanuncio.=$clave.",".substr($prefijo,0,1).",".buscarIdUbicacion($clave, $valor, $con).",".$valor."|";
				}else{
					$insert = "insert into datosformulario (nroanuncio,campo,tipocampo,valor,nroanunciante) values ('".$_SESSION['anuncio']."','".$clave."', '".substr($prefijo,0,1)."', '".$valor."', ".$_SESSION['anunciante'].")";
                    $datosplanosanuncio.=$clave.",".substr($prefijo,0,1).",".$valor."|";
                }
				$aux=Funciones::actualizarAnuncioBD($insert, $log, $con);
			} else {
				$prefijo=substr($clave,0,4);
				if (substr($clave,0,4) == 'ubic'){
                    if ($clave === "ubic_pais_id"){
                        $select .= $and."(nroanuncio in (select nroanuncio from datosformulario where campo='$clave' and valor='".buscarIdUbicacion($clave, $valor, $con)."' group by nroanuncio))" ;
                        $and=" and ";
                    }
                    if ($clave === "ubic_localidad_id"){
                        $select .= $and."(nroanuncio in (select nroanuncio from datosformulario where campo='$clave' and valor='".buscarIdUbicacion($clave, $valor, $con)."' group by nroanuncio))" ;
                        $and=" and ";
                    }
				} else {
					if (substr($clave,0,5) <> 'valor'){
						$select .= $and."(nroanuncio in (select nroanuncio from datosformulario where campo='$clave' and valor='$valor' group by nroanuncio))" ;
						$and=" and ";
					} else {
                        if ($clave <> "valor_checkbox_09_32"){
                            $select .= $and."(nroanuncio in (select nroanuncio from datosformulario where campo='$clave' and valor <=$valor group by nroanuncio))" ;
                            $and=" and ";
                        } else {
                            $select .= $and."(nroanuncio in (select nroanuncio from datosformulario where campo='$clave' and valor = $valor group by nroanuncio))" ;
                            $and=" and ";
                            }
                        }
                    }
                }
            }
		}
//        echo($select."<br>"."<br>");
//        echo($datosplanosanuncio."<br>");
//        return;
		if ($_SESSION['operacion'] == 'A' || $_SESSION['operacion'] == 'M'){
// Insert en tabla datosbusquedasformulario
            $insertbusq = "insert into datosformulario (nroanuncio,campo,tipocampo,valor,codigoubicacion,nroanunciante) values ('".$_SESSION['anuncio']."','".$clave."', '".substr($prefijo,0,1)."',".'"'.buscarIdUbicacion($clave, $valor, $con).'"'.",'".$valor."', ".$_SESSION['anunciante'].')';
        }

		if (isset($_SESSION['operacionCP']) && $_SESSION['operacionCP'] == "A"){
            $borrarCP="delete from codigospostales where cp=".$_SESSION['cp']." and cod_localidad=".$cod_localidad;
            $aux=Funciones::actualizarAnuncioBD($borrarCP, $log, $con);
			$aux=Funciones::actualizarCP_POST($_SESSION['cp'], $cod_localidad, $_POST, $con);
		}

		if ($aux){
			$alertStr="<script>alert('Anuncio procesado correctamente Nro: ".$_SESSION['anuncio']."');</script>";
			echo ("<script>alert('Anuncio procesado correctamente Nro: ".$_SESSION['anuncio']."');</script>");
			if ($log == "N"){
				echo ('<script type="text/javascript">'.
					  "window.location.href = 'indexAnuncios.php'</script>");
				}
				echo ('<script type="text/javascript">'.
					  "window.location.href = 'indexAnuncios'.php'</script>");
			}
	
		if ($_SESSION['operacion'] == 'B' || $_SESSION['operacion'] == 'C'){
//            echo ($select);
//            return;
			$elejidos=Funciones::ejecutarBusquedaAnuncios($select, $con);
			$lineaAnuncio='';
			$anuncioAnt='';
			$anuncianteAnt='';
			$seleccionados []='';
			$tope = count($elejidos);
			$j=0;
			$coma='';
			if (!empty($elejidos)){
				for ($i = 0; $i < $tope; ++$i){
					if ($anuncioAnt <> $elejidos[$i]['nroanuncio']){
						if (!empty($lineaAnuncio)){
//							echo ($lineaAnuncio."<br>");
							$seleccionados[$j] = $lineaAnuncio;
							$j++;
						}
						$coma=', ';
						$anuncioAnt = $elejidos[$i]['nroanuncio'];
						$anuncianteAnt = $elejidos[$i]['nroanunciante'];
						$lineaAnuncio = $elejidos[$i]['nroanuncio'].'#'.$elejidos[$i]['nroanunciante'].'#'.$elejidos[$i]['valor'];
					} else {
						$lineaAnuncio .= $coma.$elejidos[$i]['valor'];
					}
				}
//				echo ($lineaAnuncio."<br>");
				$seleccionados[$j] = $lineaAnuncio;
//				var_dump($lineaAnuncio);
			} else {
				echo "SIN RESULTADOS";
			}
			$_SESSION['seleccion']=$seleccionados;
			echo ('<script type="text/javascript">'.
				 "window.location.href = 'procesarSeleccionados.php'</script>");
		}
	}

function buscarIdDetalle($detalle, $con){
	$count_query = mysqli_query($con,"SELECT count(*) AS numrows FROM detalles where detalle='$detalle'");
	if ($row = mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	else {echo mysqli_error($con);}

	$query = mysqli_query($con,"SELECT id FROM detalles where detalle='$detalle'");
	if ($numrows>0){
		$row = mysqli_fetch_array($query);
		return $row['id'];
	}else{
		return "";
	}
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
