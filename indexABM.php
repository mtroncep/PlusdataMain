<?php
include "mysql.php";
$query=$mysqli->query("select * from paises");
$paises = array();
while($r=$query->fetch_object()){ $paises[]=$r; }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Actualización de ubicaciones</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#pais_id").on('change', function () {
        $("#pais_id option:selected").each(function () {
            var id_pais = $(this).val();
			$.post("regiones.php", { id_pais: id_pais }, function(data) {
                $("#region_id").html(data);
            });			
        });
   });
});

$(document).ready(function(){
    $("#region_id").on('change', function () {
        $("#region_id option:selected").each(function () {
            var id_region = $(this).val();
			$.post("provincias.php", { id_region: id_region }, function(data) {
                $("#provincia_id").html(data);
            });
        });
   });
});

$(document).ready(function(){
    $("#provincia_id").on('change', function () {
        $("#provincia_id option:selected").each(function () {
            var id_provincia = $(this).val();
			$.post("comarcas.php", { id_provincia: id_provincia }, function(data) {
                $("#comarca_id").html(data);
            });
        });
   });
});

$(document).ready(function(){
    $("#comarca_id").on('change', function () {
        $("#comarca_id option:selected").each(function () {
            var id_comarca = $(this).val();
			$.post("municipios.php", { id_comarca: id_comarca }, function(data) {
                $("#municipio_id").html(data);
            });
        });
   });
});

</script>
</head>

<body>
<BR>
 <div class="container">
<div class="panel panel-default">


<nav class="navbar navbar-default">
 
    <!-- Extra para moviles mostrar -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="./indexABM.php">BAULPHP</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li ><a href="./indexABM.php">INICIO</a></li>
        <li ><a href="./Nuevo.php">AGREGAR REGISTROS</a></li>
      </ul>

    </div><!-- /.navbar-collapse -->
  
</nav>

<div class="panel-body">
<div class="row">
<div class="col-md-12">
<h1>Actualización de ubicaciones</h1>
<?php if(isset($_COOKIE["comboadd"])):?>
<p class="alert alert-success">Combo Agregado exitosamente!</p>
<?php setcookie("comboadd",0,time()-1); endif; ?>
</div>
</div>

<div class="row">
<div class="col-md-6">
<form method="post" action="Agregar.php?opt=all">
  <div class="form-group">
    <label for="name1">Pais</label>
    <select id="pais_id" class="form-control" name="pais_id" required>
      <option value="">Seleccione...</option>
<?php foreach($paises as $c):?>
      <option value="<?php echo $c->COD_NUM; ?>"><?php echo $c->DESCRIPCION; ?></option>
<?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label for="name1">Región</label>
    <select id="region_id" class="form-control" name="region_id" required>
      <option value="">Seleccione...</option>
   </select>
  </div>

  <div class="form-group">
    <label for="name1">Provincia</label>
    <select id="provincia_id" class="form-control" name="provincia_id" required>
      <option value="">Seleccione...</option>
   </select>
  </div>

  <div class="form-group">
    <label for="name1">Comarca</label>
    <select id="comarca_id" class="form-control" name="comarca_id" required>
      <option value="">Seleccione...</option>
   </select>
  </div>

  <div class="form-group">
    <label for="name1">Municipio</label>
    <select id="municipio_id" class="form-control" name="municipio_id" required>
      <option value="">Seleccione...</option>
   </select>
  </div>

  <button type="submit" class="btn btn-default">Agregar Registro</button>
</form>
</div>
</div>
</div>

 <div class="panel-footer">BaulPHP</div>
</div><!-- /.Cierra-default-panel -->
</div><!-- /.container-fluid -->

</body>
</html>