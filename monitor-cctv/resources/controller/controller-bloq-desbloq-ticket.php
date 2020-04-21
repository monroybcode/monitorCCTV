<?php

session_start();

$funcion = "";
$id_ticket = "";
$id_usuario = "";

if (isset($_POST['funcion'])) {
    $funcion = $_POST['funcion'];
}
if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}
if (isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
}

if ($funcion === '1') {
    bloquear($id_usuario, $id_ticket);
} else if ($funcion === '2') {
    desbloquear($id_usuario, $id_ticket);
} else if ($funcion === '3') {
    verificar_ticket($id_ticket);
}

function bloquear($id_usuario, $id_ticket) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "INSERT INTO bloqueos(id_ticket, id_usuario, fecha) values('" . $id_ticket . "', '" . $id_usuario . "', now());";
    echo $mysqli->query($sql);
}

function desbloquear($id_usuario, $id_ticket) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "DELETE FROM bloqueos WHERE id_ticket='" . $id_ticket . "' and id_usuario='" . $id_usuario . "';";
    echo $mysqli->query($sql) or die("error: " + $mysqli->error);
}

function verificar_ticket($id_ticket) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select * from bloqueos where id_ticket='" . $id_ticket . "';";
    $resultado = $mysqli->query($sql);

    $num_res = $resultado->num_rows;

    if ($num_res > 0) {
        echo "bloqueado";
    } else {
        echo "desbloqueado";
    }
}
