<?php

session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'utf8'");

$id_ticket = "";
$id_nota = "";

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}
if (isset($_POST['id_nota']) && $_POST['id_nota'] != "") {
    $id_nota = $_POST['id_nota'];
}

$nombre_temporal = $_FILES["archivo_nuevo"]["tmp_name"];
$nombre_archivo = $_FILES["archivo_nuevo"]["name"];
$tamanio = $_FILES["archivo_nuevo"]["size"];
$tipo = $_FILES["archivo_nuevo"]["type"];

if ($tamanio != 0) {
    $fp = fopen($nombre_temporal, "rb");
    $contenido = fread($fp, $tamanio);
    $contenido = addslashes($contenido);
    fclose($fp);

    $sql = "insert into documentos(ticket, documento, content_type, file_name, usuario_registra, fecha_registro, ind_activo) values('$id_ticket', '$contenido', '$tipo', '$nombre_archivo', '" . $_SESSION['id_usuario'] . "', now(), 1)";

    $array['correcto'] = $mysqli->query($sql) or die("error: " + $mysqli->error);

    if ($array['correcto'] === true) {
        $array['nombre_doc'] = $nombre_archivo;
        $array['id_doc'] = $mysqli->insert_id;
        date_default_timezone_set('America/Mexico_City');
        $array['fecha'] = date("d/m/Y H:i");
        $sql = "update notas set archivo='1' where id_nota='$id_nota';";
        $mysqli->query($sql);
    }

    echo json_encode($array);
} else {
    $array['correcto'] = false;
    echo json_encode($array);
}





