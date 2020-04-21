<?php

session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
require 'registra-bitcora.php';
require 'enviar_correo.php';
require '../../utils/funciones.php';
require_once '../controller/controller-notificaciones.php';

$id_ticket = "";
$operacion = "";
$desc = "";
$sql = "";
$opcion = 0;
$operador2=0;
if (isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];
}

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if (isset($_POST['operador2'])) {
    $operador2 = $_POST['operador2'];
}

if ($id_ticket != "" && $operacion != "") {

    if ($operacion == "cerrado") {
        $sql = "update tickets set ultima_actualizacion=now(), fecha_cierre=now(), estatus=3, usuario_cierre='" . $_SESSION['id_usuario'] . "' where id_ticket='$id_ticket';";
        $desc = "Cambió estatus a Cerrado";
        $opcion = 1;
    } else if ($operacion == "reabierto") {
        $sql = "update tickets set usuario_actual=$operador2, ultima_actualizacion=now(), fecha_resolucion=null,usuario_resuelve=null,usuario_anterior='" . $_SESSION['id_usuario'] . "', estatus=1 where id_ticket='$id_ticket';";
        $desc = "Cambió estatus a Activo";
    }

    $resultado = $mysqli->query($sql) or die("error: " + $mysqli->error);

    if ($resultado == '1') {
        registra_bitacora($id_ticket, 0, $id_ticket, $desc, "", "");

        if ($opcion == 1) {
            //ENVIO DE CORREO DE CIERRE
            if (isset($_SESSION['id_usuario'])) {
                $mensaje = "Haz cerrado el ticket <strong>#$id_ticket</strong>";
                $mensaje .= "<br/>";
                $mensaje .= "<br/>";

                $resultado = traerEmailSolicitanteXTicket($id_ticket);
                $row_cnt = notificaciones('7', $resultado['1']);

                if ($row_cnt >= 1) {
                    enviar_correo("Ticket #$id_ticket", $mensaje, $resultado['0']);
                }
            }

            if (isset($_SESSION['id_usuario'])) {
                $mensaje = "El usuario <strong>" . traerNombreUsuarioXID($_SESSION['id_usuario']) . "</strong> ha cerrado el ticket <strong>#$id_ticket</strong>";
                $mensaje .= "<br/>";
                $mensaje .= "<br/>";

                $resultado =  traerEmailResuelveXTicket($id_ticket);
                $row_cnt = notificaciones('7', $resultado['1']);

                if ($row_cnt >= 1) {
                    enviar_correo("Ticket #$id_ticket", $mensaje, $resultado['0']);
                }

             //   enviar_correo("Ticket #$id_ticket", $mensaje, traerEmailResuelveXTicket($id_ticket));
            }
        }
        echo true;
    } else {
        echo $resultado;
    }
} else {
    echo "error";
}
