<?php

// Limpia cualquier dato recibido del formulario
function limpiar($dato)
{
    return htmlspecialchars(trim($dato));
}
