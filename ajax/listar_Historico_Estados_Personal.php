<?php
	session_start();

/* Connect To Database*/
require_once ("../conexion.php");
require_once("../cargarDatosFormulario.php");

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
//	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
//    $query = '%';
//	$tables="datosformulario";
//	$tables="historicoinformes";

    //	$campos="*";
/*
    $campos=" id, tipoinforme, anunciante, nombreinforme, fechahorainforme ";
    $sWhere=" anunciante = ".$_SESSION['anunciante']." and tipoinforme = 1";
    $sWhere.=" and nombreinforme LIKE '%".$query."%'";
	$sWhere.=" order by fechahorainforme desc";
*/
    include 'pagination.php'; //include pagination file
	//pagination variables
	$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
	$per_page = intval($_REQUEST['per_page']); //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
    $total_pages = 1;
    //Count the total number of row in your table*/
//	$count_query   = mysqli_query($con,"SELECT count(*) AS numrows FROM $tables where $sWhere ");
//	$count_query   = mysqli_query($con,"SELECT * FROM $tables where $sWhere ");
//	$numrows=mysqli_num_rows($count_query);
//	if ($row= mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
//	else {echo mysqli_error($con);}
//	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
//	$query = mysqli_query($con,"SELECT $campos FROM historicoinformes where $sWhere LIMIT $offset,$per_page");
	//loop through fetched data
//	if ($numrows>0){
	?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class='text-center'>Informe</th>
                </tr>
            </thead>
        <tbody>	
        <?php 
        $dir = "../informes/estadoAnunciosGlobal/"; //path o ruta de nuestro directorio 
        $dirLocal = "informes/estadoAnunciosGlobal/"; //path o ruta de nuestro directorio 
        chdir($dir);
        array_multisort(array_map('filemtime', ($files = glob("*_".$_SESSION['anunciante']."_*.pdf"))), SORT_DESC, $files);
        $finales=0;
        foreach($files as $filename)
        {
            if ($finales > 11){
                break;
            }
            $partes_ruta = pathinfo($filename);
            $partes_file = explode("_", $partes_ruta['basename'] );
            $finales++;
        ?>
        <tr class="col col-2">
        <td class="text-center"><a href="mostrarArchivo.php?path=<?php echo $dirLocal ?><?php echo $filename?>"</a><?php echo $partes_file[3]." ".$partes_file[4];?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan='6'> 
                <?php 
                    $inicios=$offset+1;
                    $finales+=$inicios -1;
                    echo "Mostrando $inicios al $finales registros";
                    echo paginate( $page, $total_pages, $adjacents);
                ?>
            </td>
        </tr>
    </tbody>			
    </table>
    </div>	
	<?php	
}
?>
