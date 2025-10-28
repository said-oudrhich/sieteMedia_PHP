<?php

function mostrarResultados($jugadores, $cartasJugadores, $puntos, $ganadores, $premios, $apuesta)
{

    echo "<h2>Resultados del juego</h2>";

    // mostrar cartas y puntos de cada jugador
    for ($i = 0; $i < count($jugadores); $i++) {
        echo "<h3>" . $jugadores[$i] . "</h3>";
        echo "<p>Cartas: ";
        foreach ($cartasJugadores[$i] as $carta) {
            echo "<img src='images/" . $carta . ".PNG' alt='" . $carta . "' width='50'> ";
        }
        echo "</p>";
        echo "<p>Puntos: " . $puntos[$i] . "</p>";
        echo "<p>Premio: " . number_format($premios[$i], 2) . "</p>";
    }

    // mostrar ganadores
    if (count($ganadores) > 0) {
        echo "<h2>Ganador(es): " . implode(", ", $ganadores) . "</h2>";
    } else {
        echo "<h2>No hay ganadores</h2>";
    }
}

// funcion que suma los puntos de las cartas
function calcularPuntos($cartas)
{
    $total = 0;
    foreach ($cartas as $carta) {
        // quito el palo y me quedo con el numero o letra
        $valor = substr($carta, 0, -1);

        // segun lo que sea sumo los puntos
        if ($valor == "1") {
            $total += 1;
        } elseif ($valor == "J" || $valor == "Q" || $valor == "K") {
            $total += 0.5;
        } else {
            $total += $valor;
        }
    }
    return round($total, 1); // redondeo a un decimal
}


// reparte cartas a los jugadores sin repetir
function repartirCartas($numCartas, $jugadores)
{
    $mazo = [
        "1D",
        "1C",
        "1P",
        "1T",
        "2D",
        "2C",
        "2P",
        "2T",
        "3D",
        "3C",
        "3P",
        "3T",
        "4D",
        "4C",
        "4P",
        "4T",
        "5D",
        "5C",
        "5P",
        "5T",
        "6D",
        "6C",
        "6P",
        "6T",
        "7D",
        "7C",
        "7P",
        "7T",
        "JD",
        "JC",
        "JP",
        "JT",
        "QD",
        "QC",
        "QP",
        "QT",
        "KD",
        "KC",
        "KP",
        "KT"
    ];
    shuffle($mazo); // mezclo el mazo
    $cartasJugadores = [];

    // reparto sin repetir
    for ($i = 0; $i < count($jugadores); $i++) {
        $cartasJugadores[$i] = array_splice($mazo, 0, $numCartas);
    }

    return $cartasJugadores;
}


// mira quien gana y cuanto gana
// devuelve array de array con nombres de ganadores y premios
function determinarGanadores($jugadores, $puntos, $apuesta)
{
    $premios = [0, 0, 0, 0];
    $ganadores = [];
    $ganan = [];

    // primero miro si alguno tiene 7.5 exacto
    for ($i = 0; $i < count($jugadores); $i++) {
        if ($puntos[$i] == 7.5)
            $ganan[] = $i;
    }

    // si nadie tiene 7.5 miro quien tiene el maximo sin pasarse
    if (count($ganan) == 0) {
        $max = 0;
        for ($i = 0; $i < count($jugadores); $i++) {
            if ($puntos[$i] <= 7.5 && $puntos[$i] > $max) {
                $max = $puntos[$i];
            }
        }
        for ($i = 0; $i < count($jugadores); $i++) {
            if ($puntos[$i] == $max) {
                $ganan[] = $i;
            }
        }
        $reparto = $apuesta * count($jugadores) * 0.5; // menos premio si nadie llega a 7.5
    } else {
        $reparto = $apuesta * count($jugadores) * 0.8; // premio mayor si hay 7.5
    }

    // reparto lo que toque
    if (count($ganan) > 0) {
        $porJugador = $reparto / count($ganan);
        foreach ($ganan as $i) {
            $premios[$i] = $porJugador;
            $ganadores[] = $jugadores[$i];
        }
    }

    return [$ganadores, $premios];
}


// limpiar datos del formulario
function limpiar($dato)
{
    return htmlspecialchars(trim($dato));
}
