<?php
	
/* Connect To Database*/
require_once ("../conexion.php");
$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));

	$tables="listaprecios";
	$campos=" * ";
//	$sWhere=" importe LIKE '%".$query."%'";
	$sWhere=" order by importe";
	
	include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
	$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM $tables $sWhere ");
	if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	else {echo mysqli_error($con);}
	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
	$query = mysqli_query($con,"SELECT $campos FROM $tables $sWhere LIMIT $offset,$per_page");
	//loop through fetched data
	
	if ($numrows>0){
	?> 
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class='text-center'></th>
                        <th class='text-center'>Importe</th>
                        <th class='text-center'>Días Publicación</th>
                        <th class='text-center'>Ultima Modificación</th>
						<th></th>
                    </tr>
                </thead>
                <tbody>	
                    <?php 
                    $finales=0;
                    while($row = mysqli_fetch_array($query)){	
                        $precio_id=$row['id'];
                        $precio_importe=$row['importe'];
                        $precio_cantidad=$row['diasparapublicar'];
                        $precio_fecha=$row['fechaactualizacion'];
                        $finales++;
                    ?>	
                    <tr>
                        <td class='text-center'></td>
                        <td class='text-center'><?php echo $precio_importe;?></td>
                        <td class='text-center'><?php echo $precio_cantidad;?></td>
                        <td class='text-center'><?php echo date("d/m/Y H:i", strtotime($precio_fecha));?></td>
                        <td>
                            <a href="#editProductModal" class="edit" data-toggle="modal" data-cantidad="<?php echo $precio_cantidad?>" data-importe="<?php echo $precio_importe?>"  data-id="<?php echo $precio_id; ?>"><i class="material-icons" data-toggle="tooltip" title="Editar" >edit</i></a>
                            <a href="#deleteProductModal" class="delete" data-toggle="modal" data-id="<?php echo $precio_id;?>">
                            <i class="material-icons" data-toggle="tooltip" title="Eliminar">delete</i></a>
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
<style>
.table{
   position:relative;
   margin:auto;
   width:100%;
   /*left:-15%*/;
}</style>