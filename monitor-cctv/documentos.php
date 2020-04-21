<?php

require('resources/connection/conexion.php');
$mysqli->query("SET NAMES 'utf8'");

$id_doc = $_GET['id_doc'];

$sql = "SELECT documento, content_type, file_name 
                FROM documentos
                WHERE id_documento = $id_doc";

$resultado = $mysqli->query($sql);
$fila = $resultado->fetch_assoc();

$contenido = $fila["documento"];

header("Content-Type: " . $fila['content_type']);
header('Content-Disposition: inline;filename="'.$fila["file_name"].'"');

print $contenido;
?>
