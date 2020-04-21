<?php

function registra_bitacora($id_ticket, $cod_origen, $id_origen, $descripcion, $campos, $cambios) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "insert into bitacora(ticket, fecha_registro, cod_origen, id_origen, descripcion, usuario_evt, campos, cambios) "
            . "values('$id_ticket', now(), '$cod_origen', '$id_origen', '$descripcion', '" . $_SESSION['id_usuario'] . "', '$campos', '$cambios');";
    
    $mysqli->query($sql);
}
