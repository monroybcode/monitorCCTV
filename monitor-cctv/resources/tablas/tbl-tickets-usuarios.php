<?php
$restriccion = "";
include '../connection/conexion.php';
$mysqli->query($sql);

if (isset($_POST['restriccion'])) {
    $restriccion = " and " . $_POST['restriccion'];
}
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 container-detalles-ticket">
    <br/>
    <div id="accordion">

        <?php
        $sql = "select * from usuarios";
        $resultado = $mysqli->query($sql);

        while ($fila = $resultado->fetch_assoc()) {// cv.descripcion as area,
            $sql_tickets = "select t.id_ticket, g.nombre as area, cv2.descripcion as estatus, u.nombre as solicita "
                    . " from tickets t "
//                    . " left join catalogo_valor cv on t.area=cv.id and cv.catalogo=3 "
                    . " left join grupos g on g.id_grupo=t.grupo "
                    . " left join catalogo_valor cv2 on t.estatus=cv2.id and cv2.catalogo=2 "
                    . " left join usuarios u on u.id_usuario=t.usuario_registra "
                    . " where t.usuario_actual='" . $fila['id_usuario'] . "' $restriccion;";

//            echo $sql_tickets . "<br/>";

            $resultado_tickets = $mysqli->query($sql_tickets);
            $num_filas = $resultado_tickets->num_rows;

            if ($num_filas > 0) {
                ?>
                <h3> <span>[<?php echo $num_filas; ?>]</span> <?php echo $fila['nombre']; ?></h3>
                <div style="padding: 0;">
                    <table class="table-s table table-striped table-tickets-list text-muted">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Grupo</th>
                                <th>Estatus</th>
                                <th>Solicitante</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            while ($fila_ticket = $resultado_tickets->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $fila_ticket['id_ticket'] . "</td>";
                                echo "<td>" . $fila_ticket['area'] . "</td>";
                                echo "<td>" . $fila_ticket['estatus'] . "</td>";
                                echo "<td>" . $fila_ticket['solicita'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <?php
            }
        }
        ?>


    </div>

</div>