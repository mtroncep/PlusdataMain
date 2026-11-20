<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar mes y año</title>
</head>
<body>
    <h2>Ingresar mes y año para generar el informe</h2>
    
    <?php
        // Fecha actual
        $mesActual  = (int)date('n');   // 1–12
        $anioActual = (int)date('Y');   // Año actual

        // Para evitar fechas futuras
        $mesMax = $mesActual;
        $anioMax = $anioActual;
    ?>

    <form method="post" action="estadoMonederoYAvisosCron.php">

        <label for="mes">Mes:</label>
        <input type="number"
               id="mes"
               name="mes"
               min="1"
               max="12"
               value="<?php echo $mesActual; ?>"
               required>

        <br><br>

        <label for="anio">Año:</label>
        <input type="number"
               id="anio"
               name="anio"
               min="2000"
               max="<?php echo $anioMax; ?>"
               value="<?php echo $anioActual; ?>"
               required>

        <br><br>

        <label for="anunciante">Anunciante:</label>
        <input type="text"
               id="anunciante"
               name="anunciante"

        <br><br>
        <br><br>

        <button type="submit">Ejecutar proceso</button>
    </form>

    <script>
        // Evitar seleccionar un mes futuro dentro del mismo año
        const mesInput = document.getElementById('mes');
        const anioInput = document.getElementById('anio');

        const mesActualJS =   "<?php echo $mesActual; ?>";
        const anioActualJS = "<?php echo $anioActual; ?>";

        anioInput.addEventListener('input', function () {
            if (parseInt(this.value) === anioActualJS) {
                mesInput.max = mesActualJS;
            } else {
                mesInput.max = 12;
            }
        });
    </script>

</body>
</html>