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
        <script src="resources/js/alta-ticket.js?<?php echo VERSION ?>"></script>

        <script>
            $(document).ready(function (e) {
                remove_class_navbar();
                $(".link-nuevo-ticket").addClass("active");
            });
        </script>

    </head>
    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse">
        <?php include 'utils/funciones.php'; ?>
        <?php include 'resources/components/notificaciones.php'; ?>

        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>
        <?php include 'resources/components/sidebar.php'; ?>

        <div class="content-wrapper">


            <div class="panel-form-alta-ticket col-lg-7 col-lg-offset-2 col-md-7 col-md-offset-2">


                <div class="panel-body">
                    <h3 style="padding-left: 30px; margin-bottom: 0;" class="no-margin">Nuevo Reporte</h3>

                    <div class="row">
                        <section>
                            <div class="wizard">
                                <div class="wizard-inner">
                                    <!--<div class="connecting-line" style="border: solid 1px #b1b1b1; width: 35%; position: absolute; top: 135px; margin: 0 auto; margin-left: 24%;"></div>-->
                                    <ul class="nav nav-tabs" role="tablist" style="background-image: url(resources/images/horizontal_line1600.png); background-size: 98% 100%; background-repeat: no-repeat">

                                        <li role="presentation" class="active" id="t-step1">
                                            <a href="#" data-toggle="tab" aria-controls="step1" role="tab">
                                                <span class="round-tab">
                                                    1
                                                </span>
                                            </a>
                                        </li>

                                        <li role="presentation" id="t-step2">
                                            <a href="#" data-toggle="tab" aria-controls="step2" role="tab">
                                                <span class="round-tab">
                                                    2
                                                </span>
                                            </a>
                                        </li>

                                        <li role="presentation" id="t-step3">
                                            <a href="#" data-toggle="tab" aria-controls="complete" role="tab">
                                                <span class="round-tab">
                                                    3
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <form class="col-lg-8 col-lg-offset-2 form-alta-ticket" role="form" id="form-alta-ticket" enctype="multipart/form-data">
                                    <div class="tab-content">
                                        <div class="tab-pane active content-panel-form" role="tabpanel" id="step1">
                                            <h4>Detalles generales</h4>
                                            <br/>

                                            <div class="form-group">
                                                <select class="form-control" id="categoria_1" name="categoria_1">
                                                    <option value="">Categoria 1</option>
                                                    <?php consulta_categorias_asignadas_usuario(); ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control" id="categoria_2" name="categoria_2" style="display: none">
                                                    <option value="">Categoria 2</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control" id="categoria_3" name="categoria_3" style="display: none">
                                                    <option value="">Categoria 3</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control" id="categoria_4" name="categoria_4" style="display: none">
                                                    <option value="">Categoria 4</option>
                                                </select>
                                            </div>



                                            <div class="form-group">
                                                <button style="float: right" onclick="panel2();
                                                        return false" class="btn btn-primary next-step">Siguiente</button>
                                            </div>
                                        </div>

                                        <div class="tab-pane content-panel-form" role="tabpanel" id="step2">
                                            <h4>Datos adicionales</h4>
                                            <br/>

                                            <div class="form-group">
                                                <textarea class="form-control" placeholder="Comentarios*" rows="5" id="comentarios" name="comentarios"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="archivos_ticket" class="btn btn-default btn-sm">Elegir Archivos</label> 
                                                <span class="num_archivos_sel">Ningun archivo seleccionado</span>
                                                <input type="file" class="hide" id="archivos_ticket" name="archivos_ticket[]" multiple>
                                                <div class="files-names text-muted" style="padding: 0 10px;">

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button style="float: left" onclick="panel1();
                                                        return false;" class="btn btn-primary">Anterior</button>
                                                <button style="float: right" onclick="panel3();
                                                        return false;" class="btn btn-primary">Siguiente</button>
                                            </div>
                                        </div>

                                        <div class="tab-pane content-panel-form" role="tabpanel" id="step3">
                                            <h4>Datos de contacto</h4>
                                            <br/>

                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Nombre de solicitante" disabled="true" name="nombre_contacto" id="nombre_contacto" value="<?php echo $_SESSION['nombre']; ?>">
                                            </div>

                                            <div class="form-group">

                                                <?php
                                                $sql = "select * from hospital h inner join usuario_hospital uh on h.id=uh.hospital where uh.usuario='" . $_SESSION['id_usuario'] . "';";
                                                $resultSet = $mysqli->query($sql);

                                                $num_hos = $resultSet->num_rows;

                                                if ($num_hos === 1) {
                                                    $fila = $resultSet->fetch_assoc();
                                                    echo '<select class="form-control" name="unidad_organizacional" id="unidad_organizacional" readonly="true">';
                                                    echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
                                                    echo '</select>';

                                                    echo "<script>consultaNumTel('" . $fila['id'] . "');</script>";
                                                } else if ($num_hos > 1) {

                                                    echo '<select class="form-control" name="unidad_organizacional" id="unidad_organizacional" onchange="javascript:consultaNumTel(this.value);">';
                                                    echo '<option value="">Unidad Organizacional</option>';

                                                    while ($fila = $resultSet->fetch_assoc()) {
                                                        echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
                                                    }

                                                    echo '</select>';
                                                } else {
                                                    echo '<select class="form-control" name="unidad_organizacional" id="unidad_organizacional" readonly="true">';
                                                    echo '<option value="0">Sin Unidad Organizacional</option>';
                                                    echo '</select>';
                                                }
                                                ?>

                                            </div>

                                            <div class="form-group">
                                                <?php
                                                $sql = "select ug.id_grupo, g.nombre from usuario_grupos ug inner join grupos g on ug.id_grupo=g.id_grupo where ug.id_usuario='" . $_SESSION['id_usuario'] . "';";
                                                $resultSet = $mysqli->query($sql);

                                                $num_grupos = $resultSet->num_rows;

                                                if ($num_grupos === 1) {
                                                    $fila = $resultSet->fetch_assoc();
                                                    echo '<select class="form-control" name="grupo" id="grupo" readonly="true">';
                                                    echo "<option value='" . $fila['id_grupo'] . "'>" . $fila['nombre'] . "</option>";
                                                    echo '</select>';
                                                } else if ($num_grupos > 1) {
                                                    echo '<select class="form-control" name="grupo" id="grupo">';
                                                    echo '<option value="">Grupo</option>';

                                                    while ($fila = $resultSet->fetch_assoc()) {
                                                        echo "<option value='" . $fila['id_grupo'] . "'>" . $fila['nombre'] . "</option>";
                                                    }

                                                    echo '</select>';
                                                } else {
                                                    echo '<select class="form-control" name="grupo" id="grupo" readonly="true">';
                                                    echo '<option value="0">Sin Grupo</option>';
                                                    echo '</select>';
                                                }
                                                ?>
                                            </div>

                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Teléfono" name="telefono_contacto" id="telefono_contacto" disabled="true">
                                            </div>

                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Extensión" name="extension_contacto" id="extension_contacto">
                                            </div>

                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Horario de contacto*" name="horario_contacto" id="horario_contacto">
                                            </div>


                                            <div class="form-group">
                                                <button style="float: left" onclick="panel2();
                                                        return false;" class="btn btn-primary">Anterior</button>
                                                <button style="float: right" class="btn btn-primary" id="btn-guarda-ticket">Guardar</button>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>






                </div>
            </div>



        </div>



        <?php include 'resources/components/footer.php'; ?>

    </body>
</html>