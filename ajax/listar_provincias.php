<?php
	
/* Connect To Database*/
require_once ("../conexion.php");

	
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));

	$tables="provincias";
	$campos="*";
//	$sWhere=" paises.descripcion LIKE '%".$query."%'";
	$sWhere=" provincias.descripcion LIKE '%".$query."%'and cod_region=".$_REQUEST['region'];
	$sWhere.=" order by provincias.descripcion";
	$pais=$_REQUEST['pais'];
	$region=$_REQUEST['region'];
	
	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*
	$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM $tables where $sWhere ");
	if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	else {echo mysqli_error($con);}
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
						<th class='text-center'></th>
						<th class="text-center">Provincia</th>
						<th class="text-center">Activa para Búsquedas</th>
					</tr>
				</thead>
				<tbody>	
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$provincia_id=$row['ID'];
							$cod_region=$row['COD_REGION'];
							$cod_provincia=$row['COD_PROVINCIA'];
							$cod_descripcion=$row['DESCRIPCION'];
							$cod_activa=$row['ACTIVA'];
							$finales++;
						?>	
						<tr>
							<td></td>
							<td class='text-center'><?php echo $cod_descripcion;?></td>
							<td class='text-center'><?php echo $cod_activa;?></td>
							<td>
								<a href="#editProvinciaModal" class="edit" data-toggle="modal" data-code="<?php echo $cod_region?>" data-name="<?php echo $cod_descripcion?>" data-id="<?php echo $provincia_id?>" data-activa="<?php echo $cod_activa ?>" ><i class="material-icons" data-toggle="tooltip" title="Editar" >edit</i></a>
                    		</td>
							<td>
								<a href="#deleteProvinciaModal" class="delete" data-toggle="modal" data-id="<?php echo $provincia_id;?>">
								<i class="material-icons" data-toggle="tooltip" title="Eliminar">delete</i></a>
							</td>
							<td>
								<a href="index_comarcas.php?pais=<?php echo $pais;?>&region=<?php echo $cod_region;?>&provincia=<?php echo $cod_provincia;?>">Comarcas</a>
							</td>
						</tr>
						<?php }?>
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