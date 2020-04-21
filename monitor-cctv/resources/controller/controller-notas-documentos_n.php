<?php

session_start();
require '../connection/conexion.php';
require '../controller/registra-bitcora.php';
$mysqli->query("SET NAMES 'utf8'");

$id_ticket = "";
$nota = "";
$stts = "";

if (isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];
}

if (isset($_POST['txt-note'])) {
    $nota = $_POST['txt-note'];
}

if (isset($_POST['stts_ticket'])) {
    $stts = $_POST['stts_ticket'];
}

if (isset($_POST['url'])) {
    $url = $_POST['url'];
}

if ($id_ticket != "") {

    if ($nota != "") {
        $sql = "insert into notas(ticket, nota, usuario_registra, fecha_registro, ind_activo, archivo) "
                . " values('$id_ticket', '$nota', '" . $_SESSION['id_usuario'] . "', now(), 1, '$url');";
        $mysqli->query($sql);
        $id_nota = $mysqli->insert_id;

        if ($id_nota != 0) {
            $array['correcto'] = true;
            $array['id_nota'] = $id_nota;

            $sql_act_ticket = "update tickets set ultima_actualizacion=now() where id_ticket='$id_ticket';";
            $mysqli->query($sql_act_ticket);
            registra_bitacora($id_ticket, 0, $id_nota, "Se registro nota", "", "");
        } else {
            $array['correcto'] = false;
        }
    } else {
        $array['correcto'] = false;
    }

//    if ($array['correcto'] === true) {
//        $sql = "select usuario_registra, usuario_actual from tickets where id_ticket='$id_ticket';";
//        $resultado = $mysqli->query($sql);
//        $fila = $resultado->fetch_assoc();
//        if ($stts == '2' && $fila['usuario_registra'] == $_SESSION['id_usuario']) {
//            $sql = "update tickets set estatus=4, ultima_actualizacion=now() where id_ticket='$id_ticket';";
//            $mysqli->query($sql);
//            registra_bitacora($id_ticket, 0, $id_ticket, "Se actualizó estatus a Pendiente", "", "");
//        }

    date_default_timezone_set('America/Mexico_City');
    $array['usuario'] = $_SESSION['nombre'];
    $array['fecha'] = date("d/m/Y H:i");
    $array['nota'] = str_replace("\n", "</br>", $nota);

//        $resultadoStts = $mysqli->query("select estatus from tickets where id_ticket='$id_ticket';");
//        $filaStts = $resultadoStts->fetch_assoc();
//        $stts = $filaStts['estatus'];

    $cont = 1;

    foreach ($_FILES['adjunto']['error'] as $archivo => $error) {
        if ($error == UPLOAD_ERR_OK) {
            $nombre_temporal = $_FILES["adjunto"]["tmp_name"][$archivo];
            $nombre_archivo = $_FILES["adjunto"]["name"][$archivo];
            $tamanio = $_FILES["adjunto"]["size"][$archivo];
            $tipo = $_FILES["adjunto"]["type"][$archivo];

            if ($tamanio != 0) {
                $fp = fopen($nombre_temporal, "rb");
                $contenido = fread($fp, $tamanio);
                $contenido = addslashes($contenido);
                fclose($fp);

                $sql = "insert into documentos(ticket, documento, content_type, file_name, usuario_registra, fecha_registro, ind_activo) "
                        . " values('$id_ticket', '$contenido', '$tipo', '$nombre_archivo', '" . $_SESSION['id_usuario'] . "', now(), 1)";

                $array['doc_correcto' . $cont] = $mysqli->query($sql) or die("error: " + $mysqli->error);

                if ($array['doc_correcto' . $cont] === true) {
                    $array['nombre_doc' . $cont] = $nombre_archivo;
                    $array['id_doc' . $cont] = $mysqli->insert_id;

                    if ($array['correcto'] === true) {
                        $sql = "update notas set archivo='" . $array['id_doc' . $cont] . "' where id_nota='$id_nota';";
                        $mysqli->query($sql);
                    }

                    $sql_act_ticket = "update tickets set ultima_actualizacion=now() where id_ticket='$id_ticket';";
                    $mysqli->query($sql_act_ticket);
                    registra_bitacora($id_ticket, $stts, $array['id_doc' . $cont], "Se registro documento", "", "");
                }
            } else {
                $array['doc_correcto' . $cont] = false;
            }
        }

        $cont++;
    }

    $array['num_docs'] = $cont;


    $agrega_n = $_SESSION['id_usuario'];

    $agrega_nota = "SELECT 
    email,puesto
FROM
    usuarios
    where  id_usuario='$agrega_n'";

    $r5 = $mysqli->query($agrega_nota);
    $agrega_notar = $r5->fetch_assoc();



      $notas = "SELECT 
    notas.*, usuarios.nombre, usuarios.rol, usuarios.puesto
FROM
    notas
        LEFT JOIN
    usuarios ON usuarios.id_usuario = notas.usuario_registra
    where ticket='$id_ticket';";
    $r6 = $mysqli->query($notas);

    $texto_n = '';
    while ($t_notas = $r6->fetch_assoc()) {
        $nombre = '';
        if ($t_notas['rol'] == 10) {
            $nombre = $t_notas['puesto'];
        } else {
            $nombre = $t_notas['nombre'];
        }
        $texto_n .= '<strong>' . $nombre . '</strong>-' . date_format(date_create($t_notas['fecha_registro']), "d/m/Y H:i") . '<br>' . $t_notas['nota'] . '<br><br>';
    }

    correo_evento($id_ticket, $agrega_notar['puesto'], $texto_n);

    echo json_encode($array);
} else {
    $array['correcto'] = false;
    echo json_encode($array);
}

function correo_evento($id_ticket, $agrega_n, $texto_n) {
    require '../connection/conexion.php';
    require '../../resources/class/PHPMailerAutoload.php';



    $query1 = "SELECT 
    id_ticket,
    prioridad,
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
where id_ticket = $id_ticket
";

    $resultSet = $mysqli->query($query1);
    $rs = $resultSet->fetch_assoc();


    $query2 = "SELECT * from usr_ticketauto where id_ticket= $id_ticket";
    $resultSet2 = $mysqli->query($query2);
    $responsable_co = '';
    $copiados = '';
    $crea = '';

    $correos = '';
    $correo_crea = '';
    $responsable = '';
    $cont=0;

    while ($rs2 = $resultSet2->fetch_assoc()) {
        if ($cont == 0) {
            $correos = $rs2['email2'];
            $cont++;
        }

        if ($rs2['email'] == 'responsables') {
            $responsable_co .= $rs2['nombre'] . ',';
        }

        if ($rs2['email'] == 'copiados') {
            $copiados .= '-' . $rs2['nombre'] . '<br>';
        }

        if ($rs2['email'] == 'crea') {
            $crea = $rs2['nombre'];
            $correo_crea = $rs2['email2'];
        }
    }
    


    $responsables1 = explode(",", $responsable_co);

    $cantidad_responsables = count($responsables1);
    $cont2 = 1;

    while ($cont2 < $cantidad_responsables) {
        $responsables = '-' . $responsables1[$cont2]. '<br>';
        $cont2++;
    }

    $boton = null;

    if ($rs['url'] != '') {
        $boton = '<a href="' . $rs["url"] . '" style="background-color:#D3D3D3;border:1px solid #EB7035;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;">Haga click aquí para ver el video &rarr;</a>';
    }

    $dato = $rs['fechaevento'];
    $fecha = date('d-m-Y', strtotime($dato));
    $hora = date('H:i:s', strtotime($dato));


    $dato2 = $rs['fechatermino'];
    $fecha2 = date('d-m-Y', strtotime($dato2));
    $hora2 = date('H:i:s', strtotime($dato2));

    $prioridad = $rs["prioridad"];




    $str_2 = str_replace("\n", '<br>', $rs["comentarios"]);


    $color = '#051C48';
    $texto = 'Baja';
    if ($prioridad == '1') {
        $color = '#ff3232';
        $texto = 'Alta';
    }

    $porciones2 = $correos . $correo_crea;
    $porciones2= explode(",", $porciones2);
    $cantidad2= count($porciones2);

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
                                        Reporte: ' . $id_ticket . ' 
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <table borde="1" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="130" valign="top">
                                                    <table style="border-right: 1px solid #000;" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td style="height: 40px; background-color: ' . $color . '; color: white;font-size: 14px">
                                                                &nbsp;' . $texto . '
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                              
                                                                <p style="font-weight: normal;font-size: 13px; color: #777779"><span style="color: #000000; font-size: 13px; font-weight: bold;">' . $rs["hospital"] . '</span><span  style="font-weight: bold;color:#1261a9"><br>' . $responsables1[1] . '</span></p>
                                                                <p style="font-weight: normal;font-size: 13px; color: #777779"><span style="color: #000000; font-size: 13px; font-weight: bold;">Responsable(S):</span>&nbsp;<span style="font-weight: bold;color:#1261a9"><br>' . $responsables1[0] . '</span></p>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                                <p style="color: #000000; font-size: 11px"><strong>Datos Generales</strong></p>
                                                                <p style="font-weight: normal;font-size: 13px; color: #777779">Area:&nbsp;' . $rs["area"] . '</p>
                                                                <p style="font-weight: normal;font-size: 13px; color: #777779">Reporte:&nbsp;' . $rs["evento"] . '</p>
                                                                
                                                            </td>
                                                        </tr>
                                                        
                                                       

                                                        <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                                <p style="color: #000000;font-size: 11px"><strong>Fecha / Hora del evento</strong></p>
                                                                <p style="font-weight: normal;font-size: 13px; color: #777779">Fecha:&nbsp;' . $fecha . '</p>
                                                                <p style="font-weight: normal;font-size: 13px; color: #777779">De:&nbsp;' . $hora . ' a:&nbsp;' . $hora2 . '</p>
                                                            </td>
                                                        </tr>
                                                        
                                                         <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                                <p style="color: #000000;font-size: 13px"><strong>Informador</strong></p>
                                                                <p style="font-weight: normal;font-size: 13px; color: #777779">' . $crea . '</p>
                                                           
                                                            </td>
                                                        </tr>
                                                        
                                                          <tr>
                                                            <td width="100%">
                                                               &nbsp; <a href="' . $rs["url"] . ' " style="background-color:#051C48;border:1px solid #051C48;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:99%;-webkit-text-size-adjust:none;mso-hide:all;">Archivos Adjuntos &rarr;</a>
                                                            </td>
                                                          </tr>
                                                          
                                                          
                                                        <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                                <p style="font-weight: normal;font-size: 13px; color: #777779"><span style="color: #000000; font-size: 11px; font-weight: bold;">Cc.</span><span style="color:#1261a9;font-weight: bold;"><br>' .utf8_encode($copiados) . '</span></p>
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
                                                             <p style="color: black; font-size: 13px; text-align: justify; line-height: 20px; border-bottom:black solid 1px; ">
                                                              ' . utf8_encode($str_2). '
                                                        
                                                            </p>
                                                               <span style="color:#051C48"> ' . $texto_n . '</span>
                                                               
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

    for ($i = 0; $i < $cantidad2; $i++) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = 'starmedica-com.mail.protection.outlook.com';
        $mail->Port = 25;
        $mail->From = 'centralmonitoreo@starmedica.com';
        $mail->FromName = utf8_decode('Central de Monitoreo');
        $mail->Username = '';

        $mail->addAddress($porciones2[$i]);
        $mail->Subject = 'Nuevo comentario en reporte';
        $mail->MsgHTML(utf8_decode($body));
        $mail->AltBody = 'Mensaje de Portal de Reporte CCTV';
        $mail->send();
    }
}
