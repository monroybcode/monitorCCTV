<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}

include 'resources/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
include 'utils/constantes.php';
include './utils/funciones.php';
$_SESSION['pag_ant'] = "reporte.php";
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo NOMBRE_SISTEMA; ?></title>
        <?php require 'resources/components/includes.php'; ?>

        <script>
            $(document).ready(function () {
                traer_tabla_reporte_resumen("inicio");
                if ($("#orden_lista").val() === "desc") {
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes-alt");
                    $("#btn_orden_resumen").prop("title", "Cambiar a ascendente");
                } else {
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes-alt");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes");
                    $("#btn_orden_resumen").prop("title", "Cambiar a descendente");
                }
            });

            function actualiza_icono_orden() {
                if ($("#orden_lista").val() === "desc") {
                    $("#orden_lista").val("asc");
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes-alt");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes");
                    $("#btn_orden_resumen").prop("title", "Cambiar a descendente");
                } else {
                    $("#orden_lista").val("desc");
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes-alt");
                    $("#btn_orden_resumen").prop("title", "Cambiar a ascendente");
                }
            }


            function traer_tabla_reporte_resumen(accion) {
                show_loader();
                var comenzar_en = 0;

                if (accion === "inicio") {
                    comenzar_en = 0;
                } else if (accion === "anterior") {
                    comenzar_en = $("#pag_anterior_comienza").val();
                } else if (accion === "siguiente") {
                    comenzar_en = $("#pag_siguiente_comienza").val();
                }

                $.ajax({
                    url: "resources/tablas/tbl-reporte-resumen.php",
                    data: $("#frm-filtros-resumen").serialize() + "&" + $("#frm-bus-ord-resumen").serialize() + "&name=" + getURL("name") + "&comenzar_en=" + comenzar_en,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        $(".container-data-report").html(data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus);
                    },
                    complete: function (jqXHR, textStatus) {
                        hide_loader();
                        if ($("#pag_siguiente").val() === "0") {
                            $(".btn-siguiente-pag").prop("disabled", true);
                        } else if ($("#pag_siguiente").val() === "1") {
                            $(".btn-siguiente-pag").prop("disabled", false);
                        }

                        if ($("#pag_anterior").val() === "0") {
                            $(".btn-anterior-pag").prop("disabled", true);
                        } else if ($("#pag_anterior").val() === "1") {
                            $(".btn-anterior-pag").prop("disabled", false);
                        }

                        $("#num_registros_resultado").html(" - " + $("#num_resultados_tickets").val() + " reportes");

                        $("#mostrando_de_hasta").html("Mostrados: " + $("#mostrando_resultado_inicio").val() + " - " + $("#mostrando_resultado_fin").val());

                    }
                });
            }

            function vista_ticket(ticket) {
                show_loader();
                window.location.href = "ticket.php?ticket=" + ticket + "&pag_ant=reporte.php";
            }
        </script>

    </head>
    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse">
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>

        <div class = "content-wrapper">

            <div class="col-lg-10 col-md-10 col-xs-12 col-sm-12 col-lg-offset-1 col-md-offset-1" style="margin-bottom: 71px;">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form id="frm-bus-ord-resumen" style="min-height: 40px;">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 no-padding">
                                <?php
                                $nr = "";

                                if (isset($_GET['report']) && $_GET['report'] != "") {
                                    $nr = $_GET['report'];
                                    $_SESSION['nr'] = $_GET['report'];
                                } else if (isset($_SESSION['nr']) && $_SESSION['nr'] != "") {
                                    $nr = $_SESSION['nr'];
                                }
                                ?>

                                <b><?php echo $nr; ?></b> <span id="num_registros_resultado"><?php echo " - " . count($_SESSION[$_GET['name']]) . " reportes"; ?></span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 no-padding">
                                <div class = "input-group col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding" id="input-group-busqueda" style = "margin: 5px 0;
                                     padding: 0 16px;
                                     float: left">
                                    <input type = "text" name = "busqueda_resumen" id = "busqueda_resumen" value = "<?php
                                    if (isset($_SESSION['filtro_busqueda_r'])) {
                                        echo $_SESSION['filtro_busqueda_r'];
                                    } else if (isset($_COOKIE['filtro_busqueda_r'])) {
                                        echo $_COOKIE['filtro_busqueda_r'];
                                    }
                                    ?>" class = "form-control input-xs pull-right"/>
                                    <span class = "input-group-btn">
                                        <button class = "btn btn-default btn-xs btn-busqueda-resumen" onclick="traer_tabla_reporte_resumen('inicio');return false;"><span class = "fa fa-search"></span> Buscar</button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding form-inline" style="margin-bottom: 5px; text-align: end;">

                            <b id="mostrando_de_hasta" class="pull-left"></b>

                            <!--<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 no-padding form-inline">-->
                            <!--<div class="input-group col-lg-3 col-md-3 col-sm-3 col-xs-3 col-lg-offset-4 col-md-offset-5 col-sm-offset-4 col-xs-offset-4">-->
                            
                            <button type="button" class="btn btn-link btn-xs" title="Exportar" onclick="exportar_reporte();"><span class="glyphicon glyphicon-save-file" style="font-size: 1.9em;"></span></button>
                            
                            <select class="form-control input-xs" name="orden_resumen" id="orden_resumen">
                                <option value="">-Ordenar por-</option>
                                <option value="t.ultima_actualizacion" <?php
                                if (isset($_SESSION['orden_r']) && $_SESSION['orden_r'] == "t.ultima_actualizacion") {
                                    echo "selected";
                                } else if (isset($_COOKIE['orden_r']) && $_COOKIE['orden_r'] == "t.ultima_actualizacion") {
                                    echo "selected";
                                }
                                ?> >Ultima actualización</option>
                                <option value="t.fecha_registro" <?php
                                if (isset($_SESSION['orden_r']) && $_SESSION['orden_r'] == "t.fecha_registro") {
                                    echo "selected";
                                } else if (isset($_COOKIE['orden_r']) && $_COOKIE['orden_r'] == "t.fecha_registro") {
                                    echo "selected";
                                }
                                ?> >Fecha de creación</option>
                                <option value="t.prioridad" <?php
                                if (isset($_SESSION['orden_r']) && $_SESSION['orden_r'] == "t.prioridad") {
                                    echo "selected";
                                } else if (isset($_COOKIE['orden_r']) && $_COOKIE['orden_r'] == "t.prioridad") {
                                    echo "selected";
                                }
                                ?> >Prioridad</option>
                                <option value="t.categoria_1" <?php
                                if (isset($_SESSION['orden_r']) && $_SESSION['orden_r'] == "t.categoria_1") {
                                    echo "selected";
                                } else if (isset($_COOKIE['orden_r']) && $_COOKIE['orden_r'] == "t.categoria_1") {
                                    echo "selected";
                                }
                                ?> >Categoria</option>
                            </select>
                            <input type="hidden" name="orden_lista" id="orden_lista" value="<?php
                            if (isset($_SESSION['orden_lista_r'])) {
                                echo $_SESSION['orden_lista_r'];
                            } else if (isset($_COOKIE['orden_lista_r'])) {
                                echo $_COOKIE['orden_lista_r'];
                            } else {
                                echo "desc";
                            }
                            ?>">
                            <!--<span class="input-group-btn">-->
                            <button class="btn btn-link btn-sm no-padding" style="font-size: 20px;
                                    " id="btn_orden_resumen" onclick=" actualiza_icono_orden();
                                            traer_tabla_reporte_resumen('inicio');
                                            return false;
                                    "><span class="glyphicon glyphicon-sort-by-attributes-alt icono-orden-lista" style="padding-left: 5px;"></span></button>
                            <!--</span>-->
                            <!--</div>-->
                            &nbsp;&nbsp;
                            <!--<div class="col-lg-5 col-md-4 col-sm-5 col-xs-5 form-inline pull-right no-padding" style="text-align: end;">-->

                            <span>Resultados por pagina:</span>
                            <select class="form-control input-xs" onchange="traer_tabla_reporte_resumen('inicio');" name="resultados_por_pagina" id="resultados_por_pagina" style="width: 100px;">
                                <option value="10" <?php
                                if (isset($_SESSION['res_pag_reporte']) && $_SESSION['res_pag_reporte'] === "10") {
                                    echo "selected";
                                } else if (isset($_COOKIE['res_pag_reporte']) && $_COOKIE['res_pag_reporte'] === "10") {
                                    echo "selected";
                                }
                                ?> >10</option>
                                <option value="20" <?php
                                if (isset($_SESSION['res_pag_reporte']) && $_SESSION['res_pag_reporte'] === "20") {
                                    echo "selected";
                                }else if (isset($_COOKIE['res_pag_reporte']) && $_COOKIE['res_pag_reporte'] === "20") {
                                    echo "selected";
                                }
                                ?> >20</option>
                                <option value="50" <?php
                                if (isset($_SESSION['res_pag_reporte']) && $_SESSION['res_pag_reporte'] === "50") {
                                    echo "selected";
                                }else if (isset($_COOKIE['res_pag_reporte']) && $_COOKIE['res_pag_reporte'] === "50") {
                                    echo "selected";
                                }
                                ?> >50</option>
                                <option value="100" <?php
                                if (isset($_SESSION['res_pag_reporte']) && $_SESSION['res_pag_reporte'] === "100") {
                                    echo "selected";
                                }else if (isset($_COOKIE['res_pag_reporte']) && $_COOKIE['res_pag_reporte'] === "100") {
                                    echo "selected";
                                }
                                ?> >100</option>
                                <option value="200" <?php
                                if (isset($_SESSION['res_pag_reporte']) && $_SESSION['res_pag_reporte'] === "200") {
                                    echo "selected";
                                }else if (isset($_COOKIE['res_pag_reporte']) && $_COOKIE['res_pag_reporte'] === "200") {
                                    echo "selected";
                                }
                                ?>>200</option>
                            </select>
                            &nbsp;

                            <button class = "btn btn-default btn-xs btn-anterior-pag" onclick="traer_tabla_reporte_resumen('anterior');
                                    return false;
                                    "><span aria-hidden="true">&laquo;</span></button>

                            <button class = "btn btn-default btn-xs btn-siguiente-pag" onclick="traer_tabla_reporte_resumen('siguiente');
                                    return false;
                                    "><span aria-hidden="true">&raquo;</span></button>

                            <!--</div>-->
                            <!--</div>-->


                        </div>
                    </form>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                        <form id="frm-filtros-resumen" class="form-inline col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding" style="text-align: end;
                              ">
                            <!--select class="form-control input-xs" name="filtro_estatus_resumen" id="filtro_estatus_resumen">
                                <option value="">-Estatus-</option>                            
                                <?php
                               /* $sql = "select id, descripcion, catalogo from catalogo_valor where catalogo=2;";
                                $resultSet = $mysqli->query($sql);

                                while ($fila = $resultSet->fetch_assoc()) {
                                    if (isset($_SESSION['filtro_estatus_r']) && $_SESSION['filtro_estatus_r'] == $fila['id']) {
                                        echo "<option value='" . $fila['id'] . "'>" . $fila['descripcion'] . "</option>";
                                    } else if (isset($_COOKIE['filtro_estatus_r']) && $_COOKIE['filtro_estatus_r'] == $fila['id']) {
                                        echo "<option value='" . $fila['id'] . "'>" . $fila['descripcion'] . "</option>";
                                    } else {
                                        echo "<option value='" . $fila['id'] . "'>" . $fila['descripcion'] . "</option>";
                                    }
                                }*/
                                ?>
                            </select-->
                            <select class="form-control input-xs" name="filtro_categoria_resumen" id="filtro_categoria_resumen">
                                <option value="">-Categoria-</option>
                                <?php
                                $sql = "select * from categoria where esta_activo=1 and tipo_categoria = 1;";
                                $resultSet = $mysqli->query($sql);

                                while ($fila = $resultSet->fetch_assoc()) {
                                    if (isset($_SESSION['filtro_categoria_r']) && isset($_SESSION['filtro_categoria_r']) == $fila['id']) {
                                        echo "<option value='" . $fila['id'] . "' >" . $fila['nombre'] . "</option>";
                                    } else if ($_COOKIE['filtro_categoria_r'] && $_COOKIE['filtro_categoria_r'] == $fila['id']) {
                                        echo "<option value='" . $fila['id'] . "' >" . $fila['nombre'] . "</option>";
                                    } else {
                                        echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <select class="form-control input-xs" name="filtro_solicitante_resumen" id="filtro_solicitante_resumen">
                                <option value="">-Solicitante-</option>
                                <?php
                                $sql = "select * from usuarios where ind_activo=1;";
                                $resultSet = $mysqli->query($sql);

                                while ($fila = $resultSet->fetch_assoc()) {
                                    if (isset($_SESSION['filtro_solicitante_r']) && $_SESSION['filtro_solicitante_r'] == $fila['id_usuario']) {
                                        echo "<option value='" . $fila['id_usuario'] . "' >" . $fila['nombre'] . "</option>";
                                    } else if (isset($_COOKIE['filtro_solicitante_r']) && $_COOKIE['filtro_solicitante_r'] == $fila['id_usuario']) {
                                        echo "<option value='" . $fila['id_usuario'] . "' >" . $fila['nombre'] . "</option>";
                                    } else {
                                        echo "<option value='" . $fila['id_usuario'] . "'>" . $fila['nombre'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <select class="form-control input-xs" name="filtro_prioridad_resumen" id="filtro_prioridad_resumen">
                                <option value="">-Prioridad-</option>
                                <?php
                                $sql = "select id, descripcion, catalogo from catalogo_valor where catalogo=4;";
                                $resultSet = $mysqli->query($sql);

                                while ($fila = $resultSet->fetch_assoc()) {
                                    if (isset($_SESSION['filtro_prioridad_r']) && $_SESSION['filtro_prioridad_r'] == $fila['id']) {
                                        echo "<option value='" . $fila['id'] . "' >" . $fila['descripcion'] . "</option>";
                                    } else if (isset($_COOKIE['filtro_prioridad_r']) && $_COOKIE['filtro_prioridad_r'] == $fila['id']) {
                                        echo "<option value='" . $fila['id'] . "' >" . $fila['descripcion'] . "</option>";
                                    } else {
                                        echo "<option value='" . $fila['id'] . "'>" . $fila['descripcion'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            &nbsp;&nbsp;
                            <span class="glyphicon glyphicon-calendar"></span> De
                            <input type="date" value="<?php
                            if (isset($_SESSION['filtro_fecha_1_r']) && $_SESSION['filtro_fecha_1_r'] != "") {
                                echo $_SESSION['filtro_fecha_1_r'];
                            } else if (isset($_COOKIE['filtro_fecha_1_r']) && $_COOKIE['filtro_fecha_1_r'] != "") {
                                echo $_COOKIE['filtro_fecha_1_r'];
                            } else {
                                echo "";
                            }
                            ?>" class="form-control input-xs" name="filtro_fecha_1_resumen" id="filtro_fecha_1_resumen"> 
                            &nbsp;a &nbsp;
                            <input type="date" value="<?php
                            if (isset($_SESSION['filtro_fecha_2_r']) && $_SESSION['filtro_fecha_2_r'] != "") {
                                echo $_SESSION['filtro_fecha_2_r'];
                            } else if (isset($_COOKIE['filtro_fecha_2_r']) && $_COOKIE['filtro_fecha_2_r'] != "") {
                                echo $_COOKIE['filtro_fecha_2_r'];
                            } else {
                                echo "";
                            }
                            ?>" class="form-control input-xs" name="filtro_fecha_2_resumen" id="filtro_fecha_2_resumen">

                            <button class="btn btn-link btn-aplica-filtros-resumen btn-sm no-padding" onclick="traer_tabla_reporte_resumen('inicio');
                                    return false;
                                    " type="submit" style="font-size: 20px;
                                    " title="Aplicar filtros"><span class="glyphicon glyphicon-filter"></span></button>



                        </form>
                    </div>
                </div>

                <div class="container-data-report col-lg-12 col-md-12 col-sm-12 col-xs-12">


                </div>

            </div>

        </div>

        <?php include 'resources/components/footer.php'; ?>
    </body>
</html>
