<?php

require '../connection/conexion.php';
$mysqli->query("SET NAMES 'utf8'");

$evento = $_POST['evento'];
$area = $_POST['area'];
$unidad = $_POST['unidad'];



$query3 = "SELECT 
    usuarios.nombre, usuarios.rol, usuarios.email, usuario_hospital.hospital
FROM
    usuarios
        LEFT JOIN
    usuario_hospital ON id_usuario = usuario 
    where rol = '3' and hospital ='$unidad' and desactivar = '0' ";

//echo $query2;
$resultado3 = $mysqli->query($query3);




while ($res2 = $resultado3->fetch_assoc()) {
    $email = $res2['email'];
    $nombre = $res2['nombre'];
    if ($email != NULL) {
        $datos[] = array('nombre' => $nombre, 'email' => $email);
    }
}


echo json_encode($datos);
?>