<?php

session_start();

include '../connection/conexion.php';
include './enviar_correo.php';
include '../../utils/constantes.php';
$mysqli->query("SET NAMES 'utf8'");

$id = $_POST['user'];
$email = $_POST['email'];
$newPass = '';
$sql = "SELECT * FROM usuarios WHERE login = '$id' AND email = '$email'";

$res = mysqli_query($mysqli, $sql);
$num_row = mysqli_num_rows($res);


if ($num_row == 1) {
    $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $cad = "";
    for ($i = 0; $i < 12; $i++) {
        $newPass.= substr($str, rand(0, 62), 1);
    }
    
    $sql = "UPDATE usuarios SET password='" . sha1($newPass) . "' WHERE login = '$id';";  
    $mysqli->query($sql);

    $body = "<hr>" . NOMBRE_SISTEMA . "<br>"
            . "Su nueva contraseña es:<br>" . $newPass . "<br><br>"
            . "<br><br>Siga el siguiente enlace para ingresar:"
            . "<br><br>"
            . URL_SISTEMA;

    $mail = enviar_correo('Reinicio de password', $body, $email);


    if (!$mail) {
        echo 'nomail';
    } else {
        echo 'true';
    }
} else {
    echo 'false';
}
?>