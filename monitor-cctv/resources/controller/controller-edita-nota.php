<?php

session_start();
require '../connection/conexion.php';
require '../controller/registra-bitcora.php';
$mysqli->query("SET NAMES 'utf8'");

$id_nota = "";
$nota = "";
$id_ticket = "";

if (isset($_POST['id_nota_hdn'])) {
    $id_nota = $_POST['id_nota_hdn'];
}

if (isset($_POST['txt-note'])) {
    $nota = $_POST['txt-note'];
}

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if ($id_nota != "" && $nota != "") {
    $sql = "select nota from notas where id_nota='$id_nota'";
    $res = $mysqli->query($sql);
    $fila = $res->fetch_assoc();
    $nota_ant = $fila['nota'];
    
    $sql = "update notas set nota='$nota' where id_nota='$id_nota';";
    $resultado = $mysqli->query($sql) or die("error: " . $mysqli->error);

    if ($resultado == "1") {
        $sql = "update tickets set ultima_actualizacion=now() where id_ticket='$id_ticket'";
        $mysqli->query($sql);

        registra_bitacora($id_ticket, 0, $id_nota, "Se modificó nota", "", $nota_ant);
    }

    echo $resultado;
} else {
    echo "error";
}