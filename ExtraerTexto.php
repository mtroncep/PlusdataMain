<?php
if (empty($_GET['archivo'])){
    return;
} else {
    $archivo = $_GET['archivo'];
}

// Ruta al archivo HTML
//$archivo = 'modificarFicha.php';

// Cargar el contenido
$html = file_get_contents($archivo);

// Crear el DOM
$dom = new DOMDocument();
libxml_use_internal_errors(true); // Evita warnings por HTML malformado
$dom->loadHTML($html);
libxml_clear_errors();

// FunciÃ³n recursiva para extraer texto visible
function extraerTextoVisible($nodo) {
    $texto = '';

    // Ignorar scripts, estilos y comentarios
    if (in_array($nodo->nodeName, ['script', 'style']) || $nodo->nodeType === XML_COMMENT_NODE) {
        return '';
    }

    if ($nodo->nodeType === XML_TEXT_NODE) {
        $contenido = trim($nodo->nodeValue);
        if ($contenido !== '') {
            $texto .= $contenido . "\n";
        }
    }

    if ($nodo->hasChildNodes()) {
        foreach ($nodo->childNodes as $hijo) {
            $texto .= extraerTextoVisible($hijo);
        }
    }

    return $texto;
}

// Ejecutar sobre el <body>
$body = $dom->getElementsByTagName('body')->item(0);
$textoExtraido = extraerTextoVisible($body);

// Mostrar resultado
echo nl2br(htmlentities($textoExtraido));
?>