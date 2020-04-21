<?php

require '../connection/conexion.php';

$mysqli->query("SET NAMES 'utf8'");

$array = [430,
440,
446,
447,
449,
450,
451,
452,
453,
454,
455,
456,
457];

$cantidad = count($array);

for ($i = 0; $i < $cantidad; $i++) {
    $valor = $array[$i];
    $d_ticket = datos_t($valor);
    $id_ticket = $valor;

    $unidad = $d_ticket['hospital'];
    $area = $d_ticket['area'];
    $evento = $d_ticket['categoria'];
    $u_registra = $d_ticket['usuario_registra'];
    $u_resuelve = $d_ticket['usuario_actual'];




    $query2 = "SELECT 
        distinct
    usuario_categorias.id_categoria,
    usuario_categorias.id_usuario,
    usuario_hospital.hospital,
    ntf_kardex_usuario.id_tipo,
    usuario_grupos.id_grupo,
    usuarios.email,
    usuarios.nombre
FROM
    usuario_categorias
        LEFT JOIN
    usuario_hospital ON usuario_categorias.id_usuario = usuario_hospital.usuario
        LEFT JOIN
    ntf_kardex_usuario ON usuario_categorias.id_usuario = ntf_kardex_usuario.id_usuario
        LEFT JOIN
    usuario_grupos ON usuario_categorias.id_usuario = usuario_grupos.id_usuario
     LEFT JOIN
    usuarios ON usuario_categorias.id_usuario = usuarios.id_usuario and usuarios.desactivar = 0
WHERE
        id_categoria = '$evento'
        AND hospital = '$unidad'
        AND id_tipo in  ('5','10')
        AND id_grupo = '$area'";



    $r1 = $mysqli->query($query2);
    $e_copias = '';
    $e_copias2 = '';
    while ($copias = $r1->fetch_assoc()) {

        $e_copias .= $copias['email'] . ',';
        $e_copias2 .= $copias['nombre'] . '<br>';
    }


    $director = " SELECT 
    usuarios.nombre, usuarios.rol, usuarios.email, usuario_hospital.hospital
FROM
    usuarios
        LEFT JOIN
    usuario_hospital ON id_usuario = usuario 
    where rol = '3' and hospital ='$unidad' and desactivar = '0'";

    $r2 = $mysqli->query($director);
    $director_r = $r2->fetch_assoc();




    $registra = " SELECT 
    email
FROM
    usuarios
    where  id_usuario='$u_registra'";

    $r3 = $mysqli->query($registra);
    $registra_r = $r3->fetch_assoc();



    $atiende = "SELECT 
    email,nombre
FROM
    usuarios
    where  id_usuario='$u_resuelve'";

    $r4 = $mysqli->query($atiende);
    $atiende_r = $r4->fetch_assoc();



    $e_copias . $director_r['email'] . ',' . $registra_r['email'] . ',' . $atiende_r['email'];

    correo_evento($e_copias, $id_ticket, $director_r['nombre'], $atiende_r['nombre'], $e_copias2);
}
/* foreach ($d_ticket as $campo => $valor) {
  echo "El " . $campo . " es " . $valor;
  echo "<br>";
  } */

//correo_evento($id_ticket);
//    }




function datos_t($id) {
    require '../connection/conexion.php';
    $sql = "select * from tickets where id_ticket='$id';";

    $resultado2 = $mysqli->query($sql);

    while ($res2 = $resultado2->fetch_assoc()) {
        $hospital = $res2['unidad_negocio'];
        $area = $res2['categoria_2'];
        $categoria = $res2['categoria_4'];

        $usuario_registra = $res2['usuario_registra'];
        $usuario_actual = $res2['usuario_actual'];

        $datos = array('hospital' => $hospital, 'area' => $area, 'categoria' => $categoria, 'usuario_registra' => $usuario_registra, 'usuario_actual' => $usuario_actual);
    }
    return $datos;
}

function correo_evento($e_copias, $id_ticket, $responsables, $responsables1, $copias) {
    require '../connection/conexion.php';
    include_once  '../../resources/class/PHPMailerAutoload.php';

    $porciones2 = explode(",", $e_copias);
    $cantidad2 = count($porciones2);

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
                                                            <td style="height: 40px; background-color: ' . $color . '; color: white;font-size: 12px">
                                                                &nbsp;' . $texto . '
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td style="padding: 3px 0 0 0;">
                                                              
                                                                <p style="font-weight: normal;font-size: 11px; color: #777779"><span style="color: #000000; font-size: 11px; font-weight: bold;">' . $rs["hospital"] . '</span><span  style="font-weight: bold;color:#1261a9"><br>' . $responsables . '</span></p>
                                                                <p style="font-weight: normal;font-size: 11px; color: #777779"><span style="color: #000000; font-size: 11px; font-weight: bold;">Responsable(S):</span>&nbsp;<span style="font-weight: bold;color:#1261a9"><br>' . $responsables1 . '</span></p>
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
                                                                <p style="font-weight: normal;font-size: 11px; color: #777779"><span style="color: #000000; font-size: 11px; font-weight: bold;">Cc.</span><span style="color:#1261a9;font-weight: bold;"><br>' . $copias . '</span></p>
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
                                                             <p style="color: black; font-size: 13px; text-align: justify; line-height: 20px">
                                                              ' . $str_2 . '<br>
                                                           
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
        $mail->Subject = 'Reporte MONITOREO CCTV';
        $mail->MsgHTML($body);
        $mail->AltBody = 'Mensaje de Portal de Reporte CCTV';
        $mail->send();
    }
}
