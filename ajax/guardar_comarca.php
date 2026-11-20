<?php
	if (empty($_POST['name_comarca'])){
		$errors[] = "Ingresa el nombre de la comarca.";
	} elseif (!empty($_POST['name_comarca'])){
	require_once ("../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
    $cod_pais = mysqli_real_escape_string($con,(strip_tags($_POST["add_pais"],ENT_QUOTES)));
    $cod_region = mysqli_real_escape_string($con,(strip_tags($_POST["add_region"],ENT_QUOTES)));
    $cod_provincia = mysqli_real_escape_string($con,(strip_tags($_POST["add_provincia"],ENT_QUOTES)));
	$cod_descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["name_comarca"],ENT_QUOTES)));	

    $sqlMax = "SELECT MAX(COD_COMARCA) as maximo from comarcas";
	$maxCodNum = mysqli_query($con,$sqlMax);
	if ($maxCodNum->num_rows == 1) {
		$maxCodNumStr = $maxCodNum->fetch_assoc();
		++$maxCodNumStr['maximo'];
		$cod_comarca=$maxCodNumStr['maximo'];
	}
	//echo ("País: " . $cod_pais . " Region: " . $cod_region . " Provincia: " . $cod_provincia . " Nombre provincia: " . $cod_descripcion);
		// REGISTER data into database
    $sql = "INSERT INTO comarcas (ID, COD_PROVINCIA, COD_COMARCA, DESCRIPCION) VALUES 
		   (NULL,$cod_provincia,'$cod_comarca','$cod_descripcion')";
    $query = mysqli_query($con,$sql);
    // if product has been added successfully
    if ($query) {
        $messages[] = "La comarca ha sido ingresada con éxito.";
    } else {
        $errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
    }
		
	} else 
	{
		$errors[] = "desconocido.";
	}
if (isset($errors)){
			
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">
					<span aria-hidden="true">&times;</span></button>
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
						<button type="button" class="close" data-dismiss="alert">
							<span aria-hidden="true">&times;</span></button>
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