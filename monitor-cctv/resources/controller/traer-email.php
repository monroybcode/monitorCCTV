<?php

require '../connection/conexion.php';
$mysqli->query("SET NAMES 'utf8'");




$query2 = "SELECT email,nombre FROM usuarios where desactivar = '0';";
$resultado2 = $mysqli->query($query2);


while ($res2 = $resultado2->fetch_assoc()) {
   $email= $res2['email'];
   $nombre= $res2['nombre'];
    
    $datos[] = $nombre." (".$email.")";
      
}

echo json_encode($datos);
?>