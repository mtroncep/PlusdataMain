<?php
session_start();

$preguntas = [
    ['pregunta' => '¿Cuánto es 4 + 5?', 'respuesta' => '9'],
    ['pregunta' => '¿Cuánto es 7 - 3?', 'respuesta' => '4'],
    ['pregunta' => 'Escribí la palabra "mar"', 'respuesta' => 'mar'],
    ['pregunta' => '¿Cuánto es 2 x 4?', 'respuesta' => '8'],

    [ 'pregunta' =>  "¿Cuánto es 5 + 3?", 'respuesta' =>  '8'],
    [ 'pregunta' =>  "¿Cuánto es 12 - 4?", 'respuesta' =>  '8'],
    [ 'pregunta' =>  "¿Cuánto es 3 x 4?", 'respuesta' =>  '12'],
    [ 'pregunta' =>  "¿Cuántos lados tiene un triángulo?", 'respuesta' =>  '3'],
    [ 'pregunta' =>  "¿Cuántos días tiene una semana?", 'respuesta' =>  "7"],
    [ 'pregunta' =>  "¿Cuántos meses tiene un año?", 'respuesta' =>  "12"],

    [ 'pregunta' =>  "¿Qué es más pesado: 1kg de plumas o 1kg de hierro?",
                                                  'respuesta' =>  "lo mismo"],
    [ 'pregunta' =>  "¿Si tienes 3 manzanas y comes 1, ¿Cuántas te quedan?", 'respuesta' =>  "2"],
    [ 'pregunta' =>  "¿Cuántos huevos hay en una docena?", 'respuesta' =>  "12"],

    [ 'pregunta' =>  "¿De qué color es el cielo en un día despejado?", 'respuesta' =>  "azul"],
    [ 'pregunta' =>  "¿Cuál es el animal más grande del mundo?", 'respuesta' =>  "ballena"],
    [ 'pregunta' =>  "¿En qué estación del año caen las hojas de los árboles?", 'respuesta' =>  "otoño"],
    [ 'pregunta' =>  "¿Cuántas patas tiene una araña?",        'respuesta' =>  "8"],
    [ 'pregunta' =>  "¿Cuántas patas tiene un perro?",         'respuesta' =>  "4"],

    [ 'pregunta' =>  "¿Cuántas letras tiene la palabra 'CASA'?", 'respuesta' =>  "4"],
    [ 'pregunta' =>  "¿Cuál es la primera letra del abecedario?", 'respuesta' =>  "a"],
    [ 'pregunta' =>  "¿Cómo se llama la capital de Francia?",  'respuesta' =>  "parís"],
];

$idx = array_rand($preguntas);

echo json_encode([
    "pregunta" => $preguntas[$idx]['pregunta'],
    "respuesta" => $preguntas[$idx]['respuesta']
]);
