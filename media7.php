<?php
require 'media7fun.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener nombres y limpiar
    $jugadores = [
        limpiar($_POST["nombre1"]),
        limpiar($_POST["nombre2"]),
        limpiar($_POST["nombre3"]),
        limpiar($_POST["nombre4"])
    ];

    // Número de cartas y apuesta
    $numcartas = (int)limpiar($_POST["numcartas"]);
    $apuesta   = (float)limpiar($_POST["apuesta"]);
}
