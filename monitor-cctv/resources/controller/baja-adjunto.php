<?php

session_start();
require '../connection/conexion.php';
require '../controller/registra-bitcora.php';

$id_documento = "";
$id_ticket = "";

if (isset($_POST['id_documento'])) {
    $id_documento = $_POST['id_documento'];
}
if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

$mysqli->query("SET NAMES 'UTF8'");

$sql = "update documentos set ind_activo=0 where id_documento='$id_documento';";
echo $mysqli->query($sql) or die("Ocurrio un error: " + $mysqli->error);

$sql = "update tickets set ultima_actualizacion=now() WHERE id_ticket='$id_ticket';";
$mysqli->query($sql);

registra_bitacora($id_ticket, "0", $id_documento, "Se dio de baja documento", "", "");
