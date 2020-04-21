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
        <title><?php echo NOMBRE_SISTEMA; ?></title>

        <?php require 'resources/components/includes.php'; ?>

    </head>
    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse" style="overflow: hidden;">
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>
        <?php include 'resources/components/sidebar.php'; ?>

        <?php
        $id_ticket = "";
        if (isset($_GET['ticket'])) {
            $id_ticket = $_GET['ticket'];
        }

        $sql = "select t.*, c1.nombre as c1, c2.nombre as c2, c3.nombre as c3, c4.nombre as c4, "
                . " cv.descripcion as unidad, h.telefono, cv2.descripcion as estatus_name, u.nombre as solicita, cv3.descripcion as prioridad_text "
                . " from tickets t left join catalogo_valor cv on t.unidad_negocio=cv.id and cv.catalogo=5"
                . " left join catalogo_valor cv2 on t.estatus=cv2.id and cv2.catalogo=2 "
                . " left join catalogo_valor cv3 on t.prioridad=cv3.id and cv3.catalogo=4 "
                . " left join hospital h on h.id=t.unidad_negocio "
                . " left join usuarios u on t.usuario_registra=u.id_usuario "
                . " left join categoria c1 on c1.id=t.categoria_1 "
                . " left join categoria c2 on c2.id=t.categoria_2 "
                . " left join categoria c3 on c3.id=t.categoria_3 "
                . " left join categoria c4 on c4.id=t.categoria_4 "
                . " where t.id_ticket='" . $id_ticket . "';";

//        echo $sql;

        $resultado = $mysqli->query($sql);
        $fila = $resultado->fetch_assoc();
        ?>



        <div class="content-wrapper">
            <input type="hidden" id="id_ticket" value="<?php echo $id_ticket; ?>">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 top-options" style="">

                <div class="col-lg-6 col-md-6 col-sm-6 hidden-xs" style="padding: 7px;">
                    <label style="margin: 0;">
                        <b class="text-muted">
                            <?php echo $fila['c1']; ?> 
                            <?php echo ($fila['c2'] == "" ? "" : "<span>-</span> " . $fila['c2']); ?>
                        </b>
                        </br>
                        <b style="font-size: 18px;">
                            <?php echo ($fila['c2'] == "" ? "" : $fila['c3']); ?>
                            <?php echo ($fila['c4'] == "" ? "" : "<span>-</span> " . $fila['c4']); ?>
                        </b>
                    </label>
                    <br/>
                    <label style="margin: 0;" class="text-muted">Ticket <span><?php echo $fila['id_ticket']; ?></span></label>
                </div>


                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding: 10px; text-align: end;">
                    <?php
                    $array_funciones = array();

                    if (isset($_SESSION['funciones'])) {
                        $array_funciones = $_SESSION['funciones'];

                        if (in_array("ver_editar_ticket", $array_funciones)) {
                            echo '<button class="btn btn-primary">Editar</button>&nbsp';
                        }

                        echo '<button class="btn btn-primary" data-toggle="modal" data-target="#mdl-tratar-ticket">Tratar</button>&nbsp';

                        if (in_array("ver_solicitar_datos", $array_funciones)) {
                            echo '<button class="btn btn-primary">Solicitar Datos</button>&nbsp';
                        }

                        if (in_array("ver_cerrar_ticket", $array_funciones)) {
                            echo '<button class="btn btn-primary">Cerrar Ticket</button>&nbsp';
                        }

                        if (in_array("ver_asignar_ticket", $array_funciones)) {
                            echo '<button class="btn btn-primary">Asignar</button>';
                        }
                    }
                    ?>

                </div>




                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 alert-success" style="padding: 5px;">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <label style="margin-bottom: 0;"><b class="hidden-xs">Prioridad:</b> <?php echo $fila['prioridad_text']; ?></label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <label style="margin-bottom: 0;"><b class="hidden-xs">Estatus:</b> <?php echo $fila['estatus_name']; ?></label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <label style="margin-bottom: 0;"><b class="hidden-xs">Fecha solicitud:</b> <?php echo date_format(date_create($fila['fecha_registro']), "d/m/Y H:i") ?></label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <label style="margin-bottom: 0;"><b class="hidden-xs">Ultima actualización:</b> <?php echo date_format(date_create($fila['ultima_actualizacion']), "d/m/Y H:i") ?></label>
                    </div>
                </div>



            </div>



            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 panel-detalles-ticket" style="">

                <section class="det-tick">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Datos del Solicitante</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <strong><i class="fa fa-user margin-r-5"></i> Solicitante</strong>
                            <p class="text-muted">
                                <?php echo $fila['solicita']; ?>
                            </p>

                            <strong><i class="fa fa-building margin-r-5"></i> Unidad</strong>
                            <p class="text-muted">
                                <?php echo $fila['unidad']; ?>
                            </p>

                            <strong><i class="fa fa-phone margin-r-5"></i> Teléfono / Ext</strong>
                            <p class="text-muted">
                                <?php echo $fila['telefono']; ?> / <?php echo $fila['extension']; ?>
                            </p>

                            <strong><i class="fa fa-clock-o margin-r-5"></i> Horario contacto</strong>
                            <p class="text-muted">
                                <?php echo $fila['horario']; ?>
                            </p>
                        </div>
                        <!-- /.box-body -->
                    </div>




                    <!--div class="panel panel-default">
                        <div class="panel-heading">
                            <label class="panel-title">Datos de Contacto</label>
                        </div>
                        <div class="panel-body">

                            <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0;">
                                <div class="col-lg-4 col-md-4 col-sm-4 "><label>Solicitante:</label></div> 
                                <div class="col-lg-8 col-md-8 col-sm-8 "><?php //echo $fila['usuario_registra'];  ?></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0;">
                                <div class="col-lg-4 col-md-4 col-sm-4 "><label>Unidad:</label></div> <div class="col-lg-8 col-md-8 col-sm-8 "><?php //echo $fila['unidad'];  ?></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0;">
                                <div class="col-lg-4 col-md-4 col-sm-4"><label>Télefono/Ext:</label></div>
                                <div class="col-lg-8 col-md-8 col-sm-8"><?php //echo $fila['telefono'];  ?> / <?php //echo $fila['extension'];  ?></div>
                            </div> 
                            <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0;">
                                <div class="col-lg-4 col-md-4 col-sm-4"><label>Horario:</label></div>
                                <div class="col-lg-8 col-md-8 col-sm-8"><?php //echo $fila['horario'];  ?></div>
                            </div>
                        </div>
                    </div-->

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Documentos adjuntos</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" id="lista_documentos">
                            <!--strong><i class="fa fa-user margin-r-5"></i> Solicitante</strong-->
                            <!--p class="text-muted">
                            <?php //echo $fila['usuario_registra'];    ?>
                            </p-->

                            <?php
                            $sql = "SELECT url FROM tickets where id_ticket='$id_ticket'";
                            echo $sql;
                            $resultado_documentos = $mysqli->query($sql);
                            while ($filaD = $resultado_documentos->fetch_assoc()) {
                                echo '<p class="text-muted">';
                                echo "<span class='glyphicon glyphicon-paperclip'></span><a href='" . $filaD['url'] . "'>" . $filaD['url'] . "</a> ";
                                echo '</p>';
                            }
                            ?>


                        </div>
                        <!-- /.box-body -->
                    </div>

                    <!--div class="panel panel-default" style="margin-bottom: 80px;">
                        <div class="panel-heading">
                            <label class="panel-title">Adjuntos</label>
                        </div>



                        <div class="panel-body">


                            <div id="lista_documentos" class="col-lg-12">

                    <?php
                    //$sql = "SELECT * FROM documentos where ticket='$id_ticket' and ind_activo=1 order by id_documento desc;";
                    //$resultado_documentos = $mysqli->query($sql);
                    //while ($filaD = $resultado_documentos->fetch_assoc()) {
                    //    echo '<div class="col-lg-12">';
                    //    echo '<span class="glyphicon glyphicon-paperclip"></span> <a href="#" onclick="javascript: ver_documento(' . $filaD['id_documento'] . ')">' . $filaD['file_name'] . '</a>';
                    //    echo '</div>';
                    //}
                    ?>

                            </div>

                        </div>
                    </div-->
                </section>
            </div>



            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-7 note-pan" style="">

                <div class="cont-notas">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Comentarios Ticket</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!--strong><i class="fa fa-file-text-o margin-r-5"></i> </strong-->
                            <p class="text-muted">
                                <?php echo $fila['comentarios']; ?>
                            </p>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!--div class="panel panel-default">
                        <div class="panel-heading">
                            <label>Comentarios</label>
                        </div>
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <p>
                    <?php //echo $fila['comentarios'];     ?>    
                                </p>
                            </div>
                        </div>

                    </div-->


                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Notas</h3>
                        </div>

                        <div class="box-body">
                            <div class="contenedor-notas" style="padding-bottom: 40px;">

                                <?php
                                $sql = "select * from notas n inner join usuarios u on n.usuario_registra=u.id_usuario "
                                        . " where n.ticket='$id_ticket' and n.ind_activo=1 order by n.id_nota desc;";


                                $resultado_notas = $mysqli->query($sql);

                                $file = "";



                                while ($filaN = $resultado_notas->fetch_assoc()) {
                                    (intval($filaN['archivo']) === 1 ? $file = '<span class="pull-right glyphicon glyphicon-paperclip" style="padding-right:10px;"></span>' : $file = "" );

                                    echo '<div class="post">'
                                    . '<div class="user-block">'
                                    . '<span class="username" style="margin-left: 15px;">'
                                    . '<span class="glyphicon glyphicon-user"></span>&nbsp' . $filaN['nombre']
                                    . $file
                                    . '</span>'
                                    . '<span class="description" style="margin-left: 15px;">'
                                    . '<span class="glyphicon glyphicon-time"></span>&nbsp' . date_format(date_create($filaN['fecha_registro']), "d/m/Y H:i")
                                    . '</span>'
                                    . '</div>'
                                    . '<p style="margin-left: 15px; font-size:14px;">'
                                    . $filaN['nota']
                                    . '</p>'
                                    . '</div>';
                                }
                                ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include './resources/modals/modal-tratar-ticket.php'; ?>
            <?php include 'resources/components/footer.php'; ?>

    </body>
</html>