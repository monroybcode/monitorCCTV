<?php

$opcion = "";
$id_categoria = "";
$subcategoria = "";
$id_ticket = "";

if (isset($_POST['opcion'])) {
    $opcion = $_POST['opcion'];
}
if (isset($_POST['id_categoria'])) {
    $id_categoria = $_POST['id_categoria'];
}
if (isset($_POST['subcategoria'])) {
    $subcategoria = $_POST['subcategoria'];
}
if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if ($opcion == "consulta_subcategoria") {
    consulta_subcategoria($id_categoria, $subcategoria);
} else if ($opcion == "consulta_telefono_hospital") {
    consulta_num_hosp($_POST['id_hospital']);
} else if ($opcion == "hospuser") {
    consulta_hospitales_usuario();
} else if ($opcion == "hosp") {
    consulta_hospitales();
} else if ($opcion == "sql_grupos") {
    consulta_grupos();
} else if ($opcion == "gruposuser") {
    consulta_grupos_usuario();
} else if ($opcion == "ntf") {
    consulta_notificaciones_usuario();
} else if ($opcion == "sql_categorias") {
    consulta_categorias_sql();
} else if ($opcion == "categoriasuser") {
    consulta_categorias_usuario();
} else if ($opcion == "alta_usuario") {
    alta_usuario();
} else if ($opcion == "resetPass") {
    reset_pass();
} else if ($opcion == "consulta_usuario_registra") {
    consulta_ticket_usuario_registra($id_ticket);
} else if ($opcion == "consulta_admin_des") {
    consulta_admin_des($id_ticket);
} else if ($opcion == "consulta_actualizador") {
    consulta_actualizador();
} else if ($opcion == "alta_rol") {
    alta_rol();
} else if ($opcion == "guarda_funciones_rol") {
    guarda_funciones_rol();
} else if ($opcion == "consulta_funciones_bd") {
    consulta_funciones_bd();
} else if ($opcion == "consulta_categoria_padre") {
    consulta_categoria_padre($id_categoria);
} else if ($opcion == "consulta_categorias_hijo") {
    consultaCategoriasHijo($id_categoria);
} else if ($opcion == "alta_categoria") {
    alta_categoria();
} else if ($opcion == "actualizarselectpadre") {
    actualizarselectpadre();
} else if ($opcion == "buscar_subcategorias") {
    buscar_subcategorias();
} else if ($opcion == "traer_detalle_categoria") {
    traer_detalle_categoria($id_categoria);
} else if ($opcion == "ntf_tipos") {
    consulta_ntf_tipos();
} else if ($opcion == "eliminar") {
    eliminar_usuario();
}else if ($opcion == "consulta_admin_des") {
    consulta_admin_des($id_ticket);
} 
/* * ************************************ */

function eliminar_usuario() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    if (isset($_POST['id_usuario'])) {
        $id_usuario = $_POST['id_usuario'];
    }

    $sql = "update usuarios set desactivar = '1' where id_usuario=$id_usuario;";
    $resultSet = $mysqli->query($sql);

    echo true;
}

/* * ************************************ */

function consulta_unidades_negocio() {
    require 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select * from hospital;";
    $resultSet = $mysqli->query($sql);

    while ($fila = $resultSet->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
    }
}

function consulta_categorias() {
    require 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select * from categoria where esta_activo=1 and categoria_padre is null;";
    $resultSet = $mysqli->query($sql);

    while ($fila = $resultSet->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
    }
}

function consultaCategoriasHijo($categoriaPadre) {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select * from categoria where esta_activo=1 and categoria_padre = '$categoriaPadre'";
    $resultSet = $mysqli->query($sql);

    while ($fila = $resultSet->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
    }
}

function consulta_categorias_asignadas_usuario() {
    session_start();
    require 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select distinct(cat.categoria1), cat.nombre "
            . " from usuario_categorias uc "
            . " inner join (select c1.nombre, c1.id as categoria1 , c2.id as categoria2  "
            . " from categoria c1 inner join categoria c2 on c1.id=c2.categoria_padre and c1.categoria_padre is null and c1.esta_activo=1) cat "
            . " on cat.categoria2=uc.id_categoria "
            . " where uc.id_usuario='" . $_SESSION['id_usuario'] . "';";

    $resultSet = $mysqli->query($sql);

    while ($fila = $resultSet->fetch_assoc()) {
        echo "<option value='" . $fila['categoria1'] . "'>" . $fila['nombre'] . "</option>";
    }
}

function consulta_categorias_asignadas_usuario_2() {
    //session_start();
    require 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select id, nombre 
            from categoria c
            join usuario_categorias uc on c.id = uc.id_categoria
            where c.esta_activo=1 and uc.id_usuario = '" . $_SESSION['id_usuario'] . "'
            order by c.nombre";
    //echo $sql;
    $resultSet = $mysqli->query($sql);

    while ($fila = $resultSet->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
    }
}

function consulta_categoria_padre($id_categoria) {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    //$sql = "SELECT nombre,id from categoria where id=(select categoria_padre from categoria where id='$id_categoria');";
    $sql = "SELECT t2.nombre as nombre_padre
                , t2.id as id_padre
                , ifnull(t1.id_grupo, 1) as grupo_categ
                , ifnull(t3.total_hijos,0) as total_hijos
                , ifnull(t1.desc_ayuda, '') as desc_ayuda
                , ifnull(t1.url_formatos, '') as url_formatos
            from categoria t1
            join categoria t2 on t2.esta_activo = 1 and t1.categoria_padre = t2.id
            left join (
                select count(id) as total_hijos, categoria_padre
                from categoria
                where esta_activo = 1 
                group by categoria_padre
            ) t3 on t1.id = t3.categoria_padre
            where t1.id='$id_categoria'";
    $resultado = $mysqli->query($sql);
    $fila = $resultado->fetch_assoc();

    $datos_categoria['name'] = $fila['nombre_padre'];
    $datos_categoria['id'] = $fila['id_padre'];
    $datos_categoria['gpo_categ'] = $fila['grupo_categ'];
    $datos_categoria['tot_hijos'] = $fila['total_hijos'];
    $datos_categoria['desc_ayuda'] = $fila['desc_ayuda'];
    $datos_categoria['url_formatos'] = $fila['url_formatos'];

    echo json_encode($datos_categoria);
}

function consulta_estatus() {
    require 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select id, descripcion, catalogo from catalogo_valor where catalogo=2;";
    $resultSet = $mysqli->query($sql);

    while ($fila = $resultSet->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['descripcion'] . "</option>";
    }
}

function consulta_prioridad() {
    require 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select id, descripcion, catalogo from catalogo_valor where catalogo=4;";
    $resultSet = $mysqli->query($sql);

    while ($fila = $resultSet->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['descripcion'] . "</option>";
    }
}

function consulta_subcategoria($id_categoria, $subcategoria) {
    session_start();
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "";

    if ($subcategoria == 2) {
        $sql = "select id,nombre from categoria where esta_activo=1 and categoria_padre='" . $id_categoria . "' and id in(select id_categoria from usuario_categorias where id_usuario='" . $_SESSION['id_usuario'] . "');";
    } else {
        $sql = "select * from categoria where esta_activo=1 and categoria_padre='" . $id_categoria . "';";
    }

    $resultSet = $mysqli->query($sql);

    $num_filas = $resultSet->num_rows;

    $array['num_resultados'] = $num_filas;

    $opciones = "";

    $opciones .= "<option value=''> - Seleccione tipo de operación - </option>";

    while ($fila = $resultSet->fetch_assoc()) {
        $opciones .= "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
    }

    $array['opciones'] = $opciones;

    echo json_encode($array);
}

function consulta_num_hosp($hospital) {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $telefono = "";
    $sql = "select telefono from hospital where id='" . $hospital . "';";
    $resultSet = $mysqli->query($sql);

    $fila = $resultSet->fetch_assoc();
    $telefono = $fila['telefono'];
    echo $telefono;
}

function consulta_usuarios() {
    require 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select * from usuarios where ind_activo=1;";
    $resultSet = $mysqli->query($sql);

    while ($fila = $resultSet->fetch_assoc()) {
        echo "<option value='" . $fila['id_usuario'] . "'>" . $fila['nombre'] . "</option>";
    }
}

function contar_tickets($con, $restriccion) {
    require $con . 'connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select count(id_ticket) as total from tickets " . $restriccion;
//    echo $sql;
    $resultado = $mysqli->query($sql);
    $fila = $resultado->fetch_assoc();

    return $fila['total'];
}

function consulta_hospitales() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select * from hospital;";
    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
    }
}

function consulta_hospitales_adm_usr() {
    require 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select * from hospital;";
    $resultado = $mysqli->query($sql);

    echo "<option value='' selected>Seleccione hospital</option>";
    while ($fila = $resultado->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
    }
}

function consulta_roles_adm_usr() {
    require 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select id, descripcion
            from catalogo_valor
            where catalogo = 1";
    $resultado = $mysqli->query($sql);

    echo "<option value='' selected>Seleccione rol</option>";
    while ($fila = $resultado->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['descripcion'] . "</option>";
    }
}

function consulta_hospitales_usuario() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $id_usuario = "";

    if (isset($_POST['id_usuario'])) {
        $id_usuario = $_POST['id_usuario'];
    }

    $hospitalesUsuario = "";
    $hospitalesNoAsignados = "";

    $sql = "select * from usuario_hospital uh inner join hospital h on uh.hospital=h.id where uh.usuario='$id_usuario';";
    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        $hospitalesUsuario .= "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
    }

    $sql = "select * from hospital where id not in (select h.id from usuario_hospital uh inner join hospital h on uh.hospital=h.id where uh.usuario='$id_usuario');";
    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        $hospitalesNoAsignados .= "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
    }

    $array = [
        "asignados" => $hospitalesUsuario,
        "noasignados" => $hospitalesNoAsignados
    ];

    echo json_encode($array);
}

function consulta_grupos() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select idareas, nombre_area from areas;";
    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        echo "<option value='" . $fila['idareas'] . "'>" . $fila['nombre_area'] . "</option>";
    }
}

function consulta_grupos_usuario() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $id_usuario = "";

    if (isset($_POST['id_usuario'])) {
        $id_usuario = $_POST['id_usuario'];
    }

    $gruposUsuario = "";
    $gruposNoAsignados = "";

    $sql = "SELECT g.idareas,g.nombre_area
FROM
    usuario_grupos ug
        INNER JOIN
    areas g ON ug.id_grupo = g.idareas
WHERE
    ug.id_usuario = '$id_usuario'";
    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        $gruposUsuario .= "<option value='" . $fila['idareas'] . "'>" . $fila['nombre_area'] . "</option>";
    }

    $sql = "SELECT 
    g.idareas, g.nombre_area
FROM
    areas g
WHERE
    idareas NOT IN (SELECT 
            g.idareas
        FROM
            usuario_grupos ug
                INNER JOIN
            areas g ON ug.id_grupo = g.idareas
        WHERE
            ug.id_usuario = '$id_usuario')";
    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        $gruposNoAsignados .= "<option value='" . $fila['idareas'] . "'>" . $fila['nombre_area'] . "</option>";
    }

    $array = [
        "asignados" => $gruposUsuario,
        "noasignados" => $gruposNoAsignados
    ];

    echo json_encode($array);
}

function consulta_categorias_sql() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    //$sql = "SELECT CONCAT(c1.nombre, ' - ', c2.nombre) AS categoria, c2.id FROM categoria c1 INNER JOIN categoria c2 ON c2.categoria_padre=c1.id AND c1.categoria_padre IS NULL;";
    /* $sql = "SELECT c2.nombre AS categoria, c2.id 
      FROM categoria c1
      INNER JOIN categoria c2 ON c2.categoria_padre=c1.id AND c1.categoria_padre IS NULL
      ORDER BY c2.nombre"; */
    $sql = "SELECT * FROM categoria where categoria_padre is null";
    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
    }
}

function consulta_ntf_tipos() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "SELECT * FROM ntf_tipo";
    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        echo "<option value='" . $fila['id_tipo'] . "'>" . $fila['descripcion_tipo'] . "</option>";
    }
}

function consulta_categorias_usuario() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $id_usuario = "";

    if (isset($_POST['id_usuario'])) {
        $id_usuario = $_POST['id_usuario'];
    }

    $categoriasUsuario = "";
    $categoriasNoAsignados = "";

    $sql = "SELECT 
    c2.nombre AS categoria, c2.id
FROM
    categoria c1
        INNER JOIN
    categoria c2 ON   c1.id = c2.id
      
        INNER JOIN
    usuario_categorias uc ON uc.id_categoria = c2.id AND uc.id_usuario = '$id_usuario'
WHERE
    c1.esta_activo = TRUE
        AND c2.esta_activo = TRUE
ORDER BY c2.nombre";


    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        $categoriasUsuario .= "<option value = '" . $fila['id'] . "'>" . $fila['categoria'] . "</option>";
    }

    $sql = "SELECT 
    c2.nombre AS categoria, c2.id
FROM
    categoria c1
        INNER JOIN
    categoria c2 ON c2.id = c1.id
       
WHERE
    c2.id NOT IN (SELECT 
            id_categoria
        FROM
            usuario_categorias
        WHERE
            id_usuario = '$id_usuario')
        AND c1.esta_activo = TRUE
      
ORDER BY c2.nombre";


    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        $categoriasNoAsignados .= "<option value = '" . $fila['id'] . "'>" . $fila['categoria'] . "</option>";
    }

    $array = [
        "asignados" => $categoriasUsuario,
        "noasignados" => $categoriasNoAsignados
    ];

    echo json_encode($array);
}

function consulta_notificaciones_usuario() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $id_usuario = "";

    if (isset($_POST['id_usuario'])) {
        $id_usuario = $_POST['id_usuario'];
    }

    $ntfUsuario = "";
    $ntfNoAsignados = "";

    $sql = "SELECT 
                ntf_kardex_usuario.id_ntf_kardex,
                ntf_kardex_usuario.id_tipo,
                ntf_tipo.descripcion_tipo
            FROM
                ntf_kardex_usuario
                    INNER JOIN
                ntf_tipo ON ntf_kardex_usuario.id_tipo = ntf_tipo.id_tipo
            WHERE
                id_usuario = '" . $id_usuario . "'";


    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        $ntfUsuario .= "<option value = '" . $fila['id_tipo'] . "'>" . $fila['descripcion_tipo'] . "</option>";
    }

    $sql = "SELECT 
*
FROM
    ntf_tipo
WHERE
    ntf_tipo.id_tipo NOT IN (SELECT 
            id_tipo 
        FROM
            ntf_kardex_usuario
        WHERE
            id_usuario = '" . $id_usuario . "') and id_tipo > 2";
    $resultado = $mysqli->query($sql);



    while ($fila = $resultado->fetch_assoc()) {
        $ntfNoAsignados .= "<option value = '" . $fila['id_tipo'] . "'>" . $fila['descripcion_tipo'] . "</option>";
    }

    $array = [
        "asignados" => $ntfUsuario,
        "noasignados" => $ntfNoAsignados
    ];

    echo json_encode($array);
}

function alta_usuario() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $nombre = "";
    $login = "";
    $rol = "";
    $hospitales = "";
    $idusuario = "";
    $activo = "";
    $email = "";
    $encripPass = "";
    $grupos = "";
    $categorias = "";
    $ntf = "";
    $puesto = "";
    $notificaciones = "";

    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
    }
    if (isset($_POST['login'])) {
        $login = $_POST['login'];
    }
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }
    if (isset($_POST['rol'])) {
        $rol = $_POST['rol'];
    }
    if (isset($_POST['puesto'])) {
        $puesto = $_POST['puesto'];
    }

    if (isset($_POST['hospitales'])) {
        $hospitales = explode("|", $_POST['hospitales']);
    }

    if (isset($_POST['grupos'])) {
        $grupos = explode("|", $_POST['grupos']);
    }

    if (isset($_POST['categorias'])) {
        $categorias = explode("|", $_POST['categorias']);
    }

    if (isset($_POST['ntf'])) {
        $ntf = explode("|", $_POST['ntf']);
    }

    if (isset($_POST['id_usuario']) && $_POST['id_usuario'] !== "") {
        $idusuario = $_POST['id_usuario'];
    }

    if (isset($_POST['activo'])) {
        $activo = "1";
    } else {
        $activo = "0";
    }
    if (isset($_POST['notificaciones'])) {
        $notificaciones = "1";
    } else {
        $notificaciones = "0";
    }


    $encripPass = sha1($login);

    if ($idusuario != "") {

        $sql = "update usuarios set nombre = '$nombre', rol = '$rol', puesto='$puesto', login = '$login', email = '$email', ind_activo = '$activo', ind_notificaciones = '$notificaciones' where id_usuario = '$idusuario';";
        $resultado = $mysqli->query($sql);

        $sql = "delete from usuario_grupos where id_usuario = '$idusuario';";
        $mysqli->query($sql);


        foreach ($grupos as $grupo) {
            if ($grupo != "") {
                $sql = "insert into usuario_grupos(id_usuario, id_grupo) values('$idusuario', '$grupo')";
                $mysqli->query($sql);
            }
        }

        $sql = "delete from usuario_hospital where usuario = '$idusuario';";
        $mysqli->query($sql);

        foreach ($hospitales as $hospital) {
            if ($hospital != "") {
                $sql = "insert into usuario_hospital(usuario, hospital) values('$idusuario', '$hospital');";
                $mysqli->query($sql);
            }
        }

        $sql = "delete from usuario_categorias where id_usuario = '$idusuario';";
        $mysqli->query($sql);

        foreach ($categorias as $categoria) {
            if ($categoria != "") {
                $sql = "insert into usuario_categorias(id_usuario, id_categoria) values('$idusuario', '$categoria');";
                $mysqli->query($sql);
            }
        }

        $sql = "delete from ntf_kardex_usuario where id_usuario = '$idusuario';";
        $mysqli->query($sql);

        foreach ($ntf as $ntfs) {

            $sql = "insert into ntf_kardex_usuario(fecha_registro, id_usuario, id_estatus, id_tipo) values(now(),'$idusuario', '1','$ntfs');";
            $mysqli->query($sql);
        }



        if ($resultado === TRUE) {
            echo "1";
        } else {
            echo "Error Actualizando registro: " . $mysqli->error;
        }

        //echo $resultado;
    } else {
        $passencript = sha1('starmedica');
        $sql = "insert into usuarios (nombre, login, email, rol, ind_activo, fecha_creacion, puesto, password,ind_notificaciones) "
                . "values('$nombre', '$login', '$email', '$rol', '$activo', now(), '$puesto', '$passencript','$notificaciones');";
        //echo $sql;
        $resultado = $mysqli->query($sql) or die(mysqli_error($mysqli));

        $id_usuario = $mysqli->insert_id;


        if ($resultado == "1" && $id_usuario > 0) {
            //$sql = "insert into usuario_grupos(id_usuario, id_grupo) values('$id_usuario', '$grupo')";
            //$mysqli->query($sql);


            foreach ($grupos as $grupo) {
                if ($grupo != "") {
                    $sql = "insert into usuario_grupos(id_usuario, id_grupo) values('$id_usuario', '$grupo')";
                    $mysqli->query($sql);
                }
            }

            foreach ($hospitales as $hospital) {
                if ($hospital != "") {
                    $sql = "insert into usuario_hospital(usuario, hospital) values('$id_usuario', '$hospital');";
                    $mysqli->query($sql);
                }
            }

            foreach ($categorias as $categoria) {
                if ($categoria != "") {
                    $sql = "insert into usuario_categorias(id_usuario, id_categoria) values('$id_usuario', '$categoria');";
                    $mysqli->query($sql);
                }
            }

            foreach ($ntf as $ntfs) {

                $sql = "insert into ntf_kardex_usuario(fecha_registro, id_usuario, id_estatus, id_tipo) values(now(),'$id_usuario', '1','$ntfs');";
                $mysqli->query($sql);
            }
        }
        if ($resultado === TRUE) {
            echo "1";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
    }
}

function reset_pass() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $idusuario = "";
    $passencript = "";

    if (isset($_POST['id_usuario'])) {
        $idusuario = $_POST['id_usuario'];
    }

    if (isset($_POST['pass'])) {
        $passencript = sha1('starmedica');
    }

    $sql = "update usuarios set password = '$passencript' where id_usuario = '$idusuario';";

    echo $mysqli->query($sql) or die("Ocurrio un error: " + $mysqli->error);
}

function consulta_ticket_usuario_registra($id_solicitud) {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select t.usuario_registra, u.nombre from tickets t inner join usuarios u on u.id_usuario = t.usuario_registra "
            . " where t.id_ticket = '" . $id_solicitud . "';";

//    echo $sql;

    $resultado = $mysqli->query($sql);
    $fila = $resultado->fetch_assoc();

    echo '<option value="' . $fila['usuario_registra'] . '">' . $fila['nombre'] . '</option>';
}

function consulta_admin_des($id_ticket) {
    session_start();
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");


    $array_grupos = array();
    $array_hospital = array();

    $idUsrSolicita = 0;

    $sql = "select usuario_registra 
            from tickets 
            where id_ticket = '$id_ticket'";
    //echo $sql;
    $resultado = $mysqli->query($sql);

    $fila = $resultado->fetch_assoc();
    $idUsrSolicita = $fila['usuario_registra'];

    $gruposSQL = "select uc.id_categoria 
        from usuario_categorias uc 
        join categoria c on uc.id_categoria = c.id and c.esta_activo = true
        where uc.id_usuario = '" . $_SESSION['id_usuario'] . "';";
    $resultadoGrupos = $mysqli->query($gruposSQL);

    while ($filaGrupo = $resultadoGrupos->fetch_assoc()) {
        array_push($array_grupos, $filaGrupo['id_categoria']);
    }

    $hospitalSQL = "select hospital from usuario_hospital where usuario = '" . $_SESSION['id_usuario'] . "';";
    $resultadoHospital = $mysqli->query($hospitalSQL);

    while ($filaHospital = $resultadoHospital->fetch_assoc()) {
        array_push($array_hospital, $filaHospital['hospital']);
    }


    $sql = "select distinct(u.id_usuario), u.nombre "
            . " from usuarios u "
            . " inner join usuario_categorias uc on uc.id_usuario = u.id_usuario "
            . " inner join usuario_hospital uh on uh.usuario = u.id_usuario "
            . " where /*u.rol in(1, 2, 3) "
            . " and*/ uc.id_categoria in('" . join("', '", $array_grupos) . "') "
            . " and uh.hospital in ('" . join("', '", $array_hospital) . "') "
            . " and u.id_usuario not in ('" . $_SESSION['id_usuario'] . "', '" . $idUsrSolicita . "') and u.ind_activo = 1"
            . " order by u.nombre";

    //echo $sql;

    $resultado = $mysqli->query($sql);

    //echo '<option value="">Seleccione el usuario *</option>';

    echo '<option value="' . $_SESSION['id_usuario'] . '"> - A mí - </option>';
    //echo '<option value="' . $idUsrSolicita . '"> - A solicitante - </option>';

    while ($fila = $resultado->fetch_assoc()) {
        echo '<option value="' . $fila['id_usuario'] . '">' . $fila['nombre'] . '</option>';
    }
}

function consulta_actualizador() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select id_usuario, nombre from usuarios where rol = 4 and ind_activo = 1;";
    $resultado = $mysqli->query($sql);

    echo '<option value="">Seleccione el usuario *</option>';

    while ($fila = $resultado->fetch_assoc()) {
        echo '<option value="' . $fila['id_usuario'] . '">' . $fila['nombre'] . '</option>';
    }
}

function alta_rol() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $nombre_rol = "";
    $id_rol = "";

    if (isset($_POST['nombre_rol'])) {
        $nombre_rol = $_POST['nombre_rol'];
    }
    if (isset($_POST['id_rol']) && $_POST['id_rol'] !== "") {
        $id_rol = $_POST['id_rol'];
    }


    if ($id_rol != "") {
        $sql = "update catalogo_valor set descripcion = '$nombre_rol' where id = '$id_rol' and catalogo = 1;";
        echo $mysqli->query($sql) or die("ocurrio un error: " + $mysqli->error);
    } else {

        $sql = "select max(id) as id from catalogo_valor where catalogo = 1;";
        $resultado = $mysqli->query($sql);
        $fila = $resultado->fetch_assoc();
        $id_rol = $fila['id'];
        $id_rol++;

        $sql = "insert into catalogo_valor (id, descripcion, catalogo) "
                . "values('$id_rol', '$nombre_rol', '1');";

        echo $mysqli->query($sql) or die("ocurrio un error: " + $mysqli->error);
    }
}

function guarda_funciones_rol() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $totalItems = 0;
    $operacion = "";
    $id_rol = "";
    $id_funcion = "";

    if (isset($_POST["id_rol_tratado"])) {
        $id_rol = $_POST["id_rol_tratado"];
    }

    if (isset($_POST['total_funciones'])) {
        $totalItems = $_POST['total_funciones'];
    }

    if ($id_rol != "" && $totalItems > 0) {
        $sqlquery = "delete from rol_funciones where id_rol = '$id_rol'";
        echo $sqlquery;
        $mysqli->query($sqlquery);
        echo $totalItems;
        for ($i = 1; $i <= $totalItems; $i++) {
            if (isset($_POST["fn-$i"]) && $_POST["fn-$i"] == 'on') {
                $sqlquery = "INSERT INTO rol_funciones (id_rol, id_funcion) VALUES ('$id_rol', '$i')";
                echo $sqlquery;
                $mysqli->query($sqlquery);
            }
        }
    }
}

function consulta_funciones_bd() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select id_funcion, nombre from funciones;";
    $resultado = $mysqli->query($sql);

    while ($fila = $resultado->fetch_assoc()) {
        echo '<option value="' . $fila['id_funcion'] . '">' . $fila['nombre'] . '</option>';
    }
}

function consultarNombreGrupo($idGrupo) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select nombre
            from grupos
            where id_grupo = '$idGrupo'";
    $resultado = $mysqli->query($sql);

    $fila = $resultado->fetch_assoc();
    return $fila['nombre'];
}

function traerCorreosUsuariosXGrupo($arrayIdsGrupo) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select distinct u.email
            from usuario_grupos ug
            join usuarios u on ug.id_usuario = u.id_usuario
            where ug.id_grupo in ('" . join("', '", $arrayIdsGrupo) . "')";
    $resultado = $mysqli->query($sql);

    $arrayMails = array();
    while ($fila = $resultado->fetch_assoc()) {
        array_push($arrayMails, $fila['email']);
    }

    return $arrayMails;
}

function traerEmailSolicitanteXTicket($idTicket) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select u.email,u.id_usuario 
            from tickets t 
            join usuarios u on t.usuario_registra = u.id_usuario 
            where t.id_ticket = '$idTicket' 
            limit 1";
    $resultado = $mysqli->query($sql);


    $fila = $resultado->fetch_assoc();
    return array($fila['email'], $fila['id_usuario']);
}

function traerEmailSolicitanteXTicket_auto($idTicket) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select u.email,u.id_usuario 
            from tickets t 
            join usuarios u on t.usuario_anterior = u.id_usuario 
            where t.id_ticket = '$idTicket' 
            limit 1";
    $resultado = $mysqli->query($sql);


    $fila = $resultado->fetch_assoc();
    //echo $sql;
    return array($fila['email'], $fila['id_usuario']);
}

function traerEmailAtiendeXTicket($idTicket) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select u.email ,u.id_usuario 
            from tickets t 
            join usuarios u on t.usuario_actual = u.id_usuario 
            where t.id_ticket = '$idTicket' 
            limit 1";
    $resultado = $mysqli->query($sql);

    $fila = $resultado->fetch_assoc();
    return array($fila['email'], $fila['id_usuario']);
}

function traerEmailResuelveXTicket($idTicket) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select u.email ,u.id_usuario 
            from tickets t 
            join usuarios u on t.usuario_resuelve = u.id_usuario 
            where t.id_ticket = '$idTicket' 
            limit 1";
    $resultado = $mysqli->query($sql);

    $fila = $resultado->fetch_assoc();
    return array($fila['email'], $fila['id_usuario']);
}

function traerNombreUsuarioXID($idUsuario) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select u.nombre 
            from usuarios u 
            where u.id_usuario = '$idUsuario'";
    $resultado = $mysqli->query($sql);

    $fila = $resultado->fetch_assoc();
    return $fila['nombre'];
}

function traerCorreosGrupoXUsuario($idUsuario) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $arrG = array();
    $arrH = array();
    $arrayMails = array();

    $sql = "select distinct id_grupo as id_g_sel
            from usuario_grupos
            where id_usuario = '$idUsuario'";
    $resultado = $mysqli->query($sql);
    while ($fila1 = $resultado->fetch_assoc()) {
        array_push($arrG, $fila1['id_g_sel']);
    }

    $sql = "select distinct hospital as id_hosp
            from usuario_hospital
            where usuario = '$idUsuario'";
    $resultado = $mysqli->query($sql);
    while ($fila2 = $resultado->fetch_assoc()) {
        array_push($arrH, $fila2['id_hosp']);
    }


    $sql = "select distinct email as email
            from usuarios u
            join usuario_grupos ug on u.id_usuario = ug.id_usuario
            join usuario_hospital uh on u.id_usuario = uh.usuario
            where u.ind_activo = true 
            and ug.id_grupo in('" . join("','", $arrG) . "')
            and uh.hospital in('" . join("','", $arrH) . "')";
    $resultado = $mysqli->query($sql);

    while ($fila3 = $resultado->fetch_assoc()) {
        array_push($arrayMails, $fila3['email']);
    }

    return $arrayMails;
}

function traerIdUsuarioAtiendeXIdTicket($idTicket) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select usuario_actual 
            from tickets 
            where id_ticket = '$idTicket'";
    $resultado = $mysqli->query($sql);

    $fila = $resultado->fetch_assoc();
    return $fila['usuario_actual'];
}

function traerIdUsuarioSolicitaXIdTicket($idTicket) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select usuario_registra 
            from tickets 
            where id_ticket = '$idTicket'";
    $resultado = $mysqli->query($sql);

    $fila = $resultado->fetch_assoc();
    return $fila['usuario_registra'];
}

function actualizarselectpadre() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql = "select id,nombre from categoria where tipo_categoria=1;";
    $resultado = $mysqli->query($sql);

    echo '<label>Categoria Padre</label>';
    echo '<select class="form-control" name="categoria_padre" id="categoria_padre"  onchange="$("#catpaddiv").html("");">';
    echo '<option value="" selected>Selecciona una Categoria</option>';

    while ($fila = $resultado->fetch_assoc()) {
        echo '<option value="' . $fila['id'] . '">' . $fila['nombre'] . '</option>';
    }
    echo '</select>
    <div name="catpaddiv" id="catpaddiv" class="errordiv"></div>';
}

function alta_categoria() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    $id_categoria = "";
    $nombre = "";
    $ind_tipo = "";
    $categoria_padre = "";
    $url = "";
    $desc_ayuda = "";
    $grupo = "";
    $array_subcategorias = array();
    $totalsubcategorias = 0;
    $ind_activo = 0;

    if (isset($_POST['id_categoria']) && !empty($_POST['id_categoria'])) {
        $id_categoria = $_POST['id_categoria'];
    }
    if (isset($_POST['nombre_categoria']) && !empty($_POST['nombre_categoria'])) {
        $nombre = $_POST['nombre_categoria'];
    }
    if (isset($_POST['ind_tipo']) && !empty($_POST['ind_tipo'])) {
        $ind_tipo = $_POST['ind_tipo'];
    }
    if (isset($_POST['categoria_padre']) && !empty($_POST['categoria_padre'])) {
        $categoria_padre = $_POST['categoria_padre'];
    }
    if (isset($_POST['subcategorias'])) {
        $array_subcategorias = $_POST['subcategorias'];
        $totalsubcategorias = count($array_subcategorias);
    }
    if (isset($_POST['grupo']) && !empty($_POST['grupo'])) {
        $grupo = $_POST['grupo'];
    }
    if (isset($_POST['url_formatos']) && !empty(trim($_POST['url_formatos']))) {
        $url = $_POST['url_formatos'];
    }
    if (isset($_POST['desc_ayuda']) && !empty(trim($_POST['desc_ayuda']))) {
        $desc_ayuda = $_POST['desc_ayuda'];
    }
    if (isset($_POST['ind_activo']) && !empty($_POST['ind_activo'])) {
        $ind_activo = 1;
    }


    if ($id_categoria == "") {
        if ($ind_tipo == 1) {
            $sql = "INSERT INTO `categoria`(`id`, `nombre`, `esta_activo`, `categoria_padre`, `id_grupo`, `cod_prioridad`, `desc_ayuda`, `url_formatos`, `tipo_categoria`) VALUES (NULL,'$nombre',1,NULL,NULL,NULL,NULL,NULL,1)";
            if ($mysqli->query($sql) === TRUE) {
                echo "1";
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
            }
        } else if ($ind_tipo == 2) {
            $sql = "INSERT INTO `categoria`(`id`, `nombre`, `esta_activo`, `categoria_padre`, `id_grupo`, `cod_prioridad`, `desc_ayuda`, `url_formatos`, `tipo_categoria`) VALUES (NULL,'$nombre',1,NULL,NULL,NULL,NULL,NULL,1)";
            if ($mysqli->query($sql) === TRUE) {
                echo "1";
                $last_id = $mysqli->insert_id;
                for ($i = 0; $i < $totalsubcategorias; $i++) {
                    if (!empty(trim($array_subcategorias[$i]))) {
                        $sql2 = "INSERT INTO `categoria`(`id`, `nombre`, `esta_activo`, `categoria_padre`, `id_grupo`, `cod_prioridad`, `desc_ayuda`, `url_formatos`, `tipo_categoria`) VALUES (NULL,'$array_subcategorias[$i]',1,$last_id,NULL,NULL,NULL,NULL,3)";
                        if ($mysqli->query($sql2) === TRUE) {
                            
                        } else {
                            echo "Error: " . $sql2 . "<br>" . $mysqli->error;
                        }
                    }
                }
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
            }
        }
    } else {
        $sql = "UPDATE `categoria` SET `nombre`='$nombre',`categoria_padre`='$categoria_padre',`id_grupo`='$grupo',`desc_ayuda`='$desc_ayuda',`url_formatos`='$url',`esta_activo` = '$ind_activo'  WHERE id='$id_categoria'";
        if ($mysqli->query($sql) === TRUE) {
            echo "1";
            $sqleliminartodo = "DELETE FROM `categoria` WHERE categoria_padre='$id_categoria';";
            if ($mysqli->query($sqleliminartodo) === TRUE) {
                for ($i = 0; $i < $totalsubcategorias; $i++) {
                    if (!empty(trim($array_subcategorias[$i]))) {
                        $sql2 = "INSERT INTO `categoria`(`id`, `nombre`, `esta_activo`, `categoria_padre`, `id_grupo`, `cod_prioridad`, `desc_ayuda`, `url_formatos`, `tipo_categoria`) VALUES (NULL,'$array_subcategorias[$i]','$ind_activo',$id_categoria,NULL,NULL,NULL,NULL,3)";
                        if ($mysqli->query($sql2) === TRUE) {
                            
                        } else {
                            echo "Error: " . $sql2 . "<br>" . $mysqli->error;
                        }
                    }
                }
            }
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
    }
}

function buscar_subcategorias() {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    //echo $_POST['id'];
    $sql = "select nombre from categoria where categoria_padre=" . $_POST['id'] . ";";
    $resultado = $mysqli->query($sql);
    while ($fila = $resultado->fetch_assoc()) {
        echo '<div><input type="text" name="subcategorias[]" value="' . $fila['nombre'] . '"/><a href="#" class="remove_field">&nbsp;<i class="fa fa-times" style="color:red; font-size:20px;" aria-hidden="true" ></i></a></div>';
    }
}

function traer_detalle_categoria($id) {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $jsonArray = array();
    $jsonArrayItem = array();

    $sql = "select nombre,
            case when url_formatos is null then '' else url_formatos end as url_formatos,
            case when desc_ayuda is null then '' else desc_ayuda end as desc_ayuda,
            categoria_padre,
            esta_activo,
            id_grupo 
            from categoria 
            where id = '$id'";
    $resultado = $mysqli->query($sql);
    $fila = $resultado->fetch_assoc();

    $array = [
        "nombre" => $fila['nombre'],
        "url_formatos" => $fila['url_formatos'],
        "desc_ayuda" => $fila['desc_ayuda'],
        "categoria_padre" => $fila['categoria_padre'],
        "id_grupo" => $fila['id_grupo'],
        "esta_activo" => $fila['esta_activo']
    ];

    echo json_encode($array);
}

