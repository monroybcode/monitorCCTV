<?php

session_start();
if (!isset($_SESSION['id_usuario'])) {
    $array['correcto'] = false;
    exit();
}
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

require 'registra-bitcora.php';
require 'enviar_correo.php';
require '../../utils/funciones.php';


$unidad_organizacional = "";
$grupo = "";
$categoria = '';
$categoria1 = 'NULL';
$categoria2 = 'NULL';
$categoria3 = 'NULL';
$categoria4 = 'NULL';

$nota = "";
$comentarios = "";

$nombre_contacto = "";
$telefono_contacto = "";
$extension_contacto = "";
$horario_contacto = "";
$director = '';

if (isset($_POST['unidad_organizacional'])) {
    $unidad_organizacional = $_POST['unidad_organizacional'];
}
if (isset($_POST['categoria_1']) && $_POST['categoria_1'] != "") {
    $categoria1 = $_POST['categoria_1'];
}
if (isset($_POST['categoria_2']) && $_POST['categoria_2'] != "") {
    $categoria2 = $_POST['categoria_2'];
}
if (isset($_POST['categoria_3']) && $_POST['categoria_3'] != "") {
    $categoria3 = $_POST['categoria_3'];
}
if (isset($_POST['categoria_4']) && $_POST['categoria_4'] != "") {
    $categoria4 = $_POST['categoria_4'];
}

if (isset($_POST['nota'])) {
    $nota = $_POST['nota'];
}
if (isset($_POST['comentarios'])) {
    $comentarios = $mysqli->real_escape_string($_POST['comentarios']);
}

if (isset($_POST['nombre_contacto'])) {
    $nombre_contacto = $mysqli->real_escape_string($_POST['nombre_contacto']);
}
if (isset($_POST['unidad_contacto'])) {
    $unidad_organizacional_contacto = $_POST['unidad_contacto'];
}
if (isset($_POST['grupo'])) {
    $grupo = $_POST['grupo'];
}
if (isset($_POST['extension_contacto'])) {
    $extension_contacto = $mysqli->real_escape_string($_POST['extension_contacto']);
}
if (isset($_POST['horario_contacto'])) {
    $horario_contacto = $mysqli->real_escape_string($_POST['horario_contacto']);
}

if (isset($_POST['unidad_organizacional'])) {
    $unidad = $_POST['unidad_organizacional'];
}

if (isset($_POST['categoria'])) {
    $categoria = '';
}

if (isset($_POST['fecha_hora'])) {
    $fecha_hora = $_POST['fecha_hora'];
}


if (isset($_POST['fecha_termino'])) {
    $fecha_termino = $_POST['fecha_termino'];
}

if (isset($_POST['area'])) {
    $categoria2 = $_POST['area'];
}


if (isset($_POST['riesgo'])) {
    $prioridad = $_POST['riesgo'];
}


if (isset($_POST['evento'])) {
    $categoria4 = $_POST['evento'];
}


if (isset($_POST['url'])) {
    $url = $_POST['url'];
}

if (isset($_POST['prioridad'])) {
    $prioridad = $_POST['prioridad'];
}

if (isset($_POST['hidden-tags'])) {
    $asignado = $_POST['hidden-tags'];
}

if (isset($_POST['hidden-tags2'])) {
    $cop = $_POST['hidden-tags2'];
}

if (isset($_POST['director'])) {
    $director = $_POST['director'];
}

if (isset($_POST['idusuario'])) {
    $reporta = $_POST['idusuario'];
}

$todos = $asignado . "," . $director;

//echo $todos;

$actual = obtnercorreos($asignado);
$nombres = obtenernombres($todos);


$copias = obtenernombres($cop);

$porciones = explode(",", $actual);
$cantidad = count($porciones);

$actual = flujonot($porciones[0]);

$resutlado = '0';


$sql_compureba = "SELECT id_ticket FROM tickets where unidad_negocio= '" . $unidad_organizacional . "'and "
        . "categoria_2='" . $categoria2 . "'"
        . " and categoria_4= '" . $categoria4 . "' and comentarios = '" . $comentarios . "' limit 4";

$result = $mysqli->query($sql_compureba);
$row_cnt = $result->num_rows;


if ($row_cnt <= 0) {

    $sql = "insert into tickets(grupo, unidad_negocio, categoria_1, categoria_2, categoria_3, categoria_4, fecha_registro, ultima_actualizacion, comentarios, usuario_contacto, extension, horario, usuario_registra,usuario_anterior, usuario_actual, estatus, prioridad,url,fechaevento,fechatermino) "
            . " values('$grupo', '$unidad_organizacional', '$categoria', '$categoria2', '$categoria3', '$categoria4', now() , now(), '$comentarios', '$nombre_contacto', '$extension_contacto', '$horario_contacto', '$reporta','$actual','$actual', '1', '$prioridad','$url','$fecha_hora','$fecha_termino');";
//echo $sql;
    $mysqli->query($sql);
    $id_ticket = $mysqli->insert_id;

    $array['folio'] = $id_ticket;

    registra_bitacora($id_ticket, "0", "$id_ticket", "Se creo ticket", "", "");


    enviarCorreosTicketNuevoSinFlujo($id_ticket, $_SESSION['usr_email']);

    $cop = $cop . "," . $asignado . "," . $director;


    $correo = obtnercorreos($cop);

    $porciones_2 = explode(",", $correo);
    $porciones_3 = explode(",", $nombres);
    $copias = explode(",", $copias);

    $copias_f = "";
    $c_copias = count($copias);


    $cantidad = count($porciones_3);
    $responsables = "";
    $responsable1 = "<br>-" . $porciones_3[0];



    for ($i = 0; $i < $cantidad; $i++) {
        $sql2 = "insert into usr_ticketauto (id_ticket, nombre, email, email2,estr)"
                . " values('$id_ticket', '$porciones_3[$i]', 'responsables','$correo','1');";
//echo $sql2;
        $mysqli->query($sql2);
    }



    for ($i = 1; $i < $cantidad; $i++) {
        $responsables .= "<br>-" . $porciones_3[$i];
    }

    for ($i = 0; $i < $c_copias; $i++) {
        $copias_f .= "<br>" . $copias[$i];
    }


    for ($i = 0; $i < $c_copias; $i++) {
        $sql_copiados = "insert into usr_ticketauto (id_ticket, nombre, email, email2,estr)"
                . " values('$id_ticket', '$copias[$i]', 'copiados','','1');";
//echo $sql2;
        $mysqli->query($sql_copiados);
    }




    $sql_crea = "insert into usr_ticketauto (id_ticket, nombre, email, email2,estr)"
            . " values('$id_ticket', '" . $_SESSION['puesto'] . "', 'crea','" . $_SESSION['usr_email'] . "', '1');";
    $mysqli->query($sql_crea);




    $resutlado = enviar_copias($correo, $id_ticket, $responsable1, $responsables, $prioridad, $copias_f);

//echo $resutlado;

    if ($resutlado <= "0") {
        $eliminar = "delete from tickets where id_ticket=$id_ticket";
        $mysqli->query($eliminar);

        $bitacora = registra_bitacora($id_ticket, "0", "$id_ticket", "Se elimino ticket con error", "", "");
        $mysqli->query($bitacora);

        $array['correcto'] = false;
        echo json_encode($array);
    } else {
        $array['correcto'] = true;
        echo json_encode($array);
    }
} else {
    $array['correcto'] = "repetido";
    echo json_encode($array);
}

function obtenernombres($cadena) {

    $resultado = preg_replace("/\((.*?)\)/i", "", $cadena);
    return $resultado;
}

function obtnercorreos($cadena) {

    $string = preg_match_all('#([a-z0-9\._-]+@[a-z0-9\._-]+)#is', $cadena, $emails);
    $i = 0;
    $correo = "";

    foreach ($emails[1] as $value) {
        $correo .= $value . ",";
    }

    return $correo;
}

function flujonot($asignado) {

    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $query = "SELECT id_usuario from usuarios where email='$asignado'";

    $resultSet = $mysqli->query($query);
    $rs = $resultSet->fetch_assoc();
    return $rs['id_usuario'];
}

function enviarCorreosTicketNuevoSinFlujo($id_ticket, $actual) {
    require '../connection/conexion.php';
    require '../controller/controller-notificaciones.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $sql_usuario = "select nombre from usuarios where id_usuario='$actual'";
    $resultSet = $mysqli->query($sql_usuario);
    $rs2 = $resultSet->fetch_assoc();


    if (isset($_SESSION['usr_email']) && $_SESSION['usr_email'] != '') {

        $mensaje = "Haz creado el Reporte <strong>#$id_ticket</strong>";
        $mensaje .= "<br/>";
        $mensaje .= "<br/>";
        $mensaje .= "Asignado a: <strong>" . $rs2['nombre'] . "</strong>";
        $mensaje .= "<br/>";


        enviar_correo("Reporte #$id_ticket", $mensaje, $_SESSION['usr_email']);
    }
}

function enviar_copias($usuarios, $ticket, $responsables1, $responsables, $prioridad, $copias) {
    require '../connection/conexion.php';
    $mysqli->query("SET NAMES 'UTF8'");

    $contador_veces = 0;

    $query = "SELECT 
    id_ticket,
    comentarios,
    fechaevento,
    fechatermino,
    areas.nombre_area AS area,
    categoria.nombre AS evento,
    fechaevento,
    u1.nombre AS informador,
    u1.puesto AS puesto,
    u2.nombre AS resuelve,
    hospital.nombre AS hospital,
    url
FROM
    tickets
        LEFT JOIN
    areas ON idareas = categoria_2
        LEFT JOIN
    categoria ON id = categoria_4
        LEFT JOIN
    usuarios u1 ON u1.id_usuario = tickets.usuario_registra
        LEFT JOIN
    usuarios u2 ON u2.id_usuario = tickets.usuario_actual
        LEFT JOIN
    hospital ON hospital.id = unidad_negocio
where id_ticket = $ticket
";

//echo $query;
    $resultSet = $mysqli->query($query);
    $rs = $resultSet->fetch_assoc();

    $boton = null;
    $bolean = FALSE;

    if ($rs['url'] != '') {
        $boton = '<a href="' . $rs["url"] . '" style="background-color:#D3D3D3;border:1px solid #EB7035;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;">Haga click aquí para ver el video &rarr;</a>';
    }

    $dato = $rs['fechaevento'];
    $fecha = date('d-m-Y', strtotime($dato));
    $hora = date('H:i:s', strtotime($dato));


    $dato2 = $rs['fechatermino'];
    $fecha2 = date('d-m-Y', strtotime($dato2));
    $hora2 = date('H:i:s', strtotime($dato2));


    $usuarios = $usuarios . $_SESSION['usr_email'];
    $porciones = explode(",", $usuarios);
    $cantidad = count($porciones);

    $str_2 = str_replace("\n", '<br>', $rs["comentarios"]);

    $color = '#051C48';
    $texto = 'Baja';

    if ($prioridad == '1') {
        $color = '#ff3232';
        $texto = 'Alta';
    } else
    if ($prioridad == '2') {
        $color = '#A0D3FE';
        $texto = 'Media';
    } else
    if ($prioridad == '3') {
        $color = '#051C48';
        $texto = 'Baja';
    }

    $body = '
   <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
</html>
<body style="margin: 0; padding: 0;">
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <table align="center"  cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                    <tr>
                        <td align="center"  style="padding: 0px 0 0px 0;">
                            <img src="../images/head_email.PNG"  width="100%"  style="display: block;" />
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 0px 0px 0px 0px;">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="padding-bottom: 15px">
                                        Reporte: ' . $rs["id_ticket"] . ' 

                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table borde="1" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="130" valign="top">
                                                    <table style="border-right: 1px solid #000;" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td style="height: 40px; background-color: ' . $color . '; color: white;font-size: 12px">
                                                                &nbsp;' . $texto . '
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                              
                                                                <p style="font-weight: normal;font-size: 11px; color: #777779"><span style="color: #000000; font-size: 11px; font-weight: bold;">' . $rs["hospital"] . '</span><span  style="font-weight: bold;color:#1261a9">' . $responsables . '</span></p>
                                                                <p style="font-weight: normal;font-size: 11px; color: #777779"><span style="color: #000000; font-size: 11px; font-weight: bold;">Responsable(S):</span>&nbsp;<span style="font-weight: bold;color:#1261a9">' . $responsables1 . '</span></p>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                                <p style="color: #000000; font-size: 11px"><strong>Datos Generales</strong></p>
                                                                <p style="font-weight: normal;font-size: 11px; color: #777779">Area:&nbsp;' . $rs["area"] . '</p>
                                                                <p style="font-weight: normal;font-size: 11px; color: #777779">Reporte:&nbsp;' . $rs["evento"] . '</p>
                                                                
                                                            </td>
                                                        </tr>
                                                        
                                                       

                                                        <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                                <p style="color: #000000;font-size: 11px"><strong>Fecha / Hora del evento</strong></p>
                                                                <p style="font-weight: normal;font-size: 11px; color: #777779">Fecha:&nbsp;' . $fecha . '</p>
                                                                <p style="font-weight: normal;font-size: 11px; color: #777779">De:&nbsp;' . $hora . ' a:&nbsp;' . $hora2 . '</p>
                                                            </td>
                                                        </tr>
                                                        
                                                         <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                                <p style="color: #000000;font-size: 11px"><strong>Informador</strong></p>
                                                                <p style="font-weight: normal;font-size: 11px; color: #777779">' . $rs["puesto"] . '</p>
                                                           
                                                            </td>
                                                        </tr>
                                                        
                                                          <tr>
                                                            <td width="100%">
                                                               &nbsp; <a href="' . $rs["url"] . ' " style="background-color:#051C48;border:1px solid #051C48;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:99%;-webkit-text-size-adjust:none;mso-hide:all;">Archivos Adjuntos &rarr;</a>
                                                            </td>
                                                          </tr>
                                                          
                                                          
                                                        <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                                <p style="font-weight: normal;font-size: 11px; color: #777779"><span style="color: #000000; font-size: 11px; font-weight: bold;">Cc.</span><span style="color:#1261a9;font-weight: bold;">' . $copias . '</span></p>
                                                            </td>
                                                        </tr>
                                                          
                                                          <tr>
                                                            <td width="100%">
                                                           <br>
                                                            
                                                              <a href="http://monitor.starmedica.com/monitor-cctv/tickets-preview.php"><span>Ingresar al Portal</span></a>
                                                            </td>
                                                          </tr>

                                                        <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                                <p style="color: #777779; font-size: 11px; text-alig: justify;">Cualquier Informacion al Respecto Sera Atendida de Manera Imediata</p>

                                                            </td>
                                                        </tr>

                                                    </table>
                                                </td>
                                                <td style="font-size: 0; line-height: 0;" width="1">

                                                </td>
                                                <td width="260" valign="top">
                                                    <table  cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td style="height: 35px; background-color: #b9b7ba; color: white;font-size: 12px">
                                                                <p>&nbsp;Comentarios</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 15px 0 0 0;">
                                                             <p style="color: black; font-size: 14px; text-align: justify; line-height: 20px">
                                                              ' . $str_2 . '
                                                            </p>
                                                               
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    

                    <tr>
                        <td >
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="100%">
                                        <p style="color: #777779; font-size: 11px; text-alig: justify;"><strong>Aviso de Confidencialidad</strong><br>Este correo electrónico y sus archivos adjuntos, contienen información confidencial y privilegiada, propiedad de Star Médica, S.A. de C.V., sometida a secreto profesional, cuya divulgación está prohibida por la ley, por lo cual, Usted debe abstenerse de darla a conocer a persona alguna, así como a reproducirla o copiarla sin autorización previa y por escrito de un representante legal de Star Médica, S.A. de C.V. Si recibe este mensaje por error, favor de notificarlo de inmediato a <span style="#6666ff"> centralmonitoreo@starmedica.com </span> y eliminarlo de su sistema.</p>
                                    </td>


                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
';

//echo $usuarios;
    $cadena = '';
    $cadena2 = '';

    for ($i = 0; $i < $cantidad; $i++) {

        $debug = '';

        $mail = new PHPMailer();
        $mail->isSMTP();

        $mail->SMTPDebug = 0;
        $mail->Debugoutput = function($str, $level) {
            $debug = "debug level $level; message: $str";
        };


        $mail->Host = 'starmedica-com.mail.protection.outlook.com';
        $mail->Port = 25;
        $mail->From = 'centralmonitoreo@starmedica.com';
        $mail->FromName = utf8_decode('Central de Monitoreo');
        $mail->Username = '';

        $mail->addAddress($porciones[$i]);
        $mail->Subject = 'Reporte MONITOREO CCTV';
        $mail->MsgHTML(utf8_decode($body));
        $mail->AltBody = 'Mensaje de Portal de Reporte CCTV';
        if ($mail->Send()) {
            $cadena .= '*success: ' . $porciones[$i];
            $contador_veces++;
        } else {

            $cadena2 .= "*" . $porciones[$i] . " Descripcion " . $mail->ErrorInfo;
        }
    }


    $id_ticket = $rs["id_ticket"];


    if ($cadena != '') {
        $sql_log = "insert into log_mail (idlog, id_ticket,status, log)"
                . " values('' ,'$id_ticket','Enviado', '$cadena');";
        $bolean = TRUE;

        $mysqli->query($sql_log);
    }

    if ($cadena2 != '') {
        $sql_log2 = "insert into log_mail (idlog, id_ticket,status, log)"
                . " values('' ,'$id_ticket', 'Error','$cadena2');";
        $bolean = TRUE;

        $mysqli->query($sql_log2);
    }


    return $contador_veces;
}
