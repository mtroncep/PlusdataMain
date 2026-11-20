<?php
	
/* Connect To Database*/
require_once ("../conexion.php");

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));

	$tables="localidades";
	$campos="*";
//	$sWhere=" paises.descripcion LIKE '%".$query."%'";
	$sWhere=" localidades.descripcion LIKE '%".$query."%' and COD_MUNICIPIO=".$_REQUEST['municipio'];
	$sWhere.=" order by localidades.descripcion";

	$pais=$_REQUEST['pais'];
	$region=$_REQUEST['region'];
	$provincia=$_REQUEST['provincia'];
	$comarca=$_REQUEST['comarca'];
	$municipio=$_REQUEST['municipio'];
	
	//echo ($pais." ".$region." ".$provincia." ".$comarca);

	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
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
						<th>Localidad</th>
						<th></th>
					</tr>
				</thead>
				<tbody>	
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$localidad_id=$row['ID'];
							$cod_localidad=$row['COD_LOCALIDAD'];
							$cod_descripcion=$row['DESCRIPCION'];
							$finales++;
						?>	
						<tr>
							<td class='text-center'></td>
							<td ><?php echo $cod_descripcion;?></td>
							<td>
								<a href="#editCiudadModal" class="edit" data-toggle="modal" data-code="<?php echo $cod_localidad;?>" data-name="<?php echo $cod_descripcion?>"  data-id="<?php echo $localidad_id?>?>"><i class="material-icons" data-toggle="tooltip" title="Editar" >edit</i></a>
								<a href="#deleteCiudadModal" class="delete" data-toggle="modal" data-id="<?php echo $localidad_id;?>">
								<i class="material-icons" data-toggle="tooltip" title="Eliminar">delete</i></a>
                    		</td>
							<td>
								<a href="index_barrios.php?pais=<?php echo $pais;?>&region=<?php echo $region;?>
										 &provincia=<?php echo $provincia;?>&comarca=<?php echo $comarca;?>
										 &municipio=<?php echo $municipio;?>&localidad=<?php echo $cod_localidad;?>">Barrios</a>
								<h5>  </h5>
								<a href="indexCP.php?cod_localidad=<?php echo $cod_localidad;?>&cod_descripcion=<?php echo $cod_descripcion;?>
										 &pais=<?php echo $pais;?>&region=<?php echo $region;?>&provincia=<?php echo $provincia;?>&comarca=<?php echo $comarca;?>&municipio=<?php echo $municipio;?>">Códigos Postales</a>
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