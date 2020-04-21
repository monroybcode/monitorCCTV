<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}
include 'resources/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
include 'utils/constantes.php';
date_default_timezone_set('America/Mexico_City');
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo NOMBRE_SISTEMA; ?></title>




        <?php require 'resources/components/includes.php'; ?>
        <script src="resources/js/alta-ticket.js?<?php echo VERSION ?>"></script>

        <script>
            $(document).ready(function (e) {


                $('.js-example-basic-single').select2();
                remove_class_navbar();

                $(".link-nuevo-ticket").css("background-color", "rgb(52,96,148)");

                $("#evento").on('change', function () {
                    $("#evento option:selected").each(function () {
                        var elegido = $(this).val();
                        $.post("resources/controller/eventos.php", {elegido: elegido}, function (data) {
                            $("#categoria").html(data);
                        });
                    });
                });


                var tagApi = $(".tm-input").tagsManager();


                jQuery(".typeahead").typeahead({
                    name: 'tags',
                    displayKey: 'name',
                    source: function (query, process) {
                        return $.get("resources/controller/traer-email.php", {query: query}, function (data) {
                            data = $.parseJSON(data);
                            return process(data);
                        });
                    },
                    afterSelect: function (item) {
                        tagApi.tagsManager("pushTag", item);
                    }
                });


                var tagApi_asignado = $(".tm-input-asignado").tagsManager();


                jQuery(".typeahead2").typeahead({
                    name: 'tags',
                    displayKey: 'name',
                    source: function (query, process) {
                        return $.get("resources/controller/traer-email.php", {query: query}, function (data) {
                            data = $.parseJSON(data);
                            return process(data);
                        });
                    },
                    afterSelect: function (item) {
                        tagApi_asignado.tagsManager("pushTag", item);
                    }
                });




            });

            function noPuntoComa(event) {

                var e = event || window.event;
                var key = e.keyCode || e.which;
                

                if (key === 110 || key === 190 || key === 188 || key === 13) {

                    e.preventDefault();
                }
            }




        </script>



        <script>
            $(function () {


                $('#prioridad').on('change', function () {
                    if ($("#evento").val() !== '' && $("#area").val() !== '' && $("#categoria").val() !== '' && $("#unidad_organizacional").val() !== '')
                    {

                        $.ajax({
                            type: "POST",
                            url: "resources/controller/traer-director.php",
                            data: "evento=" + $("#evento").val() + "&area=" + $("#area").val() + "&unidad=" + $("#unidad_organizacional").val(),
                            success: function (data) {

                                var datos = JSON.parse(data);

                                var correo_director = datos[0].nombre + "(" + datos[0].email + ")";

                                console.log(correo_director);
                                $("#director").val(correo_director);

                            }, error: function () {
                                alert("Hubo un Problema");
                            }


                        });




                        $.ajax({
                            type: "POST",
                            url: "resources/controller/traer-email2.php",
                            data: "evento=" + $("#evento").val() + "&area=" + $("#area").val() + "&unidad=" + $("#unidad_organizacional").val(),
                            success: function (data) {

                                var datos = JSON.parse(data);
                                var correos = '';

                                var tagApi_asignado = $(".tm-input-asignado").tagsManager();
                                var tagApi = $(".tm-input").tagsManager();


                                for (var i = 0; i <= datos.length; i++) {

                                    if (datos[i].tipo === '5') {

                                        var correo_asignado = datos[i].nombre + " (" + datos[i].email + ")";
                                        tagApi_asignado.tagsManager("pushTag", correo_asignado);

                                    } else
                                    if (datos[i].tipo === '10')
                                    {
                                        correos = datos[i].nombre + " (" + datos[i].email + ")" + ",";
                                        tagApi.tagsManager("pushTag", correos);
                                    } else
                                    {


                                    }


                                }


                            }, error: function () {

                                alert("Hubo un Problema");

                            }


                        });

                    }
                });
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
                    <!--h3 style="padding-left: 30px; padding-bottom: 15px;" class="no-margin">Nuevo Evento</h3-->
                    <div class="row">
                        <span class="fa fa-plus" style="color: rgb(46, 85, 151);"></span><span style="color: rgb(46, 85, 151);font-size: 12pt;">Nuevo Reporte</span>
                    </div>
                    <div class="row">
                        <section>
                            <form class="col-lg-8 col-lg-offset-2 form-alta-ticket" role="form" id="form-alta-ticket" enctype="multipart/form-data">
                                <div class="tab-content">
                                    <div class="tab-pane active content-panel-form" role="tabpanel">
                                        <!--div class="box-header box-head-data" style="margin: 30px 0 15px 0;">
                                            <label class="box-title title-box-ticket titulo-categoria-ticket">Detalles generales &nbsp;</label>
                                        </div-->
                                        <div class="" style="margin: 10px 0 10px 0; border-bottom: 1.5pt #4b4e53 solid;">
                                            <span class="box-title title-box-ticket titulo-categoria-ticket">Detalles generales</span>
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
                                                echo '<option value=""> - Seleccione Unidad Hospitalaria - </option>';

                                                while ($fila = $resultSet->fetch_assoc()) {
                                                    echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
                                                }

                                                echo '</select>';
                                            } else {
                                                echo '<select class="form-control" name="unidad_organizacional" id="unidad_organizacional" readonly="true">';
                                                echo '<option value="0"> - Sin Unidad Hospitalaria - </option>';
                                                echo '</select>';
                                            }
                                            ?>

                                        </div>

                                        <div>

                                            <div class="form-group">
                                                <?php
                                                $sql3 = "SELECT * FROM areas where bandera = 1;";

                                                $resultSet3 = $mysqli->query($sql3);

                                                $evento3 = $resultSet3->num_rows;

                                                if ($evento3 > 1) {

                                                    echo '<select class="form-control" name="area" id="area">';
                                                    echo '<option value=""> - Seleccione Area - </option>';

                                                    while ($fila3 = $resultSet3->fetch_assoc()) {
                                                        echo "<option value='" . $fila3['idareas'] . "'>" . $fila3['nombre_area'] . "</option>";
                                                    }

                                                    echo '</select>';
                                                }
                                                ?>

                                            </div>


                                            <div class="form-group">

                                                <?php
                                                $sql2 = "SELECT * FROM categoria where categoria_padre = '0' or categoria_padre is null;";

                                                $resultSet2 = $mysqli->query($sql2);

                                                $evento2 = $resultSet2->num_rows;

                                                if ($evento2 > 1) {

                                                    echo '<select class="form-control js-example-basic-single" name="evento" id="evento">';
                                                    echo '<option value=""> - Seleccione Evento - </option>';

                                                    while ($fila2 = $resultSet2->fetch_assoc()) {
                                                        echo "<option value='" . $fila2['id'] . "'>" . $fila2['nombre'] . "</option>";
                                                    }

                                                    echo '</select>';
                                                }
                                                ?>

                                            </div>


                                            <div class="form-group" hidden="true">
                                                <select class="form-control" name="categoria" id="categoria" >
                                                    <option value=""> - Seleccione Categoria - </option>
                                                </select>

                                            </div>



                                            <div class="form-group">
                                                <select class="form-control" name="prioridad" id="prioridad">
                                                    <option value=""> - Seleccione Prioridad - </option>

                                                    <option value='1'>Alta</option>
                                                    <option value='2'>Media</option>
                                                    <option value='3'>Baja</option>


                                                </select>


                                            </div>

                                            <div class="form-group col-lg-12">
                                                <div class="col-md-6">
                                                    <label>Inicio Evento</label>
                                                    <input style="font-size: 11px" type="datetime-local" class="form-control" placeholder="Fecha hora evento" name="fecha_hora" id="Fecha_hora" value="<?php echo date("Y-m-d\TH:i:s"); ?>">
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Termino Evento</label>
                                                    <input style="font-size: 11px" type="datetime-local" class="form-control" placeholder="Fecha hora evento" name="fecha_termino" id="Fecha_termino" value="<?php echo date("Y-m-d\TH:i:s"); ?>">
                                                </div>
                                                <!--</div>--> 
                                            </div>


                                            <div class="form-group">

                                                <!--</div>--> 
                                            </div>



                                            <div class="" style="margin: 20px 0 10px 0; border-bottom: 1.5pt #4b4e53 solid;">
                                                <span class="box-title title-box-ticket titulo-categoria-ticket">Datos adicionales &nbsp;</span>
                                                <span id="spnInfoAyudaT" class="fa fa-info-circle text-right" style="display: none; color: rgb(237,194,76)"></span>
                                            </div>


                                            <div>
                                                <div class="form-group">
                                                    <textarea class="form-control" placeholder="Comentarios*" rows="5" id="comentarios" name="comentarios"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label>Ej: http://www.starmedica.com</label>
                                                    <input type="text" class="form-control" placeholder="Url Drive" name="url" id="url"  required>
                                                </div>
                                            </div>


                                            <div class="" style="margin: 20px 0 10px 0; border-bottom: 1.5pt #4b4e53 solid;">
                                                <span class="box-title title-box-ticket titulo-categoria-ticket">Director Hospital &nbsp;</span>
                                                <span id="spnInfoAyudaT" class="fa fa-info-circle text-right" style="display: none; color: rgb(237,194,76)"></span>
                                            </div>

                                            <div class="form-group">
                                                <input type="text" class="form-control" name="director" id="director" readonly>
                                            </div>




                                            <div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Nombre de solicitante" disabled="true" name="nombre_contacto" id="nombre_contacto" value="<?php echo $_SESSION['nombre']; ?>" style="display: none">
                                                </div>


                                                <div class="form-group">
                                                    <input id="grupo" name="grupo" type="hidden" />
                                                </div>

                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Email" name="Email" id="Email" value="<?php echo $_SESSION['usr_email'] ?>"  disabled style="display: none">
                                                </div>


                                                <div class="form-group" style="margin: 20px 0 10px 0; border-bottom: 1.5pt #4b4e53 solid;">
                                                    <span class="box-title title-box-ticket titulo-categoria-ticket">Responsable(s): &nbsp;</span>
                                                    <span id="spnInfoAyudaT" class="fa fa-info-circle text-right" style="display: none; color: rgb(237,194,76)"></span>
                                                </div>


                                                <div class="form-group">
                                                    <input type="text" class="typeahead2 tm-input-asignado form-control tm-input-info" name="tags" id="tags" size="65" style="position: relative" onkeydown="noPuntoComa(event)">
                                                </div> 


                                                <div class="form-group" style="margin: 20px 0 10px 0; border-bottom: 1.5pt #4b4e53 solid;">
                                                    <span class="box-title title-box-ticket titulo-categoria-ticket">Con Copia a: &nbsp;</span>
                                                    <span id="spnInfoAyudaT" class="fa fa-info-circle text-right" style="display: none; color: rgb(237,194,76)"></span>
                                                </div>

                                                <div class="form-group">
                                                    <input type="text" class="typeahead tm-input form-control tm-input-info" name="tags2" id="tags2" size="65" style="position: relative" onkeydown="noPuntoComa(event)">
                                                </div> 

                                                <input type="text"  name="idusuario" id="idusuario" value="<?php echo $_SESSION['id_usuario'] ?>" hidden="true">

                                            </div>
                                            <br><br>
                                            <div class="form-group">
                                                <button type="button" style="float: right" class="btn btn-primary" id="btn-crear-ticket">Crear</button>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'resources/components/footer.php'; ?>
    </body>


</html>