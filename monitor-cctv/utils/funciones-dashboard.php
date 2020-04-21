<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}

$opcion = "";


if (isset($_POST['opcion'])) {
    $opcion = $_POST['opcion'];
}


if ($opcion == "ajx_mostrar_tickets_activos_pag") {
    if (isset($_POST['indice_pag'])) {
        mostrar_tickets_activos_paginados($_POST['indice_pag']);
    } else {
        mostrar_tickets_activos_paginados(0);
    }
} else if ($opcion == "subir_valiable_session") {
    if (isset($_POST['v_nv'])) {
        $_SESSION['nv'] = $_POST['v_nv'];
    } else {
        $_SESSION['nv'] = 7;
    }
} else {
    echo "";
}

/* * ************************************ */

function mostrar_tickets_activos_paginados($indiceInicial) {
    require '../resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $query = "SELECT 
                                                    t.id_ticket,categoria.nombre
                                                FROM
                                                    tickets t

                                                        left join
                                                    categoria ON  t.categoria_4 = categoria.id

                                                ORDER BY t.fecha_registro DESC
                                                LIMIT " . ($indiceInicial * 10) . ", 10";

    //echo $query;
    $resultado = $mysqli->query($query);
    $totalRegs = $resultado->num_rows;
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td><a href='ticket.php?ticket=" . $fila['id_ticket'] . "'>" . $fila['id_ticket'] . "</a></td>";
        echo "<td>" . $fila['nombre'] . "</td>";
        echo "</tr>";
    }

    echo "<tr style='display:none;'><td><input id='hdnTieneMas' type='hidden' value='" . ($totalRegs == 10 ? 1 : 0) . "'></td></tr>";
}
