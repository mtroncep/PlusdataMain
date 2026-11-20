<?php
session_start();
require_once ("../conexion.php");
require_once("../cargarDatosFormulario.php");

?>
<html lang="es">
<head>
    <title>Indice navegacion particular</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="css/fuentes.css">

<script>
document.addEventListener('click', function (e) {
    const enlace = e.target.closest('a.edit');
    if (!enlace) return;
    document.getElementById('loader').style.display = 'block';
});
</script>

</head>

<?php
//	session_start();

/* Connect To Database*/
//require_once ("../conexion.php");
//require_once("../cargarDatosFormulario.php");

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
//    $query = '%';
//	$tables="datosformulario";
	$tables="datosbusquedasformulario";

    //	$campos="*";
	$campos=" id, nroanuncio, cod_pais, datosasociados, estado ";

//    $sWhere=" datosformulario.nroanuncio LIKE '%".$query."%'"." and datosformulario.nroanunciante=".$_SESSION['anunciante'];
    $sWhere=" nroanuncio LIKE '%".$query."%'"." and nroanunciante=".$_SESSION['anunciante']." and cod_pais = ".$_SESSION['codPais'];
//    $sWhere=" datosasociados LIKE '%".$query."%'"." and nroanunciante=".$_SESSION['anunciante']." and cod_pais = ".$_SESSION['codPais'];

//    $sWhere.=" group by datosformulario.nroanuncio";
	$sWhere.=" order by nroanuncio desc";

    include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
//	$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM $tables where $sWhere ");
	$count_query   = mysqli_query($con,"SELECT * FROM $tables where $sWhere ");
	$numrows=mysqli_num_rows($count_query);
//	if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
//	else {echo mysqli_error($con);}
	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
	$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere LIMIT $offset,$per_page");
	//loop through fetched data
	
	if ($numrows>0){	
	?>
<body>
    <div id="loader" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); font-size:22px;">
        Procesando... ⏳
    </div>

        <div class="table-responsive">
		<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th></th>
						<th class="text-left" width="100" data-trad="Anuncios">Anuncios</th>
						<th class="text-left" width="50" data-trad="Contenido">Contenido</th>
						<th class="text-left" width="100" data-trad="Funciones">Funciones</th>
						<th></th>
					</tr>
				</thead>
				<tbody>	
						<?php 
						$finales=0;
//                        $_SESSION['volvere_a']="procesarVistaPreviaAnuncio.php?elAnuncio=185&volver_a=indexAnuncios.php";
						while($row = mysqli_fetch_array($query)){	
							$anuncio_id=$row['id'];
							$anuncio_code=$row['nroanuncio'];
                            $anuncioLink= Funciones::formato_numero($anuncio_code, 8, 4);
                            $estado=$row['estado'];
                            
							$contenidoAnuncio = Funciones::obtenerContenidoAnuncioReducido($row['datosasociados'],$anuncio_code, $con);
                            $contenidoAnuncioTraducido = '';
                            $contenidoATraducir = explode(",",$contenidoAnuncio);
                            foreach ($contenidoATraducir as $item) {
                                $item = trim($item);
//                                $item=$item." ";
                                //echo "<span data-trad='" . $item . "'>" . $item . "</span> ";
                                $contenidoAnuncioTraducido .= "<span data-trad='" . $item . "'>" . $item . "</span> ";
                            }    
//                            error_log("Elemento[0]: ".$contenidoTraducido[0]." - ".$contenidoTraducido[1],0);
//							$contenidoAnuncio = $row['datosasociados'];
                            $volver_a="procesarVistaPreviaAnuncio.php?elAnuncio=$anuncio_code&volver_a=indexAnuncios.php";
							$finales++;
						?>	
						<tr class="">
							<td></td>
                            <td class="text-left"><a target="_blank" href="procesarVistaPreviaAnuncio.php?elAnuncio=<?php echo $anuncio_code?>" name="elAnuncio"> <?php echo( Funciones::formato_numero($anuncio_code, 8, 4)); ?> </a></td>
<!--							<td class='text-center'><?php echo $anuncioLink;?></td> -->
							<td class="text-left"><?php echo $contenidoAnuncioTraducido;?></td>
<!--							<td class="text-left"> -->
							<td>
                                <div class="row">
                                    <a href="pruebaActualizarDetalles.php?operacion=M&anuncio=<?php echo $anuncio_code?>"  class="edit" data-code='<?php echo $anuncio_code;?>' data-name="<?php echo 'Contenido CP'?>" data-id="<?php echo $anuncio_id; ?>">
                                    <i class="material-icons" data-toggle="tooltip" title="Editar" >edit</i></a>
<!--
                                    <a href="#deleteAnuncioModal" class="delete" data-toggle="modal" data-id="<?php echo $anuncio_code;?>" 
                                       data-estado="<?php echo $estado;?>" onClick="return controlEstadoAnuncio(<?php echo $estado ?>)">
                                    <i class="material-icons" data-toggle="tooltip" title="Eliminar">delete</i></a>
-->
                                    <a href="javascript:void(0);" 
                                       class="delete" 
                                       data-id="<?php echo $anuncio_code; ?>" 
                                       data-estado="<?php echo $estado; ?>"
                                       onclick="controlEstadoAnuncio(<?php echo $anuncio_code; ?>,<?php echo $estado ?>)">
                                       <i class="material-icons" data-toggle="tooltip" title="BORRAR">delete</i>
                                    </a>
                                    <?php if ($estado==0){?>

                                        <a href="ajax/activarAnuncio.php?anuncio_id=<?php echo $anuncio_code?>">
                                            <i class="material-icons" data-toggle="tooltip" title="Dar ALTA">
                                            <img src="imagenes/activarNuevo.png" alt="Activar" width="22px"></i>
                                        </a> 
                                        <?php } else { 
                                        if ($estado == 1) {?>
                                        <a href="ajax/inactivarAnuncio.php?anuncio_id=<?php echo $anuncio_code?>">
                                            <i class="material-icons" data-toggle="tooltip" title="Dar BAJA">
                                            <img src="imagenes/tildeVerdeGordo.png" alt="Inactivar" width="22px"></i>
                                        </a> 
                                    <?php } } ?>
                                </div>
                            </td>
						</tr>
						<?php } ?>
						<tr>
							<td colspan='6'> 
								<?php 
									$inicios=$offset+1;
									$finales+=$inicios -1;
									echo "Mostrando $inicios al $finales de $numrows registros";
									echo paginate( $page, $total_pages, $adjacents);
								?>
							</td>
						</tr>
				</tbody>			
			</table>
		</div>
        <script>
            function controlEstadoAnuncio(id, estado){
                if (estado === 1){
                    alert ('ANTES DE BORRAR EL ANUNCIO, DEBE DARLO DE BAJA');
                    return false;
                } else {
                    $('#deleteAnuncioModal').modal('show');
                    $('#delete_id').val(id);
                    return true;
                }
            }
        </script>


</body>

<?php
	}	
}
?>

