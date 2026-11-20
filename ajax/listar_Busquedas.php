<?php
session_start();

/* Connect To Database*/
require_once ("../conexion.php");
require_once("../cargarDatosFormulario.php");

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="trad/traduccion.php?lang=<?php echo $_SESSION['idioma'];?>"></script>

<script type="text/javascript">
  function extenderVigencia(fecha, campoEnt, campoSal, anuncio){
    var campoSalida = document.getElementById(campoSal);
    $.ajax({
     type: 'POST',  // Envío con método POST
     url: 'extenderVigencia.php',  // Fichero destino (el PHP que trata los datos)
     data: { fecha: fecha, nrobusqueda: anuncio } // Datos que se envían
     }).done(function( msg ) {  // Función qidiomue se ejecuta si todo ha ido bien
      $(campoSalida).html(msg);  // Escribimos en el div consola el mensaje devuelto
     }).fail(function (jqXHR, textStatus, errorThrown){ // Función que se ejecuta si algo ha ido mal
     // Mostramos en consola el mensaje con el error que se ha producido
     $(campoSal).html("ERROR"); 
    });
  }

  function verBusqueda(idBusqueda){
    window.location.href = "procesarVistaBusquedaGuardada.php?laBusqueda="+idBusqueda;  
  }
</script>

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

    $tables="busquedasguardadas";
	$campos=" * ";
    $sWhere=" id LIKE '%".$query."%'"." and anunciante=".$_SESSION['anunciante'];
	$sWhere.=" order by nrobusqueda desc";

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
						<th></th>
						<th class='text-left'     width="100"><span data-trad="Búsqueda">Búsqueda</th></span>
						<th class='text-left'     width="600"><span data-trad="Criterio">Criterio</th></span>
                        <th class='text-center'   width="200"><span data-trad="Guardada">Guardada</th></span>
                        <th class='text-center'   width="200"><span data-trad="Vigencia">Vigencia</th></span>
                        <th class='text-center'   width="200"><span data-trad="Encontrada">Encontrada</th></span>
						<th class='text-left'     width="200"><span data-trad="Funciones">Funciones</th></span>
						<th></th>
					</tr>
				</thead>
				<tbody>	
						<?php 
						$finales=0;
//                        $_SESSION['volvere_a']="procesarVistaPreviaAnuncio.php?elAnuncio=185&volver_a=indexAnuncios.php";
						while($row = mysqli_fetch_array($query)){	
							$anuncio=$row['nrobusqueda'];
                            $datosBusqueda = $row['datosasociados'];
//                            $fechaGuardada = Funciones::fecha_AMD_to_DMA_ddmmaaaa($row['fechaguardada']);
                            $fechaGuardada = $row['fechaguardada'];
                            $fechaVigencia = $row['fechavigencia'];
                            $fechaEncontrada = $row['fechaencontrada'];
							$contenidoAnuncio = Funciones::obtenerContenidoBusqueda($anuncio, $con);
                            $volver_a="procesarVistaPreviaAnuncio.php?elAnuncio=$anuncio&volver_a=indexAnuncios.php";
							$finales++;
						?>	
						<tr class="">
							<td></td>
                            <td class="text-center"><?php echo($anuncio); ?></td>
<!--							<td class='text-center'><?php// echo $anuncioLink;?></td> -->
							<td class="text-left"><?php echo $contenidoAnuncio;?></td>
							<td class="text-center" id="<?php echo 'fecha_'.$anuncio ?>"><?php echo $fechaGuardada;?></td>
							<td class="text-center" id="<?php echo 'fechasal_'.$anuncio ?>"><?php echo $fechaVigencia;?></td>
							<td class="text-center" id="<?php echo 'fechaenc_'.$anuncio ?>"><?php echo $fechaEncontrada;?></td>
							<td>
                                <div class="row">
                                    <a class="btn" onclick="verBusqueda(<?php echo $anuncio ?>)" type="image" id="verBusqueda" name="verBusqueda">
                                    <img src="imagenes/lupa.png" width="20" height="20" title="Resumen búsqueda"></img></a>

                                    <a class="btn" onclick="extenderVigencia('<?php echo $fechaVigencia?>','<?php echo 'fecha_'.$anuncio ?>','<?php echo 'fechasal_'.$anuncio ?>','<?php echo $anuncio ?>')" type="image" id="extender" name="extender">
                                    <img src="imagenes/extenderVigencia.png" width="35" height="35" title="Extender Vigencia"></img></a>

                                    <a href="#deleteBusquedaModal" class="delete" data-toggle="modal" 
                                       data-id="<?php echo $anuncio;?>">
                                    <img src="imagenes/inactivar.png" width="20" height="20" title="Eliminar Búsqueda"></img></a>
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
<script>
     function traducir(texto) {
      if (window.TRADUCCIONES?.[texto]) {
        return window.TRADUCCIONES[texto];
      }
      return texto;
    }

    function aplicarTraducciones() {
        document.querySelectorAll('[data-trad]').forEach(el => {
          const clave = el.getAttribute('data-trad');
        // Si es un input con placeholder
        if (el.hasAttribute('placeholder')) {
          el.setAttribute('placeholder', traducir(clave));
        } else {
          // Para otros elementos, traducimos el texto interno
          el.innerText = traducir(clave);
        }
        });
      }

      // Ejecutamos al cargar la página
    window.onload = aplicarTraducciones();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
