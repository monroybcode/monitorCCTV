<?php

require './enviar_correo.php';
$mensaje = "correo enviado desde service_desk_app";
$mensaje.= "<br><br><a href='http://localhost/ServiceDesk/ticket.php?ticket=1'>Atender ticket</a>";
echo enviar_correo("Ticket asignado", $mensaje, "mavega@starmedica.com");
