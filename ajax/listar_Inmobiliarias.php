<?php
	session_start();

/* Connect To Database*/
require_once ("../conexion.php");
require_once("../cargarDatosFormulario.php");

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<style>
.btn {     
    background-color: Transparent;
    background-repeat:no-repeat;
    border: none;
    cursor:pointer;
    overflow: hidden;        
}    
</style>
<?php
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));

    $tables="inmobiliarias";
	$campos=" * ";
//    $sWhere=" email IS NOT NULL";
    $sWhere=" pais LIKE '%$query%'";
//	$sWhere.=" order by cpostal desc";

    include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	$count_query   = mysqli_query($con,"SELECT * FROM $tables where $sWhere ");
	$numrows=mysqli_num_rows($count_query);
	$total_pages = ceil($numrows/$per_page);
	$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere LIMIT $offset,$per_page");
	
	if ($numrows>0){
	?>
        <div class="table-responsive">
		<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th class='text-left'     width="20">País</th>
						<th class='text-left'     width="5">TiE</th>
                        <th class='text-left'     width="10">CP</th>
                        <th class='text-left'     width="30">Ciudad</th>
                        <th class='text-left'     width="60">E-Mail</th>
						<th class='text-left'     width="50">Funciones</th>
					</tr>
				</thead>
				<tbody>	
						<?php 
						$finales=0;
//                        $_SESSION['volvere_a']="procesarVistaPreviaAnuncio.php?elAnuncio=185&volver_a=indexAnuncios.php";
						while($row = mysqli_fetch_array($query)){	
							$id=$row['id'];
                            $pais = $row['pais'];
                            $tipoenvio = $row['tipoenvio'];
                            $cp = $row['cpostal'];
                            $ciudad = $row['ciudad'];
                            $email = $row['email'];
							$finales++;
						?>	
						<tr class="">
                            <td class="text-left"><?php echo($pais); ?></td>
                            <td class="text-left"><?php echo($tipoenvio); ?></td>
							<td class="text-left"><?php echo $cp;?></td>
							<td class="text-left"><?php echo $ciudad; ?></td>
							<td class="text-left"><?php echo $email; ?></td>
							<td>
                                <div class="row">
                                    <a href="#deleteBusquedaModal" class="delete" data-toggle="modal" 
                                       data-id="<?php echo $id;?>">
                                    <img src="imagenes/inactivar.png" width="20" height="20" title="Eliminar Inmobiliaria"></img></a>
<!--                                    <i class="material-icons" data-toggle="tooltip" title="Eliminar">delete</i></a> -->
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
	<?php	
	}	
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
