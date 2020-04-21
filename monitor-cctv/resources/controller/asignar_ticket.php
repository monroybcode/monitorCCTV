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
$id_usuario_asignado = "";
$idUsrSession = "";
$emailUsrSession = "";

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if (isset($_POST['operador']) && $_POST['operador'] != "") {
    $id_usuario_asignado = $_POST['operador'];
}

if (isset($_SESSION['id_usuario'])) {
    $idUsrSession = $_SESSION['id_usuario'];
} else if (isset($_POST['id_usr_sess'])) {
    $idUsrSession = $_POST['id_usr_sess'];
    $_SESSION['id_usuario'] = $idUsrSession;
}

if (isset($_SESSION['usr_email'])) {
    $emailUsrSession = $_SESSION['usr_email'];
} else if (isset($_POST['email_usr_sess'])) {
    $emailUsrSession = $_POST['email_usr_sess'];
}


if ($id_ticket != "" && $id_usuario_asignado != "") {
    $sql = "update tickets set usuario_anterior='" . $idUsrSession . "', usuario_actual='$id_usuario_asignado', ultima_actualizacion=now() where id_ticket='$id_ticket';";
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
                         <th scope='row' style='text-align: left; padding: 8px; background-color:#5D80A9; color: white; width:12%'>Reporte</th>
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
                enviar_correo("Ticket #$id_ticket", $mensaje, $resultado['0']);
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
            $row_cnt = notificaciones('5', $resultado['1']);
          
            if ($row_cnt >= 1) {
                enviar_correo("Ticket #$id_ticket", $mensaje, $resultado['0']);
            }
        }

        echo "asignado";
    } else {
        echo $resultado;
    }
} else {
    echo "error";
}
