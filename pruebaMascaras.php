<!doctype html>
<?php
	require_once("cargarDatosFormulario.php");
?>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<script>
/*
    document.addEventListener('DOMContentLoaded', function() {
      applyInputMask('elinput', '0000 0000');
    });

    function applyInputMask(elementId, mask) {
      let inputElement = document.getElementById(elementId);
      let content = '';
      let maxChars = numberCharactersPattern(mask);

      inputElement.addEventListener('keydown', function(e) {
        e.preventDefault();
        if (isNumeric(e.key) && content.length < maxChars) {
          content += e.key;
        }
        if(e.keyCode == 8) {
          if(content.length > 0) {
            content = content.substr(0, content.length - 1);
          }
        }
        inputElement.value = maskIt('0000 0000', content);
      })
    }

    function isNumeric(char) {
      return !isNaN(char - parseInt(char));
    }

    function maskIt(pattern, value) {
      let position = 0;
      let currentChar = 0;
      let masked = '';
      while(position < pattern.length && currentChar < value.length) {
        if(pattern[position] === '0') {
          masked += value[currentChar];
          currentChar++;
        } else {
          masked += pattern[position];
        }
        position++;
      }
      return masked;
    }
    
    function numberCharactersPattern(pattern) {
      let numberChars = 0;
      for(let i = 0; i < pattern.length; i++) {
        if(pattern[i] === '0') {
          numberChars ++;
        }
      }
      return numberChars;
    }
*/    
</script>
<style>
</style>
</head>

<body> 
    <a href="submitMascara.php?elinput=<?php echo "128"?>" name="elinput"> <?php echo( Funciones::formato_numero("128", 8, 4)); ?> </a>
</body>
</html>
