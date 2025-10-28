<?php
require 'media7fun.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // nombres de jugadores
    $jugadores = [
        limpiar($_POST["nombre1"]),
        limpiar($_POST["nombre2"]),
        limpiar($_POST["nombre3"]),
        limpiar($_POST["nombre4"])
    ];

    // cartas y apuesta
    $numcartas = (int)limpiar($_POST["numcartas"]);
    $apuesta   = (float)limpiar($_POST["apuesta"]);

    // repartir cartas
    $cartasJugadores = repartirCartas($numcartas, $jugadores);

    // puntos
    $puntos = [];
    foreach ($cartasJugadores as $nombre => $cartas) {
        $puntos[$nombre] = calcularPuntos($cartas);
    }

    // ganadores
    [$ganadores, $premios] = determinarGanadores($jugadores, $puntos, $apuesta);

    // mostrar todo
    mostrarResultados($jugadores, $cartasJugadores, $puntos, $ganadores, $premios, $apuesta);
}
