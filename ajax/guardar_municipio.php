<?php
	if (empty($_POST['name_municipio'])){
		$errors[] = "Ingresa el nombre del municipio.";
	} elseif (!empty($_POST['name_municipio'])){
	require_once ("../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
    $cod_pais = mysqli_real_escape_string($con,(strip_tags($_POST["add_pais"],ENT_QUOTES)));
    $cod_region = mysqli_real_escape_string($con,(strip_tags($_POST["add_region"],ENT_QUOTES)));
    $cod_provincia = mysqli_real_escape_string($con,(strip_tags($_POST["add_provincia"],ENT_QUOTES)));
    $cod_comarca = mysqli_real_escape_string($con,(strip_tags($_POST["add_comarca"],ENT_QUOTES)));
	$cod_descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["name_municipio"],ENT_QUOTES)));	

    $sqlMax = "SELECT MAX(COD_MUNICIPIO) as maximo from municipiosxcomarca";
	$maxCodNum = mysqli_query($con,$sqlMax);
	if ($maxCodNum->num_rows == 1) {
		$maxCodNumStr = $maxCodNum->fetch_assoc();
		++$maxCodNumStr['maximo'];
		$cod_municipio=$maxCodNumStr['maximo'];
	}
	//echo ("País: " . $cod_pais . " Region: " . $cod_region . " Provincia: " . $cod_provincia . " Nombre provincia: " . $cod_descripcion);
		// REGISTER data into database
    $sql = "INSERT INTO municipiosxcomarca (ID, COD_COMARCA, COD_MUNICIPIO, DESCRIPCION) VALUES 
		   (NULL,$cod_comarca,'$cod_municipio','$cod_descripcion')";
    $query = mysqli_query($con,$sql);
    // if product has been added successfully
    if ($query) {
        $messages[] = "El municipio ha sido ingresado con éxito.";
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