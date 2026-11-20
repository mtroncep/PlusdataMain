<!doctype html>
<?php
	session_start();
	require_once ("conexion.php");
    require_once("cargarDatosFormulario.php");

    mysqli_autocommit($con, FALSE);
        
    $PeriodoFact = $_REQUEST['periodofact'];
    $PeriodoFactMostrar = substr($PeriodoFact,4,2).'/'.substr($PeriodoFact,0,4);

    $test=$_REQUEST['test'];

    $sql = "SELECT * FROM detallefacturacion where periodofacturacion='$PeriodoFact';";
    $resultado = $con->query($sql);
    $linea = array();
    while( $rows = mysqli_fetch_array($resultado) ) {
    $linea[] = $rows;
    }
    //mysqli_close($con);

    ?>
    <style>
        table {
          table-layout: fixed;
          width: 40%;
          border-collapse: collapse;
          border: 3px solid purple;
        }
        
    </style>
    <body>
    <div class='container'>
    <h2 align="center">Informe Mensual de Publicaciones</h2>
    <h1 align="center">Período: <?php echo $PeriodoFactMostrar ?></h1>
    <div class='well-sm col-sm-12'>
        <div class='btn-group pull-right'>
        <form action="facturacionExcel.php" method='post'>
            <div align="center">
                <button type='submit' id='export_data' name='export_data' value='Export to excel' class='btn btn-info'>Exportar a Excel</button>
            </div>
        </form>
        </div>
    </div>
    <table id='' class='table table-striped table-bordered' border="1" align="center">
    <tr>
        <th width="20">País</th>
        <th width="20">Anunciante</th>
        <th width="20">Anuncio</th>
        <th width="20">Desde</th>
        <th width="20">Hasta</th>
        <th width="20">Dias</th>
        <th width="20">Estado</th>
    </tr>
    <tbody>

    <?php
    $paisAnterior = 0;
    $titPais = "";
    $titPaisAnt = "";
    $descPais = "";
    $totalGeneral = 0;
    $anuncianteAnt = 0;
    $anuncioAnt = 0;
    $totalAnunciante = 0;
    $totalAnuncio = 0;
    $totalPais = 0;

    foreach($linea as $lin) {
        $fechaDesdeStr = substr($lin['fechadesde'],8,2) . '/' . substr($lin['fechadesde'],5,2) . '/' . substr($lin['fechadesde'],0,4);
        $fechaHastaStr = substr($lin['fechahasta'],8,2) . '/' . substr($lin['fechahasta'],5,2) . '/' . substr($lin['fechahasta'],0,4);

        if ($paisAnterior <> $lin['cod_pais']){
            $descPais=Funciones::obtenerDescPais($con,$lin['cod_pais']);
        }
        
        if ($anuncioAnt <> $lin['nroanuncio']){
            $anuncioAnt = $lin['nroanuncio'];
            if ($totalAnuncio <> 0){?>
            <!--
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right">Tot Anuncio</td>
                <td align="center"><?php echo $totalAnuncio;?></td>
                <td></td>
            </tr> -->
            <?php    
            }
            ?>
        <?php
            $totalAnuncio=0;
            $totalAnuncio=$totalAnuncio + $lin['diaspublicado'];
            } else {
            $totalAnuncio=$totalAnuncio + $lin['diaspublicado'];
            }

        if ($anuncianteAnt <> $lin['nroanunciante'] || $paisAnterior <> $lin['cod_pais']){
            $aux=$anuncianteAnt;
            $anuncianteAnt = $lin['nroanunciante'];
            if ($totalAnunciante <> 0){
                $restanAvisos=Funciones::obtenerRestoAvisos($con,$aux) - $totalAnunciante;
                $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$aux,$restanAvisos);
            ?> 
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right" style="background-color:lightblue">Tot Cliente</td>
                <td align="center" style="background-color:lightblue"><?php echo $totalAnunciante;?></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align="right">Restan Avisos</td>
                <td align="center"><?php echo $restanAvisos;?></td>
                <td></td>
            </tr>
        <?php }
            $totalAnunciante=$lin['diaspublicado'];
            } else {
                if ($paisAnterior == $lin['cod_pais']){
                    $totalAnunciante=$totalAnunciante + $lin['diaspublicado'];
                } else {
                    $totalAnunciante=$lin['diaspublicado'];
                }
            }
        ?>
    <?php 
        
    //    }
    ?>
    <?php
    if ($paisAnterior <> $lin['cod_pais']){
        $titPais = $titPaisAnt;
        $paisAnterior = $lin['cod_pais'];
        if ($totalPais <> 0){
    ?> 
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right" style="background-color:darkgray">Tot País</td>
            <td align="center" style="background-color:darkgray"><b><?php echo $totalPais;?></b></td>
            <td></td>
        </tr>
        <tr height="10">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td</td>
            <td</td>
            <td></td>
        </tr>
        <?php } ?>
    <?php $totalGeneral=$totalGeneral + $totalPais;
          $totalPais=0;
          $totalPais = $totalPais + $lin['diaspublicado'];
        } else {
          $totalPais = $totalPais + $lin['diaspublicado'];
        }
    ?>
        
    <tr>
        <td align="center"><?php echo $descPais; ?></td>
        <td align="center"><?php echo $lin ['nroanunciante']; ?></td>
        <td align="center"><?php echo $lin ['nroanuncio']; ?></td>
        <td align="center"><?php echo $fechaDesdeStr; ?></td>
        <?php
            if (!$fechaHastaStr){ ?>
                <td align="center"><?php echo $fechaHastaStr; ?></td>
        <?php
            } else { ?>
                <td align="center"><?php echo ""; ?></td>
        <?php
            }
        ?>
        <td align="center"><?php echo $lin ['diaspublicado'];?></td>
        <td align="center"><?php echo $lin ['estado']; ?></td>
    </tr>

    <?php 
        }
    ?>
    <?php
        $restanAvisos=Funciones::obtenerRestoAvisos($con,$anuncianteAnt) - $totalAnunciante;
        $actualizaRestanAvisos=Funciones::actualizaRestoAvisos($con,$anuncianteAnt,$restanAvisos);
        
        if ($test){
            mysqli_rollback($con);
        } else {
            mysqli_commit($con);
        }
        mysqli_close($con);

    ?>
    <!--    <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right">Tot Anuncio</td>
            <td align="center"><?php echo $totalAnuncio;?></td>
            <td></td>
        </tr> -->

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right" style="background-color:lightblue">Tot Cliente</td>
            <td align="center" style="background-color:lightblue"><?php echo $totalAnunciante;?></td>
            <td></td>
        </tr>
            <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right">Restan Avisos</td>
            <td align="center"><?php echo $restanAvisos;?></td>
            <td></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right" style="background-color:darkgray">Tot País</td>
            <td align="center" style="background-color:darkgray"><b><?php echo $totalPais;?></b></td>
            <td></td>
        </tr>
        <tr height="10">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td</td>
            <td</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right" style="background-color:springgreen">Tot General</td>
            <?php $totalGeneral = $totalGeneral + $totalPais; ?>
            <td align="center" style="background-color:springgreen"><b><?php echo $totalGeneral;?></b></td>
            <td></td>
        </tr>
        <tr height="10">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td</td>
            <td</td>
            <td></td>
        </tr>
    </tbody>
    </table>
    </div>

<?php
/*
        if(isset($_POST['export_data'])) {
            if(!empty($linea)) {
                $filename = 'reporteFacturacion.xlsx';
                //header('Content-Type: application/vnd.ms-excel');
                //header('Content-Disposition: attachment; filename='.$filename);
                $mostrar_columnas = false;
                foreach($linea as $lin) {
                if(!$mostrar_columnas) {
//                var_dump(array_keys($linea));
                var_dump( implode('\t', array_keys($linea)) . '\n');
                $mostrar_columnas = true;
                }
//                var_dump(array_keys($linea));
//                echo implode('\t', array_values($linea)) . '\n';
                var_dump( implode('\t', array_keys($linea)) . '\n');
                }
            }}
    */
    ?>
        
</body>
