<?php

session_start();
require '../connection/conexion.php';
require '../controller/registra-bitcora.php';
$mysqli->query("SET NAMES 'utf8'");


$id = $_POST['id'];

$query2 = "SELECT * from usr_ticketauto where id_ticket= $id";



$r1 = $mysqli->query($query2);
$copias = $r1->fetch_assoc();

if ($copias['estr'] == '1') {
    echo 1;
} else {
    echo 0;
}

