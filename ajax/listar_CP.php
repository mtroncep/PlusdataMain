<?php
session_start();
/* Connect To Database*/
require_once ("../conexion.php");
require_once("../cargarDatosFormulario.php");

$cod_localidad = $_REQUEST['cod_localidad'];
$cod_descripcion = $_SESSION['cod_descripcionCP'];

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
//	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
    $query = '';
	$tables="codigospostales";
//	$campos="*";
	$campos=" cp, cod_localidad ";
	$sWhere=" cp LIKE '%".$query."%'"." and cod_localidad = ".$cod_localidad;
	$sWhere.=" group by cp, cod_localidad";
	$sWhere.=" order by cp, cod_localidad";
	
	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
//	$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM $tables where $sWhere ");
	$count_query   = mysqli_query($con,"SELECT cp, cod_localidad FROM $tables where $sWhere ");
	$numrows=mysqli_num_rows($count_query);
//	if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
//	else {echo mysqli_error($con);}
	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
	$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere LIMIT $offset,$per_page");
	//loop through fetched data
	
	if ($numrows>0){
		
	?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
<!--						<th></th> -->
						<th class='text-center'>Código Postal</th>
						<th class='text-center'>Localidad</th>
						<th class='text-left'>Contenido</th>
						<th></th>
					</tr>
				</thead>
				<tbody>	
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
//							$cp_id=$row['id'];
							$cp_id="0";
							$cp_code=$row['cp'];
							$cp_cod_localidad=$row['cod_localidad'];
							$contenidoCP = Funciones::obtenerContenidoCP($cp_code, $cp_cod_localidad, $con);
							$finales++;
						?>	
						<tr class="">
<!--							<td></td> -->
							<td class='text-center'><?php echo $cp_code;?></td>
							<td class="text-center"><?php echo $cod_descripcion?></td>
							<td class='text-left'><?php echo $contenidoCP;?></td>
							<td>
								<a href="codigosPostales.php?
										 	cp=<?php echo $cp_code?>&
											pais=<?php echo $_SESSION['paisCP']?>&
										 	region=<?php echo $_SESSION['regionCP']?>&
										 	provincia=<?php echo $_SESSION['provinciaCP']?>&
										 	comarca=<?php echo $_SESSION['comarcaCP']?>&
										 	cod_localidad=<?php echo $cod_localidad?>&
										 	cod_descripcion=<?php echo $cod_descripcion?>"
								   data-target="#" class="edit" data-code='<?php echo $cp_code;?>' data-name="<?php echo 'Contenido CP'?>" data-id="<?php echo $cp_id; ?>"><i class="material-icons" data-toggle="tooltip" title="Editar" >edit</i></a>

								<a href="borrarCP.php?delete_id=<?php echo $cp_code?>&cod_localidad=<?php echo $cod_localidad?>" class="delete" data-toggle="modal">
								<i class="material-icons" data-toggle="tooltip" title="Eliminar">delete</i></a>

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
	<?php	
	}	
}
?>