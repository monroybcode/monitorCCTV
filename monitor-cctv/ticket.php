<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}

include 'resources/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
include 'utils/constantes.php';
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <title><?php echo NOMBRE_SISTEMA; ?></title>

        <?php require 'resources/components/includes.php'; ?>
        <?php require 'resources/components/buttons.php'; ?>

        <script>
            var saved = false;
            $(document).ready(function (e) {
//                if ($("#usuario_actual_id").val() === "" && $("#solicitante_t").val() === $("#id_usuario_logueado").val() && $("#stts_ticket").val() === "1") {
//                    $("#btn-cancelar-ts").show();
//                }

//                if ($("#solicitante_t").val() === $("#id_usuario_logueado").val() && $("#stts_ticket").val() === "2") {
//                    $("#btn-cerrar-t").show();
//                }
//                if ($("#solicitante_t").val() === $("#id_usuario_logueado").val() && $("#stts_ticket").val() === "2") {
//                    $("#btn-reabrir-t").show();
//                }

//                if ($("#usuario_actual_id").val() === $("#id_usuario_logueado").val()) {
//                    $("#btn-resolver-ts").show();
//                }

                $(".btn-busqueda-ticket-id").click(function (e) {
                    show_loader();
                    window.location.href = "ticket.php?ticket=" + $("#busqueda_ticket_id").val();
                });


                $("#busqueda_ticket_id").keypress(function (e) {
                    if (e.which == 13) {
                        show_loader();
                        window.location.href = "ticket.php?ticket=" + $("#busqueda_ticket_id").val();
                    }
                });

            });

            window.onbeforeunload = function () {
                if (saved == true) {
                    return "Tienes cambios pendientes, deseas salir?";
                }
            };

            function save() {
                saved = true;
            }

            function ant_sig(id_ticket) {
                show_loader();
                window.location.href = "ticket.php?ticket=" + id_ticket;
            }
        </script>
    </head>
    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse">
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>

        <?php
        $id_ticket = "";
        if (isset($_GET['ticket'])) {
            $id_ticket = $_GET['ticket'];
        }

        $rol = $_SESSION['rol'];
        // echo $rol;
        if (($rol !== "3" && $rol !== "8")) {
            $sqlValidate = "select count(t.id_ticket) as total
                    from tickets t
                    JOIN usuario_hospital uh on t.unidad_negocio=uh.hospital and uh.usuario='" . $_SESSION['id_usuario'] . "' ";
            if (isset($_SESSION['pag_ant']) && $_SESSION['pag_ant'] == "reporte.php") {
                $sqlValidate .= " where ";
            } else {
                $sqlValidate .= " where t.estatus in (1,2) and ";
            }
            $sqlValidate .= "t.id_ticket = '$id_ticket'
                    and
                    (
                        t.usuario_registra='" . $_SESSION['id_usuario'] . "'
                        or
                        t.usuario_actual='" . $_SESSION['id_usuario'] . "'
                        or
                        t.usuario_resuelve='" . $_SESSION['id_usuario'] . "'
                        or
                        exists(select uc.id_categoria from usuario_categorias uc where uc.id_usuario = t.usuario_actual and uc.id_categoria in('" . join("','", $_SESSION['usr_categorias']) . "'))
                        or
                        exists(select uc.id_categoria from usuario_categorias uc where uc.id_usuario = t.usuario_registra and uc.id_categoria in('" . join("','", $_SESSION['usr_categorias']) . "'))
                    )";
        } else
        if ($rol == 3 || $rol == 8) {
            $sqlValidate = " SELECT 
                COUNT(t.id_ticket) AS total
            FROM
                tickets t
                    JOIN
                usuario_hospital uh ON t.unidad_negocio = uh.hospital
                    AND uh.usuario = '" . $_SESSION['id_usuario'] . "'
            WHERE
                t.estatus IN (1 , 2, 3, 4)
                    AND t.id_ticket =  '$id_ticket' ";
        }

        //echo $sqlValidate;

        $resultadoVal = $mysqli->query($sqlValidate);

        $filaVal = $resultadoVal->fetch_assoc();

        if (($filaVal['total'] > 0) || (in_array("ver_detalle_ticket_completo", isset($_SESSION['funciones']) ? $_SESSION['funciones'] : array()))) {
            $sql = "SELECT 
                    t.*,
                    c1.nombre AS c1,
                    c2.nombre AS c2,
                    c3.nombre AS c3,
                    c4.nombre AS c4,
                    g.nombre_area AS nombre_grupo,
                    h.nombre AS unidad,
                    h.telefono,
                    UPPER(cv2.descripcion) AS estatus_name,
                    t.estatus AS id_estatus,
                    u.puesto AS solicita,
                    IFNULL(u2.nombre, 'Sin asignar') AS usuario_asignado,
                    u3.nombre AS resuelve,
                    cv3.descripcion AS prioridad_text,
                    cv4.descripcion AS color_stts,
                    fechaevento,
                    fechatermino
                FROM
                    tickets t
                        LEFT JOIN
                    catalogo_valor cv2 ON t.estatus = cv2.id AND cv2.catalogo = 2
                        LEFT JOIN
                    catalogo_valor cv3 ON t.prioridad = cv3.id
                        AND cv3.catalogo = 4
                        LEFT JOIN
                    catalogo_valor cv4 ON t.estatus = cv4.id AND cv4.catalogo = 6
                        LEFT JOIN
                    hospital h ON h.id = t.unidad_negocio
                        LEFT JOIN
                    usuarios u ON t.usuario_registra = u.id_usuario
                        LEFT JOIN
                    usuarios u2 ON t.usuario_actual = u2.id_usuario
                        LEFT JOIN
                    usuarios u3 ON t.usuario_resuelve = u3.id_usuario
                        LEFT JOIN
                    categoria c1 ON c1.id = t.categoria_1
                        LEFT JOIN
                    categoria c2 ON c2.id = t.categoria_2
                        LEFT JOIN
                    categoria c3 ON c3.id = t.categoria_3
                        LEFT JOIN
                    categoria c4 ON c4.id = t.categoria_4
                        LEFT JOIN
                    areas g ON g.idareas = t.categoria_2
                WHERE
                    t.id_ticket = '$id_ticket'";

            // echo $sql;

            $resultado = $mysqli->query($sql);
            $num_res = $resultado->num_rows;
            $fila = $resultado->fetch_assoc();

            $queryTO = "select fp.operacion_etapa, fp.tp_responsable
                from flujo_proceso fp
                where fp.ind_activo = true
                    and fp.num_etapa = '" . $fila['sec_flujo'] . "'
                    and fp.id_categoria_2 = '" . $fila['categoria_2'] . "'
                    and fp.unidad_negocio = '" . $fila['unidad_negocio'] . "'";

            if ($fila['categoria_3'] > 0) {
                $queryTO .= " and fp.id_categoria_3 = '" . $fila['categoria_3'] . "' ";
            }

            if ($fila['categoria_4'] > 0) {
                $queryTO .= " and fp.id_categoria_4 = '" . $fila['categoria_4'] . "' ";
            }

            $queryTO .= " limit 1";

            $resultSetTO = $mysqli->query($queryTO);
            $rsTO = $resultSetTO->fetch_assoc();
            $tipoOperacionFP = "";

            //if($rsTO['tp_responsable'] == "I"){
            $tipoOperacionFP = $rsTO['operacion_etapa'];
            //}

            $sql2 = "select * from usr_ticketauto where id_ticket='$id_ticket' and email ='responsables'";
            // echo $sql2;
            $resultado2 = $mysqli->query($sql2);

            $cadena_res = "";
            while ($fila2 = $resultado2->fetch_assoc()) {
                $cadena_res .= "<br>-" . $fila2['nombre'];
            }

            $porciones_2 = explode("<br>-", $cadena_res);

            $cantidad = count($porciones_2);

            $cadena = '';

            if ($cantidad > 2) {
                for ($i = 2; $i < $cantidad; $i++) {
                    $cadena .= "<br>-" . $porciones_2[$i];
                }
            }

            $dato = $fila['fechaevento'];
            $fecha = date('d-m-Y', strtotime($dato));
            $hora = date('H:i:s', strtotime($dato));

            $dato2 = $fila['fechatermino'];
            $fecha2 = date('d-m-Y', strtotime($dato2));
            $hora2 = date('H:i:s', strtotime($dato2));
            ?>

            <div class = "content-wrapper">
                <div class = "col-lg-10 col-md-10 col-sm-12 col-xs-12 col-lg-offset-1 col-md-offset-1 contenido-w">
                    <input type = "hidden" id = "id_ticket" value = "<?php echo $id_ticket; ?>">
                    <div class="row">
                        <div class = "input-group col-lg-4 col-md-4 col-sm-4 col-xs-4" id="input-group-busqueda" style = "margin: 5px 0; padding: 0 16px; float: left">
                            <input type = "text" name = "busqueda_ticket_id" id = "busqueda_ticket_id" class = "form-control input-sm"/>
                            <span class = "input-group-btn">
                                <button type="button" class="btn btn-default btn-sm btn-busqueda-ticket-id" style="height: 25px;padding: 4px;"> Ir a Ticket</button>
                            </span>
                        </div>
                        <?php
                        if ($num_res > 0) {
                            ?>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 cont-buttons" style="margin: 5px 0; padding: 0 16px; float: left; text-align: end;">
                                <?php mostrar_botones($fila['usuario_actual'], $fila['estatus'], $fila['id_ticket'], $tipoOperacionFP, $fila['categoria_2'], $fila['categoria_3'], $fila['categoria_4'], $fila['sec_flujo'], $fila['unidad_negocio'], $fila['usuario_registra']); ?>
                            </div>
                            <?php
                        } else {
                            echo '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-5 panel-detalles-ticket">';
                            echo '<label class="text-danger">';
                            echo "No se encontro el ticket con el folio: " . $id_ticket;
                            echo '</label>';
                            echo '&nbsp;';
                            echo '<button class="btn btn-danger btn-sm" onclick="javascript:window.location.href=\'tickets-preview.php\'">Ir a tickets</button>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <?php
                    if ($num_res > 0) {
                        ?>

                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-5 panel-detalles-ticket"> <!--style="padding-right: 30px; padding-left: 100px;"-->

                                <section class="det-tick">
                                    <div style="text-align: center;">
                                        <span class="logo-lg">Reportes <b><?php echo $id_ticket; ?></b></span>
                                        <br>
                                        <label style = "width: 35%;overflow: hidden; color: #FFF; letter-spacing: 2px; background-color: <?php echo ($fila['color_stts'] == "" ? "#8e8f90" : $fila['color_stts']); ?>"><?php echo $fila['estatus_name']; ?></label>
                                        <input type="hidden" name="stts_ticket" id="stts_ticket" value="<?php echo $fila['estatus']; ?>">
                                    </div>
                                    <div class="content-data-side">
                                        <div class="box-header  box-head-data">
                                            <label class="box-title title-box-ticket titulo-categoria-ticket">Datos generales &nbsp;</label>

                                        </div>

                                        <div class="box-body side-data">
                                            <label style="margin-bottom: 0;"><b class="hidden-xs" style="font-weight: 100;">Area:</b> <span><?php echo  $fila['nombre_grupo']; ?></span></label>
                                            <br>  <i class="fa fa-tags margin-r-5"></i><span><b><?php echo ($fila['c4'] == "" ? "" : $fila['c4']); ?></b></span><br>
                                            <br>Fecha:&nbsp;<?php echo $fecha;  ?><br>
                                            Hora del evento:
                                            <?php echo $hora;  ?> a  <?php echo $hora2;  ?>
                                        </div>
                                    </div>
                                    <div class="content-data-side">
                                        <div class="box-header  box-head-data">
                                            <label class="box-title title-box-ticket titulo-categoria-ticket">Datos contacto &nbsp;</label>
                                        </div>
                                        <div class="box-body side-data">
                                            <i class="fa fa-user margin-r-5" style="display: none"> <span><?php solicitante($fila['solicita'], $fila['id_ticket']) ?></span></br></i>

                                            <i class="fa fa-building margin-r-5"></i> <span><?php echo $fila['unidad']; ?></span><?php echo $cadena; ?><br><br>
                                            <i class="fa fa-user margin-r-5" style="width: 15px;"></i><label style="width:90px;font-weight:100;">Responsable(s): </label><br>- <?php echo $porciones_2[1]; ?></br>


                                        </div>
                                    </div>
                                    <div class="content-data-side">
                                        <div class="box-header  box-head-data">
                                            <label class="box-title title-box-ticket titulo-categoria-ticket">Fechas &nbsp;</label>
                                        </div>

                                        <div class="box-body side-data">
                                            <i class="fa fa-plus margin-r-5"></i>       <label style="width:80px;font-weight:100;">Creado: </label>     <span><?php echo date_format(date_create($fila['fecha_registro']), "d/m/Y H:i"); ?></span></br>
                                            <i class="fa fa-refresh margin-r-5"></i>    <label style="width:80px;font-weight:100;">Actualizado: </label>   <span><?php echo date_format(date_create($fila['ultima_actualizacion']), "d/m/Y H:i"); ?></span></br>
                                            <i class="fa fa-check margin-r-5"></i>      <label style="width:80px;font-weight:100;">Resuelto: </label>    <span><?php echo isset($fila['fecha_resolucion']) ? date_format(date_create($fila['fecha_resolucion']), "d/m/Y H:i") : ""; ?></span></br>
                                        </div>
                                    </div>
                                    <div class="content-data-side">
                                        <div class="box-header  box-head-data">
                                            <label class="box-title title-box-ticket titulo-categoria-ticket">Informador &nbsp;</label>
                                        </div>
                                        <div class="box-body side-data">
                                            <i class="fa fa-user-circle margin-r-5" style="width: 15px;"></i><label style="width:90px;font-weight:100;">Monitorista: </label><span><?php solicitante($fila['solicita'], $fila['id_ticket']) ?></span></br>
                                           <!-- <i class="fa fa-users margin-r-5" style="width: 15px;"></i><label style="width:90px;font-weight:100;">Participantes: </label>
                                           
                                            $sqlParticipantes = "select nombre from "
                                                    . " ((select distinct(n.usuario_registra) from notas n inner join tickets t on t.id_ticket=n.ticket where t.id_ticket='$id_ticket') "
                                                    . " union "
                                                    . " (select distinct(d.usuario_registra) FROM documentos d inner join tickets t on t.id_ticket=d.ticket where t.id_ticket='$id_ticket')) "
                                                    . " p inner join usuarios u on p.usuario_registra=u.id_usuario";

                                            $resultadoParticipantes = $mysqli->query($sqlParticipantes);

                                            $cp = 0;

                                            while ($filaParticipante = $resultadoParticipantes->fetch_assoc()) {
                                                echo ($cp > 0 ? '<i style="margin-right: 23px;"></i>' : '');
                                                echo '<span>' . $filaParticipante['nombre'] . '</span></br>';
                                                $cp++;
                                            }
                                            ?>--->
                                        </div>
                                    </div>

                                </section>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7 note-pan">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 panel-detail no-padding">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 3px 0; height: 45px;">

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 no-padding">

                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 no-padding">
                                                <!--label style="margin-bottom: 0;"><span class="hidden-xs" style="font-weight: 100;">Atiende:</span> <span id="usuario_asignado"><?php // echo ($fila['usuario_asignado'] == "Sin asignar" ? $fila['nombre_grupo'] : $fila['usuario_asignado'] );    ?></span></label-->
                                                <input type="hidden" name="usuario_actual_id" id="usuario_actual_id" value="<?php echo $fila['usuario_actual']; ?>">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="cont-notas-data col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="cont-notas">
                                        <input type="hidden" id="usuario_elimina_nota" value="<?php
                                        if (in_array("ver_eliminar_notas", $_SESSION['funciones'])) {
                                            echo "1";
                                        } else {
                                            echo "0";
                                        }
                                        ?>">

                                        <input type="hidden" id="usuario_edita_nota" value="<?php
                                        if (in_array("ver_editar_notas", $_SESSION['funciones'])) {
                                            echo "1";
                                        } else {
                                            echo "0";
                                        }
                                        ?>">
                                        <div class="content-data-side">
                                            <div class="box-header  box-head-data">
                                                <label class="box-title title-box-ticket titulo-categoria-ticket">Comentarios &nbsp;</label>
                                            </div>
                                            <div class="box-body side-data">
                                                <p class="" style="word-wrap: break-word;">
                                                    <?php echo str_replace("\n", "</br>", $fila['comentarios']); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <ul class="nav nav-tabs">
                                            <li class="active" id="li-notas"><a href="#tab-notas" data-toggle="tab" id="a-notas" style="border-radius: 0;">Notas</a></li>
                                            <li id="li-adjuntos"><a href="#tab-adjuntos" data-toggle="tab" id="a-adjuntos" style="border-radius: 0;">Adjuntos</a></li>
                                            <li id="li-bitacora"><a href="#tab-bitacora" data-toggle="tab" id="a-bitacora" style="border-radius: 0;">Bitácora</a></li>
                                        </ul>
                                        <div class="tab-content col-lg-12 no-padding" id="tab-content" style="margin-top: 25px;">
                                            <div class="tab-pane active" id="tab-notas">

                                                <div class="contenedor-notas">

                                                    <?php
                                                    $sql = "select n.*, u.*, d.ind_activo AS doc from notas n inner join usuarios u on n.usuario_registra=u.id_usuario "
                                                            . " left join documentos d on d.id_documento=n.archivo "
                                                            . " where n.ticket='$id_ticket' and n.ind_activo=1 order by n.id_nota desc;";

                                                    //  echo $sql;
                                                    $resultado_notas = $mysqli->query($sql);
                                                    $file = "";

                                                    while ($filaN = $resultado_notas->fetch_assoc()) {
                                                        $adjuntor = '';
                                                        if ($filaN['archivo'] != '') {
                                                            $adjuntor = '<a href="' . $filaN['archivo'] . '" target="_blank">Adjunto a Nota</a>';
                                                        }

                                                        $controles = "";
                                                        (intval($filaN['doc']) != 0 ? $file = '<span class="glyphicon glyphicon-paperclip" style="padding-right:10px;padding-left:10px;"></span>' : $file = "" );

                                                        if (in_array("ver_eliminar_notas", $_SESSION['funciones']) && $fila['estatus'] != '3') {
                                                            $controles .= '<button class="btn-link link-muted pull-right" onclick="javascript:baja_nota(' . $filaN['id_nota'] . ')"> <span class="glyphicon glyphicon-trash"></span> </button>';
                                                        }

                                                        if ((in_array("ver_editar_notas", $_SESSION['funciones']) && $fila['estatus'] != '3' && $filaN['usuario_registra'] == $_SESSION['id_usuario']) || (in_array("ver_editar_notas_admin", $_SESSION['funciones']) && $fila['estatus'] != '3')) {
                                                            $controles .= '<button class="btn-link link-muted pull-right" onclick="javascript:edita_nota(' . $filaN['id_nota'] . ')"><span class = "glyphicon glyphicon-pencil"></span> </button>';
                                                        }

                                                        echo '<div class="post" id="div_post_' . $filaN['id_nota'] . '">'
                                                        . '<div class="">'
                                                        . '<b><span class="username" style="margin-left: 15px;">'
                                                        . '<span>' . $filaN['puesto'] . ' - ' . date_format(date_create($filaN['fecha_registro']), "d/m/Y H:i") . '</span>'
                                                        . '</b>'
                                                        . '</div>'
                                                        . '<p style="margin-left: 15px;font-size:14px;font-family:HelveticaNeue" class="texto-nota-' . $filaN['id_nota'] . '" >'
                                                        . str_replace("\n", "<br>", $filaN['nota'])
                                                        . '</p>'
                                                        . $adjuntor
                                                        . '</div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="tab-pane" id="tab-adjuntos">
                                                <div class="box-body"  id="lista_documentos" style="padding-top: 0 !important;">

                                                    <?php
                                                    $sql = "SELECT url FROM tickets where id_ticket='$id_ticket'";

                                                    $resultado_documentos = $mysqli->query($sql);
                                                    while ($filaD = $resultado_documentos->fetch_assoc()) {
                                                        echo '<p class="text-muted">';
                                                        echo "<span class='glyphicon glyphicon-paperclip'></span><a href='" . $filaD['url'] . "' target='_blank'>" . $filaD['url'] . "</a> ";
                                                        echo '</p>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab-bitacora">
                                                <table class="table-r table table-responsive table-hover text-muted">
                                                    <thead class="">
                                                        <tr>
                                                            <th>Fecha registro</th>
                                                            <th>Descripción</th>
                                                            <th>Usuario</th>
                                                            <th>Cambios</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>

                                                        <?php
                                                        $sql = "select b.ticket, b.fecha_registro, b.cod_origen, b.id_origen, b.descripcion, b.campos, b.cambios, u.puesto "
                                                                . " from bitacora b inner join usuarios u on b.usuario_evt=u.id_usuario where b.ticket='$id_ticket' order by b.id asc;";
                                                        $resultado = $mysqli->query($sql);

                                                        while ($fila = $resultado->fetch_assoc()) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo date_format(date_create($fila['fecha_registro']), "d/m/Y H:i"); ?></td>
                                                                <td><?php echo $fila['descripcion']; ?></td>
                                                                <td><?php echo $fila['puesto']; ?></td>
                                                                <td><?php echo $fila['cambios']; ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>

            <?php
        } else {
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 panel-detalles-ticket">';

            echo '<div class = "input-group col-lg-4 col-md-4 col-sm-4 col-xs-4" id="input-group-busqueda" style = "margin: 5px 0; padding: 0 16px; float: left">
                            <input type = "text" name = "busqueda_ticket_id" id = "busqueda_ticket_id" class = "form-control input-sm"/>
                            <span class = "input-group-btn">
                                <button type="button" class="btn btn-default btn-sm btn-busqueda-ticket-id" style="height: 25px;padding: 4px;"> Ir a Ticket</button>
                            </span>
                        </div>';
            echo '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><label class="text-danger" style="padding-top:10px;">';
            echo "No se encontro o no tiene permisos de visualizar el ticket con el folio: " . $id_ticket;
            echo '</label></div>';
            echo '&nbsp;';
            //echo '<button class="btn btn-danger btn-sm" onclick="javascript:window.location.href=\'tickets-preview.php\'">Ir a tickets</button>';
            echo '</div>';
        }

        function solicitante($id_sol, $id_ticket) {


            if ($id_sol === 'Usuario Sistema') {

                require './resources/connection/conexion.php';
                $mysqli->query("SET NAMES 'UTF8'");
                date_default_timezone_set('America/Mexico_City');


                $query = "SELECT nombre FROM usr_ticketauto where id_ticket='" . $id_ticket . "'";
                $resultados_t = $mysqli->query($query);
                $mysqli->query("SET NAMES 'UTF8'");
                $fila_t = $resultados_t->fetch_assoc();

                if ($fila_t['nombre'] == '') {
                    echo "<span></span>";
                } else {
                    echo "<span>" . $fila_t['nombre'] . "</span>";
                }
            } else {
                echo "<span>$id_sol</span>";
            }
        }

        function email_s($id_sol, $id_ticket) {


            if ($id_sol === 'Usuario Sistema') {
                require './resources/connection/conexion.php';
                $mysqli->query("SET NAMES 'UTF8'");
                date_default_timezone_set('America/Mexico_City');


                $query = "SELECT * FROM usr_ticketauto where id_ticket='" . $id_ticket . "'";
                $resultados_t = $mysqli->query($query);
                $mysqli->query("SET NAMES 'UTF8'");
                $fila_t = $resultados_t->fetch_assoc();

                if ($fila_t['nombre'] == '') {
                    echo "<span></span>";
                } else {
                    echo "<span>" . $fila_t['email'] . "</span>";
                }
            } else {
                echo "<span></span>";
            }
        }

        function solicitante_a($id_sol, $id_ticket) {


            if ($id_sol === 'Usuario Sistema') {
                require './resources/connection/conexion.php';
                $mysqli->query("SET NAMES 'UTF8'");
                date_default_timezone_set('America/Mexico_City');


                $query = "SELECT nombre FROM usr_ticketauto where id_ticket='" . $id_ticket . "'";
                $resultados_t = $mysqli->query($query);
                $mysqli->query("SET NAMES 'UTF8'");
                $fila_t = $resultados_t->fetch_assoc();

                if ($fila_t['nombre'] == '') {
                    echo "";
                } else {
                    echo $fila_t['nombre'];
                }
            } else {
                echo $id_sol;
            }
        }
        ?>
        <?php include './resources/modals/modal-tratar-ticket.php'; ?>
        <?php include './resources/modals/modal-recategorizar.php'; ?>
        <?php include './resources/modals/modal-asignar-operador.php'; ?>
        <?php include './resources/modals/modal-procesar-ticket.php'; ?>
        <?php include './resources/modals/modal-resolver.php'; ?>
        <?php include './resources/modals/modal-autoriza-rechaza-ticket.php'; ?>
        <?php include './resources/modals/modal-cancelar-ticket.php'; ?>
        <?php include './resources/modals/modal-cerrar-ticket.php'; ?>
        <?php include './resources/modals/modal-edita-nota.php'; ?>
        <?php include './resources/modals/modal-reabrir-ticket.php'; ?>
        <?php include './resources/modals/modal-confirm.php'; ?>
        <?php //include './resources/modals/modal-fp-resolver.php'; ?>
        <?php include './resources/modals/modal-fp-regresar.php'; ?>
        <?php //include './resources/modals/modal-fp-autorizar.php'; ?>
        <?php include './resources/modals/modal-fp-rechazar.php'; ?>
        <?php include './resources/modals/modal-fp-rechazar-c.php'; ?>

        <?php include 'resources/components/footer.php'; ?>
    </body>
</html>