<!doctype html>
<?php
require_once("conexion.php");

//$query = mysqli_query($con,"SELECT * FROM paises where id=62");
//$pais=mysqli_fetch_assoc($query);
//echo base64_encode($pais['bandera']);

$query = mysqli_query($con,"select * from paises order by descripcion");
?>

	<h2><center> <?php echo "Seleccione Ubicación"?></center></h2>
	<div class="table-wrapper-scroll-y my-custom-scrollbar position-relative">
		<table class="table table-bordered table-striped mb-5" border-light>
		<thead>
		  <tr>
			<th scope="col" align="center">Bandera</th>
			<th scope="col" align="center">País</th>
			<th scope="col" align="center">Moneda</th>
			<th scope="col" align="center">Código País</th>
		  </tr>
		</thead>
		<tbod>
			<?php
			$finales=0;
			while($row = mysqli_fetch_array($query)){	
				$bandera=$row['bandera'];
				$pais=$row['DESCRIPCION'];
				$moneda=$row['moneda'];
				$codPais=$row['COD_NUM'];
            ?>
		  <tr>
			<td><img src = data:image/png;base64,<?php echo base64_encode($bandera);?> width = "40px" height = "30px"></td>
            <td> <?php echo $pais ?></td>
            <td> <?php echo $moneda ?></td>
            <td> <?php echo $codPais ?></td>
		  </tr>
			<?php } ?>
		</tbod>
	  </table>
	</div>

