<?php
session_start();

// Banco de preguntas simples
$preguntas = [
    ['pregunta' => '¿Cuánto es 2 + 3?', 'respuesta' => '5'],
    ['pregunta' => '¿Cuánto es 10 - 4?', 'respuesta' => '6'],
    ['pregunta' => '¿De qué color es el cielo en un día despejado?', 'respuesta' => 'azul'],
    ['pregunta' => '¿Cuántos días tiene una semana?', 'respuesta' => '7'],
    ['pregunta' => 'Escribí la palabra "gato" al revés', 'respuesta' => 'otag'],
    ['pregunta' => '¿Cuál es el primer mes del año?', 'respuesta' => 'enero'],
];

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $respuestaUsuario = mb_strtolower(trim($_POST['captcha_respuesta'] ?? ''));
    $respuestaCorrecta = mb_strtolower($_SESSION['captcha_respuesta'] ?? '');

    if ($respuestaUsuario !== '' && $respuestaUsuario === $respuestaCorrecta) {
        // ✅ Captcha OK: seguís la lógica normal, en la MISMA ejecución
        unset($_SESSION['captcha_respuesta']);

        // ---- ACÁ CONTINÚA TU SCRIPT ORIGINAL ----
        continuarProceso();
        exit;
    } else {
        $error = "Respuesta incorrecta, intentá de nuevo.";
    }
}

// Elegimos una pregunta nueva (para el intento actual)
$preguntaElegida = $preguntas[array_rand($preguntas)];
$_SESSION['captcha_respuesta'] = $preguntaElegida['respuesta'];

function continuarProceso() {
    echo "¡Listo! Acá sigue el resto del proceso...";
}
?>
<!DOCTYPE html>
<html>
<body>
    <?php if ($error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label><?= htmlspecialchars($preguntaElegida['pregunta']) ?></label>
        <br>
        <input type="text" name="captcha_respuesta" autocomplete="off" required>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>