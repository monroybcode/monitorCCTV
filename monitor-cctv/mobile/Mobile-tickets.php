<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}


include '../resources/connection/conexion.php';
include "../utils/constantes.php";
$mysqli->query("SET NAMES 'UTF8'");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Star Médica | Reportes Mobile</title>
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <meta name="HandheldFriendly" content="true" />

        <?php include '../resources/components/includes-mobile.php'; ?>


    </head>
    <body id="mobile2">


        <div class="profile-head ">

            <img class="ima-star" src="../resources/images/starmedica.png"/>
            <div class="top-message">
                <?php echo $_SESSION['nombre']; ?>
            </div>
            <div class="enmedio-mensaje">
                Reportes
            </div>

        </div>

        <div class="4-col-grids mobile-body">


            <?php
            $mysqli->query("SET NAMES 'UTF8'");

            $sql = "SELECT 
                    t.id_ticket,
                    t.unidad_negocio,
                    t.ultima_actualizacion,
                    t.fecha_registro,
                    t.prioridad,
                    t.grupo,
                    g.nombre AS nombre_grupo,
                    h.nombre AS hospital,
                    UPPER(cv2.descripcion) AS estatus,
                    t.estatus AS id_estatus,
                    u.nombre AS registra,
                    cv3.descripcion AS prioridad_text,
                    u2.nombre AS atiende,
                    u3.nombre AS resuelve,
                    c1.nombre AS categoria_1,
                    c2.nombre AS categoria_2,
                    c3.nombre AS categoria_3,
                    c4.nombre AS categoria_4,
                    cv4.descripcion AS color_stts,
                    IFNULL(n.num_notas, 0) AS cant_notas,
                    IFNULL(d.num_docs, 0) AS cant_docs
                FROM
                    tickets t
                        LEFT JOIN
                    categoria c1 ON c1.id = t.categoria_1
                        LEFT JOIN
                    categoria c2 ON c2.id = t.categoria_2
                        LEFT JOIN
                    categoria c3 ON c3.id = t.categoria_3
                        LEFT JOIN
                    categoria c4 ON c4.id = t.categoria_4
                        LEFT JOIN
                    catalogo_valor cv2 ON cv2.id = t.estatus AND cv2.catalogo = 2
                        LEFT JOIN
                    catalogo_valor cv3 ON cv3.id = t.prioridad
                        AND cv3.catalogo = 4
                        LEFT JOIN
                    catalogo_Valor cv4 ON cv4.id = t.estatus AND cv4.catalogo = 6
                        LEFT JOIN
                    grupos g ON g.id_grupo = t.grupo
                        LEFT JOIN
                    usuarios u ON t.usuario_registra = u.id_usuario
                        LEFT JOIN
                    usuarios u2 ON t.usuario_actual = u2.id_usuario
                        LEFT JOIN
                    usuarios u3 ON t.usuario_resuelve = u3.id_usuario
                        LEFT JOIN
                    hospital h ON h.id = t.unidad_negocio
                        JOIN
                    usuario_hospital uh ON t.unidad_negocio = uh.hospital
                        AND uh.usuario = '" . $_SESSION['id_usuario'] . "'
                        LEFT JOIN
                    (SELECT 
                        COUNT(id_nota) AS num_notas, ticket
                    FROM
                        notas
                    WHERE
                        ind_activo = 1
                    GROUP BY ticket) n ON n.ticket = t.id_ticket
                        LEFT JOIN
                    (SELECT 
                        COUNT(id_documento) AS num_docs, ticket
                    FROM
                        documentos
                    WHERE
                        ind_activo = 1
                    GROUP BY ticket) d ON d.ticket = t.id_ticket
                WHERE
                    TRUE AND t.estatus = 1  
                        AND t.usuario_actual = '" . $_SESSION['id_usuario'] . "'
                ORDER BY t.fecha_registro DESC";
            // echo $sql;
            $result = $mysqli->query($sql);

            while ($fila = $result->fetch_assoc()) {
                $color = '';
                if ($fila["prioridad"] == '1') {
                    $color = '#F9C3C5';
                }
                echo '<a href="mobile-detalle-ticket.php?id=' . $fila['id_ticket'] . '" onclick="javascript:nuevo_rol()" style="background-color:' . $color . '" ><div class="list-group-item list-group-item-action"><strong> #' . $fila["id_ticket"] . '</strong> | <strong>' . date_format(date_create($fila['fecha_registro']), "d/m/Y H:i") . '</strong> | <strong>' . $fila['categoria_1'] . ' - ' . $fila['categoria_2'] . '</strong><span class="glyphicon glyphicon-chevron-right" aria-hidden="true" style="display: block;
                float: right"></span></div></a>';
            }
            ?>

        </div>


        <div class="footer">
            <a href="resources/controller/controller-mobile-logout.php">
                <div class="link-mobile">
                    <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> CERRAR SESIÓN
                </div>
            </a>
            <div id="adn"></div>
            <div class="grey-space">
                <img src="../resources/images/espacio-humano.png" class="slogan-left" alt=""/>
            </div>
        </div>


    </body>
</html>
