<?php

function notificaciones($tipo,$idusuario) {

    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $query = "select * from ntf_kardex_usuario where id_usuario='$idusuario' and id_tipo ='$tipo'";
    //echo $query;
    $resultSetF = $mysqli->query($query);
    $row_cnt = $resultSetF->num_rows;

    return $row_cnt;
}
