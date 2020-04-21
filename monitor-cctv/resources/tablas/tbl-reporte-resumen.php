<?php
session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$resultados_por_pagina = 10;
$comenzar_en = 0;

$condiciones = "";

$nombre_resumen = "";

$_SESSION['filtro_busqueda_r'] = "";
$_SESSION['filtro_estatus_r'] = "";
$_SESSION['filtro_categoria_r'] = "";
$_SESSION['filtro_solicitante_r'] = "";
$_SESSION['filtro_prioridad_r'] = "";
$_SESSION['filtro_fecha_1_r'] = "";
$_SESSION['filtro_fecha_2_r'] = "";

$_SESSION['orden_r'] = "";
$_SESSION['orden_lista_r'] = "";


setcookie("filtro_busqueda_r", '', time() + 700000, '/', 'localhost');
setcookie("filtro_estatus_r", '', time() + 700000, '/', 'localhost');
setcookie("filtro_categoria_r", '', time() + 700000, '/', 'localhost');
setcookie("filtro_solicitante_r", '', time() + 700000, '/', 'localhost');
setcookie("filtro_prioridad_r", '', time() + 700000, '/', 'localhost');
setcookie("filtro_fecha_1_r", '', time() + 700000, '/', 'localhost');
setcookie("filtro_fecha_2_r", '', time() + 700000, '/', 'localhost');
setcookie("orden_r", '', time() + 700000, '/', 'localhost');
setcookie("orden_lista_r", '', time() + 700000, '/', 'localhost');
setcookie("res_pag_reporte", '', time() + 700000, '/', 'localhost');


if (isset($_POST['name']) && $_POST['name'] != "null" && $_POST['name'] != "") {
    $nombre_resumen = $_POST['name'];
    $_SESSION['nombre_resumen'] = $_POST['name'];
} else if (isset($_SESSION['nombre_resumen']) && $_SESSION['nombre_resumen'] != "") {
    $nombre_resumen = $_SESSION['nombre_resumen'];
}

if (isset($_POST['resultados_por_pagina']) && $_POST['resultados_por_pagina'] != "") {
    $resultados_por_pagina = $_POST['resultados_por_pagina'];
    $_SESSION['res_pag_reporte'] = $_POST['resultados_por_pagina'];
    setcookie("res_pag_reporte", $_POST['resultados_por_pagina'], time() + 700000, '/', 'localhost');
}
if (isset($_POST['comenzar_en']) && $_POST['comenzar_en'] != "") {
    $comenzar_en = $_POST['comenzar_en'];
}

if (isset($_POST['filtro_estatus_resumen']) && $_POST['filtro_estatus_resumen'] != "") {
    $condiciones .= " and t.estatus='" . $_POST['filtro_estatus_resumen'] . "' ";
    $_SESSION['filtro_estatus_r'] = $_POST['filtro_estatus_resumen'];
    setcookie("filtro_estatus_r", $_POST['filtro_estatus_resumen'], time() + 700000, '/', 'localhost');
}
if (isset($_POST['filtro_categoria_resumen']) && $_POST['filtro_categoria_resumen'] != "") {
    $condiciones .= " and t.categoria_2='" . $_POST['filtro_categoria_resumen'] . "'  ";
    $_SESSION['filtro_categoria_r'] = $_POST['filtro_categoria_resumen'];
    setcookie("filtro_categoria_r", $_POST['filtro_categoria_resumen'], time() + 700000, '/', 'localhost');
}
if (isset($_POST['filtro_solicitante_resumen']) && $_POST['filtro_solicitante_resumen'] != "") {
    $condiciones .= " and t.usuario_registra='" . $_POST['filtro_solicitante_resumen'] . "' ";
    $_SESSION['filtro_solicitante_r'] = $_POST['filtro_solicitante_resumen'];
    setcookie("filtro_solicitante_r", $_POST['filtro_solicitante_resumen'], time() + 700000, '/', 'localhost');
}
if (isset($_POST['filtro_prioridad_resumen']) && $_POST['filtro_prioridad_resumen'] != "") {
    $condiciones .= " and t.prioridad='" . $_POST['filtro_prioridad_resumen'] . "' ";
    $_SESSION['filtro_prioridad_r'] = $_POST['filtro_prioridad_resumen'];
    setcookie("filtro_prioridad_r", $_POST['filtro_prioridad_resumen'], time() + 700000, '/', 'localhost');
}
if (isset($_POST['filtro_fecha_1_resumen']) && isset($_POST['filtro_fecha_2_resumen'])) {
    if ($_POST['filtro_fecha_1_resumen'] != "" && $_POST['filtro_fecha_2_resumen'] != "") {
        $condiciones .= " and t.fecha_registro >= '" . $_POST['filtro_fecha_1_resumen'] . "' and t.fecha_registro <= '" . $_POST['filtro_fecha_2_resumen'] . "'  ";
    } else if ($_POST['filtro_fecha_1_resumen'] != "" && $_POST['filtro_fecha_2_resumen'] == "") {
        $condiciones .= " and t.fecha_registro >= '" . $_POST['filtro_fecha_1_resumen'] . "' ";
    } else if ($_POST['filtro_fecha_1_resumen'] == "" && $_POST['filtro_fecha_2_resumen'] != "") {
        $condiciones .= " and t.fecha_registro <= '" . $_POST['filtro_fecha_2_resumen'] . "' ";
    }

    $_SESSION['filtro_fecha_1_r'] = $_POST['filtro_fecha_1_resumen'];
    $_SESSION['filtro_fecha_2_r'] = $_POST['filtro_fecha_2_resumen'];

    setcookie("filtro_fecha_1_r", $_POST['filtro_fecha_1_resumen'], time() + 700000, '/', 'localhost');
    setcookie("filtro_fecha_2_r", $_POST['filtro_fecha_2_resumen'], time() + 700000, '/', 'localhost');
}
if (isset($_POST['busqueda_resumen']) && $_POST['busqueda_resumen'] != "") {
    $condiciones .= " and (t.id_ticket like '" . $_POST['busqueda_resumen'] . "%' or t.comentarios like '%" . $_POST['busqueda_resumen'] . "%' OR EXISTS(SELECT * FROM notas WHERE ticket=t.id_ticket AND nota LIKE '%" . $_POST['busqueda_resumen'] . "%')  ) ";
    $_SESSION['filtro_busqueda_r'] = $_POST['busqueda_resumen'];
    setcookie("filtro_busqueda_r", $_POST['busqueda_resumen'], time() + 700000, '/', 'localhost');
}

if (isset($_POST['orden_resumen']) && $_POST['orden_resumen'] != "") {
    if (isset($_POST['orden_lista']) && $_POST['orden_lista'] != "") {
        $condiciones .= " order by " . $_POST['orden_resumen'] . " " . $_POST['orden_lista'] . " ";

        $_SESSION['orden_r'] = $_POST['orden_resumen'];
        $_SESSION['orden_lista_r'] = $_POST['orden_lista'];

        setcookie("orden_r", $_POST['orden_resumen'], time() + 700000, '/', 'localhost');
        setcookie("orden_lista_r", $_POST['orden_lista'], time() + 700000, '/', 'localhost');
    }
}
?>



<table class="table-s table table-hover table-responsive tbl-det-tickets">

    <thead class="box box-primary">
        <tr>
            <td># Reporte</td>
            <td>Estatus</td>
            <td>Categoria</td>
            <td>Area</td>
            <td>Hospital</td>
            <td>Solicitante</td>
            <td>Asignado</td>
            <td>Prioridad</td>
            <td>Fecha solicitud</td>
            <td>Fecha Resolucion</td>
            <td>Fecha Cierre</td>
        </tr>
    </thead>

    <?php
    $sql = "SELECT 
     t.id_ticket,
    t.fecha_resolucion,
    t.fecha_cierre,
    cv.descripcion AS estatus,
    c.nombre AS categoria,
    u.puesto AS puesto,
    u2.nombre AS asignado,
    cv2.descripcion AS prioridad,
    t.fecha_registro,
    t.ultima_actualizacion,
    hospital.nombre as hospital,
    areas.nombre_area
FROM
    tickets t
    
        LEFT JOIN
    catalogo_valor cv ON t.estatus = cv.id AND cv.catalogo = 2
        LEFT JOIN
    categoria c ON c.id = t.categoria_4
        LEFT JOIN
    usuarios u ON u.id_usuario = t.usuario_registra
        LEFT JOIN
    usuarios u2 ON u2.id_usuario = t.usuario_actual
        LEFT JOIN
    catalogo_valor cv2 ON cv2.id = t.prioridad
    right join
		
        hospital on hospital.id = t.unidad_negocio
        right join 
    areas on areas.idareas = t.categoria_2
        AND cv2.catalogo = 4
WHERE
    t.id_ticket IN ('" . join("','", $_SESSION[$nombre_resumen]) . "') " . $condiciones;

   //  echo $sql;

    $resultado = $mysqli->query($sql);
    $num_filas = $resultado->num_rows;

    $_SESSION['sql_reporte_resumen'] = $sql;

    $array_tickets = array();
    while ($fila = $resultado->fetch_assoc()) {
        array_push($array_tickets, $fila['id_ticket']);
    }

    $_SESSION['lista_tickets'] = $array_tickets;


    $sql .= " limit $comenzar_en, $resultados_por_pagina ";
    $resultado = $mysqli->query($sql);

//    echo $sql;

    while ($fila = $resultado->fetch_assoc()) {
        echo '<tr>';
        echo '<td>  <a href="#" onclick="javascript: vista_ticket(' . $fila['id_ticket'] . ')" title="Ver detalle completo">  ' . $fila['id_ticket'] . ' </a> </td>';
        echo '<td>' . $fila['estatus'] . '</td>';
        echo '<td>' . $fila['categoria'] . '</td>';
        echo '<td>' . $fila['nombre_area'] . '</td>';
         echo '<td>' . $fila['hospital'] . '</td>';
        echo '<td>' . $fila['puesto'] . '</td>';
        echo '<td>' . $fila['asignado'] . '</td>';
        echo '<td>' . $fila['prioridad'] . '</td>';
        if ($fila['fecha_registro'] != '') {
            echo '<td>' . date_format(date_create($fila['fecha_registro']), "d/m/Y H:i") . '</td>';
        } else {
            echo '<td></td>';
        }
        if ($fila['fecha_resolucion'] != '') {
            echo '<td>' . date_format(date_create($fila['fecha_resolucion']), "d/m/Y H:i") . '</td>';
        } else {
            echo '<td></td>';
        }
        if ($fila['fecha_cierre'] != '') {
            echo '<td>' . date_format(date_create($fila['fecha_cierre']), "d/m/Y H:i") . '</td>';
        } else {
            echo '<td></td>';
        }
        echo '</tr>';
    }
    ?>


</table>

<?php
$siguiente = 0;
$anterior = 0;

$pag_sig_comienza = ($comenzar_en + $resultados_por_pagina);
$pag_ant_comienza = ($comenzar_en - $resultados_por_pagina);

$mostrando_hasta = $pag_sig_comienza;

if ($pag_sig_comienza < $num_filas) {
    $siguiente = 1;
}

if ($pag_ant_comienza >= 0) {
    $anterior = 1;
}

if ($pag_sig_comienza > $num_filas) {
    $mostrando_hasta = $num_filas;
}
?>


<input type="hidden" id="pag_siguiente" name="pag_siguiente" value="<?php echo $siguiente; ?>">
<input type="hidden" id="pag_anterior" name="pag_anterior" value="<?php echo $anterior; ?>">

<input type="hidden" id="pag_siguiente_comienza" name="pag_siguiente_comienza" value="<?php echo $pag_sig_comienza; ?>">
<input type="hidden" id="pag_anterior_comienza" name="pag_anterior_comienza" value="<?php echo $pag_ant_comienza; ?>">

<input type="hidden" id="mostrando_resultado_inicio" name="mostrando_resultado_inicio" value="<?php echo $comenzar_en + 1; ?>">
<input type="hidden" id="mostrando_resultado_fin" name="mostrando_resultado_fin" value="<?php echo $mostrando_hasta; ?>">

<input type="hidden" id="num_resultados_tickets" value="<?php echo $num_filas; ?>">