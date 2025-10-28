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

    // Repartir cartas
    $cartasJugadores = repartirCartas($numcartas, $jugadores);

    // Calcular puntos
    $puntos = [];
    foreach ($cartasJugadores as $cartas) {
        $puntos[] = calcularPuntos($cartas);
    }

    // Determinar ganadores
    $ganadores = determinarGanadores($jugadores, $puntos, $apuesta);
    mostrarResultados($jugadores, $cartasJugadores, $puntos, $ganadores[0], $ganadores[1], $apuesta);
}
