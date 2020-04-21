<?php

function enviar_correo($asunto, $mensaje, $email) {

    //echo $email;
    $path = $_SERVER['DOCUMENT_ROOT'];
    $path .= '/monitor-cctv/resources/class/PHPMailerAutoload.php';
    include_once($path);
    date_default_timezone_set('America/Mexico_City');


    $body2 = "<br><br>
    <a href='https://monitor.starmedica.com/monitor-cctv/tickets-preview.php'><span>Ingresar al Portal</span></a>
    <br><br>
    <table>
        <tbody>
            <tr>
                <td style='width:282px'>
                    <img src='../images/logo-mail.png' alt=''>
                </td>
                <td style='width:282px;vertical-align: middle;'>
                    <font face='Arial, Helvetica, sans-serif'>
                        <span style='font-size:10pt'>
                            <font color='#444444'>
                                <b>
                                    <div style='color:rgb(125,127,130);display:inline'>Sistema Central de Monitoreo&nbsp;</div>
                                </b>
                            </font>
                        </span>
                    </font>				
                </td>
            </tr>
	</tbody>
    </table>";
   // $cont = '0';

       // echo "entra contador";
        
        $mail = new PHPMailer();
        $mail->isSMTP();

        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = 'starmedica-com.mail.protection.outlook.com';
        $mail->Port = 25;
        $mail->From = 'centralmonitoreo@starmedica.com';
        $mail->FromName = utf8_decode('Central de Monitoreo');
        $mail->Username = '';

        $mail->addAddress($email, 'ServiceDesk');
        $mail->Subject = $asunto;
        $mail->MsgHTML(utf8_decode($mensaje . $body2));
        $mail->AltBody = 'Mensaje de Portal de Reporte CCTV';
      
       // $cont++;
  
}
