<?php
session_start();
require '../connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$id_ticket = "";
if (isset($_POST['ticket'])) {
    $id_ticket = $_POST['ticket'];
}

$sql = "select t.*, c1.nombre as c1, c2.nombre as c2, c3.nombre as c3, c4.nombre as c4, g.nombre as nombre_grupo, "
        . " h.nombre as unidad, h.telefono, UPPER(cv2.descripcion) as estatus_name, u.nombre as solicita, "
        . " ifnull(u2.nombre, 'Sin asignar') as usuario_asignado, "
        . " cv3.descripcion as prioridad_text, cv4.descripcion as color_stts "
        . " from tickets t "
        . " left join catalogo_valor cv2 on t.estatus=cv2.id and cv2.catalogo=2 "
        . " left join catalogo_valor cv3 on t.prioridad=cv3.id and cv3.catalogo=4 "
        . " left join catalogo_valor cv4 on t.estatus=cv4.id and cv4.catalogo=6 "
        . " left join hospital h on h.id=t.unidad_negocio "
        . " left join usuarios u on t.usuario_registra=u.id_usuario "
        . " left join usuarios u2 on t.usuario_anterior=u2.id_usuario "
        . " left join categoria c1 on c1.id=t.categoria_1 "
        . " left join categoria c2 on c2.id=t.categoria_2 "
        . " left join categoria c3 on c3.id=t.categoria_3 "
        . " left join categoria c4 on c4.id=t.categoria_4 "
        . " left join grupos g on g.id_grupo=t.grupo "
        . " where t.id_ticket='" . $id_ticket . "';";

//echo $sql;

$resultado = $mysqli->query($sql);
$fila = $resultado->fetch_assoc();
?>

<div class = "content-wrapper">
    <div class = "col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
        <input type = "hidden" id = "id_ticket" value = "<?php echo $id_ticket; ?>">


        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <a href="#" title="Ver detalle completo" onclick = "javascript: vista_ticket(<?php echo $fila['id_ticket']; ?>)"> <span class="glyphicon glyphicon-file"></span> <span class="logo-lg">Reporte <b><?php echo $id_ticket; ?></b></span></a>
                    <label style = "text-align: center;width: 100%;overflow: hidden; color: #FFF; letter-spacing: 2px; background-color: <?php echo ($fila['color_stts'] == "" ? "#8e8f90" : $fila['color_stts']); ?>"><?php echo $fila['estatus_name']; ?></label>
                </div>

                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                        <label style="margin-bottom: 0;"><b class="hidden-xs" style="font-weight: 100;">Fecha sol:</b> <span><?php echo date_format(date_create($fila['fecha_registro']), "d/m/Y H:i") ?></span></label>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                        <label style="margin-bottom: 0;"><b class="hidden-xs" style="font-weight: 100;">Ultima act:</b> <span id="ultima_actualizacion"><?php echo date_format(date_create($fila['ultima_actualizacion']), "d/m/Y H:i") ?></span></label>
                    </div>
                </div>
            </div>


            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="content-data-side col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-right: 4px;">
                    <div class="box-header  box-head-data">
                        <label class="box-title title-box-ticket titulo-categoria-ticket">Datos generales &nbsp;</label>
                    </div>

                    <div class="box-body side-data">

                        <i class="fa fa-tags margin-r-5"></i><span><b><?php echo ($fila['c4'] == "" ? "" : $fila['c4']); ?></b></span>

                    </div>
                </div>


                <div class="content-data-side col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 4px;">
                    <div class="box-header  box-head-data">
                        <label class="box-title title-box-ticket titulo-categoria-ticket">Datos contacto &nbsp;</label>
                    </div>

                    <div class="box-body side-data">
                        <i class="fa fa-user margin-r-5" style="display: none"><span><?php solicitante($fila['solicita'], $fila['id_ticket']) ?></span></br></i> 


                        <i class="fa fa-building margin-r-5"> <span></i><?php echo $fila['unidad']; ?></span></br>
                        <i class="fa fa-user margin-r-5" style="width: 15px;"></i><label style="width:90px;font-weight:100;">Responsable: </label><span><?php echo $fila['usuario_asignado']; ?></span></br>

                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="content-data-side col-lg-12 c" style="padding: 0 15px;">
                    <div class="box-header  box-head-data">
                        <label class="box-title title-box-ticket titulo-categoria-ticket">Comentarios &nbsp;</label>
                    </div>
                    <div class="box-body side-data">
                        <p class="" style="word-wrap: break-word;">
                            <?php echo str_replace("\n", "</br>", $fila['comentarios']); ?>
                        </p>
                    </div>
                </div>
            </div>


            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                            <div class="box-body" id="lista_documentos" style="padding-top: 0 !important;">
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
    </div>
</div>
<?php

function solicitante($id_sol, $id_ticket) {


    if ($id_sol === 'Usuario Sistema') {
        require '../connection/conexion.php';
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
        require '../connection/conexion.php';
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
?>