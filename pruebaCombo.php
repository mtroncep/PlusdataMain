<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script language="javascript">
$(document).ready(function(){
    $("#paisesSel").on('change', function () {
        $("#paisesSel option:selected").each(function () {
            var id_pais = $(this).val();
			$.post("regiones.php", { id_pais: id_pais }, function(data) {
                $("#regionesSel").html(data);
            });			
        });
   });
});
</script>
</head>

<body>
  <form class="row" action="" method="post">
   <div class="row" align="left">
   <p>
	  <label for="paisesSel">País </label>
      <select name="paisesSel" id="paisesSel" class="form-control">
        <option value="0">Seleccione...</option>
        <?php
          include("mysql.php");
          $query = $mysqli -> query ("SELECT * FROM paises");
          while ($valores = mysqli_fetch_array($query)) {
            echo '<option value="'.$valores[COD_NUM].'">'.$valores[DESCRIPCION].'</option>';
          }
        ?>
      </select>
    </p>
  </div>
  <div class="row" align="left">
  	<label for="regionesSel">Región </label>
    <select name="regionesSel" id="regionesSel" class="form-control"></select>
  </div>
 </form>
</body>
</html>