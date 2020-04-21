<?php

session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
require 'registra-bitcora.php';

$id_ticket = "";
$operacion = "";
$sql = "";

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if (isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];
}

if ($operacion == "Autorizar") {
    $sql = "update tickets set estatus=4, ultima_actualizacion=now(), usuario_anterior=NULL  where id_ticket='$id_ticket';";
    
} else if ($operacion == "Rechazar") {
    $sqlUe = "select usuario_anterior from tickets where id_ticket='$id_ticket';";
    $resultadoUe = $mysqli->query($sqlUe);
    $fila = $resultadoUe->fetch_assoc();

    $sql = "update tickets set estatus=4, ultima_actualizacion=now(), usuario_anterior=NULL, usuario_actual='" . $fila['usuario_anterior'] . "' where id_ticket='$id_ticket';";
}

echo $mysqli->query($sql);
registra_bitacora($id_ticket, 0, $id_ticket, "Cambió estatus a Pendiente", "", "");
