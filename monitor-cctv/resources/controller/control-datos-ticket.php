<?php

function actualiza_datos_ticket($id_ticket, $estatus) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    require 'registra-bitcora.php';
   
    $sql = "UPDATE tickets SET estatus='$estatus', ultima_actualizacion=now() WHERE id_ticket='$id_ticket';";

    echo $mysqli->query($sql) or die("Error: " + $mysqli->error);

    registra_bitacora($id_ticket, "1", "1", "Cambió estatus");
    
}