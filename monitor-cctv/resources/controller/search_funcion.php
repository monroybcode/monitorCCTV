<?php

//if (session_status() != PHP_SESSION_ACTIVE){
//    session_start();
//}

require('../connection/conexion.php');
$mysqli->query("SET NAMES 'utf8'");

$search = $_REQUEST['term'];


$query = "SELECT f.id_funcion, f.nombre "
        . "FROM funciones f "
        . "WHERE f.nombre like '" . $search . "%' AND f.ind_activo=1 "
        . "ORDER BY f.nombre ASC";
$resultado = $mysqli->query($query);

$rows = array();

while ($fila = $resultado->fetch_assoc()) {
    $rows[] = $fila;
}

print json_encode($rows);
?>