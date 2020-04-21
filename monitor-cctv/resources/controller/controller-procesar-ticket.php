<?php

session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
require 'registra-bitcora.php';


$id_ticket = "";

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if ($id_ticket != "") {
    $sql = "update tickets set ultima_actualizacion=now(), estatus=5 where id_ticket='$id_ticket';";
    $resultado = $mysqli->query($sql) or die("error: " + $mysqli->error);

    if ($resultado == '1') {
        registra_bitacora($id_ticket, 0, $id_ticket, "Cambió estatus a Activo", "", "");
        echo "procesado";
    } else {
        echo $resultado;
    }
} else {
    echo "error";
}
