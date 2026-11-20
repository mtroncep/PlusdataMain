<?php
	if (empty($_POST['name_nucleo'])){
		$errors[] = "Ingresa el nombre del núcleo.";
	} elseif (!empty($_POST['name_nucleo'])){
	require_once ("../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
    $cod_pais = mysqli_real_escape_string($con,(strip_tags($_POST["add_pais"],ENT_QUOTES)));
    $cod_region = mysqli_real_escape_string($con,(strip_tags($_POST["add_region"],ENT_QUOTES)));
    $cod_provincia = mysqli_real_escape_string($con,(strip_tags($_POST["add_provincia"],ENT_QUOTES)));
    $cod_comarca = mysqli_real_escape_string($con,(strip_tags($_POST["add_comarca"],ENT_QUOTES)));
    $cod_municipio = mysqli_real_escape_string($con,(strip_tags($_POST["add_municipio"],ENT_QUOTES)));
    $cod_localidad = mysqli_real_escape_string($con,(strip_tags($_POST["add_localidad"],ENT_QUOTES)));
    $cod_barrio = mysqli_real_escape_string($con,(strip_tags($_POST["add_barrio"],ENT_QUOTES)));
	$cod_descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["name_nucleo"],ENT_QUOTES)));	

    $sqlMax = "SELECT MAX(COD_NUCLEO) as maximo from nucleos";
	$maxCodNum = mysqli_query($con,$sqlMax);
	if ($maxCodNum->num_rows == 1) {
		$maxCodNumStr = $maxCodNum->fetch_assoc();
		++$maxCodNumStr['maximo'];
		$cod_nucleo=$maxCodNumStr['maximo'];
	}
	//echo ("País: " . $cod_pais . " Region: " . $cod_region . " Provincia: " . $cod_provincia . " Nombre provincia: " . $cod_descripcion);
		// REGISTER data into database
    $sql = "INSERT INTO nucleos (ID, COD_BARRIO, COD_NUCLEO, DESCRIPCION) VALUES 
		   (NULL,$cod_barrio, $cod_nucleo,'$cod_descripcion')";
    $query = mysqli_query($con,$sql);
    // if product has been added successfully
    if ($query) {
        $messages[] = "El núcleo ha sido ingresado con éxito.";
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