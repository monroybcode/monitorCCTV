<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}

include 'resources/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
include 'utils/constantes.php';

$_SESSION['ticket_vista'] = 'tickets';
$_SESSION['pag_ant']='tickets.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo NOMBRE_SISTEMA; ?></title>

        <?php require 'resources/components/includes.php'; ?>

        <script>
            $(document).ready(function (e) {
                remove_class_navbar();
                $(".link-principal").addClass("active");

                if ($("#orden_lista").val() === "desc") {
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes-alt");
                    $("#btn_orden_lista").prop("title", "Ascendente");
                } else {
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes-alt");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes");
                    $("#btn_orden_lista").prop("title", "Descendente");
                }
            });

            function ordenar_lista() {
                if ($("#orden_lista").val() === "desc") {
                    $("#orden_lista").val("asc");
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes-alt");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes");
                    $("#btn_orden_lista").prop("title", "Ascendente");
                } else {
                    $("#orden_lista").val("desc");
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes-alt");
                    $("#btn_orden_lista").prop("title", "Descendente");
                }
                aplica_filtros("inicio");
            }

            function cargar_vista() {
                $.ajax({
                    url: "resources/tablas/tbl-tickets.php",
                    data: $("#search-filter").serialize() + "&" + $("#frm-ordenar").serialize(),
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        $("#panel-show-tbl-tickets").html(data);
                        $("#tot-tick").html("- " + $("#num_tickets").val() + " tickets");
                    }
                });
            }

            function aplica_filtros(accion, nv) {
                show_loader();
                var comenzar_en = 0;

                if (accion === "inicio") {
                    comenzar_en = 0;
                } else if (accion === "anterior") {
                    comenzar_en = $("#pag_anterior_comienza").val();
                } else if (accion === "siguiente") {
                    comenzar_en = $("#pag_siguiente_comienza").val();
                }


                var data = $("#search-filter").serialize() + "&" + $("#frm-ordenar").serialize();

                $.ajax({
                    url: "resources/tablas/tbl-tickets.php",
                    data: data + "&nv=" + nv + "&comenzar_en=" + comenzar_en,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        $("#panel-show-tbl-tickets").html(data);
                        $("#tot-tick").html("- " + $("#num_tickets").val() + " tickets");
//                        hide_loader();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus);
                        hide_loader();
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

                        $("#num_registros_resultado").html(" - " + $("#num_resultados_tickets").val() + " tickets");

                        $("#mostrando_de_hasta").html("Mostrados: " + $("#mostrando_resultado_inicio").val() + " - " + $("#mostrando_resultado_fin").val());

                    }
                });
            }

            function vista_ticket(ticket) {
                window.location.href = "ticket.php?ticket=" + ticket;
            }

            function muestra_tickets_cat(objeto, nv, cat) {
                $(".nav-filtros-ticket").children("li").removeClass("active")
                $(objeto).parent("li").addClass("active");
                aplica_filtros("inicio", nv);
                $(".categoria").html(cat);
            }


        </script>

    </head>
    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse" onload="aplica_filtros('inicio')">

        <?php include 'utils/funciones.php'; ?>
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>
        <?php // include 'resources/components/sidebar.php'; ?>

        <div class="content-wrapper" style="margin-top: 0;">

            <div class="col-lg-12 no-padding" style="background-color: #d1c7be;">
                <?php include './resources/components/sub-navbar.php'; ?>
            </div>

            <div class="col-lg-10 col-xs-12 col-md-10 col-sm-12 container-detalles-ticket col-lg-offset-1 col-md-offset-1" style="margin-bottom: 70px; margin-top: 10px;">

                <div class="" style="border-radius: 0;">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                        <div class="col-lg-5 col-md-5 col-sm-5 no-padding" style="margin-bottom: 5px;">
                            <h3 style="padding: 0 5px;" class="no-margin">
                                <span class="categoria">
                                    <?php
                                    if (isset($_SESSION['nv'])) {
                                        switch ($_SESSION['nv']) {
                                            case 1:
                                                echo "En grupo ";
                                                break;
                                            case 2:
                                                echo "Reportados por mi";
                                                break;
                                            case 2:
                                                echo "Asignadas a mi";
                                                break;
                                            case 4:
                                             //   echo "Asignadas a mi grupo";
                                                break;
                                            case 5:
                                                echo "Resueltas por mi";
                                                break;
                                            case 6:
                                              //  echo "Reportados por mi grupo";
                                                break;
                                            case 7:
                                                echo "Todos";
                                                break;
                                            default :
                                                echo NOMBRE_SISTEMA;
                                                break;
                                        }
                                    } else {
                                        echo NOMBRE_SISTEMA;
                                    }
                                    ?>
                                </span>
                                <span class="text-muted h4" id="tot-tick">

                                </span>
                            </h3>
                        </div>


                        <div class="col-lg-7 col-md-7 col-sm-7" style="margin-bottom: 5px; padding: 0 5px;">
                            <form class="form-inline no-margin pull-right" id="frm-ordenar">
                                <span class="hidden-sm hidden-xs">Ordenar por:</span>
                                <select class="form-control form-inline input-xs" name="orden" id="orden">
                                    <option value="t.ultima_actualizacion" <?php
                                    if (isset($_SESSION['orden']) && $_SESSION['orden'] == "t.ultima_actualizacion") {
                                        echo "selected";
                                    } else if (isset($_COOKIE['orden']) && $_COOKIE['orden'] == "t.ultima_actualizacion") {
                                        echo "selected";
                                    }
                                    ?> >Ultima actualización</option>
                                    <option value="t.fecha_registro" <?php
                                    if (isset($_SESSION['orden']) && $_SESSION['orden'] == "t.fecha_registro") {
                                        echo "selected";
                                    } else if (isset($_COOKIE['orden']) && $_COOKIE['orden'] == "t.fecha_registro") {
                                        echo "selected";
                                    }
                                    ?> >Fecha de creación</option>
                                    <option value="t.prioridad" <?php
                                    if (isset($_SESSION['orden']) && $_SESSION['orden'] == "t.prioridad") {
                                        echo "selected";
                                    } else if (isset($_COOKIE['orden']) && $_COOKIE['orden'] == "t.prioridad") {
                                        echo "selected";
                                    }
                                    ?> >Prioridad</option>
                                    <option value="t.unidad_negocio" <?php
                                    if (isset($_SESSION['orden']) && $_SESSION['orden'] == "t.unidad_negocio") {
                                        echo "selected";
                                    } else if (isset($_COOKIE['orden']) && $_COOKIE['orden'] == "t.unidad_negocio") {
                                        echo "selected";
                                    }
                                    ?> >Hospital</option>
                                    <option value="t.categoria_1" <?php
                                    if (isset($_SESSION['orden']) && $_SESSION['orden'] == "t.categoria_1") {
                                        echo "selected";
                                    } else if (isset($_COOKIE['orden']) && $_COOKIE['orden'] == "t.categoria_1") {
                                        echo "selected";
                                    }
                                    ?> >Categoria</option>
                                </select>

                                <input type="hidden" name="orden_lista" id="orden_lista" value="<?php
                                if (isset($_SESSION['orden_lista'])) {
                                    echo $_SESSION['orden_lista'];
                                } else if (isset($_COOKIE['orden_lista'])) {
                                    echo $_COOKIE['orden_lista'];
                                } else {
                                    echo "desc";
                                }
                                ?>">

                                <button class="btn btn-link btn-sm no-padding" style="font-size: 20px;" id="btn_orden_lista" onclick="ordenar_lista();
                                        return false;"><span class=" glyphicon glyphicon-sort-by-attributes-alt icono-orden-lista"></span></button>

                                &nbsp;&nbsp;

                                <span class="hidden-xs hidden-sm hidden-md">Resultados por pagina:</span>
                                <select class="form-control input-xs" onchange="aplica_filtros('inicio');" name="resultados_por_pagina" id="resultados_por_pagina" style="width: 80px;">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                </select>
                                &nbsp;

                                <button class = "btn btn-default btn-xs btn-anterior-pag" onclick="aplica_filtros('anterior');
                                        return false;
                                        "><span aria-hidden="true">&laquo;</span></button>

                                <button class = "btn btn-default btn-xs btn-siguiente-pag" onclick="aplica_filtros('siguiente');
                                        return false;
                                        "><span aria-hidden="true">&raquo;</span></button>



                            </form>


                        </div>

                        <!--                        <div style="height: 30px; margin-top: 5px;" class="pull-right col-lg-7">
                        
                                                </div>-->

                    </div>

                    <div class="panel-heading panel-filtros no-padding" style="text-align: end;">
                        <?php include './resources/components/filtros.php'; ?>
                    </div>


                    <div class="panel-body panel-tickets-list" id="panel-show-tbl-tickets">

                    </div>

                </div>
            </div>

        </div>

        <?php include 'resources/components/footer.php'; ?>

    </body>
</html>
