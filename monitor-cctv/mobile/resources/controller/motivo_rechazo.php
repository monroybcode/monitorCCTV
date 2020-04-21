<?php
session_start();
include './../../../resources/connection/conexion.php';
if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}

$mysqli->query("SET NAMES 'utf8'");

include "./../../../utils/constantes.php";
require './../../../resources/controller/registra-bitcora.php';
include ('../../enviar_correo_mobile.php');
require '../../../utils/funciones.php';

$mysqli->query("SET NAMES 'utf8'");
$id_ticket = "";
$nota = "";

if (isset($_POST['message'])) {
    $nota = $_POST['message'];
}
if (isset($_POST['id'])) {
    $id_ticket = $_POST['id'];
}


$sql = "select * from tickets where id_ticket='".$id_ticket."'";
$resultado = $mysqli->query($sql);
$ticket = $resultado->fetch_assoc();

$ticket2=$ticket['id_ticket'];

 $sql = "insert into notas(ticket, nota, usuario_registra, fecha_registro, ind_activo,aut_rech) values('$ticket2', '$nota', '" . $_SESSION['id_usuario'] . "', now(), 1,2);";
 //echo $sql; 
 $mysqli->query($sql);
    $id_nota = $mysqli->insert_id;
    $array['id_nota'] = $id_nota;
    $array['correcto'] = true;
    if ($array['correcto'] === true) {
        date_default_timezone_set('America/Mexico_City');
        $array['usuario'] = $_SESSION['nombre'];
        $array['fecha'] = date("d/m/Y H:i");
        echo json_encode($array);
    }
    
    
 if ($id_ticket != "") {
    //TODO Obtener paso anterior del flujo
     $query = "select t.categoria_2, t.categoria_3, t.categoria_4, t.sec_flujo 
        from tickets t 
        where t.id_ticket = '$id_ticket'";
    $resultSet = $mysqli->query($query);
    $rsT = $resultSet->fetch_assoc();
    
    $query = "select fp.num_etapa, fp.id_usuario_responsable
        from flujo_proceso fp 
        where fp.ind_activo = true 
            and fp.id_categoria = '" . $rsT['categoria_2'] . "' 
            and fp.num_etapa < '" . $rsT['sec_flujo'] . "'";

    if ($rsT['categoria_3'] > 0) {
        $query .= " and fp.id_categoria_3 = '" . $rsT['categoria_3'] . "' ";
    }

    if ($rsT['categoria_4'] > 0) {
        $query .= " and fp.id_categoria_4 = '" . $rsT['categoria_4'] . "' ";
    }

    $query .= " order by fp.num_etapa desc 
        limit 1";
    //echo $query;
    $resultSet = $mysqli->query($query);
    $rs = $resultSet->fetch_assoc();
    $numRegistros = $resultSet->num_rows;

    if ($numRegistros > 0) {
        regresarFlujoProceso($id_ticket, $rs['id_usuario_responsable'], $rs['num_etapa']);
        echo "resuelta";
    } else {
        echo "error";
    }
} else {
    echo "error";
}

function regresarFlujoProceso($id_ticket, $idUsrResponsable, $etapaAnteriorFP) {
    require './../../../resources/connection/conexion.php';
    
    $mysqli->query("SET NAMES 'UTF8'");
    
    // where are we posting to?
    $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url = substr($url, 0, strrpos($url, "/")) . "/asignar_ticket.php";

    // what post fields?
    $fields = array(
        'id_ticket' => $id_ticket,
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
        set sec_flujo = '$etapaNuevaFP', estatus= '2'
        where id_ticket = '$id_ticket'";
    //echo $query;
    $mysqli->query($query);
}

 if($id_ticket != ''){
            $mensaje = "El ticket <strong>#$id_ticket</strong> ha sido rechazado por el usuario <strong>" . $_SESSION['nombre'] . "</strong>";
            $mensaje .= "<br/>";
            $mensaje .= "<br/>";
            enviar_correo2("Ticket #$id_ticket", $mensaje, $_SESSION['usr_email']);
        }


?>
