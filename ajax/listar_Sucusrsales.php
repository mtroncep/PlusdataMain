<?php
	session_start();

/* Connect To Database*/
require_once ("../conexion.php");
require_once("../cargarDatosFormulario.php");

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
//    $query = '%';
//	$tables="datosformulario";
	$tables="ficha";

    //	$campos="*";
	$campos=" nombrerz, cod_pais, estadosucursal, anunciante ";
    $sWhere=" anunciante =".$_SESSION['anunciante']." and nroanuncianteinmob=".$_SESSION['anunciante']." and clientetipo = 'O'";
	$sWhere.=" order by nombrerz";

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
        <div class="table-responsive">
		<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th></th>
						<th class='text-center'>Sucursal</th>
						<th class='text-left'>País</th>
						<th class='text-left'>Avisos</th>
						<th class='text-left'>Estado</th>
						<th></th>
					</tr>
				</thead>
				<tbody>	
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$suc_id=$row['id'];
							$suc_nombre=$row['nombrerz'];
                            $suc_estado=$row['estadosucursal'];
							$suc_pais = Funciones::obtenerDescPais($row['cos_pais'], $con);
                            $suc_cant_avisos = Funciones::obtenerCantAvisosActivos($row['anunciante'], $con);
							$finales++;
						?>	
						<tr class="">
							<td></td>
							<td class='text-center'><?php echo $suc_nombre;?></td>
							<td class='text-left'><?php echo $suc_pais;?></td>
							<td class='text-left'><?php echo $suc_cant_avisos;?></td>
							<td class='text-left'><?php echo $suc_estado;?></td>
                            <td class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="estadoSuc" checked>
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