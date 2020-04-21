<?php

require '../connection/conexion.php';
$mysqli->query("SET NAMES 'utf8'");

$evento = $_POST['evento'];
$area = $_POST['area'];
$unidad = $_POST['unidad'];



$query2 = "SELECT 
    usuario_categorias.id_categoria,
    usuario_categorias.id_usuario,
    usuario_hospital.hospital,
    ntf_kardex_usuario.id_tipo,
    usuario_grupos.id_grupo,
    usuarios.email,
    usuarios.nombre
FROM
    usuario_categorias
        LEFT JOIN
    usuario_hospital ON usuario_categorias.id_usuario = usuario_hospital.usuario
        LEFT JOIN
    ntf_kardex_usuario ON usuario_categorias.id_usuario = ntf_kardex_usuario.id_usuario
        LEFT JOIN
    usuario_grupos ON usuario_categorias.id_usuario = usuario_grupos.id_usuario
     LEFT JOIN
    usuarios ON usuario_categorias.id_usuario = usuarios.id_usuario and usuarios.desactivar = 0
WHERE
        id_categoria = '$evento'
        AND hospital = '$unidad'
        AND id_tipo in  ('5','10')
        AND id_grupo = '$area'";

//echo $query2;
$resultado2 = $mysqli->query($query2);

$datos[] = array('nombre' => '', 'email' => '', 'tipo' => '8');
while ($res2 = $resultado2->fetch_assoc()) {
    $email = $res2['email'];
    $tipo = $res2['id_tipo'];
    $nombre = $res2['nombre'];
    if ($email != NULL) {
        $datos[] = array('nombre' => $nombre, 'email' => $email, 'tipo' => $tipo);
    }
}


echo json_encode($datos);
?>