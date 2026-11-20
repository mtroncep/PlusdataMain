<?php
	if (empty($_POST['delete_id'])){
		$errors[] = "Id vacío.";
	} elseif (!empty($_POST['delete_id'])){
	require_once ("../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
    $id_anuncio=intval($_POST['delete_id']);
	// DELETE FROM  database
    $sql = "DELETE FROM datosformulario WHERE nroanuncio='$id_anuncio'";
    $sql1 = "DELETE FROM datosbusquedasformulario WHERE nroanuncio='$id_anuncio'";
    $sql2 = "DELETE FROM slider WHERE nroanuncio='$id_anuncio'";
    $query = mysqli_query($con,$sql);
    $query1 = mysqli_query($con,$sql1);
    $query2 = mysqli_query($con,$sql2);
    // if product has been added successfully
    if (($query) && ($query1) && ($query2)) {
        $messages[] = "El anuncio ha sido eliminado con éxito.";
    } else {
        $errors[] = "Lo sentimos, la eliminación falló. Por favor, regrese y vuelva a intentarlo.";
    }
		
	} else 
	{
		$errors[] = "desconocido.";
	}
	if (isset($errors)){
	?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> 
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
				}
			if (isset($messages)){
				
				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
			}
?>