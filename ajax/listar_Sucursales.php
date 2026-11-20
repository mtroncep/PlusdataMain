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
	$tables="fichas";

    //	$campos="*";
	$campos=" id, nombrerz, estadosucursal, anunciante ";
    $sWhere=" (anunciante =".$_SESSION['anunciante']." or anuncianteinmob=".$_SESSION['anunciante'].") and clientetipo = 'O'";
    $sWhere.=" and nombrerz LIKE '%".$query."%'";
	$sWhere.=" order by nombrerz";

    include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
//	$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM $tables where $sWhere ");
	$count_query   = mysqli_query($con,"SELECT * FROM fichas where $sWhere ");
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
						<th class="text_center"></th>
                            <th class='text-center'>Sucursal</th>
                            <th class='text-center'>Avisos Publicados</th>
                            <th class='text-center'>Activa</th>
						<th></th>
					</tr>
				</thead>
				<tbody>	<div id="tooltip" class="custom-tooltip" hiiden>
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$suc_id=$row['id'];
							$suc_nombre=$row['nombrerz'];
                            $suc_estado=$row['estadosucursal'];
                            $suc_anunciante=$row['anunciante'];
                            $suc_cant_avisos = Funciones::obtenerCantAvisosActivos($row['anunciante'], $con);
							$finales++;
						?>	
						<tr class="">
							<td></td>
							<td class='text-center'><?php echo $suc_nombre;?></td>
							<td class='text-center'><?php echo $suc_cant_avisos;?></td>
                            <td class="form-check text-center">
                                <?php 
                                if ($suc_estado == 1) {
                                ?>
                                <input class="form-check-input" onChange="actualizarSucursal(<?php echo ($suc_id)?>,this,<?php echo ($suc_anunciante)?>)" type="checkbox" value="" id="estadoSuc" data-mensaje="CIERRE DE SUCURSAL: Si desmarca la sucursal, indica que la totalidad e avisos de la misma, pasan a su inmobiliaria. Podrá habilitarla nuevamente, pero quedará sin avisos asociados." checked>
                                <?php
                                } else {?>
                                <input class="form-check-input" onChange="actualizarSucursal(<?php echo ($suc_id)?>,this,<?php echo ($suc_anunciante)?>)" type="checkbox" value="" id="estadoSuc" data-mensaje="CIERRE DE SUCURSAL: Si desmarca la sucursal, indica que la totalidad de avisos de la misma, pasaran a su inmobiliaria. Podrá habilitarla nuevamente, pero quedará sin avisos asociados.">
                                <?php
                                }
                                ?>
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
<link rel="stylesheet" href="css/mensajeGlobo.css">
<script type="text/javascript">
	function actualizarSucursal(id,checkbox,anunciante){
        console.log("Entre a la funcion");
//        var estado = document.getElementById('estadoSuc');
        if (checkbox.checked) {
            console.log("Estado checkbox: marcado");
            var oper = 1;
        } else {
            console.log("Estado checkbox NO marcado");
            var oper = 0;
            if (confirm("Esta operación, no tiene vuelta atras. ¿Continua?")) {
                // El usuario hizo clic en "Aceptar"
                var aux = 0;
            } else {
                // El usuario hizo clic en "Cancelar"
                checkbox.checked = true;
                return;
            }
        }

        var parametros = {
			"id": id,
            "oper": oper,
            "anunciante": anunciante
		};
		$.ajax({
			url: 'ajax/actualizarEstadoSucursal.php',
            method: "POST",
			data: parametros,
            success: function(data) {
                $(".estadoSuc").html(data).fadeIn('slow');
            }
            
		});
	}

</script>
<script src="js/mensajeGlobo.js"></script>
