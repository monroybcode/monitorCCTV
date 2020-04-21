<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}

include 'resources/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
include 'utils/constantes.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo NOMBRE_SISTEMA; ?></title>
        <?php require 'resources/components/includes.php'; ?>

        <script>
            $(document).ready(function () {
                remove_class_navbar();
                $(".link-resumen").addClass("active");
            });
        </script>

    </head>
    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse">
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>


        <?php
        $rolUsr = $_SESSION['rol'];

        $array_grupos = array();

        $gruposSQL = "select id_usuario, id_grupo from usuario_grupos where id_usuario='" . $_SESSION['id_usuario'] . "';";
        $resultadoGrupos = $mysqli->query($gruposSQL);

        while ($filaGrupo = $resultadoGrupos->fetch_assoc()) {
            array_push($array_grupos, $filaGrupo['id_grupo']);
        }

        $_SESSION['asignados'] = array();
        $_SESSION['resueltos'] = array();
        $_SESSION['cerrados'] = array();
        $_SESSION['cancelados'] = array();
        $_SESSION['total_mi_actividad'] = array();
        $_SESSION['asignados_area'] = array();
        $_SESSION['resueltos_area'] = array();
        $_SESSION['cerrados_area'] = array();
        $_SESSION['cancelados_area'] = array();
        $_SESSION['total_actividad_area'] = array();
        $_SESSION['total_asignados'] = array();
        $_SESSION['total_resueltos'] = array();
        $_SESSION['total_cerrados'] = array();
        $_SESSION['total_cancelados'] = array();
        $_SESSION['total_total_mi_actividad'] = array();

        $sql = "";
        ?>

        <div class = "content-wrapper">

            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-lg-offset-1 col-md-offset-1" style="margin-bottom: 71px">


                <?php
                $asignados = 0;
                $resueltos = 0;
                $cerrados = 0;
                $cancelados = 0;
                $total = 0;

                //MI ACTIVIDAD
                //asignados
                if (in_array("ver_actividad_admin", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT id_ticket FROM tickets WHERE (usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=1) OR (usuario_actual='" . $_SESSION['id_usuario'] . "' and estatus=1)";
                } else if (in_array("ver_actividad_ejecutivo", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT id_ticket FROM tickets WHERE (usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=1) OR (usuario_actual='" . $_SESSION['id_usuario'] . "' and estatus=1)";
                } else {
                    $sql = "SELECT id_ticket FROM tickets WHERE usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=1 OR (usuario_actual='" . $_SESSION['id_usuario'] . "' and estatus=1)";
                }

                if ($sql != "") {
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['asignados'], $fila['id_ticket']);
                        array_push($_SESSION['total_mi_actividad'], $fila['id_ticket']);
                    }
                    $asignados = $resultado->num_rows;
                }

                //resueltos
                if (in_array("ver_actividad_admin", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT id_ticket FROM tickets WHERE (usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=2) OR (usuario_resuelve='" . $_SESSION['id_usuario'] . "' and estatus=2)";
                } else if (in_array("ver_actividad_ejecutivo", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT id_ticket FROM tickets WHERE (usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=2) OR (usuario_resuelve='" . $_SESSION['id_usuario'] . "' and estatus=2)";
                } else {
                    $sql = "SELECT id_ticket FROM tickets WHERE usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=2 OR (usuario_resuelve='" . $_SESSION['id_usuario'] . "' and estatus=2)";
                }
                //echo $sql;

                if ($sql != "") {
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['resueltos'], $fila['id_ticket']);
                        array_push($_SESSION['total_mi_actividad'], $fila['id_ticket']);
                    }
                    $resueltos = $resultado->num_rows;
                }

                //cerrados
                if (in_array("ver_actividad_admin", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT id_ticket FROM tickets WHERE (usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=3) OR (usuario_cierre='" . $_SESSION['id_usuario'] . "' and estatus=3)";
                } else if (in_array("ver_actividad_ejecutivo", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT id_ticket FROM tickets WHERE (usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=3) OR (usuario_cierre='" . $_SESSION['id_usuario'] . "' and estatus=3)";
                } else {
                    $sql = "SELECT id_ticket FROM tickets WHERE usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=3 OR (usuario_cierre='" . $_SESSION['id_usuario'] . "' and estatus=3)";
                }
                
               // echo $sql;

                if ($sql != "") {
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['cerrados'], $fila['id_ticket']);
                        array_push($_SESSION['total_mi_actividad'], $fila['id_ticket']);
                    }
                    $cerrados = $resultado->num_rows;
                }

                //cancelados
                if (in_array("ver_actividad_admin", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT id_ticket FROM tickets WHERE usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=4";
                } else if (in_array("ver_actividad_ejecutivo", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT id_ticket FROM tickets WHERE usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=4";
                } else if (in_array("ver_actividad_solicitante", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT id_ticket FROM tickets WHERE usuario_registra='" . $_SESSION['id_usuario'] . "' and estatus=4";
                }

                if ($sql != "") {
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['cancelados'], $fila['id_ticket']);
                        array_push($_SESSION['total_mi_actividad'], $fila['id_ticket']);
                    }
                    $cancelados = $resultado->num_rows;
                }

                $total = $asignados + $resueltos + $cerrados + $cancelados;
                ?>

                <div class="col-lg-6 col-md-6 col-sm-6 sol-xs-6">
                    <table class="table table-resumen table-responsivee">
                        <thead>
                            <tr>
                                <td colspan="2" style="border-bottom: 1.5pt #4b4e53 solid !important;">Mi actividad</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr onclick="show_report_resumen('Mi actividad - Asignados', 'asignados')">
                                <td>Activos</td>
                                <td style="text-align: right;"><?php echo $asignados; ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-menu-right" style="font-size: 9pt;"></span></td>
                            </tr>
                            <tr onclick="show_report_resumen('Mi actividad - Resueltos', 'resueltos')">
                                <td>Resueltos</td>
                                <td style="text-align: right;"><?php echo $resueltos; ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-menu-right" style="font-size: 9pt;"></span></td>
                            </tr>
                            <tr onclick="show_report_resumen('Mi actividad - Cerrados', 'cerrados')">
                                <td>Cerrados</td>
                                <td style="text-align: right;"><?php echo $cerrados; ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-menu-right" style="font-size: 9pt;"></span></td>
                            </tr>
                          
                        </tbody>
                        <tfoot>
                            <tr onclick="show_report_resumen('Mi actividad', 'total_mi_actividad')">
                                <td>Total</td>
                                <td style="text-align: right;"><?php echo $total; ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-menu-right" style="font-size: 9pt;"></span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>




                <!-- ////////////////////////////////////// -->

                <?php
                $asignados_area = 0;
                $resueltos_area = 0;
                $cerrados_area = 0;
                $cancelados_area = 0;
                $total_area = 0;
                //MI GRUPO
                //asignados
                if (in_array("ver_actividad_admin", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket FROM tickets t WHERE t.estatus=1 "
                            . " and (exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_actual and ug.id_grupo in('" . join("','", $array_grupos) . "')) "
                            . " OR t.grupo in('" . join("','", $array_grupos) . "')"
                            . " OR exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "'))) ";
                } else if (in_array("ver_actividad_ejecutivo", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket FROM tickets t WHERE t.estatus=1 "
                            . " and (exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_actual and ug.id_grupo in('" . join("','", $array_grupos) . "')) "
                            . " OR t.grupo in('" . join("','", $array_grupos) . "')"
                            . " OR exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "'))) ";
                } else if (in_array("ver_actividad_solicitante", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket FROM tickets t WHERE t.estatus=1 "
                            . " OR t.grupo in('" . join("','", $array_grupos) . "')"
                            . " and exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "')) ";
                }

                if ($sql != "") {
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['asignados_area'], $fila['id_ticket']);
                        array_push($_SESSION['total_actividad_area'], $fila['id_ticket']);
                    }
                    $asignados_area = $resultado->num_rows;
                }

                //resueltos
                if (in_array("ver_actividad_admin", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket from tickets t WHERE t.estatus=2 "
                            . " and (exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "')) "
                            . " OR t.grupo in('" . join("','", $array_grupos) . "')"
                            . "OR exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_resuelve and ug.id_grupo in('" . join("','", $array_grupos) . "')))";
                } else if (in_array("ver_actividad_ejecutivo", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket from tickets t WHERE t.estatus=2 "
                            . " and (exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "')) "
                            . " OR t.grupo in('" . join("','", $array_grupos) . "')"
                            . "OR exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_resuelve and ug.id_grupo in('" . join("','", $array_grupos) . "')))";
                } else if (in_array("ver_actividad_solicitante", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket from tickets t WHERE t.estatus=2 "
                            . " and exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "'))";
                }
 
                if ($sql != "") {
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['resueltos_area'], $fila['id_ticket']);
                        array_push($_SESSION['total_actividad_area'], $fila['id_ticket']);
                    }
                    $resueltos_area = $resultado->num_rows;
                }

                //cerrados
                if (in_array("ver_actividad_admin", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket from tickets t WHERE t.estatus=3 ";
                    $sql .= "and (exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "')) "
                            . " OR t.grupo in('" . join("','", $array_grupos) . "')"
                            . "OR exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_cierre and ug.id_grupo in('" . join("','", $array_grupos) . "')))";
                } else if (in_array("ver_actividad_ejecutivo", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket from tickets t WHERE t.estatus=3 ";
                    $sql .= " and (exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "')) "
                            . " OR t.grupo in('" . join("','", $array_grupos) . "')"
                            . "OR exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_cierre and ug.id_grupo in('" . join("','", $array_grupos) . "')))";
                } else if (in_array("ver_actividad_solicitante", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket from tickets t WHERE t.estatus=3 ";
                    $sql .= " and exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "'))";
                }

                if ($sql != "") {
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['cerrados_area'], $fila['id_ticket']);
                        array_push($_SESSION['total_actividad_area'], $fila['id_ticket']);
                    }
                    $cerrados_area = $resultado->num_rows;
                }

                //cancelados
                if (in_array("ver_actividad_admin", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket from tickets t WHERE t.estatus=4 ";
                    $sql .= "and exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "'))";
                } else if (in_array("ver_actividad_ejecutivo", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket from tickets t WHERE t.estatus=4 ";
                    $sql .= "and exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "'))";
                } else if (in_array("ver_actividad_solicitante", isset($array_funciones) ? $array_funciones : array())) {
                    $sql = "SELECT t.id_ticket from tickets t WHERE t.estatus=4 ";
                    $sql .= "and exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $array_grupos) . "'))";
                }

                if ($sql != "") {
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['cancelados_area'], $fila['id_ticket']);
                        array_push($_SESSION['total_actividad_area'], $fila['id_ticket']);
                    }
                    $cancelados_area = $resultado->num_rows;
                }

                $total_area = $asignados_area + $resueltos_area + $cerrados_area + $cancelados_area;
                ?>


                

                <!-- /////////////////// -->
                <?php
                if (in_array("ver_actividad_admin", isset($array_funciones) ? $array_funciones : array())) {
                    $total_asignados = 0;
                    $total_resueltos = 0;
                    $total_cerrados = 0;
                    $total_cancelados = 0;
                    $total_total = 0;

                    //asignados
                    $sql = "SELECT id_ticket FROM tickets WHERE estatus=1";
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['total_asignados'], $fila['id_ticket']);
                        array_push($_SESSION['total_total_mi_actividad'], $fila['id_ticket']);
                    }
                    $total_asignados = $resultado->num_rows;

                    //resueltos
                    $sql = "SELECT id_ticket from tickets WHERE estatus=2";
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['total_resueltos'], $fila['id_ticket']);
                        array_push($_SESSION['total_total_mi_actividad'], $fila['id_ticket']);
                    }
                    $total_resueltos = $resultado->num_rows;

                    //cerrados
                    $sql = "SELECT id_ticket from tickets WHERE estatus=3;";
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['total_cerrados'], $fila['id_ticket']);
                        array_push($_SESSION['total_total_mi_actividad'], $fila['id_ticket']);
                    }
                    $total_cerrados = $resultado->num_rows;

                    //cancelados
                    $sql = "SELECT id_ticket from tickets WHERE estatus=4;";
                    $resultado = $mysqli->query($sql);
                    while ($fila = $resultado->fetch_assoc()) {
                        array_push($_SESSION['total_cancelados'], $fila['id_ticket']);
                        array_push($_SESSION['total_total_mi_actividad'], $fila['id_ticket']);
                    }
                    $total_cancelados = $resultado->num_rows;

                    $total_total = $total_asignados + $total_resueltos + $total_cerrados + $total_cancelados;
                    ?>

                    <div class="col-lg-6 col-md-6 col-sm-6 sol-xs-6">
                        <table class="table table-resumen table-responsivee">
                            <thead>
                                <tr>
                                    <td colspan="2" style="border-bottom: 1.5pt #4b4e53 solid !important;">Toda la actividad</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr onclick="show_report_resumen('Toda la actividad - Asignados', 'total_asignados')">
                                    <td>Activos</td>
                                    <td style="text-align: right;"><?php echo $total_asignados; ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-menu-right" style="font-size: 9pt;"></span></td>
                                </tr>
                                <tr onclick="show_report_resumen('Toda la actividad - Resueltos', 'total_resueltos')">
                                    <td>Resueltos</td>
                                    <td style="text-align: right;"><?php echo $total_resueltos; ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-menu-right" style="font-size: 9pt;"></span></td>
                                </tr>
                                <tr onclick="show_report_resumen('Toda la actividad - Cerrados', 'total_cerrados')">
                                    <td>Cerrados</td>
                                    <td style="text-align: right;"><?php echo $total_cerrados; ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-menu-right" style="font-size: 9pt;"></span></td>
                                </tr>
                                
                            </tbody>
                            <tfoot>
                                <tr onclick="show_report_resumen('Toda la actividad', 'total_total_mi_actividad')">
                                    <td>Total</td>
                                    <td style="text-align: right;"><?php echo $total_total; ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-menu-right" style="font-size: 9pt;"></span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- //////////////////////////// -->
            <?php
            if (in_array("ver_actividad_admin", isset($array_funciones) ? $array_funciones : array()) || in_array("ver_actividad_ejecutivo", isset($array_funciones) ? $array_funciones : array())) {
                $sql = "SELECT t.id_ticket, c.nombre as categoria, t.fecha_registro, t.ultima_actualizacion "
                        . "FROM tickets t "
                        . "left join categoria c on c.id=t.categoria_2 "
                        . "WHERE (t.usuario_actual='" . $_SESSION['id_usuario'] . "' and t.estatus=1) or (t.usuario_resuelve='" . $_SESSION['id_usuario'] . "' and t.estatus=2) "
                        . "limit 10";
            } else if (in_array("ver_actividad_solicitante", isset($array_funciones) ? $array_funciones : array())) {
                $sql = "SELECT t.id_ticket, c.nombre as categoria, t.fecha_registro, t.ultima_actualizacion "
                        . "FROM tickets t "
                        . "left join categoria c on c.id=t.categoria_2 "
                        . "WHERE t.usuario_registra='" . $_SESSION['id_usuario'] . "' "
                        . "limit 10";
            }
            ?>
          

        </div>

        <?php include 'resources/components/footer.php'; ?>
    </body>
</html>
