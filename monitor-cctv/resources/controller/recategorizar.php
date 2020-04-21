<?php

if (!isset($_SESSION)) {
    session_start();
}
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

require_once 'registra-bitcora.php';
require_once 'enviar_correo.php';
require_once '../../utils/funciones.php';
require_once '../controller/controller-notificaciones.php';

$id_ticket = "";
$id_categoria = "";


if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if (isset($_POST['categoria_r'])) {
    $id_categoria = $_POST['categoria_r'];
}

$sql_ticket_h = "select unidad_negocio from tickets where id_ticket='" . $id_ticket . "' ";
//echo $sql_ticket_h;
$resultado_h = $mysqli->query($sql_ticket_h);

$row = $resultado_h->fetch_assoc();

$hospital_t = $row['unidad_negocio'];

$nombre_hospital = '';
if ($id_categoria === '6') {
    if ($hospital_t == '1102') {
        $id_usuario_asignado = '8';
        $nombre_hospital = 'HSM Aguascalientes';
    } else
    if ($hospital_t == '1103') {
        $id_usuario_asignado = '8';
        $nombre_hospital = 'HSM Mérida';
    } else
    if ($hospital_t == '1104') {
        $id_usuario_asignado = '8';
        $nombre_hospital = 'HSM Cd Juárez';
    }
} else
if ($id_categoria === '7') {
    if ($hospital_t == '1102') {
        $id_usuario_asignado = '5';
        $nombre_hospital = 'HSM Aguascalientes';
    } else
    if ($hospital_t == '1103') {
        $id_usuario_asignado = '6';
        $nombre_hospital = 'HSM Mérida';
    } else
    if ($hospital_t == '1104') {
        $id_usuario_asignado = '7';
        $nombre_hospital = 'HSM Cd Juárez';
    }
} else
if ($id_categoria === '8') {
    if ($hospital_t == '1102') {
        $id_usuario_asignado = '9';
        $nombre_hospital = 'HSM Aguascalientes';
    } else
    if ($hospital_t == '1103') {
        $id_usuario_asignado = '10';
        $nombre_hospital = 'HSM Mérida';
    } else
    if ($hospital_t == '1104') {
        $id_usuario_asignado = '11';
        $nombre_hospital = 'HSM Cd Juárez';
    }
}





if ($id_ticket != "" && $id_usuario_asignado != "") {

    $sql_ticket_usuarioant= "select usuario_actual from tickets where id_ticket='" . $id_ticket . "' ";

    $resultado_usuario= $mysqli->query($sql_ticket_usuarioant);

    $row_usuario = $resultado_usuario->fetch_assoc();
    $usuarioanterior=$row_usuario['usuario_actual'];


    $sql = "update tickets set categoria_2='" . $id_categoria . "', usuario_anterior='" . $usuarioanterior . "', usuario_actual='$id_usuario_asignado', ultima_actualizacion=now() where id_ticket='$id_ticket';";
    $resultado = $mysqli->query($sql) or die("error: " + $mysqli->error);

    if ($resultado == '1') {
        registra_bitacora($id_ticket, "0", $id_ticket, "Se asignó usuario", "", "");

        //Traer datos de ticket
        $sql2 = "select t.id_ticket
                , date_format(t.fecha_registro,'%d/%m/%Y %H:%i:%s') as fecha_registro
                , t.comentarios
                , h.nombre as hospital
                , concat(c2.nombre,' | ',c3.nombre) as categ
                from tickets t
                left join hospital h on t.unidad_negocio = h.id
                left join categoria c1 on c1.id=t.categoria_1 
                left join categoria c2 on c2.id=t.categoria_2 
                left join categoria c3 on c3.id=t.categoria_3 
                where t.id_ticket='" . $id_ticket . "';";

        $resultado2 = $mysqli->query($sql2);
        $fila = $resultado2->fetch_assoc();
        $concatena = ($fila['hospital'] == "" ? "" : $fila['hospital']) . " | " . ($fila['categ'] == "" ? "" : $fila['categ']);
        //$concatena = utf8_encode($concatena);
        //ENVIO DE CORREOS
        if ($id_ticket != '') {


            $mensaje = "<table  style='border-collapse: collapse; width: 100%;'>
                <caption style='background-color: #f2f2f2'><strong style='font-size:18px'> $concatena </strong> </caption>
                    <colgroup>
                       <col />
                       <col />
                       <col />
                    </colgroup>
                    <tbody>
                        <tr>
                         <th scope='row' style='text-align: left; padding: 8px; background-color:#5D80A9; color: white; width:12%'>Evento</th>
                         <td style='text-align: left; padding: 8px;'><p style='font-size:13px;line-height: 1.6; text-align:justify;'>Se asignó el ticket <strong>#$id_ticket</strong> al usuario <strong>" . traerNombreUsuarioXID($id_usuario_asignado) . "</strong></p></td>
                       </tr>
                       <tr style='background-color: #fafafa'>
                         <th scope='row' style='text-align: left; padding: 8px; background-color: rgb(52, 96, 148); color: white;'>Comentarios</th>
                         <td style='text-align: left; padding: 8px;'><p style='font-size:13px;line-height: 1.6; text-align:justify;'>" . $fila['comentarios'] . "</p></td>
                       </tr>
                       <tr>
                         <th scope='row' style='text-align: left; padding: 8px; background-color:#5D80A9; color: white; width:12%'>Creado</th>
                         <td style='text-align: left; padding: 8px;'><p style='font-size:13px;line-height: 1.6; text-align:justify;'>" . $fila['fecha_registro'] . "</p></td>
                       </tr>
                    </tbody> 
             </table>";

            $resultado = traerEmailSolicitanteXTicket($id_ticket);

            $row_cnt = notificaciones('5', $resultado['1']);

            if ($row_cnt >= 1) {

                enviar_correo("Reporte #$id_ticket", $mensaje, $resultado['0']);
            }
        }

        if ($id_usuario_asignado != '') {


            $mensaje = "<table  style='border-collapse: collapse; width: 100%;'>
                <caption style='background-color: #f2f2f2'><strong style='font-size:18px'> $concatena </strong> </caption>
                    <colgroup>
                       <col />
                       <col />
                       <col />
                    </colgroup>
                    <tbody>
                        <tr>
                         <th scope='row' style='text-align: left; padding: 8px; background-color:#5D80A9; color: white; width:12%'>Acción</th>
                         <td style='text-align: left; padding: 8px;'><p style='font-size:13px;line-height: 1.6; text-align:justify;'>Atender el ticket <strong>#$id_ticket</strong></p></td>
                       </tr>
                       <tr>
                         <th scope='row' style='text-align: left; padding: 8px; background-color:#5D80A9; color: white; width:12%'>Hospital</th>
                         <td style='text-align: left; padding: 8px;'><p style='font-size:13px;line-height: 1.6; text-align:justify;'> <strong>$nombre_hospital</strong></p></td>
                       </tr>
                       <tr style='background-color: #fafafa'>
                         <th scope='row' style='text-align: left; padding: 8px; background-color: rgb(52, 96, 148); color: white;'>Comentarios</th>
                         <td style='text-align: left; padding: 8px;'><p style='font-size:13px;line-height: 1.6; text-align:justify;'>" . $fila['comentarios'] . "</p></td>
                       </tr>
                       <tr>
                         <th scope='row' style='text-align: left; padding: 8px; background-color:#5D80A9; color: white; width:12%'>Creado</th>
                         <td style='text-align: left; padding: 8px;'><p style='font-size:13px;line-height: 1.6; text-align:justify;'>" . $fila['fecha_registro'] . "</p></td>
                       </tr>
                    </tbody> 
             </table>";

            $resultado = traerEmailAtiendeXTicket($id_ticket);
            $row_cnt = notificaciones('8', $resultado['1']);

            if ($row_cnt >= 1) {
                enviar_correo("Reporte #$id_ticket", $mensaje, $resultado['0']);
            }
        }



        echo "asignado";
    } else {
        echo $resultado;
    }
} else {
    echo "error";
}
