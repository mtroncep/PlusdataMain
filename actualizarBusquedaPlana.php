<?php
require_once("conexion.php");
require_once("cargarDatosFormulario.php");

$nroanuncio_post="176,177,178,181,185,197,198,199,209,210,211,212,215,216";

$log="N";
$select="select * from datosformulario where nroanuncio in ( $nroanuncio_post ) order by nroanuncio, id";
$and="";
$aux=false;

$query = mysqli_query($con,$select);
$nroanuncioant=0;
$cod_pais=0;
$cod_region=0;
$cod_provincia=0;
$cod_comarca=0;
$cod_municipio=0;
$cod_localidad='';
$cod_barrio=0;
$cod_nucleo=0;
$cod_manzana=0;
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
            $delete = "delete from datosbusquedasformulario where nroanuncio = $nroanuncioant";
            $insert = "insert into datosbusquedasformulario (nroanuncio,cod_pais,cod_localidad,cod_region,cod_provincia,cod_comarca,cod_municipio,cod_barrio,cod_nucleo,cod_manzana,cp,nroanunciante,datosasociados) values (".$nroanuncioant.",".$cod_pais.",".$cod_localidad.",".$cod_region.",".$cod_provincia.",".$cod_comarca.",".$cod_municipio.",".$cod_barrio.",".$cod_nucleo.",".$cod_manzana.",".$cp.",".$nroanuncianteNT.",".'"'.$datosplanosanuncio.'"'.")";
            $aux=Funciones::actualizarAnuncioBD($delete, $log, $con);
            $aux=Funciones::actualizarAnuncioBD($insert, $log, $con);

            $datosplanosanuncio='';
            $cod_pais=0;
            $cod_region=0;
            $cod_provincia=0;
            $cod_comarca=0;
            $cod_municipio=0;
            $cod_localidad='';
            $cod_barrio=0;
            $cod_nucleo=0;
            $cod_manzana=0;
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

                if ($row['campo'] === 'ubic_region_id'){
                    $cod_region = $row['codigoubicacion'];
                }

                if ($row['campo'] === 'ubic_provincia_id'){
                    $cod_provincia = $row['codigoubicacion'];
                }

                if ($row['campo'] === 'ubic_comarca_id'){
                    $cod_comarca = $row['codigoubicacion'];
                }

                if ($row['campo'] === 'ubic_municipio_id'){
                    $cod_municipio = $row['codigoubicacion'];
                }

                if ($row['campo'] === 'ubic_localidad_id'){
                    $cod_localidad = $row['codigoubicacion'];
                }

                if ($row['campo'] === 'ubic_barrio_id'){
                    $cod_barrio = $row['codigoubicacion'];
                }

                if ($row['campo'] === 'ubic_nucleo_id'){
                    $cod_nucleo = $row['codigoubicacion'];
                }

                if ($row['campo'] === 'ubic_manzana_id'){
                    $cod_manzana = $row['codigoubicacion'];
                }

                $datosplanosanuncio.=$row['campo'].",".substr($prefijo,0,1).",".$row['valor'].",".$row['codigoubicacion']."|";
            } else {
                $datosplanosanuncio.=$row['campo'].",".substr($prefijo,0,1).",".$row['valor']."|";
            }
        }
    } else {
        $prefijo=substr($row['campo'],0,4);

        if (substr($row['campo'],0,5) === 'ubic_'){
            if ($row['campo'] === 'ubic_pais_id'){
                $cod_pais = $row['codigoubicacion'];
            }

            if ($row['campo'] === 'ubic_region_id'){
                $cod_region = $row['codigoubicacion'];
            }

            if ($row['campo'] === 'ubic_provincia_id'){
                $cod_provincia = $row['codigoubicacion'];
            }

            if ($row['campo'] === 'ubic_comarca_id'){
                $cod_comarca = $row['codigoubicacion'];
            }

            if ($row['campo'] === 'ubic_municipio_id'){
                $cod_municipio = $row['codigoubicacion'];
            }

            if ($row['campo'] === 'ubic_localidad_id'){
                $cod_localidad = $row['codigoubicacion'];
            }

            if ($row['campo'] === 'ubic_barrio_id'){
                $cod_barrio = $row['codigoubicacion'];
            }

            if ($row['campo'] === 'ubic_nucleo_id'){
                $cod_nucleo = $row['codigoubicacion'];
            }

            if ($row['campo'] === 'ubic_manzana_id'){
                $cod_manzana = $row['codigoubicacion'];
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

$select1 = "select * from datosbusquedasformulario where nroanuncio = $nroanuncioant";
$query1 = mysqli_query($con,$select1);
$row1 = mysqli_fetch_array($query1);
$datosplanosanuncio1=$row1['datosasociados'];

$delete = "delete from datosbusquedasformulario where nroanuncio = $nroanuncioant";

$insert = "insert into datosbusquedasformulario (nroanuncio,cod_pais,cod_localidad,cod_region,cod_provincia,cod_comarca,cod_municipio,cod_barrio,cod_nucleo,cod_manzana,cp,nroanunciante,datosasociados) values (".$nroanuncioant.",".$cod_pais.",".$cod_localidad.",".$cod_region.",".$cod_provincia.",".$cod_comarca.",".$cod_municipio.",".$cod_barrio.",".$cod_nucleo.",".$cod_manzana.",".$cp.",".$nroanuncianteNT.",".'"'.$datosplanosanuncio1.'"'.")";

//echo 'Delete: '.$delete.'<br>';
//echo 'Insert: '.$insert.'<br>';
$aux=Funciones::actualizarAnuncioBD($delete, $log, $con);
$aux=Funciones::actualizarAnuncioBD($insert, $log, $con);
?>
