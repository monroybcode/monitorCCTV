<?php

session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'utf8'");

$id_ticket = "";
$nota = "";

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if (isset($_POST['txt-note'])) {
    $nota = $_POST['txt-note'];
}

if ($id_ticket != "" && $nota != "") {
    $sql = "insert into notas(ticket, nota, usuario_registra, fecha_registro, ind_activo) values('$id_ticket', '$nota', '" . $_SESSION['id_usuario'] . "', now(), 1);";
    $mysqli->query($sql);
    $id_nota = $mysqli->insert_id;
    $array['id_nota'] = $id_nota;

    $array['correcto'] = true;
    if ($array['correcto'] === true) {
        date_default_timezone_set('America/Mexico_City');
        $array['usuario'] = $_SESSION['nombre'];
        $array['fecha'] = date("d/m/Y H:i");
        echo json_encode($array);
    }
} else {
    $array['correcto'] = false;
    echo json_encode($array);
}



