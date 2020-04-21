<?php

session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
require 'registra-bitcora.php';
require_once '../controller/controller-notificaciones.php';
require '../../utils/funciones.php';
require '../controller/enviar_correo.php';

$id_ticket = "";

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if ($id_ticket != "") {
    $sql = "update tickets set estatus=3, ultima_actualizacion=now() where id_ticket='$id_ticket';";
    $resultado = $mysqli->query($sql) or die("error: " + $mysqli->error);

    if ($resultado == '1') {
        registra_bitacora($id_ticket, 0, $id_ticket, "Cambió estatus a Cancelado", "", "");

        //ENVIAR CORREO
        if ($id_ticket != '') {
            $mensaje = "Haz cancelado el ticket <strong>#$id_ticket</strong>";
            $mensaje .= "<br/>";
            $mensaje .= "<br/>";

            $resultado = traerEmailSolicitanteXTicket($id_ticket);
            $row_cnt = notificaciones('6', $resultado['1']);

            if ($row_cnt >= 1) {
                enviar_correo("Ticket #$id_ticket", $mensaje, $resultado['0']);
            }
            
        }

        if ($id_ticket != '') {
            $mensaje = "El usuario de tu grupo <strong>" . traerNombreUsuarioXID($_SESSION['id_usuario']) . "</strong> ha cancelado el ticket <strong>#$id_ticket</strong>";
            $mensaje .= "<br/>";

            $correosGrupoAtiende = traerCorreosGrupoXUsuario($_SESSION['id_usuario']);
            foreach ($correosGrupoAtiende as $mail) {
                if ($mail != $_SESSION['usr_email']) {
                    enviar_correo("Ticket #$id_ticket", $mensaje, $mail);
                }
            }
        }

        if ($id_ticket != '') {
            $mensaje = "El usuario <strong>" . traerNombreUsuarioXID($_SESSION['id_usuario']) . "</strong> ha cancelado el ticket <strong>#$id_ticket</strong>";
            $mensaje .= "<br/>";

            $correosGrupoAtiende = traerCorreosGrupoXUsuario(traerIdUsuarioAtiendeXIdTicket($id_ticket));
            foreach ($correosGrupoAtiende as $mail) {
                enviar_correo("Ticket #$id_ticket", $mensaje, $mail);
            }
        }

        echo "cancelada";
    } else {
        echo $resultado;
    }
} else {
    echo "error";
}
