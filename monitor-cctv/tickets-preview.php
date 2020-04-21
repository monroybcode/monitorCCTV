<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location:index.php");
}

include 'resources/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");
include 'utils/constantes.php';

$_SESSION['ticket_vista'] = 'tickets-preview';
$_SESSION['pag_ant'] = 'tickets-preview.php';
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

                $(".scroll-list-tick").slimScroll({height: "500px"});
                $("#panel_preview").slimScroll({height: "100%"});

                if ($("#orden_lista").val() === "desc") {
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes-alt");
                    $("#btn_orden_lista").prop("title", "Cambiar a ascendente");
                } else {
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes-alt");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes");
                    $("#btn_orden_lista").prop("title", "Cambiar a descendente");
                }
                switch_turn();
            });

            function switch_turn() {
                if ($("#switch").val().trim() === "off") {
                    $("#panel-tickets-list-p").css("height", "auto");
                    $(".col-stts").removeClass("no-padding");

                    $("#accion_ticket").val("2");
                    $(".col-preview").removeClass("in");
                    $(".col-tickets").animate({width: '100%'}, 500);
                    $(".icon-svp").addClass("fa-toggle-off");
                    $(".icon-svp").removeClass("fa-toggle-on");
                    $(".btn-show-preview").prop("title", "Mostrar vista previa");
                    $(".scroll-list-tick").slimScroll({destroy: true});
                    $(".col-footer-detalles-ticket").css("width", "20%");

                    $(".container-detalles-ticket").removeClass("col-lg-12");
                    $(".container-detalles-ticket").removeClass("col-md-12");
                    $(".container-detalles-ticket").addClass("col-lg-offset-1");
                    $(".container-detalles-ticket").addClass("col-md-offset-1");
                    $(".container-detalles-ticket").addClass("col-lg-10");
                    $(".container-detalles-ticket").addClass("col-md-10");
                } else {
                    $(".col-stts").addClass("no-padding");

                    $("#accion_ticket").val("1");
                    $(".col-tickets").animate({width: '55%'}, 500);
                    $(".btn-show-preview").prop("title", "Ocultar vista previa");
                    $(".icon-svp").addClass("fa-toggle-on");
                    $(".icon-svp").removeClass("fa-toggle-off");
                    setTimeout(function () {
                        $(".col-preview").addClass("in");
                    }, 500);
                    $(".scroll-list-tick").slimScroll({height: "100%"});
                    $(".col-footer-detalles-ticket").css("width", "27%");

                    $(".container-detalles-ticket").removeClass("col-lg-10");
                    $(".container-detalles-ticket").removeClass("col-md-10");
                    $(".container-detalles-ticket").removeClass("col-lg-offset-1");
                    $(".container-detalles-ticket").removeClass("col-md-offset-1");
                    $(".container-detalles-ticket").addClass("col-lg-12");
                    $(".container-detalles-ticket").addClass("col-md-12");
                }
            }

            function ordenar_lista() {
                if ($("#orden_lista").val() === "desc") {
                    $("#orden_lista").val("asc");
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes-alt");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes");
                    $("#btn_orden_lista").prop("title", "Cambiar a descendente");
                } else {
                    $("#orden_lista").val("desc");
                    $(".icono-orden-lista").removeClass("glyphicon-sort-by-attributes");
                    $(".icono-orden-lista").addClass("glyphicon-sort-by-attributes-alt");
                    $("#btn_orden_lista").prop("title", "Cambiar a ascendente");
                }
                aplica_filtros("inicio");
            }

            function cargar_vista() {
                show_loader();
                $.ajax({
                    url: "resources/tablas/tbl-tickets.php",
                    data: $("#search-filter").serialize() + "&" + $("#frm-ordenar").serialize(),
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        $("#panel-show-tbl-tickets").html(data);
                        $("#tot-tick").html("- " + $("#num_tickets").val() + " reportes");
                    },
                    complete: function (jqXHR, textStatus) {
                        hide_loader();
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
                        $("#tot-tick").html("- " + $("#num_tickets").val() + " reportes");
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

            function vista_ticket_preview(ticket, elemento) {
                show_loader();
                $(".panel-detalles-ticket-lista").css("border", "none");
                $(".panel-detalles-ticket-lista").css("border-top", "1px #B8B7B9 solid"); 
                $(".panel-detalles-ticket-lista").css("border-bottom", "1px #B8B7B9 solid"); 
                $.ajax({
                    url: "resources/tablas/tbl-preview.php",
                    data: "ticket=" + ticket,
                    type: 'POST',
                    success: function (data, textStatus, jqXHR) {
                        $("#preview_ticket").html(data);
                        hide_loader();
                        $(elemento).css("border", "3px #B8B7B9 solid");
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus);
                        hide_loader();
                    }
                });
            }

            function accion_ticket(id, objeto) {
                if ($("#accion_ticket").val() === "1") {
                    vista_ticket_preview(id, objeto)
                } else if ($("#accion_ticket").val() === "2") {
                    vista_ticket(id);
                }
            }

            function vista_ticket(ticket) {
                show_loader();
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
    <body class="skin-blue sidebar-mini wysihtml5-supported fixed sidebar-collapse" onload="aplica_filtros('inicio');">

        <?php include 'utils/funciones.php'; ?>
        <?php include 'resources/components/notificaciones.php'; ?>
        <?php include 'resources/components/header-2.php'; ?>
        <?php include './resources/components/navbar.php'; ?>

        <div class="content-wrapper" style="margin-top: 0;">

            <div class="col-lg-12 no-padding" style="/*background-color: #d1c7be;*/background-color: #FFF;">
                <?php include './resources/components/sub-navbar.php'; ?>
            </div>

            <div class="col-lg-10 col-xs-12 col-md-10 col-sm-12 container-detalles-ticket col-lg-offset-1 col-md-offset-1" style="margin-bottom: 70px; margin-top: 10px;">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                        
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 no-padding" style="margin-bottom: 5px;">
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
                                              //  echo "Asignadas a mi grupo";
                                                break;
                                            case 5:
                                                echo "Resueltas por mi";
                                                break;
                                            case 6:
                                               // echo "Reportados por mi grupo";
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

                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7" style="margin-bottom: 5px; padding: 0 5px;">
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
                                    <option value="10" <?php
                                    if (isset($_SESSION['res_pag_tickets']) && $_SESSION['res_pag_tickets'] === "10") {
                                        echo "selected";
                                    } else if (isset($_COOKIE['res_pag_tickets']) && $_COOKIE['res_pag_tickets'] === "10") {
                                        echo "selected";
                                    }
                                    ?> >10</option>
                                    <option value="20" <?php
                                    if (isset($_SESSION['res_pag_tickets']) && $_SESSION['res_pag_tickets'] === "20") {
                                        echo "selected";
                                    } else if (isset($_COOKIE['res_pag_tickets']) && $_COOKIE['res_pag_tickets'] === "20") {
                                        echo "selected";
                                    }
                                    ?> >20</option>
                                    <option value="50" <?php
                                    if (isset($_SESSION['res_pag_tickets']) && $_SESSION['res_pag_tickets'] === "50") {
                                        echo "selected";
                                    } else if (isset($_COOKIE['res_pag_tickets']) && $_COOKIE['res_pag_tickets'] === "50") {
                                        echo "selected";
                                    }
                                    ?> >50</option>
                                    <option value="100" <?php
                                    if (isset($_SESSION['res_pag_tickets']) && $_SESSION['res_pag_tickets'] === "100") {
                                        echo "selected";
                                    } else if (isset($_COOKIE['res_pag_tickets']) && $_COOKIE['res_pag_tickets'] === "100") {
                                        echo "selected";
                                    }
                                    ?> >100</option>
                                    <option value="200" <?php
                                    if (isset($_SESSION['res_pag_tickets']) && $_SESSION['res_pag_tickets'] === "200") {
                                        echo "selected";
                                    } else if (isset($_COOKIE['res_pag_tickets']) && $_COOKIE['res_pag_tickets'] === "200") {
                                        echo "selected";
                                    }
                                    ?>>200</option>
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
                    </div>
                    <div class="panel-heading panel-filtros no-padding filter-pnl-collapsable" style="text-align: end;">
                        <?php include './resources/components/filtros.php'; ?>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding" style="margin-top: 5px; margin-bottom: 15px;">
                    <button class="btn btn-link btn-show-preview" onclick="collapse_preview();" title="Ocultar vista previa" style="padding: 5px; font-size: 16px;">
                        <span class="icon-svp fa fa-toggle-on"></span>
                    </button>

                    <input type="hidden" name="switch" id="switch" value="
                    <?php
                    if (isset($_COOKIE['switch']) && $_COOKIE['switch'] != "") {
                        echo trim($_COOKIE['switch']);
                    } else {
                        echo "on";
                    }
                    ?> ">
                    <input type="hidden" name="accion_ticket" id="accion_ticket" value="1">

                    <div class="no-padding col-tickets" style="width: 55%;border-top: 1.5pt solid #4b4e53;">
                        <div id="panel-tickets-list-p" class="scroll-list-tick" style="border-radius: 0; height: 100%;">
                            <div class="panel-body panel-tickets-list no-padding" id="panel-show-tbl-tickets" style="height: 80%;">


                            </div>
                        </div>
                    </div>

                    <div class=" no-padding collapse in col-preview">
                        <div  id="panel_preview" style="overflow-x: hidden; height: 100%; border-top: 1.5pt solid #4b4e43;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding" id="preview_ticket" style="height: 100%;">

                                <label style="margin-top: 50px; position: absolute; transform: translate(50%, 50%); right: 50%;">No hay nada que mostrar</label>

                            </div>
                        </div>
                    </div>
                </div>



            </div>

        </div>

        <?php include 'resources/components/footer.php'; ?>

    </body>
</html>
