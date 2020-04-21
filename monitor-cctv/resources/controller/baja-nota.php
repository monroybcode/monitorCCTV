<?php

session_start();
require '../connection/conexion.php';
require '../controller/registra-bitcora.php';

$id_nota = "";
$id_ticket = "";

if (isset($_POST['id_nota'])) {
    $id_nota = $_POST['id_nota'];
}
if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

$mysqli->query("SET NAMES 'UTF8'");

$sql = "update notas set ind_activo=0 where id_nota='$id_nota';";
echo $mysqli->query($sql) or die("Ocurrio un error: " + $mysqli->error);

$sql = "update tickets set ultima_actualizacion=now() WHERE id_ticket='$id_ticket';";
$mysqli->query($sql);

registra_bitacora($id_ticket, "0", $id_nota, "Se dio de baja nota", "", "");
