<?php
	if (empty($_POST['name_region'])){
		$errors[] = "Ingresa el nombre de la región.";
	} elseif (!empty($_POST['name_region'])){
	require_once ("../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
    $cod_pais = mysqli_real_escape_string($con,(strip_tags($_POST["add_pais"],ENT_QUOTES)));
	$cod_descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["name_region"],ENT_QUOTES)));	
	//echo ("Pais: " . $cod_pais . " Nombre región: " . $cod_descripcion);

    $sqlMax = "SELECT MAX(COD_REGION) as maximo from regiones";
	$maxCodNum = mysqli_query($con,$sqlMax);
	if ($maxCodNum->num_rows == 1) {
		$maxCodNumStr = $maxCodNum->fetch_assoc();
		++$maxCodNumStr['maximo'];
		$cod_region=$maxCodNumStr['maximo'];
	}
		// REGISTER data into database
    $sql = "INSERT INTO regiones (id, COD_PAIS, COD_REGION, DESCRIPCION) VALUES 
		   (NULL,$cod_pais,'$cod_region','$cod_descripcion')";
    $query = mysqli_query($con,$sql);
    // if product has been added successfully
    if ($query) {
        $messages[] = "La región ha sido ingresada con éxito.";
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