<!DOCTYPE html>
<html>
<head>
  <title>Input de Número Internacional</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
</head>
<body>
        <label for="search">Buscar:</label>
        <input type="text" id="search" onkeyup="filterOptions()">

        <select class="form-select form-select-sm" id="codPaisesIntl" name="codPaisesIntl">
        </select>        
<!--
        <select id="mySelect">
            <option value="+54">Argentina</option>
            <option value="+55">Brasil</option>
            <option value="+34">España</option>
            <option value="+598">Uruguay</option>
            <option value="+386">Slovenia</option>
        </select>
-->
        <input type="text" id="codigoPais">

    <script>
        function filterOptions() {
            var search = document.getElementById("search").value.toLowerCase();
            var select = document.getElementById("codPaisesIntl");
            var pais = document.getElementById("codigoPais");
            var options = select.getElementsByTagName("option");

            for (var i = 0; i < options.length; i++) {
                var optionText = options[i].textContent.toLowerCase();
                if (optionText.indexOf(search) > -1) {
                    options[i].style.display = ""; // Mostrar la opción
                    options[i].selected = true;
                    pais.value=options[i].value;
                } else {
                    options[i].style.display = "none"; // Ocultar la opción
                }
            }
        }  
    </script>
    
    <script>
        const selectElement = document.getElementById("codPaisesIntl");

        selectElement.addEventListener("change", function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedValue = selectedOption.value;
            const selectedText = selectedOption.text;
            const pais = document.getElementById("codigoPais");

            // Realizar acciones con el valor o el texto de la opción seleccionada
            selectedOption.selected = true;
            pais.value=selectedOption.value;
        });
    </script>

    <script>
		$(document).ready(function(){
            $.post("cargarSelectIntl.php", 
                   function(data) {
                   $("#codPaisesIntl").html(data);
            });			

            const selectElement = document.getElementById("codPaisesIntl");

            selectElement.addEventListener("change", function() {
                const selectedOption = this.options[this.selectedIndex];
                const selectedValue = selectedOption.value;
                const selectedText = selectedOption.text;
                const pais = document.getElementById("codigoPais");

                // Realizar acciones con el valor o el texto de la opción seleccionada
                selectedOption.selected = true;
                pais.value=selectedOption.value;
            });
        });
    </script>
</body>
</html>