<?php

// muestra resultados del juego
function mostrarResultados($jugadores, $cartasJugadores, $puntos, $ganadores, $premios, $apuesta)
{
    echo "<h2>Resultados del juego</h2>";

    // muestro cartas y puntos de cada jugador
    foreach ($jugadores as $nombre) {
        echo "<h3>$nombre</h3>";
        echo "<p>Cartas: ";
        foreach ($cartasJugadores[$nombre] as $carta) {
            echo "<img src='images/" . $carta . ".PNG' alt='$carta' width='50'> ";
        }
        echo "</p>";
        echo "<p>Puntos: " . $puntos[$nombre] . "</p>";
        echo "<p>Premio: " . (isset($premios[$nombre]) ? number_format($premios[$nombre], 2) : 0) . " €</p>";
    }

    // muestro ganadores
    if (count($ganadores) > 0) {
        echo "<h2>Ganador(es): " . implode(", ", $ganadores) . "</h2>";
    } else {
        echo "<h2>No hay ganadores</h2>";
    }
}


// calcula los puntos de las cartas
function calcularPuntos($cartas)
{
    $total = 0;
    foreach ($cartas as $carta) {
        $valor = substr($carta, 0, -1); // quito el palo

        if ($valor == "1") {
            $total += 1;
        } elseif ($valor == "J" || $valor == "Q" || $valor == "K") {
            $total += 0.5;
        } else {
            $total += $valor;
        }
    }
    return round($total, 1);
}


// reparte cartas sin repetir
// reparte cartas sin repetir
function repartirCartas($numCartas, $jugadores)
{
    // mazo completo
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

    // barajar el mazo
    shuffle($mazo);

    $cartasJugadores = [];
    $indice = 0;

    // repartir a cada jugador
    foreach ($jugadores as $nombre) {
        $cartasJugadores[$nombre] = [];
        for ($i = 0; $i < $numCartas; $i++) {
            $cartasJugadores[$nombre][] = $mazo[$indice];
            $indice++;
        }
    }

    return $cartasJugadores;
}



// mira quien gana y cuanto gana
function determinarGanadores($jugadores, $puntos, $apuesta)
{
    $premios = [];
    $ganadores = [];
    $ganan = [];

    // primero miro si alguno tiene 7.5 exacto
    foreach ($jugadores as $nombre) {
        if ($puntos[$nombre] == 7.5) {
            $ganan[] = $nombre;
        }
    }

    // si nadie tiene 7.5 miro el max sin pasarse
    if (count($ganan) == 0) {
        $max = 0;
        foreach ($jugadores as $nombre) {
            if ($puntos[$nombre] <= 7.5 && $puntos[$nombre] > $max) {
                $max = $puntos[$nombre];
            }
        }
        foreach ($jugadores as $nombre) {
            if ($puntos[$nombre] == $max) {
                $ganan[] = $nombre;
            }
        }
        $reparto = $apuesta * count($jugadores) * 0.5;
    } else {
        $reparto = $apuesta * count($jugadores) * 0.8;
    }

    // reparto premio
    if (count($ganan) > 0) {
        $porJugador = $reparto / count($ganan);
        foreach ($ganan as $nombre) {
            $premios[$nombre] = $porJugador;
            $ganadores[] = $nombre;
        }
    }

    return [$ganadores, $premios];
}


// limpia los datos del formulario
function limpiar($dato)
{
    return htmlspecialchars(trim($dato));
}
