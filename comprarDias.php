<!DOCTYPE html>
<?php 
    session_start();
/*
    if (!isset($_SESSION['codPais'])){
        echo ("<script>alert('Falta seleccionar el país.');</script>");
//        echo ('<script type="text/javascript">'."window.close();"."</script>");
        return;
    }
*/
?>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ABM de precios de publicaciones - MySQL - jQuery AJAX </title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/custom_new.css">
<link rel="stylesheet" href="css/fuentes.css">

<script>
function miConfirmacion(importe, dias, radio) {
  if(confirm("¿"+traducir("Confirma la compra")+"? "+traducir("Importe")+"= "+importe+", "+traducir("Dias")+": "+dias)){
    alert("Se lo derivara a la plataforma de pagos");
    var parametros = {
        "cantDias": dias
    };
    $.ajax({
        type: "POST",
        url: 'actualizarImporteMonederoBD.php',
        data: parametros,
        beforeSend: function(objeto) {},
        success: function(data) {}
    })
//    window.location.href = '<?php echo $_SESSION['menuinicio'] ?>';
  }else{
    alert("Usted cancelo la compra");
    radio.checked=false;
  }
}
</script>

<script src="trad/traduccion.php?lang=<?php echo $_SESSION['idioma']?>"></script>

</head>

<body>
<?php
	
/* Connect To Database*/
require_once ("conexion.php");

	$tables="listaprecios";
	$campos=" * ";
//	$sWhere=" importe LIKE '%".$query."%'";
	$sWhere=" order by importe";
	
	$query = mysqli_query($con,"SELECT $campos FROM $tables $sWhere");
    $numrows = mysqli_num_rows($query);
	if ($numrows>0){
    ?> <h1 data-trad="Cargar Monedero" class="text-center">Cargar Monedero</h1>
       <div class="container">
        <div class="table-responsive">
<!--            <table class="table table-striped table-hover col col-sm-1">  -->
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class='text-center col-sm-4'><span data-trad="Importe">Importe</th></span>
                        <th class='text-center col-sm-4'><span data-trad="Días para Publicar">Días para Publicar</th></span>
                        <th class='text-center col-sm-4'><span data-trad="Elección">Elección</th></span>
                    </tr>
                </thead>
                <tbody>	
                    <?php 
                    $finales=0;
                    while($row = mysqli_fetch_array($query)){	
                        $precio_id=$row['id'];
                        $precio_importe=$row['importe'];
                        $precio_cantidad=$row['diasparapublicar'];
                        $finales++;
                    ?>	
                    <tr>
                        <td class='text-center col-sm-4'><?php echo $precio_importe;?></td>
                        <td class='text-center col-sm-4'><?php echo $precio_cantidad;?></td>
                        <td class="text-center col-sm-4">
                            <input type="radio" name="fav_language" value="seleccionar" 
                                   onChange="miConfirmacion(<?php echo $precio_importe?><?php echo ','?><?php echo $precio_cantidad?>,this)">
                        </td>
                    </tr>
                    <?php }?>
                </tbody>			
            </table>
        </div>
       </div>
	<?php	
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

</body>