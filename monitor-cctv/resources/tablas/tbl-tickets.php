<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$resultados_por_pagina = 10;
$comenzar_en = 0;

$condiciones = "";
$filtros = "";
$filtroEstatus = " and t.estatus!=3 ";
$orden = " order by t.ultima_actualizacion ";
$orden_lista = " desc ";

$_SESSION['filtro_folio'] = "";
$_SESSION['filtro_estatus'] = "";
$_SESSION['filtro_hospital'] = "";
$_SESSION['filtro_usuario'] = "";
$_SESSION['filtro_categoria'] = "";
$_SESSION['filtro_areas'] = "";
$_SESSION['orden'] = "";
$_SESSION['orden_lista'] = "";

setcookie("filtro_folio", '', time() + 700000, '/', 'sites.starmedica.com');
setcookie("filtro_estatus", '', time() + 700000, '/', 'sites.starmedica.com');
setcookie("filtro_hospital", '', time() + 700000, '/', 'sites.starmedica.com');
setcookie("filtro_usuario", '', time() + 700000, '/', 'sites.starmedica.com');
setcookie("filtro_categoria", '', time() + 700000, '/', 'sites.starmedica.com');
setcookie("filtro_areas", '', time() + 700000, '/', 'sites.starmedica.com');
setcookie("nv", '', time() + 700000, '/', 'sites.starmedica.com');
setcookie("orden", '', time() + 700000, '/', 'sites.starmedica.com');
setcookie("orden_lista", '', time() + 700000, '/', 'sites.starmedica.com');
setcookie("res_pag_tickets", '', time() + 700000, '/', 'sites.starmedica.com');



if (isset($_POST['resultados_por_pagina']) && $_POST['resultados_por_pagina'] != "") {
    $resultados_por_pagina = $_POST['resultados_por_pagina'];
    $_POST['res_pag_tickets'] = $_POST['resultados_por_pagina'];
    setcookie("res_pag_tickets", $_POST['resultados_por_pagina'], time() + 700000, '/', 'sites.starmedica.com');
}
if (isset($_POST['comenzar_en']) && $_POST['comenzar_en'] != "") {
    $comenzar_en = $_POST['comenzar_en'];
}

if (isset($_POST['filtro_folio_t']) && $_POST['filtro_folio_t'] != "") {
    $filtros .= " and t.id_ticket = '" . $_POST['filtro_folio_t'] . "' ";
    $_SESSION['filtro_folio'] = $_POST['filtro_folio_t'];
    setcookie("filtro_folio", $_POST['filtro_folio_t'], time() + 700000, '/', 'sites.starmedica.com');
}

if (isset($_POST['filtro_estatus_t']) && $_POST['filtro_estatus_t'] != "") {
    $filtros .= " and t.estatus='" . $_POST['filtro_estatus_t'] . "' ";
    $filtroEstatus = "";
    $_SESSION['filtro_estatus'] = $_POST['filtro_estatus_t'];
    setcookie("filtro_estatus", $_POST['filtro_estatus_t'], time() + 700000, '/', 'sites.starmedica.com');
}

if (isset($_POST['filtro_hospital_t']) && $_POST['filtro_hospital_t'] != "") {
    $filtros .= " and t.unidad_negocio='" . $_POST['filtro_hospital_t'] . "' ";
    $_SESSION['filtro_hospital'] = $_POST['filtro_hospital_t'];
    setcookie("filtro_hospital", $_POST['filtro_hospital_t'], time() + 700000, '/', 'sites.starmedica.com');
}

if (isset($_POST['filtro_areas_t']) && $_POST['filtro_areas_t'] != "") {

    $_SESSION['filtro_areas_t'] = $_POST['filtro_areas_t'];
    setcookie("filtro_areas", $_POST['filtro_areas_t'], time() + 700000, '/', 'sites.starmedica.com');
} else {
    
}

if (isset($_POST['filtro_usuario_t']) && $_POST['filtro_usuario_t'] != "") {
    $filtros .= " and t.usuario_registra='" . $_POST['filtro_usuario_t'] . "' ";
    $_SESSION['filtro_usuario'] = $_POST['filtro_usuario_t'];
    setcookie("filtro_usuario", $_POST['filtro_usuario_t'], time() + 700000, '/', 'sites.starmedica.com');
}

if (isset($_POST['filtro_categoria_t']) && $_POST['filtro_categoria_t'] != "") {
    $filtros .= " and t.categoria_4='" . $_POST['filtro_categoria_t'] . "' ";
    $_SESSION['filtro_categoria'] = $_POST['filtro_categoria_t'];
    setcookie("filtro_categoria", $_POST['filtro_categoria_t'], time() + 700000, '/', 'sites.starmedica.com');
}

if (isset($_POST['nv']) && $_POST['nv'] != "undefined" && $_POST['nv'] != "") {
    $_SESSION['nv'] = $_POST['nv'];
    setcookie("nv", $_POST['nv'], time() + 700000, '/', 'sites.starmedica.com');
}

if (isset($_POST['orden']) && $_POST['orden'] != "") {
    $orden = " order by " . $_POST['orden'] . " ";
    $_SESSION['orden'] = $_POST['orden'];
    setcookie("orden", $_POST['orden'], time() + 700000, '/', 'sites.starmedica.com');
}

if (isset($_POST['orden_lista']) && $_POST['orden_lista'] != "") {
    $orden_lista = " " . $_POST['orden_lista'] . " ";
    $_SESSION['orden_lista'] = $_POST['orden_lista'];
    setcookie("orden_lista", $_POST['orden_lista'], time() + 700000, '/', 'sites.starmedica.com');
}


/* $array_grupos = array();

  $gruposSQL = "select id_usuario, id_grupo from usuario_grupos where id_usuario='" . $_SESSION['id_usuario'] . "';";
  $resultadoGrupos = $mysqli->query($gruposSQL);

  while ($filaGrupo = $resultadoGrupos->fetch_assoc()) {
  array_push($array_grupos, $filaGrupo['id_grupo']);
  } */

if (isset($_SESSION['nv']) && $_SESSION['nv'] != "") {
    switch ($_SESSION['nv']) {
        case 1://sin asignar
            $condiciones .= " and t.categoria_4 in('" . join("','", $_SESSION['usr_categorias']) . "') and t.usuario_actual is null ";
            break;
        case 2://reportados por mi
            $condiciones .= " and t.usuario_registra='" . $_SESSION['id_usuario'] . "' ";
            break;
        case 3://asignadas a mi
            $condiciones .= " and t.usuario_actual='" . $_SESSION['id_usuario'] . "' ";
            break;
        case 4://asignadas a mi area

            /* if (isset($_SESSION['funciones']) && in_array("ver_asignadas_a_mi_area_usuario_null", $_SESSION['funciones'])) {
              $condiciones.=" and (exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_actual and ug.id_grupo in('" . join("','", $array_grupos) . "'))) ";
              } else {
              $condiciones.=" and exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_actual and ug.id_grupo in('" . join("','", $array_grupos) . "')) ";
              } */
            //$condiciones .= " and t.categoria_2 in('" . join("','", $_SESSION['usr_categorias']) . "') ";
            $condiciones .= " and exists(select uc.id_categoria from usuario_categorias uc where uc.id_usuario = t.usuario_actual and t.categoria_2 in ('" . join("','", $_SESSION['usr_categorias']) . "') and areas.idareas in('" . join("','", $_SESSION['usr_grupos']) . "')) ";

            break;
        case 5://resueltas por mi
            $condiciones .= " and t.usuario_resuelve='" . $_SESSION['id_usuario'] . "' and t.estatus=2 ";
            break;
        case 6://reportados por mi area
            //$condiciones .= " and t.categoria_2 in('" . join("','", $_SESSION['usr_categorias']) . "') ";
            $condiciones .= " and exists(select uc.id_categoria from usuario_categorias uc where uc.id_usuario = t.usuario_registra and t.categoria_4 in ('" . join("','", $_SESSION['usr_categorias']) . "') and areas.idareas in('" . join("','", $_SESSION['usr_grupos']) . "')) ";
            break;
        case 7://ver todos
            $array_funciones = $_SESSION['funciones'];
            $condiciones .= " and (";
            $cont7 = 0;
            if (in_array("ver_tickets_sin_asignar", $array_funciones)) {
                if ($cont7 > 0) {
                    $condiciones .= " or ";
                }
                $condiciones .= " (t.categoria_4 in('" . join("','", $_SESSION['usr_categorias']) . "') and t.usuario_actual is null) ";
                $cont7++;
            }
            if (in_array("ver_tickets_reportados_por_mi", $array_funciones)) {
                if ($cont7 > 0) {
                    $condiciones .= " or ";
                }
                $condiciones .= " t.usuario_registra='" . $_SESSION['id_usuario'] . "' ";
                $cont7++;
            }
            if (in_array("ver_tickets_asignado_a_mi", $array_funciones)) {
                if ($cont7 > 0) {
                    $condiciones .= " or ";
                }
                $condiciones .= " t.usuario_actual='" . $_SESSION['id_usuario'] . "' ";
                $cont7++;
            }
            if (in_array("ver_tickets_asignados_a_mi_area", $array_funciones)) {
                if ($cont7 > 0) {
                    $condiciones .= " or ";
                }
                //$condiciones .= " t.categoria_2 in('" . join("','", $_SESSION['usr_categorias']) . "') ";
                $condiciones .= " exists(select uc.id_categoria from usuario_categorias uc where uc.id_usuario = t.usuario_actual and t.categoria_4 in ('" . join("','", $_SESSION['usr_categorias']) . "') and areas.idareas in('" . join("','", $_SESSION['usr_grupos']) . "')) ";
                $cont7++;
            }
            if (in_array("ver_tickets_resueltos_por_mi", $array_funciones)) {
                if ($cont7 > 0) {
                    $condiciones .= " or ";
                }
                $condiciones .= " (t.usuario_resuelve='" . $_SESSION['id_usuario'] . "' and t.estatus=2) ";
                $cont7++;
            }
            if (in_array("ver_tickets_reportados_por_mi_area", $array_funciones)) {
                if ($cont7 > 0) {
                    $condiciones .= " or ";
                }
                //$condiciones .= " exists(select ug.id_grupo from usuario_grupos ug where ug.id_usuario=t.usuario_registra and ug.id_grupo in('" . join("','", $_SESSION['usr_grupos']) . "')) ";
                $condiciones .= " exists(select uc.id_categoria from usuario_categorias uc where uc.id_usuario = t.usuario_registra and t.categoria_2 in ('" . join("','", $_SESSION['usr_categorias']) . "') and areas.idareas in('" . join("','", $_SESSION['usr_grupos']) . "')) ";
                $cont7++;
            }


            if (isset($_POST['filtro_areas_t']) && $_POST['filtro_areas_t'] != "") {
                $condiciones .= ")  and t.categoria_2 in ('" . $_SESSION['filtro_areas_t'] . "')";
            } else {
                $condiciones .= ")";
            }
            break;
        default :
            $condiciones = "";
            break;
    }
}

$sql = "select t.id_ticket, t.unidad_negocio, t.ultima_actualizacion, t.fecha_registro, t.prioridad, t.grupo, g.nombre as nombre_grupo, "
        . " h.nombre as hospital, UPPER(cv2.descripcion) as estatus, t.estatus as id_estatus, u.nombre as registra,u.puesto as puesto_re, cv3.descripcion as prioridad_text, "
        . " u2.nombre as atiende, u3.nombre as resuelve,c1.nombre as categoria_1, areas.nombre_area as categoria_2, c3.nombre as categoria_3, "
        . " c4.nombre as categoria_2,  c4.id as id_categoria, cv4.descripcion as color_stts, IFNULL(n.num_notas, 0) AS cant_notas, IFNULL(d.num_docs, 0) AS cant_docs "
        . " from tickets t "
        . " LEFT JOIN categoria c1 on c1.id=t.categoria_1 "
        . " LEFT JOIN areas  on areas.idareas=t.categoria_2 "
        . " LEFT JOIN categoria c3 on c3.id=t.categoria_3 "
        . " LEFT JOIN categoria c4 on c4.id=t.categoria_4 "
        . " LEFT JOIN catalogo_valor cv2 on cv2.id=t.estatus and cv2.catalogo=2 "
        . " LEFT JOIN catalogo_valor cv3 on cv3.id=t.prioridad and cv3.catalogo=4 "
        . " LEFT JOIN catalogo_Valor cv4 on cv4.id=t.estatus and cv4.catalogo=6 "
        . " LEFT JOIN grupos g on g.id_grupo=t.grupo "
        . " LEFT JOIN usuarios u on t.usuario_registra=u.id_usuario "
        . " LEFT JOIN usuarios u2 on t.usuario_actual=u2.id_usuario "
        . " LEFT JOIN usuarios u3 on t.usuario_resuelve=u3.id_usuario "
        . " LEFT JOIN hospital h on h.id=t.unidad_negocio "
        . " JOIN usuario_hospital uh on t.unidad_negocio=uh.hospital and uh.usuario='" . $_SESSION['id_usuario'] . "' "
        . " LEFT JOIN (SELECT COUNT(id_nota) AS num_notas, ticket FROM notas WHERE ind_activo=1 GROUP BY ticket) n ON n.ticket=t.id_ticket "
        . " LEFT JOIN (SELECT COUNT(id_documento) AS num_docs, ticket FROM documentos WHERE ind_activo=1 GROUP BY ticket) d ON d.ticket=t.id_ticket "
        . " where true "
        . " $filtroEstatus $filtros $condiciones  $orden $orden_lista ";

//echo $sql;

$resultado = $mysqli->query($sql);
$num_filas = $resultado->num_rows;

$array_tickets = array();

while ($fila = $resultado->fetch_assoc()) {
    array_push($array_tickets, $fila['id_ticket']);
}

$sql .= " limit $comenzar_en, $resultados_por_pagina ";
$resultado = $mysqli->query($sql);

//echo $sql;
?>

<!--<div class="col-lg-12">
    <label class="pull-right text-muted">Num. Registros:&nbsp;<span><?php // echo $num_filas;                                         ?></span></label>
</div>

<br/>-->

<input type="hidden" name="num_tickets" id="num_tickets" value="<?php echo $num_filas; ?>">

<?php
while ($fila = $resultado->fetch_assoc()) {
//    array_push($array_tickets, $fila['id_ticket']);

    if ($_SESSION['ticket_vista'] === 'tickets-preview') {
        ?>
        <div class = "panel panel-detalles-ticket-lista" onclick = "javascript: accion_ticket(<?php echo $fila['id_ticket']; ?>, this)" style="border-radius: 0px; margin-bottom: 10px; border-top: 1px #B8B7B9 solid !important; border-bottom: 1px #B8B7B9 solid !important;">
            <?php
        } else {
            ?>
            <div class = "panel panel-default panel-detalles-ticket-lista panel-no-border" onclick = "javascript: vista_ticket(<?php echo $fila['id_ticket']; ?>)" style="border-radius: 0px; margin-bottom: 10px; border-top: 1px #B8B7B9 solid !important; border-bottom: 1px #B8B7B9 solid !important;">
                <?php
            }
            ?>


            <div class = "panel-body" style = "padding: 0;">
                <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12" style = "padding: 2px 0; font-size: 13px;">
                    <div class = "col-lg-2 col-md-2 col-sm-2 col-xs-4 no-padding col-stts" style = "text-align: center;">
                        <label style = "width: 100%;overflow: hidden;"><?php echo $fila['id_ticket']; ?></label>
                        <br/>
                        <label class="titulo-categoria-ticket" style = "width: 100%; color: #FFF; letter-spacing: 2px; background-color: <?php echo ($fila['color_stts'] == "1" ? "#8e8f90" : $fila['color_stts']); ?>" ><?php echo $fila['estatus']; ?></label>
                    </div>
                    <div class="col-lg-7 col-md-6 col-sm-6 col-xs-4" style="padding-top: 3px;">
                        <!--b class="text-muted">
                        <?php //echo $fila['categoria_1'];          ?>
                        <?php //echo ($fila['categoria_2'] == "" ? "" : "<span>-</span> " . $fila['categoria_2']);      ?>
                        </b>
                        </br-->
                        <p class="titulo-categoria-ticket">
                            <?php echo ($fila['categoria_1'] == "" ? "" : $fila['categoria_2']); ?>
                            <?php //echo ($fila['categoria_2'] == "" ? "" : "<span>-</span> " . $fila['categoria_2']);  ?>
                            &nbsp;
                        </p>




                        <span class="text-muted"><strong style="color:black;"><?php echo ($fila['id_estatus'] == '2' || $fila['id_estatus'] == '3' ? "Resuelve" : "Atiende") ?>: <?php echo ($fila['atiende'] == "" ? $fila['nombre_grupo'] : ($fila['id_estatus'] == '2' || $fila['id_estatus'] == '3' ? $fila['resuelve'] : $fila['atiende'])); ?></strong></span>
                    </div>
                    <div class = "col-lg-3 col-md-6 col-sm-6 col-xs-4" style="padding-top: 5px;">
                        <span class="text-muted">Ultima Act.</span>
                        <br/>
                        <label class="titulo-detalles-ticket-tbl"><?php calcular_tiempo($fila['ultima_actualizacion']); ?></label>
                    </div>
                </div>

                <?php
                $color = "rgb(235,233,233)";

                if ($fila['prioridad'] == "1") {
                    $color = "#ff7f7f";
                } else
                if ($fila['prioridad'] == "2") {
                    $color = "#A0D3FE";
                }
                ?>

                <div class = "col-lg-12 col-xs-12" style = "background-color: <?php echo $color ?>; font-size: 12px; /*border-top: solid 1px #B8B7B9;*/ padding: 0;">

                    <div class = "no-padding col-footer-detalles-ticket-cont" style = "border-right: none; text-align: center; font-size: 1em; margin-top: 7px;">
                        <span class="glyphicon glyphicon-pencil cont-ad-nt" style="padding: 3px; color: #777;"></span>
                        <label class="titulo-detalles-ticket-tbl cont-ad-nt"><?php echo $fila['cant_notas']; ?></label>
                        &nbsp;&nbsp;&nbsp;
                        <span class="glyphicon glyphicon-paperclip cont-ad-nt" style="padding: 3px; color: #777;"></span>
                        <label class="titulo-detalles-ticket-tbl cont-ad-nt"><?php echo $fila['cant_docs']; ?></label>
                    </div>


                    <div class = "col-footer-detalles-ticket" style = "border-right: none;">
                        <span class="text-muted">Solicitante</span>
                        <br/>
                        <label class="titulo-detalles-ticket-tbl"><?php echo $fila['puesto_re']; ?></label>
                    </div>
                    <div class = "col-footer-detalles-ticket" style = "border-right: none;">
                        <span class="text-muted">Unidad </span>
                        <br/>
                        <label class="titulo-detalles-ticket-tbl"> <?php echo $fila['hospital']; ?></label>
                    </div>
                    <!--                <div class = "col-footer-detalles-ticket" style = "border-right: none;">
                                        <span class="text-muted">Prioridad</span>
                                        <br/>
                                        <label class="titulo-detalles-ticket-tbl"><?php // echo $fila['prioridad_text'];                  ?></label>
                                    </div>-->
                    <div class = "col-footer-detalles-ticket cat-col-det-tick <?php
                    if (isset($_COOKIE['switch']) && $_COOKIE['switch'] === "on") {
                        echo "hidden";
                    } else if (!isset($_COOKIE['switch'])) {
                        echo "hidden";
                    }
                    ?>  ?> " style = "border-right: none;">

                        <br/>
                        <label class="titulo-detalles-ticket-tbl"><?php echo $fila['categoria_1']; ?></label>
                        <?php calcular_tiempo($fila['fecha_registro']) ?>
                    </div>

                    <div class = "col-footer-detalles-ticket" style="float: right; ">
                        <?php horas_vencer($fila['unidad_negocio'], $fila['fecha_registro'], $fila['id_categoria'], 'titulo', $fila['id_estatus']) ?>
                        <br/>
                        <label class="titulo-detalles-ticket-tbl"><?php
                            if ($fila['id_estatus'] == 1)
                                horas_vencer($fila['unidad_negocio'], $fila['fecha_registro'], $fila['id_categoria'], 'calcular', $fila['id_estatus']);
                            else
                                echo "<label class='blinking2' style='font-size:20px'>" . $fila['estatus'] . "</label>";
                            ?></label>
                    </div>

                </div>

            </div>
        </div>



        <?php
    }

    $_SESSION['lista_tickets'] = $array_tickets;


    $siguiente = 0;
    $anterior = 0;

    $pag_sig_comienza = ($comenzar_en + $resultados_por_pagina);
    $pag_ant_comienza = ($comenzar_en - $resultados_por_pagina);

    $mostrando_hasta = $pag_sig_comienza;

    if ($pag_sig_comienza < $num_filas) {
        $siguiente = 1;
    }

    if ($pag_ant_comienza >= 0) {
        $anterior = 1;
    }

    if ($pag_sig_comienza > $num_filas) {
        $mostrando_hasta = $num_filas;
    }
    ?>


    <input type="hidden" id="pag_siguiente" name="pag_siguiente" value="<?php echo $siguiente; ?>">
    <input type="hidden" id="pag_anterior" name="pag_anterior" value="<?php echo $anterior; ?>">

    <input type="hidden" id="pag_siguiente_comienza" name="pag_siguiente_comienza" value="<?php echo $pag_sig_comienza; ?>">
    <input type="hidden" id="pag_anterior_comienza" name="pag_anterior_comienza" value="<?php echo $pag_ant_comienza; ?>">

    <input type="hidden" id="mostrando_resultado_inicio" name="mostrando_resultado_inicio" value="<?php echo $comenzar_en + 1; ?>">
    <input type="hidden" id="mostrando_resultado_fin" name="mostrando_resultado_fin" value="<?php echo $mostrando_hasta; ?>">

    <input type="hidden" id="num_resultados_tickets" value="<?php echo $num_filas; ?>">



    <?php

    function horas_vencer($unidad, $tiempo, $categoria, $operacion, $status) {
        $horas_def = 150;
        switch ($operacion) {
            case 'calcular':
                if ($categoria != 150) {
                    require '../connection/conexion.php';
                    $mysqli->query("SET NAMES 'UTF8'");
                    date_default_timezone_set('America/Mexico_City');


                    $date2 = new DateTime(date('y-m-d H:i:s'));


                    $contv_ag = 0;
                    $contpv_ag = 0;
                    $contt_ag = 0;
                    $color = '';


                    /* $query = "SELECT tiempo FROM tiempos_resolucion where id_hospital='" . $unidad . "' and categoria='" . $categoria . "'";
                      $resultados_t = $mysqli->query($query);
                      $mysqli->query("SET NAMES 'UTF8'");
                      $fila_t = $resultados_t->fetch_assoc(); */


                    $date1 = new DateTime(date('y-m-d H:i:s', strtotime($tiempo)));

                    $diff = $date1->diff($date2);
                    $dias = $diff->format('%d %h %i %s');
                    $horas = $diff->format('%h');
                    $minutos = $diff->format('%i');
                    $segundos = $diff->format('%s');
                    $dias_h = ($dias * 24) + $horas;

                    // echo "<br>" . $dias_h . ":" . $minutos . ":" . $segundos;

                    $resta = $horas_def - $dias_h;

                    $resultado = $horas_def - $dias_h;

                    if ($resultado < 0) {
                        $resultado = $resultado * -1;
                        $color = 'RED';
                    } else
                    if ($resultado >= 0 && $resta <= 3) {
                        $color = "black;";
                    } else
                    if ($resultado > 3) {
                        $color = "black;";
                    }

                    $resultado = $resultado;

                    echo "<span style='color:$color; font-size: 30px; text-align: center'>" . $resultado . "</span><span style='font-size: 15px; color:$color'>&nbspHrs.</span>";
                } else
                if ($categoria == 9) {
                    echo "<span style='color:#81C784; font-size: 30px; text-align: center'>48 Hrs</span>";
                }
                break;


            case 'titulo':
                if ($categoria != 9) {
                    require '../connection/conexion.php';
                    $mysqli->query("SET NAMES 'UTF8'");
                    date_default_timezone_set('America/Mexico_City');


                    $date2 = new DateTime(date('y-m-d H:i:s'));


                    $contv_ag = 0;
                    $contpv_ag = 0;
                    $contt_ag = 0;
                    $color = '';


                    /* $query = "SELECT tiempo FROM tiempos_resolucion where id_hospital='" . $unidad . "' and categoria='" . $categoria . "'";
                      $resultados_t = $mysqli->query($query);
                      $mysqli->query("SET NAMES 'UTF8'");
                      $fila_t = $resultados_t->fetch_assoc(); */




                    $date1 = new DateTime(date('y-m-d H:i:s', strtotime($tiempo)));

                    $diff = $date1->diff($date2);
                    $dias = $diff->format('%d %h %i %s');
                    $horas = $diff->format('%h');
                    $minutos = $diff->format('%i');
                    $segundos = $diff->format('%s');
                    $dias_h = ($dias * 24) + $horas;

                    // echo "<br>" . $dias_h . ":" . $minutos . ":" . $segundos;

                    $resta = $horas_def - $dias_h;
                    $titulo = '';


                    $resultado = $horas_def - $dias_h;

                    if ($status == 1) {
                        if ($resultado < 0) {
                            $resultado = $resultado * -1;
                            $color = 'RED';
                            $titulo = "<span class='text-muted' style='color:$color;'>Vencio hace</span>";
                        } else
                        if ($resultado >= 0 && $resta <= 3) {
                            $color = "black;";
                            $titulo = "<span class='text-muted' style='color:$color;'>Vencimiento en</span>";
                        } else
                        if ($resultado > 3) {
                            $color = "black;";
                            $titulo = "<span class='text-muted' style='color:$color;'>Vencimiento en</span>";
                        }
                        echo $titulo;
                    } else {
                        $titulo = "<span class='text-muted'></span>";
                    }
                } else
                if ($categoria == 9) {
                    echo "<span class='text-muted' style='color:black;'>Vencimiento en</span>";
                }

                break;
        }
    }

    function calcular_tiempo($fecha_registro) {
        date_default_timezone_set('America/Mexico_City');
        $fecha1 = date_create($fecha_registro);
        $fecha2 = date_create(date("Y-m-d H:i:s"));

        $intervalo = date_diff($fecha1, $fecha2);

        $segundos = $intervalo->format('%s');
        $min = $intervalo->format('%i');
        $horas = $intervalo->format('%h');
        $dias = $intervalo->format('%d');
        $semanas = intval($dias / 7);
        $meses = $intervalo->format('%m');
        $anio = $intervalo->format('%y');

        if ($anio != 0) {
            echo "Hace " . $anio . " años";
        } else if ($meses != 0) {
            echo "Hace " . $meses . " meses";
        } else if ($semanas != 0) {
            echo "Hace " . $semanas . " semanas ";
            $diasR = $dias - ($semanas * 7);
            if ($diasR != 0) {
                echo $diasR . " dias";
            }
        } else if ($dias != 0) {
            echo "Hace " . $dias . " dias";
        } else if ($horas != 0) {
            echo "Hace " . $horas . " horas";
        } else if ($min != 0) {
            echo "Hace " . $min . " minutos";
        } else {
            echo "Hace " . "un momento";
        }
    }
    ?>