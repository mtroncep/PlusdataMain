<?php
	session_start();

/* Connect To Database*/
require_once ("../conexion.php");
require_once("../cargarDatosFormulario.php");

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
//	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
    $query = '';
    $tables="fichas, anunciantes";
	$campos=" * ";
	$sWhere=" fichas.nombrerz LIKE '%".$query."%' and anunciantes.ficha = fichas.idFicha";
	$sWhere.=" order by fichas.idficha";
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
						<th class='text-center'>Ficha</th>
						<th class='text-left'>Razón Social</th>
						<th class='text-left'>E-Mail Laboral</th>
						<th class='text-left'>Número de Móvil</th>
						<th class='text-left'>Operaciones</th>
						<th></th>
					</tr>
				</thead>
				<tbody>	
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$ficha_id=$row['id'];
							$idFicha=$row['idficha'];
							$razonSocial=$row['nombrerz'];
							$email=$row['emaillaboral'];
							$movil=$row['telmovil'];
                            $estado=$row['estado'];
                            $idAnunciante=$row['idanunciante'];
							$finales++;
						?>	
						<tr>
							<td></td>
							<td class='text-center'><?php echo $idFicha;?></td>
							<td class='text-left'><?php echo $razonSocial;?></td>
							<td class='text-left'><?php echo $email;?></td>
							<td class='text-left'><?php echo $movil;?></td>
							<td>
                                <div style="display: flex; justify-content: center; align-items: center;">
                                    <a href="modificarFicha.php?idFicha=<?php echo $idFicha?>"  data-target="#" class="edit" data-code='<?php echo $idFicha;?>' data-name="<?php echo 'Contenido CP'?>" data-id="<?php echo $idFicha; ?>">
                                    <i class="material-icons" data-toggle="tooltip" title="Editar" >edit</i></a>

                                    <?php if($estado === '1'){ ?>
                                        <img src="./imagenes/iconoApagar.png" width="20" height="20" title="inhabilita" 
                                             onClick="cambiaEstadoCuenta('<?php echo $idAnunciante ?>','0',this);">
                                    <?php } else { ?>
                                        <img src="./imagenes/habilitar.png" width="20" height="20" title="habilita" 
                                             onClick="cambiaEstadoCuenta('<?php echo $idAnunciante ?>','1',this);">
                                    <?php } ?>
<!--                                        <div id="tacho">
                                            <a href="#deleteFichaModal" class="delete" data-toggle="modal" data-id="<?php echo $idFicha;?>">
                                            <i class="material-icons" data-toggle="tooltip" title="Eliminar">delete</i></a>

                                        </div>
-->                                </div>

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
<html>
<script>
    
function cambiaEstadoCuenta(idAnunciante,estado,elemento){
    var parametros = {
        "estado": estado,
        "idAnunciante": idAnunciante
    };

    $.ajax({
        url: 'cambiarEstadoCuenta.php',
        method: "post",
        async: false,
        data: parametros,
        success: function(data) {
            if (estado === '1'){
                elemento.src = './imagenes/iconoApagar.png';
                elemento.title = 'deshabilita';
                elemento.onclick = function() {
                  cambiaEstadoCuenta(idAnunciante, estado === '1' ? '0' : '0', this);
                };
            } else {
                elemento.src = './imagenes/habilitar.png';
                elemento.title = 'habilita';
                elemento.onclick = function() {
                  cambiaEstadoCuenta(idAnunciante, estado === '1' ? '0' : '1', this);
                };
            }
        }
    });
}
</script>

</html>