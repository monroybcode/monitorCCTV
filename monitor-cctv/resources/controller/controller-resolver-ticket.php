<?php

session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
require 'registra-bitcora.php';
require 'enviar_correo.php';
require '../../utils/funciones.php';
require_once '../controller/controller-notificaciones.php';

$id_ticket = "";

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if ($id_ticket != "") {

    $usuario_anterior = traerEmailSolicitanteXTicket($id_ticket);

    if ($usuario_anterior['0'] === 'ticketauto') {
       // echo $usuario_anterior['0'] . "preuba";
        $resultado2 = traerEmailSolicitanteXTicket_auto($id_ticket);

        $row_cnt = notificaciones('9', $resultado2['1']);
    }


     $sql = "update tickets set ultima_actualizacion=now(), fecha_resolucion=now(), estatus=2, usuario_resuelve='" . $_SESSION['id_usuario'] . "', usuario_anterior='" . $_SESSION['id_usuario'] . "', usuario_actual=usuario_registra where id_ticket='$id_ticket';";
     $resultado = $mysqli->query($sql) or die("error: " + $mysqli->error);

    if ($resultado == '1') {
        registra_bitacora($id_ticket, 0, $id_ticket, "Cambió estatus a Resuelto", "", "");

        //ENVIAR CORREO
        if ($id_ticket != '') {
            $mensaje = "Tu ticket <strong>#$id_ticket</strong> ha sido resuelto por el usuario <strong>" . traerNombreUsuarioXID($_SESSION['id_usuario']) . "</strong>";
            $mensaje .= "<br/>";
            $mensaje .= "<br/>";

            $resultado = traerEmailSolicitanteXTicket($id_ticket);
            if ($resultado['0'] == 'ticketauto') {


                if ($row_cnt >= 1) {
                    enviar_correo("Ticket #$id_ticket", $mensaje, $resultado2['0']);
                }
            } else {
                $row_cnt = notificaciones('9', $resultado['1']);

                if ($row_cnt >= 1) {
                    enviar_correo("Ticket #$id_ticket", $mensaje, $resultado['0']);
                }
            }
        }

        /* if($id_ticket != ''){
          $idUsrSolicita = traerIdUsuarioSolicitaXIdTicket($id_ticket);
          $mensaje = "El ticket <strong>#$id_ticket</strong> creado por <strong>" . traerNombreUsuarioXID($idUsrSolicita) . "</strong> fue resuelto por el usuario <strong>" . traerNombreUsuarioXID($_SESSION['id_usuario']) . "</strong>";
          $mensaje .= "<br/>";

          $correosGrupoSolicita = traerCorreosGrupoXUsuario($idUsrSolicita);
          foreach ($correosGrupoSolicita as $mail) {
          enviar_correo("Ticket #$id_ticket", $mensaje, $mail);
          }
          } */

        if ($id_ticket != '') {
            $mensaje = "Haz resuelto el ticket <strong>#$id_ticket</strong>";
            $mensaje .= "<br/>";
            $mensaje .= "<br/>";

            $resultado = traerEmailAtiendeXTicket($id_ticket);
            $row_cnt = notificaciones('9', $resultado['1']);

            if ($row_cnt >= 1) {
                enviar_correo("Ticket #$id_ticket", $mensaje, $resultado['0']);
            }
        }

        /* if($id_ticket != ''){
          $mensaje = "El usuario de tu grupo <strong>" . traerNombreUsuarioXID($_SESSION['id_usuario']) . "</strong> ha resuelto el ticket <strong>#$id_ticket</strong>";
          $mensaje .= "<br/>";

          $correosGrupoAtiende = traerCorreosGrupoXUsuario($_SESSION['id_usuario']);
          foreach ($correosGrupoAtiende as $mail) {
          enviar_correo("Ticket #$id_ticket", $mensaje, $mail);
          }
          } */

        echo "resuelta";
    } else {
        echo $resultado;
    }
} else {
    echo "error";
}
