<?php

function atrasados($unidad) {
    include 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    $sql = $sql = "SELECT 
                                        id_ticket,
                                        catalogo_valor.descripcion,
                                        categoria.nombre  as categoria,
                                        categoria_2,
                                        estatus,
                                        usuarios.nombre,
                                        fecha_registro
                                    FROM
                                        tickets
                                            LEFT JOIN
                                        catalogo_valor ON tickets.estatus = catalogo_valor.id
                                            LEFT JOIN
                                        categoria ON tickets.categoria_2 = categoria.id
                                            LEFT JOIN
                                        usuarios ON tickets.usuario_resuelve = usuarios.id_usuario
                                    WHERE
                                        unidad_negocio = $unidad AND catalogo = '2' and estatus='1' order by fecha_registro asc;";
    // echo $sql;

    $resultados = $mysqli->query($sql);
    date_default_timezone_set('America/Mexico_City');


    $date2 = new DateTime(date('y-m-d H:i:s'));


    $contv_ag = 0;
    $contpv_ag = 0;
    $contt_ag = 0;



    while ($fila = $resultados->fetch_assoc()) {


        $query = "SELECT tiempo FROM tiempos_resolucion where id_hospital='".$unidad."' and categoria='" . $fila['categoria_2'] . "'";
        $resultados_t = $mysqli->query($query);
        $fila_t = $resultados_t->fetch_assoc();




        $date1 = new DateTime(date('y-m-d H:i:s', strtotime($fila['fecha_registro'])));

        $diff = $date1->diff($date2);
        $dias = $diff->format('%d %h %i %s');
        $horas = $diff->format('%h');
        $minutos = $diff->format('%i');
        $segundos = $diff->format('%s');
        $dias_h = ($dias * 24) + $horas;

        // echo "<br>" . $dias_h . ":" . $minutos . ":" . $segundos;

        $resta = $fila_t['tiempo'] - $dias_h;

        $resultado = $fila_t['tiempo'] - $dias_h;

        if ($resultado < 0) {
            $resultado = $resultado * -1;
        }

        $resultado = $resultado .  " Hrs "  . $minutos . " Min " . $segundos;

        if ($resta < 0) {
            $color_b = "#FFCDD2;";
            $contv_ag++;
            echo '<tr>';
            echo '<td style="background: #eae9e9;">' . $fila['id_ticket'] . '</td>';
            echo '<td class="tot" style=" text-align: center;">' . ($fila['descripcion']) . '</td>';
            echo '<td class="tot" style=" text-align: left;">' . ($fila['categoria']) . '</td>';
            echo '<td class="tot" style=" text-align: left;">' . ($fila['nombre']) . '</td>';
            echo '<td class="tot" style=" text-align: center;">' . $fila['fecha_registro'] . '</td>';
            echo '<td class="tot" style=" text-align: left;">' . $resultado . '</td>';
            echo '</tr>';
        }
    }
    echo '<tr>';
    echo '<td style="background: #FFCDD2;"></td>';
    echo '<td class="tot" style="background:#FFCDD2; text-align: center;" ></td>';
    echo '<td class="tot" style="background:#FFCDD2; text-align: center;" ></td>';
    echo '<td class="tot" style="background:#FFCDD2; text-align: center;" ></td>';
    echo '<td class="tot" style="background:#FFCDD2; text-align: left;" colspan="2"><strong>TOTAL DE TICKETS ATRASADOS: ' . $contv_ag . '</strong></td>';


    echo '</tr>';
    if ($unidad === '1102') {
        ?>
        <script type="text/javascript">
            $("#a_vencidos").html('Aguascalientes: ' + '<?php echo $contv_ag; ?>');


        </script> 
        <?php
    } else
    if ($unidad === '1103') {
        ?>
        <script type="text/javascript">
            $("#m_vencidos").html('Merida: ' + '<?php echo $contv_ag; ?>');


        </script> 
        <?php
    } else
    if ($unidad === '1104') {
        ?>
        <script type="text/javascript">
            $("#j_vencidos").html('Juarez: ' + '<?php echo $contv_ag; ?>');


        </script> 
        <?php
    }
}

function vencer($unidad) {
    include 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
    $sql = $sql = "SELECT 
                                        id_ticket,
                                        catalogo_valor.descripcion,
                                        categoria.nombre  as categoria,
                                        categoria_2,
                                        estatus,
                                        usuarios.nombre,
                                        fecha_registro
                                    FROM
                                        tickets
                                            LEFT JOIN
                                        catalogo_valor ON tickets.estatus = catalogo_valor.id
                                            LEFT JOIN
                                        categoria ON tickets.categoria_2 = categoria.id
                                            LEFT JOIN
                                        usuarios ON tickets.usuario_resuelve = usuarios.id_usuario
                                    WHERE
                                        unidad_negocio = $unidad AND catalogo = '2' and estatus='1' order by fecha_registro asc;";
    // echo $sql;

    $resultados = $mysqli->query($sql);
    date_default_timezone_set('America/Mexico_City');


    $date2 = new DateTime(date('y-m-d H:i:s'));
    $contv_ag = 0;
    $contpv_ag = 0;
    $contt_ag = 0;
    while ($fila = $resultados->fetch_assoc()) {


        $query = "SELECT tiempo FROM tiempos_resolucion where id_hospital='".$unidad."' and categoria='" . $fila['categoria_2'] . "'";
        $resultados_t = $mysqli->query($query);
        $fila_t = $resultados_t->fetch_assoc();




        $date1 = new DateTime(date('y-m-d H:i:s', strtotime($fila['fecha_registro'])));

        $diff = $date1->diff($date2);
        $dias = $diff->format('%d %h %i %s');
        $horas = $diff->format('%h');
        $minutos = $diff->format('%i');
        $segundos = $diff->format('%s');
        $dias_h = ($dias * 24) + $horas;

        // echo "<br>" . $dias_h . ":" . $minutos . ":" . $segundos;

        $resta = $fila_t['tiempo'] - $dias_h;
        $resultado = $fila_t['tiempo'] - $dias_h . " Hrs " . $minutos . " Min " . $segundos;


        if ($resta >= 0 && $resta <= 3) {
            $color_b = "#FFF9C4;";
            $contpv_ag++;
            echo '<tr>';
            echo '<td style="background: #eae9e9;">' . $fila['id_ticket'] . '</td>';
            echo '<td class="tot" style=" text-align: center;">' . ($fila['descripcion']) . '</td>';
            echo '<td class="tot" style=" text-align: left;">' . ($fila['categoria']) . '</td>';
            echo '<td class="tot" style=" text-align: left;">' . ($fila['nombre']) . '</td>';
            echo '<td class="tot" style=" text-align: center;">' . $fila['fecha_registro'] . '</td>';
            echo '<td class="tot" style=" text-align: left;">' . $resultado . '</td>';
            echo '</tr>';
        }
    }
    echo '<tr>';
    echo '<td style="background: #FFF9C4;"></td>';
    echo '<td class="tot" style="background:#FFF9C4; text-align: center;" ></td>';
    echo '<td class="tot" style="background:#FFF9C4; text-align: center;" ></td>';
    echo '<td class="tot" style="background:#FFF9C4; text-align: center;" ></td>';
    echo '<td class="tot" style="background:#FFF9C4; text-align: left;" colspan="2"><strong>&nbsp;&nbsp;TOTAL DE TICKETS POR VENCER: ' . $contpv_ag . '</strong></td>';


    echo '</tr>';
    if ($unidad === '1102') {
        ?>
        <script type="text/javascript">

            $("#a_pvencidos").html('Aguascalientes: ' + '<?php echo $contpv_ag; ?>');


        </script> 
        <?php
    } else
    if ($unidad === '1103') {
        ?>
        <script type="text/javascript">
            $("#m_pvencidos").html('Merida: ' + '<?php echo $contpv_ag; ?>');


        </script> 
        <?php
    } else
    if ($unidad === '1104') {
        ?>
        <script type="text/javascript">
            $("#j_pvencidos").html('Juarez: ' + '<?php echo $contpv_ag; ?>');


        </script> 
        <?php
    }
}

function atiempo($unidad) {
    include 'resources/connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");
     $sql = "SELECT 
                                        id_ticket,
                                        catalogo_valor.descripcion,
                                        categoria.nombre  as categoria,
                                        categoria_2,
                                        estatus,
                                        usuarios.nombre,
                                        fecha_registro
                                    FROM
                                        tickets
                                            LEFT JOIN
                                        catalogo_valor ON tickets.estatus = catalogo_valor.id
                                            LEFT JOIN
                                        categoria ON tickets.categoria_2 = categoria.id
                                            LEFT JOIN
                                        usuarios ON tickets.usuario_resuelve = usuarios.id_usuario
                                    WHERE
                                        unidad_negocio = $unidad AND catalogo = '2' and estatus='1' order by fecha_registro asc;";
    // echo $sql;

    $resultados = $mysqli->query($sql);
    date_default_timezone_set('America/Mexico_City');


    $date2 = new DateTime(date('y-m-d H:i:s'));
    $contv_ag = 0;
    $contpv_ag = 0;
    $contt_ag = 0;
    while ($fila = $resultados->fetch_assoc()) {


        $query = "SELECT tiempo FROM tiempos_resolucion where id_hospital='".$unidad."' and categoria='" . $fila['categoria_2'] . "'";
        $resultados_t = $mysqli->query($query);
        $fila_t = $resultados_t->fetch_assoc();




        $date1 = new DateTime(date('y-m-d H:i:s', strtotime($fila['fecha_registro'])));

        $diff = $date1->diff($date2);
        $dias = $diff->format('%d %h %i %s');
        $horas = $diff->format('%h');
        $minutos = $diff->format('%i');
        $segundos = $diff->format('%s');
        $dias_h = ($dias * 24) + $horas;

        // echo "<br>" . $dias_h . ":" . $minutos . ":" . $segundos;

        $resta = $fila_t['tiempo'] - $dias_h;
        $resultado = $fila_t['tiempo'] - $dias_h . " Hrs " . $minutos . " Min " . $segundos;


        if ($resta > 3) {
            $color_b = "#dcedc8;";
            $contt_ag++;
            echo '<tr>';
            echo '<td style="background: #eae9e9;">' . $fila['id_ticket'] . '</td>';
            echo '<td class="tot" style=" text-align: center;">' . ($fila['descripcion']) . '</td>';
            echo '<td class="tot" style=" text-align: left;">' . ($fila['categoria']) . '</td>';
            echo '<td class="tot" style=" text-align: left;">' . ($fila['nombre']) . '</td>';
            echo '<td class="tot" style=" text-align: center;">' . $fila['fecha_registro'] . '</td>';
            echo '<td class="tot" style=" text-align: left;">' . $resultado . '</td>';
            echo '</tr>';
        }
    }
    echo '<tr>';
    echo '<td style="background:#dcedc8;"></td>';
    echo '<td class="tot" style="background:#dcedc8; text-align: center;" ></td>';
    echo '<td class="tot" style="background:#dcedc8; text-align: center;" ></td>';
    echo '<td class="tot" style="background:#dcedc8; text-align: center;" ></td>';
    echo '<td class="tot" style="background:#dcedc8; text-align: left;" colspan="2"><strong>&nbsp;&nbsp;TOTAL DE TICKETS EN TIEMPO: ' . $contt_ag . '</strong></td>';


    echo '</tr>';
    if ($unidad === '1102') {
        ?>
        <script type="text/javascript">

            $("#a_tiempo").html('Aguascalientes: ' + '<?php echo $contt_ag; ?>');

        </script> 
        <?php
    } else
    if ($unidad === '1103') {
        ?>
        <script type="text/javascript">
            $("#m_tiempo").html('Merida: ' + '<?php echo $contt_ag; ?>');


        </script> 
        <?php
    } else
    if ($unidad === '1104') {
        ?>
        <script type="text/javascript">
            $("#j_tiempo").html('Juarez: ' + '<?php echo $contt_ag; ?>');


        </script> 
        <?php
    }
}
?>




