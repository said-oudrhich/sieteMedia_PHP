<?php

// muestra resultados del juego en una tabla
// @param array $jugadores
// @param array $cartasJugadores
// @param array $puntos
// @param array $ganadores
// @param array $premios
// @param float $apuesta
// @return void
function mostrarResultados($jugadores, $cartasJugadores, $puntos, $ganadores, $premios, $apuesta)
{
    echo "<h2>Resultados del juego</h2>";

    // tabla con todos los datos
    echo "<table border='1' cellspacing='0' cellpadding='6'>";
    echo "<tr><th>Jugador</th><th>Cartas</th><th>Puntos</th><th>Premio (€)</th></tr>";

    // muestro cartas y puntos de cada jugador
    foreach ($jugadores as $nombre) {
        echo "<tr>";
        echo "<td>$nombre</td>";
        echo "<td>";
        foreach ($cartasJugadores[$nombre] as $carta) {
            echo "<img src='images/" . $carta . ".PNG' alt='$carta' width='50'> ";
        }
        echo "</td>";
        echo "<td>" . $puntos[$nombre] . "</td>";
        echo "<td>" . (isset($premios[$nombre]) ? number_format($premios[$nombre], 2) : "0.00") . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // muestro ganadores o mensaje si no hay
    if (count($ganadores) > 0) {
        echo "<h2>Ganador(es): " . implode(", ", $ganadores) . "</h2>";
    } else {
        $bote = number_format($apuesta * count($jugadores), 2);
        echo "<h2>No hay ganadores, se añade al bote $bote €</h2>";
    }
}

//**************************************************************************************************************************************************
// obtiene las iniciales de un nombre
// @param string $nombre
// @return string
function obtenerIniciales($nombre)
{
    $partes = explode(" ", $nombre);
    $iniciales = "";
    foreach ($partes as $parte) {
        $iniciales .= strtoupper(substr($parte, 0, 1));
    }
    return $iniciales;
}

//**************************************************************************************************************************************************
// guarda los resultados en un fichero de texto
// @param array $jugadores
// @param array $puntos
// @param array $ganadores
// @param array $premios
// @param float $apuesta
// @return void
function guardarResultados($jugadores, $puntos, $ganadores, $premios)
{
    $nombreArchivo = "apuestas_" . date("dmYHis") . ".txt";
    $ruta = "Ficheros/$nombreArchivo";
    $archivo = fopen($ruta, "w");

    $contadorGanadores = 0;
    foreach ($jugadores as $nombre) {
        $punto = $puntos[$nombre];
        $premio = isset($premios[$nombre]) ? $premios[$nombre] : "0.00";
        $linea = obtenerIniciales($nombre) . "#" . $punto . "#" . $premio . "\n";
        fwrite($archivo, $linea);

        if (in_array($nombre, $ganadores))
            $contadorGanadores++;
    }
    fwrite($archivo, "TOTAL PREMIOS#$contadorGanadores#" . array_sum($premios) . "\n");
    fclose($archivo);
}

//**************************************************************************************************************************************************
// calcula los puntos de las cartas
// @param array $cartas
// @return float
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

//**************************************************************************************************************************************************
// reparte cartas sin repetir
// @param int $numCartas
// @param array $jugadores
// @return array
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

//**************************************************************************************************************************************************
// mira quien gana y cuanto gana
// @param array $jugadores
// @param array $puntos
// @param float $apuesta
// @return array
function determinarGanadores($jugadores, $puntos, $apuesta)
{
    $premios = [];
    $ganadores = [];

    // ordeno los puntos de mayor a menor sin perder nombres
    arsort($puntos);

    // miro si hay alguno con 7.5 justo
    $hayExacto = false;
    foreach ($puntos as $nombre => $valor) {
        if ($valor == 7.5) {
            $hayExacto = true;
            $ganadores[] = $nombre;
        }
    }

    // si no hay 7.5 busco el mas alto sin pasarse
    if (!$hayExacto) {
        $max = 0;
        foreach ($puntos as $nombre => $valor) {
            if ($valor <= 7.5 && $valor > $max) {
                $max = $valor;
            }
        }
        // vuelvo a mirar quienes tienen ese max
        foreach ($puntos as $nombre => $valor) {
            if ($valor == $max) {
                $ganadores[] = $nombre;
            }
        }
        $reparto = $apuesta * count($jugadores) * 0.5;
    } else {
        $reparto = $apuesta * count($jugadores) * 0.8;
    }

    // reparto premio entre los ganadores
    if (count($ganadores) > 0) {
        $porJugador = $reparto / count($ganadores);
        foreach ($ganadores as $nombre) {
            $premios[$nombre] = $porJugador;
        }
    }

    return [$ganadores, $premios];
}

//**************************************************************************************************************************************************
// limpia los datos del formulario
// @param string $dato
// @return string
function limpiar($dato)
{
    return htmlspecialchars(trim($dato));
}
