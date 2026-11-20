<?php
	session_start();

/* Connect To Database*/
require_once ("../conexion.php");

$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
if($action == 'ajax'){
	$query = mysqli_real_escape_string($con,(strip_tags($_REQUEST['query'], ENT_QUOTES)));
	$tables="detalles";
	$campos=" * ";
	$sWhere=" detalles.detalle LIKE '%".$query."%'";
	$sWhere.=" order by detalles.nro_en_form, detalles.secuencia";
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
	//main q//uery to fetch the data
//	$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere LIMIT $offset,$per_page");
	$query = mysqli_query($con,"SELECT $campos FROM  $tables where $sWhere");
	
	if ($numrows>0){
		
	?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th></th>
						<th class='text-left'>Detalle</th>
						<th class='text-left'>Ayuda</th>
						<th></th>
					</tr>
				</thead>
				<tbody>	
						<?php 
						$finales=0;
						while($row = mysqli_fetch_array($query)){	
							$detalles_id=$row['id'];
							$detalle=$row['detalle'];
                            $ayuda=$row['mensajeayuda'];
/*
                            $nro_en_form=$row['nro_en_form'];
							$secuencia=$row['secuencia'];
							$tipo_campo=$row['tipo_campo'];
							$casa=$row['Casa'];
							$cabana=$row['CabaÃ±a'];
							$chalet=$row['Chalet'];
							$apartamento=$row['Apartamento'];
							$loft=$row['Loft'];
							$monoambiente=$row['Monoambiente'];
							$duplex=$row['DÃºplex'];
							$atico=$row['Ãtico'];
							$locales=$row['Locales'];
							$oficinas=$row['Oficinas'];
							$suelos=$row['Suelos'];
							$naves=$row['Naves'];
							$sepulcros=$row['Sepulcros'];
							$parking=$row['Parking'];
*/
							$finales++;
						?>	
						<tr data-id="<?php echo $detalles_id;?>">
							<td></td>
<!--							<td class='text-left'><?php echo $detalle;?></td> -->
							<td>
                                <span class="texto-detalle"><?php echo $detalle;?></span>
                                <input class="input-detalle" type="text" align="left" style="display:none;" value="<?php echo ltrim($detalle);?>"></input>
                            </td>
							<td> 
                                <span class="texto-techo"><?php echo $ayuda;?></span>
                                <textarea rows"3" cols="40" class="input-techo" style="display:none;"><?php echo ltrim($ayuda);?></textarea>
                            </td>
							<td>
                                <button class="btn-editar">✏️</button>
                                <button class="btn-guardar" style="display:none;">💾</button>
                    		</td>
						</tr>
						<?php } ?>
                        <script>
                            var textoAnterior = "";
                            document.querySelectorAll('tbody > tr').forEach(fila => {
                              const editarBtn = fila.querySelector('.btn-editar');
                              const guardarBtn = fila.querySelector('.btn-guardar');
                              const spanDetalle = fila.querySelector('.texto-detalle');
                              const textInput = fila.querySelector('.input-detalle');
                              const span = fila.querySelector('.texto-techo');
                              const textarea = fila.querySelector('.input-techo');
                              const id = fila.dataset.id;
                              if (id){
                              }
                              if (editarBtn){
                                  editarBtn.addEventListener('click', () => {
                                    span.style.display = 'none';
                                    textarea.style.display = 'inline-block';
                                    textarea.focus();
                                    editarBtn.style.display = 'none';
                                    guardarBtn.style.display = 'inline-block';
                                      
                                    spanDetalle.style.display = 'none';
                                    textInput.style.display = 'inline-block';
                                    textoAnterior = textInput.value;
                                    console.log("textInput Antes: "+textoAnterior);
                                    textInput.focus();
                                    editarBtn.style.display = 'none';
                                    guardarBtn.style.display = 'inline-block';
                                  });
                              }
                              if (guardarBtn){
                                  guardarBtn.addEventListener('click', () => {
                                    const nuevoValorTextArea = textarea.value;
                                    const nuevoValorInput = textInput.value;

                                    console.log("textInput Antes/Despues: "+textoAnterior+"/"+nuevoValorInput);

                                    // Volver al modo solo lectura
                                    spanDetalle.textContent = nuevoValorInput;
                                    spanDetalle.style.display = 'inline';
                                    span.textContent = nuevoValorTextArea;
                                    span.style.display = 'inline';
                                    textInput.style.display = 'none';
                                    textarea.style.display = 'none';
                                    guardarBtn.style.display = 'none';
                                    editarBtn.style.display = 'inline-block';

                                    // Enviar al backend
                                    fetch('ajax/ActualizarMensajeAyuda.php', {
                                      method: 'POST',
                                      headers: {
                                        'Content-Type': 'application/json'
                                      },
                                      body: JSON.stringify({ id: id, techo: nuevoValorTextArea, detalleant: textoAnterior, detalle: nuevoValorInput })
                                    })
                                    .then(res => res.ok ? console.log('Guardado OK') : Promise.reject())
                                    .catch(err => console.error('Error al guardar:', err));
                                  });
                            }
                            });
                            
                        </script>
						<tr>
							<td colspan='20'> 
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