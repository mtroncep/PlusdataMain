<?php
	if (empty($_POST['importe'])){
		$errors[] = "Ingresa el importe del precio.";
	} elseif (!empty($_POST['importe'])){
	require_once ("../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
    $importe = mysqli_real_escape_string($con,(strip_tags($_POST["importe"],ENT_QUOTES)));
	$cantidad = mysqli_real_escape_string($con,(strip_tags($_POST["cantidad"],ENT_QUOTES)));	

		// REGISTER data into database
    $sql = "INSERT INTO listaprecios (id, importe, diasparapublicar, fechaactualizacion) VALUES (NULL,".$importe.",".$cantidad.",CURRENT_TIMESTAMP)";
    $query = mysqli_query($con,$sql);
    // if product has been added successfully
    if ($query) {
        $messages[] = "El precio ha sido guardado con éxito.";
    } else {
//        $errors[] = $sql;
        $errors[] = "Lo sentimos, el registro falló. Por favor, vuelva a intentarlo.";
    } 
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