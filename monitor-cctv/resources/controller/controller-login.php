<?php

session_start();

require('../connection/conexion.php');
$mysqli->query("SET NAMES 'utf8'");

$usuario = "";
$password = "";

if (isset($_POST['password'])) {
    $password = $_POST['password'];
}
if (isset($_POST['usuario'])) {
    $usuario = $_POST['usuario'];
}

//$encripPass = sha1($password);
//$sql = "SELECT * FROM usuarios WHERE lower(login)=lower('" . $usuario . "') and password='" . $encripPass . "' and ind_activo=1 limit 1";

$sql = "SELECT * FROM usuarios WHERE lower(login)=lower('" . $usuario . "') and ind_activo=1 limit 1";
//echo $sql;
$resultado = $mysqli->query($sql);
$num_filas = $resultado->num_rows;

$fila = $resultado->fetch_assoc();

if ($num_filas == 1) {
    $_SESSION['id_usuario'] = $fila['id_usuario'];
    $_SESSION['nombre'] = $fila['nombre'];
    $_SESSION['rol'] = $fila['rol'];
    $_SESSION['usr_email'] = $fila['email'];
    $_SESSION['puesto'] = $fila['puesto'];

    $arrayFunciones = array();
    $arrayFuncionesUsuario = array();
    $array_grupos = array();
    $array_categorias_usr = array();
    $array_hospitales = array();
    $array['existe'] = true;

    $sqlFuncionesR = "select f.nombre "
            . "from funciones f "
            . "inner join rol_funciones rf on f.id_funcion=rf.id_funcion "
            . "where rf.id_rol='" . $fila['rol'] . "' ";
    $resultadoFuncionesR = $mysqli->query($sqlFuncionesR);
    while ($filaFuncionesR = $resultadoFuncionesR->fetch_assoc()) {
        array_push($arrayFunciones, $filaFuncionesR['nombre']);
    }

    $sqlFuncionesU = "select f.nombre "
            . "from funciones f "
            . "inner join usuario_funciones uf on f.id_funcion=uf.id_funcion "
            . "where uf.id_usuario='" . $fila['id_usuario'] . "' ";
    $resultadoFuncionesU = $mysqli->query($sqlFuncionesU);
    while ($filaFuncionesU = $resultadoFuncionesU->fetch_assoc()) {
        array_push($arrayFuncionesUsuario, $filaFuncionesU['nombre']);
    }



    $sql = "select descripcion from catalogo_valor where id='" . $fila['rol'] . "' and catalogo='1';";
    $resultado = $mysqli->query($sql);
    $filaR = $resultado->fetch_assoc();

    $sqlGrupos = "select id_grupo from usuario_grupos where id_usuario = '" . $fila['id_usuario'] . "'";
    $resultadoGrupos = $mysqli->query($sqlGrupos);
    while ($filaGrupos = $resultadoGrupos->fetch_assoc()) {
        array_push($array_grupos, $filaGrupos['id_grupo']);
    }

    $sqlCategorias = "select id_categoria from usuario_categorias where id_usuario = '" . $fila['id_usuario'] . "'";
    $resultadoCategorias = $mysqli->query($sqlCategorias);
    while ($filaCategorias = $resultadoCategorias->fetch_assoc()) {
        array_push($array_categorias_usr, $filaCategorias['id_categoria']);
    }

    $sqlhospitales = "select hospital from usuario_hospital where usuario = '" . $fila['id_usuario'] . "'";
    $resultadohospitales = $mysqli->query($sqlhospitales);
    while ($filahospitales = $resultadohospitales->fetch_assoc()) {
        array_push($array_hospitales, $filahospitales['hospital']);
    }

    $_SESSION['nv'] = '7';
    $_SESSION['nombre_rol'] = $filaR['descripcion'];
    $_SESSION['funciones'] = $arrayFunciones;
    $_SESSION['usr_funciones'] = $arrayFuncionesUsuario;
    $_SESSION['usr_grupos'] = $array_grupos;
    $_SESSION['usr_categorias'] = $array_categorias_usr;
    $_SESSION['usr_hospitales'] = $array_hospitales;


    if (in_array("ver_dashboard", $arrayFunciones)) {
        $_SESSION['home'] = "tablero-control.php";
    } else {
        $_SESSION['home'] = "tickets-preview.php";
    }

    $array['home'] = $_SESSION['home'];

    $sql = "update usuarios set ultima_visita=now() where id_usuario='" . $fila['id_usuario'] . "'";
    $mysqli->query($sql);

    echo json_encode($array);
} else {
    $array['existe'] = false;

    echo json_encode($array);
}
?>