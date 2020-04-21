<?php

require('plugins/fpdf/fpdf.php');
require('resources/connection/conexion.php');

class Reporte extends FPDF {

    function Header() {

        /* date_default_timezone_set('America/Mexico_City');
          $fecha = strftime("%d/%m/%Y %H:%M:%S", time());


          $this->Image('../img/logo-header.png', 170, 10, 40);
          $this->SetY(30);
          $this->SetX(10);
          $this->SetFont('Arial', 'B', 16);
          $this->Cell(0, 5, ' Sistema de Gestion de Tickets MetLife'); */
    }

    // Pie de página
    function Footer() {

        // $this->Image('../img/adn3.png', 0, 270, 220);
    }

    function atrasados($unidad) {
        require('resources/connection/conexion.php');

        $sql = $sql = "SELECT 
                                        id_ticket,
                                        catalogo_valor.descripcion,
                                        categoria.nombre  as categoria,
                                        categoria_2,
                                        estatus,
                                        usuarios.nombre,
                                        fecha_registro
                                    FROM
                                        tickets
                                            LEFT JOIN
                                        catalogo_valor ON tickets.estatus = catalogo_valor.id
                                            LEFT JOIN
                                        categoria ON tickets.categoria_2 = categoria.id
                                            LEFT JOIN
                                        usuarios ON tickets.usuario_resuelve = usuarios.id_usuario
                                    WHERE
                                        unidad_negocio = $unidad AND catalogo = '2' and estatus='1' order by fecha_registro asc;";
        // echo $sql;

        $resultados = $mysqli->query($sql);
        date_default_timezone_set('America/Mexico_City');


        $date2 = new DateTime(date('y-m-d H:i:s'));


        $contv_ag = 0;
        $contpv_ag = 0;
        $contt_ag = 0;



        $this->SetFillColor(194, 199, 222);

        $this->SetTextColor(0, 0, 0);

        $this->Ln();
        $this->SetDrawColor(0, 0, 0);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(8, 6, 'Folio', 'B,T', 0, 'C', true);
        $this->Cell(10, 6, 'Estatus', 'B,T', 0, 'C', true);
        $this->Cell(40, 6, 'Categoria', 'B,T', 0, 'C', true);
        $this->Cell(85, 6, 'Asignado a', 'B,T', 0, 'C', true);
        $this->Cell(30, 6, 'Creacion Ticket', 'B,T', 0, 'C', true);
        $this->Cell(20, 6, 'Horas', 'B,T', 0, 'C', true);
        $this->Ln();
        while ($fila = $resultados->fetch_assoc()) {


            $query = "SELECT tiempo FROM tiempos_resolucion where id_hospital='".$unidad."' and categoria='" . $fila['categoria_2'] . "'";
            $resultados_t = $mysqli->query($query);
            $mysqli->query("SET NAMES 'UTF8'");
            $fila_t = $resultados_t->fetch_assoc();




            $date1 = new DateTime(date('y-m-d H:i:s', strtotime($fila['fecha_registro'])));

            $diff = $date1->diff($date2);
            $dias = $diff->format('%d %h %i %s');
            $horas = $diff->format('%h');
            $minutos = $diff->format('%i');
            $segundos = $diff->format('%s');
            $dias_h = ($dias * 24) + $horas;

            // echo "<br>" . $dias_h . ":" . $minutos . ":" . $segundos;

            $resta = $fila_t['tiempo'] - $dias_h;



            $resultado = $fila_t['tiempo'] - $dias_h;

            if ($resultado < 0) {
                $resultado = $resultado * -1;
            }

            $resultado = $resultado . ":" . $minutos . ":" . $segundos;

            $this->SetDrawColor(235, 235, 224);

            if ($resta < 0) {

                $contv_ag++;

                $this->SetFont('Arial', '', 7);
                $this->SetFillColor(225, 225, 208);
                $this->SetTextColor(0, 0, 0);
                $this->Cell(8, 7, $fila["id_ticket"], 'B', 0, "c", true);
                $this->Cell(10, 7, $fila['descripcion'], 'B', 0, 'C');
                $this->Cell(40, 7, $fila['categoria'], 'B', 0, 'C');
                $this->Cell(85, 7, $fila['nombre'], 'B', 0, 'L');
                // $this->SetFillColor(255, 205, 210);
                $this->Cell(30, 7, $fila['fecha_registro'], 'B', 0, true);
                $this->Cell(20, 7, $resultado, 'B', 0, true);
                $this->Ln();
            }
        }
        $this->SetFillColor(255, 205, 210);
        $this->Cell(193, 7, 'TOTAL DE TICKETS ATRASADOS: ' . $contv_ag . '  ', 1, 1, 'R', TRUE);
        $this->Ln();
    }

    function vencer($unidad) {
        require('resources/connection/conexion.php');

        $sql = $sql = "SELECT 
                                        id_ticket,
                                        catalogo_valor.descripcion,
                                        categoria.nombre  as categoria,
                                        categoria_2,
                                        estatus,
                                        usuarios.nombre,
                                        fecha_registro
                                    FROM
                                        tickets
                                            LEFT JOIN
                                        catalogo_valor ON tickets.estatus = catalogo_valor.id
                                            LEFT JOIN
                                        categoria ON tickets.categoria_2 = categoria.id
                                            LEFT JOIN
                                        usuarios ON tickets.usuario_resuelve = usuarios.id_usuario
                                    WHERE
                                        unidad_negocio = $unidad AND catalogo = '2' and estatus='1' order by fecha_registro asc;";
        // echo $sql;

        $resultados = $mysqli->query($sql);
        date_default_timezone_set('America/Mexico_City');


        $date2 = new DateTime(date('y-m-d H:i:s'));
        $contv_ag = 0;
        $contpv_ag = 0;
        $contt_ag = 0;
        while ($fila = $resultados->fetch_assoc()) {
            $mysqli->query("SET NAMES 'UTF8'");

            $query = "SELECT tiempo FROM tiempos_resolucion where id_hospital='".$unidad."' and categoria='" . $fila['categoria_2'] . "'";
            $resultados_t = $mysqli->query($query);
            $fila_t = $resultados_t->fetch_assoc();




            $date1 = new DateTime(date('y-m-d H:i:s', strtotime($fila['fecha_registro'])));

            $diff = $date1->diff($date2);
            $dias = $diff->format('%d %h %i %s');
            $horas = $diff->format('%h');
            $minutos = $diff->format('%i');
            $segundos = $diff->format('%s');
            $dias_h = ($dias * 24) + $horas;

            // echo "<br>" . $dias_h . ":" . $minutos . ":" . $segundos;

            $resta = $fila_t['tiempo'] - $dias_h;
            $resultado = $fila_t['tiempo'] - $dias_h;

            if ($resultado < 0) {
                $resultado = $resultado * -1;
            }

            $resultado = $resultado . ":" . $minutos . ":" . $segundos;



            if ($resta >= 0 && $resta <= 3) {

                $contpv_ag++;

                $this->Cell(8, 7, $fila["id_ticket"], 'B', 0, "c");
                $this->Cell(10, 7, $fila['descripcion'], 'B', 0, 'C');
                $this->Cell(40, 7, $fila['categoria'], 'B', 0, 'C');
                $this->Cell(85, 7, $fila['nombre'], 'B', 0, 'L');
                // $this->SetFillColor(255, 249, 196);
                $this->Cell(30, 7, $fila['fecha_registro'], 'B', 0, true);
                $this->Cell(20, 7, $resultado, 'B', 1, true);
            }
        }

        $this->SetFillColor(255, 249, 196);
        $this->Cell(193, 7, 'TOTAL DE TICKETS POR VENCER: ' . $contpv_ag . '  ', 1, 1, 'R', TRUE);
        $this->Ln();
    }

    function atiempo($unidad) {
        require('resources/connection/conexion.php');

        $sql = $sql = "SELECT 
                                        id_ticket,
                                        catalogo_valor.descripcion,
                                        categoria.nombre  as categoria,
                                        categoria_2,
                                        estatus,
                                        usuarios.nombre,
                                        fecha_registro
                                    FROM
                                        tickets
                                            LEFT JOIN
                                        catalogo_valor ON tickets.estatus = catalogo_valor.id
                                            LEFT JOIN
                                        categoria ON tickets.categoria_2 = categoria.id
                                            LEFT JOIN
                                        usuarios ON tickets.usuario_resuelve = usuarios.id_usuario
                                    WHERE
                                        unidad_negocio = $unidad AND catalogo = '2' and estatus='1' order by fecha_registro asc;";
        // echo $sql;

        $resultados = $mysqli->query($sql);
        $mysqli->query("SET NAMES 'UTF8'");
        date_default_timezone_set('America/Mexico_City');


        $date2 = new DateTime(date('y-m-d H:i:s'));
        $contv_ag = 0;
        $contpv_ag = 0;
        $contt_ag = 0;
        while ($fila = $resultados->fetch_assoc()) {


            $query = "SELECT tiempo FROM tiempos_resolucion where id_hospital='".$unidad."' and categoria='" . $fila['categoria_2'] . "'";

            $resultados_t = $mysqli->query($query);
            $mysqli->query("SET NAMES 'UTF8'");
            $fila_t = $resultados_t->fetch_assoc();




            $date1 = new DateTime(date('y-m-d H:i:s', strtotime($fila['fecha_registro'])));

            $diff = $date1->diff($date2);
            $dias = $diff->format('%d %h %i %s');
            $horas = $diff->format('%h');
            $minutos = $diff->format('%i');
            $segundos = $diff->format('%s');
            $dias_h = ($dias * 24) + $horas;

            // echo "<br>" . $dias_h . ":" . $minutos . ":" . $segundos;

            $resta = $fila_t['tiempo'] - $dias_h;
            $resultado = $fila_t['tiempo'] - $dias_h . ":" . $minutos . ":" . $segundos;


            if ($resta > 3) {

                $contt_ag++;
                $this->Cell(8, 7, $fila["id_ticket"], 'B', 0, "c");
                $this->Cell(10, 7, $fila['descripcion'], 'B', 0, 'C');
                $this->Cell(40, 7, $fila['categoria'], 'B', 0, 'C');
                $this->Cell(85, 7, $fila['nombre'], 'B', 0, 'L');
                // $this->SetFillColor(220, 237, 200);
                $this->Cell(30, 7, $fila['fecha_registro'], 'B', 0, true);
                $this->Cell(20, 7, $resultado, 'B', 1, true);
            }
        }
        $this->SetFillColor(220, 237, 200);
        $this->Cell(193, 7, 'TOTAL DE TICKETS EN TIEMPO:  ' . $contt_ag . '  ', 1, 1, 'R', TRUE);
        $this->Ln();
    }

}

$mysqli->query("SET NAMES 'utf8'");
$mysqli->query("SET lc_time_names = 'es_MX'");


/* * ****************ACTIVOS********************* */

$query_ag = "select count(id_ticket) as total_activos_ag
from tickets 
where estatus ='1' and unidad_negocio='1102'";
$resultado_ag = $mysqli->query($query_ag);
$fila_ag = $resultado_ag->fetch_assoc();

$query_ag_r = "select count(id_ticket) as total_activos_ag
from tickets 
where estatus ='2' and unidad_negocio='1102'";
$resultado_ag_r = $mysqli->query($query_ag_r);
$fila_ag_r = $resultado_ag_r->fetch_assoc();

$query_ag_c = "select count(id_ticket) as total_activos_ag
from tickets 
where estatus ='4' and unidad_negocio='1102'";
$resultado_ag_c = $mysqli->query($query_ag_c);
$fila_ag_c = $resultado_ag_c->fetch_assoc();

$query_ag_ce = "select count(id_ticket) as total_activos_ag
from tickets 
where estatus ='3' and unidad_negocio='1102'";
$resultado_ag_ce = $mysqli->query($query_ag_ce);
$fila_ag_ce = $resultado_ag_ce->fetch_assoc();




/* * ****************Resueltos********************* */


$query_me_r = "select count(id_ticket) as total_activos_me
from tickets 
where estatus ='2' and unidad_negocio='1103'";
$resultado_me_r = $mysqli->query($query_me_r);
$fila_me_r = $resultado_me_r->fetch_assoc();

$query_me = "select count(id_ticket) as total_activos_me
from tickets 
where estatus ='1' and unidad_negocio='1103'";
$resultado_me = $mysqli->query($query_me);
$fila_me = $resultado_me->fetch_assoc();

$query_me_c = "select count(id_ticket) as total_activos_me
from tickets 
where estatus ='4' and unidad_negocio='1103'";
$resultado_me_c = $mysqli->query($query_me_c);
$fila_me_c = $resultado_me_c->fetch_assoc();

$query_me_ce = "select count(id_ticket) as total_activos_me
from tickets 
where estatus ='3' and unidad_negocio='1103'";
$resultado_me_ce = $mysqli->query($query_me_ce);
$fila_me_ce = $resultado_me_ce->fetch_assoc();



/* * ****************cancelados********************* */
$query_ju = "select count(id_ticket) as total_activos_ju
from tickets 
where estatus ='1' and unidad_negocio='1104'";
$resultado_ju = $mysqli->query($query_ju);
$fila_ju = $resultado_ju->fetch_assoc();


$query_ju_r = "select count(id_ticket) as total_activos_ju
from tickets 
where estatus ='2' and unidad_negocio='1104'";
$resultado_ju_r = $mysqli->query($query_ju_r);
$fila_ju_r = $resultado_ju_r->fetch_assoc();



$query_ju_c = "select count(id_ticket) as total_activos_ju
from tickets 
where estatus ='4' and unidad_negocio='1104'";
$resultado_ju_c = $mysqli->query($query_ju_c);
$fila_ju_c = $resultado_ju_c->fetch_assoc();


$query_ju_ce = "select count(id_ticket) as total_activos_ju
from tickets 
where estatus ='3' and unidad_negocio='1104'";
$resultado_ju_ce = $mysqli->query($query_ju_ce);
$fila_ju_ce = $resultado_ju_ce->fetch_assoc();



/* * ****************cerrados********************* */











/////////////////////////////////////////////////////////////////////

$pdf = new Reporte('P', 'mm', 'Letter');


$pdf->AddPage();



$pdf->setY(10);
$pdf->setX(10);
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(194, 5, 'Sistema de Gestion de Tickets MetLife Aguascalientes', 0, 0, 'C');

date_default_timezone_set('America/Mexico_City');
$fecha = strftime("%d/%m/%Y %H:%M:%S", time());

$pdf->setY(20);
$pdf->setX(10);
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(194, 5, $fecha, 0, 0, 'C');


$pdf->Ln();
$pdf->SetFillColor(100, 181, 246);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(255, 255, 255);

$pdf->Ln();

$pdf->Cell(39, 6, 'Hospital', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Activos', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Resueltos', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Cancelados', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Cerrados', 0, 0, 'C', true);
$pdf->Ln();

$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(51, 51, 0);

$pdf->SetDrawColor(235, 235, 224);
$pdf->Cell(39, 7, 'AGUASCALIENTES', 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_ag['total_activos_ag'], 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_ag_r['total_activos_ag'], 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_ag_c['total_activos_ag'], 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_ag_ce['total_activos_ag'], 'B', 0, 'C');

$pdf->Ln();

/* $pdf->Cell(39, 7, 'MERIDA', 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_me['total_activos_me'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_me_r['total_activos_me'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_me_c['total_activos_me'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_me_ce['total_activos_me'], 'B', 0, 'C');
  $pdf->Ln();

  $pdf->Cell(39, 7, 'JUAREZ', 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ju['total_activos_ju'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ju_r['total_activos_ju'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ju_c['total_activos_ju'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ju_ce['total_activos_ju'], 'B', 0, 'C');
  $pdf->Ln(); */

$pdf->SetFillColor(225, 225, 208);
$pdf->Cell(39, 7, 'TOTAL', 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_act = $fila_ag['total_activos_ag'] /* + $fila_me['total_activos_me'] + $fila_ju['total_activos_ju'] */, 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_res = $fila_ag_r['total_activos_ag'] /* + $fila_me_r['total_activos_me'] + $fila_ju_r['total_activos_ju'] */, 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_can = $fila_ag_c['total_activos_ag'] /* + $fila_me_c['total_activos_me'] + $fila_ju_c['total_activos_ju'] */, 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_cer = $fila_ag_ce['total_activos_ag'] /* + $fila_me_ce['total_activos_me'] + $fila_ju_ce['total_activos_ju'] */, 'B', 0, 'C', true);
$pdf->Ln();
$pdf->Ln();


$pdf->SetFont('Arial', 'B', 13);
$pdf->MultiCell(197, 5, utf8_decode("AGUASCALIENTES "), 0, 1, '');

$pdf->atrasados('1102');
$pdf->vencer('1102');
$pdf->atiempo('1102');

/*
  $pdf->SetFont('Arial', 'B', 13);
  $pdf->MultiCell(197, 5, utf8_decode("MERIDA "), 0, 1, '');

  $pdf->atrasados('1103');
  $pdf->vencer('1103');
  $pdf->atiempo('1103');

  $pdf->SetFont('Arial', 'B', 13);
  $pdf->MultiCell(197, 5, utf8_decode("Juarez "), 0, 1, '');

  $pdf->atrasados('1104');
  $pdf->vencer('1104');
  $pdf->atiempo('1104');
 */

$pdf->Output("t_metlife_aguascalientes.pdf", "F");

$nombre_fichero_aguascalientes = "t_metlife_aguascalientes.pdf";

if (file_exists($nombre_fichero_aguascalientes)) {

    require 'resources/class/PHPMailerAutoload.php';


    $sql = "SELECT  
            usuarios.nombre,
            usuarios.email,
          
            usuario_hospital.hospital,
           
            ntf_kardex_usuario.id_tipo
        FROM
            usuarios
                INNER JOIN
            usuario_hospital ON usuarios.id_usuario = usuario_hospital.usuario
                INNER JOIN
            ntf_kardex_usuario ON usuarios.id_usuario = ntf_kardex_usuario.id_usuario
        WHERE
            id_tipo = '1' AND hospital = '1102'";
    $resultados = $mysqli->query($sql);

    $mail = new PHPMailer();
    $mail->isSMTP();

    $body = "<br><br>
        
<p>Esta es una entrega automática del Reporte adjunto de Incidencias o Tickets enviados por Metlife AGUSCALIENTES. No es necesario que conteste este correo.
 
Si desea entrar al portal de Tickets de Metlife por favor haga clic en la siguiente URL</p>
      
      
    <a href='http://gsm-help-desk.starmedica.com/service-ish/index.php'><span>Ingresar al Portal</span></a>
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
                                    <div style='color:rgb(125,127,130);display:inline'>Sistema de Gestión de Eventos&nbsp;</div>
                                </b>
                            </font>
                        </span>
                    </font>				
                </td>
            </tr>
	</tbody>
    </table>";

    while ($fila = $resultados->fetch_assoc()) {
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp-relay.gmail.com';
        $mail->Port = 587;
        $mail->From = 'service_desk@starmedica.com';
        $mail->FromName = utf8_decode('ServiceDesk');
        $mail->Username = '';

        $mail->addAddress($fila['email'], 'ServiceDesk');
        $mail->Subject = 'Reporte Gestion de Tickets MetLife';
        $mail->MsgHTML(utf8_decode($body));
        $mail->AltBody = 'Mensaje de Portal de ServiceDesk';
        $archivo = 'prueba.pdf';
        $mail->AddAttachment($nombre_fichero_aguascalientes, 'metlife_aguascalientes.pdf');
    }
    $mail->send();
}

unlink("t_metlife_aguascalientes.pdf");



/////////////////////////////////////////////////////////////////////

$pdf = new Reporte('P', 'mm', 'Letter');


$pdf->AddPage();



$pdf->setY(10);
$pdf->setX(10);
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(194, 5, 'Sistema de Gestion de Tickets MetLife Merida', 0, 0, 'C');

date_default_timezone_set('America/Mexico_City');
$fecha = strftime("%d/%m/%Y %H:%M:%S", time());

$pdf->setY(20);
$pdf->setX(10);
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(194, 5, $fecha, 0, 0, 'C');


$pdf->Ln();
$pdf->SetFillColor(100, 181, 246);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(255, 255, 255);

$pdf->Ln();

$pdf->Cell(39, 6, 'Hospital', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Activos', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Resueltos', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Cancelados', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Cerrados', 0, 0, 'C', true);
$pdf->Ln();

$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(51, 51, 0);

/* $pdf->SetDrawColor(235, 235, 224);
  $pdf->Cell(39, 7, 'AGUASCALIENTES', 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ag['total_activos_ag'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ag_r['total_activos_ag'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ag_c['total_activos_ag'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ag_ce['total_activos_ag'], 'B', 0, 'C');

  $pdf->Ln(); */


$pdf->Cell(39, 7, 'MERIDA', 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_me['total_activos_me'], 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_me_r['total_activos_me'], 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_me_c['total_activos_me'], 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_me_ce['total_activos_me'], 'B', 0, 'C');
$pdf->Ln();

/*
  $pdf->Cell(39, 7, 'JUAREZ', 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ju['total_activos_ju'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ju_r['total_activos_ju'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ju_c['total_activos_ju'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ju_ce['total_activos_ju'], 'B', 0, 'C');
  $pdf->Ln();
 */

$pdf->SetFillColor(225, 225, 208);
$pdf->Cell(39, 7, 'TOTAL', 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_act = /* $fila_ag['total_activos_ag'] + */ $fila_me['total_activos_me'] /* + $fila_ju['total_activos_ju'] */, 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_res = /* $fila_ag_r['total_activos_ag'] + */ $fila_me_r['total_activos_me'] /* + $fila_ju_r['total_activos_ju'] */, 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_can = /* $fila_ag_c['total_activos_ag'] + */ $fila_me_c['total_activos_me'] /* + $fila_ju_c['total_activos_ju'] */, 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_cer = /* $fila_ag_ce['total_activos_ag'] + */ $fila_me_ce['total_activos_me'] /* + $fila_ju_ce['total_activos_ju'] */, 'B', 0, 'C', true);
$pdf->Ln();
$pdf->Ln();

/*
  $pdf->SetFont('Arial', 'B', 13);
  $pdf->MultiCell(197, 5, utf8_decode("AGUASCALIENTES "), 0, 1, '');

  $pdf->atrasados('1102');
  $pdf->vencer('1102');
  $pdf->atiempo('1102');
 */

$pdf->SetFont('Arial', 'B', 13);
$pdf->MultiCell(197, 5, utf8_decode("MERIDA "), 0, 1, '');

$pdf->atrasados('1103');
$pdf->vencer('1103');
$pdf->atiempo('1103');

/*
  $pdf->SetFont('Arial', 'B', 13);
  $pdf->MultiCell(197, 5, utf8_decode("Juarez "), 0, 1, '');

  $pdf->atrasados('1104');
  $pdf->vencer('1104');
  $pdf->atiempo('1104');
 */

$pdf->Output("t_metlife_merida.pdf", "F");

$nombre_fichero_merida = "t_metlife_merida.pdf";

if (file_exists($nombre_fichero_merida)) {

    // require 'resources/class/PHPMailerAutoload.php';


    $sql = "SELECT  
            usuarios.nombre,
            usuarios.email,
          
            usuario_hospital.hospital,
           
            ntf_kardex_usuario.id_tipo
        FROM
            usuarios
                INNER JOIN
            usuario_hospital ON usuarios.id_usuario = usuario_hospital.usuario
                INNER JOIN
            ntf_kardex_usuario ON usuarios.id_usuario = ntf_kardex_usuario.id_usuario
        WHERE
            id_tipo = '1' AND hospital = '1103'";
    $resultados = $mysqli->query($sql);

    $mail = new PHPMailer();
    $mail->isSMTP();

    $body = "<br><br>
      <p>Esta es una entrega automática del Reporte adjunto de Incidencias o Tickets enviados por Metlife MERIDA. No es necesario que conteste este correo.
 
Si desea entrar al portal de Tickets de Metlife por favor haga clic en la siguiente URL</p>
      
    <a href='http://gsm-help-desk.starmedica.com/service-ish/index.php'><span>Ingresar al Portal</span></a>
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
                                    <div style='color:rgb(125,127,130);display:inline'>Sistema de Gestión de Tickets&nbsp;</div>
                                </b>
                            </font>
                        </span>
                    </font>				
                </td>
            </tr>
	</tbody>
    </table>";

    while ($fila = $resultados->fetch_assoc()) {
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp-relay.gmail.com';
        $mail->Port = 587;
        $mail->From = 'service_desk@starmedica.com';
        $mail->FromName = utf8_decode('ServiceDesk');
        $mail->Username = '';

        $mail->addAddress($fila['email'], 'ServiceDesk');
        $mail->Subject = 'Reporte Gestion de Tickets MetLife';
        $mail->MsgHTML(utf8_decode($body));
        $mail->AltBody = 'Mensaje de Portal de ServiceDesk';
        $archivo = 'prueba.pdf';
        $mail->AddAttachment($nombre_fichero_merida, 'metlife_merida.pdf');
    }
    $mail->send();
}

unlink("t_metlife_merida.pdf");


/////////////////////////////////////////////////////////////////////

$pdf = new Reporte('P', 'mm', 'Letter');


$pdf->AddPage();



$pdf->setY(10);
$pdf->setX(10);
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(194, 5, 'Sistema de Gestion de Tickets MetLife Juarez', 0, 0, 'C');

date_default_timezone_set('America/Mexico_City');
$fecha = strftime("%d/%m/%Y %H:%M:%S", time());

$pdf->setY(20);
$pdf->setX(10);
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(194, 5, $fecha, 0, 0, 'C');


$pdf->Ln();
$pdf->SetFillColor(100, 181, 246);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(255, 255, 255);

$pdf->Ln();

$pdf->Cell(39, 6, 'Hospital', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Activos', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Resueltos', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Cancelados', 0, 0, 'C', true);
$pdf->Cell(39, 6, 'Cerrados', 0, 0, 'C', true);
$pdf->Ln();

$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(51, 51, 0);
/*
  $pdf->SetDrawColor(235, 235, 224);
  $pdf->Cell(39, 7, 'AGUASCALIENTES', 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ag['total_activos_ag'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ag_r['total_activos_ag'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ag_c['total_activos_ag'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_ag_ce['total_activos_ag'], 'B', 0, 'C');

  $pdf->Ln();

  $pdf->Cell(39, 7, 'MERIDA', 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_me['total_activos_me'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_me_r['total_activos_me'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_me_c['total_activos_me'], 'B', 0, 'C');
  $pdf->Cell(39, 7, $fila_me_ce['total_activos_me'], 'B', 0, 'C');
  $pdf->Ln();
 */
$pdf->Cell(39, 7, 'JUAREZ', 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_ju['total_activos_ju'], 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_ju_r['total_activos_ju'], 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_ju_c['total_activos_ju'], 'B', 0, 'C');
$pdf->Cell(39, 7, $fila_ju_ce['total_activos_ju'], 'B', 0, 'C');
$pdf->Ln();

$pdf->SetFillColor(225, 225, 208);
$pdf->Cell(39, 7, 'TOTAL', 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_act = /* $fila_ag['total_activos_ag'] + $fila_me['total_activos_me'] + */ $fila_ju['total_activos_ju'], 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_res = /* $fila_ag_r['total_activos_ag'] + $fila_me_r['total_activos_me'] + */ $fila_ju_r['total_activos_ju'], 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_can = /* $fila_ag_c['total_activos_ag'] + $fila_me_c['total_activos_me'] + */ $fila_ju_c['total_activos_ju'], 'B', 0, 'C', true);
$pdf->Cell(39, 7, $totales_cer = /* $fila_ag_ce['total_activos_ag'] + $fila_me_ce['total_activos_me'] + */ $fila_ju_ce['total_activos_ju'], 'B', 0, 'C', true);
$pdf->Ln();
$pdf->Ln();

/*
  $pdf->SetFont('Arial', 'B', 13);
  $pdf->MultiCell(197, 5, utf8_decode("AGUASCALIENTES "), 0, 1, '');

  $pdf->atrasados('1102');
  $pdf->vencer('1102');
  $pdf->atiempo('1102');


  $pdf->SetFont('Arial', 'B', 13);
  $pdf->MultiCell(197, 5, utf8_decode("MERIDA "), 0, 1, '');

  $pdf->atrasados('1103');
  $pdf->vencer('1103');
  $pdf->atiempo('1103');

 */

$pdf->SetFont('Arial', 'B', 13);
$pdf->MultiCell(197, 5, utf8_decode("Juarez "), 0, 1, '');

$pdf->atrasados('1104');
$pdf->vencer('1104');
$pdf->atiempo('1104');


$pdf->Output("t_metlife_juarez.pdf", "F");

$nombre_fichero_juarez = "t_metlife_juarez.pdf";

if (file_exists($nombre_fichero_juarez)) {




    $sql = "SELECT  
            usuarios.nombre,
            usuarios.email,
          
            usuario_hospital.hospital,
           
            ntf_kardex_usuario.id_tipo
        FROM
            usuarios
                INNER JOIN
            usuario_hospital ON usuarios.id_usuario = usuario_hospital.usuario
                INNER JOIN
            ntf_kardex_usuario ON usuarios.id_usuario = ntf_kardex_usuario.id_usuario
        WHERE
            id_tipo = '1' AND hospital = '1104'";
    $resultados = $mysqli->query($sql);

    $mail = new PHPMailer();
    $mail->isSMTP();

    $body = "<br><br>
      <p>Esta es una entrega automática del Reporte adjunto de Incidencias o Tickets enviados por Metlife JUAREZ. No es necesario que conteste este correo.
 
Si desea entrar al portal de Tickets de Metlife por favor haga clic en la siguiente URL</p>
      
    <a href='http://gsm-help-desk.starmedica.com/service-ish/index.php'><span>Ingresar al Portal</span></a>
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
                                    <div style='color:rgb(125,127,130);display:inline'>Sistema de Gestión de Tickets&nbsp;</div>
                                </b>
                            </font>
                        </span>
                    </font>				
                </td>
            </tr>
	</tbody>
    </table>";

    while ($fila = $resultados->fetch_assoc()) {
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = 'smtp-relay.gmail.com';
        $mail->Port = 587;
        $mail->From = 'service_desk@starmedica.com';
        $mail->FromName = utf8_decode('ServiceDesk');
        $mail->Username = '';

        $mail->addAddress($fila['email'], 'ServiceDesk');
        $mail->Subject = 'Reporte Gestion de Tickets MetLife';
        $mail->MsgHTML(utf8_decode($body));
        $mail->AltBody = 'Mensaje de Portal de ServiceDesk';
        $archivo = 'prueba.pdf';
        $mail->AddAttachment($nombre_fichero_juarez, 'metlife_juarez.pdf');
    }
    $mail->send();
}

unlink("t_metlife_juarez.pdf");
?>

