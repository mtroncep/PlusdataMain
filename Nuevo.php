<?php
include "mysql.php";
$query=$mysqli->query("select * from paises");
$paises = array();
while($r=$query->fetch_object()){ 
	$paises[]=$r;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Combo 3 Niveles</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	setTimeout(function() {
		$(".col-md-12").fadeOut(1000);
	},1000);
});
</script>
	
<script type="text/javascript">
$(document).ready(function(){
    $("#pais_id").on('change', function () {
		$("#pais_id option:selected").each(function () {
            var id_pais = $(this).val();
			$.post("regiones.php", { id_pais: id_pais }, function(data) {
                $("#region_id").html(data);
            });
			$("#paisFijo").text($(this).val());
        });
	});
});


$(document).ready(function(){
    $("#pais_id").on('change', function () {
		$("#pais_id option:selected").each(function () {
            var id_pais = $(this).val();
			$.post("regiones.php", { id_pais: id_pais }, function(data) {
                $("#regionProv_id").html(data);
            });			
        });
   });
});

$(document).ready(function(){
    $("#regionProv_id").on('change', function () {
        $("#regionProv_id option:selected").each(function () {
            var id_region = $(this).val();
			$.post("provincias.php", { id_region: id_region }, function(data) {
                $("#provincias_id").html(data);
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
<div class="container">
<div class="panel panel-default">
<nav class="navbar navbar-default">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="./indexABM.php">BAULPHP</a>
    </div>
    <!-- Coleccion de etiquetas nav links, forms, y otros contenidos -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li ><a href="./indexABM.php">INICIO</a></li>
        <li ><a href="./Nuevo.php">AGREGAR REGISTROS</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
</nav>
<div class="panel-body">
	<h1>Agregar Datos</h1>
</div>
<div class="row">
<div class="col-md-12">
<?php if(isset($_GET["paisadd"])):?>
<p class="alert alert-success">Pais agregado exitosamente!</p>
<?php unset($paisadd); endif; ?>

<?php if(isset($_GET["regionadd"])):?>
<p class="alert alert-info">Región agregada exitosamente!</p>
<?php unset($regionadd); endif; ?>

<?php if(isset($_GET["provinciaadd"])):?>
<p class="alert alert-warning">Provincia agregada exitosamente!</p>
<?php unset($provinciaadd); endif; ?>
</div>
</div>
<div class="row">
<div class="col-md-10">
<h3>Nuevo País</h3>
<form method="post" action="agregar.php?opt=country">
  <div class="form-group">
    <label for="name1">Nombre</label>
    <input type="text" class="form-control" id="name1" name="name" placeholder="Nombre" required>
  </div>
  <button type="submit" class="btn btn-default">Agregar País</button>
  <h1><strong>CARGA DE NUEVAS ENTIDADES</strong><br>
<h3>En País</label>
  <span class="form-group">
  <select id="pais_id" class="form-control" name="pais_id" required>
    <option value="">Seleccione...</option>
    <?php foreach($paises as $p):?>
    <option value="<?php echo $p->COD_NUM; ?>"><?php echo $p->DESCRIPCION; ?></option>
    <?php endforeach; ?>
  </select>
  </span></h1>
  <p id=paisFijo class=paisFijo></p>
</form>
</div>
	
<div class="row">
<div class="form-group">
<h3>&nbsp;</h3>
</div>
</div>
	
<div class="col-md-4">
<h3>Nueva Region</h3>
<form method="post" action="agregar.php?opt=region">
  <p>
    <label for="name1">Regiones actuales</label>
    <select id="region_id" class="form-control" name="region_id">
      <option value="">Seleccione...</option>
    </select>
  </p>
  <div class="form-group">
    <label for="name1">Nombre región</label>
    <input type="text" class="form-control" id="name1" name="name" placeholder="Nombre" required>
</div>
  <button type="submit" class="btn btn-default">Agregar Región</button>
</form>
</div>

<div class="col-md-4">
<h3>Nueva Provincia</h3>
<form method="post" action="Agregar.php?opt=provincia">
  <div class="form-group">
    <label for="name1">En región</label>
    <select id="regionProv_id" class="form-control" name="regionProv_id" required>
      <option value="">Seleccione...</option>
   </select>
  </div>
	
  <div class="form-group">
    <label for="name1">Provincias actuales</label>
    <select id="provincias_id" class="form-control" name="provincias_id">
      <option value="">Seleccione...</option>
    </select>
  </div>


  <div class="form-group">
    <label for="name1">Nombre provincia</label>
    <input type="text" class="form-control" id="name1" name="name" placeholder="Nombre" required>
  </div>
  <button type="submit" class="btn btn-default">Agregar Provincia</button>
</form>
</div>

</div>
<div class="col-md-4">
<h3>Nueva Comarca</h3>
<form method="post" action="Agregar.php?opt=comarca">
  <div class="form-group">
    <label for="name1">En provincia</label>
    <select class="form-control" name="region_id" required>
      <option value="">Seleccione...</option>
	  <?php foreach($provincias as $r):?>
      	<option value="<?php echo $r->COD_PROVINCIA; ?>"><?php echo $r->DESCRIPCION; ?></option>
	  <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label for="name1">Nombre comarca</label>
    <input type="text" class="form-control" id="name1" name="name" placeholder="Nombre" required>
  </div>
  <button type="submit" class="btn btn-default">Agregar Provincia</button>
</form>
</div>

<div class="col-md-4">
<h3>Nuevo Municipio</h3>
<form method="post" action="Agregar.php?opt=municipio">
  <div class="form-group">
    <label for="name1">En provincia</label>
    <select class="form-control" name="provincia_id" required>
      <option value="">Seleccione...</option>
	  <?php foreach($provincias as $r):?>
      	<option value="<?php echo $r->COD_PROVINCIA; ?>"><?php echo $r->DESCRIPCION; ?></option>
	  <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group">
    <label for="name1">Nombre municipio</label>
    <input type="text" class="form-control" id="name1" name="name" placeholder="Nombre" required>
  </div>
  <button type="submit" class="btn btn-default">Agregar Municipio</button>
</form>
</div>
</div>
</div>
</body>
</html>