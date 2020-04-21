<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}

include 'resources/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
include 'utils/constantes.php';
$id = $_SESSION['id_usuario'];
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

        <div class = "content-wrapper">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-lg-offset-1 col-md-offset-1" style="margin-bottom: 70px">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 10px 15px; padding-top: 0;">
                    <h3 class="no-margin">Resumen Estadístico</h3>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                        <!--categorias-->
                        <table class="table-s table table-hover table-responsive tbl-det-tickets">
                            <thead class="box box-primary">
                                <tr>
                                    <td style="width: 30%;">Por categoría</td>
                                    <td style="width: 17%; text-align: center;" class="tot">Activos</td>
                                    <td style="width: 17%; text-align: center;" class="tot">Resueltos</td>
                                    <td style="width: 17%; text-align: center;" class="tot">Cerrados</td>
                                    <td style="width: 17%; text-align: center;" class="tot">Total</td>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $contador1 = 0;
                                $categorias = " '' ";

                                while ($contador1 < count($_SESSION['usr_categorias'])) {
                                    $categorias .= "'" . $_SESSION['usr_categorias'][$contador1] . "'" . ",";

                                    $contador1++;
                                }

                                $categorias = substr($categorias, 0, -1);


                                $sql = "select c.nombre, ifnull(cer.total, 0) as cerrados, ifnull(res.total, 0) as resueltos, ifnull(asig.total, 0) as activos "
                                        . "from categoria c "
                                        . " left join (SELECT count(t.id_ticket) as total, t.categoria_4 as id from tickets t where t.estatus=3 "
                                        . " group by t.categoria_4) cer on cer.id=c.id "
                                        . " left join (SELECT count(t.id_ticket) as total, t.categoria_4 as id from tickets t where t.estatus=2 "
                                        . " group by t.categoria_4) res on res.id=c.id "
                                        . " left join (SELECT count(t.id_ticket) as total, t.categoria_4 as id from tickets t where t.estatus=1 "
                                        . " group by t.categoria_4) asig on asig.id=c.id "
                                        . " order by c.nombre;";




                                $resultados = $mysqli->query($sql);

                                while ($fila = $resultados->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td style="background: #eae9e9;">' . $fila['nombre'] . '</td>';
                                    echo '<td class="tot" style="text-align: center;">' . ($fila['activos'] > 0 ? $fila['activos'] : "") . '</td>';
                                    echo '<td class="tot" style="text-align: center;">' . ($fila['resueltos'] > 0 ? $fila['resueltos'] : "") . '</td>';
                                    echo '<td class="tot" style="text-align: center;">' . ($fila['cerrados'] > 0 ? $fila['cerrados'] : "") . '</td>';
                                    echo '<td class="tot" style="text-align: center;background: #eae9e9;">' . (($fila['activos'] + $fila['resueltos'] + $fila['cerrados']) > 0 ? ($fila['activos'] + $fila['resueltos'] + $fila['cerrados']) : "") . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>

                        <!--hopitales-->
                        <table class="table-s table table-hover table-responsive tbl-det-tickets">
                            <thead class="box box-primary">
                                <tr>
                                    <td style="width: 30%;">Por hospital</td>
                                    <td style="width: 17%; text-align: center;" class="tot">Activos</td>
                                    <td style="width: 17%; text-align: center;" class="tot">Resueltos</td>
                                    <td style="width: 17%; text-align: center;" class="tot">Cerrados</td>
                                    <td style="width: 17%; text-align: center;" class="tot">Total</td>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $contador = 0;
                                $hospitales = " '' ";

                                while ($contador < count($_SESSION['usr_hospitales'])) {
                                    $hospitales .= "'" . $_SESSION['usr_hospitales'][$contador] . "'" . ",";

                                    $contador++;
                                }


                                $hospitales = substr($hospitales, 0, -1);

                                $sql = "SELECT concat(h.id, ' - ' ,h.nombre) as hospital, ifnull(cer.total, 0) as cerrados, ifnull(res.total, 0) as resueltos, "
                                        . " ifnull(asig.total, 0) as activos, ifnull(tot.total, 0) as total from hospital h "
                                        . " left join(SELECT count(t.id_ticket) as total, t.unidad_negocio as id from tickets t "
                                        . " where t.estatus=3 group by t.unidad_negocio) cer on cer.id=h.id "
                                        . " left join(SELECT count(t.id_ticket) as total, t.unidad_negocio as id from tickets t "
                                        . " where t.estatus=2 group by t.unidad_negocio) res on res.id=h.id "
                                        . " left join(SELECT count(t.id_ticket) as total, t.unidad_negocio as id from tickets t "
                                        . " where t.estatus=1 group by t.unidad_negocio) asig on asig.id=h.id "
                                        . " left join(SELECT count(t.id_ticket) as total, t.unidad_negocio as id from tickets t "
                                        . " group by t.unidad_negocio) tot on tot.id=h.id";
                                //echo $sql;


                                $resultados = $mysqli->query($sql);

                                while ($fila = $resultados->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td style="background: #eae9e9;">' . $fila['hospital'] . '</td>';
                                    echo '<td class="tot" style="text-align: center;">' . ($fila['activos'] > 0 ? $fila['activos'] : "") . '</td>';
                                    echo '<td class="tot" style="text-align: center;">' . ($fila['resueltos'] > 0 ? $fila['resueltos'] : "") . '</td>';
                                    echo '<td class="tot" style="text-align: center;">' . ($fila['cerrados'] > 0 ? $fila['cerrados'] : "") . '</td>';
                                    echo '<td class="tot" style="text-align: center;background: #eae9e9;">' . ($fila['total'] > 0 ? $fila['total'] : "") . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>

                        <!--desarrolladores-->
                        <?php if ($_SESSION['nombre_rol'] == 'Administrador') { ?>
                            <table class="table-s table table-hover table-responsive tbl-det-tickets">
                                <thead class="box box-primary">
                                    <tr>
                                        <td style="width: 30%;"></td>
                                        <td style="width: 17%; text-align: center;" class="tot">Activos</td>
                                        <td style="width: 17%; text-align: center;" class="tot">Resueltos</td>
                                        <td style="width: 17%; text-align: center;" class="tot">Cerrados</td>
                                        <td style="width: 17%; text-align: center;" class="tot">Total</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $sql = "SELECT 
    u.id_usuario,
    u.nombre,
    IFNULL(cer.total, 0) AS cerrados,
    IFNULL(res.total, 0) AS resueltos,
    IFNULL(asig.total, 0) AS activos,
    IFNULL(tot.total, 0) AS total
FROM
    usuarios u
        LEFT JOIN
    (SELECT 
        COUNT(t.id_ticket) AS total, t.usuario_anterior
    FROM
        tickets t
    WHERE
        t.estatus = 3
    GROUP BY t.usuario_anterior) cer ON cer.usuario_anterior = u.id_usuario
        LEFT JOIN
    (SELECT 
        COUNT(t.id_ticket) AS total, t.usuario_anterior
    FROM
        tickets t
    WHERE
        t.estatus = 2
    GROUP BY t.usuario_anterior) res ON res.usuario_anterior = u.id_usuario
        LEFT JOIN
    (SELECT 
        COUNT(t.id_ticket) AS total, t.usuario_actual
    FROM
        tickets t
    WHERE
        t.estatus = 1
    GROUP BY t.usuario_actual) asig ON asig.usuario_actual = u.id_usuario
        LEFT JOIN
    (SELECT 
        COUNT(t.id_ticket) AS total, t.usuario_anterior
    FROM
        tickets t
    GROUP BY t.usuario_anterior) tot ON tot.usuario_anterior = u.id_usuario;
       ";

                                    //echo $sql;

                                    $resultados = $mysqli->query($sql);

                                    while ($fila = $resultados->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td style="background: #eae9e9;">' . $fila['nombre'] . '</td>';
                                        echo '<td class="tot" style="text-align: center;">' . ($fila['activos'] > 0 ? $fila['activos'] : "") . '</td>';
                                        echo '<td class="tot" style="text-align: center;">' . ($fila['resueltos'] > 0 ? $fila['resueltos'] : "") . '</td>';
                                        echo '<td class="tot" style="text-align: center;">' . ($fila['cerrados'] > 0 ? $fila['cerrados'] : "") . '</td>';
                                        echo '<td class="tot" style="text-align: center;background: #eae9e9;">' . ($fila['total'] ) . '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php } ?>
                        <!--informadores-->




                    </div>

                    <!--                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12" style="padding-right: 0;">
                                            prioridad
                                            <table class="table table-hover table-responsive table-reporte-estadistica text-muted">
                                                <thead>
                                                    <tr>
                                                        <td>Por prioridad</td>
                                                        <td class="tot">Activos</td>
                                                        <td class="tot">Resueltos</td>
                                                        <td class="tot">Cerrados</td>
                                                    </tr>
                                                </thead>
                    
                                                <tbody>
                    <?php
                    $sql = "select cv.descripcion, ifnull(cer.total, 0) as cerrados, ifnull(res.total, 0) as resueltos, ifnull(asig.total, 0) as activos "
                            . " from catalogo_valor cv "
                            . " left join (SELECT count(id_ticket) as total, prioridad from tickets where estatus=3 group by prioridad) cer on cer.prioridad=cv.id "
                            . " left join (SELECT count(id_ticket) as total, prioridad from tickets where estatus=2 group by prioridad) res on res.prioridad=cv.id "
                            . " left join (SELECT count(id_ticket) as total, prioridad from tickets where estatus=1 group by prioridad) asig on asig.prioridad=cv.id "
                            . " WHERE cv.catalogo=4;";

                    $resultados = $mysqli->query($sql);

                    while ($fila = $resultados->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $fila['descripcion'] . '</td>';
                        echo '<td class="tot">' . ($fila['activos'] > 0 ? $fila['activos'] : "") . '</td>';
                        echo '<td class="tot">' . ($fila['resueltos'] > 0 ? $fila[''] : "") . '</td>';
                        echo '<td class="tot">' . ($fila['cerrados'] > 0 ? $fila[''] : "") . '</td>';
                        echo '</tr>';
                    }
                    ?>
                                                </tbody>
                                            </table>
                    
                    
                                        </div>
                                    </div> -->


                </div>
            </div>

            <?php include 'resources/components/footer.php'; ?>
    </body>
</html>
