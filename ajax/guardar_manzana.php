<?php
	if (empty($_POST['name_manzana'])){
		$errors[] = "Ingresa el nombre de la manzana.";
	} elseif (!empty($_POST['name_manzana'])){
	require_once ("../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
    $cod_pais = mysqli_real_escape_string($con,(strip_tags($_POST["add_pais"],ENT_QUOTES)));
    $cod_region = mysqli_real_escape_string($con,(strip_tags($_POST["add_region"],ENT_QUOTES)));
    $cod_provincia = mysqli_real_escape_string($con,(strip_tags($_POST["add_provincia"],ENT_QUOTES)));
    $cod_comarca = mysqli_real_escape_string($con,(strip_tags($_POST["add_comarca"],ENT_QUOTES)));
    $cod_municipio = mysqli_real_escape_string($con,(strip_tags($_POST["add_municipio"],ENT_QUOTES)));
    $cod_localidad = mysqli_real_escape_string($con,(strip_tags($_POST["add_localidad"],ENT_QUOTES)));
    $cod_barrio = mysqli_real_escape_string($con,(strip_tags($_POST["add_barrio"],ENT_QUOTES)));
    $cod_nucleo = mysqli_real_escape_string($con,(strip_tags($_POST["add_nucleo"],ENT_QUOTES)));
	$cod_descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["name_manzana"],ENT_QUOTES)));	

    $sqlMax = "SELECT MAX(COD_MANZANA) as maximo from manzanas";
	$maxCodNum = mysqli_query($con,$sqlMax);
	if ($maxCodNum->num_rows == 1) {
		$maxCodNumStr = $maxCodNum->fetch_assoc();
		++$maxCodNumStr['maximo'];
		$cod_manzana=$maxCodNumStr['maximo'];
	}
	//echo ("Núcleo: " . $cod_nucleo . " Manzana: ". $cod_manzana . " Nombre manzana: " . $cod_descripcion);
		// REGISTER data into database
    $sql = "INSERT INTO manzanas (ID, COD_NUCLEO, COD_MANZANA, DESCRIPCION) VALUES 
		   (NULL,$cod_nucleo, $cod_manzana,'$cod_descripcion')";
    $query = mysqli_query($con,$sql);
    // if product has been added successfully
    if ($query) {
        $messages[] = "La manzana ha sido ingresada con éxito.";
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