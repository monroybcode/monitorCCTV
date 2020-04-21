<?php

if (!isset($_SESSION)) {
    session_start();
}
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");


$html = "";
$elegido = $_POST["elegido"];

$sql2 = "SELECT * FROM categoria where categoria_padre = $elegido";
echo $sql2;

$resultSet2 = $mysqli->query($sql2);

$evento2 = $resultSet2->num_rows;


while ($fila2 = $resultSet2->fetch_assoc()) {
    echo "<option value='" . $fila2['id'] . "'>" . $fila2['nombre'] . "</option>";
}



