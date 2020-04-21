<?php

session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
require 'registra-bitcora.php';
require 'enviar_correo.php';
require '../../utils/funciones.php';

$id_ticket = "";
$sigEtapaFP = "";
$idSigUsrResp = "";
$numRegistros = 0;

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if (isset($_POST['sig_etepa_fp'])) {
    $sigEtapaFP = $_POST['sig_etepa_fp'];
}

if ($id_ticket != "") {
    //TODO Obtener siguiente paso del flujo
    $query = "select t.categoria_2, t.categoria_3, t.categoria_4, t.unidad_negocio, t.tp_evt_flujo, t.sec_flujo_ant, t.usuario_anterior  
        from tickets t 
        where t.id_ticket = '$id_ticket'";
    $resultSet = $mysqli->query($query);
    $rsT = $resultSet->fetch_assoc();
    $numRegistros = $resultSet->num_rows;
    $tipoResponsable = "";

    if ($rsT['tp_evt_flujo'] == 'R') {
        $idSigUsrResp = $rsT['usuario_anterior'];
        $sigEtapaFP = $rsT['sec_flujo_ant'];
    } else {
        $query = "select fp.id_usuario_responsable, fp.tp_responsable 
        from flujo_proceso fp 
        where fp.ind_activo = true 
            and fp.id_categoria_2 = '" . $rsT['categoria_2'] . "' 
            and fp.num_etapa = '" . $sigEtapaFP . "' 
            and fp.unidad_negocio = '" . $rsT['unidad_negocio'] . "' ";

        if ($rsT['categoria_3'] > 0) {
            $query .= " and fp.id_categoria_3 = '" . $rsT['categoria_3'] . "' ";
        }

        if ($rsT['categoria_4'] > 0) {
            $query .= " and fp.id_categoria_4 = '" . $rsT['categoria_4'] . "' ";
        }

        $query .= " limit 1";
        $resultSet = $mysqli->query($query);
        $rs = $resultSet->fetch_assoc();
        $numRegistros = $resultSet->num_rows;
        $idSigUsrResp = $rs['id_usuario_responsable'];
        $tipoResponsable = $rs['tp_responsable'];
    }

    if ($numRegistros > 0) {
        if ($tipoResponsable == "G") {
            $query = "update tickets
                set sec_flujo = '$sigEtapaFP', 
                    tp_evt_flujo = null, 
                    sec_flujo_ant = null, 
                    grupo = '$idSigUsrResp', 
                    usuario_actual = null, 
                    ultima_actualizacion = now()
                where id_ticket = '$id_ticket'";
            $mysqli->query($query);

            $sql = "insert into notas(ticket, nota, usuario_registra, fecha_registro, ind_activo) "
                    . " values('$idTicket', 'Validado', '" . $_SESSION['id_usuario'] . "', now(), 1);";
            $mysqli->query($sql);
        } else {
            continuarFlujoProceso($id_ticket, $idSigUsrResp, $sigEtapaFP);
        }
        echo "resuelta";
    } else {
        echo "error";
    }
} else {
    echo "error";
}

function continuarFlujoProceso($idTicket, $idUsrResponsable, $etapaNuevaFP) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    // where are we posting to?
    $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url = substr($url, 0, strrpos($url, "/")) . "/asignar_ticket.php";

    // what post fields?
    $fields = array(
        'id_ticket' => $idTicket,
        'operador' => $idUsrResponsable,
        'id_usr_sess' => $_SESSION['id_usuario'],
        'email_usr_sess' => $_SESSION['usr_email']
    );

    // build the urlencoded data
    $postvars = http_build_query($fields);

    // open connection
    $ch = curl_init();

    // set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // execute post
    $result = curl_exec($ch);
    //echo ">>>[$result]<<<";
    // close connection
    curl_close($ch);

    $query = "update tickets
        set sec_flujo = '$etapaNuevaFP', 
            tp_evt_flujo = null, 
            sec_flujo_ant = null, 
            ultima_actualizacion = now() 
        where id_ticket = '$idTicket'";
    $mysqli->query($query);


    $sql = "insert into notas(ticket, nota, usuario_registra, fecha_registro, ind_activo) "
            . " values('$idTicket', 'Validado', '" . $_SESSION['id_usuario'] . "', now(), 1);";
    $mysqli->query($sql);
}
